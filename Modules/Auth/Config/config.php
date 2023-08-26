<?php

return [
    'name'             => 'Auth',
    'smsir-api-key'    => env('SMSIR_API_KEY'),
    'smsir-secret-key' => env('SMSIR_SECRET_KEY'),

    'kavehnegar-api-key' => env('KAVEHNEGAR_API_KEY'),

    'otp-delay'         => env('OTP_DELAY_BETWEEN_RESENDS_SECONDS', 30),
    'otp-expires-after' => env('OTP_TOKEN_EXPIRATION_SECONDS', 120),
    'otp-token-length'  => env('OTP_TOKEN_LENGTH', 4),

    'users' => [
        'model' => 'App\Models\User',
    ],
];
