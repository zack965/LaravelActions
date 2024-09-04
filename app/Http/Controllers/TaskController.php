<?php

namespace App\Http\Controllers;

use App\Actions\CreateTaskAction;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //
    public function createTask(Request $request, CreateTaskAction $createTaskAction)
    {
        $task = $createTaskAction->handle($request->only(["title", "description"]));
        return response()->json($task, 201);
    }
}
