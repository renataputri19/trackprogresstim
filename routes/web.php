<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarController;
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

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Public route for calendar events
Route::get('/calendar/events', [CalendarController::class, 'publicCalendarEvents'])->name('public.calendar.events');


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

    // Superadmin specific routes
    Route::get('/superadmin/tasks', [AdminTasksAssignmentController::class, 'superadminIndex'])->name('superadmin.tasks.index');
    Route::get('/superadmin/tasks/create', [AdminTasksAssignmentController::class, 'superadminCreate'])->name('superadmin.tasks.create');
    Route::post('/superadmin/tasks', [AdminTasksAssignmentController::class, 'superadminStore'])->name('superadmin.tasks.store');
    Route::get('/superadmin/tasks/{task}/edit', [AdminTasksAssignmentController::class, 'superadminEdit'])->name('superadmin.tasks.edit');
    Route::put('/superadmin/tasks/{task}', [AdminTasksAssignmentController::class, 'superadminUpdate'])->name('superadmin.tasks.update');
    Route::delete('/superadmin/tasks/{task}', [AdminTasksAssignmentController::class, 'destroy'])->name('superadmin.tasks.destroy');



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


Route::get('register', function() {
    return redirect('/');
});

Auth::routes(['register' => false]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// This route should be placed at the end of your route definitions
Route::fallback(function () {
    return redirect('/');
});