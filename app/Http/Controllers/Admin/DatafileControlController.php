<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatafileUpdateLog;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DatafileControlController extends Controller
{
    private const ALLOWED_FILES = [
        'elements.data',
        'tasks.data',
        'dyn_tasks.data',
        'aipolicy.data',
        'gshopsev.data',
        'gshopsev1.data',
        'gshopsev2.data',
    ];

    private const DEFAULT_SCRIPT = '/home/pw_server155/tools/replace_datafile.sh';
    private const DEFAULT_NPCGEN_SCRIPT = '/home/pw_server155/tools/replace_npcgen.sh';

    private function runCommand(string $cmd): array
    {
        if (function_exists('exec')) {
            $output = [];
            $exitCode = 0;
            exec($cmd, $output, $exitCode);

            return [
                'output' => trim(implode("\n", $output)),
                'exit' => (int) $exitCode,
            ];
        }

        if (function_exists('shell_exec')) {
            $marker = '__PW_RC__';
            $wrapped = "bash -lc " . escapeshellarg($cmd . '; printf "\\n' . $marker . '%s" "$?"');
            $raw = @shell_exec($wrapped . ' 2>&1');

            if (! is_string($raw) || $raw === '') {
                return ['output' => 'Command returned no output.', 'exit' => 127];
            }

            $pos = strrpos($raw, $marker);
            if ($pos === false) {
                return ['output' => trim($raw), 'exit' => 0];
            }

            $output = trim(substr($raw, 0, $pos));
            $codeRaw = trim(substr($raw, $pos + strlen($marker)));
            $exit = is_numeric($codeRaw) ? (int) $codeRaw : 1;

            return ['output' => $output, 'exit' => $exit];
        }

        return ['output' => 'Both exec() and shell_exec() are disabled on this server.', 'exit' => 127];
    }

    private function serverPath(): string
    {
        return (string) (Setting::get('server_path') ?: '/home/pw_server155');
    }

    private function datafilePath(): string
    {
        $stored = (string) (Setting::get('datafile_path') ?: '');
        if ($stored !== '') {
            return rtrim($stored, '/');
        }

        return rtrim($this->serverPath(), '/') . '/gamed/config/DATAFILE';
    }

    private function replaceScriptPath(): string
    {
        $stored = (string) (Setting::get('datafile_replace_script') ?: '');
        return $stored !== '' ? $stored : self::DEFAULT_SCRIPT;
    }

    private function worldPath(): string
    {
        return rtrim($this->serverPath(), '/') . '/gamed/config/world';
    }

    private function npcgenScriptPath(): string
    {
        $stored = (string) (Setting::get('npcgen_replace_script') ?: '');
        return $stored !== '' ? $stored : self::DEFAULT_NPCGEN_SCRIPT;
    }

    public function adminIndex(): View
    {
        return view('admin.datafile-control', [
            'datafilePath' => $this->datafilePath(),
            'replaceScript' => $this->replaceScriptPath(),
            'allowedFiles' => self::ALLOWED_FILES,
            'logs' => DatafileUpdateLog::query()->latest()->limit(50)->get(),
            'canEditPath' => auth()->user()?->isWebAdmin() === true,
            'worldPath' => $this->worldPath(),
            'npcgenScript' => $this->npcgenScriptPath(),
            'npcgenLogs' => DatafileUpdateLog::query()->where('target_file', 'npcgen.data')->latest()->limit(30)->get(),
        ]);
    }

    public function gmIndex(): View
    {
        return view('gm.datafile-control', [
            'datafilePath' => $this->datafilePath(),
            'replaceScript' => $this->replaceScriptPath(),
            'allowedFiles' => self::ALLOWED_FILES,
            'logs' => DatafileUpdateLog::query()->latest()->limit(30)->get(),
            'worldPath' => $this->worldPath(),
            'npcgenScript' => $this->npcgenScriptPath(),
            'npcgenLogs' => DatafileUpdateLog::query()->where('target_file', 'npcgen.data')->latest()->limit(20)->get(),
        ]);
    }

    public function savePath(Request $request): RedirectResponse
    {
        $request->validate([
            'datafile_path' => ['required', 'string', 'regex:#^/[a-zA-Z0-9/_\-\.]+/?$#'],
        ]);

        Setting::set('datafile_path', rtrim((string) $request->input('datafile_path'), '/'), 'server');

        return back()->with('success', 'Path DATAFILE berhasil disimpan.');
    }

    public function upload(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'target_file' => 'required|in:' . implode(',', self::ALLOWED_FILES),
            'datafile' => 'required|file|max:102400',
        ]);

        $targetFile = (string) $request->input('target_file');
        $upload = $request->file('datafile');
        $tempPath = $upload?->getRealPath() ?: '';
        $datafilePath = $this->datafilePath();
        $scriptPath = $this->replaceScriptPath();

        if (! is_dir($datafilePath)) {
            $msg = 'Path DATAFILE tidak ditemukan: ' . $datafilePath;
            return $this->uploadError($request, $msg);
        }

        if (! is_file($scriptPath)) {
            $msg = 'Script replace tidak ditemukan: ' . $scriptPath;
            return $this->uploadError($request, $msg);
        }

        if ($tempPath === '' || ! is_file($tempPath)) {
            return $this->uploadError($request, 'File upload tidak valid.');
        }

        $actor = (string) (auth()->user()?->name ?: 'unknown');
        $panelArea = request()->routeIs('gm.*') ? 'gm' : 'admin';

        $cmd = sprintf(
            'sudo -n %s %s %s %s %s 2>&1',
            escapeshellarg($scriptPath),
            escapeshellarg($tempPath),
            escapeshellarg($targetFile),
            escapeshellarg($actor),
            escapeshellarg($datafilePath)
        );

        $result = $this->runCommand($cmd);
        $textOutput = trim((string) ($result['output'] ?? ''));
        $exitCode = (int) ($result['exit'] ?? 1);
        $status = $exitCode === 0 ? 'success' : 'failed';

        DatafileUpdateLog::create([
            'user_id' => auth()->id(),
            'actor_name' => $actor,
            'actor_role' => (string) (auth()->user()?->role ?: 'unknown'),
            'panel_area' => $panelArea,
            'target_file' => $targetFile,
            'original_name' => $upload?->getClientOriginalName(),
            'file_size' => (int) ($upload?->getSize() ?: 0),
            'script_output' => $textOutput,
            'status' => $status,
        ]);

        if ($exitCode !== 0) {
            $msg = 'Gagal';
            return $this->uploadError($request, $msg);
        }

        $okMessage = 'Sukses';
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => $okMessage,
            ]);
        }

        return back()->with('success', $okMessage);
    }

    public function uploadNpcgen(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'npcgen_file' => 'required|file|max:102400',
        ]);

        $upload = $request->file('npcgen_file');
        $tempPath = $upload?->getRealPath() ?: '';
        $worldPath = $this->worldPath();
        $scriptPath = $this->npcgenScriptPath();

        if (! is_dir($worldPath)) {
            $msg = 'World directory tidak ditemukan: ' . $worldPath;
            return $this->uploadError($request, $msg);
        }

        if (! is_file($scriptPath)) {
            $msg = 'Script replace tidak ditemukan: ' . $scriptPath;
            return $this->uploadError($request, $msg);
        }

        if ($tempPath === '' || ! is_file($tempPath)) {
            return $this->uploadError($request, 'File upload tidak valid.');
        }

        $actor = (string) (auth()->user()?->name ?: 'unknown');
        $panelArea = request()->routeIs('gm.*') ? 'gm' : 'admin';

        $cmd = sprintf(
            'sudo -n %s %s %s %s 2>&1',
            escapeshellarg($scriptPath),
            escapeshellarg($tempPath),
            escapeshellarg($actor),
            escapeshellarg($worldPath)
        );

        $result = $this->runCommand($cmd);
        $textOutput = trim((string) ($result['output'] ?? ''));
        $exitCode = (int) ($result['exit'] ?? 1);
        $status = $exitCode === 0 ? 'success' : 'failed';

        DatafileUpdateLog::create([
            'user_id' => auth()->id(),
            'actor_name' => $actor,
            'actor_role' => (string) (auth()->user()?->role ?: 'unknown'),
            'panel_area' => $panelArea,
            'target_file' => 'npcgen.data',
            'original_name' => $upload?->getClientOriginalName(),
            'file_size' => (int) ($upload?->getSize() ?: 0),
            'script_output' => $textOutput,
            'status' => $status,
        ]);

        if ($exitCode !== 0) {
            $msg = 'Gagal';
            return $this->uploadError($request, $msg);
        }

        $okMessage = 'Sukses';
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => $okMessage,
            ]);
        }

        return back()->with('success', $okMessage);
    }

    private function uploadError(Request $request, string $message): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => false,
                'message' => $message,
            ], 422);
        }

        return back()->with('error', $message);
    }
}
