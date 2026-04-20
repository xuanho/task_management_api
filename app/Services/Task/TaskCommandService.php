<?php

namespace App\Services\Task;

use App\Repositories\Task\TaskRepository;
use App\DTOs\Task\CreateTaskDTO;
use App\Http\Resources\Task\TaskResource;
use App\Exceptions\UnauthorizedException;
use App\Models\Task\Task;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\DB;
class TaskCommandService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private TaskRepository $taskRepository)
    {
        //
    }
    public function create(CreateTaskDTO $dto, int $user_id): Task
    {
        DB::beginTransaction();
        try {
            $this->ensureTaskLimit($user_id);
            $this->ensureTaskTitleUnique($dto->getTitle(),$user_id);
            $dto->setUserId($user_id);
            $task = $this->taskRepository->create($dto->toArray());
            DB::commit();
            return $task;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(),$e->getErrorCode(), $e->getStatusCode());
        }
    }
    private function ensureTaskLimit(int $user_id): void
    {
        if($this->taskRepository->countByUserId($user_id) >= 10){
            throw new ApiException('You have reached the maximum number of tasks','TASK_MAX_NUMBER_OF_TASKS_REACHED', 403);
        }
    }
    private function ensureTaskTitleUnique(string $title, int $user_id): void
    {
        if($this->taskRepository->existsByTitleAndUserId($title,$user_id)){
            throw new ApiException('Task with this title already exists','TASK_TITLE_ALREADY_EXISTS', 409);
        }
    }
}
