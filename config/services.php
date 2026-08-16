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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'gtm' => [
        'container_id' => env('GTM_CONTAINER_ID', 'GTM-5FMK8WJR'),
    ],

    'meta' => [
        'pixel_id' => env('META_PIXEL_ID', '27909085665438703'),
        'capi_token' => env('META_CAPI_TOKEN'),
    ],

    'tiktok' => [
        'pixel_id' => env('TIKTOK_PIXEL_ID', 'DA0MI2JC77UFIU519G0G'),
        'access_token' => env('TIKTOK_ACCESS_TOKEN'),
    ],

    'snapchat' => [
        'pixel_id' => env('SNAP_PIXEL_ID', 'bdbd26a3-c7a9-4250-b6a4-bcfe43476132'),
        'access_token' => env('SNAP_ACCESS_TOKEN'),
    ],

];
