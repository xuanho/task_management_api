<?php

namespace App\Repositories\TaskHistory;

use App\DTOs\TaskHistory\CreateTaskHistoryDTO;
use App\Models\Task\TaskHistory\TaskHistory;

class TaskHistoryRepository
{
    public function logStatusChange(CreateTaskHistoryDTO $createTaskHistoryDTO)
    {
        return TaskHistory::create($createTaskHistoryDTO->toArray());
    }
}
