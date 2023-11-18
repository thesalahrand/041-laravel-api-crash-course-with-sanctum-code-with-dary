<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(TaskResource::collection(Task::with('user')->where('user_id', auth()->id())->get()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        $task = Task::create([...$validated, 'user_id' => auth()->id()]);

        return $this->success(new TaskResource($task));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if (Gate::denies('show-update-delete-task', $task)) {
            return $this->error(null, 403, 'You\'re not authorized to show this task');
        }

        return $this->success(new TaskResource($task));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        if (Gate::denies('show-update-delete-task', $task)) {
            return $this->error(null, 403, 'You\'re not authorized to update this task');
        }

        $validated = $request->validated();

        $task->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
        ]);

        return $this->success(new TaskResource($task));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
