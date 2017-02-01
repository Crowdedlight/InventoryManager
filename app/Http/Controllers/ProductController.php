<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use DebugBar;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Auth::user()->Event()->products()->get();

        //DebugBar::info($products);
        View()->share('products', $products);

        //for error messages, reflash session
        $request->session()->reflash();

        return view('products.manage_products');
    }

    public function add(Request $request, $eventID)
    {
        $storages = Auth::user()->Event()->storages()->get();

        $product = new Product();
        $product->name = $request->input('name');
        $product->FK_eventID = $eventID;
        $product->createdBy = Auth::user()->name;
        $product->save();

        foreach ($storages as $storage)
        {
            $storage->products()->attach($product->id, ['modifiedBy' => Auth::user()->name]);
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
