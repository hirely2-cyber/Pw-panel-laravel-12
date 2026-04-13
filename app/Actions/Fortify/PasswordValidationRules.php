<?php

/*
 * @author   Wahyu Suhandi <andietz.orion@gmail.com>
 * @link     https://wa.me/6208118719377
 * @project  Perfect World Panel
 * @version  2.0.0
 * @license  MIT
 */

namespace App\Actions\Fortify;

/**
 * Perfect World password validation rules.
 *
 * PW requirement: lowercase alphanumeric only [a-z0-9], min 6 chars.
 * No special characters — game server limitation.
 */
trait PasswordValidationRules
{
    protected function passwordRules(): array
    {
        return ['required', 'string', 'min:6', 'confirmed', 'regex:/^[a-z0-9]+$/'];
    }

    protected function registerPasswordRules(): array
    {
        return ['required', 'string', 'min:6', 'confirmed', 'regex:/^[a-z0-9]+$/'];
    }

    protected function updatePasswordRules(): array
    {
        return ['required', 'string', 'min:6', 'confirmed', 'regex:/^[a-z0-9]+$/'];
    }

    protected function resetPasswordRules(): array
    {
        return ['required', 'string', 'min:6', 'regex:/^[a-z0-9]+$/'];
    }
}
