<?php

namespace App\Services\Task;

use App\DTOs\Task\CreateTaskDTO;
use App\DTOs\Task\TaskMailDTO;
use App\DTOs\Task\UpdateTaskDTO;
use App\Events\TaskCreated;
use App\Exceptions\ApiException;
use App\Interfaces\Mail\MailServiceInterface;
use App\Interfaces\TaskQueueInterface;
use App\Models\Task\Task;
use App\Repositories\Task\TaskRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TaskCommandService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private TaskRepositoryInterface $taskRepository,
        private TaskQueueInterface $queue,
        private MailServiceInterface $mailService)
    {
        //
    }

    public function create(CreateTaskDTO $dto, int $user_id): Task
    {
        DB::beginTransaction();
        try {
            $this->ensureTaskLimit($user_id);
            $this->ensureTaskTitleUnique($dto->getTitle(), $user_id);
            $dto->setUserId($user_id);
            $task = $this->taskRepository->create($dto->toArray());
            DB::commit();
            // event
            event(new TaskCreated($task->id));
            return $task;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function ensureTaskLimit(int $user_id): void
    {
        if ($this->taskRepository->countByUserId($user_id) >= 10) {
            throw new ApiException('You have reached the maximum number of tasks', 'TASK_MAX_NUMBER_OF_TASKS_REACHED', 403);
        }
    }

    private function ensureTaskTitleUnique(string $title, int $user_id): void
    {
        if ($this->taskRepository->existsByTitleAndUserId($title, $user_id)) {
            throw new ApiException('Task with this title already exists', 'TASK_TITLE_ALREADY_EXISTS', 409);
        }
    }

    public function update(UpdateTaskDTO $dto, int $id, int $user_id): Task
    {
        DB::beginTransaction();
        try {
            $task = $this->taskRepository->findByIdOrFail($id);
            $this->validateUpdate($dto, $task, $user_id);
            $dto->setUserId($user_id);
            $updatedTask = $this->taskRepository->updateById($id, $dto->toArray());
            // async email
            $this->queue->sendTaskUpdatedEmail($updatedTask->id);
            DB::commit();

            return $updatedTask;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function delete(int $id, int $user_id): void
    {
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
}
