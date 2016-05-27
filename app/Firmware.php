<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Firmware extends Model
{
    protected $fillable = ['url', 'device_id', 'category_id'];

    public function device()
    {
        return $this->belongsTo('App\Device');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}
