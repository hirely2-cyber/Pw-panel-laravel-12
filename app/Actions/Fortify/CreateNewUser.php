<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\CaptchaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        // Validate CAPTCHA first
        if (! CaptchaService::verify($input['captcha'] ?? '')) {
            throw ValidationException::withMessages([
                'captcha' => [__('main.auth_captcha_error')],
            ]);
        }

        Validator::make($input, [
            // Username: 4-12 chars, lowercase alphanumeric only (PW requirement)
            'name'        => ['required', 'string', 'between:4,12', 'unique:users', 'regex:/^[a-z0-9]+$/'],
            'email'       => ['required', 'string', 'email', 'max:64', 'unique:users'],
            'phonenumber' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:9', 'max:16'],
            'truename'    => ['required', 'string', 'regex:/^[a-zA-Z ]+$/', 'max:32'],
            // Password: min 6, lowercase alphanumeric (PW requirement)
            'password'    => $this->registerPasswordRules(),
            // PIN: 4-6 digit numeric (PW game PIN)
            'pin'         => ['required', 'string', 'digits_between:4,6', 'regex:/^[0-9]+$/'],
            'terms'       => ['accepted'],
        ])->validate();

        /*
         * PW password hash format (game-compatible):
         *   passwd  = base64_encode(md5(lowercase_username . password, true))
         *
         * Matches authd / account.jsp format sehingga akun yang didaftarkan
         * via panel juga bisa langsung login ke game client.
         */
        $concat  = strtolower($input['name']) . $input['password'];
        $passwd  = base64_encode(md5($concat, true));
        $passwd2 = $passwd;

        // Resolve referrer from referral code
        $referredBy = null;
        if (! empty($input['referral_code'])) {
            $referrer = User::where('referral_code', $input['referral_code'])->first();
            if ($referrer) {
                $referredBy = $referrer->ID;
            }
        }

        return User::create([
            'ID'          => User::nextId(),
            'name'        => $input['name'],
            'email'       => $input['email'],
            'phonenumber' => $input['phonenumber'],
            'truename'    => ucwords($input['truename']),
            'passwd'      => $passwd,
            'passwd2'     => $passwd2,
            'qq'          => $input['pin'],
            'role'        => 'player',
            'money'       => 0,
            'bonuses'     => 0,
            'language'    => 'id',
            'creatime'    => Carbon::now(),
            'referred_by' => $referredBy,
            'register_ip' => request()->ip(),
        ]);
    }
}
