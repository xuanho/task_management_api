<?php

namespace App\Jobs\Task\Email;

use App\Services\Task\TaskCommandService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;

class SendTaskUpdatedEmailJob implements ShouldQueue
{
    use Queueable, Dispatchable;
    public int $tries = 3;

    public function backoff():array{
        return [10,30,60];

    }


    /**
     * Create a new job instance.
     */
    public function __construct(public $taskId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(TaskCommandService $task): void
    {
        $task->handleSendEmail($this->taskId, 'updated');
        
    }
}
