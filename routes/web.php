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
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::redirect('/login', '/login');

    Route::delete('permissions/destroy', [PermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', PermissionsController::class);

    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', RolesController::class);

    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', UsersController::class);

    Route::resource('audit-logs', AuditLogsController::class, ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
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
    Route::get('excel/template/{type}', [PatientRecordsController::class, 'excelTemplate'])
        ->name('excel.template');
    
    Route::put('patient-records/{id}/restore', [PatientRecordsController::class, 'restore'])
        ->name('patient-records.restore');
    Route::post('patient-records/mass-restore', [PatientRecordsController::class, 'massRestore'])
        ->name('patient-records.massRestore');
    Route::delete('patient-records/{id}/force-delete', [PatientRecordsController::class, 'forceDelete'])
        ->name('patient-records.force-delete');
    Route::delete('patient-records/mass-force-delete', [PatientRecordsController::class, 'massForceDelete'])
        ->name('patient-records.massForceDelete');

    Route::resource('online-applications', OnlinePatientApplicationController::class)
        ->only(['index', 'show']);
    Route::post('/applications/{application}/confirm', [OnlinePatientApplicationController::class, 'confirmTransfer'])
        ->name('applications.confirm');


    Route::resource('process-tracking', ProcessTrackingController::class)->only(['index', 'show']);
    Route::post('process-tracking/{id}/submit', [ProcessTrackingController::class, 'submit'])->name('process-tracking.submit');
    Route::post('process-tracking/{id}/decision', [ProcessTrackingController::class, 'decision'])->name('process-tracking.decision');
    Route::post('process-tracking/mass-decision', [ProcessTrackingController::class, 'massDecision'])->name('process-tracking.massDecision');

    Route::post('process-tracking/{id}/store-budget', [ProcessTrackingController::class, 'storeBudget'])->name('process-tracking.storeBudget');


    Route::put('process-tracking/{id}/update-budget', [ProcessTrackingController::class, 'updateBudget'])->name('process-tracking.updateBudget');
    Route::post('process-tracking/massBudgetAllocate', [ProcessTrackingController::class, 'massBudgetAllocate'])->name('process-tracking.massBudgetAllocate');

    Route::post('process-tracking/{patient}/store-dv', [ProcessTrackingController::class, 'storeDV'])->name('process-tracking.storeDV');
    Route::put('process-tracking/{id}/update-dv', [ProcessTrackingController::class, 'updateDV'])->name('process-tracking.updateDV');
    Route::post('process-tracking/massDVInput', [ProcessTrackingController::class, 'massDVInput'])->name('process-tracking.massDVInput');


    Route::post('process-tracking/{id}/disburse-budget', [ProcessTrackingController::class, 'markBudgetAsDisbursed'])->name('process-tracking.disburseBudget');
    Route::post('process-tracking/{patient}/rollback', [ProcessTrackingController::class, 'rollback'])->name('process-tracking.rollback');
    Route::post('process-tracking/{id}/mark-as-ready-for-disbursement', [ProcessTrackingController::class, 'markAsReadyForDisbursement'])
        ->name('process-tracking.markAsReadyForDisbursement');
    // Route::post('process-tracking/send-otp/{id}', [ProcessTrackingController::class, 'sendOtpForDisbursement'])->name('process-tracking.sendOtp');
    // Route::post('process-tracking/{id}/verify-otp', [ProcessTrackingController::class, 'verifyOtp'])->name('process-tracking.verifyOtp');

    Route::post('process-tracking/{id}/quick-disburse', [ProcessTrackingController::class, 'quickDisburse'])->name('process-tracking.quickDisburse');
    Route::post('process-tracking/massQuickDisburse', [ProcessTrackingController::class, 'massQuickDisburse'])->name('process-tracking.massQuickDisburse');
    Route::post('process-tracking/mass-ready-for-disbursement', [ProcessTrackingController::class, 'massReadyForDisbursement'])
        ->name('process-tracking.massReadyForDisbursement');


    Route::post('process-tracking/{id}/return-to-rollbacker', [ProcessTrackingController::class, 'returnToRollbacker'])->name('process-tracking.returnToRollbacker');


    Route::resource('budget-records', BudgetRecordController::class)->only(['index']);

    Route::delete('document-management/mass-destroy', [DocumentManagementController::class, 'massDestroy'])
        ->name('document-management.massDestroy');

    Route::get('document-management/view/{id}', [DocumentManagementController::class, 'view'])
        ->name('document-management.view'); 

    Route::resource('document-management', DocumentManagementController::class)
        ->names('document-management');

    //time series
    Route::get('time-series', [TimeSeriesController::class, 'index'])->name('time-series.index');
    Route::get('timeseries/get-stl-json', [TimeSeriesController::class, 'getStlJson']);
    Route::get('statistics/get-statistics', [StatisticsController::class, 'index'])->name('statistics.getStatistics');

    Route::get('statistics/deficiencies', [StatisticsController::class, 'getDeficiencyData'])->name('statistics.deficiencies');
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::delete('settings/delete-all', [SettingsController::class, 'deleteAll'])->name('settings.deleteAll');
    // Backup & Restore Routes
    Route::post('settings/backup/create', [SettingsController::class, 'createBackup'])->name('settings.backup.create');
    Route::post('settings/backup/restore', [SettingsController::class, 'restoreBackup'])->name('settings.backup.restore');
    Route::get('settings/backup/download/{id}', [SettingsController::class, 'downloadBackup'])->name('settings.backup.download');
    Route::delete('settings/backup/{id}', [SettingsController::class, 'deleteBackup'])->name('settings.backup.delete');
    Route::get('settings/backups', [SettingsController::class, 'getBackups'])->name('settings.backups.list');


    Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/list', [NotificationController::class, 'getNotifications'])->name('list');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/', [NotificationController::class, 'index'])->name('index');
    });

    Route::post('/profile-image', [ProfileImageController::class, 'store'])->name('profile.image.store');
    Route::put('/profile-image/{profileImage}/set-current', [ProfileImageController::class, 'setCurrent'])->name('profile.image.set-current');
    Route::delete('/profile-image/{profileImage}', [ProfileImageController::class, 'destroy'])->name('profile.image.destroy');
  
    Route::get('/profile-image/{profileImage}', [ProfileImageController::class, 'show'])
        ->name('profile.image.show')
        ->middleware('auth');
});




Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], function () {
    Route::get('password', [ChangePasswordController::class, 'edit'])->name('password.edit');
    Route::post('password', [ChangePasswordController::class, 'update'])->name('password.update');
    Route::post('profile', [ChangePasswordController::class, 'updateProfile'])->name('password.updateProfile');
    Route::post('profile/destroy', [ChangePasswordController::class, 'destroy'])->name('password.destroyProfile');
});
Broadcast::routes();
