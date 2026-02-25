<?php

namespace App\Exports;

use App\Models\SbrBusiness;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SbrBusinessesExport implements FromQuery, WithHeadings, WithMapping
{
    /** @var array<string> */
    protected array $columns = [
        'id',
        'nama_usaha',
        'kecamatan',
        'kelurahan',
        'latitude',
        'longitude',
    ];

    /**
     * Query only businesses with coordinates to minimize memory usage
     */
    public function query()
    {
        return SbrBusiness::query()
            ->select($this->columns)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('id');
    }

    /**
     * Column headings for the export file
     */
    public function headings(): array
    {
        return $this->columns;
    }

    /**
     * Map each row to an array in the same order as headings
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->nama_usaha,
            $row->kecamatan,
            $row->kelurahan,
            $row->latitude,
            $row->longitude,
        ];
    }
}