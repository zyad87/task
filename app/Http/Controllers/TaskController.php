<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // لعرض جميع المهام
    public function index()
    {
        return Task::all();
    }

    // لإنشاء مهمة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tasks|max:255',
            'description' => 'nullable',
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);

        $task = Task::create($request->all());

        return response()->json($task, 201);
    }

    // لتحديث مهمة موجودة
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $request->validate([
            'name' => 'required|max:255|unique:tasks,name,' . $id,
            'description' => 'nullable',
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);

        $task->update($request->all());

        return response()->json($task, 200);
    }

    // لحذف مهمة موجودة
    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully'], 200);
    }
}
