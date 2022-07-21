<?php

namespace App\Models;

use App\Models\Propel;
use App\Models\Proposal;
use App\Models\RincianKegiatanProposal;
use Illuminate\Database\Eloquent\Model;

class RincianKegiatanProposal extends Model
{
    protected $table = 'rinciankegiatanproposal';
    protected $primaryKey = 'rincianID';
    protected $fillable = ['proposalID', 'rincianDeskripsi', 'tempat', 'waktuMulai', 'waktuSelesai'];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposalID');
    }

}
