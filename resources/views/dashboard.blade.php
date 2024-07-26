@extends('layouts.app')

@section('content')

<div class="container mt-5">
        <div class="panel-body">
            <div class="col-12 text-center">
                <h1 class="display-4">Welcome to the To-Do List Application</h1>
                <p class="lead">Manage your tasks efficiently and effortlessly.</p>
                <div class="mt-4">
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-lg">Create Task</a>
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-lg">View All Tasks</a>
                    <a href="{{ route('categories.index') }}" class="btn btn-info btn-lg">View Categories</a>
                </div>
            </div>
        </div>
    </div>
  
</div>
@endsection