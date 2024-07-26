<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Apply middleware to protect routes
Route::middleware(['auth'])->group(function () {
    // Define the route for viewing trash
    Route::get('/tasks/trash', [TaskController::class, 'trash'])->name('tasks.trash');
    
    // Other task routes
    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{task}/restore', [TaskController::class, 'restore'])->name('tasks.restore');
    Route::delete('tasks/{task}/force', [TaskController::class, 'forceDelete'])->name('tasks.forceDelete');
    Route::patch('tasks/{task}/complete', [TaskController::class, 'markAsCompleted'])->name('tasks.complete');
});


// Protect the category routes with authentication middleware
Route::resource('categories', CategoryController::class)->middleware('auth');

require __DIR__.'/auth.php';
