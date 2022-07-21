<?php

namespace App\Models;

use App\Models\Propel;
use App\Models\SumberDana;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    protected $table = 'anggaranpropel';
    protected $primaryKey = 'anggaranPropelID';
    protected $fillable = ['propelID', 'anggaranDeskripsi', 'hargaSatuan', 'kuantitas', 'sumberID'];

    public function propel()
    {
        return $this->belongsTo(Propel::class, 'propelID');
    }

    

    public function sumberdana()
    {
        return $this->belongsTo(SumberDana::class, 'sumberID');
    }
}
