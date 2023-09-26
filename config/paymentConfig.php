<?php
 return [
    'tbc' => [
        'api_endpoint' => 'https://api.tbcbank.ge/v1/tpay/',
        'api_key' => env('TBC_API_KEY'),
        // 'redirect_url' => 'http://127.0.0.1:8000/redirect',
        // 'callback_url' => 'http://127.0.0.1:8000/callback'
        'redirect_url' => 'https://justpay.ge',
        'callback_url' => 'https://justpay.ge'

    ]

 ];
