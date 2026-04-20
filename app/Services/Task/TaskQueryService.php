<?php

namespace App\Services\Task;

use App\Repositories\Task\TaskRepository;
use App\Http\Resources\Task\TaskResource;
class TaskQueryService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private TaskRepository $taskRepository)
    {
    }
    public function getListTasks()
    {
        return $this->taskRepository->paginate(10);
         
    }
}
