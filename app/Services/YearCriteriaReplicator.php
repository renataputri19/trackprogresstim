<?php

namespace App\Services;

use App\Models\Criterion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class YearCriteriaReplicator
{
    /**
     * Ensure baseline Criterion rows exist for the given year.
     * If no rows exist for $year, clone from a source year (defaults to latest existing year, typically 2025),
     * and reset all user-input fields.
     */
    public static function ensureYear(int $year): void
    {
        if (Criterion::where('year', $year)->exists()) {
            return; // already present, nothing to do
        }

        // Determine source year: prefer 2025, otherwise use the latest available different year
        $preferredSourceYear = 2025;
        $sourceYear = Criterion::where('year', $preferredSourceYear)->exists()
            ? $preferredSourceYear
            : (int) (Criterion::where('year', '!=', $year)->max('year') ?: 0);

        if (! $sourceYear) {
            // No source data at all — nothing we can replicate from
            return;
        }

        DB::transaction(function () use ($sourceYear, $year) {
            $now = now();

            Criterion::where('year', $sourceYear)
                ->orderBy('id')
                ->chunk(500, function ($chunk) use ($year, $now) {
                    $payload = [];
                    foreach ($chunk as $row) {
                        $payload[] = [
                            'penilaian' => $row->penilaian,
                            'kriteria_nilai' => $row->kriteria_nilai,
                            'pilihan_jawaban' => $row->pilihan_jawaban,
                            'jawaban_unit' => null,
                            'nilai_unit' => null,
                            'catatan_unit' => null,
                            'bukti_dukung_unit' => null,
                            'url_bukti_dukung' => $row->url_bukti_dukung,
                            'jawaban_tpi' => null,
                            'nilai_tpi' => null,
                            'catatan_reviu_tpi' => null,
                            'category' => $row->category,
                            'last_updated_by' => null,
                            'year' => $year,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    if (! empty($payload)) {
                        Criterion::insert($payload);
                    }
                });
        });
    }
}