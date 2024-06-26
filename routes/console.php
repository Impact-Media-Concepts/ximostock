<?php

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


//calculate all stock, status, and sales for sorting purposes
Schedule::call(function(){
    $products = Product::all();
    foreach($products as $product){
        $product->update([
            'orderByStock' => $product->stock,
            'orderBySold' => $product->sales,
            'orderByOnline' => Count($product->salesChannels) > 0
        ]);
    }
})->daily();

Schedule::call( function(){
    // Get the current date and time
    $thirtyDaysAgo = Carbon::now()->subDays(30);
    // delete products that were archived 30 days ago or longer
    Product::onlyTrashed()
        ->whereDate('deleted_at', '<=', $thirtyDaysAgo)
        ->forceDelete();
})->daily();
