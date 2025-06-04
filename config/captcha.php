<?php

return [
    'secret' => env('GOOGLE_RECAPTCHA_KEY'),
    'sitekey' => env('GOOGLE_RECAPTCHA_SECRET_KEY'),
    'options' => [
        'timeout' => 30,
    ],
];
