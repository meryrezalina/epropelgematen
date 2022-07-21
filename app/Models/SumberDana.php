<?php

namespace App\Models;

use App\Models\Propel;
use App\Models\Anggaran;
use App\Models\AnggaranProposal;
use Illuminate\Database\Eloquent\Model;

class SumberDana extends Model
{
    protected $table = "sumberdana";
    protected $primaryKey = "sumberID";
    protected $fillable = ['sumberDeskripsi'];

    public function propel(){
        return $this->hasMany(Anggaran::class, 'sumberID');
    }

    public function proposal(){
        return $this->hasMany(AnggaranProposal::class, 'sumberID');
    }
}
