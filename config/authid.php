<?php

use Stia\AuthidPhp\Features;

return [
    'paths' => [
        'two-factor' => [
            'login' => null,
            'enable' => null,
            'confirm' => null,
            'disable' => null,
            'qr-code' => null,
            'secret-key' => null,
            'recovery-codes' => null,
        ],
    ],
    'features' => [
        Features::twoFactorAuthentication(),
    ],
];
