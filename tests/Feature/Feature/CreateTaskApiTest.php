<?php

namespace Tests\Feature\Feature;

use App\Events\TaskCreated;
use App\Models\Task\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateTaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public function test_create_task_api_success(): void
    {
        $user = User::factory()->create();
        $status = TaskStatus::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/task', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => $status->id,

        ]);
        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'status_id',
                    'user_id',
                    'created_at',
                    'updated_at',
                ],
            ]);
        // assert DB
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => $status->id,
            'user_id' => $user->id,
        ]);
        Event::assertDispatched(TaskCreated::class);
    }

    public function test_event_not_dispatched_when_task_creation_fails(): void
    {
        $user = User::factory()->create();
        Task::factory()->create([
            'title' => 'Test Task',
            'user_id' => $user->id,
        ]);
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/task', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => 1,
        ]);
        $response->assertStatus(422);
        Event::assertNotDispatched(TaskCreated::class);
    }

    public function test_unauthenticated_user_cannot_create_task(): void
    {
        $response = $this->postJson('/api/v1/task', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => 1,
        ]);
        $response->assertStatus(401);
    }

    public function test_validation_fail_due_to_missing_title(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/task', [
            'description' => 'Test Description',
            'status_id' => 1,
        ]);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'The title field is required.',
                    'details' => [
                        'title' => ['The title field is required.'],
                    ],
                ],
            ]);
    }

    public function test_validation_fail_due_to_duplicate_title(): void
    {
        $user = User::factory()->create();
        Task::factory()->create([
            'title' => 'Test Task',
            'user_id' => $user->id,
        ]);
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/task', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => 1,
        ]);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'TASK_TITLE_ALREADY_EXISTS',
                    'message' => 'Task with this title already exists',
                ],
            ]);
    }

    public function test_validation_fail_due_to_invalid_data(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/task', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => 999, // invalid status_id
        ]);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'The selected status id is invalid.',
                    'details' => [
                        'status_id' => ['The selected status id is invalid.'],
                    ],
                ],
            ]);
    }
}
