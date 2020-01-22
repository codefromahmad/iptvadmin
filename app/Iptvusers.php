<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Iptvusers extends Model
{
    protected $table = "iptvusers";
    protected $fillable = ['mac_address','period'];

    public function m3ufile(){
        return $this->hasOne(M3ufiles::class,'user_id','id');
    }

    public function epgfile(){
        return $this->hasOne(Epgfiles::class, 'user_id', 'id');
    }
}
