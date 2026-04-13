<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class BackupMonitorController extends Controller
{
    private function serverPath(): string
    {
        $path = (string) (Setting::get('server_path') ?: '/home/pw_server155');
        return rtrim($path, '/');
    }

    private function backupFilePath(string $filename): ?string
    {
        if (! preg_match('/^pw_backup_[0-9]{4}-[0-9]{2}-[0-9]{2}_[0-9]{2}-[0-9]{2}-[0-9]{2}\.tar\.gz$/', $filename)) {
            return null;
        }

        $full = $this->serverPath() . '/' . $filename;
        if (! is_file($full)) {
            return null;
        }

        return $full;
    }

    public function index(): View
    {
        $serverPath = $this->serverPath();
        $files = collect(File::glob($serverPath . '/pw_backup_*.tar.gz') ?: [])
            ->map(function (string $path) {
                return [
                    'name' => basename($path),
                    'size' => (int) @filesize($path),
                    'mtime' => (int) @filemtime($path),
                ];
            })
            ->sortByDesc('mtime')
            ->values();

        return view('admin.backup-monitor', compact('files', 'serverPath'));
    }

    public function download(Request $request): Response|RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'string', 'max:255'],
        ]);

        $path = $this->backupFilePath((string) $request->input('file'));
        if (! $path) {
            return back()->with('error', 'File backup tidak valid atau tidak ditemukan.');
        }

        return response()->download($path, basename($path));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'string', 'max:255'],
        ]);

        $path = $this->backupFilePath((string) $request->input('file'));
        if (! $path) {
            return back()->with('error', 'File backup tidak valid atau tidak ditemukan.');
        }

        @unlink($path);

        return back()->with('success', 'File backup berhasil dihapus.');
    }
}
