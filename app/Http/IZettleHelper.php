<?php
/**
 * Created by PhpStorm.
 * User: Crow
 * Date: 06-09-2017
 * Time: 00:44
 */

namespace App\Http;

use App\Events\Errors;
use App\Events\SalesUpdated;
use App\Models\Event;
use App\Models\Product;
use App\Models\User;
use App\Models\Storage;
use DebugBar;
use Illuminate\Support\Facades\Auth;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Socialite;

class IZettleHelper
{
    //Get latest products function -> update storage based on sales in database. Broadcast changes to frontend though redis
    public function getLatestSales($event) {

        //make sure is authed
        $user = Socialite::driver('izettle')->stateless()->user();

        // Send an asynchronous request.
        $url = 'https://purchase.izettle.com/purchases';
        $client = new Client();

        $headers = ['Authorization' => 'Bearer ' . $user->token];

        $request = new Request('GET', $url . '?limit=100' . '&lastPurchaseHash=' . $event->lastSaleHash, $headers);

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
		
	if (count($sales->purchases) <= 0) {
	    //nothing to update. Empty sales
	    return;
	}

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
                    $updateArray[$storedProduct->id]->soldAmount += $soldAmount;
                }
                else {
                    $obj = [
                        'storageID' => $storedProduct->pivot->FK_storageID,
                        'soldAmount'=> $soldAmount,
                    ];
                    $updateArray[$storedProduct->id] = (object)$obj;
                }
            }
        }

        //go though array and actually update the database values
        foreach($updateArray as $key => $item)
        {
            $product = $storage->products->where('id', $key)->first();

            $product->pivot->amount -= $item->soldAmount;
            $product->pivot->sold_amount += $item->soldAmount;
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

        //dd($broadcastObject); //TODO
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
}
