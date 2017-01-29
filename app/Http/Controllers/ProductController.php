<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Auth::user()->Event()->products();
        View()->share('products', $products);

        return view('products.manage_products');
    }

    public function addProduct(Request $request, $eventID)
    {
        $storages = Auth::user()->Event()->storages();

        $product = new Product();
        $product->name = $request->input('name');
        $product->FK_eventID = $eventID;
        $product->save();

        foreach ($storages as $storage)
        {
            $storage->products()->attach($product->id);
        }

        return redirect()->route('event.products');

    }

    public function delete(Request $request, $id)
    {
        Product::find($id)->storages()->detach();

        return redirect()->route('event.products');
    }
}
