<?php

use App\Http\Controllers\Admin\BudgetRecordController;
use App\Http\Controllers\Admin\DocumentManagementController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\OnlineApplicationController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\ProfileImageController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\TimeSeriesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\AuditLogsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OnlinePatientApplicationController;
use App\Http\Controllers\Admin\PatientRecordsController;
use App\Http\Controllers\Admin\ProcessTrackingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Auth\ChangePasswordController;


Route::get('/online-application', [OnlineApplicationController::class, 'index'])->name('online-application.index');

Route::post('/applications/store', [OnlineApplicationController::class, 'store'])
    ->name('applications.store');
Route::get('/track', [OnlineApplicationController::class, 'track'])->name('track.application');



Route::get('/', function () {
    return redirect()->route('online-application.index');
});


Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    // Home route
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::redirect('/login', '/login');
    
    // Permissions
    Route::delete('permissions/destroy', [PermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', PermissionsController::class);

    // Roles
    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', RolesController::class);

    // Users
    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', UsersController::class);

    // Audit Logs
    Route::resource('audit-logs', AuditLogsController::class, ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Patient Records
    Route::delete('patient-records/destroy', [PatientRecordsController::class, 'massDestroy'])->name('patient-records.massDestroy');
    Route::post('patient-records/parse-csv-import', [PatientRecordsController::class, 'parseCsvImport'])->name('patient-records.parseCsvImport');
    Route::post('patient-records/process-csv-import', [PatientRecordsController::class, 'processCsvImport'])->name('patient-records.processCsvImport');
    Route::post('patient-records/{id}/submit', [PatientRecordsController::class, 'submit'])->name('patient-records.submit');
    Route::resource('patient-records', PatientRecordsController::class);
    Route::post('patient-records/mass-submit', [PatientRecordsController::class, 'massSubmit'])->name('patient-records.massSubmit');
    Route::post('patient-records/{id}/submit-emergency', [PatientRecordsController::class, 'submitEmergency'])
        ->name('patient-records.submit-emergency');

    Route::get('csv/template/{type}', [PatientRecordsController::class, 'csvTemplate'])
    ->name('csv.template');


    Route::resource('online-applications', OnlinePatientApplicationController::class)
        ->only(['index', 'show']);
    Route::post('/applications/{application}/confirm', [OnlinePatientApplicationController::class, 'confirmTransfer'])
        ->name('applications.confirm');


    //process tracking
    Route::resource('process-tracking', ProcessTrackingController::class)->only(['index', 'show']);
    Route::post('process-tracking/{id}/submit', [ProcessTrackingController::class, 'submit'])->name('process-tracking.submit');
    Route::post('process-tracking/{id}/decision', [ProcessTrackingController::class, 'decision'])->name('process-tracking.decision');
    Route::post('process-tracking/mass-decision', [ProcessTrackingController::class, 'massDecision'])->name('process-tracking.massDecision');

    Route::post('process-tracking/{id}/store-budget', [ProcessTrackingController::class, 'storeBudget'])->name('process-tracking.storeBudget');


    Route::put('process-tracking/{id}/update-budget', [ProcessTrackingController::class, 'updateBudget'])->name('process-tracking.updateBudget');
    Route::post('process-tracking/massBudgetAllocate', [ProcessTrackingController::class, 'massBudgetAllocate'])->name('process-tracking.massBudgetAllocate');

    Route::post('process-tracking/{patient}/store-dv', [ProcessTrackingController::class, 'storeDV'])->name('process-tracking.storeDV');
    Route::put( 'process-tracking/{id}/update-dv', [ProcessTrackingController::class, 'updateDV'])->name('process-tracking.updateDV');
    Route::post('process-tracking/massDVInput', [ProcessTrackingController::class, 'massDVInput'])->name('process-tracking.massDVInput');


    Route::post('process-tracking/{id}/disburse-budget', [ProcessTrackingController::class, 'markBudgetAsDisbursed'])->name('process-tracking.disburseBudget');
    Route::post('process-tracking/{patient}/rollback', [ProcessTrackingController::class, 'rollback'])->name('process-tracking.rollback');
    Route::post('process-tracking/{id}/mark-as-ready-for-disbursement', [ProcessTrackingController::class, 'markAsReadyForDisbursement'])
    ->name('process-tracking.markAsReadyForDisbursement');
    // Route::post('process-tracking/send-otp/{id}', [ProcessTrackingController::class, 'sendOtpForDisbursement'])->name('process-tracking.sendOtp');
    // Route::post('process-tracking/{id}/verify-otp', [ProcessTrackingController::class, 'verifyOtp'])->name('process-tracking.verifyOtp');

    Route::post('process-tracking/{id}/quick-disburse', [ProcessTrackingController::class, 'quickDisburse'])->name('process-tracking.quickDisburse');
    Route::post('process-tracking/massQuickDisburse', [ProcessTrackingController::class, 'massQuickDisburse'])->name('process-tracking.massQuickDisburse');


    Route::post('process-tracking/{id}/return-to-rollbacker', [ProcessTrackingController::class, 'returnToRollbacker'])->name('process-tracking.returnToRollbacker');


    Route::resource('budget-records', BudgetRecordController::class)->only(['index']);



    Route::delete('document-management/mass-destroy', [DocumentManagementController::class, 'massDestroy'])->name('document-management.massDestroy');
    Route::get('document-management/patient/{id}', [DocumentManagementController::class, 'show'])->name('admin.document-management.show');
    Route::delete('document-management/{id}', [DocumentManagementController::class, 'destroy'])->name('admin.document-management.destroy');
    Route::resource('document-management', DocumentManagementController::class)->names('document-management');

    //time series
    Route::get('time-series', [TimeSeriesController::class, 'index'])->name('time-series.index');
    Route::get('timeseries/get-stl-json', [TimeSeriesController::class, 'getStlJson']);
    Route::get('statistics/get-statistics', [StatisticsController::class, 'index'])->name('statistics.getStatistics');

    Route::get('statistics/deficiencies', [StatisticsController::class, 'getDeficiencyData'])->name('statistics.deficiencies');
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::delete('settings/delete-all', [SettingsController::class, 'deleteAll'])->name('settings.deleteAll');
// Add this to your admin routes group in web.php
Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
    Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
    Route::get('/list', [NotificationController::class, 'getNotifications'])->name('list');
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::get('/', [NotificationController::class, 'index'])->name('index');
});

// In your admin routes group, change these lines:
Route::post('/profile-image', [ProfileImageController::class, 'store'])->name('profile.image.store');
Route::put('/profile-image/{profileImage}/set-current', [ProfileImageController::class, 'setCurrent'])->name('profile.image.set-current');
Route::delete('/profile-image/{profileImage}', [ProfileImageController::class, 'destroy'])->name('profile.image.destroy');
    
});




Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], function () {
    // Change password
    Route::get('password', [ChangePasswordController::class, 'edit'])->name('password.edit');
    Route::post('password', [ChangePasswordController::class, 'update'])->name('password.update');
    Route::post('profile', [ChangePasswordController::class, 'updateProfile'])->name('password.updateProfile');
    Route::post('profile/destroy', [ChangePasswordController::class, 'destroy'])->name('password.destroyProfile');
});
Broadcast::routes();