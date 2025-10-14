<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // عرض جميع المهام
    public function index()
    {
        return response()->json(Task::orderBy('order', 'asc')->get());
    }

    // إنشاء مهمة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'done'  => false,
            'order' => Task::max('order') + 1,
        ]);

        return response()->json($task, 201);
    }

    // تحديث مهمة
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'done'  => 'sometimes|boolean',
            'order' => 'sometimes|integer|nullable',
        ]);

        $task->update($request->only(['title', 'done', 'order']));

        return response()->json($task);
    }

    // حذف مهمة
 public function destroy(Task $task)
{
    $task->delete();
return response()->json(['message' => 'Task deleted']);
}


    // إعادة ترتيب المهام (اختياري)
    public function reorder(Request $request)
    {
        $request->validate([
            'tasks' => 'required|array',
        ]);

        foreach ($request->tasks as $item) {
            Task::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['message' => 'Tasks reordered successfully']);
    }
}
