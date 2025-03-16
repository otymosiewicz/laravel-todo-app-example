<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SharedTaskController extends Controller
{
    public function show(string $hash)
    {
        try {
            $task = Task::query()->where('hash', $hash)->firstOrFail();

            return view('shared_task', ['task' => $task]);
        } catch (ModelNotFoundException) {
            abort(404, 'Task not found or invalid link');
        }
    }
}
