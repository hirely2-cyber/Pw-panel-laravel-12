<?php

return [
    'custom' => [
        'password' => [
            'regex'    => 'Password must not contain uppercase letters or symbols. Use lowercase letters (a-z) and numbers (0-9) only.',
            'min'      => 'Password must be at least 6 characters.',
            'max'      => 'Password may not be greater than 20 characters.',
            'required' => 'Password is required.',
            'confirmed'=> 'Password confirmation does not match. Make sure both passwords are the same.',
        ],
        'password_confirmation' => [
            'required' => 'Password confirmation is required.',
        ],
    ],
];
