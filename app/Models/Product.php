<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function storages()
    {
        return $this->belongsToMany('App\Models\Storage')
            ->withPivot('amount', 'sold_amount', 'modifiedBy')
            ->withTimestamps();
    }
}
