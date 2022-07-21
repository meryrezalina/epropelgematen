<?php

namespace App\Models;

use App\Models\Propel;
use App\Models\Proposal;
use Illuminate\Database\Eloquent\Model;

class IndikatorTargetProposal extends Model
{
    protected $table = 'indikatortarget';
    protected $primaryKey = 'indikatorID';
    protected $fillable = ['proposalID', 'indikatorDeskripsi', 'target', 'pencapaianLPJ'];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposalID');
    }

}
