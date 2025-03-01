<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * @return TaskCollection
     */
    public function index(): TaskCollection
    {
        $tasks = Cache::remember('tasks_index_' . Auth::user()->id, 60, function () {
            return Auth::user()->tasks;
        });

        return new TaskCollection($tasks);
    }

    /**
     * @param CreateTaskRequest $request
     * @return TaskResource
     */
    public function store(CreateTaskRequest $request): TaskResource
    {
        $createdTask = Task::query()->create([
            'user_id' => Auth::user()->id,
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'due_date' => $request->validated('due_date'),
        ]);

        Cache::forget('tasks_index_' . Auth::user()->id);

        return new TaskResource($createdTask);
    }

    /**
     * @param Task $task
     * @return TaskResource
     */
    public function show(Task $task): TaskResource
    {
        Gate::authorize('view', $task);

        return new TaskResource($task);
    }

    /**
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return TaskResource
     */
    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        Gate::authorize('update', $task);

        $task->update(
            $request->validated()
        );

        Cache::forget('tasks_index_' . Auth::user()->id);

        return new TaskResource($task);
    }

    /**
     * @param Task $task
     * @return Response
     */
    public function destroy(Task $task): Response
    {
        Gate::authorize('delete', $task);

        $task->delete();

        Cache::forget('tasks_index_' . Auth::user()->id);

        return response()->noContent();
    }
}
