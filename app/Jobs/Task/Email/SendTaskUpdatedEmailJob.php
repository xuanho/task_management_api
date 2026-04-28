<?php

namespace App\Jobs\Task\Email;

use App\Enums\Email\EmailStatus;
use App\Models\Task\Email\EmailLog;
use App\Services\Email\EmailLogService;
use App\Services\Task\TaskCommandService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class SendTaskUpdatedEmailJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 3;

    public function backoff(): array
    {
        return [10, 30, 60];

    }

    /**
     * Create a new job instance.
     */
    public function __construct(public $taskId, public $emailLogId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(TaskCommandService $task, EmailLogService $emailLogService): void
    {
        // idempotency check
        if ($this->emailLogId !== null) {
            $emailLog = EmailLog::find($this->emailLogId);
            if ($emailLog && $emailLog->status == EmailStatus::SENT) {
                return;
            }
        }
        $task->handleSendEmail($this->taskId, 'updated');
        $updatedStatus = $emailLogService->updateStatus($this->emailLogId, ['status' => EmailStatus::SENT]);
    }

    public function failed(Throwable $e)
    {
        $updatedStatus = app(EmailLogService::class)->updateStatus($this->emailLogId,
            [
                'status' => EmailStatus::FALIED,
                'error_message' => $e->getMessage(),
            ]);

        Log::error('Send email failed', [
            'task_id' => $this->taskId,
            'message' => $e->getMessage(),
        ]);
    }
}
