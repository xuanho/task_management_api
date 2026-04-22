<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Auth\AuthRepository;
use App\Interfaces\TaskQueueInterface;
use App\Services\Queue\TaskQueue;
use App\Infrastructure\MailService;
use App\Interfaces\Mail\MailServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(TaskQueueInterface::class, TaskQueue::class);
        $this->app->bind(MailServiceInterface::class, MailService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
