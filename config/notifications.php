<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging (FCM) Configuration
    |--------------------------------------------------------------------------
    */

    'fcm_server_key' => env('FCM_SERVER_KEY', 'YOUR_DEFAULT_FIREBASE_KEY_HERE'),
    'fcm_project_id' => env('FCM_PROJECT_ID', 'musaef-medical-platform'),
    'fcm_oauth_token'=> env('FCM_OAUTH_TOKEN', null),

    /*
    |--------------------------------------------------------------------------
    | Notification Custom Sounds & Media
    |--------------------------------------------------------------------------
    */

    'sounds' => [
        'emergency' => env('FCM_SOUND_EMERGENCY', 'emergency_siren.mp3'),
        'info'      => 'default',
        'reward'    => 'reward_coins.mp3',
    ],

];
