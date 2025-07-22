<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MDA\MdaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Step\StepController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;
// use App\Http\Controllers\User\UserRoleController;
// use App\Http\Controllers\CommendationController;
use App\Http\Controllers\AuditLog\AuditLogController;
use App\Http\Controllers\Document\DocumentController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Paygroup\PayGroupController;
use App\Http\Controllers\UserRole\UserRoleController;
use App\Http\Controllers\Employee\LeaveTypeController;
use App\Http\Controllers\Gradelevel\GradeLevelController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\Employee\EmployeeLeaveController;
use App\Http\Controllers\Transfer\TransferHistoryController;
use App\Http\Controllers\Commendation\CommendationController;
use App\Http\Controllers\Queries\QueriesMisconductController;
use App\Http\Controllers\Promotiom\PromotionHistoryController;
use App\Http\Controllers\ServiceAccount\ServiceAccountController;
use App\Http\Controllers\UserPermission\UserPermissionController;
use App\Http\Controllers\Platformfeatures\PlatformFeatureController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/employee/dashboard', function () {
    return view('admin.employee.dashboard');
})->middleware(['auth', 'employee'])->name('employee.dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');

// All Platform Features Routes
Route::get('/platform-features', [PlatformFeatureController::class, 'index'])->name('platform-features.index');
Route::get('/platform-features/create', [PlatformFeatureController::class, 'create'])->name('platform-features.create');
Route::post('/platform-features', [PlatformFeatureController::class, 'store'])->name('platform-features.store');
Route::get('/platform-features/{platform_feature}', [PlatformFeatureController::class, 'show'])->name('platform-features.show');
Route::get('/platform-features/{platform_feature}/edit', [PlatformFeatureController::class, 'edit'])->name('platform-features.edit');
Route::put('/platform-features/{platform_feature}', [PlatformFeatureController::class, 'update'])->name('platform-features.update');
Route::patch('/platform-features/{platform_feature}', [PlatformFeatureController::class, 'update']);
Route::delete('/platform-features/{platform_feature}', [PlatformFeatureController::class, 'destroy'])->name('platform-features.destroy');

Route::prefix('users')->middleware(['auth'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Profile routes
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');


// All Employees Routes
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
Route::patch('/employees/{employee}', [EmployeeController::class, 'update']);
Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
Route::get('/employees/reports', [EmployeeController::class, 'reports'])->name('employees.reports');


// All MDAs Routes
Route::get('/mdas', [MdaController::class, 'index'])->name('mdas.index');
Route::get('/mdas/create', [MdaController::class, 'create'])->name('mdas.create');
Route::post('/mdas', [MdaController::class, 'store'])->name('mdas.store');
Route::get('/mdas/{mda}', [MdaController::class, 'show'])->name('mdas.show');
Route::get('/mdas/{mda}/edit', [MdaController::class, 'edit'])->name('mdas.edit');
Route::put('/mdas/{mda}', [MdaController::class, 'update'])->name('mdas.update');
Route::patch('/mdas/{mda}', [MdaController::class, 'update']);
Route::delete('/mdas/{mda}', [MdaController::class, 'destroy'])->name('mdas.destroy');
// Deactivate/Activate Routes
Route::get('/mdas/{mda}/deactivate', [MdaController::class, 'deactivate'])->name('mdas.deactivate');
Route::get('/mdas/{mda}/activate', [MdaController::class, 'activate'])->name('mdas.activate');



// All Pay Groups Routes
Route::get('/pay-groups', [PayGroupController::class, 'index'])->name('pay-groups.index');
Route::get('/pay-groups/create', [PayGroupController::class, 'create'])->name('pay-groups.create');
Route::post('/pay-groups', [PayGroupController::class, 'store'])->name('pay-groups.store');
Route::get('/pay-groups/{pay_group}', [PayGroupController::class, 'show'])->name('pay-groups.show');
Route::get('/pay-groups/{pay_group}/edit', [PayGroupController::class, 'edit'])->name('pay-groups.edit');
Route::put('/pay-groups/{pay_group}', [PayGroupController::class, 'update'])->name('pay-groups.update');
Route::patch('/pay-groups/{pay_group}', [PayGroupController::class, 'update']);
Route::delete('/pay-groups/{pay_group}', [PayGroupController::class, 'destroy'])->name('pay-groups.destroy');
// Deactivate/Activate Routes
Route::patch('/pay-groups/{payGroup}/activate', [PayGroupController::class, 'activate'])->name('pay-groups.activate');
Route::patch('/pay-groups/{payGroup}/deactivate', [PayGroupController::class, 'deactivate'])->name('pay-groups.deactivate');


// All Grade Levels Routes
Route::get('/grade-levels', [GradeLevelController::class, 'index'])->name('grade-levels.index');
Route::get('/grade-levels/create', [GradeLevelController::class, 'create'])->name('grade-levels.create');
Route::post('/grade-levels', [GradeLevelController::class, 'store'])->name('grade-levels.store');
Route::get('/grade-levels/{grade_level}', [GradeLevelController::class, 'show'])->name('grade-levels.show');
Route::get('/grade-levels/{grade_level}/edit', [GradeLevelController::class, 'edit'])->name('grade-levels.edit');
Route::put('/grade-levels/{grade_level}', [GradeLevelController::class, 'update'])->name('grade-levels.update');
Route::patch('/grade-levels/{grade_level}', [GradeLevelController::class, 'update']);
Route::delete('/grade-levels/{grade_level}', [GradeLevelController::class, 'destroy'])->name('grade-levels.destroy');
Route::get('/grade-levels/export', [GradeLevelController::class, 'export'])->name('grade-levels.export');
Route::post('/grade-levels/import', [GradeLevelController::class, 'import'])->name('grade-levels.import');
Route::get('/grade-levels/search', [GradeLevelController::class, 'search'])->name('grade-levels.search');

// All Steps Routes
Route::get('/steps', [StepController::class, 'index'])->name('steps.index');
Route::get('/steps/create', [StepController::class, 'create'])->name('steps.create');
Route::post('/steps', [StepController::class, 'store'])->name('steps.store');
Route::get('/steps/{step}', [StepController::class, 'show'])->name('steps.show');
Route::get('/steps/{step}/edit', [StepController::class, 'edit'])->name('steps.edit');
Route::put('/steps/{step}', [StepController::class, 'update'])->name('steps.update');
Route::patch('/steps/{step}', [StepController::class, 'update']);
Route::delete('/steps/{step}', [StepController::class, 'destroy'])->name('steps.destroy');
Route::delete('/steps/export', [StepController::class, 'export'])->name('steps.export');
Route::delete('/steps/import', [StepController::class, 'import'])->name('steps.import');
Route::delete('/steps/search', [StepController::class, 'search'])->name('steps.search');

// All Documents Routes
Route::prefix('employees/{employee}/documents')->group(function () {
    Route::get('/', [DocumentController::class, 'index'])->name('employees.documents.index');
    Route::get('/create', [DocumentController::class, 'create'])->name('employees.documents.create');
    Route::post('/', [DocumentController::class, 'store'])->name('employees.documents.store');
    Route::get('/{document}', [DocumentController::class, 'show'])->name('employees.documents.show');
    Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('employees.documents.edit');
    Route::put('/{document}', [DocumentController::class, 'update'])->name('employees.documents.update');
    Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('employees.documents.destroy');
});

// All Transfer Routes
Route::prefix('employees/{employee}/transfers')->group(function () {
    Route::get('/', [TransferHistoryController::class, 'index'])->name('employees.transfers.index');
    Route::get('/create', [TransferHistoryController::class, 'create'])->name('employees.transfers.create');
    Route::post('/', [TransferHistoryController::class, 'store'])->name('employees.transfers.store');
    Route::get('/{transfer}', [TransferHistoryController::class, 'show'])->name('employees.transfers.show');
    Route::delete('/{transfer}', [TransferHistoryController::class, 'destroy'])->name('employees.transfers.destroy');
});

// All Promotions Routes
Route::prefix('employees/{employee}/promotions')->group(function () {
    Route::get('/', [PromotionHistoryController::class, 'index'])->name('employees.promotions.index');
    Route::get('/create', [PromotionHistoryController::class, 'create'])->name('employees.promotions.create');
    Route::post('/', [PromotionHistoryController::class, 'store'])->name('employees.promotions.store');
    Route::get('{promotion}', [PromotionHistoryController::class, 'show'])->name('employees.promotions.show');
    Route::delete('{promotion}', [PromotionHistoryController::class, 'destroy'])->name('employees.promotions.destroy');
});

// Route::get('/dashboard', [EmployeeController::class, 'admin.dashboard'])->name('admin.dashboard');


Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/by-lga', [EmployeeController::class, 'bylga'])->name('reports.by-lga');
    Route::get('/by-mda', [EmployeeController::class, 'byMda'])->name('by-mda');
    Route::get('/by-rank', [EmployeeController::class, 'byRank'])->name('by-rank');
    Route::get('/by-gender', [EmployeeController::class, 'byGender'])->name('by-gender');
    Route::get('/by-qualification', [EmployeeController::class, 'byQualification'])->name('by-qualification');
    Route::get('/by-pay-structure', [EmployeeController::class, 'byPayStructure'])->name('by-pay-structure');
    Route::get('/retired', [EmployeeController::class, 'retiredEmployees'])->name('retired');
    Route::get('/retiring', [EmployeeController::class, 'retiringEmployees'])->name('retiring');
});


Route::get('/get-lgas/{state_id}', function ($state_id) {
    return \App\Models\LGA::where('state_id', $state_id)->get();
});

// Roles Routes
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [UserRoleController::class, 'index'])->name('index');
        Route::get('/create', [UserRoleController::class, 'create'])->name('create');
        Route::post('/', [UserRoleController::class, 'store'])->name('store');
        Route::get('/{role}', [UserRoleController::class, 'show'])->name('show');
        Route::get('/{role}/edit', [UserRoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [UserRoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [UserRoleController::class, 'destroy'])->name('destroy');
    });

    // Features Routes
Route::prefix('features')->name('features.')->group(function () {
    Route::get('/', [PlatformFeatureController::class, 'index'])->name('index');
    Route::get('/create', [PlatformFeatureController::class, 'create'])->name('create');
    Route::post('/', [PlatformFeatureController::class, 'store'])->name('store');
    Route::get('/{feature}', [PlatformFeatureController::class, 'show'])->name('show');
    Route::get('/{feature}/edit', [PlatformFeatureController::class, 'edit'])->name('edit');
    Route::put('/{feature}', [PlatformFeatureController::class, 'update'])->name('update');
    Route::delete('/{feature}', [PlatformFeatureController::class, 'destroy'])->name('destroy');
});

// Permissions Routes
Route::prefix('permissions')->name('permissions.')->group(function () {
    Route::get('/', [UserPermissionController::class, 'index'])->name('index');
    Route::get('/create', [UserPermissionController::class, 'create'])->name('create');
    Route::post('/', [UserPermissionController::class, 'store'])->name('store');
    Route::get('/{permission}', [UserPermissionController::class, 'show'])->name('show');
    Route::get('/{permission}/edit', [UserPermissionController::class, 'edit'])->name('edit');
    Route::put('/{permission}', [UserPermissionController::class, 'update'])->name('update');
    Route::delete('/{permission}', [UserPermissionController::class, 'destroy'])->name('destroy');
});

// All Commendations/Awards Routes
Route::prefix('employees/{employee}/commendations')->name('employees.commendations.')->group(function () {
    Route::get('/', [CommendationController::class, 'index'])->name('index');
    Route::get('/create', [CommendationController::class, 'create'])->name('create');
    Route::post('/', [CommendationController::class, 'store'])->name('store');
    Route::get('/{commendation}', [CommendationController::class, 'show'])->name('show');
    Route::get('/{commendation}/edit', [CommendationController::class, 'edit'])->name('edit');
    Route::put('/{commendation}', [CommendationController::class, 'update'])->name('update');
    Route::delete('/{commendation}', [CommendationController::class, 'destroy'])->name('destroy');
});


// All Queries Routes (Nested under Employee)
Route::prefix('employees/{employee}/queries')->middleware(['auth'])->group(function () {
    Route::get('/', [QueriesMisconductController::class, 'index'])->name('employees.queries.index');
    Route::get('/create', [QueriesMisconductController::class, 'create'])->name('employees.queries.create');
    Route::post('/', [QueriesMisconductController::class, 'store'])->name('employees.queries.store');
    Route::get('/{queriesMisconduct}', [QueriesMisconductController::class, 'show'])->name('employees.queries.show');
    Route::get('/{queriesMisconduct}/edit', [QueriesMisconductController::class, 'edit'])->name('employees.queries.edit');
    Route::put('/{queriesMisconduct}', [QueriesMisconductController::class, 'update'])->name('employees.queries.update');
    Route::delete('/{queriesMisconduct}', [QueriesMisconductController::class, 'destroy'])->name('employees.queries.destroy');
});


// Report Routes
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/employees-per-lga', [EmployeeController::class, 'employeesPerLga'])->name('employees.per-lga');
    Route::get('/employees/by-rank', [EmployeeController::class, 'employeesByRank'])->name('by-rank');
    Route::get('/employees/by-qualification', [EmployeeController::class, 'employeesByQualification'])->name('by-qualification');
    Route::get('/employees/by-pay-structure', [EmployeeController::class, 'employeesByPayStructure'])->name('by-pay-structure');
    Route::get('/employees/retired', [EmployeeController::class, 'retiredEmployees'])->name('employees.retired');
    Route::get('/employees/retiring', [EmployeeController::class, 'retiringEmployees'])->name('employees.retiring');
    Route::get('/employees/by-mda', [EmployeeController::class, 'byMda'])->name('by-mda');
});

Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.logs');
// For "My Service Account"
Route::get('/service-account', [ServiceAccountController::class, 'edit'])->name('service-account.edit')->middleware('auth');
Route::put('/service-account', [ServiceAccountController::class, 'update'])->name('service-account.update')->middleware('auth');

    // Import Excel
Route::get('/import-employees-form', [EmployeeController::class, 'showImportForm'])->name('import.employees.form');
Route::post('/import-employees', [EmployeeController::class, 'import'])->name('import.employees');

// Add this route for quick status changes
Route::post('/users/{user}/status', [UserController::class, 'changeStatus'])
    ->name('users.status.change')->middleware(['auth', 'user.status:active']);

    Route::middleware(['auth', 'check.status'])->group(function () {
        Route::get('/user/management/', [UserController::class, 'usermgt'])->name('user.management');
        // Other routes...
    });
    
//     Route::middleware(['auth', 'check.status'])->group(function () {
//     Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
//     Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
//     // any shared routes
// });
//    Route::middleware(['auth', 'check.status'])->group(function () {
//     Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
//     // other routes...
// });

// Regular users
Route::middleware(['auth', 'check.status'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
});

// Admins only
Route::middleware(['auth', 'check.status', 'role:BDIC Super Admin,Head of Service,Commissioner,Director'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // other admin routes
});

   
    // Excel Template Download Route
Route::get('/download-sample-template', [EmployeeController::class, 'downloadSampleTemplate'])->name('download.sample.template');

// Leave Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/leaves', [EmployeeLeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [EmployeeLeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [EmployeeLeaveController::class, 'store'])->name('leaves.store');
    Route::get('/leaves/{leave}', [EmployeeLeaveController::class, 'show'])->name('leaves.show');
    Route::get('/leaves/{leave}/edit', [EmployeeLeaveController::class, 'edit'])->name('leaves.edit');
    Route::match(['put', 'patch'], '/leaves/{leave}', [EmployeeLeaveController::class, 'update'])->name('leaves.update');
    // Route::put('/leaves/{leave}', [EmployeeLeaveController::class, 'update'])->name('leaves.update');
    Route::put('/leaves/{leave}/cancel', [EmployeeLeaveController::class, 'cancel'])->name('leaves.cancel');
    Route::post('/leaves/{leave}/approve', [EmployeeLeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('/leaves/{leave}/reject', [EmployeeLeaveController::class, 'reject'])->name('leaves.reject');

       // Routes for Leave Balance
Route::get('/leave-balance', [EmployeeLeaveController::class, 'showLeaveBalance'])->name('leave.balance.show');
Route::get('/dashboard/my-leave-balance', [EmployeeLeaveController::class, 'getLeaveBalance'])->name('dashboard.my_leave_balance');
Route::get('/leaves/history', [EmployeeLeaveController::class, 'history'])->name('leaves.history');
Route::get('/employee-leaves/history', [EmployeeLeaveController::class, 'history'])->name('employee_leaves.history');
// Download routes file
Route::get('/leaves/{id}/document', [EmployeeLeaveController::class, 'viewDocument'])->name('leaves.document.view')
     ->middleware('auth');

     // Leave Type Controller
Route::prefix('leave-types')->group(function () {
    Route::get('/', [LeaveTypeController::class, 'index'])->name('leave-types.index'); // List all
    Route::get('/create', [LeaveTypeController::class, 'create'])->name('leave-types.create'); // Show create form
    Route::post('/', [LeaveTypeController::class, 'store'])->name('leave-types.store'); // Handle form submit

    Route::get('/{leaveType}/edit', [LeaveTypeController::class, 'edit'])->name('leave-types.edit'); // Show edit form
    Route::put('/{leaveType}', [LeaveTypeController::class, 'update'])->name('leave-types.update'); // Handle update

    Route::patch('/{leaveType}/toggle-status', [LeaveTypeController::class, 'toggleStatus'])->name('leave-types.toggle-status'); // Toggle active status

    Route::get('/{leaveType}', [LeaveTypeController::class, 'show'])->name('leave-types.show'); // Optional: View single record
    Route::delete('/{leaveType}', [LeaveTypeController::class, 'destroy'])->name('leave-types.destroy'); // Optional: Delete
});


});

