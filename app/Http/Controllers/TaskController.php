<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function index(Request $request): TaskCollection
    {
        return new TaskCollection(Task::all());
    }

    public function show(Request $request, Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    public function store(StoreTaskRequest $request): TaskResource
    {
        $validated = $request->validated();

        $task = Task::create($validated);

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $validated = $request->validated();
        
        $task->update($validated);

        return new TaskResource($task);
    }

    public function destroy(Request $request, Task $task): Response
    {
        $task->delete();
        return response()->noContent();
    }
}
