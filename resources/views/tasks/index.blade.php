@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tasks</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Add Task</a>
        <a href="{{ route('tasks.trash') }}" class="btn btn-secondary mb-3">View Trash</a>
        <a href="{{ route('tasks.index') }}" class="btn btn-info mb-3">View All Tasks</a>

        <form method="GET" action="{{ route('tasks.index') }}" class="mb-3">
            <!-- Filter form here -->
        </form>

        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Category</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->status }}</td>
                        <td>{{ $task->category ? $task->category->name : 'No Category' }}</td>
                        <td>{{ $task->due_date }}</td>
                        <td>
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning btn-sm">Edit</a>

                            @if ($task->trashed())
                                <form action="{{ route('tasks.restore', $task) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                </form>
                                <form action="{{ route('tasks.forceDelete', $task) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete Permanently</button>
                                </form>
                            @else
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                @if ($task->status == 'pending')
                                    <form action="{{ route('tasks.complete', $task) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">Done</button>
                                    </form>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $tasks->links() }}
    </div>
@endsection
