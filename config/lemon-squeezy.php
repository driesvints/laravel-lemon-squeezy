<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Lemon Squeezy API Key
    |--------------------------------------------------------------------------
    |
    | The Lemon Squeezy API key is used to authenticate with the Lemon Squeezy
    | API. You can find your API key in the Lemon Squeezy dashboard. You can
    | set this value using the LEMON_SQUEEZY_API_KEY environment variable.
    |
    */

    'api_key' => env('LEMON_SQUEEZY_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Lemon Squeezy Signing Secret
    |--------------------------------------------------------------------------
    |
    | The Lemon Squeezy signing secret is used to verify that the webhook
    | requests are coming from Lemon Squeezy. You can find your signing
    | secret in the Lemon Squeezy dashboard under the "Webhooks" section.
    |
    */

    'signing_secret' => env('LEMON_SQUEEZY_SIGNING_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Lemon Squeezy Url Path
    |--------------------------------------------------------------------------
    |
    | This is the base URI where routes from Lemon Squeezy will be served
    | from. The URL built into Lemon Squeezy is used by default; however,
    | you can modify this path as you see fit for your application.
    |
    */

    'path' => env('LEMON_SQUEEZY_PATH', 'lemon-squeezy'),

    /*
    |--------------------------------------------------------------------------
    | Lemon Squeezy Store
    |--------------------------------------------------------------------------
    |
    | This is the URL to your Lemon Squeezy store. You can find your store
    | URL in the Lemon Squeezy dashboard. The entered value should be the
    | subdomain of your store URL right before the .lemonsqueezy.com part.
    |
    */

    'store' => env('LEMON_SQUEEZY_STORE'),

];
