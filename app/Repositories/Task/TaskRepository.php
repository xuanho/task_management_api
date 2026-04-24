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
    public function findByIdOrFail(int $id){
        return Task::query()->where('id', $id)->findOrFail($id);
    }
    public function updateById(int $id, array $data)
    {
        $task = Task::query()->where('id', $id)->where('user_id', $data['user_id'])->firstOrFail();
        $task->update($data);
        return $task;
    }
    public function deleteById(int $id, int $user_id){
        $task = Task::query()->where('id', $id)->where('user_id', $user_id)->firstOrFail();
        $task->delete();
        return $task;
    }
    public function countByUserId(int $user_id){
        return Task::query()->where('user_id','=',$user_id)->count();
    }
    public function existsByTitleAndUserId(string $title, int $user_id){
        return Task::query()->where('title','=',$title)->where('user_id','=',$user_id)->exists();
    }
    public function findWithUser($id){
        return Task::with(['user','status'])->findOrFail($id);
    }
}
