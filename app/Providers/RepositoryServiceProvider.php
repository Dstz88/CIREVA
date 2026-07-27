<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\ActivityLogRepositoryInterface::class,
            \App\Repositories\ActivityLogRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\BookingItemRepositoryInterface::class,
            \App\Repositories\BookingItemRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\BookingRepositoryInterface::class,
            \App\Repositories\BookingRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\CooperationAgreementRepositoryInterface::class,
            \App\Repositories\CooperationAgreementRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\eventCategoryRepositoryInterface::class,
            \App\Repositories\eventCategoryRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\eventLocationRepositoryInterface::class,
            \App\Repositories\eventLocationRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\eventRepositoryInterface::class,
            \App\Repositories\eventRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\eventScheduleRepositoryInterface::class,
            \App\Repositories\eventScheduleRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\NotificationRepositoryInterface::class,
            \App\Repositories\NotificationRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\OrganizerDocumentRepositoryInterface::class,
            \App\Repositories\OrganizerDocumentRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\OrganizerRepositoryInterface::class,
            \App\Repositories\OrganizerRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\PaymentProofRepositoryInterface::class,
            \App\Repositories\PaymentProofRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\ReportRepositoryInterface::class,
            \App\Repositories\ReportRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\RoleRepositoryInterface::class,
            \App\Repositories\RoleRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\TicketRepositoryInterface::class,
            \App\Repositories\TicketRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\TransactionRepositoryInterface::class,
            \App\Repositories\TransactionRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
