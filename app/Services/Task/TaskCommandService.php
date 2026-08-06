<?php

namespace App\Services\Task;

use App\DTOs\Task\CreateTaskDTO;
use App\DTOs\Task\TaskMailDTO;
use App\DTOs\Task\UpdateTaskDTO;
use App\DTOs\TaskHistory\CreateTaskHistoryDTO;
use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use App\Exceptions\ApiException;
use App\Interfaces\Auth\PermissionServiceInterface;
use App\Interfaces\Mail\MailServiceInterface;
use App\Models\Task\Task;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Repositories\TaskHistory\TaskHistoryRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class TaskCommandService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private TaskRepositoryInterface $taskRepository,
        private MailServiceInterface $mailService,
        private TaskHistoryRepository $taskHistoryRepository,
        private PermissionServiceInterface $permissionService) {}

    public function create(CreateTaskDTO $dto, int $user_id): Task
    {
        if (! $this->permissionService->canCreateTask($user_id, $dto->getProjectId())) {
            throw new ApiException('Forbidden', 'FORBIDDEN', 403);
        }

        try {
            return DB::transaction(function () use ($dto, $user_id) {
                $this->ensureTaskLimit($user_id);
                $this->ensureTaskTitleUnique($dto->getTitle(), $user_id);
                $dto->setUserId($user_id);
                $task = $this->taskRepository->create($dto->toArray());
                event(new TaskCreated($task->id));

                return $task;
            });
        } catch (QueryException $e) {
            if (DatabaseErrorDetector::isUniqueViolation($e)) {
                throw new ApiException('Task with this title already exists', 'TASK_TITLE_ALREADY_EXISTS', 422);
            }
            throw $e;
        }

    }

    private function ensureTaskLimit(int $user_id): void
    {
        if ($this->taskRepository->countByUserId($user_id) >= 5) {
            throw new ApiException('You have reached the maximum number of tasks', 'TASK_MAX_NUMBER_OF_TASKS_REACHED', 403);
        }
    }

    private function ensureTaskTitleUnique(string $title, int $user_id): void
    {
        if ($this->taskRepository->existsByTitleAndUserId($title, $user_id)) {
            throw new ApiException('Task with this title already exists', 'TASK_TITLE_ALREADY_EXISTS', 422);
        }
    }

    public function update(UpdateTaskDTO $dto, int $id, int $user_id): Task
    {
        $task = $this->taskRepository->findByIdOrFail($id);
        if (! $this->permissionService->canUpdateTask($user_id, $task)) {
            throw new ApiException('Forbidden', 'FORBIDDEN', 403);
        }

        return DB::transaction(function () use ($dto, $id, $user_id, $task) {
            $this->validateUpdate($dto, $task, $user_id);
            $dto->setUserId($user_id);
            $updatedTask = $this->taskRepository->updateById($id, $dto->toArray());
            $this->recordTaskHistory($task, $updatedTask, $task->user->id);
            // event
            event(new TaskUpdated($task->id));

            return $updatedTask;
        });
    }

    public function delete(int $id, int $user_id): void
    {
        $task = $this->taskRepository->findByIdOrFail($id);
        if (! $this->permissionService->canDeleteTask($user_id, $task)) {
            throw new ApiException('Forbidden', 'FORBIDDEN', 403);
        }
        $this->taskRepository->deleteById($id, $user_id);
    }

    public function handleSendEmail(int $taskId, string $type): void
    {
        $task = $this->taskRepository->findWithUser($taskId);
        // business logic
        $mailDTO = new TaskMailDTO($task->title, $task->description, $task->user->name, $task->status?->name);
        if ($type == 'created') {
            $this->mailService->sendTaskCreated($task->user->email, $mailDTO);
        } elseif ($type == 'updated') {
            $this->mailService->sendTaskUpdated($task->user->email, $mailDTO);

        }
    }

    public function validateUpdate(UpdateTaskDTO $dto, Task $task, int $user_id): void
    {
        if ($dto->getTitle() !== $task->title) {
            $this->ensureTaskTitleUnique($dto->getTitle(), $user_id);
        }

    }

    private function recordTaskHistory(Task $task, Task $updatedTask, int $userId): void
    {
        $historyDTO = new CreateTaskHistoryDTO(
            taskId: $task->id,
            oldStatusId: $task->status_id,
            newStatusId: $updatedTask->status_id,
            changedBy: $userId);
        $this->taskHistoryRepository->logStatusChange($historyDTO);

    }
}
