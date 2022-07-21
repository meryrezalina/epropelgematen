<?php

namespace App\Exports;

use App\Models\Propel;
use App\Models\Anggaran;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;


class DataExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;   
    
    protected $propelID;
    // protected $anggaranPropelID;

    public function __construct($propelID)
    {
        $this->propelID = $propelID;
        // $this->anggaranPropelID = $anggaranPropelID;
        
    }

    public function query()
    {
        return Propel::where('propelID', $this->propelID)
                      ->with('bidang', 'timpel', 'anggaran');
        // return Anggaran::where('anggaranPropelID', $this->anggaranPropelID);       
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
            $propel->updated_at,
            $propel->anggaran,

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
            'Updated At',
            'Harga'
        ];
    }
}
