<?php

namespace Tests\Unit\Services;

use App\DTOs\Task\CreateTaskDTO;
use App\Exceptions\ApiException;
use App\Models\Task\Task;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Services\Task\TaskCommandService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TaskCommandServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $userId = 1;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        $this->dto = CreateTaskDTO::fromArray([
            'id' => 1,
            'title' => 'Test Task',
            'description' => 'Test Description',
            'user_id' => $this->userId,
            'status_id' => 1,
        ]);
        $this->repo = $this->mock(TaskRepositoryInterface::class);
        $this->service = app(TaskCommandService::class);
    }

    public function test_create_task_success()
    {
        $this->repo->shouldReceive('countByUserId')->once()->andReturn(0);
        $this->repo->shouldReceive('existsByTitleAndUserId')->once()->andReturn(false);
        $this->repo->shouldReceive('create')->once()->andReturnUsing(function () {
            $task = new Task;
            $task->id = 1;
            $task->title = 'Test Task';

            return $task;
        });

        $task = $this->service->create($this->dto, $this->userId);
        $this->assertEquals(1, $task->id);
    }

    public function test_create_task_fail_due_to_duplicate_title()
    {
        $this->repo->shouldReceive('countByUserId')->once()->andReturn(0);
        $this->repo->shouldReceive('existsByTitleAndUserId')->once()->andReturn(true);
        $this->repo->shouldNotReceive('create');
        $this->expectException(ApiException::class);
        $task = $this->service->create($this->dto, $this->userId);

    }

    public function test_create_task_fail_due_to_limit()
    {
        $this->repo->shouldReceive('countByUserId')->once()->andReturn(5);
        $this->repo->shouldNotReceive('existsByTitleAndUserId');
        $this->repo->shouldNotReceive('create');
        $this->expectException(ApiException::class);
        $task = $this->service->create($this->dto, $this->userId);
    }

    public function test_repository_throw_exception()
    {
        $this->repo->shouldReceive('countByUserId')->once()->andReturn(0);
        $this->repo->shouldReceive('existsByTitleAndUserId')->once()->andReturn(false);
        $this->repo->shouldReceive('create')->once()->andThrow(new Exception('DB ERROR'));
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('DB ERROR');
        $this->service->create($this->dto, $this->userId);
    }
}
