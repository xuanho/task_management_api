<?php

namespace App\Services\Auth;

use App\Enums\Auth\PermissionEnum;
use App\Exceptions\ApiException;
use App\Interfaces\Auth\PermissionServiceInterface;
use App\Interfaces\Auth\RolePermissionRepositoryInterface;
use App\Interfaces\Project\ProjectMemberRepositoryInterface;
use App\Models\Task\Task;

class PermissionService implements PermissionServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private ProjectMemberRepositoryInterface $projectMemberRepository,
        private RolePermissionRepositoryInterface $rolePermissionRepository)
    {
        //
    }

    public function canCreateTask(int $userId, int $projectId): bool
    {
        return $this->hasPermission($userId, $projectId, PermissionEnum::TASK_CREATE);
    }

    public function canUpdateTask(int $userId, Task $task): bool
    {
        if ($task->user_id === $userId) {
            return true;
        }

        return $this->hasPermission($userId, $task->project_id, PermissionEnum::TASK_UPDATE);
    }

    public function canDeleteTask(int $userId, Task $task): bool
    {
        return $this->hasPermission($userId, $task->project_id, PermissionEnum::TASK_DELETE);
    }

    private function hasPermission(int $userId, int $projectId, PermissionEnum $permission): bool
    {
        $member = $this->projectMemberRepository->findByUserAndProject($userId, $projectId);

        if (! $member) {
            throw new ApiException('User is not a member of the project', 'USER_NOT_PROJECT_MEMBER', 403);
        }
        $role = $this->rolePermissionRepository->roleHasPermission($member->role_id, $permission);

        return $role;
    }
}
