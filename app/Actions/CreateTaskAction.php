<?php

namespace App\Actions;

use App\Models\Task;

class CreateTaskAction
{
    public function handle(array $data): Task
    {
        $task = Task::create([
            'title' => $data["title"],
            'description' => $data["description"],
        ]);
        return $task;
    }
}
