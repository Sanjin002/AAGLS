<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParcelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DepartmentController;
use App\Http\Middleware\CheckRole;
use App\Models\Department;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/parcels/printed', [ParcelController::class, 'printedParcels'])->name('parcels.printed');
    Route::get('/parcels/create', [ParcelController::class, 'create'])->name('parcels.create');
    Route::get('/parcels/create-from-pantheon', [ParcelController::class, 'createFromPantheon'])->name('parcels.create-from-pantheon');
    Route::get('/parcels/search', [ParcelController::class, 'search'])->name('parcels.search');
    Route::get('/parcels/search-pantheon', [ParcelController::class, 'searchPantheonDocuments'])->name('parcels.search-pantheon');

    Route::get('/parcels', [ParcelController::class, 'index'])->name('parcels.index');
    Route::get('/parcels/{parcel}', [ParcelController::class, 'show'])->name('parcels.show');

    Route::middleware(['can:create,App\Models\Parcel'])->group(function () {
        Route::post('/parcels', [ParcelController::class, 'store'])->name('parcels.store');
        Route::get('/parcels/create-from-pantheon', [ParcelController::class, 'createFromPantheon'])->name('parcels.create-from-pantheon');
        Route::post('/parcels/store-from-pantheon', [ParcelController::class, 'storeFromPantheon'])->name('parcels.store-from-pantheon');
    });

    Route::middleware(['can:update,parcel'])->group(function () {
        Route::get('/parcels/{parcel}/edit', [ParcelController::class, 'edit'])->name('parcels.edit');
        Route::put('/parcels/{parcel}', [ParcelController::class, 'update'])->name('parcels.update');
    });

    Route::delete('/parcels/{parcel}', [ParcelController::class, 'destroy'])->name('parcels.destroy')->middleware('can:delete,parcel');

    Route::post('/parcels/bulk-action', [ParcelController::class, 'bulkAction'])->name('parcels.bulk-action');
    Route::post('/parcels/prepare-for-gls', [ParcelController::class, 'prepareForGLS'])->name('parcels.prepare-for-gls');
    Route::get('/parcels/{parcel}/print', [ParcelController::class, 'printLabel'])->name('parcels.print');
    Route::get('/parcels/{parcel}/reprint', [ParcelController::class, 'reprintLabel'])->name('parcels.reprint');

    Route::middleware([CheckRole::class.':admin'])->group(function () {
        Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');

        Route::resource('admin/departments', DepartmentController::class)->names([
            'index' => 'admin.departments.index',
            'create' => 'admin.departments.create',
            'store' => 'admin.departments.store',
            'edit' => 'admin.departments.edit',
            'update' => 'admin.departments.update',
            'destroy' => 'admin.departments.destroy',
        ]);
        
        Route::resource('admin/users', UserManagementController::class)->names([
            'index' => 'admin.users.index',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
        ]);
    });
});

require __DIR__.'/auth.php';