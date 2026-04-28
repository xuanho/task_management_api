<?php

namespace App\Services\Queue;

use App\Interfaces\TaskQueueInterface;
use App\Jobs\Task\Email\SendTaskCreatedEmailJob;
use App\Jobs\Task\Email\SendTaskUpdatedEmailJob;

class TaskQueue implements TaskQueueInterface
{
    public function sendTaskCreatedEmail(int $taskId, int $emailLogId): void
    {
        SendTaskCreatedEmailJob::dispatch($taskId, $emailLogId)->onQueue('emails');
    }

    public function sendTaskUpdatedEmail(int $taskId, int $emailLogId): void
    {
        SendTaskUpdatedEmailJob::dispatch($taskId, $emailLogId)->onQueue('emails');

    }
}
