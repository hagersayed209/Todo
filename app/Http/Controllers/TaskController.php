<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
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

        $categories = Auth::user()->categories()->get();

        return view('tasks.index', compact('tasks', 'categories'));
    }

    public function create()
    {
        $categories = Auth::user()->categories()->get();
        return view('tasks.create', compact('categories'));
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

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $categories = Auth::user()->categories()->get();
        return view('tasks.edit', compact('task', 'categories'));
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

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
    public function trash()
    {
        $tasks = Task::onlyTrashed()->where('user_id', Auth::id())->paginate(10);
        $categories = Auth::user()->categories()->get();

        return view('tasks.trash', compact('tasks', 'categories'));
    }
    


    public function restore($id)
    {
        $task = Task::withTrashed()->find($id);
        if ($task) {
            $task->restore();
            return redirect()->route('tasks.trash')->with('success', 'Task restored successfully.');
        }
        return redirect()->route('tasks.trash')->with('error', 'Task not found.');
    }

    public function forceDelete(Task $task)
    {
        if ($task->trashed()) {
            $task->forceDelete();
            return redirect()->route('tasks.trash')->with('success', 'Task permanently deleted.');
        }
        return redirect()->route('tasks.index')->with('error', 'Task not found or not in trash.');
    }
    
}