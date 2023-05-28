<?php

/*
 * All configuration options for the application
 */

use Domain\Authorization\Models\Role;

return [
    /*
    |--------------------------------------------------------------------------
    | Access
    |--------------------------------------------------------------------------
    */
    'access' => [
        'role' => [
            /*
             * The ID of the super admin
             */
            'super_admin' => Role::SUPER_ADMIN,

            /*
             * The ID of the default role to give newly registered users
             * Use ID because the name can be changed from the backend
             */
            'default' => Role::BROKER,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Avatar
    |--------------------------------------------------------------------------
    |
    | Configurations related to the boilerplate's avatar system
    */
    'logo' => [
        /*
         * Default size of the logo if none is supplied
         */
        'size' => 80,
        'default_logo_url' => env('APP_URL').'/static/images/logo_saham.jpg',
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Deployment Date
    |--------------------------------------------------------------------------
    |
    | When the application has been deployed to production
    |
    */
    'start_date' => '2019-12-16',

    /*
    |--------------------------------------------------------------------------
    | Supply Fallback for anterior attestations
    |--------------------------------------------------------------------------
    |
    */
    'anterior_supply_attestation_number' => [
        \Domain\Attestation\Models\AttestationType::YELLOW => 8022999999,
        \Domain\Attestation\Models\AttestationType::GREEN => 8052999999,
        \Domain\Attestation\Models\AttestationType::BROWN => 127999999,
    ],

    'brokers' => [
        'minimum_consumption_percentage' => 75,
    ],

    'storage' => [
        'upload_path' => storage_path('app/public/attestations'),
        'output_path' => storage_path('app/public/attestations_images'),
        'tmp_dir' => storage_path('app/public/attestations-tmp'),
    ],
];
