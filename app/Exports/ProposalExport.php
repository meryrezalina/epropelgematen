<?php

namespace App\Exports;

use App\Models\Proposal;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProposalExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Proposal::with('bidang', 'timpel')->get();
    }

    public function map($proposal): array
    {
        return [
            $proposal->proposalID,
            $proposal->bidang->namaBidang,
            $proposal->timpel->namaTimpel,
            $proposal->namaKegiatan,
            $proposal->nomorRAPB,
            $proposal->totalBiaya,
            $proposal->status,
            $proposal->created_at,
            $proposal->updated_at
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
            'Total Biaya',
            'Status',
            'Created At',
            'Updated At'
        ];
    }
}
