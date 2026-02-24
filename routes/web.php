<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterielController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\AffectationController;
use App\Http\Controllers\ValidationController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FournisseurController;
use App\Http\Controllers\Admin\OrganisationController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('materiels', MaterielController::class);
    
    Route::resource('demandes', DemandeController::class);
    Route::post('demandes/{demande}/officialise', [DemandeController::class, 'officialise'])->name('demandes.officialise');

    Route::get('validations', [ValidationController::class, 'index'])->name('validations.index');
    Route::post('validations/{demande}', [ValidationController::class, 'store'])->name('validations.store');

    Route::get('affectations', [AffectationController::class, 'index'])->name('affectations.index');
    Route::get('affectations/{affectation}/download', [AffectationController::class, 'download'])->name('affectations.download');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
        
        Route::resource('fournisseurs', FournisseurController::class);
        
        Route::get('organisation', [OrganisationController::class, 'index'])->name('organisation.index');
        Route::post('organisation/ville', [OrganisationController::class, 'storeVille'])->name('organisation.ville.store');
        Route::delete('organisation/ville/{ville}', [OrganisationController::class, 'destroyVille'])->name('organisation.ville.destroy');
        Route::post('organisation/direction', [OrganisationController::class, 'storeDirection'])->name('organisation.direction.store');
        Route::delete('organisation/direction/{direction}', [OrganisationController::class, 'destroyDirection'])->name('organisation.direction.destroy');
        Route::post('organisation/service', [OrganisationController::class, 'storeService'])->name('organisation.service.store');
        Route::delete('organisation/service/{servic}', [OrganisationController::class, 'destroyService'])->name('organisation.service.destroy');
        Route::post('organisation/bureau', [OrganisationController::class, 'storeBureau'])->name('organisation.bureau.store');
        Route::delete('organisation/bureau/{bureau}', [OrganisationController::class, 'destroyBureau'])->name('organisation.bureau.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
