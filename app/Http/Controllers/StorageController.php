<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Storage;
use App\Models\Product;
use App\Http\Requests\StockStorageRequest;
use DebugBar;

class StorageController extends Controller
{
    public function index()
    {
        $storages = Auth::user()->Event()->storages()->get();

        //DebugBar::info($products);
        View()->share('storages', $storages);

        return view('storages.manage_storage');
    }

    public function add(Request $request, $eventID)
    {
        $products = Auth::user()->Event()->products()->get();

        $storage = new Storage();
        $storage->name = $request->input('name');
        $storage->createdBy = Auth::user()->name;
        $storage->FK_eventID = $eventID;

        if($request->input('depot') != null)
            $storage->depot = $request->input('depot');

        $storage->save();

        foreach ($products as $product)
        {
            $product->storages()->attach($storage->id, ['modifiedBy' => Auth::user()->name]);
        }

        return redirect()->route('event.storages');

    }

    public function StockStorage(StockStorageRequest $request, $eventID)
    {
        $storageID = (int) $request->input('storage');
        $storage = Storage::find($storageID);

        $stockUpProds = $request->input('products');

        $keys = array_keys($stockUpProds);

        for($i = 0; $i < count($stockUpProds); $i++)
        {
            //skip if not entered a value
            if ($stockUpProds[$keys[$i]] == null)
                continue;

            $product = $storage->products()->where('FK_productID', $keys[$i])->first();
            $product->pivot->amount += $stockUpProds[$keys[$i]];
            $product->pivot->save();
        }

        $request->session()->flash('success', 'success');

        return redirect()->route('event.overview');
    }

    public function MoveProduct(Request $request, $eventID)
    {
        
    }

    public function delete(Request $request, $id)
    {
        Product::find($id)->storages()->detach();

        return redirect()->route('event.storages');
    }
}
