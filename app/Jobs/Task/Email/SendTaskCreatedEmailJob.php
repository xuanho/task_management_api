<?php

namespace App\Jobs\Task\Email;

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

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    /**
     * Create a new job instance.
     */
    public function __construct(public int $taskId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(TaskCommandService $taskCommandService): void
    {
        $taskCommandService->handleSendEmail($this->taskId, 'created');
    }

    public function failed(Throwable $e): void
    {
        Log::error('Send email failed', [
            'task_id' => $this->taskId,
            'message' => $e->getMessage(),
        ]);

    }
}
