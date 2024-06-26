<?php

namespace App\Providers;
use App\Services\EventService;
use App\Services\EventServiceInterface;
use App\Services\CustomerService;
use App\Services\CustomerServiceInterface;
use App\Services\BookingService;
use App\Services\BookingServiceInterface;
use App\Repositories\EventRepositoryInterface;
use App\Repositories\EventRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(EventServiceInterface::class, EventService::class);
        $this->app->bind(BookingServiceInterface::class, BookingService::class);
        $this->app->bind(CustomerServiceInterface::class, CustomerService::class);
        $this->app->bind(EventRepositoryInterface::class,EventRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
