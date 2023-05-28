<?php

use App\Http\Controllers\Auth\WelcomeController;
use Illuminate\Support\Facades\Route;
use Spatie\WelcomeNotification\WelcomesNewUsers;

Route::redirect('', 'tableau-de-bord');

Route::get('not-found', function () {
    return response()->json('Aucun tableau de bord correspondant à ce rôle');
})->name('notFound');

Route::group(['middleware' => ['web', WelcomesNewUsers::class,]], function () {
    Route::get('bienvenue/{user}', [WelcomeController::class, 'showWelcomeForm'])->name('welcome');
    Route::post('bienvenue/{user}', [WelcomeController::class, 'savePassword']);
});

/**
 * Authentication
 */
Route::middleware('guest')->group(function () {
    Route::get('login', \App\Http\Livewire\Auth\Login::class)->name('login');
    Route::get('password/reset', \App\Http\Livewire\Auth\Passwords\Email::class)->name('password.request');
    Route::get('password/reset/{token}', \App\Http\Livewire\Auth\Passwords\Reset::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    /**
    * Authentication
    */
    Route::get('email/verify', \App\Http\Livewire\Auth\Verify::class)->middleware('throttle:6,1')->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', App\Http\Controllers\Auth\EmailVerificationController::class)
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('logout', App\Http\Controllers\Auth\LogoutController::class)->name('logout');
    Route::get('password/confirm', \App\Http\Livewire\Auth\Passwords\Confirm::class)->name('password.confirm');
    Route::get('retour', [App\Http\Controllers\HomeController::class, 'leaveImpersonate'])->name('impersonate_leave');

    /**
     * Administration
     */
    Route::name('backend.')->prefix('administration')->middleware('can:view_backend')->group(function () {
        Route::get('tableau-de-bord', \App\Http\Livewire\Backend\Dashboard::class)->name('dashboard');

        /**
        * Departments
        */
        Route::name('departments.')->prefix('departements')->group(function () {
            Route::get('', \App\Http\Livewire\Department\ListDepartments::class)->name('index');
            Route::get('creation', \App\Http\Livewire\Department\CreateDepartmentForm::class)->name('create');
            Route::get('{department}/modification', \App\Http\Livewire\Department\EditDepartmentForm::class)->name('edit');
        });

        /**
         * Type imprime
         */
        Route::name('imprimes.')->prefix('type-attestations')->group(function () {
            Route::get('', \App\Http\Livewire\AttestationType\ListAttestationTypes::class)->name('index');
            Route::get('creation', \App\Http\Livewire\AttestationType\CreateAttestationTypeForm::class)->name('create');
            Route::get('{attestationType}/modification', \App\Http\Livewire\AttestationType\EditAttestationTypeForm::class)->name('edit');
        });

        /**
        * Role et permissions
        */
        Route::name('roles.')->prefix('role')->group(function () {
            Route::get('', \App\Http\Livewire\Authorization\ListRoles::class)->name('index');
            Route::get('creation', \App\Http\Livewire\Authorization\CreateRoleForm::class)->name('create');
            Route::get('{role}/modification', \App\Http\Livewire\Authorization\EditRoleForm::class)->name('edit');
        });

        /**
         * Users
         */
        Route::name('users.')->prefix('utilisateur')->group(function () {
            Route::get('reinitialisation', \App\Http\Livewire\User\ResetUserPassword::class)->name('reset');
        });

        /**
         * Parametrage super admin
         */
        Route::name('settings.')->prefix('parametrages')->group(function () {
            Route::get('', \App\Http\Livewire\Backend\Setting::class)->name('index');
        });
    });

    /**
     * Lecture des notifications
     */
    Route::name('notifications.')->prefix('notifications')->group(function () {
        Route::get('', \App\Http\Livewire\Notification\ListNotifications::class)->name('index');
        Route::get('lecture/tous', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::get('{id}/lecture', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('markAsRead');
    });

    /**
     * Dashboards
     */
    Route::get('tableau-de-bord', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard'); // Used for dashboards menu link
    Route::name('dashboard.')->prefix('tableau-de-bord')->group(function () {
        Route::get('administrateur', \App\Http\Livewire\Dashboards\AdminDashboard::class)->name('admin');
        Route::get('intermediaire', \App\Http\Livewire\Dashboards\BrokerDashboard::class)->name('broker');
        Route::get('validateur', \App\Http\Livewire\Dashboards\ValidatorDashboard::class)->name('validator');
        Route::get('chef-stock', \App\Http\Livewire\Dashboards\SupervisorDashboard::class)->name('supervisor');
        Route::get('gestionnaire', \App\Http\Livewire\Dashboards\ManagerDashboard::class)->name('manager');
        Route::get('controleur', \App\Http\Livewire\Dashboards\AuditorDashboard::class)->name('auditor');
        Route::get('souscripteur', \App\Http\Livewire\Dashboards\SouscriptorDashboard::class)->name('souscriptor');
    });

    /**
     * Users
     */
    Route::name('users.')->prefix('utilisateurs')->middleware('can:user.list')->group(function () {
        Route::get('', \App\Http\Livewire\User\ListUsers::class)->name('index');
        Route::get('creation', \App\Http\Livewire\User\CreateUserForm::class)->name('create');
        Route::get('{user}', \App\Http\Livewire\User\ShowUser::class)->name('show');
        Route::get('{user}/modification', \App\Http\Livewire\User\EditUserForm::class)->name('edit');
    });

    /**
     * Brokers
     */
    Route::name('brokers.')->prefix('intermediaires')->middleware('can:broker.list')->group(function () {
        Route::get('', \App\Http\Livewire\Broker\ListBrokers::class)->name('index');
        Route::get('creation', \App\Http\Livewire\Broker\CreateBrokerForm::class)->name('create');
        Route::get('{broker}', \App\Http\Livewire\Broker\ShowBroker::class)->name('show');
        Route::get('{broker}/modification', \App\Http\Livewire\Broker\EditBrokerForm::class)->name('edit');
    });

    /**
     * Suppliers
     */
    Route::name('suppliers.')->prefix('fournisseurs')->middleware('can:supplier.list')->group(function () {
        Route::get('', \App\Http\Livewire\Supplier\ListSuppliers::class)->name('index');
        Route::get('creation', \App\Http\Livewire\Supplier\CreateSupplierForm::class)->name('create');
        Route::get('{supplier}', \App\Http\Livewire\Supplier\ShowSupplier::class)->name('show');
        Route::get('{supplier}/modification', \App\Http\Livewire\Supplier\EditSupplierForm::class)->name('edit');
    });

    /**
     * Activities
     */
    Route::name('activities.')->prefix('activites')->middleware('can:activity.list')->group(function () {
        Route::get('', \App\Http\Livewire\Activities\ListActivities::class)->name('index');
    });

    /**
     * Requests
     */
    Route::prefix('demandes')->name('request.')->middleware('can:request.list')->group(function () {
        Route::get('', \App\Http\Livewire\Request\ListRequests::class)->name('index');
        Route::get('creation', \App\Http\Livewire\Request\CreateRequestForm::class)->middleware('can:request.create')->name('create');
        Route::get('statistiques', \App\Http\Livewire\Request\ShowRequestsStats::class)->name('stats');
        Route::get('{request}', \App\Http\Livewire\Request\ShowRequest::class)->name('show');
        Route::get('{request}/modification', \App\Http\Livewire\Request\EditRequestForm::class)->middleware('can:request.update')->name('edit');
        Route::get('{request}/approuver', \App\Http\Livewire\Request\ApproveRequestForm::class)->middleware('can:approve,request')->name('showApprovalForm');
        Route::get('{request}/valider', \App\Http\Livewire\Request\ValidateRequestForm::class)->middleware('can:validate,request')->name('showValidationForm');
        Route::get('{request}/livrer', \App\Http\Livewire\Request\ShowDeliveryForm::class)->middleware('can:delivery.create')->name('showDeliveryForm');
    });

    /**
     * Deliveries
     */
    Route::prefix('livraisons')->name('delivery.')->middleware('can:delivery.list')->group(function () {
        Route::get('', \App\Http\Livewire\Delivery\ListDeliveries::class)->name('index');
        Route::get('{delivery}/facture', \App\Http\Livewire\Delivery\ShowDeliveryInvoice::class)->name('showInvoice');
        Route::get('{delivery}/facture/pdf', App\Http\Controllers\ShowDeliveryInvoice::class)->name('showInvoicePdf');
    });

    Route::get('livraisons/rapports', \App\Http\Livewire\Delivery\DeliveryStats::class)->name('reports.delivery');


    /**
     * Supplies
     */
    Route::prefix('approvisionnements')->name('supply.')->middleware('can:supply.list')->group(function () {
        Route::get('', \App\Http\Livewire\Supply\ListSupplies::class)->name('index');
        Route::get('creation', \App\Http\Livewire\Supply\SupplyStock::class)->middleware('can:supply.create')->name('supplyStockForm');
    });

    /**
     * Attestations
     */
    Route::prefix('attestations')->name('attestation.')->middleware('can:attestation.list')->group(function () {
        Route::get('', \App\Http\Livewire\Attestation\ListAttestations::class)->name('index');
        Route::get('anterieures', \App\Http\Livewire\Attestation\ListAnteriorAttestations::class)->middleware('can:attestation.list_anterior')->name('anterior.index');
    });

    /**
     * Scans
     */
    Route::prefix('scans')->name('scan.')->middleware('can:attestation.scan')->group(function () {
        Route::get('', \App\Http\Livewire\Scan\ListScans::class)->name('index');
        Route::get('nouveau/manual', \App\Http\Livewire\Scan\ManualScan::class)->name('manual');
        Route::get('nouveau/ocr', \App\Http\Livewire\Scan\OcrScan::class)->name('ocr');
        Route::get('{scan}', \App\Http\Livewire\Scan\ShowScan::class)->name('show');
        Route::get('{scan}/attestations', \App\Http\Livewire\Scan\ListScanAttestations::class)->name('show.attestations.index');
        Route::get('{scan}/litigieuse', \App\Http\Livewire\Scan\ListScanMismatches::class)->name('show.mismatches.index');
        Route::get('{scan}/litigieuse/revue-humaine', \App\Http\Livewire\Scan\HumanReview::class)->name('show.mismatches.humanReview');
    });

    /**
    * Stock disponible
    */
    Route::prefix('stocks')->name('stock.')->middleware(['can:stock.show'])->group(function () {
        Route::get('', \App\Http\Livewire\Attestation\TotalAvailableStock::class)->name('index');
    });

    /**
     * Account
     */
    Route::name('account.')->prefix('compte')->group(function () {
        Route::get('profile', \App\Http\Livewire\Account\Show::class)->name('profile');
    });

    /**
     * Settings
     */
    Route::get('parametres', \App\Http\Livewire\Settings\Show::class)->middleware('role:Intermédiaire')->name('settings.show');

    /**
     * Reports
     */
    Route::prefix('rapports')->name('report.')->middleware('permission:analytics.*')->group(function () {
        Route::redirect('', '/rapports/livraisons')->name('index');
        Route::get('livraisons', \App\Http\Livewire\Reports\DeliveriesReport::class)->name('delivery');
        Route::get('livraisons/pdf', \App\Http\Controllers\ShowDeliveryReport::class)->name('delivery.pdf');
        Route::get('demandes', \App\Http\Livewire\Reports\RequestsReport::class)->name('request');
        Route::get('demandes/pdf', \App\Http\Controllers\ShowRequestReport::class)->name('request.pdf');
    });

    Route::get('exports/telechargement/{file}', App\Http\Controllers\DownloadExport::class)->name('download.export');
});
