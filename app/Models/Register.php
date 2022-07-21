<?php

namespace App\Models;

use App\Models\Bidang;
use App\Models\Timpel;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    //relasi dengan tabel bidang
    // public function bidang(){
    //     return $this->hasMany('App\Models\Movie', 'id', 'movie_id');
    // }

    protected $table = 'proposals';
    protected $primaryKey = 'proposalID';
    protected $fillable = ['bidangID', 'timpelID', 'name', 'email', 'password'];

    public function bidang(){
        return $this->belongsTo(Bidang::class, 'bidangID');
    }

    public function timpel(){
        return $this->belongsTo(Timpel::class, 'timpelID');
    }

    public function lpj(){
        return $this->hasMany(Lpj::class, 'lpjID');
    }
}
