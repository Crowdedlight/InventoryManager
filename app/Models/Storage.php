<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Storage extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'products_storages', 'FK_storageID', 'FK_productID')
            ->withPivot('amount', 'sold_amount', 'modifiedBy')
            ->withTimestamps();
    }
}
