<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventBonusController extends Controller
{
    // User dianggap "online" jika aktif di panel dalam 30 menit terakhir
    private const ONLINE_THRESHOLD_SECONDS = 1800;

    /**
     * Form konfigurasi bonus event.
     */
    public function index(Request $request)
    {
        $history = DB::table('pw_event_bonuses')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $baseCubi           = max(0, (int) $request->input('base_cubi', 100));
        $referralCubiPerRef = max(0, (int) $request->input('referral_cubi_per_ref', 0));
        $referralMaxBonus   = max(0, (int) $request->input('referral_max_bonus', 0));
        $onlineOnly         = (bool) $request->input('online_only', false);

        $onlineUserIds = $this->getOnlineUserIds();
        $preview       = $this->buildPreview($baseCubi, $referralCubiPerRef, $referralMaxBonus, $onlineOnly ? $onlineUserIds : null);

        return view('admin.event-bonus', compact(
            'history',
            'preview',
            'baseCubi',
            'referralCubiPerRef',
            'referralMaxBonus',
            'onlineOnly',
            'onlineUserIds'
        ));
    }

    /**
     * Distribute bonus Cubi ke semua user (atau hanya yang online) dengan source "Event".
     * Masuk ke usecashnow (point=4) → otomatis ke usecashlog saat game proses.
     * Dicatat di pw_event_deliveries agar Cubi Monitor mengenalinya sebagai "Event".
     */
    public function distribute(Request $request)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'nullable|string|max:1000',
            'base_cubi'           => 'required|integer|min:1|max:100000',
            'referral_cubi_per_ref' => 'nullable|integer|min:0|max:100000',
            'referral_max_bonus'  => 'nullable|integer|min:0|max:1000000',
            'online_only'         => 'nullable|boolean',
        ]);

        $baseCubi           = (int) $validated['base_cubi'];
        $referralCubiPerRef = (int) ($validated['referral_cubi_per_ref'] ?? 0);
        $referralMaxBonus   = (int) ($validated['referral_max_bonus'] ?? 0);
        $onlineOnly         = (bool) ($validated['online_only'] ?? false);

        $onlineUserIds = $onlineOnly ? $this->getOnlineUserIds() : null;

        // Buat entry bonus event
        $bonusEventId = DB::table('pw_event_bonuses')->insertGetId([
            'title'                   => $validated['title'],
            'description'             => ($validated['description'] ?? null)
                                         . ($onlineOnly ? ' [online only]' : ''),
            'base_cubi'               => $baseCubi,
            'referral_cubi_per_ref'   => $referralCubiPerRef,
            'referral_max_bonus'      => $referralMaxBonus,
            'status'                  => 'draft',
            'distributed_by'          => Auth::id(),
            'created_at'              => now(),
            'updated_at'              => now(),
        ]);

        // Ambil user yang akan menerima
        $query = DB::table('users')->select('ID', 'name', 'referred_by');
        if ($onlineOnly && !empty($onlineUserIds)) {
            $query->whereIn('ID', $onlineUserIds);
        }
        $users = $query->get();

        // Hitung referral count per user
        $referralCounts = [];
        if ($referralCubiPerRef > 0) {
            $referralCounts = DB::table('users')
                ->whereNotNull('referred_by')
                ->select('referred_by', DB::raw('COUNT(*) as total'))
                ->groupBy('referred_by')
                ->pluck('total', 'referred_by')
                ->toArray();
        }

        // Buat entry event virtual di pw_events untuk referensi pw_event_deliveries
        $eventRowId = DB::table('pw_events')->insertGetId([
            'type'               => 'grand_launch',
            'title'              => $validated['title'],
            'description'        => $validated['description'] ?? null,
            'req_level'          => 1,
            'req_cultivation'    => 0,
            'prize_total_cubi'   => 0,
            'prize_winner_count' => 0,
            'status'             => 'distributed',
            'start_at'           => now(),
            'end_at'             => now(),
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        $totalRecipients = 0;
        $totalCubi       = 0;
        $errors          = [];
        $now             = now();

        foreach ($users as $user) {
            $userId = (int) $user->ID;

            $referralBonus = 0;
            if ($referralCubiPerRef > 0 && isset($referralCounts[$userId])) {
                $referralBonus = $referralCubiPerRef * (int) $referralCounts[$userId];
                if ($referralMaxBonus > 0) {
                    $referralBonus = min($referralBonus, $referralMaxBonus);
                }
            }

            $userTotalCubi = $baseCubi + $referralBonus;
            $cashValue     = $userTotalCubi * 100;

            try {
                DB::connection('mysql_game')->transaction(function () use ($userId, $cashValue, $userTotalCubi, $eventRowId, $now) {
                    $nextSn = ((int)(DB::connection('mysql_game')
                        ->table('usecashnow')
                        ->where('userid', $userId)
                        ->where('zoneid', 1)
                        ->min('sn') ?? 0)) - 1;

                    DB::connection('mysql_game')->table('usecashnow')->insert([
                        'userid'   => $userId,
                        'zoneid'   => 1,
                        'sn'       => $nextSn,
                        'aid'      => 1,
                        'point'    => 4,
                        'cash'     => $cashValue,
                        'status'   => 0,
                        'creatime' => $now,
                    ]);

                    DB::table('pw_event_deliveries')->insert([
                        'event_id'   => $eventRowId,
                        'user_id'    => $userId,
                        'rank'       => 0,
                        'amount'     => $userTotalCubi,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                });

                $totalRecipients++;
                $totalCubi += $userTotalCubi;

            } catch (\Throwable $e) {
                Log::error('EventBonus distribute error', [
                    'user_id' => $userId,
                    'error'   => $e->getMessage(),
                ]);
                $errors[] = "User #{$userId} ({$user->name}): {$e->getMessage()}";
            }
        }

        DB::table('pw_event_bonuses')->where('id', $bonusEventId)->update([
            'total_recipients'        => $totalRecipients,
            'total_cubi_distributed'  => $totalCubi,
            'status'                  => 'distributed',
            'distributed_at'          => now(),
            'updated_at'              => now(),
        ]);

        Log::info('EventBonus distributed', [
            'bonus_event_id'   => $bonusEventId,
            'pw_event_id'      => $eventRowId,
            'online_only'      => $onlineOnly,
            'total_recipients' => $totalRecipients,
            'total_cubi'       => $totalCubi,
            'errors'           => count($errors),
        ]);

        if (!empty($errors)) {
            $errMsg = implode('; ', array_slice($errors, 0, 5));
            return redirect()->route('admin.event-bonus.index')
                ->with('warning', "Distribusi selesai ke {$totalRecipients} user ({$totalCubi} Cubi total). Ada " . count($errors) . " error: {$errMsg}");
        }

        $target = $onlineOnly ? 'user yang sedang online' : 'semua user';
        return redirect()->route('admin.event-bonus.index')
            ->with('success', "Bonus Event berhasil dikirim ke {$totalRecipients} {$target}. Total: " . number_format($totalCubi) . " Cubi Gold (sumber: Event).");
    }

    /**
     * Ambil ID user yang sedang aktif (online di panel dalam 30 menit terakhir).
     */
    private function getOnlineUserIds(): array
    {
        return DB::table('pw_sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->timestamp - self::ONLINE_THRESHOLD_SECONDS)
            ->pluck('user_id')
            ->unique()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->toArray();
    }

    /**
     * Bangun data preview distribusi.
     * $filterUserIds = null → semua user; array ID → hanya user tersebut.
     */
    private function buildPreview(int $baseCubi, int $referralCubiPerRef, int $referralMaxBonus, ?array $filterUserIds = null): array
    {
        $query = DB::table('users')->select('ID', 'name', 'referred_by')->orderBy('ID');
        if ($filterUserIds !== null) {
            $query->whereIn('ID', $filterUserIds);
        }
        $users = $query->get();

        $referralCounts = [];
        if ($referralCubiPerRef > 0) {
            $referralCounts = DB::table('users')
                ->whereNotNull('referred_by')
                ->select('referred_by', DB::raw('COUNT(*) as total'))
                ->groupBy('referred_by')
                ->pluck('total', 'referred_by')
                ->toArray();
        }

        $rows      = [];
        $totalCubi = 0;

        foreach ($users as $user) {
            $userId = (int) $user->ID;

            $refCount      = $referralCounts[$userId] ?? 0;
            $referralBonus = $referralCubiPerRef > 0
                ? ($referralMaxBonus > 0
                    ? min($referralCubiPerRef * $refCount, $referralMaxBonus)
                    : $referralCubiPerRef * $refCount)
                : 0;

            $userTotal = $baseCubi + $referralBonus;
            $totalCubi += $userTotal;

            $rows[] = [
                'id'        => $userId,
                'name'      => $user->name,
                'base'      => $baseCubi,
                'ref_count' => $refCount,
                'ref_bonus' => $referralBonus,
                'total'     => $userTotal,
            ];
        }

        return [
            'rows'       => $rows,
            'total_cubi' => $totalCubi,
            'count'      => count($rows),
        ];
    }
}
