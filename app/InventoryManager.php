<?php

namespace App;

use App\Models\LocationZone;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Inventory;
use Illuminate\Support\Facades\Log;

class InventoryManager
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
    }
    
    public static function processOrder(Order $order){
        foreach($order->sales as $sale){
            $product = $sale->product;
            if($product->stock < $sale->quantity){
                if($product->backorders){
                    //maak een backorder aan
                    $sale->status = 'backorderd';
                    $sale->update();
        
                }else{
                    //probleem notify manager.
                    //er is een order van een product uit vorraad en er is geen backorder toegestaan.
                    $sale->status = 'error';
                    $sale->update();        
                }
            }else{
                //verwerk sale normaal
                InventoryManager::processSale($sale);
            }
        }
    }

    protected static function processSale(Sale $sale){
        if($sale->status != 'closed'){ //verwerk nooit closed sales
            $product = $sale->product;
            $zoneCount = count($product->locationZones);
            if($zoneCount == 1){
                $zone = $product->locationZones[0];
                $inventory = Inventory::where('product_id', $product->id)->where('location_zone_id', $zone->id)->get()->first();
                $inventory->stock = $inventory->stock - $sale->quantity;
                $inventory->update();
                $sale->status = 'closed';
                $sale->update();
            }
            else if($zoneCount > 1){
                //maak taak aan om de  locatie te kiezen
                $sale->status = 'waiting_for_location';
                $sale->update();
            }else{
                //the product has no location. this is a problem. (raad aan omn een task aan te maken bij een manager. of een mail te sturen.)
                $sale->status = 'error';
                $sale->update();
            }
            $woocommerce = new WooCommerceManager;
            $woocommerce->updateProductStock($product);
        }
        //update stock on the saleschannels(todo)
    }

    public static function setSaleLocation(Sale $sale, LocationZone $zone ){
        $inventory = Inventory::where('product_id', $sale->product->id)->where('location_zone_id', $zone->id);
        if($inventory->stock - $sale->quantity >= 0 || $sale->product->backorder){
            $inventory->stock =- $sale->quantity;
            $inventory->update();
            return 'succes';
        }else{
            return 'error';
        }
    }
}