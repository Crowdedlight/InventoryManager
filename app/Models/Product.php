<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function storages()
    {
        return $this->belongsToMany('App\Models\Product', 'products_storages', 'FK_productID', 'FK_storageID')
            ->withPivot('amount', 'sold_amount', 'modifiedBy')
            ->withTimestamps();
    }
}
