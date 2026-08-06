<?php

namespace App\Services\Project;

use App\DTOs\Project\StoreProjectDTO;
use App\DTOs\Project\UpdateProjectDTO;
use App\Interfaces\Project\ProjectRepositoryInterface;
use App\Services\Auth\RoleService;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function __construct(protected ProjectRepositoryInterface $projectRepository, protected RoleService $roleService) {}

    public function getListProjects()
    {
        return $this->projectRepository->paginate(10);
    }

    public function create(StoreProjectDTO $dto)
    {
        return DB::transaction(function () use ($dto) {
            $project = $this->projectRepository->create($dto->toArray());
            $roleId = $this->roleService->getAdminRoleId();
            $this->projectRepository->attachMember($project, $dto->ownerId, $roleId);

            return $project;
        });

    }

    public function update(string $id, UpdateProjectDTO $dto)
    {
        return DB::transaction(function () use ($id, $dto) {
            $project = $this->projectRepository->update($id, $dto->toArray());

            return $project;
        });
    }
}
