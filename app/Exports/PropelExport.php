<?php

namespace App\Exports;

use App\Models\Propel;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class PropelExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Propel::with('bidang', 'timpel')->get();
    }

    public function map($propel): array
    {
        return [
            $propel->propelID,
            $propel->bidang->namaBidang,
            $propel->timpel->namaTimpel,
            $propel->namaKegiatan,
            $propel->nomorRAPB,
            $propel->namaPJ,
            $propel->sasaranStrategis,
            $propel->status,
            $propel->created_at,
            $propel->updated_at
        ];
    }

    public function headings(): array
    {
        return[
            '#',
            'Bidang',
            'Timpel',
            'Nama Kegiatan',
            'Nomor RAPB',
            'Nama PJ',
            'Sasaran Strategis',
            'Status',
            'Created At',
            'Updated At'
        ];
    }
}
