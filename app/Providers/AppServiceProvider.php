<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            request()->server->set('HTTPS', 'on');
        }

        Inertia::share([
            'auth' => function () {
                if (auth()->check()) {
                    return [
                        'user' => [
                            'id' => auth()->user()->id,
                            'name' => auth()->user()->name,
                            'email' => auth()->user()->email,
                            'account_type' => auth()->user()->account_type,
                            'filiere' => auth()->user()->filiere,
                            'is_premium' => auth()->user()->is_premium,
                            'premium_until' => auth()->user()->premium_until,
                            'balance' => auth()->user()->balance,
                            // AJOUTEZ CE CI-DESSOUS :
                            'canPublishCours' => auth()->user()->canPublishCours(),
                        ],
                    ];
                }

                return ['user' => null];
            },
        ]);
    }
}
