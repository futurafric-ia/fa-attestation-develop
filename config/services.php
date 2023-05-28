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

    'azure' => [
        'credentials' => env('AZURE_CREDENTIALS'),
        'api_key' => env('AZURE_API_KEY'),
        'sas_token' => env('AZURE_SAS_TOKEN'),
        'query_key' => env('AZURE_QUERY_KEY'),
        'api_version' => env('AZURE_API_VERSION'),
        'base_uri_storage' => 'https://futurafricstorage.blob.core.windows.net',
        'base_uri_search_index' => 'https://basicsearch.search.windows.net/indexes',
        'base_uri_search_datasource' => 'https://basicsearch.search.windows.net/datasources',
        'base_uri_search_indexer' => 'https://basicsearch.search.windows.net/indexers',
    ],
];
