<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    /** Keys that are file uploads */
    private const FILE_KEYS = ['site_logo', 'site_hero_bg', 'site_auth_bg', 'site_favicon', 'site_footer_logo', 'seo_og_image'];

    public function index(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('admin.settings.content');
    }

    public function content(): View
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.content', compact('settings'));
    }

    public function panel(): View
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.panel', compact('settings'));
    }

    /**
     * Save "Konten Website" settings (images, SEO, social, downloads).
     */
    public function updateContent(Request $request): RedirectResponse
    {
        $request->validate([
            'site_logo'               => 'nullable|image|max:2048',
            'site_footer_logo'        => 'nullable|image|max:2048',
            'site_hero_bg'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'site_auth_bg'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'site_favicon'            => 'nullable|file|mimes:ico,png,svg|max:512',
            'seo_og_image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'site_name'               => 'nullable|string|max:50',
            'site_tagline'            => 'nullable|string|max:50',
            'site_description'        => 'nullable|string|max:200',
            'seo_title'               => 'nullable|string|max:60',
            'seo_description'         => 'nullable|string|max:160',
            'seo_keywords'            => 'nullable|string|max:255',
            'seo_google_analytics'    => 'nullable|string|max:30|regex:/^G-[A-Z0-9]+$/',
            'seo_google_verification' => 'nullable|string|max:255',
            'social_whatsapp'         => 'nullable|string|max:30',
            'social_facebook'         => 'nullable|url:https|max:255',
            'social_discord'          => 'nullable|url|max:255',
            'download_url'            => 'nullable|url|max:255',
            'download_url_part'       => 'nullable|url|max:255',
            'download_url_patch'      => 'nullable|url|max:255',
        ]);

        // Handle file uploads
        foreach (self::FILE_KEYS as $key) {
            if ($request->hasFile($key)) {
                $old = Setting::get($key);
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $path = $request->file($key)->store("settings/{$key}", 'public');
                Setting::set($key, $path, 'site');
            } elseif ($request->input("remove_{$key}")) {
                $old = Setting::get($key);
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                Setting::set($key, null, 'site');
            }
        }

        // Handle text settings (content page only)
        $textGroups = [
            'site_name'               => 'site',
            'site_tagline'            => 'site',
            'site_description'        => 'site',
            'social_whatsapp'         => 'social',
            'social_facebook'         => 'social',
            'social_discord'          => 'social',
            'download_url'            => 'social',
            'download_url_part'       => 'social',
            'download_url_patch'      => 'social',
            'seo_title'               => 'seo',
            'seo_description'         => 'seo',
            'seo_keywords'            => 'seo',
            'seo_google_analytics'    => 'seo',
            'seo_google_verification' => 'seo',
        ];
        foreach ($textGroups as $key => $group) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key) ?: null, $group);
            }
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Save "Konfigurasi Panel" settings (server info, payment, features).
     */
    public function updatePanel(Request $request): RedirectResponse
    {
        $request->validate([
            'server_version'         => 'nullable|string|max:20',
            'payhook_url'            => 'nullable|url|max:255',
            'payhook_api_key'        => 'nullable|string|max:255',
            'payhook_webhook_secret' => 'nullable|string|max:255',
        ]);

        // Server version
        if ($request->has('server_version')) {
            Setting::set('server_version', $request->input('server_version') ?: null, 'game');
        }

        // Payment gateway settings
        $paymentKeys = ['payhook_url', 'payhook_api_key', 'payhook_webhook_secret'];
        foreach ($paymentKeys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key) ?: null, 'payment');
            }
        }

        // PayHook sandbox toggle
        Setting::set('payhook_sandbox', $request->boolean('payhook_sandbox') ? '1' : '0', 'payment');

        // Feature toggles
        $features = ['shop', 'donate', 'voucher', 'ranking', 'vote', 'service', 'news', 'register', 'cubi_shop'];
        foreach ($features as $feat) {
            $val = $request->input('feature_' . $feat) === '1' ? '1' : '0';
            Setting::set('feature_' . $feat, $val, 'features');
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
