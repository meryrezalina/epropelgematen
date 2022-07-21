<?php

namespace App\Models;

use App\Models\Proposal;
use App\Models\SumberDana;
use App\Models\AnggaranProposal;
use Illuminate\Database\Eloquent\Model;

class AnggaranProposal extends Model
{
    protected $table = 'anggaran';
    protected $primaryKey = 'anggaranID';
    protected $fillable = ['proposalID', 'anggaranDeskripsi', 'hargaSatuan', 'kuantitas', 'satuan', 'subtotal', 'sumberID'];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposalID');
    }

    public function sumberdana()
    {
        return $this->belongsTo(SumberDana::class, 'sumberID');
    }
}
