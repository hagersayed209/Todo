@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Trashed Tasks</h1>
        <a href="{{ route('tasks.index') }}" class="btn btn-primary mb-3">Back to Tasks</a>

        <form method="GET" action="{{ route('tasks.trash') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search by title or description" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
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
                @forelse ($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->status }}</td>
                        <td>{{ $task->category ? $task->category->name : 'No Category' }}</td>
                        <td>{{ $task->due_date }}</td>
                        <td>
    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning btn-sm">Edit</a>

    <form action="{{ route('tasks.restore', $task) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('POST')
        <button type="submit" class="btn btn-success btn-sm">Restore</button>
    </form>

    <form action="{{ route('tasks.forceDelete', $task) }}"  method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Delete Permanently</button>
    </form>
</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No trashed tasks found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $tasks->links() }}
    </div>
@endsection
