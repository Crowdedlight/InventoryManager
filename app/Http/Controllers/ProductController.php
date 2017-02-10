<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Http\Requests\ImportProductsRequest;
use DebugBar;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Auth::user()->Event()->products()->get();

        //DebugBar::info($products);
        View()->share('products', $products);

        //for error messages, reflash session
        $request->session()->keep(['error', 'success']);

        return view('products.manage_products');
    }

    public function add(Request $request, $eventID)
    {
        $user = Auth::user();
        $storages = $user->Event()->storages()->get();

        $product = new Product();
        $product->name = $request->input('name');
        $product->FK_eventID = $eventID;
        $product->createdBy = $user->name;
        $product->save();

        foreach ($storages as $storage)
        {
            $storage->products()->attach($product->id, ['modifiedBy' => $user->name]);
        }

        return redirect()->route('event.products');

    }

    public function import(ImportProductsRequest $request, $eventID)
    {
        $user = Auth::user();
        $storages = $user->Event()->storages()->get();

        $path = $request->file('import_file')->getRealPath();

        $data = Excel::load($path, function($reader) {
        })->get(array('navn'));

        foreach($data as $prod)
        {
            $name = $prod->navn;
            //if "gratis" exist in name skip it, as it's an free variant and we have the actually variant later.
            if (str_contains($name, "Gratis"))
                continue;

            //check if variant exists in database. If it exists keep going
            $exist = Product::where('FK_eventID', $eventID)->where('name', "like", "%" . $name . "%")->exists();

            if($exist)
                continue;

            //Doesn't exist, make new one
            $prod = new Product();
            $prod->name = $name;
            $prod->FK_eventID = $eventID;
            $prod->createdBy = $user->name;
            $prod->save();

            //Stock product as 0 in every storage
            foreach ($storages as $storage)
            {
                $storage->products()->attach($prod->id, ['modifiedBy' => $user->name]);
            }
        }
        return redirect()->route('event.products');
    }

    public function delete(Request $request, $id)
    {
        //for now, only admins can delete products
        if(Auth::user()->admin == false)
        {
            $request->session()->flash('error', 'Only Admins can delete products');
            return redirect()->route('event.products');
        }
        $productID = (int) $id;
        Product::destroy($productID);

        $request->session()->flash('success', 'Deleted product');

        return redirect()->route('event.products');
    }
}
