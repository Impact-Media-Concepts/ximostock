<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    public function updatestock(Request $request){
        Log::debug($request->all());
        return $request->all();
    }
}
