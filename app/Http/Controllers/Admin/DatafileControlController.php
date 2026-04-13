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

    public function adminIndex(): View
    {
        return view('admin.datafile-control', [
            'datafilePath' => $this->datafilePath(),
            'replaceScript' => $this->replaceScriptPath(),
            'allowedFiles' => self::ALLOWED_FILES,
            'logs' => DatafileUpdateLog::query()->latest()->limit(50)->get(),
            'canEditPath' => auth()->user()?->isWebAdmin() === true,
        ]);
    }

    public function gmIndex(): View
    {
        return view('gm.datafile-control', [
            'datafilePath' => $this->datafilePath(),
            'replaceScript' => $this->replaceScriptPath(),
            'allowedFiles' => self::ALLOWED_FILES,
            'logs' => DatafileUpdateLog::query()->latest()->limit(30)->get(),
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
            'sudo %s %s %s %s %s 2>&1',
            escapeshellarg($scriptPath),
            escapeshellarg($tempPath),
            escapeshellarg($targetFile),
            escapeshellarg($actor),
            escapeshellarg($datafilePath)
        );

        $output = [];
        $exitCode = 0;
        exec($cmd, $output, $exitCode);

        $textOutput = trim(implode("\n", $output));
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
            $msg = 'Replace gagal: ' . ($textOutput !== '' ? $textOutput : 'unknown error');
            return $this->uploadError($request, $msg);
        }

        $okMessage = 'DATAFILE berhasil diganti. Lakukan restart game server sesuai SOP update.';
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
