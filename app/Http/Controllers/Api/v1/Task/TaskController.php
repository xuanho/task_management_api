<?php

namespace App\Http\Controllers\Api\v1\Task;

use Illuminate\Http\Request;
use App\Services\Task\TaskQueryService;
use App\Services\Task\TaskCommandService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\CreateTaskRequest;
use App\DTOs\Task\CreateTaskDTO;
use App\Http\Resources\Task\TaskResource;
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     */
    public function __construct(private TaskQueryService $taskQueryService, private TaskCommandService $taskCommandService)
    {
    }
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
        // lay task chi tiet voi get method
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // cap nhat task voi patch method
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // xoa task voi delete method
    }
}
