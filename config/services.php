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
        'region' => 'us-east-1',
    ],
    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],
    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'google' => [
        'client_id' => '77955071426-fc3lo1583mjhmljptie80357b7r8psjn.apps.googleusercontent.com',
        'client_secret' => 'iUg4pSRTTw851wOKAt2-bAai',
        'redirect' => 'http://127.0.0.1:8000/admin/login/google/callback',
    ],
    'facebook' => [
        'client_id' => '2616990068611031',
        'client_secret' => '8f44feec132fe434406240ff304fa091',
        'redirect' => 'http://localhost:8000/admin/login/facebook/facebook/callback'
    ],
];
