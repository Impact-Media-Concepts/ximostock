<?php

namespace App\Http\Controllers;

use App\InventoryManager;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use App\Models\ProductSalesChannel;
use App\Models\SalesChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class WooCommerceWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::debug($request);
        if (isset($request->id)) {
            $saleschannel = $request->_links['self'][0]['href'];
            $saleschannel = $this->extractBaseUrl($saleschannel);
            $saleschannel = SalesChannel::where('url', $saleschannel)->get()->first();

            if ($saleschannel) {
                $order = Order::where('external_id', $request->id)->where('sales_channel_id', $saleschannel->id)->get()->first();
                Log::debug('order:');
                Log::debug($order);
                if (isset($order)) {
                    //do somehting if order is already present in datbase
                    if ($request->status == 'processing' && $order->status != 'processing' ) {
                        Log::debug('prosess order');
                        $productsData = $this->extractProducts($request, $order->sales_channel_id);
                        $this->createSales($productsData, $order->id);
                        InventoryManager::processOrder($order);
                    }
                    $order->status = $request->status;
                    $order->update();
                } else {
                    //create and process order
                    if (isset($request->status)) {
                        $order = Order::create([
                            'sales_channel_id' => $saleschannel->id,
                            'status' => $request->status,
                            'external_id' => $request->id
                        ]);
                        
                        if ($request->status == 'processing') {
                            $productsData = $this->extractProducts($request, $saleschannel->id);
                            $this->createSales($productsData, $order->id);
                            InventoryManager::processOrder($order);
                        }
                    }
                }
            }
        }
        return 200; //make sure the webhook gets its 200 responce
    }

    protected function extractBaseUrl($url)
    {
        // Parse the URL to get components
        $parsed_url = parse_url($url);

        // Reconstruct the base URL
        $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'];

        return $base_url;
    }

    protected function createSales($productsData, $orderID)
    {
        if ($productsData) {
            foreach ($productsData as $productData) {
                Sale::create([
                    'product_id' => $productData['id'],
                    'order_id' => $orderID,
                    'price' => $productData['price'],
                    'quantity' => $productData['quantity'],
                    'status' => 'in_progress'
                ]);
            }
        }
    }

    protected function extractProducts($request, $salesChannelId)
    {
        $productsData = $request->line_items;
        $products = [];
        foreach ($productsData as $productData) {
            $productSaleschannel = ProductSalesChannel::where('sales_channel_id', $salesChannelId)->where('external_id', $productData['product_id'])->get()->first();
            if ($productSaleschannel) {
                $product = [
                    'id' => $productSaleschannel->product_id,
                    'quantity' => $productData['quantity'],
                    'price' => $productData['total']
                ];
                array_push($products, $product);
            }
        }
        return $products;
    }
}
