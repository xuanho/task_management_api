<?php

namespace Tests\Feature\Services;

use App\DTOs\Task\CreateTaskDTO;
use App\Events\TaskCreated;
use App\Exceptions\ApiException;
use App\Models\Task\Task;
use App\Models\TaskStatus;
use App\Models\User;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Services\Task\TaskCommandService;
use Database\Seeders\TaskStatusSeeder;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        $this->seed(TaskStatusSeeder::class);
        $this->user = User::factory()->create();
        $this->taskStatus = TaskStatus::where('code', 'todo')->first();
        $this->dto = CreateTaskDTO::fromArray(['title' => 'Test Task', 'description' => 'Test Description', 'status_id' => $this->taskStatus->id]);
    }

    public function test_create_task_success(): void
    {
        $this->taskCommandService = app(TaskCommandService::class);
        $task = $this->taskCommandService->create($this->dto, $this->user->id);
        // assert return
        $this->assertInstanceOf(Task::class, $task);
        // assert DB
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'user_id' => $this->user->id,
        ]);
        $this->assertDatabaseCount('tasks', 1);
        Event::assertDispatched(TaskCreated::class, function ($event) use ($task) {
            return $event->taskId === $task->id;
        });

    }

    public function test_create_task_failed_due_to_task_limit_reached_throw_exception(): void
    {
        $this->taskCommandService = app(TaskCommandService::class);
        Task::factory()->count(5)->create(['user_id' => $this->user->id]);
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('You have reached the maximum number of tasks');
        $this->taskCommandService->create($this->dto, $this->user->id);
    }

    public function test_create_task_failed_due_to_task_title_already_exists_throw_exception(): void
    {
        $this->taskCommandService = app(TaskCommandService::class);
        Task::factory()->create(['title' => 'Test Task', 'user_id' => $this->user->id]);
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Task with this title already exists');
        $this->taskCommandService->create($this->dto, $this->user->id);
    }

    public function test_commit_transaction_failed_rollback_transaction(): void
    {
        // arrange
        $this->mock(TaskRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('countByUserId')->andReturn(0);
            $mock->shouldReceive('existsByTitleAndUserId')->andReturn(false);
            $mock->shouldReceive('create')->andThrow(new Exception('DB ERROR'));

        });
        // action
        $this->taskCommandService = app(TaskCommandService::class);
        try {
            $this->taskCommandService->create($this->dto, $this->user->id);
        } catch (Exception $e) {
            // assert
            Event::assertNotDispatched(TaskCreated::class);
            $this->assertDatabaseCount('tasks', 0);
            $this->assertEquals('DB ERROR', $e->getMessage());

            return;
        }

        $this->fail('Expected exception was not thrown');
    }
}
