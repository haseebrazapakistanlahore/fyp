<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'facebook' => [
        'client_id' => '259645291651585', //Facebook API
        'client_secret' => '7daf90138f87106215bcca915501bfe5', //Facebook Secret
        'redirect' => 'https://airizo.com/demo/airizo/login/facebook/callback',
    ],
    'google' => [ 
        'client_id' =>'77228743153-5p8smvhm4gol1tmmi8t64a7gf87p4h7a.apps.googleusercontent.com',
        'client_secret' =>'oWygk0fzLSDjIUNHLmGFQEj-',
        'redirect' => 'https://airizo.com/demo/airizo/login/google/callback' 
    ],
];
