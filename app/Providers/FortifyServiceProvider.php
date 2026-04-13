<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
        $this->app->bind(
            \Laravel\Fortify\Contracts\RegisterResponse::class,
            \App\Http\Responses\RegisterResponse::class
        );
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Register views
        Fortify::loginView(function () {
            \App\Services\CaptchaService::generate();
            return view('auth.login');
        });

        Fortify::registerView(function () {
            \App\Services\CaptchaService::generate();
            return view('auth.register');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]);
        });

        /**
         * Custom authentication callback for Perfect World password format.
         *
         * PW login uses `name` (username) as identifier.
         * Password is stored as: bcrypt(username + password)
         * So we reconstruct the hash input as: username + entered_password
         */
        Fortify::authenticateUsing(function (Request $request) {
            \Log::info('LOGIN ATTEMPT', [
                'name' => $request->name,
                'password_len' => strlen($request->password ?? ''),
                'captcha_input' => $request->input('captcha', '(empty)'),
                'session_id' => session()->getId(),
                'has_captcha_session' => session()->has('pw_captcha_answer'),
                'captcha_session_val' => session('pw_captcha_answer', '(null)'),
            ]);

            // Validate captcha first
            $captchaResult = \App\Services\CaptchaService::verify($request->input('captcha', ''));
            \Log::info('CAPTCHA RESULT', ['passed' => $captchaResult]);

            if (! $captchaResult) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'captcha' => [__('main.auth_captcha_error')],
                ]);
            }

            $user = User::where('name', $request->name)->first();
            \Log::info('USER LOOKUP', ['found' => (bool)$user, 'name_query' => $request->name]);
            if (! $user) return null;

            $concat = strtolower($user->name) . $request->password;
            $computed = base64_encode(md5($concat, true));

            \Log::info('PASSWORD CHECK', [
                'stored' => $user->passwd,
                'computed' => $computed,
                'match' => $user->passwd === $computed,
            ]);

            if ($user->passwd === $computed) {
                \Log::info('LOGIN SUCCESS', ['user_id' => $user->ID]);
                return $user;
            }

            // Fallback format lama
            if ($user->passwd === '0x' . md5($concat)) {
                $user->forceFill(['passwd' => $computed])->save();
                return $user;
            }

            \Log::info('LOGIN FAILED - password mismatch');
            return null;
        });

        // Strict rate limiting: 5 attempts per minute per username+IP
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::lower($request->input('name')) . '|' . $request->ip();
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
