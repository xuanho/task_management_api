<?php

namespace App\Repositories\Task;

use App\Repositories\Task\TaskRepositoryInterface;
use App\Models\Task\Task;
class TaskRepository implements TaskRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function paginate(int $perPage = 10){
        return Task::query()->latest()->paginate($perPage);
    }
    public function create(array $data){
        return Task::create($data);
    }
    public function findByIdOrFail(int $id){}
    public function updateById(int $id, array $data){}
    public function deleteById(int $id){}
    public function countByUserId(int $user_id){
        return Task::query()->where('user_id','=',$user_id)->count();
    }
    public function existsByTitleAndUserId(string $title, int $user_id){
        return Task::query()->where('title','=',$title)->where('user_id','=',$user_id)->exists();
    }
}
