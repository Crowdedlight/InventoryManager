<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function storages()
    {
        return $this->hasMany('App\Models\Storage', 'FK_eventID', 'id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'FK_eventID', 'id');
    }
}
