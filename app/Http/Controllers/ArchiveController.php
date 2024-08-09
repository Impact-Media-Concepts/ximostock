<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category; 
use App\Models\Product;
use App\Models\Property;
use App\Models\SalesChannel;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $current_workspace = (int) session('active_workspace_id');
        
        $products = Product::select('id', 'title')->onlyTrashed()->where('work_space_id', $current_workspace);

        $results = [
            
        ];
        //Log::debug($results['salesChannels']);
        return view('archive.index', $results);
    }
}
