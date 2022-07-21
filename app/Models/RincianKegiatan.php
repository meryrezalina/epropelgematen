<?php

namespace App\Models;

use App\Models\Propel;
use Illuminate\Database\Eloquent\Model;

class RincianKegiatan extends Model
{
    protected $table = 'rinciankegiatanpropel';
    protected $primaryKey = 'rincianPropelID';
    protected $fillable = ['propelID', 'rincianDeskripsi', 'tempat', 'waktuMulai', 'waktuSelesai'];

    public function propel()
    {
        return $this->belongsTo(Propel::class, 'propelID');
    }

}
