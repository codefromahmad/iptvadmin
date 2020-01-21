<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class M3ufiles extends Model
{
    protected $fillable = ['user_id','mfile'];

    public function iptvuser()
    {
        return $this->hasOne(Iptvusers::class, 'id', 'user_id');
    }
}
