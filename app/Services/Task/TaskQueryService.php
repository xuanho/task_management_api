<?php

namespace App\Services\Task;

use App\Repositories\Task\TaskRepository;

class TaskQueryService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private TaskRepository $taskRepository) {}

    public function getListTasks()
    {
        return $this->taskRepository->paginate(10);

    }

    public function findByIdOrFail(int $id)
    {
        return $this->taskRepository->findByIdOrFail($id);
    }
}
