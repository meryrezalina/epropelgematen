<?php

namespace App\Models;

use App\Models\Lpj;
use App\Models\Propel;
use App\Models\SumberDana;
use App\Models\AnggaranLpj;
use Illuminate\Database\Eloquent\Model;

class AnggaranLpj extends Model
{
    protected $table = 'pengeluaranlpj';
    protected $primaryKey = 'pengeluaranID';
    protected $fillable = ['lpjID', 'pengeluaranDeskripsi', 'hargaSatuan', 'kuantitas', 'satuan', 'sumberID'];

    public function lpj()
    {
        return $this->belongsTo(Lpj::class, 'lpjID');
    }

    public function sumberdana()
    {
        return $this->belongsTo(SumberDana::class, 'sumberID');
    }
}
