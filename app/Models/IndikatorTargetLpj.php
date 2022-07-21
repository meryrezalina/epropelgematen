<?php

namespace App\Models;

use App\Models\Lpj;
use App\Models\Propel;
use App\Models\IndikatorTargetLpj;
use Illuminate\Database\Eloquent\Model;

class IndikatorTargetLpj extends Model
{
    protected $table = 'indikatortargetlpj';
    protected $primaryKey = 'indikatorLpjlID';
    protected $fillable = ['lpjID', 'indikatorDeskripsi', 'target', 'pencapaianLPJ'];

    public function lpj()
    {
        return $this->belongsTo(Lpj::class, 'lpjID');
    }

}
