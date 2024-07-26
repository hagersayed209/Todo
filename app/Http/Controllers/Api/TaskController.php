<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Auth::user()->tasks()
            ->with('category')
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->category_id, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                      ->orWhere('description', 'like', "%$search%");
                });
            })
            ->paginate(10);

        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed',
            'category_id' => 'nullable|exists:categories,id',
            'due_date' => 'nullable|date',
        ]);

        $task = Auth::user()->tasks()->create($request->all());

        return response()->json($task, 201);
    }

    public function show(Task $task)
    {
        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed',
            'category_id' => 'nullable|exists:categories,id',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->all());

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(null, 204);
    }

    public function restore($id)
    {
        $task = Task::withTrashed()->find($id);

        if ($task) {
            $task->restore();
            return response()->json($task);
        }

        return response()->json(['error' => 'Task not found'], 404);
    }
}
