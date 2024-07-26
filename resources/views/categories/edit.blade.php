@extends('layouts.app')

@section('content')
    <div class="container">
    <h1>Edit Category</h1>

<!-- Display validation errors if any -->
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('categories.update', $category->id) }}" method="POST" class="mb-3">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Update Category</button>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back to Categories</a>
</form>

@endsection
