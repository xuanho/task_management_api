<?php

namespace Tests\Unit\Listeners;

use App\Events\TaskCreated;
use App\Jobs\Task\Email\SendTaskCreatedEmailJob;
use App\Listeners\CreateEmailLog;
use App\Models\Task\Email\EmailLog;
use App\Models\Task\Task;
use App\Models\TaskStatus;
use App\Services\Email\EmailLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SendTaskEmailListenerTest extends TestCase
{
    use RefreshDatabase;

    public function test_listener_dispatches_job()
    {
        Queue::fake();

        $this->mock(EmailLogService::class, function ($mock) {
            $mock->shouldReceive('create')->once()->andReturn(new EmailLog(['id' => 1]));
        });

        $status = TaskStatus::factory()->create();
        $task = Task::factory()->create(['status_id' => $status->id]);

        $listener = app(CreateEmailLog::class);
        $event = new TaskCreated($task->id);
        $listener->handle($event);

        Queue::assertPushed(SendTaskCreatedEmailJob::class, function ($job) use ($task) {
            return $job->taskId === $task->id;
        });
    }
}
