<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Worker;
use App\Policies\WorkerPolicy;

/**
 * @class AppServiceProvider
 * 
 * The main service provider for the FixUp application.
 * This provider handles the bootstrapping of core application services,
 * including password reset URL customization and policy registration.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * 
     * This method is called before the boot method and is used to
     * register services with the service container.
     * 
     * @return void
     */
    public function register(): void
    {
        // Register application services here
    }

    /**
     * Bootstrap any application services.
     * 
     * This method is called after all other service providers have been registered.
     * It configures password reset URLs and registers authorization policies.
     * 
     * @return void
     */
    public function boot(): void
    {
        // Customize password reset URL to work with frontend application
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        // Register the Worker policy for authorization
        Gate::policy(Worker::class, WorkerPolicy::class);
    }
}
