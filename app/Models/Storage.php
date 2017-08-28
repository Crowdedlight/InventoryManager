<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Storage
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Storage onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Storage withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Storage withoutTrashed()
 * @mixin \Eloquent
 */
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
