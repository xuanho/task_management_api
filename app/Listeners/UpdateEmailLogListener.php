<?php

namespace App\Listeners;

use App\DTOs\Task\TaskMailDTO;
use App\Enums\Email\EmailStatus;
use App\Enums\Email\EmailType;
use App\Events\TaskUpdated;
use App\Interfaces\TaskQueueInterface;
use App\Models\Task\Task;
use App\Services\Email\EmailLogService;
use Illuminate\Container\Attributes\Log;

class UpdateEmailLogListener
{
    /**
     * Create the event listener.
     */
    public function __construct(private EmailLogService $emailLogService, private TaskQueueInterface $taskQueueInterface)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskUpdated $event): void
    {
        $task = Task::with('user')->findOrFail($event->taskId);
        $mailDTO = new TaskMailDTO($task->title, $task->description, $task->user->name, $task->status->name);
        $body = view('emails.task_updated', (array) $mailDTO)->render();
        $emailLog = $this->emailLogService->create([
            'task_id' => $task->id,
            'type' => EmailType::STATUS_CHANGED,
            'recipient_email' => $task->user->email,
            'subject' => 'Task updated',
            'body' => $body,
            'status' => EmailStatus::PENDING,
            
        ]);
        $this->taskQueueInterface->sendTaskUpdatedEmail($task->id, $emailLog->id);

    }
}
