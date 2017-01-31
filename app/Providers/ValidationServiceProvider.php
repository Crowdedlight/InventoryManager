<?php

namespace App\Providers;

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
            $storageToID = (int) $allData['from'];

            $productID = (int) explode('.',$attribute)[1];
            $product = Product::where('id', $productID)->with('storages')->first();

            if ($product->storages->where('id', $storageToID)->first()->pivot->amount < $value)
            {
                //Error, trying to move more than there is in storage
                return false;
            }

            return true;
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
