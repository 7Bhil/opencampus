<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Enregistrez vos middlewares personnalisés
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminOnly::class,
            'professeur' => \App\Http\Middleware\ProfesseurOnly::class,
            'etudiant' => \App\Http\Middleware\EtudiantOnly::class,
            'canPublish' => \App\Http\Middleware\CanPublishCours::class,
            'redirect.after.login' => \App\Http\Middleware\RedirectAfterLogin::class,
        ]);

        // NE MODIFIEZ PAS le groupe 'web' - laissez-le par défaut
        // Laravel gère automatiquement les middlewares web par défaut
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
