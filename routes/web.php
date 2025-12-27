<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Professeur\DevoirController;
use App\Http\Controllers\Professeur\SoumissionController;
use App\Http\Controllers\Etudiant\DashboardController as EtudiantDashboardController;
use App\Http\Controllers\Etudiant\CoursController as EtudiantCoursController;
use App\Http\Controllers\Etudiant\DevoirController as EtudiantDevoirController;
use App\Http\Controllers\Professeur\DashboardController as ProfesseurDashboardController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\MarketplaceCoursController;
use App\Http\Controllers\Admin\AdminController; // IMPORT AJOUTÉ
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
*/
Route::get('/test-simple', function() {
    return Inertia::render('Cours/Create');
});

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard.redirect');
    }
    return Inertia::render('Dashboard');
})->name('home');

/*
|--------------------------------------------------------------------------
| Routes Protégées
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Redirection dashboard selon rôle
    Route::get('/dashboard', function () {
        $user = Auth::user();

        // Vérifiez d'abord si c'est un admin
        if ($user->account_type === 'Admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->account_type === 'Professeur') {
            return redirect()->route('professeur.dashboard');
        }

        // Par défaut : étudiant
        return redirect()->route('etudiant.dashboard');
    })->name('dashboard.redirect');

    /*
    |--------------------------------------------------------------------------
    | Routes Étudiants
    |--------------------------------------------------------------------------
    */
    Route::prefix('etudiant')->name('etudiant.')->group(function () {
        Route::get('/dashboard', [EtudiantDashboardController::class, 'index'])->name('dashboard');

        // Cours suivis/achetés par l'étudiant
        Route::prefix('cours')->name('cours.')->group(function () {
            Route::get('/', [EtudiantCoursController::class, 'index'])->name('index');
            Route::get('/{cours}', [EtudiantCoursController::class, 'show'])->name('show');
            Route::get('/{cours}/download', [EtudiantCoursController::class, 'download'])->name('download');
            Route::post('/{cours}/toggle-like', [EtudiantCoursController::class, 'toggleLike'])->name('toggle-like');
        });

        // Devoirs étudiants
        Route::prefix('devoirs')->name('devoirs.')->group(function () {
            Route::get('/', [EtudiantDevoirController::class, 'index'])->name('index');
            Route::get('/{devoir}', [EtudiantDevoirController::class, 'show'])->name('show');
            Route::post('/{devoir}/soumettre', [EtudiantDevoirController::class, 'soumettre'])->name('soumettre');
            Route::get('/{devoir}/download', [EtudiantDevoirController::class, 'download'])->name('devoirs.download');
        });

        // Premium
        Route::prefix('premium')->name('premium.')->group(function () {
            Route::get('/', [PremiumController::class, 'index'])->name('index');
            Route::post('/souscrire', [PremiumController::class, 'souscrire'])->name('souscrire');
            Route::post('/annuler', [PremiumController::class, 'annuler'])->name('annuler');
        });

        // Autres
        Route::get('/upload', function () {
            return Inertia::render('Etudiants/UploadFile');
        })->name('upload');

        Route::get('/notifications', function () {
            return Inertia::render('Etudiants/Notifications');
        })->name('notifications');

        Route::get('/settings', function () {
            return Inertia::render('Etudiants/Settings');
        })->name('settings');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes pour TOUS les utilisateurs (Marketplace UNIFIÉE)
    |--------------------------------------------------------------------------
    */
    Route::prefix('cours')->name('cours.')->group(function () {
        // Marketplace (cours publics)
        Route::get('/', [CoursController::class, 'index'])->name('index');

        // IMPORTANT : Création AVANT les routes paramétrées
        Route::get('/create', [MarketplaceCoursController::class, 'create'])->name('create');
        Route::post('/', [CoursController::class, 'store'])->name('store');

        // Routes paramétrées - APRÈS les routes spécifiques
        Route::get('/{cours}', [CoursController::class, 'show'])->name('show');
        Route::get('/{cours}/download', [CoursController::class, 'download'])->name('download');

        // Édition/suppression (vérification dans le contrôleur)
        Route::get('/{cours}/edit', [CoursController::class, 'edit'])->name('edit');
        Route::put('/{cours}', [CoursController::class, 'update'])->name('update');
        Route::delete('/{cours}', [CoursController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Professeurs (dashboard et devoirs uniquement)
    |--------------------------------------------------------------------------
    */
    Route::prefix('professeur')->name('professeur.')->group(function () {
        Route::get('/dashboard', [ProfesseurDashboardController::class, 'index'])->name('dashboard');

        // Cours créés par le professeur (vue spéciale)
        Route::get('/mes-cours', [CoursController::class, 'mesCours'])->name('mes-cours');

        // Devoirs (spécifique aux profs)
        Route::prefix('devoirs')->name('devoirs.')->group(function () {
            Route::get('/', [DevoirController::class, 'index'])->name('index');
            Route::get('/create', [DevoirController::class, 'create'])->name('create');
            Route::post('/', [DevoirController::class, 'store'])->name('store');
            Route::get('/{devoir}', [DevoirController::class, 'show'])->name('show');
            Route::get('/{devoir}/edit', [DevoirController::class, 'edit'])->name('edit');
            Route::put('/{devoir}', [DevoirController::class, 'update'])->name('update');
            Route::delete('/{devoir}', [DevoirController::class, 'destroy'])->name('destroy');
            Route::post('/{devoir}/toggle-actif', [DevoirController::class, 'toggleActif'])
                ->name('toggle-actif');

            // Soumissions
            Route::prefix('{devoir}/soumissions')->name('soumissions.')->group(function () {
                Route::get('/{soumission}/download', [SoumissionController::class, 'download'])
                    ->name('download');
                Route::post('/{soumission}/corriger', [SoumissionController::class, 'corriger'])
                    ->name('corriger');
                Route::delete('/{soumission}', [SoumissionController::class, 'destroy'])
                    ->name('destroy');
            });
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Admin
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/update-status', [AdminController::class, 'updateUserStatus'])->name('users.update-status');
        Route::get('/cours', [AdminController::class, 'cours'])->name('cours');
        Route::delete('/cours/{cours}', [AdminController::class, 'deleteCours'])->name('cours.delete');
        Route::get('/cours-a-moderer', [AdminController::class, 'coursAModerer'])->name('cours-a-moderer');
        Route::post('/cours/{cours}/approuver', [AdminController::class, 'approuver'])->name('cours.approuver');
        Route::post('/cours/{cours}/rejeter', [AdminController::class, 'rejeter'])->name('cours.rejeter');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Profil
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
