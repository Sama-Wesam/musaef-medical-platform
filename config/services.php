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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Socialite OAuth Credentials
    |--------------------------------------------------------------------------
    */

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID', 'demo-google-client-id.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', 'demo-google-client-secret'),
        'redirect'      => env('GOOGLE_REDIRECT_URI', 'http://localhost:8000/api/auth/social/google/callback'),
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_CLIENT_ID', 'demo-facebook-app-id'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', 'demo-facebook-app-secret'),
        'redirect'      => env('FACEBOOK_REDIRECT_URI', 'http://localhost:8000/api/auth/social/facebook/callback'),
    ],

    'apple' => [
        'client_id'     => env('APPLE_CLIENT_ID', 'demo-apple-service-id'),
        'client_secret' => env('APPLE_CLIENT_SECRET', 'demo-apple-client-secret'),
        'redirect'      => env('APPLE_REDIRECT_URI', 'http://localhost:8000/api/auth/social/apple/callback'),
    ],

];
