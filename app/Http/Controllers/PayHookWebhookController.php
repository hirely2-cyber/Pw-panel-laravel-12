<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\ReferralPartner;
use App\Services\PayHookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayHookWebhookController extends Controller
{
    public function __construct(private PayHookService $payHook) {}

    /**
     * Handle incoming PayHook payment notification.
     *
     * Security: HMAC-SHA256 signature is verified before processing.
     * Only status=PAID triggers gold credit — no false positives.
     */
    public function handle(Request $request): JsonResponse
    {
        $rawBody   = $request->getContent();
        $signature = $request->header('X-Webhook-Signature', '');

        // 1. Verify HMAC signature
        if (! $this->payHook->verifyWebhookSignature($rawBody, $signature)) {
            Log::warning('PayHook webhook: signature verification failed.', [
                'ip' => $request->ip(),
            ]);
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $payload = json_decode($rawBody, true);

        // PayHook webhook payload structure:
        // { "event": "payment.status.updated", "invoice": { "invoice_number": "...", "status": "paid" }, "transaction": { "paid_at": "..." } }
        $invoiceNumber = $payload['invoice']['invoice_number'] ?? null;

        if (! is_array($payload) || empty($invoiceNumber)) {
            return response()->json(['message' => 'Bad payload'], 400);
        }

        $status = $payload['invoice']['status'] ?? '';  // 'paid', 'pending', 'expired'

        // 2. Find invoice in our DB — match by payhook_invoice_number first, fallback to invoice_number
        $invoice = Invoice::where('payhook_invoice_number', $invoiceNumber)
            ->orWhere('invoice_number', $invoiceNumber)
            ->first();

        if (! $invoice) {
            Log::warning('PayHook webhook: invoice not found.', ['invoice' => $invoiceNumber]);
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        // 3. Idempotency: already processed
        if ($invoice->status === Invoice::STATUS_PAID) {
            return response()->json(['message' => 'Already processed'], 200);
        }

        // 4. Process based on status
        if ($status === 'paid') {
            DB::transaction(function () use ($invoice, $payload) {

                if ($invoice->type === 'cubi') {
                    // ── Cubi Invoice: deliver via usecashnow queue ──
                    // SN counts DOWN per user (0, -1, -2, ...). No inner try/catch:
                    // exceptions propagate → outer transaction rolls back → invoice stays
                    // PENDING → PayHook retries → no lost Cubi.
                    $cashValue = $invoice->cubi_amount * 100;

                    $nextSn = (DB::connection('mysql_game')
                        ->table('usecashnow')
                        ->where('userid', $invoice->user_id)
                        ->where('zoneid', 1)
                        ->min('sn') ?? 0) - 1;

                    DB::connection('mysql_game')->table('usecashnow')->insert([
                        'userid'   => $invoice->user_id,
                        'zoneid'   => 1,
                        'sn'       => $nextSn,
                        'aid'      => 1,
                        'point'    => 0,
                        'cash'     => $cashValue,
                        'status'   => 0,
                        'creatime' => now(),
                    ]);
                    // Note: usecashlog is written by the billing daemon after delivery.

                    // ── Partner commission (non-fatal: failure logs but won't block buyer delivery) ──
                    if ($invoice->partner_user_id && $invoice->commission_amount > 0) {
                        try {
                            $partner = ReferralPartner::where('user_id', $invoice->partner_user_id)->first();
                            $commissionType = $partner->reward_type ?? 'gold';

                            if ($commissionType === 'cubi') {
                                $cubiRate = config('pw-config.currency.cubi_rate_idr', 1000);
                                $commissionCubi = max(1, (int) floor($invoice->commission_amount / $cubiRate));
                                $commCashValue  = $commissionCubi * 100;

                                $partnerNextSn = (DB::connection('mysql_game')
                                    ->table('usecashnow')
                                    ->where('userid', $invoice->partner_user_id)
                                    ->where('zoneid', 1)
                                    ->min('sn') ?? 0) - 1;

                                DB::connection('mysql_game')->table('usecashnow')->insert([
                                    'userid'   => $invoice->partner_user_id,
                                    'zoneid'   => 1,
                                    'sn'       => $partnerNextSn,
                                    'aid'      => 1,
                                    'point'    => 0,
                                    'cash'     => $commCashValue,
                                    'status'   => 0,
                                    'creatime' => now(),
                                ]);

                                Log::info('Partner commission credited (Cubi Gold).', [
                                    'partner_id'      => $invoice->partner_user_id,
                                    'commission_idr'  => $invoice->commission_amount,
                                    'commission_cubi' => $commissionCubi,
                                ]);

                            } elseif ($commissionType === 'tunai') {
                                Log::info('Partner commission recorded (Tunai/Rupiah).', [
                                    'partner_id'     => $invoice->partner_user_id,
                                    'commission_idr' => $invoice->commission_amount,
                                ]);

                            } else {
                                $rate = config('pw-config.currency.rate_idr', 10000);
                                $commissionGold = max(1, (int) floor($invoice->commission_amount / $rate));

                                DB::table('users')
                                    ->where('ID', $invoice->partner_user_id)
                                    ->increment('money', $commissionGold);

                                Log::info('Partner commission credited (Gold Points).', [
                                    'partner_id'      => $invoice->partner_user_id,
                                    'commission_idr'  => $invoice->commission_amount,
                                    'commission_gold' => $commissionGold,
                                ]);
                            }

                            $invoice->commission_credited = true;
                        } catch (\Throwable $e) {
                            Log::error('Partner commission delivery failed.', [
                                'partner_id' => $invoice->partner_user_id,
                                'invoice'    => $invoice->invoice_number,
                                'error'      => $e->getMessage(),
                            ]);
                        }
                    }
                } else {
                    // ── Gold Invoice ──
                    DB::table('users')->where('ID', $invoice->user_id)->increment('money', $invoice->gold_amount);
                }

                // Update invoice status
                $invoice->update([
                    'status'  => Invoice::STATUS_PAID,
                    'paid_at' => $payload['transaction']['paid_at'] ?? now(),
                    'meta'    => $payload,
                ]);
            });

            Log::info('PayHook webhook: payment confirmed.', [
                'invoice' => $invoiceNumber,
                'type'    => $invoice->type,
                'gold'    => $invoice->gold_amount,
                'cubi'    => $invoice->cubi_amount,
                'user_id' => $invoice->user_id,
            ]);

        } elseif ($status === 'expired' || $status === 'failed') {
            $invoice->update(['status' => Invoice::STATUS_FAILED]);

            Log::info('PayHook webhook: payment failed/expired.', [
                'invoice' => $invoiceNumber,
                'status'  => $status,
            ]);
        }

        return response()->json(['message' => 'OK'], 200);
    }
}
