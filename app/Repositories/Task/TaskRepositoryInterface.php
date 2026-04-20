<?php
namespace App\Repositories\Task;

interface TaskRepositoryInterface
{
    public function paginate(int $perPage = 10);
    public function create(array $data);
    public function findByIdOrFail(int $id);
    public function updateById(int $id, array $data);
    public function deleteById(int $id);
}