<?php

namespace App\Http\Controllers;

use App\Models\SbrBusiness;
use App\Models\LaksamanaSubmission;
use App\Models\LaksamanaUser;
use Illuminate\Http\Request;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use OpenSpout\Reader\CSV\Reader as CsvReader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Jobs\ProcessSbrImport;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SbrBusinessesExport;

class SbrController extends Controller
{
    /**
     * Display the main SBR tagging page
     */
    public function index(Request $request)
    {
        // Get distinct kecamatan for filter dropdown
        $kecamatanList = SbrBusiness::getDistinctKecamatan();

        // Compute Top 5 Contributors (registered Laksamana users only)
        $top = LaksamanaSubmission::select('laksamana_user_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('laksamana_user_id')
            ->groupBy('laksamana_user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $users = LaksamanaUser::whereIn('id', $top->pluck('laksamana_user_id'))
            ->get()
            ->keyBy('id');

        $leaderboard = $top->map(function ($row) use ($users) {
            return [
                'user' => $users[$row->laksamana_user_id] ?? null,
                'count' => $row->total,
            ];
        });

        return view('laksamana.index', compact('kecamatanList', 'leaderboard'));
    }

    /**
     * Display authenticated user's dashboard with their submissions
     */
    public function dashboard(Request $request)
    {
        if (!Auth::guard('laksamana')->check()) {
            return redirect()->route('laksamana.login');
        }

        $userId = Auth::guard('laksamana')->id();
        // Get distinct business IDs the user has submitted
        $businessIds = LaksamanaSubmission::where('laksamana_user_id', $userId)
            ->pluck('sbr_business_id')
            ->unique()
            ->values();

        $perPage = (int) $request->get('per_page', 20);
        $perPage = max(1, min($perPage, 100));

        $businessQuery = SbrBusiness::whereIn('id', $businessIds);
        $businesses = $businessQuery->orderBy('nama_usaha')->paginate($perPage);
        $businesses->appends(['per_page' => $perPage]);

        $stats = [
            'total_my_entries' => count($businessIds),
            'finalized' => SbrBusiness::whereIn('id', $businessIds)->whereIn('status', ['aktif', 'tutup'])->count(),
            'editable' => SbrBusiness::whereIn('id', $businessIds)->whereNull('status')->count(),
        ];

        return view('laksamana.dashboard', compact('businesses', 'stats', 'perPage'));
    }

    /**
     * Return Top 5 Contributors as JSON for optional dynamic use.
     */
    public function leaderboard()
    {
        $top = LaksamanaSubmission::select('laksamana_user_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('laksamana_user_id')
            ->groupBy('laksamana_user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $users = LaksamanaUser::whereIn('id', $top->pluck('laksamana_user_id'))
            ->get()
            ->keyBy('id');

        $data = $top->map(function ($row) use ($users) {
            $user = $users[$row->laksamana_user_id] ?? null;
            return [
                'name' => $user?->name ?? 'Anonim',
                'email' => $user?->email,
                'count' => $row->total,
            ];
        });

        return response()->json(['data' => $data]);
    }

    /**
     * Public leaderboard and statistics page
     */
    public function leaderboardPage()
    {
        // Pagination controls
        $perPage = (int) request()->get('per_page', 25);
        $perPage = max(1, min($perPage, 100));
        $usersPage = (int) request()->get('users_page', 1);
        $usersPage = $usersPage > 0 ? $usersPage : 1;
        $breakdownPage = (int) request()->get('breakdown_page', 1);
        $breakdownPage = $breakdownPage > 0 ? $breakdownPage : 1;

        // Top contributors (top 20)
        $top = LaksamanaSubmission::select('laksamana_user_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('laksamana_user_id')
            ->groupBy('laksamana_user_id')
            ->orderByDesc('total')
            ->limit(20)
            ->get();

        $users = LaksamanaUser::whereIn('id', $top->pluck('laksamana_user_id'))
            ->get()
            ->keyBy('id');

        $leaderboard = $top->map(function ($row) use ($users) {
            return [
                'user' => $users[$row->laksamana_user_id] ?? null,
                'count' => $row->total,
            ];
        });

        // All users with counts (paginated)
        $allCountsQuery = LaksamanaSubmission::select('laksamana_user_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('laksamana_user_id')
            ->groupBy('laksamana_user_id')
            ->orderByDesc('total');

        $usersWithCounts = $allCountsQuery->paginate($perPage, ['*'], 'users_page', $usersPage);
        $allUsers = LaksamanaUser::whereIn('id', collect($usersWithCounts->items())->pluck('laksamana_user_id'))
            ->get()
            ->keyBy('id');
        $usersWithCounts->appends(['per_page' => $perPage]);
        $usersWithCounts->setCollection(
            collect($usersWithCounts->items())->map(function ($row) use ($allUsers) {
                return [
                    'user' => $allUsers[$row->laksamana_user_id] ?? null,
                    'count' => $row->total,
                ];
            })
        );

        // Per-user tag breakdown (distinct businesses and status counts)
        $breakdownQuery = LaksamanaSubmission::select(
                'laksamana_user_id',
                DB::raw('COUNT(DISTINCT laksamana_submissions.sbr_business_id) as businesses'),
                DB::raw("SUM(CASE WHEN sbr_businesses.status = 'aktif' THEN 1 ELSE 0 END) as aktif_count"),
                DB::raw("SUM(CASE WHEN sbr_businesses.status = 'tutup' THEN 1 ELSE 0 END) as tutup_count")
            )
            ->join('sbr_businesses', 'sbr_businesses.id', '=', 'laksamana_submissions.sbr_business_id')
            ->whereNotNull('laksamana_user_id')
            ->groupBy('laksamana_user_id')
            ->orderByDesc('businesses')
            ;

        $userBreakdown = $breakdownQuery->paginate($perPage, ['*'], 'breakdown_page', $breakdownPage);
        $breakdownUsers = LaksamanaUser::whereIn('id', collect($userBreakdown->items())->pluck('laksamana_user_id'))
            ->get()
            ->keyBy('id');
        $userBreakdown->appends(['per_page' => $perPage]);
        $userBreakdown->setCollection(
            collect($userBreakdown->items())->map(function ($row) use ($breakdownUsers) {
                return [
                    'user' => $breakdownUsers[$row->laksamana_user_id] ?? null,
                    'businesses' => (int) $row->businesses,
                    'aktif' => (int) $row->aktif_count,
                    'tutup' => (int) $row->tutup_count,
                ];
            })
        );

        // Public statistics
        $stats = [
            'total' => SbrBusiness::count(),
            'tagged' => SbrBusiness::whereNotNull('latitude')->whereNotNull('longitude')->count(),
            'untagged' => SbrBusiness::whereNull('latitude')->orWhereNull('longitude')->count(),
            'aktif' => SbrBusiness::where('status', 'aktif')->count(),
            'tutup' => SbrBusiness::where('status', 'tutup')->count(),
        ];

        return view('laksamana.leaderboard', compact('leaderboard', 'usersWithCounts', 'userBreakdown', 'stats', 'perPage'));
    }

    /**
     * Get businesses with filtering and search (for AJAX)
     */
    public function search(Request $request)
    {
        // Normalize and apply filters to a single base query
        $kecamatan = trim((string) $request->input('kecamatan', ''));
        $kelurahan = trim((string) $request->input('kelurahan', ''));
        $status = (string) $request->input('status', '');
        $search = trim((string) $request->input('search', ''));

        $baseQuery = SbrBusiness::query();

        if ($kecamatan !== '') {
            $baseQuery->byKecamatan($kecamatan);
        }

        if ($kelurahan !== '') {
            $baseQuery->byKelurahan($kelurahan);
        }

        if ($status !== '') {
            $baseQuery->byStatus($status);
        }

        if ($search !== '') {
            $baseQuery->search($search);
        }

        // Efficient pagination for large datasets
        $perPage = (int) $request->get('per_page', 20);
        $perPage = max(1, min($perPage, 100)); // Clamp between 1 and 100

        // Compute accurate total from the filtered query
        $total = (clone $baseQuery)->count();

        // Determine requested and effective page indices
        $requestedPage = (int) $request->get('page', 1);
        $requestedPage = $requestedPage > 0 ? $requestedPage : 1;
        $lastPage = max(1, (int) ceil($total / $perPage));
        $effectivePage = min($requestedPage, $lastPage);

        // Paginate using the clamped page number to ensure consistent items and counts
        $businesses = (clone $baseQuery)
            ->orderBy('nama_usaha')
            ->paginate($perPage, ['*'], 'page', $effectivePage);

        // Use paginator metadata for correctness
        $currentPage = (int) $businesses->currentPage();
        $lastPage = (int) $businesses->lastPage();
        $from = $businesses->firstItem() ?? 0;
        $to = $businesses->lastItem() ?? 0;

        return response()->json([
            'data' => $businesses->items(),
            'current_page' => $currentPage,
            'last_page' => $lastPage,
            'total' => $total,
            'per_page' => $perPage,
            'from' => $from,
            'to' => $to,
        ]);
    }

    /**
     * Get kelurahan list for a specific kecamatan (for dependent dropdown)
     */
    public function getKelurahan(Request $request, $kecamatan)
    {
        $kelurahanList = SbrBusiness::getDistinctKelurahan($kecamatan);
        return response()->json($kelurahanList);
    }

    /**
     * Display the import page
     */
    public function importPage()
    {
        return view('laksamana.import');
    }

    /**
     * Show export page for BPS users to download XLSX of all SBR businesses
     */
    public function exportPage()
    {
        // Require main app authentication (BPS users)
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => route('laksamana.export.page')]);
        }

        return view('laksamana.export');
    }

    /**
     * Generate and stream XLSX export of all SBR business data
     */
    public function exportExcel()
    {
        if (!Auth::check()) {
            return redirect()->route('login', ['redirect' => route('laksamana.export.xlsx')]);
        }

        $fileName = 'laksamana_businesses.xlsx';
        return Excel::download(new SbrBusinessesExport(), $fileName, \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * Handle Excel/CSV file import
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:51200', // Max 50MB
            'import_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first('file') ?? 'File import tidak valid.',
                    'errors' => $validator->errors(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Increase execution limits to reduce timeout risk for upload + dispatch
        try {
            @set_time_limit(0);
            @ini_set('max_execution_time', '0');
            @ini_set('memory_limit', '1024M');
        } catch (\Throwable $t) {
            // Silently ignore if ini_set is restricted
        }

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        try {
            // Persist uploaded file to storage for background processing
            $storedPath = $file->store('imports/sbr');
            // Use client-provided import_id if valid UUID, else generate new
            $requestedId = (string) ($request->input('import_id') ?? '');
            $importId = (method_exists(Str::class, 'isUuid') && Str::isUuid($requestedId)) ? $requestedId : (string) Str::uuid();

            // Initialize progress state in cache
            Cache::put("sbr_import:$importId", [
                'status' => 'queued',
                'processed' => 0,
                'success' => 0,
                'errors' => 0,
                'message' => null,
            ], now()->addHours(2));

            // Dispatch background job that performs chunked, bulk inserts
            $queueDriver = config('queue.default');
            $jobsTable = config('queue.connections.database.table', 'jobs');
            $dbHasJobsTable = true;
            if ($queueDriver === 'database') {
                try {
                    $dbHasJobsTable = Schema::hasTable($jobsTable);
                } catch (\Throwable $t) {
                    $dbHasJobsTable = false;
                }
            }

            if ($queueDriver === 'database' && !$dbHasJobsTable) {
                ProcessSbrImport::dispatchSync($storedPath, $extension, $importId);
            } else {
                ProcessSbrImport::dispatch($storedPath, $extension, $importId);
            }

            // If the request expects JSON (AJAX), return import id for polling
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'import_id' => $importId,
                    'message' => 'Import telah diantrikan dan akan diproses di latar belakang.'
                ]);
            }

            // Otherwise, redirect with a message including the tracking ID
            return redirect()->route('sbr.import.page')->with('success',
                'File berhasil diunggah. Import sedang diproses di latar belakang. ID tracking: ' . $importId);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error saat mengunggah/menjadwalkan import: ' . $e->getMessage(),
                ], 500);
            }
            return back()->with('error', 'Error saat mengunggah/menjadwalkan import: ' . $e->getMessage());
        }
    }

    /**
     * Map column names to their indexes
     */
    private function mapColumns(array $headerRow): array
    {
        $map = [
            'nama_usaha' => null,
            'kecamatan' => null,
            'kelurahan' => null,
            'idsbr' => null,
            'alamat' => null,
        ];

        foreach ($headerRow as $index => $header) {
            $headerLower = strtolower(trim($header ?? ''));

            if (str_contains($headerLower, 'nama') && str_contains($headerLower, 'usaha')) {
                $map['nama_usaha'] = $index;
            } elseif (str_contains($headerLower, 'nama_usaha')) {
                $map['nama_usaha'] = $index;
            } elseif ($headerLower === 'kecamatan' || str_contains($headerLower, 'kecamatan')) {
                $map['kecamatan'] = $index;
            } elseif ($headerLower === 'kelurahan' || str_contains($headerLower, 'kelurahan')) {
                $map['kelurahan'] = $index;
            } elseif ($headerLower === 'idsbr' || str_contains($headerLower, 'id sbr') || str_contains($headerLower, 'idsbr')) {
                $map['idsbr'] = $index;
            } elseif ($headerLower === 'alamat' || str_contains($headerLower, 'alamat') || str_contains($headerLower, 'address')) {
                $map['alamat'] = $index;
            }
        }

        return $map;
    }

    /**
     * Extract data from a row based on column mapping
     */
    private function extractRowData(array $row, array $columnMap): array
    {
        return [
            'nama_usaha' => $columnMap['nama_usaha'] !== null ? ($row[$columnMap['nama_usaha']] ?? '') : '',
            'kecamatan' => $columnMap['kecamatan'] !== null ? ($row[$columnMap['kecamatan']] ?? '') : '',
            'kelurahan' => $columnMap['kelurahan'] !== null ? ($row[$columnMap['kelurahan']] ?? '') : '',
            'idsbr' => $columnMap['idsbr'] !== null ? ($row[$columnMap['idsbr']] ?? '') : '',
            'alamat' => $columnMap['alamat'] !== null ? ($row[$columnMap['alamat']] ?? '') : '',
        ];
    }

    /**
     * Get current import progress by ID (for polling)
     */
    public function importStatus(string $id)
    {
        $state = Cache::get("sbr_import:$id");
        if (!$state) {
            return response()->json([
                'success' => false,
                'status' => 'not_found',
                'message' => 'Import ID tidak ditemukan atau telah kedaluwarsa.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'status' => $state['status'] ?? 'unknown',
            'processed' => $state['processed'] ?? 0,
            'success_count' => $state['success'] ?? 0,
            'error_count' => $state['errors'] ?? 0,
            'message' => $state['message'] ?? null,
        ]);
    }

    /**
     * Delete all SBR business data (truncate table)
     */
    public function deleteAll(Request $request)
    {
        try {
            SbrBusiness::truncate();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Semua data SBR berhasil dihapus.'
                ]);
            }

            return redirect()->route('sbr.import.page')->with('success', 'Semua data SBR berhasil dihapus.');
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->route('sbr.import.page')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Update business coordinates and status
     */
    public function update(Request $request, $id)
    {
        // Must be authenticated via Laksamana guard
        if (!Auth::guard('laksamana')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login untuk melakukan perubahan.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'status' => 'required|in:aktif,tutup',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $business = SbrBusiness::findOrFail($id);

        // Block edits if already finalized
        if (in_array($business->status, ['aktif', 'tutup'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Data usaha telah final (aktif/tutup) dan tidak dapat diedit.'
            ], 403);
        }

        // Ownership: only the first submitting user can edit
        $currentUserId = Auth::guard('laksamana')->id();
        $firstSubmission = LaksamanaSubmission::where('sbr_business_id', $business->id)
            ->whereNotNull('laksamana_user_id')
            ->orderBy('id', 'asc')
            ->first();

        if ($firstSubmission && $firstSubmission->laksamana_user_id !== $currentUserId) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengedit entri ini.'
            ], 403);
        }

        $business->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $request->status,
        ]);

        // Record a submission for leaderboard tracking
        try {
            LaksamanaSubmission::create([
                'laksamana_user_id' => $currentUserId,
                'sbr_business_id' => $business->id,
                'payload' => [
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'status' => $request->status,
                ],
            ]);
        } catch (\Throwable $e) {
            // Silently ignore tracking errors; don't block core functionality.
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
            'business' => $business
        ]);
    }

    /**
     * Clear business coordinates and status (set to null)
     */
    public function clearTagging(Request $request, $id)
    {
        try {
            if (!Auth::guard('laksamana')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login untuk melakukan perubahan.'
                ], 401);
            }

            $business = SbrBusiness::findOrFail($id);

            // Block edits if already finalized
            if (in_array($business->status, ['aktif', 'tutup'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data usaha telah final (aktif/tutup) dan tidak dapat dihapus.'
                ], 403);
            }

            // Ownership check
            $currentUserId = Auth::guard('laksamana')->id();
            $firstSubmission = LaksamanaSubmission::where('sbr_business_id', $business->id)
                ->whereNotNull('laksamana_user_id')
                ->orderBy('id', 'asc')
                ->first();
            if ($firstSubmission && $firstSubmission->laksamana_user_id !== $currentUserId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus tagging entri ini.'
                ], 403);
            }

            $business->update([
                'latitude' => null,
                'longitude' => null,
                'status' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Koordinat dan status berhasil dihapus (null)',
                'business' => $business,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus koordinat/status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single business for editing
     */
    public function show($id)
    {
        $business = SbrBusiness::findOrFail($id);

        // Determine last submission with a registered Laksamana user
        $lastSubmission = LaksamanaSubmission::where('sbr_business_id', $business->id)
            ->whereNotNull('laksamana_user_id')
            ->orderByDesc('id')
            ->first();

        $lastUser = null;
        if ($lastSubmission) {
            $lastUser = LaksamanaUser::find($lastSubmission->laksamana_user_id);
        }

        $payload = $business->toArray();
        $payload['finalized'] = in_array($business->status, ['aktif', 'tutup'], true);
        $payload['last_updated'] = $lastSubmission ? [
            'user_id' => $lastUser?->id,
            'name' => $lastUser?->name ?? 'Anonim',
            'email' => $lastUser?->email,
            'at' => optional($lastSubmission->created_at)->toDateTimeString(),
        ] : null;

        return response()->json($payload);
    }

    /**
     * Get statistics for the SBR data
     */
    public function stats()
    {
        return response()->json([
            'total' => SbrBusiness::count(),
            'tagged' => SbrBusiness::whereNotNull('latitude')->whereNotNull('longitude')->count(),
            'untagged' => SbrBusiness::whereNull('latitude')->orWhereNull('longitude')->count(),
            'aktif' => SbrBusiness::where('status', 'aktif')->count(),
            'tutup' => SbrBusiness::where('status', 'tutup')->count(),
        ]);
    }
}

