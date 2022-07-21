<?php

namespace App\Models;

use App\Models\Propel;
use App\Models\Proposal;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    protected  $table = "bidang";
    protected $primaryKey = "bidangID";
    protected $fillable = ['bidangID', 'namaBidang'];

    public function proposal(){
        return $this->hasMany(Proposal::class, 'bidangID');
    }

    public function propel(){
        return $this->hasMany(Propel::class, 'timpelID');
    }

    public function user(){
        return $this->hasMany(User::class, 'timpelID');
    }
}
