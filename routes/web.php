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
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return Inertia::render('Dashboard', [
        'auth' => ['user' => Auth::user()] 
    ]);
})->name('home');

/*
|--------------------------------------------------------------------------
| Routes Protégées
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Redirection dashboard selon rôle
    // Dans web.php, modifie la route /dashboard
Route::get('/dashboard', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    $accountType = trim(strtolower($user->account_type ?? 'etudiant'));

    // Vérifie d'abord admin (case insensitive)
    if ($accountType === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    // Vérifie professeur
    if ($accountType === 'professeur') {
        return redirect()->route('professeur.dashboard');
    }

    // Par défaut : étudiant
    return redirect()->route('etudiant.dashboard');
})->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Routes Étudiants (Protégées par middleware 'etudiant')
    |--------------------------------------------------------------------------
    */
    Route::prefix('etudiant')->name('etudiant.')->group(function () {
        Route::middleware(['etudiant'])->group(function () {
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
                Route::post('/deposer', [PremiumController::class, 'deposer'])->name('deposer');
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
    });

    /*
    |--------------------------------------------------------------------------
    | Routes pour TOUS les utilisateurs (Marketplace)
    |--------------------------------------------------------------------------
    */
// web.php - Version corrigée :
Route::prefix('cours')->name('cours.')->group(function () {
    // Marketplace (cours publics approuvés)
    Route::get('/', [CoursController::class, 'index'])->name('index');

    // Page de création (accessible à tous, vérification dans contrôleur)
    Route::get('/create', [CoursController::class, 'create'])->name('create'); // SANS middleware

    // Vue d'un cours spécifique
    Route::get('/{cours}', [CoursController::class, 'show'])->name('show');
    Route::get('/{cours}/download', [CoursController::class, 'download'])->name('download');
    Route::post('/{cours}/acheter', [\App\Http\Controllers\AchatController::class, 'acheter'])->name('acheter');

    // Routes protégées (sauf /create qui est déjà géré dans le contrôleur)
    Route::middleware(['canPublish'])->group(function () {
        Route::post('/', [CoursController::class, 'store'])->name('store');
        Route::get('/{cours}/edit', [CoursController::class, 'edit'])->name('edit');
        Route::put('/{cours}', [CoursController::class, 'update'])->name('update');
        Route::delete('/{cours}', [CoursController::class, 'destroy'])->name('destroy');
    });
});

/*    |--------------------------------------------------------------------------
    | Routes Professeurs (Protégées par middleware 'professeur')
    |--------------------------------------------------------------------------
    */
    Route::prefix('professeur')->name('professeur.')->group(function () {
        Route::middleware(['professeur'])->group(function () {
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
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Admin (Protégées par middleware 'admin')
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::middleware(['admin'])->group(function () {
            // Dashboard Admin
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

            // Gestion des utilisateurs
            Route::get('/users', [AdminController::class, 'users'])->name('users');
            Route::post('/users/{user}/update-status', [AdminController::class, 'updateUserStatus'])
                ->name('users.update-status');
            Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

            // Gestion des cours
            Route::get('/cours', [AdminController::class, 'cours'])->name('cours');
            Route::get('/cours/{cours}', [AdminController::class, 'showCours'])->name('cours.show');
            Route::delete('/cours/{cours}', [AdminController::class, 'deleteCours'])->name('cours.delete');


            // Modération des cours étudiants
            Route::get('/cours-a-moderer', [AdminController::class, 'coursAModerer'])->name('cours-a-moderer');
            Route::post('/cours/{cours}/approuver', [AdminController::class, 'approuver'])->name('cours.approuver');
            Route::post('/cours/{cours}/rejeter', [AdminController::class, 'rejeter'])->name('cours.rejeter');

            // Gestion des devoirs
            Route::get('/devoirs', [AdminController::class, 'devoirs'])->name('devoirs');
            Route::get('/devoirs/{devoir}', [AdminController::class, 'showDevoir'])->name('devoirs.show');

            // Statistiques
            Route::get('/statistiques', [AdminController::class, 'statistiques'])->name('statistiques');

            // Paramètres système
            Route::get('/parametres', [AdminController::class, 'parametres'])->name('parametres');
            Route::post('/parametres', [AdminController::class, 'updateParametres'])->name('parametres.update');

            // Journal d'activité
            Route::get('/journal', [AdminController::class, 'journal'])->name('journal');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Routes Profil (Accessibles à tous les utilisateurs connectés)
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
