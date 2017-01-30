<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'products_storages', 'FK_storageID', 'FK_productID')
            ->withPivot('amount', 'sold_amount', 'modifiedBy')
            ->withTimestamps();
    }
}
