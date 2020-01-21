<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Epgfiles extends Model
{
    protected $fillable = ['user_id','efile'];

    public function iptvuser()
    {
        return $this->hasOne(Iptvusers::class, 'id', 'user_id');
    }
}
