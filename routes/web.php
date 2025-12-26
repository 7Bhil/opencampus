<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Professeur\CoursController;
use App\Http\Controllers\Professeur\DevoirController;
use App\Http\Controllers\Professeur\SoumissionController;
use App\Http\Controllers\Etudiant\DashboardController as EtudiantDashboardController;
use App\Http\Controllers\Etudiant\CoursController as EtudiantCoursController;
use App\Http\Controllers\Etudiant\DevoirController as EtudiantDevoirController;
use App\Http\Controllers\Professeur\DashboardController as ProfesseurDashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
*/
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

    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->email === 'Admin@gmail.com') {
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

        // Routes pour les cours des étudiants
        Route::prefix('cours')->name('cours.')->group(function () {
            Route::get('/', [EtudiantCoursController::class, 'index'])->name('index');
            Route::get('/{cours}', [EtudiantCoursController::class, 'show'])->name('show');
            Route::get('/{cours}/download', [EtudiantCoursController::class, 'download'])->name('download');
            Route::post('/{cours}/toggle-like', [EtudiantCoursController::class, 'toggleLike'])->name('toggle-like');
        });

        // Routes pour les devoirs des étudiants
        Route::prefix('devoirs')->name('devoirs.')->group(function () {
            Route::get('/', [EtudiantDevoirController::class, 'index'])->name('index');
            Route::get('/{devoir}', [EtudiantDevoirController::class, 'show'])->name('show');
            Route::post('/{devoir}/soumettre', [EtudiantDevoirController::class, 'soumettre'])->name('soumettre');
            // Dans votre groupe de routes devoirs
            Route::get('/{devoir}/download', [EtudiantDevoirController::class, 'download'])->name('devoirs.download');
        });

        // Autres routes étudiantes...
        Route::get('/upload', function () {
            return Inertia::render('Etudiants/UploadFile');
        })->name('upload');

        Route::get('/notifications', function () {
            return Inertia::render('Etudiants/Notifications');
        })->name('notifications');

        Route::get('/premium', function () {
            return Inertia::render('Etudiants/Premium');
        })->name('premium');

        Route::get('/settings', function () {
            return Inertia::render('Etudiants/Settings');
        })->name('settings');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Professeurs
    |--------------------------------------------------------------------------
    */
    Route::prefix('professeur')->name('professeur.')->group(function () {
        Route::get('/dashboard', [ProfesseurDashboardController::class, 'index'])->name('dashboard');

        // Routes pour les cours des professeurs (CORRIGÉ)
        Route::prefix('cours')->name('cours.')->group(function () {
            Route::get('/', [CoursController::class, 'index'])->name('index');
            Route::get('/create', [CoursController::class, 'create'])->name('create');
            Route::post('/', [CoursController::class, 'store'])->name('store');
            Route::get('/{cours}/edit', [CoursController::class, 'edit'])->name('edit');
            Route::put('/{cours}', [CoursController::class, 'update'])->name('update');
            Route::delete('/{cours}', [CoursController::class, 'destroy'])->name('destroy');
            Route::get('/{cours}', [CoursController::class, 'show'])->name('show');
            Route::post('/{cours}/publish', [CoursController::class, 'publish'])->name('publish');
            Route::post('/{cours}/unpublish', [CoursController::class, 'unpublish'])->name('unpublish');

        });

        // Routes pour les devoirs des professeurs
        Route::prefix('devoirs')->name('devoirs.')->group(function () {
            Route::get('/', [DevoirController::class, 'index'])->name('index');
            Route::get('/create', [DevoirController::class, 'create'])->name('create');
            Route::post('/', [DevoirController::class, 'store'])->name('store');
            Route::get('/{devoir}', [DevoirController::class, 'show'])->name('show');
            Route::get('/{devoir}/edit', [DevoirController::class, 'edit'])->name('edit');
            Route::put('/{devoir}', [DevoirController::class, 'update'])->name('update');
            Route::delete('/{devoir}', [DevoirController::class, 'destroy'])->name('destroy');
            Route::post('/devoirs/{devoir}/toggle-actif', [DevoirController::class, 'toggleActif'])
            ->name('professeur.devoirs.toggle-actif');

            // Routes pour les soumissions
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
        Route::get('/dashboard', function () {
            $user = Auth::user();
            if ($user->account_type !== 'Admin') {
                return redirect()->route('dashboard.redirect');
            }
            return Inertia::render('Admin/Dashboard');
        })->name('dashboard');
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Profil Utilisateur
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
