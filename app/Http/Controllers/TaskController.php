<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{

    public function getAll(Request $request)
    {
        $user_id = $request->user()->id;
        $taskList = Task::with("user")
            ->where('user_id', $user_id)->get();

        return response()->json($taskList, 200);
    }

    public function getOne(Request $request, $id)
    {
        $task = Task::find($id);

        if (empty($task)) {
            return response()->json("La tâche n'existe pas", 404);
        }
        if ($request->user()->id !== $task->user_id) {
            return response()->json("Accès à la tâche non autorisé", 403);
        }

        return response()->json($task, 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'done' => 'required|boolean'
        ]);

        $task = Task::create([
            'title' => $request->title,
            'body' => $request->body,
            'done' => $request->done,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($task, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'body' => 'required|string',
            'done' => 'required|boolean'
        ]);

        $task = Task::find($id);
        if (empty($task)) {
            return response()->json("La tâche n'existe pas", 404);
        }
        if ($request->user()->id !== $task->user_id) {
            return response()->json("Accès à la tâche non autorisé", 403);
        }

        $task->update([
            'title' => $request->title,
            'body' => $request->body,
            'done' => $request->done,
        ]);

        $updatedTask = Task::find($id);
        return response()->json($updatedTask, 200);
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::find($id);

        if (empty($task)) {
            return response()->json("La tâche n'existe pas", 404);
        }
        if ($request->user()->id !== $task->user_id) {
            return response()->json("Accès à la tâche non autorisé", 403);
        }

        $task->delete();

        return response()->json($task, 200);
    }
}
