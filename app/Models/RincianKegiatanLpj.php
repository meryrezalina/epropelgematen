<?php

namespace App\Models;

use App\Models\Lpj;
use App\Models\Propel;
use App\Models\Proposal;
use App\Models\RincianKegiatanLpj;
use App\Models\RincianKegiatanProposal;
use Illuminate\Database\Eloquent\Model;

class RincianKegiatanLpj extends Model
{
    protected $table = 'rinciankegiatanlpj';
    protected $primaryKey = 'rincianKeglpjID';
    protected $fillable = ['lpjID', 'rincianDeskripsiLPJ', 'tempatLPJ', 'waktuMulaiLPJ', 'waktuSelesaiLPJ'];

    public function lpj()
    {
        return $this->belongsTo(Lpj::class, 'lpjID');
    }

}
