<?php

namespace Tests\Unit\Services;

use App\DTOs\Task\CreateTaskDTO;
use App\Events\TaskCreated;
use App\Exceptions\ApiException;
use App\Models\Task\Task;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Services\Task\TaskCommandService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Mockery;

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
            'status_id' => 1
        ]);
    }

    public function test_create_task_success()
    {
        /** @var MockInterface $mock */
        $this->mock(TaskRepositoryInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('countByUserId')->once()->andReturn(0);
            $mock->shouldReceive('existsByTitleAndUserId')->once()->andReturn(false);
            $mock->shouldReceive('create')->once()->andReturnUsing( function() {
                $task = new Task();
                $task->id = 1;
                $task->title = 'Test Task';
                return $task;
            });

        });

        $service = app(TaskCommandService::class); 
        $task = $service->create($this->dto, $this->userId);
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals(1, $task->id);
        Event::assertDispatched(TaskCreated::class);
    }

    public function test_create_task_fail_due_to_duplicate_title()
    {
        $this->mock(TaskRepositoryInterface::class, function ($mock) {
            $mock->shouldReceive('countByUserId')->once()->andReturn(0);
            $mock->shouldReceive('existsByTitleAndUserId')->once()->andReturn(true);
            $mock->shouldNotReceive('create');
        });

        $service = app(TaskCommandService::class); 
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Task with this title already exists');
        $task = $service->create($this->dto, $this->userId);
        Event::assertNotDispatched(TaskCreated::class);
        
    }

    public function test_create_task_fail_due_to_limit()
    {
        $this->mock(TaskRepositoryInterface::class, function ($mock)
        {
            $mock->shouldReceive('countByUserId')->once()->andReturn(5);
            $mock->shouldNotReceive('existsByTitleAndUserId');
            $mock->shouldNotReceive('create');

        });

        $service = app(TaskCommandService::class); 
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('You have reached the maximum number of tasks');
        $task = $service->create($this->dto, $this->userId);
        Event::assertNotDispatched(TaskCreated::class);
    }

    public function test_dto_is_mutated_with_user_id()
    {
        $this->mock(TaskRepositoryInterface::class, function ($mock)
        {
            $mock->shouldReceive('countByUserId')->once()->andReturn(0);
            $mock->shouldReceive('existsByTitleAndUserId')->once()->andReturn(false);
            $mock->shouldReceive('create')->with(Mockery::on(function($data) {
                return isset($data['user_id']) && $data['user_id'] == 1;
            }))->andReturnUsing(function() {
                $task = new Task();
                $task->id = 1;
                return $task;
            });

        });

        $service = app(TaskCommandService::class); 
        $task = $service->create($this->dto, $this->userId);
    }

    public function test_event_contains_correct_task_id()
    {
        $this->mock(TaskRepositoryInterface::class, function($mock) {
            $mock->shouldReceive('countByUserId')->once()->andReturn(0);
            $mock->shouldReceive('existsByTitleAndUserId')->once()->andReturn(false);
            $mock->shouldReceive('create')->once()->andReturnUsing(function() {
                $task = new Task();
                $task->id = 1;
                return $task;
            });
        });

        $service = app(TaskCommandService::class);
        $task = $service->create($this->dto, $this->userId);
        Event::assertDispatched(TaskCreated::class, function($event) use($task) {
            return $event->taskId === $task->id;
        });
    }

    public function test_repository_throw_exception() 
    {
        $this->mock(TaskRepositoryInterface::class, function($mock) {
            $mock->shouldReceive('countByUserId')->andReturn(0);
            $mock->shouldReceive('existsByTitleAndUserId')->andReturn(false);
            $mock->shouldReceive('create')->andThrow(new Exception('DB ERROR'));
        });

        $service = app(TaskCommandService::class);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('DB ERROR');
        $service->create($this->dto, $this->userId);

        Event::assertNotDispatched(TaskCreated::class);

    }

    public function test_repository_return_null() 
    {
        $this->mock(TaskRepositoryInterface::class, function($mock) {
            $mock->shouldReceive('countByUserId')->andReturn(0);
            $mock->shouldReceive('existsByTitleAndUserId')->andReturn(false);
            $mock->shouldReceive('create')->andReturn(null);
        });

        $service = app(TaskCommandService::class);
        $this->expectException(Exception::class);
        $service->create($this->dto, $this->userId);

        Event::assertNotDispatched(TaskCreated::class);

    }
}
