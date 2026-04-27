<?php

namespace App\Listeners;

use App\Enums\Email\EmailStatus;
use App\Enums\Email\EmailType;
use App\Events\TaskCreated;
use App\Interfaces\TaskQueueInterface;
use App\Models\Task\Task;
use App\Services\Email\EmailLogService;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateEmailLog implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(protected EmailLogService $emailLogService, private TaskQueueInterface $taskQueue)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskCreated $event): void
    {
        $task = Task::with('user')->findOrFail($event->taskId);

        $emailLog = $this->emailLogService->create([
            'task_id' => $task->id,
            'type' => EmailType::TASK_CREATED,
            'recipient_email' => $task->user->email,
            'subject' => 'Task created',
            'status' => EmailStatus::PENDING,
        ]);

        $this->taskQueue->sendTaskCreatedEmail($task->id, $emailLog->id);

    }
}
