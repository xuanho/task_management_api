<?php

namespace App\Repositories\Project;

use App\Interfaces\Project\ProjectMemberRepositoryInterface;
use App\Models\Project\ProjectMember;

class ProjectMemberRepository implements ProjectMemberRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function findByUserAndProject(int $userId, int $projectId): ?ProjectMember
    {
        return ProjectMember::where('user_id', $userId)
            ->where('project_id', $projectId)
            ->first();
    }
}
