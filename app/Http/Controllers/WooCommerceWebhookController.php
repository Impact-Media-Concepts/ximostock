<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSalesChannel;
use App\Models\SalesChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WooCommerceWebhookController extends Controller
{
    public function handle(Request $request){
        $saleschannel = $request->_links['self'][0]['href'];
        $saleschannel = $this->extractBaseUrl($saleschannel);
        $saleschannel = SalesChannel::where('url', $saleschannel)->get();
        if($saleschannel){
            $result = $this->extractProducts($request, $saleschannel);
            Log::debug(json_encode($result));
        }
        Log::debug('end');
        return 200;
    }

    protected function extractBaseUrl($url)
    {
        // Parse the URL to get components
        $parsed_url = parse_url($url);
        
        // Reconstruct the base URL
        $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'];
        
        return $base_url;
    }

    protected function extractProducts($request, $salesChannel){
        $productsData = $request->line_items;
        $products = [];
        foreach($productsData as $productData){
            $product = [
            'id' => ProductSalesChannel::where('sales_channel_id', $salesChannel->id)->where('external_id', $productData['id'])->get()->product_id,
            'quantity' => $productData['quantity'],
            'price' => $productData['total'] 
            ];
            array_push($products,$product);
        }
        return $products;
    }
}
