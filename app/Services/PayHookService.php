<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Services;

use App\Models\Invoice;
use App\Models\Setting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class PayHookService
{
    private Client $client;
    private string $baseUrl;
    private string $apiKey;
    private string $webhookSecret;

    public function __construct()
    {
        $this->baseUrl       = rtrim(Setting::get('payhook_url') ?: config('pw-config.payhook.url'), '/');
        $this->apiKey        = Setting::get('payhook_api_key') ?: config('pw-config.payhook.api_key');
        $this->webhookSecret = Setting::get('payhook_webhook_secret') ?: config('pw-config.payhook.webhook_secret');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 15,
            'headers'  => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ],
        ]);
    }

    /**
     * Create a new invoice in PayHook.
     *
     * @param  int    $userId        Game user ID (stored as external_id for tracing)
     * @param  int    $goldAmount    Gold/coins to credit after payment
     * @param  int    $amountIdr     Total IDR amount (including unique suffix)
     * @param  string $customerName  Display name for the invoice
     * @return array{
     *   invoice_number: string,
     *   qris_url: string|null,
     *   expired_at: string|null,
     *   channel_type: string|null
     * }|null
     */
    public function createInvoice(int $userId, int $goldAmount, int $amountIdr, string $customerName = 'User', string $channelType = ''): ?array
    {
        try {
            $json = [
                'amount'        => $amountIdr,
                'customer_name' => $customerName,
                'external_id'   => (string) $userId,
                'description'   => 'Top-up ' . $goldAmount . ' ' . config('pw-config.currency.name'),
            ];

            if ($channelType !== '') {
                $json['channel_type'] = $channelType;
            }

            $response = $this->client->post('/api/v1/invoices', ['json' => $json]);

            $body = json_decode($response->getBody()->getContents(), true);

            if (empty($body['success']) || empty($body['data'])) {
                Log::error('PayHook createInvoice: unexpected response', ['body' => $body]);
                return null;
            }

            $data        = $body['data'];
            $channelInfo = $data['channel'] ?? [];
            $instruction = $data['payment_instruction'] ?? [];
            $resolvedType = $channelInfo['type'] ?? $channelType;

            $qrisUrl = null;
            if ($resolvedType === 'qris') {
                $qrisUrl = $instruction['qris_svg'] ?? null;
            }

            return [
                'invoice_number'      => $data['invoice_number'],
                'pay_amount'          => $data['pay_amount'] ?? null,
                'unique_suffix'       => $data['unique_suffix'] ?? null,
                'qris_url'            => $qrisUrl,
                'expired_at'          => $data['expires_at'] ?? null,
                'channel_type'        => $resolvedType,
                'payment_instruction' => $instruction,
            ];
        } catch (RequestException $e) {
            Log::error('PayHook createInvoice failed', [
                'user_id' => $userId,
                'error'   => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get invoice detail/status from PayHook by invoice number.
     */
    public function getInvoice(string $invoiceNumber): ?array
    {
        try {
            $response = $this->client->get("/v1/invoices/{$invoiceNumber}");
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('PayHook getInvoice failed', [
                'invoice' => $invoiceNumber,
                'error'   => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Verify HMAC signature from PayHook webhook.
     *
     * PayHook sends: X-Webhook-Signature: sha256=<hmac>
     * We verify: hash_hmac('sha256', $rawBody, $webhookSecret)
     *
     * @param  string $rawBody   Raw request body (not decoded)
     * @param  string $signature Header value of X-Webhook-Signature
     */
    public function verifyWebhookSignature(string $rawBody, string $signature): bool
    {
        if (empty($this->webhookSecret)) {
            Log::warning('PayHook webhook secret not configured.');
            return false;
        }

        // PayHook sends raw hex HMAC without prefix.
        // Support both "sha256=<hash>" and plain "<hash>" formats.
        $clean    = str_starts_with($signature, 'sha256=') ? substr($signature, 7) : $signature;
        $expected = hash_hmac('sha256', $rawBody, $this->webhookSecret);

        return hash_equals($expected, $clean);
    }

    /**
     * Calculate the unique IDR amount for an invoice.
     *
     * A random suffix (1-999) is added to the base amount so that
     * QRIS payments can be matched by exact transfer amount.
     *
     * @param  int $baseAmount  Base IDR amount before suffix
     * @param  int $suffix      Unique suffix (1-999), stored in pw_invoices.unique_suffix
     * @return int
     */
    public static function calculateUniqueAmount(int $baseAmount, int $suffix): int
    {
        return $baseAmount + $suffix;
    }

    /**
     * Generate a random unique suffix for amount disambiguation.
     */
    public static function generateSuffix(): int
    {
        return random_int(1, 999);
    }
}
