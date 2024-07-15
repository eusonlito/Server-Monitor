<?php
return [
    'provider' => env('TRANSLATOR_PROVIDER'),

    'providers' => [
        'Microsoft' => [
            'key' => env('MICROSOFT_AZURE_KEY'),
            'region' => env('MICROSOFT_AZURE_REGION'),
        ],

        'DeepL' => [
            'key' => env('DEEPL_KEY'),
        ],
    ],
];
