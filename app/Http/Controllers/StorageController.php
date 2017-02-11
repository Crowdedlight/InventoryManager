<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportProductsRequest;
use App\Http\Requests\UpdateSalesRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\MoveStorageRequest;
use App\Http\Requests\StockStorageRequest;
use App\Models\Storage;
use App\Models\Product;
use DebugBar;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\CountValidator\Exception;

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

    public function ImportSales(ImportProductsRequest $request, $eventID)
    {
        $storageID = (int) $request->input('storageFrom');
        $storage = Storage::with('products')->where('id', $storageID)->first();

        $path = $request->file('import_file')->getRealPath();

        $data = Excel::load($path, function($reader) {
            $reader->noHeading();
        })->get();

        $start = false;
        $errors = array();

        //products loop
        foreach ($data as $prod)
        {
            //update start/end values
            if (!$start && str_is("Solgte produkter", $prod[0]))
            {
                $start = true;
                continue;
            }
            if ($start && str_is("Samlet salg", $prod[0]))
                break;

            //check if we reached start point
            if (!$start)
                continue;

            //get element and number of sold items
            $prodName = $prod[0];
            $prodAmount = $prod[1];

            $product = null;
            $modifier = null;
            $realProdName = null;

            //if "gratis" is in name, take first word. always 1 piece
            if (str_contains($prodName, "Gratis"))
            {
                $nameArray = explode(",", $prodName);
                $realProdName = rtrim(rtrim($nameArray[0], "Gratis"));
                $modifier = 1;
            }
            //elseif "stk" is in name, take first part to find product, and second part to see how much
            elseif (str_contains($prodName, "stk"))
            {
                $nameArray = explode(",", $prodName);
                $modifier = intval(rtrim($nameArray[1], "stk."));
                $realProdName = rtrim($nameArray[0]);
            }
            //else the whole name must be the product name
            else
            {
                $nameArray = explode(",", $prodName);
                $realProdName = rtrim($nameArray[0]);
                $modifier = 1;
            }

            try{
                $product =  $storage->products->where('name', '==', $realProdName)->first();
            } catch (Exception $ex){
                //add to error array
                array_push($errors, $prodName . " : gave error and it sales did not get updated");
                continue;
            }
            //null check
            if ($product == null)
            {
                array_push($errors, $prodName . " : gave error and it sales did not get updated");
                continue;
            }
            $newSoldAmount = $modifier * $prodAmount;
            $currSoldAmount = $product->pivot->sold_amount;
            $deltaAmount = ($newSoldAmount - $currSoldAmount);

            //todo Do we want this check? Or do we just want it to go in minus?
            if ($product->pivot->amount < $deltaAmount)
            {
                array_push($errors, $prodName . " : Couldn't update as there is seemingly sold more than current stock");
                continue;
            }

            $product->pivot->amount -= $deltaAmount;
            $product->pivot->sold_amount += $deltaAmount;
            //$product->pivot->save(); todo uncomment for testing when errors are fixed

        }
        //catch errors and add them to error list
        dd($errors);
        if (count($errors) > 0)
            $request->session()->flash('error', $errors);
        else
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
