<?php

namespace App\Http\Controllers;

use App\Models\SbrBusiness;
use Illuminate\Http\Request;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use OpenSpout\Reader\CSV\Reader as CsvReader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Jobs\ProcessSbrImport;
use Illuminate\Support\Facades\Validator;

class SbrController extends Controller
{
    /**
     * Display the main SBR tagging page
     */
    public function index(Request $request)
    {
        // Get distinct kecamatan for filter dropdown
        $kecamatanList = SbrBusiness::getDistinctKecamatan();
        
        return view('sbr.index', compact('kecamatanList'));
    }

    /**
     * Get businesses with filtering and search (for AJAX)
     */
    public function search(Request $request)
    {
        $query = SbrBusiness::query();
        
        // Apply filters
        if ($request->filled('kecamatan')) {
            $query->byKecamatan($request->kecamatan);
        }
        
        if ($request->filled('kelurahan')) {
            $query->byKelurahan($request->kelurahan);
        }
        
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        // Efficient pagination for large datasets
        $perPage = $request->get('per_page', 20);
        $businesses = $query->orderBy('nama_usaha')
                           ->paginate($perPage);
        
        return response()->json([
            'data' => $businesses->items(),
            'current_page' => $businesses->currentPage(),
            'last_page' => $businesses->lastPage(),
            'total' => $businesses->total(),
            'per_page' => $businesses->perPage()
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
        return view('sbr.import');
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

        $business->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $request->status,
        ]);

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
            $business = SbrBusiness::findOrFail($id);
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
        return response()->json($business);
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

