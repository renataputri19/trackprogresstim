<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserTaskController;
use App\Http\Controllers\AdminTaskController;
use App\Http\Controllers\UserTasksController;
use App\Http\Controllers\TaskAssignmentController;
use App\Http\Controllers\UserAssignmentController;
use App\Http\Controllers\AdminTasksAssignmentController;
use App\Http\Controllers\SuperAdminTasksAssignmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::get('/tasks/events', [TaskController::class, 'events'])->name('tasks.events');

// Admin Task Assignments Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('tasks', [AdminTasksAssignmentController::class, 'index'])->name('tasks.index');
    Route::get('tasks/create', [AdminTasksAssignmentController::class, 'create'])->name('tasks.create');
    Route::post('tasks', [AdminTasksAssignmentController::class, 'store'])->name('tasks.store');
    Route::get('tasks/{task}/edit', [AdminTasksAssignmentController::class, 'edit'])->name('tasks.edit');
    Route::put('tasks/{task}', [AdminTasksAssignmentController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{task}', [AdminTasksAssignmentController::class, 'destroy'])->name('tasks.destroy');
    Route::get('tasks/{task}/assign', [AdminTasksAssignmentController::class, 'assign'])->name('tasks.assign');
    Route::post('tasks/{task}/assign', [UserAssignmentController::class, 'store'])->name('assignments.store');
    Route::delete('assignments/{assignment}', [UserAssignmentController::class, 'destroy'])->name('assignments.destroy');
    Route::get('assignments/{assignment}/edit', [UserAssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('assignments/{assignment}', [UserAssignmentController::class, 'update'])->name('assignments.update');

    // Super Admin View (all tasks and assignments)
    Route::get('superadmin/tasks', [AdminTasksAssignmentController::class, 'superadminIndex'])->name('superadmin.tasks.index');
    
    // Admin View Assigned Tasks (same as user view)
    Route::get('assigned-tasks', [AdminTasksAssignmentController::class, 'assignedTasks'])->name('tasks.assigned');
    
    // Fetch Calendar Events
    Route::get('calendar/events', [AdminTasksAssignmentController::class, 'calendarEvents'])->name('calendar.events');
    Route::get('dashboard', [AdminTasksAssignmentController::class, 'dashboard'])->name('dashboard');
    
});

// User Task Routes
Route::middleware(['auth'])->name('user.')->group(function () {
    Route::get('tasks', [UserTasksController::class, 'index'])->name('tasks.index');
    Route::get('tasks/{task}/edit', [UserTasksController::class, 'edit'])->name('tasks.edit');
    Route::put('tasks/{task}', [UserTasksController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{task}', [UserTasksController::class, 'destroy'])->name('tasks.destroy');
});

