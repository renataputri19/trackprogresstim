<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; // Use the correct Request class
use App\Http\Controllers\KmsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\UserTaskController;
use App\Http\Controllers\AdminTaskController;
use App\Http\Controllers\UserTasksController;
use App\Http\Controllers\GanttChartController;
use App\Http\Controllers\TaskAssignmentController;
use App\Http\Controllers\TextGenerationController;
use App\Http\Controllers\UserAssignmentController;
use App\Http\Controllers\BusinessTaggingController;
use App\Http\Controllers\AdminTasksAssignmentController;
use App\Http\Controllers\NewHomepage\HomeController as NewHomepageController;
use App\Http\Controllers\SuperAdminTasksAssignmentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\MapRequestController;
use App\Http\Controllers\HaloIPController;
use App\Http\Controllers\PublicViewController;
use App\Http\Controllers\VhtsController;
use App\Http\Controllers\VhtsControllerv2;
use App\Http\Controllers\VhtsControllerv3;
use App\Http\Controllers\SettingsController;

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

// Add these routes outside of any middleware group
Route::get('/tagging', [BusinessTaggingController::class, 'index'])->name('business.tagging');
Route::post('/business', [BusinessTaggingController::class, 'store'])->name('business.store');
Route::get('/tagging/data', [BusinessTaggingController::class, 'list'])->name('business.list');

// SBR (Survei Bisnis Registrasi) Routes
Route::get('/sbr', [App\Http\Controllers\SbrController::class, 'index'])->name('sbr.index');
Route::get('/sbr/search', [App\Http\Controllers\SbrController::class, 'search'])->name('sbr.search');
Route::get('/sbr/stats', [App\Http\Controllers\SbrController::class, 'stats'])->name('sbr.stats');
Route::get('/sbr/kelurahan/{kecamatan}', [App\Http\Controllers\SbrController::class, 'getKelurahan'])->name('sbr.kelurahan');
Route::get('/sbr/{id}', [App\Http\Controllers\SbrController::class, 'show'])
    ->whereNumber('id')
    ->name('sbr.show');
Route::put('/sbr/{id}', [App\Http\Controllers\SbrController::class, 'update'])->name('sbr.update');
Route::delete('/sbr/{id}/tagging', [App\Http\Controllers\SbrController::class, 'clearTagging'])->name('sbr.clear');

// Import-related routes require authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/sbr/import', [App\Http\Controllers\SbrController::class, 'importPage'])->name('sbr.import.page');
    Route::post('/sbr/import', [App\Http\Controllers\SbrController::class, 'import'])->name('sbr.import');
    Route::post('/sbr/delete-all', [App\Http\Controllers\SbrController::class, 'deleteAll'])->name('sbr.delete.all');
    Route::get('/sbr/import/status/{id}', [App\Http\Controllers\SbrController::class, 'importStatus'])->name('sbr.import.status');
});

// Default route - using the new homepage
Route::get('/', [NewHomepageController::class, 'index'])->name('home');

// New improved homepage route - commented out as we've integrated the design into the main homepage
// Route::get('/new-home', [NewHomepageController::class, 'newWelcome'])->name('new-home');

// Documentation and Tutorials routes
Route::get('/documentation', [App\Http\Controllers\NewHomepage\DocumentationController::class, 'index'])->name('documentation.index');
Route::get('/documentation/{slug}', [App\Http\Controllers\NewHomepage\DocumentationController::class, 'show'])->name('documentation.show');
Route::get('/tutorials', [App\Http\Controllers\NewHomepage\TutorialController::class, 'index'])->name('tutorials.index');
Route::get('/tutorials/{id}', [App\Http\Controllers\NewHomepage\TutorialController::class, 'show'])->name('tutorials.show');

// Old homepage route - keeping for backward compatibility
Route::get('/old-home', function () {
    return view('home');
})->name('old-home');

// Custom login routes to override default Auth routes
Route::get('/login', function (Request $request) {
    if (Auth::check()) {
        // If the user is already logged in, check for a redirect parameter
        if ($request->has('redirect')) {
            return redirect($request->input('redirect'));
        }
        // Otherwise, redirect to /welcome
        return redirect()->route('welcome');
    }
    // Otherwise, show the new login page
    return view('new-homepage.auth.login');
})->name('login');

// Handle login form submission
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

// Other auth routes (excluding login GET/POST which we've customized)
Auth::routes(['register' => false, 'login' => false]); // Disable registration and default login routes

Route::middleware(['auth'])->group(function () {

    // New welcome route using the new homepage controller
    Route::get('/welcome', [NewHomepageController::class, 'welcome'])->name('welcome');

    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');

    // New application routes - under development
    Route::get('/employee-performance', [NewHomepageController::class, 'underDevelopment'])->name('employee-performance.index');
    Route::get('/financial-administration', [NewHomepageController::class, 'underDevelopment'])->name('financial-administration.index');

    // Old welcome route - keeping for backward compatibility
    Route::get('/old-welcome', [HomeController::class, 'welcome'])->name('old-welcome');

    Route::prefix('kms')->name('kms.')->group(function () {
        // Main index
        Route::get('/', [KmsController::class, 'index'])->name('index');

        // Division routes
        Route::get('/divisions', [KmsController::class, 'divisions'])->name('divisions.index');
        Route::get('/divisions/create', [KmsController::class, 'createDivision'])->name('divisions.create');
        Route::post('/divisions', [KmsController::class, 'storeDivision'])->name('divisions.store');
        Route::get('/divisions/{division}', [KmsController::class, 'division'])->name('division');
        Route::get('/divisions/{division}/edit', [KmsController::class, 'editDivision'])->name('divisions.edit');
        Route::put('/divisions/{division}', [KmsController::class, 'updateDivision'])->name('divisions.update');
        Route::delete('/divisions/{division}', [KmsController::class, 'destroyDivision'])->name('divisions.destroy');

        // Activity routes
        Route::get('/divisions/{division}/activities/create', [KmsController::class, 'createActivity'])->name('activities.create');
        Route::post('/divisions/{division}/activities', [KmsController::class, 'storeActivity'])->name('activities.store');
        Route::get('/divisions/{division}/activities/{activity}', [KmsController::class, 'activity'])->name('activity');
        Route::get('/activities/{activity}/edit', [KmsController::class, 'editActivity'])->name('activities.edit');
        Route::put('/activities/{activity}', [KmsController::class, 'updateActivity'])->name('activities.update');
        Route::delete('/activities/{activity}', [KmsController::class, 'destroyActivity'])->name('activities.destroy');

        // Document routes
        Route::get('/activities/{activity}/documents/create', [KmsController::class, 'createDocument'])->name('documents.create');
        Route::post('/activities/{activity}/documents', [KmsController::class, 'storeDocument'])->name('documents.store');

        // Document routes
        Route::get('/documents/{document}/edit', [KmsController::class, 'editDocument'])->name('documents.edit');
        Route::put('/documents/{document}', [KmsController::class, 'updateDocument'])->name('documents.update');
        Route::delete('/documents/{document}', [KmsController::class, 'destroyDocument'])->name('documents.destroy');
    });

    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
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


        // Route::get('tasks/gantt-chart', [AdminTasksAssignmentController::class, 'showGanttChart'])->name('tasks.gantt_chart');
        // Route::post('/admin/calendar/gantt-chart/update', [AdminTasksAssignmentController::class, 'updateGantt'])->name('gantt.update');
        // Route::get('/admin/calendar/gantt-chart', [GanttChartController::class, 'getTasks'])->name('gantt.chart');

        // Explicit routes for task management
        Route::post('gantt-tasks', [GanttChartController::class, 'store'])->name('gantt.store');
        Route::put('gantt-tasks/{id}', [GanttChartController::class, 'update'])->name('gantt.update');
        Route::delete('gantt-tasks/{id}', [GanttChartController::class, 'destroy'])->name('gantt.destroy');
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('dashboard', [UserTasksController::class, 'dashboard'])->name('dashboard');
        Route::get('tasks', [UserTasksController::class, 'index'])->name('tasks.index');
        Route::get('tasks/{task}/edit', [UserTasksController::class, 'edit'])->name('tasks.edit');
        Route::put('tasks/{task}', [UserTasksController::class, 'update'])->name('tasks.update');
        Route::delete('tasks/{task}', [UserTasksController::class, 'destroy'])->name('tasks.destroy');
    });

    // General Dashboard Route (Accessible by all authenticated users)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin dashboard view accessible by all authenticated users
    Route::get('/admin/dashboard', [AdminTasksAssignmentController::class, 'dashboard'])->name('admin.dashboard');

    // Calendar Events for Gantt chart
    Route::get('/admin/calendar/gantt-chart', [AdminTasksAssignmentController::class, 'ganttChartEvents'])->name('admin.calendar.gantt_chart');

    Route::get('/generate-text', [TextGenerationController::class, 'showForm'])->name('generate.form');
    Route::post('/generate-text', [TextGenerationController::class, 'generate'])->name('generate.text');
    // Route::post('/generate-text', [TextGenerationController::class, 'scrapp'])->name('generate.text');
});



// // Public route for calendar events
// Route::get('/calendar/events', [CalendarController::class, 'publicCalendarEvents'])->name('public.calendar.events');


// // Admin Task Assignments Routes
// Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('tasks', [AdminTasksAssignmentController::class, 'index'])->name('tasks.index');
//     Route::get('tasks/create', [AdminTasksAssignmentController::class, 'create'])->name('tasks.create');
//     Route::post('tasks', [AdminTasksAssignmentController::class, 'store'])->name('tasks.store');
//     Route::get('tasks/{task}/edit', [AdminTasksAssignmentController::class, 'edit'])->name('tasks.edit');
//     Route::put('tasks/{task}', [AdminTasksAssignmentController::class, 'update'])->name('tasks.update');
//     Route::delete('tasks/{task}', [AdminTasksAssignmentController::class, 'destroy'])->name('tasks.destroy');
//     Route::get('tasks/{task}/assign', [AdminTasksAssignmentController::class, 'assign'])->name('tasks.assign');
//     Route::post('tasks/{task}/assign', [UserAssignmentController::class, 'store'])->name('assignments.store');
//     Route::delete('assignments/{assignment}', [UserAssignmentController::class, 'destroy'])->name('assignments.destroy');
//     Route::get('assignments/{assignment}/edit', [UserAssignmentController::class, 'edit'])->name('assignments.edit');
//     Route::put('assignments/{assignment}', [UserAssignmentController::class, 'update'])->name('assignments.update');

//     // Superadmin specific routes
//     Route::get('/superadmin/tasks', [AdminTasksAssignmentController::class, 'superadminIndex'])->name('superadmin.tasks.index');
//     Route::get('/superadmin/tasks/create', [AdminTasksAssignmentController::class, 'superadminCreate'])->name('superadmin.tasks.create');
//     Route::post('/superadmin/tasks', [AdminTasksAssignmentController::class, 'superadminStore'])->name('superadmin.tasks.store');
//     Route::get('/superadmin/tasks/{task}/edit', [AdminTasksAssignmentController::class, 'superadminEdit'])->name('superadmin.tasks.edit');
//     Route::put('/superadmin/tasks/{task}', [AdminTasksAssignmentController::class, 'superadminUpdate'])->name('superadmin.tasks.update');
//     Route::delete('/superadmin/tasks/{task}', [AdminTasksAssignmentController::class, 'destroy'])->name('superadmin.tasks.destroy');



//     // Admin View Assigned Tasks (same as user view)
//     Route::get('assigned-tasks', [AdminTasksAssignmentController::class, 'assignedTasks'])->name('tasks.assigned');

//     // Fetch Calendar Events
//     Route::get('calendar/events', [AdminTasksAssignmentController::class, 'calendarEvents'])->name('calendar.events');


// });

// // User Task Routes
// Route::middleware(['auth'])->name('user.')->group(function () {
//     Route::get('tasks', [UserTasksController::class, 'index'])->name('tasks.index');
//     Route::get('tasks/{task}/edit', [UserTasksController::class, 'edit'])->name('tasks.edit');
//     Route::put('tasks/{task}', [UserTasksController::class, 'update'])->name('tasks.update');
//     Route::delete('tasks/{task}', [UserTasksController::class, 'destroy'])->name('tasks.destroy');
// });

// Route::middleware(['auth'])->group(function () {
//     Route::get('/welcome', [HomeController::class, 'welcome'])->name('welcome');
//     Route::get('dashboard', [AdminTasksAssignmentController::class, 'dashboard'])->name('dashboard');
// });



// Public routes - Unified HaloIP structure (using lowercase for consistency)
Route::get('/haloip', [HaloIPController::class, 'index'])->name('haloip.index');

// Backward compatibility redirects (redirect old uppercase URLs to lowercase)
Route::get('/haloIP', function() {
    return redirect()->route('haloip.index', request()->query());
});
Route::get('/haloIP/ticket', function() {
    return redirect()->route('haloip.index');
});
Route::get('/haloip/ticket', function() {
    return redirect()->route('haloip.index');
});
Route::get('/haloIP/map-request', function() {
    return redirect()->route('haloip.index', ['category' => 'Peta Cetak']);
});
Route::get('/haloip/map-request', function() {
    return redirect()->route('haloip.index', ['category' => 'Peta Cetak']);
});
Route::get('/map-requests', function() {
    return redirect()->route('haloip.index', ['category' => 'Peta Cetak']);
});
Route::get('/tickets/manage', function() {
    return redirect()->route('haloip.manage');
});
Route::get('/map-requests/manage', function() {
    return redirect()->route('haloip.manage', ['category' => 'Peta Cetak']);
});

// Keep old named routes for backward compatibility
Route::get('/haloIP/tickets/index', function() {
    return redirect()->route('haloip.index');
})->name('tickets.index');
Route::get('/haloIP/map-requests/index', function() {
    return redirect()->route('haloip.index', ['category' => 'Peta Cetak']);
})->name('map-requests.index');

// Public viewing routes (no authentication required) - Updated to HaloIP structure
Route::get('/haloip/public/view/{token}', [PublicViewController::class, 'viewByToken'])->name('public.view');
// Backward compatibility for old public links
Route::get('/haloIP/public/view/{token}', function($token) {
    return redirect()->route('public.view', ['token' => $token]);
});
Route::get('/public/view/{token}', function($token) {
    return redirect()->route('public.view', ['token' => $token]);
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Unified HaloIP creation route
    Route::get('/haloip/create', [HaloIPController::class, 'create'])->name('haloip.create');
    Route::post('/haloip', [HaloIPController::class, 'store'])->name('haloip.store');

    // Backward compatibility redirects for creation routes
    Route::get('/haloip/tickets/create', function() {
        return redirect('/haloip/create');
    })->name('tickets.create');
    Route::get('/haloip/map-requests/create', function() {
        return redirect('/haloip/create?category=Peta+Cetak');
    })->name('map-requests.create');
    Route::get('/tickets/create', function() {
        return redirect('/haloip/create');
    });
    Route::get('/map-requests/create', function() {
        return redirect('/haloip/create?category=Peta+Cetak');
    });

    // Backward compatibility for store routes
    Route::post('/haloip/tickets', function() {
        return redirect('/haloip/create');
    })->name('tickets.store');
    Route::post('/haloip/map-requests', function() {
        return redirect('/haloip/create?category=Peta+Cetak');
    })->name('map-requests.store');

    // Location API for dropdowns - Available to all authenticated users
    Route::get('/api/villages/{districtCode}', [HaloIPController::class, 'getVillagesByDistrict'])->name('villages.by-district');

    Route::middleware('it_staff')->group(function () {
        // Unified ticket management (using lowercase for consistency)
        Route::get('/haloip/manage', [HaloIPController::class, 'manage'])->name('haloip.manage');

        // Assignment routes - accessible to all IT staff
        Route::get('/haloip/{ticket}/assign', [HaloIPController::class, 'assign'])->name('haloip.assign');
        Route::post('/haloip/{ticket}/assign', [HaloIPController::class, 'storeAssignment'])->name('haloip.storeAssignment');

        // Status update routes - only accessible to assigned IT staff
        // Using match(['put', 'post']) to support servers that block PUT requests
        Route::get('/haloip/{ticket}/update-status', [HaloIPController::class, 'editStatus'])->name('haloip.editStatus');
        Route::match(['put', 'post'], '/haloip/{ticket}/update-status', [HaloIPController::class, 'updateStatus'])->name('haloip.updateStatus');

        // Legacy routes - kept for backward compatibility
        // Using match(['put', 'post']) to support servers that block PUT requests
        Route::get('/haloip/{ticket}', [HaloIPController::class, 'show'])->name('haloip.show');
        Route::match(['put', 'post'], '/haloip/{ticket}', [HaloIPController::class, 'update'])->name('haloip.update');
        Route::delete('/haloip/{ticket}', [HaloIPController::class, 'destroy'])->name('haloip.destroy');

        Route::get('/api/haloip/pending-count', [HaloIPController::class, 'pendingCount'])->name('haloip.pendingCount');
        Route::get('/api/haloip/tickets', [HaloIPController::class, 'getTickets'])->name('haloip.getTickets');

        // Backward compatibility routes for old ticket management (redirect uppercase to lowercase)
        Route::get('/haloIP/manage', function() {
            return redirect()->route('haloip.manage', request()->query());
        });
        Route::get('/haloIP/tickets/manage', function() {
            return redirect()->route('haloip.manage');
        })->name('tickets.manage');
        Route::get('/haloIP/map-requests/manage', function() {
            return redirect()->route('haloip.manage', ['category' => 'Peta Cetak']);
        })->name('map-requests.manage');
        Route::get('/haloIP/tickets/{ticket}', function($ticket) {
            return redirect()->route('haloip.show', ['ticket' => $ticket]);
        })->name('tickets.show');
        Route::get('/haloIP/map-requests/{mapRequest}', function($mapRequest) {
            return redirect()->route('haloip.show', ['ticket' => $mapRequest]);
        })->name('map-requests.show');
        Route::match(['put', 'post'], '/haloIP/tickets/{ticket}', function($ticket) {
            return redirect()->route('haloip.update', ['ticket' => $ticket]);
        })->name('tickets.update');
        Route::match(['put', 'post'], '/haloIP/map-requests/{mapRequest}', function($mapRequest) {
            return redirect()->route('haloip.update', ['ticket' => $mapRequest]);
        })->name('map-requests.update');
        Route::get('/api/tickets/pending-count', function() {
            return redirect()->route('haloip.pendingCount');
        })->name('tickets.pendingCount');
        Route::get('/api/map-requests/pending-count', function() {
            return redirect()->route('haloip.pendingCount');
        })->name('map-requests.pendingCount');

        // Backward compatibility for uppercase assign and update-status routes
        Route::get('/haloIP/{ticket}/assign', function($ticket) {
            return redirect()->route('haloip.assign', ['ticket' => $ticket]);
        });
        Route::post('/haloIP/{ticket}/assign', function($ticket) {
            return redirect()->route('haloip.storeAssignment', ['ticket' => $ticket]);
        });
        Route::get('/haloIP/{ticket}/update-status', function($ticket) {
            return redirect()->route('haloip.editStatus', ['ticket' => $ticket]);
        });
        Route::match(['put', 'post'], '/haloIP/{ticket}/update-status', function($ticket) {
            return redirect()->route('haloip.updateStatus', ['ticket' => $ticket]);
        });

        // Backward compatibility: support DELETE on old uppercase base path
        // Allow direct deletion via /haloIP/{ticket} to avoid method redirect issues
        Route::delete('/haloIP/{ticket}', [HaloIPController::class, 'destroy'])
            ->whereNumber('ticket');
        Route::get('/api/tickets', function() {
            return redirect()->route('haloip.getTickets');
        })->name('tickets.get');
        Route::get('/api/map-requests', function() {
            return redirect()->route('haloip.getTickets', ['category' => 'Peta Cetak']);
        })->name('map-requests.get');
    });
});

// BAHTERA Main Route (version selector page)
Route::get('/bahtera', [VhtsController::class, 'main'])->name('bahtera.main');

// BAHTERA Version 1 Routes
Route::get('/bahtera/v1', [VhtsController::class, 'index'])->name('bahtera.v1.index');
Route::post('/bahtera/v1/validate', [VhtsController::class, 'processValidation'])->name('bahtera.v1.validate');

// BAHTERA Version 2 Routes
Route::get('/bahtera/v2', [VhtsControllerv2::class, 'index'])->name('bahtera.v2.index');
Route::post('/bahtera/v2/validate', [VhtsControllerv2::class, 'processValidation'])->name('bahtera.v2.validate');

// BAHTERA Version 3 Routes (Enhanced validation with foreign guest flow logic)
Route::get('/bahtera/v3', [VhtsControllerv3::class, 'index'])->name('bahtera.v3.index');
Route::post('/bahtera/v3/validate', [VhtsControllerv3::class, 'processValidation'])->name('bahtera.v3.validate');

// Backward compatibility redirects - VHTS to BAHTERA
Route::get('/vhts', function() {
    return redirect('/bahtera');
});
Route::get('/vhtsv1', function() {
    return redirect('/bahtera/v1');
});
Route::get('/vhtsv2', function() {
    return redirect('/bahtera/v2');
});
Route::get('/vhtsv3', function() {
    return redirect('/bahtera/v3');
});
Route::get('/vhts-main', function() {
    return redirect('/bahtera');
});
Route::get('/vhts/validate', function() {
    return redirect('/bahtera/v1');
});

// This route should be placed at the end of your route definitions
Route::fallback(function () {
    return redirect('/');
});