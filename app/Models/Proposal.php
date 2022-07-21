<?php

namespace App\Models;

use App\Models\Lpj;
use App\Models\Bidang;
use App\Models\Timpel;
use App\Models\AnggaranProposal;
use Kyslik\ColumnSortable\Sortable;
use App\Models\IndikatorTargetProposal;
use App\Models\RincianKegiatanProposal;
use Illuminate\Database\Eloquent\Model;


class Proposal extends Model
{
    use Sortable;

    protected $table = 'proposals';
    protected $primaryKey = 'proposalID';
    protected $fillable = ['bidangID', 'timpelID', 'jenisProposal', 'namaKegiatan', 'totalBiaya', 'status'];
    public $sortable = ['proposalID', 'bidangID', 'timpelID', 'namaKegiatan', 'totalBiaya', 'status', 'created_at'];
    
    public function bidang(){
        return $this->belongsTo(Bidang::class, 'bidangID');
    }

    public function timpel(){
        return $this->belongsTo(Timpel::class, 'timpelID');
    }

    public function lpj(){
        return $this->hasMany(Lpj::class, 'lpjID');
    }

    public function anggaranProposal(){
        return $this->hasMany(AnggaranProposal::class, 'proposalID');
    }   

    public function hargaSatuan(){
        return $this->hasMany(AnggaranProposal::get('hargaSatuan'), 'proposalID');
    }

    public function sumberdana()
    {
        return $this->belongsTo(SumberDana::class, 'sumberID');
    }

    public function indikatorTargetProposal(){
        return $this->hasMany(IndikatorTargetProposal::class, 'proposalID');
    }

    public function rincianKegiatanProposal(){
        return $this->hasMany(RincianKegiatanProposal::class, 'proposalID');
    }
}
