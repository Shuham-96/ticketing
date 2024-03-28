<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\TicketRepositoryInterface;
use App\Repositories\TicketRepository;
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TicketRepositoryInterface::class,TicketRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}