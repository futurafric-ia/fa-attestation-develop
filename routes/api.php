<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\API\BrokerAttestationController;
use App\Http\Controllers\API\BrokerDashboardController;
use App\Http\Controllers\API\BrokerDeliveryController;
use App\Http\Controllers\API\BrokerRequestController;
use App\Http\Controllers\API\BrokerNotificationController;
use App\Http\Controllers\API\LoginController;

Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    /**
     * Dashboard
     */
    Route::get('dashboard/intermediaire', [BrokerDashboardController::class, 'index'])->middleware('role:Intermédiaire');

    /**
     * Attestations
     */
    Route::prefix('attestations')->middleware('role:Intermédiaire')->group(function () {
        Route::get('', [BrokerAttestationController::class, 'index']);
        Route::get('{attestation:attestation_number}', [BrokerAttestationController::class, 'show']);
        Route::get('{attestation:attestation_number}/statut', [BrokerAttestationController::class, 'showStatus']);
    });

    /**
     * Demandes
     */
    Route::apiResource('demandes', BrokerRequestController::class)->middleware('role:Intermédiaire');

    /**
     * Livraisons
     */
    Route::prefix('livraisons')->middleware('role:Intermédiaire')->group(function () {
        Route::get('', [BrokerDeliveryController::class, 'index']);
        Route::get('{delivery}', [BrokerDeliveryController::class, 'show']);
    });

    /**
     * Notifications
     */
    Route::prefix('notifications')->middleware('role:Intermédiaire')->group(function () {
        Route::get('', [BrokerNotificationController::class, 'index']);
        Route::patch('{notification}/read', [BrokerNotificationController::class, 'markAsRead']);
    });
});
