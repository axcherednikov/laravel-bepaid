<?php

return [
    /*
    |--------------------------------------------------------------------------
    | BePaid Gateway base url
    |--------------------------------------------------------------------------
    |
    | The base url to communicate with BePaid to process payment.
    |
    */
    'gateway_base_url' => env('BEPAID_GATEWAY_BASE_URL', 'https://demo-gateway.begateway.com'),

    /*
    |--------------------------------------------------------------------------
    | BePaid Checkout base url
    |--------------------------------------------------------------------------
    |
    | The base url to communicate with BePaid to create a new payment token.
    |
    */
    'checkout_base_url' => env('BEPAID_CHECKOUT_BASE_URL', 'https://checkout.begateway.com'),

    /*
    |--------------------------------------------------------------------------
    | BePaid API base url
    |--------------------------------------------------------------------------
    |
    | The base url to communicate with BePaid by API, e.g., create product,
    | get payment url, get product info, etc.
    |
    */
    'api_base_url' => env('BEPAID_API_BASE_URL', 'https://api.begateway.com'),

    /*
    |--------------------------------------------------------------------------
    | BePaid ShopId
    |--------------------------------------------------------------------------
    |
    | The shop key is required to authenticate in service.
    | Default ID is a test.
    |
    */
    'shop_id' => (int)env('BEPAID_SHOP_ID', 361),

    /*
    |--------------------------------------------------------------------------
    | BePaid ShopKey
    |--------------------------------------------------------------------------
    |
    | The shop key is required to authenticate in service.
    | Default key is a test.
    |
    */
    'shop_key' => env('BEPAID_SHOP_KEY', 'b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d'),

    /*
    |--------------------------------------------------------------------------
    | Testing mode
    |--------------------------------------------------------------------------
    |
    | Indicates if payment should not be processed as real.
    |
    */
    'test_mode' => env('APP_ENV') !== 'production',

    /*
    |--------------------------------------------------------------------------
    | Default currency
    |--------------------------------------------------------------------------
    |
    | Define the default currency that will be used in payments.
    |
    */
    'currency' => 'BYN',

    /*
    |--------------------------------------------------------------------------
    | Fallback currency
    |--------------------------------------------------------------------------
    |
    | The fallback currency determines the currency to use when the current one
    | is not available.
    |
    */
    'fallback_currency' => 'USD',

    /*
    |--------------------------------------------------------------------------
    | Default language
    |--------------------------------------------------------------------------
    |
    | Define the default language that will be used in payments.
    |
    */
    'lang' => 'ru',

    /*
    |--------------------------------------------------------------------------
    | Fallback language
    |--------------------------------------------------------------------------
    |
    | The fallback language determines the language to use when the current one
    | is not available.
    |
    */
    'fallback_lang' => 'en',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | In this section you can define paths of URLs which will be used in
    | different API calls. All of these URLs have prefix '/bepaid'.
    |
    */
    'urls' => [
        'notifications' => [
            'path' => '/notifications',
            'name' => 'bepaid.notifications',
        ],
        'success' => [
            'path' => '/success',
            'name' => 'bepaid.success',
        ],
        'decline' => [
            'path' => '/decline',
            'name' => 'bepaid.decline',
        ],
        'fail' => [
            'path' => '/fail',
            'name' => 'bepaid.fail',
        ],
        'cancel' => [
            'path' => '/cancel',
            'name' => 'bepaid.cancel',
        ],
        'return' => [
            'path' => '/return',
            'name' => 'bepaid.return'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Visible fields
    |--------------------------------------------------------------------------
    |
    | Define default visible fields.
    | Note: if when you create new DTO and set new values, it'll override ones,
    | that is defined here.
    |
    */
    'visible' => [
        'first_name',
        'last_name',
    ],

    /*
    |--------------------------------------------------------------------------
    | Readonly fields
    |--------------------------------------------------------------------------
    |
    | Define default readonly fields.
    | Note: if when you create new DTO and set new values, it'll override ones,
    | that is defined here.
    |
    */
    'read_only' => [
        'email',
    ],

    /*
    |--------------------------------------------------------------------------
    | Max attempts
    |--------------------------------------------------------------------------
    |
    | This value is used to retry call n-times if it was failed.
    |
    */
    'attempts' => 1,

    /*
    |--------------------------------------------------------------------------
    | Expiration date
    |--------------------------------------------------------------------------
    |
    | This value defines how long invoice is actual.
    |
    */
    'expired_at' => 24 * 60,

    /*
    |--------------------------------------------------------------------------
    | Middlewares
    |--------------------------------------------------------------------------
    |
    | Apply any middlewares to act route's response as your app.
    | This middlewares will be applied to all routes.
    | Note: list values in this way:
    | 'api', 'auth', ...
    |
    */
    'middlewares' => [],
];
