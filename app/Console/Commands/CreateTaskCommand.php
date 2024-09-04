<?php

namespace App\Console\Commands;

use App\Actions\CreateTaskAction;
use App\Models\Task;
use Illuminate\Console\Command;

class CreateTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-task-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Task';

    protected CreateTaskAction $createTaskAction;

    public function __construct(CreateTaskAction $createTaskAction)
    {
        parent::__construct();
        $this->createTaskAction = $createTaskAction;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        // Ask for the task title
        $title = $this->ask('Enter the task title');

        // Ask for the task description
        $description = $this->ask('Enter the task description');

        // Create a new task
        $task = $this->createTaskAction->handle([
            'title' => $title,
            'description' => $description,
        ]);

        // Output a success message
        $this->info('Task created successfully with ID: ' . $task->id);
    }
}
