<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSalesRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\MoveStorageRequest;
use App\Http\Requests\StockStorageRequest;
use App\Models\Storage;
use App\Models\Product;
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
        $storageID = (int) $request->input('storageFrom');
        $storage = Storage::where('id', $storageID)->with('products')->first();

        $stockUpProds = $request->input('stockProducts');

        $keys = array_keys($stockUpProds);

        for($i = 0; $i < count($stockUpProds); $i++)
        {
            //skip if not entered a value
            if ($stockUpProds[$keys[$i]] == null)
                continue;

            $product = $storage->products->where('id', $keys[$i])->first();
            $product->pivot->amount += $stockUpProds[$keys[$i]];
            $product->pivot->save();
        }

        $request->session()->flash('success', 'success');

        return redirect()->route('event.overview');
    }

    public function MoveProduct(MoveStorageRequest $request, $eventID)
    {
        //Remember to use eager loading to save database calls
        $storageFromID = (int) $request->input('storageFrom');
        $storageFrom = Storage::where('id', $storageFromID)->with('products')->first();

        $storageToID = (int) $request->input('storageTo');
        $storageTo = Storage::where('id', $storageToID)->with('products')->first();

        $moveProds = $request->input('moveProducts');
        $keys = array_keys($moveProds);

        for($i = 0; $i < count($moveProds); $i++)
        {
            //skip if not entered a value
            if ($moveProds[$keys[$i]] == null)
                continue;

            //subtract from storage
            $productFrom = $storageFrom->products->where('id', $keys[$i])->first();
            $productFrom->pivot->amount -= $moveProds[$keys[$i]];
            $productFrom->pivot->save();

            //Add to new storage
            $productTo = $storageTo->products->where('id', $keys[$i])->first();
            $productTo->pivot->amount += $moveProds[$keys[$i]];
            $productTo->pivot->save();
        }

        $request->session()->flash('success', 'success');

        return redirect()->route('event.overview');
    }

    public function UpdateSales(UpdateSalesRequest $request, $eventID)
    {
        $storageID = (int) $request->input('storageFrom');
        $storage = Storage::where('id', $storageID)->with('products')->first();

        $stockUpProds = $request->input('salesProducts');

        $keys = array_keys($stockUpProds);

        for($i = 0; $i < count($stockUpProds); $i++)
        {
            //skip if not entered a value
            if ($stockUpProds[$keys[$i]] == null)
                continue;

            $product = $storage->products->where('id', $keys[$i])->first();
            $product->pivot->amount -= $stockUpProds[$keys[$i]];
            $product->pivot->sold_amount += $stockUpProds[$keys[$i]];
            $product->pivot->save();
        }

        $request->session()->flash('success', 'success');

        return redirect()->route('event.overview');
    }

    public function delete(Request $request, $id)
    {
        //for now, only admins can delete products
        if(Auth::user()->admin == false)
        {
            $request->session()->flash('error', 'Only Admins can delete products');
            return redirect()->route('event.storages');
        }
        $storageID = (int) $id;

        Storage::destroy($storageID);
        $request->session()->flash('success', 'Deleted storage');

        return redirect()->route('event.storages');
    }
}
