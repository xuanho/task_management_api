<?php

namespace App\Providers;

use App\Infrastructure\MailService;
use App\Interfaces\Auth\PermissionServiceInterface;
use App\Interfaces\Auth\RefreshTokenRepositoryInterface;
use App\Interfaces\Auth\RolePermissionRepositoryInterface;
use App\Interfaces\Auth\TokenServiceInterface;
use App\Interfaces\Email\EmailLogReponsitoryInterface;
use App\Interfaces\Mail\MailServiceInterface;
use App\Interfaces\Project\ProjectMemberRepositoryInterface;
use App\Interfaces\Project\ProjectRepositoryInterface;
use App\Interfaces\TaskQueueInterface;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Auth\RefreshTokenRepository;
use App\Repositories\Auth\RolePermissionRepository;
use App\Repositories\Email\EmailLogRepository;
use App\Repositories\Project\ProjectMemberRepository;
use App\Repositories\Project\ProjectRepository;
use App\Repositories\Task\TaskRepository;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Services\Auth\JwtTokenService;
use App\Services\Auth\PermissionService;
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
        $this->app->bind(TokenServiceInterface::class, JwtTokenService::class);
        $this->app->bind(RefreshTokenRepositoryInterface::class, RefreshTokenRepository::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);
        $this->app->bind(ProjectMemberRepositoryInterface::class, ProjectMemberRepository::class);
        $this->app->bind(RolePermissionRepositoryInterface::class, RolePermissionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
