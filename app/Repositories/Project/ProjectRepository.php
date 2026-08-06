<?php

namespace App\Repositories\Project;

use App\Interfaces\Project\ProjectRepositoryInterface;
use App\Models\Project\Project;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function paginate(int $perPage = 10)
    {
        return Project::query()->latest()->paginate($perPage);
    }

    public function create($data): Project
    {
        $project = Project::create($data);

        return $project;

    }

    public function attachMember(Project $project, int $userId, int $roleId): void
    {
        $project->members()->attach($userId, ['role_id' => $roleId]);

    }

    public function update($id, $data): Project
    {
        $project = Project::findOrFail($id);
        $project->update($data);

        return $project;

    }

    public function delete($id): void {}

    public function findById($id): ?Project {}
}
