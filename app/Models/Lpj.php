<?php

namespace App\Models;

use App\Models\Proposal;
use App\Models\AnggaranLpj;
use App\Models\IndikatorTargetLpj;
use App\Models\RincianKegiatanLpj;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class Lpj extends Model
{
    use Sortable;
    protected $table = 'lpj';
    protected $primaryKey = 'lpjID';
    protected $fillable = ['proposalID', 'saldo', 'totalPengeluaran', 'approvedbyRomo', 'approvedbyKabid', 'created_at'];
    public $sortable = ['proposalID', 'saldo', 'totalPengeluaran', 'approvedbyRomo', 'approvedbyKabid', 'created_at'];

    public function proposal(){
        return $this->belongsTo(Proposal::class, 'proposalID');
    }

    public function anggaranLpj(){
        return $this->hasMany(AnggaranLpj::class, 'lpjID');
    }

    public function sumberdana()
    {
        return $this->belongsTo(SumberDana::class, 'sumberID');
    }

    public function indikatorTargetLpj(){
        return $this->hasMany(IndikatorTargetLpj::class, 'lpjID');
    }
    public function rincianKegiatanLpj(){
        return $this->hasMany(RincianKegiatanLpj::class, 'lpjID');
    }
}