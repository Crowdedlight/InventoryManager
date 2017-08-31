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

        $request = new Request('GET', $url . '?limit=5' . 'descending=true', $headers);

        $response = $client->send($request);
        $sales = json_decode($response->getBody()->getContents());

        //set last hash
        $event->lastSaleHash = $sales->lastPurchaseHash;

        //set true in event for api
        $event->activeAPI = true;
        $event->save();

        return redirect()->route('event.overview');
    }

    //Get latest products function -> update storage based on sales in database. Broadcast changes to frontend though redis
    public function getLatestSales($event) {

        //make sure is authed
        $user = Socialite::driver('izettle')->stateless()->user();

        // Send an asynchronous request.
        $url = 'https://purchase.izettle.com/purchases';
        $client = new Client();

        $headers = ['Authorization' => 'Bearer ' . $user->token];

        $request = new Request('GET', $url . '?limit=100' . '?lastPurchaseHash=' . $event->lastSaleHash, $headers);

        $response = $client->send($request);
        $sales = json_decode($response->getBody()->getContents());
        $this->handleRecievedSales($sales, $event);

//        $promise = $client->sendAsync($request)->then(function ($response) {
//
//            $sales = json_decode($response->getBody()->getContents());
//
//            $loggedUser = Auth::user();
//
//            $this->handleRecievedSales($sales, $loggedUser->Event());
//        });
//        $promise->wait();
    }

    public function handleRecievedSales($sales, $event) {

        $errors = array();
        $updateArray = array();

        //loop though sales and update database
        foreach($sales->purchases as $sale) {
            foreach($sale->products as $product) {

                //first part of variant until "." is soldAmount that is sold
                $variantArray = explode(".", $product->variantName);
                $soldAmount = intval($variantArray[0]);

                //multiply soldAmount with quantity to get total soldAmount
                $soldAmount = $soldAmount * $product->quantity;

                //find storage
                try {
                    $storage = $event->storages()->with('products')->where('name', $sale->userDisplayName)->first();
                } catch (Exception $ex) {
                    //stop function and
                    array_push($errors, $product->name . " " . $product->variantName . " : gave error and it sales did not get updated");
                    //broadcast error TODO
                    return;
                }

                //find product in database
                try{
                    $storedProduct =  $storage->products->where('name', '==', $product->name)->first();
                } catch (Exception $ex){
                    //add to error array
                    array_push($errors, $product->name . " : gave error and it sales did not get updated");
                    continue;
                }
                //null check
                if ($storedProduct == null)
                {
                    array_push($errors, $product->name . " : gave error and it sales did not get updated");
                    continue;
                }

                //push to array with id as key
                if (array_key_exists($storedProduct->id, $updateArray))
                {
                    $updateArray[$storedProduct->id] += $soldAmount;
                }
                else
                    $updateArray[$storedProduct->id] = $soldAmount;

            }
        }

        dd($updateArray); //TODO

        //go though array and actually update the database values
        foreach($updateArray as $key => $soldAmount)
        {
            $product = $storage->products->where('id', $key)->first();

            $product->pivot->amount -= $soldAmount;
            $product->pivot->sold_amount += $soldAmount;
            $product->pivot->save();
        }

        //set last hash
        $event->lastSaleHash = $sales->lastPurchaseHash;
        $event->save();

        //broadcast update array to frontend
        $broadcastObject = (object)[
            'eventID' => $event->id,
            'updateArray' => $updateArray
        ];

        dd($broadcastObject); //TODO
        event(new SalesUpdated($broadcastObject));

        //if errors exists broadcast them to frontend too
        if (count($errors) > 0) {
            //Wrap error
            $errorBroadcast = (object)[
                'eventID' => $event->id,
                'errorArray' => $errors
            ];
            event(new Errors($errorBroadcast));
        }

        //if size is equal to limit call next portion from api straight away
        if (count($sales->purchases) >= 100) {
            $this->getLatestSales($event);
        }
    }

    //TODO REMOVE
    public function testBroadcast() {
        $eventID = Auth::user()->Event()->id;
        //broadcast update array to frontend
        $broadcastObject = (object)[
            'eventID' => $eventID,
            'updateArray' => ['something' => 'something2']
        ];

        event(new SalesUpdated($broadcastObject));

    }

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
        }

        return redirect()->route('event.products');
    }
}
