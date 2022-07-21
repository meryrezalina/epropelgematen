<?php

namespace App\Models;

use App\Models\Bidang;
use App\Models\Timpel;
use App\Models\Anggaran;
use App\Models\SumberDana;
use App\Models\IndikatorTarget;
use App\Models\RincianKegiatan;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Propel extends Model 
{
    use Sortable;
    protected $table = 'propel';
    protected $primaryKey = 'propelID';
    protected $fillable = ['bidangID', 'timpelID', 'namaKegiatan', 'nomorRAPB', 'namaPJ', 'sasaranStrategis', 'status'];

    public $sortable = ['propelID', 'bidangID', 'timpelID', 'namaKegiatan', 'nomorRAPB', 'namaPJ', 'sasaranStrategis', 'status','created_at'];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidangID');
    }

    public function timpel()
    {
        return $this->belongsTo(Timpel::class, 'timpelID');
    }

    public function anggaran(){
        return $this->hasMany(Anggaran::class, 'propelID');
    }

    public function hargaSatuan(){
        return $this->hasMany(Anggaran::get('hargaSatuan'), 'propelID');
    }

    public function sumberdana()
    {
        return $this->belongsTo(SumberDana::class, 'sumberID');
    }

    public function indikatorTarget(){
        return $this->hasMany(IndikatorTarget::class, 'propelID');
    }
    
    public function rincianKegiatan(){
        return $this->hasMany(RincianKegiatan::class, 'propelID');
    }
}
