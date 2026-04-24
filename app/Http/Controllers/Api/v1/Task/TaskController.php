<?php

namespace App\Http\Controllers\Api\v1\Task;

use App\DTOs\Task\CreateTaskDTO;
use App\DTOs\Task\UpdateTaskDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\Task\TaskResource;
use App\Services\Task\TaskCommandService;
use App\Services\Task\TaskQueryService;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(private TaskQueryService $taskQueryService, private TaskCommandService $taskCommandService) {}

    public function index()
    {
        $tasks = $this->taskQueryService->getListTasks();

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTaskRequest $request)
    {
        $dto = CreateTaskDTO::fromArray($request->validated());
        $task = $this->taskCommandService->create($dto, auth()->user()->id);

        return new TaskResource($task->load('user'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = $this->taskQueryService->findByIdOrFail($id);

        return new TaskResource($task->load('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        $dto = UpdateTaskDTO::fromArray($request->validated());
        $task = $this->taskCommandService->update($dto, $id, auth()->user()->id);

        return new TaskResource($task->load('user'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->taskCommandService->delete($id, auth()->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully',
        ], 200);

    }
}
