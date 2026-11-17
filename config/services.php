<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    | Inbound API key for /api/hero/* (X-API-Key).
    | Prefer ARAIM_API_KEY so it stays distinct from Hero's API key. Both apps
    | share one Windows Apache process; each HTTP request reloads its own .env.
    */
    'api' => [
        'key' => env('ARAIM_API_KEY', env('API_KEY')),
    ],

    'hero' => [
        'url' => env('HERO_API_URL'),
        'token' => env('HERO_API_TOKEN'),
    ],

];
