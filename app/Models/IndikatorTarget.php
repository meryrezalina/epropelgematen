<?php

namespace App\Models;

use App\Models\Propel;
use Illuminate\Database\Eloquent\Model;

class IndikatorTarget extends Model
{
    protected $table = 'indikatotargetpropel';
    protected $primaryKey = 'indikatorPropelID';
    protected $fillable = ['propelID', 'indikatorDeskripsi', 'target', 'pencapaianLPJ'];

    public function propel()
    {
        return $this->belongsTo(Propel::class, 'propelID');
    }
}
