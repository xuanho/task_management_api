<?php

namespace App\Jobs\Task\Email;

use App\Enums\Email\EmailStatus;
use App\Services\Email\EmailLogService;
use App\Services\Task\TaskCommandService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendTaskCreatedEmailJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 3;
    public ?int $emailLogId = null;

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    /**
     * Create a new job instance.
     */
    public function __construct(public int $taskId, ?int $emailLogId = null)
    {
        $this->emailLogId = $emailLogId;
    }

    /**
     * Execute the job.
     */
    public function handle(TaskCommandService $taskCommandService, EmailLogService $emailLogService): void
    {
        $taskCommandService->handleSendEmail($this->taskId, 'created');
        if ($this->emailLogId !== null) {
            $emailLogService->updateStatus($this->emailLogId, ['status' => EmailStatus::SENT]);
        }
    }

    public function failed(Throwable $e): void
    {
        if ($this->emailLogId !== null) {
            app(EmailLogService::class)->updateStatus($this->emailLogId,
                [
                    'status' => EmailStatus::FALIED,
                    'error_message' => $e->getMessage(),
                ]);
        }
        Log::error('Send email failed', [
            'task_id' => $this->taskId,
            'message' => $e->getMessage(),
        ]);

    }
}
