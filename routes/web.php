<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Professeur\CoursController;
use App\Http\Controllers\Professeur\DevoirController;
use App\Http\Controllers\Professeur\SoumissionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Routes Publiques (sans authentification)
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
| Routes Protégées par Authentification
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Redirection automatique selon le rôle
    |--------------------------------------------------------------------------
    */
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
        Route::get('/dashboard', function () {
            return Inertia::render('Etudiants/DashboardEtudiants', [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
            ]);
        })->name('dashboard');

        Route::get('/upload', function () {
            return Inertia::render('Etudiants/UploadFile', [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
            ]);
        })->name('upload');

        Route::get('/courses', function () {
            return Inertia::render('Etudiants/Cours', [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
            ]);
        })->name('courses');

        Route::get('/assignments', function () {
            return Inertia::render('Etudiants/MesDevoirs', [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
            ]);
        })->name('assignments');

        Route::get('/notifications', function () {
            return Inertia::render('Etudiants/Notifications');
        })->name('notifications');

        Route::get('/premium', function () {
            return Inertia::render('Etudiants/Premium');
        })->name('premium');

        Route::get('/settings', function () {
            return Inertia::render('Etudiants/Settings');
        })->name('settings');

        // Routes étudiantes pour les devoirs (À AJOUTER PLUS TARD)
        // Route::prefix('devoirs')->name('devoirs.')->group(function () {
        //     Route::get('/', [Etudiant\DevoirController::class, 'index'])->name('index');
        //     Route::get('/{devoir}', [Etudiant\DevoirController::class, 'show'])->name('show');
        //     Route::post('/{devoir}/soumettre', [Etudiant\DevoirController::class, 'soumettre'])->name('soumettre');
        // });
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Professeurs
    |--------------------------------------------------------------------------
    */
    Route::prefix('professeur')->name('professeur.')->group(function () {
        Route::get('/dashboard', function () {
            // Vérification manuelle du rôle
            $user = Auth::user();
            if ($user->account_type !== 'Professeur' && $user->account_type !== 'Admin') {
                return redirect()->route('dashboard.redirect');
            }
            return Inertia::render('Professeurs/DashboardProfesseurs');
        })->name('dashboard');

        // Routes pour les cours des professeurs
        Route::prefix('cours')->name('cours.')->group(function () {
            Route::get('/', [CoursController::class, 'index'])->name('index');
            Route::get('/create', [CoursController::class, 'create'])->name('create');
            Route::post('/', [CoursController::class, 'store'])->name('store');
            Route::get('/{cours}', [CoursController::class, 'show'])->name('show');
            Route::get('/{cours}/edit', [CoursController::class, 'edit'])->name('edit');
            Route::put('/{cours}', [CoursController::class, 'update'])->name('update');
            Route::delete('/{cours}', [CoursController::class, 'destroy'])->name('destroy');
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

            // Routes pour les soumissions
             Route::prefix('{devoir}/soumissions')->group(function () {
        Route::get('/{soumission}/download', [SoumissionController::class, 'download'])->name('soumissions.download');
        Route::post('/{soumission}/corriger', [SoumissionController::class, 'corriger'])->name('soumissions.corriger');
        Route::delete('/{soumission}', [SoumissionController::class, 'destroy'])->name('soumissions.destroy');
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
            // Vérification manuelle du rôle
            $user = Auth::user();
            if ($user->account_type !== 'Admin') {
                return redirect()->route('dashboard.redirect');
            }
            return Inertia::render('Admin/Dashboard');
        })->name('dashboard');

        // Autres routes admin...
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Test/Exemple
    |--------------------------------------------------------------------------
    */
    Route::get('/a', function () {
        return Inertia::render('Welcome');
    })->name('a');

    /*
    |--------------------------------------------------------------------------
    | Routes Profil Utilisateur (commun à tous)
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| Routes d'Authentification (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
