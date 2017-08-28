<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Event
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Storage[] $storages
 * @mixin \Eloquent
 */
class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'FK_eventID',
    ];

    public function storages()
    {
        return $this->hasMany('App\Models\Storage', 'FK_eventID', 'id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'FK_eventID', 'id');
    }
}
