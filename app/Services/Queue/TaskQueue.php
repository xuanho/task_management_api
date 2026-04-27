<?php

namespace App\Services\Queue;

use App\Interfaces\TaskQueueInterface;
use App\Jobs\Task\Email\SendTaskCreatedEmailJob;
use App\Jobs\Task\Email\SendTaskUpdatedEmailJob;

class TaskQueue implements TaskQueueInterface
{
    public function sendTaskCreatedEmail(int $taskId, int $emailLogId): void
    {
        SendTaskCreatedEmailJob::dispatch($taskId, $emailLogId);

    }

    public function sendTaskUpdatedEmail(int $taskId): void
    {
        SendTaskUpdatedEmailJob::dispatch($taskId);

    }
}
