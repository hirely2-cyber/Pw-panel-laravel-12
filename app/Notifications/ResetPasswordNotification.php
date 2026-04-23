<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Setting;

class ResetPasswordNotification extends BaseResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $siteName  = Setting::get('seo_title') ?: config('pw-config.server.name') ?: 'Perfect World';
        $logoPath  = Setting::get('site_footer_logo');
        $logoUrl   = $logoPath
            ? rtrim(config('app.url'), '/') . '/storage/' . $logoPath
            : null;
        $appUrl    = config('app.url');
        $resetUrl  = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Password — ' . $siteName)
            ->view('emails.reset-password', [
                'siteName' => $siteName,
                'logoUrl'  => $logoUrl,
                'appUrl'   => $appUrl,
                'resetUrl' => $resetUrl,
                'username' => $notifiable->name ?? $notifiable->email,
                'expireMinutes' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60),
            ]);
    }
}
