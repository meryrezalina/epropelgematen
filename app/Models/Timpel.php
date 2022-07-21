<?php

namespace App\Models;

use App\Models\Propel;
use App\Models\Proposal;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Timpel extends Model
{
    protected $table = "timpel";
    protected $primaryKey = "timpelID";
    protected $fillable = ['namaTimpel', 'namaKTP'];

    public function proposal(){
        return $this->hasMany(Proposal::class, 'timpelID');
    }

    public function propel(){
        return $this->hasMany(Propel::class, 'timpelID');
    }

    public function user(){
        return $this->hasMany(User::class, 'timpelID');
    }
}
