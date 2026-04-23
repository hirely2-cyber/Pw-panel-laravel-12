<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\GameApiService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sync ranking dari game DB setiap 10 menit
Schedule::command('pw:sync-ranking')->everyTenMinutes();

// Sync event progress setiap 3 menit
Schedule::command('pw:sync-event')->everyThreeMinutes();

// Auto-distribute event rewards ke user yang sudah memenuhi syarat level (setiap 5 menit)
// Register reward (100 Cubi) → char level >= register_req_level
// Referral milestone        → referred user char level >= referral_req_level
Schedule::command('pw:auto-distribute-event')->everyFiveMinutes();

// Update Top Sultan setiap hari jam 03:00
Schedule::command('pw:update-sultan')->dailyAt('03:00');

// Safe stop with in-game countdown broadcast
Artisan::command('pw:safe-stop {--delay=300} {--server=/home/pw_server155}', function () {
    $delay = max(0, (int) $this->option('delay'));
    $serverRoot = rtrim((string) $this->option('server'), '/');

    $fmtRemain = function (int $sec): string {
        if ($sec >= 60) {
            $m = intdiv($sec, 60);
            $s = $sec % 60;
            return $s > 0 ? "{$m}m {$s}s" : "{$m}m";
        }
        return "{$sec}s";
    };

    // Countdown checkpoints (seconds remaining)
    $checkpoints = [300, 180, 120, 60, 30, 10, 5, 4, 3, 2, 1];
    $checkpoints = array_values(array_filter($checkpoints, fn ($s) => $s > 0 && $s < $delay));
    rsort($checkpoints);

    $this->info("Safe stop started with delay {$delay}s");
    GameApiService::worldChat("[PW Notice] Stop Map Delay aktif. Semua map akan dihentikan dalam " . $fmtRemain($delay) . ".", 9, 0);

    $startedAt = time();
    foreach ($checkpoints as $remain) {
        $targetElapsed = $delay - $remain;
        $sleepFor = $targetElapsed - (time() - $startedAt);
        if ($sleepFor > 0) {
            sleep($sleepFor);
        }

        GameApiService::worldChat("[PW Notice] Safe Stop: " . $fmtRemain($remain) . " lagi. Segera logout ke tempat aman.", 9, 0);
    }

    $finalSleep = $delay - (time() - $startedAt);
    if ($finalSleep > 0) {
        sleep($finalSleep);
    }

    $serverScript = $serverRoot . '/server';
    $mapLines = [];
    exec("ps -A w | grep './gs ' | grep -v grep", $mapLines);

    $mapIds = [];
    foreach ($mapLines as $line) {
        if (preg_match('/\.\/gs\s+([A-Za-z0-9_]+)/', $line, $m)) {
            $mapIds[$m[1]] = true;
        }
    }
    $mapIds = array_keys($mapIds);

    $stopped = 0;
    $failed = 0;

    foreach ($mapIds as $mapId) {
        $cmd = sprintf('sudo %s stop-map %s', escapeshellarg($serverScript), escapeshellarg($mapId));
        exec($cmd, $out, $rc);
        if ($rc === 0) {
            $stopped++;
        } else {
            $failed++;
        }
        usleep(200000);
    }

    // Fallback: force kill leftovers if any map process still running
    $forceCmd = 'sudo /usr/bin/pkill -f ' . escapeshellarg('./gs ');
    exec($forceCmd, $fOut, $fRc);

    if ($stopped > 0 && $failed === 0) {
        $msg = "[PW Notice] Safe Stop selesai. {$stopped} map dihentikan dengan aman.";
    } elseif ($stopped > 0 || $failed > 0) {
        $msg = "[PW Notice] Safe Stop selesai. {$stopped} map berhenti, {$failed} map dipaksa stop (fallback).";
    } elseif ($fRc === 0) {
        $msg = '[PW Notice] Safe Stop selesai. Map tersisa dihentikan via fallback.';
    } else {
        $msg = '[PW Notice] Safe Stop selesai. Tidak ada map aktif atau proses stop sudah dijalankan.';
    }

    GameApiService::worldChat($msg, 9, 0);

    $this->info("Safe stop finished. stopped={$stopped}, failed={$failed}, fallback_rc={$fRc}");
})->purpose('Broadcast in-game safe-stop countdown and stop all gs map processes');
