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
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password'         => $this->updatePasswordRules(),
        ])->after(function ($validator) use ($user, $input) {
            $expected = base64_encode(md5(strtolower($user->name) . $input['current_password'], true));
            // Fallback format lama
            $expectedOld = '0x' . md5(strtolower($user->name) . $input['current_password']);
            if ($user->passwd !== $expected && $user->passwd !== $expectedOld) {
                $validator->errors()->add(
                    'current_password',
                    __('The provided password does not match your current password.')
                );
            }
        })->validateWithBag('updatePassword');

        $concat = strtolower($user->name) . $input['password'];
        $hash   = base64_encode(md5($concat, true));

        $user->forceFill([
            'passwd'  => $hash,
            'passwd2' => $hash,
        ])->save();
    }
}
