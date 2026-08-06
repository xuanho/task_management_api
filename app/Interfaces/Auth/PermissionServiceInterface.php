<?php

namespace App\Interfaces\Auth;

use App\Models\Task\Task;

interface PermissionServiceInterface
{
    public function canCreateTask(int $userId, int $projectId): bool;

    public function canUpdateTask(int $userId, Task $task): bool;

    public function canDeleteTask(int $userId, Task $task): bool;
}
