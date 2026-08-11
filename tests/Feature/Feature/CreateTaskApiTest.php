<?php

namespace Tests\Feature\Feature;

use App\Enums\Auth\PermissionEnum;
use App\Events\TaskCreated;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Auth\RolePermission;
use App\Models\Project\Project;
use App\Models\Project\ProjectMember;
use App\Models\Task\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateTaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->project = Project::factory()->create();
        $role = Role::factory()->admin()->create();
        $permission = Permission::query()->create([
            'name' => PermissionEnum::TASK_CREATE->value,
            'description' => 'Create task permission',
        ]);
        RolePermission::query()->create([
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);
        ProjectMember::factory()->for($this->user)->for($this->project)->for($role)->create();
        Event::fake();

    }

    protected function asAuthenticatedApiUser(User $user): self
    {
        $token = JWTAuth::claims(['type' => 'access'])->fromUser($user);

        return $this->actingAs($user, 'api')->withHeader('Authorization', 'Bearer '.$token);
    }

    public function test_create_task_api_success(): void
    {
        $status = TaskStatus::factory()->create();
        $response = $this->asAuthenticatedApiUser($this->user)->postJson('/api/v1/task', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => $status->id,
            'project_id' => $this->project->id,
            'assigned_to' => $this->user->id,
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
            'user_id' => $this->user->id,
        ]);
        Event::assertDispatched(TaskCreated::class);
    }

    public function test_event_not_dispatched_when_task_creation_fails(): void
    {
        Task::factory()->create([
            'title' => 'Test Task',
            'user_id' => $this->user->id,
        ]);

        $response = $this->asAuthenticatedApiUser($this->user)->postJson('/api/v1/task', [
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

        $response = $this->asAuthenticatedApiUser($this->user)->postJson('/api/v1/task', [
            'description' => 'Test Description',
            'status_id' => 1,
            'project_id' => $this->project->id,
            'assigned_to' => $this->user->id,
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
        Task::factory()->create([
            'title' => 'Test Task',
            'user_id' => $this->user->id,
            'project_id' => $this->project->id,
            'assigned_to' => $this->user->id,
        ]);
        $response = $this->asAuthenticatedApiUser($this->user)->postJson('/api/v1/task', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => 1,
            'project_id' => $this->project->id,
            'assigned_to' => $this->user->id,
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
        $response = $this->asAuthenticatedApiUser($this->user)->postJson('/api/v1/task', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => 999, // invalid status_id
            'project_id' => $this->project->id,
            'assigned_to' => $this->user->id,
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
