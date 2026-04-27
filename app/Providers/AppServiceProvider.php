<?php

namespace App\Providers;

use App\Infrastructure\MailService;
use App\Interfaces\Email\EmailLogReponsitoryInterface;
use App\Interfaces\Mail\MailServiceInterface;
use App\Interfaces\TaskQueueInterface;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Email\EmailLogRepository;
use App\Repositories\Task\TaskRepository;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Services\Queue\TaskQueue;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(EmailLogReponsitoryInterface::class, EmailLogRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
