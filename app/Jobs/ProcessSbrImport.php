<?php

namespace App\Jobs;

use App\Models\SbrBusiness;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use OpenSpout\Reader\CSV\Reader as CsvReader;

class ProcessSbrImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Allow long-running import without worker killing the job.
     */
    public $timeout = 7200; // 2 hours

    /**
     * Path to stored file relative to storage/app
     */
    protected string $path;

    /**
     * File extension (xlsx, xls, csv)
     */
    protected string $extension;

    /**
     * Import tracking ID (UUID)
     */
    protected string $importId;

    /**
     * Maximum rows per bulk insert
     */
    protected int $chunkSize = 1000;

    public function __construct(string $path, string $extension, string $importId)
    {
        $this->path = $path;
        $this->extension = strtolower($extension);
        $this->importId = $importId;
    }

    public function handle(): void
    {
        Cache::put($this->cacheKey(), [
            'status' => 'processing',
            'processed' => 0,
            'success' => 0,
            'errors' => 0,
            'message' => null,
        ], now()->addHours(2));

        $fullPath = storage_path('app/' . $this->path);

        $successCount = 0;
        $errorCount = 0;
        $processed = 0;

        $batch = [];
        $batchRowNumbers = [];

        // Choose reader based on extension
        if ($this->extension === 'csv') {
            $reader = new CsvReader();
        } else {
            $reader = new XlsxReader();
        }

        try {
            @set_time_limit(0);
            @ini_set('max_execution_time', '0');

            $reader->open($fullPath);

            $isFirstRow = true;
            $columnMap = [];

            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                    $cells = $row->toArray();

                    if ($isFirstRow) {
                        $columnMap = $this->mapColumns($cells);
                        $isFirstRow = false;
                        continue;
                    }

                    if (empty(array_filter($cells))) {
                        continue;
                    }

                    $data = $this->extractRowData($cells, $columnMap);

                    if (empty($data['nama_usaha'])) {
                        $errorCount++;
                        $processed++;
                        $this->updateProgress($processed, $successCount, $errorCount);
                        continue;
                    }

                    $batch[] = [
                        'nama_usaha' => trim($data['nama_usaha']),
                        'kecamatan' => trim($data['kecamatan'] ?? ''),
                        'kelurahan' => trim($data['kelurahan'] ?? ''),
                        'idsbr' => (trim($data['idsbr'] ?? '') !== '') ? trim($data['idsbr']) : null,
                        'alamat' => (trim($data['alamat'] ?? '') !== '') ? trim($data['alamat']) : null,
                        'latitude' => null,
                        'longitude' => null,
                        'status' => null,
                    ];
                    $batchRowNumbers[] = $rowIndex;
                    $processed++;

                    if (count($batch) >= $this->chunkSize) {
                        $this->flushBatch($batch, $batchRowNumbers, $successCount, $errorCount);
                        $this->updateProgress($processed, $successCount, $errorCount);
                    }
                }
                // Only process first sheet (consistent with original implementation)
                break;
            }

            if (!empty($batch)) {
                $this->flushBatch($batch, $batchRowNumbers, $successCount, $errorCount);
            }

            $reader->close();

            Cache::put($this->cacheKey(), [
                'status' => 'completed',
                'processed' => $processed,
                'success' => $successCount,
                'errors' => $errorCount,
                'message' => "Import selesai. {$successCount} baris berhasil, {$errorCount} baris gagal.",
            ], now()->addHours(2));
        } catch (\Throwable $e) {
            Cache::put($this->cacheKey(), [
                'status' => 'failed',
                'processed' => $processed,
                'success' => $successCount,
                'errors' => $errorCount,
                'message' => 'Gagal memproses import: ' . $e->getMessage(),
            ], now()->addHours(2));
        }
    }

    protected function flushBatch(array &$batch, array &$batchRowNumbers, int &$successCount, int &$errorCount): void
    {
        if (empty($batch)) {
            return;
        }

        try {
            // Add-only import: insert new records and skip existing ones.
            $table = (new SbrBusiness())->getTable();

            // Build lookup sets from the batch
            $idsSet = [];
            $namesSet = [];
            $kecSet = [];
            $kelSet = [];
            foreach ($batch as $row) {
                $id = trim((string) ($row['idsbr'] ?? ''));
                if ($id !== '') {
                    $idsSet[$id] = true;
                } else {
                    $name = strtolower(trim((string) ($row['nama_usaha'] ?? '')));
                    $kec = strtolower(trim((string) ($row['kecamatan'] ?? '')));
                    $kel = strtolower(trim((string) ($row['kelurahan'] ?? '')));
                    if ($name !== '' && $kec !== '' && $kel !== '') {
                        $namesSet[$name] = true;
                        $kecSet[$kec] = true;
                        $kelSet[$kel] = true;
                    }
                }
            }

            // Existing ids lookup
            $existingIds = [];
            if (!empty($idsSet)) {
                $existingIds = DB::table($table)
                    ->whereIn('idsbr', array_keys($idsSet))
                    ->pluck('idsbr')
                    ->map(function ($v) { return trim((string) $v); })
                    ->all();
                $existingIds = array_flip($existingIds);
            }

            // Existing triples lookup for rows without idsbr
            $existingTriples = [];
            if (!empty($namesSet) && !empty($kecSet) && !empty($kelSet)) {
                $existingRecords = DB::table($table)
                    ->whereIn('nama_usaha', array_keys($namesSet))
                    ->whereIn('kecamatan', array_keys($kecSet))
                    ->whereIn('kelurahan', array_keys($kelSet))
                    ->get(['nama_usaha', 'kecamatan', 'kelurahan']);

                foreach ($existingRecords as $rec) {
                    $key = strtolower(trim((string) $rec->nama_usaha)) . '|' .
                           strtolower(trim((string) $rec->kecamatan)) . '|' .
                           strtolower(trim((string) $rec->kelurahan));
                    $existingTriples[$key] = true;
                }
            }

            // Filter the batch to only new entries and deduplicate within the batch
            $filtered = [];
            $seenKeys = [];
            foreach ($batch as $row) {
                $id = trim((string) ($row['idsbr'] ?? ''));
                $name = strtolower(trim((string) ($row['nama_usaha'] ?? '')));
                $kec = strtolower(trim((string) ($row['kecamatan'] ?? '')));
                $kel = strtolower(trim((string) ($row['kelurahan'] ?? '')));

                $key = $id !== '' ? ('id:' . $id) : ('tri:' . $name . '|' . $kec . '|' . $kel);

                // Skip duplicates within the same batch
                if (isset($seenKeys[$key])) {
                    continue;
                }

                // Skip existing DB records based on idsbr or triple when idsbr is empty
                if ($id !== '') {
                    if (isset($existingIds[$id])) {
                        continue;
                    }
                } else {
                    // Require valid triple identity
                    if ($name === '' || $kec === '' || $kel === '') {
                        $errorCount++;
                        continue;
                    }
                    $triKey = $name . '|' . $kec . '|' . $kel;
                    if (isset($existingTriples[$triKey])) {
                        continue;
                    }
                }

                $filtered[] = $row;
                $seenKeys[$key] = true;
            }

            if (!empty($filtered)) {
                $inserted = DB::table($table)->insertOrIgnore($filtered);
                if (is_int($inserted)) {
                    $successCount += $inserted;
                } else {
                    $successCount += count($filtered);
                }
            }
        } catch (\Throwable $e) {
            // Fallback to row-by-row insert-or-ignore to isolate errors
            foreach ($batch as $index => $rowData) {
                try {
                    $table = (new SbrBusiness())->getTable();
                    DB::table($table)->insertOrIgnore([$rowData]);
                    $successCount++;
                } catch (\Throwable $inner) {
                    $errorCount++;
                }
            }
        }

        $batch = [];
        $batchRowNumbers = [];
    }

    protected function updateProgress(int $processed, int $successCount, int $errorCount): void
    {
        Cache::put($this->cacheKey(), [
            'status' => 'processing',
            'processed' => $processed,
            'success' => $successCount,
            'errors' => $errorCount,
            'message' => null,
        ], now()->addHours(2));
    }

    protected function cacheKey(): string
    {
        return 'sbr_import:' . $this->importId;
    }

    /**
     * Column mapping logic duplicated from controller to keep job self-contained
     */
    protected function mapColumns(array $headerRow): array
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

    protected function extractRowData(array $row, array $columnMap): array
    {
        return [
            'nama_usaha' => $columnMap['nama_usaha'] !== null ? ($row[$columnMap['nama_usaha']] ?? '') : '',
            'kecamatan' => $columnMap['kecamatan'] !== null ? ($row[$columnMap['kecamatan']] ?? '') : '',
            'kelurahan' => $columnMap['kelurahan'] !== null ? ($row[$columnMap['kelurahan']] ?? '') : '',
            'idsbr' => $columnMap['idsbr'] !== null ? ($row[$columnMap['idsbr']] ?? '') : '',
            'alamat' => $columnMap['alamat'] !== null ? ($row[$columnMap['alamat']] ?? '') : '',
        ];
    }
}