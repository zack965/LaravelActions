# Laravel action pattern 
In this example, I created a simple Laravel application that allows creating a task using both a controller and a command. To make the task creation logic reusable, I encapsulated it within an action class, which can be easily utilized by both the controller and the command handler.

## The model  and the migration
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ["title", "description"];
}
```
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
```
#### As you see the model is pretty simple
### Let's see now the action class
```php
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
        /*
        after the creation of the object , you can send notification , log it , etc........
        */
        return $task;
    }
}
```
#### You can adapt the action based on your needs 
### How can you use this class in controller
```php
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
```
### How can you use this class in the command 
```php
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
```
#### in this example we did encapulate the logic of creation of a task inside a class action and we did reuse it inside the controller and the command line
#### You can adapt this example to your own needs
