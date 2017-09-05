<?php

namespace App\Http\Controllers;

use App\Events\Errors;
use App\Events\SalesUpdated;
use App\Http\Requests\ImportProductsRequest;
use App\Models\Event;
use App\Models\Product;
use App\Models\User;
use DebugBar;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Socialite;
use GuzzleHttp\Psr7\Request;

class IZettleController extends Controller
{

    //Activate api -> get first record and save hash for future requests
    public function activateAPI() {
        //get event from current logged in user
        $user = Auth::user();

        $event = $user->Event();

        //do call and get latest salehash
        $user = Socialite::driver('izettle')->stateless()->user();

        // Send an request.
        $url = 'https://purchase.izettle.com/purchases';
        $client = new Client();

        $headers = ['Authorization' => 'Bearer ' . $user->token];

        $request = new Request('GET', $url . '?limit=5' . '&' . 'descending=true', $headers);

        $response = $client->send($request);
        $sales = json_decode($response->getBody()->getContents());

        //set last hash
        $event->lastSaleHash = $sales->lastPurchaseHash;

        //set true in event for api
        $event->activeAPI = true;
        $event->save();

        return redirect()->route('event.overview');
    }

//    //TODO REMOVE
//    public function testBroadcast() {
//        $eventID = Auth::user()->Event()->id;
//        //broadcast update array to frontend
//
//        $updateA = array();
//        $obj = [
//            'storageID' => 1,
//            'soldAmount'=> 10,
//        ];
//        $obj2 = [
//            'storageID' => 3,
//            'soldAmount'=> 5,
//        ];
//        $updateA[1] = $obj;
//        $updateA[2] = $obj2;
//
//
//        $broadcastObject = (object)[
//            'eventID' => $eventID,
//            'updateArray' => $updateA
//        ];
//
//        event(new SalesUpdated($broadcastObject));
//    }

    //Deactivate api -> stop schduler and save last recieved hash
    public function deactivateAPI() {
        //get event from current logged in user
        $user = Auth::user();

        $event = $user->Event();

        $event->activeAPI = false;
        $event->save();

        return redirect()->route('event.overview');
    }

    public function GetProducts($eventID) {

        $event = Event::find($eventID);
        $user = Auth::user();

        $storages = $user->Event()->storages()->get();

        //Call API and get all products
        $IZuser = Socialite::driver('izettle')->stateless()->user();

        // Send an request.
        $url = 'https://products.izettle.com/organizations/' . $IZuser->organization . '/products';
        $client = new Client();

        $headers = ['Authorization' => 'Bearer ' . $IZuser->token];

        $request = new Request('GET', $url, $headers);

        $response = $client->send($request);
        $products = json_decode($response->getBody()->getContents());
        foreach ($products as $product) {
            //If exists update values, else make new
            $prod = Product::firstOrNew([
                'name' => $product->name,
                'FK_eventID' => $event->id,
            ]);
            $prod->uuid = $product->uuid;
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
}
