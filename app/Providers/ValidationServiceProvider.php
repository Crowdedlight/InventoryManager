<?php

namespace App\Providers;

use App\Models\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('largerthenstock', function ($attribute, $value, $parameters, $validator) {

            //if input is null, return true as we don't validate for null
            if ($value == null)
                return true;

            $allData = $validator->getData();
            $storageFromID = (int) $allData['storageFrom'];

            $productID = (int) explode('.',$attribute)[1];
            $product = Product::with('storages')->where('id', $productID)->first();
            if ($product->storages->where('id', $storageFromID)->first()->pivot->amount < $value)
            {
                //Error, trying to move more than there is in storage
                return false;
            }

            return true;
        });

        Validator::extend('isnotdepot', function ($attribute, $value, $parameters, $validator) {

            //if input is null, return true as we don't validate for null
            if ($value == null)
                return true;

            $allData = $validator->getData();
            $storageToID = (int) $allData['storageFrom'];
            $storage = Storage::where('id', $storageToID)->first();

            //If depot return false, as we don't want depots, else return true, as it is not depot. (Used mainly for salesUpdate, can't sell from depot)
            return !$storage->depot;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
