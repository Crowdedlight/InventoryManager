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

    public function StockStorage(Request $request, $eventID)
    {
        $storageID = (int) $request->input('storage');
        $storage = Storage::find($storageID);

        $stockUpProds = $request->input();

        dd($request->input());
        //Remove every key/pair but products
        array_pull($stockUpProds, '_token');
        array_pull($stockUpProds, '_action');
        array_pull($stockUpProds, 'storage');

        $keys = array_keys($stockUpProds);

        dd($stockUpProds);

        for($i = 0; $i < count($stockUpProds); $i++)
        {
            $product = $storage->products()->where('FK_productID', $keys[$i])->first();
            $product->pivot->amount += $stockUpProds[$keys[$i]];
            $product->pivot->save();
        }

        return redirect()->route('event.overview');
    }

    public function delete(Request $request, $id)
    {
        Product::find($id)->storages()->detach();

        return redirect()->route('event.storages');
    }
}
