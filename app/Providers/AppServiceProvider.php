<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\EventRepository;

use Illuminate\Support\Facades\Gate;
use App\Models\OrganizerProfile;
use App\Policies\OrganizerPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            EventRepositoryInterface::class,
            EventRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(OrganizerProfile::class, OrganizerPolicy::class);
    }
}
