<?php

namespace App\Interfaces\Project;

use App\Models\Project\Project;

interface ProjectRepositoryInterface
{
    public function create($data): Project;

    public function update($id, $data): Project;

    public function delete($id): void;

    public function findById($id): ?Project;
}
