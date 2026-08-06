<?php

namespace App\Interfaces\Project;

use app\Models\Project\ProjectMember;

interface ProjectMemberRepositoryInterface
{
    public function findByUserAndProject(int $userId, int $projectId): ?ProjectMember;
}
