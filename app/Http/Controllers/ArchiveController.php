<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category; 
use App\Models\Product;
use App\Models\Property;
use App\Models\SalesChannel;
use Illuminate\Support\Facades\DB;


class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $current_workspace = (int) session('active_workspace_id');
            // Haal de producten op die gearchiveerd (soft-deleted) zijn en hernoem 'title' naar 'name'
            $products = Product::onlyTrashed()
            ->select('id', DB::raw('title as name'), 'deleted_at', DB::raw("'Product' as type"));

            // Haal de categorieën op die gearchiveerd zijn
            $categories = Category::onlyTrashed()
            ->select('id', 'name', 'deleted_at', DB::raw("'Category' as type"));

            // Haal de properties op die gearchiveerd zijn
            $properties = Property::onlyTrashed()
            ->select('id', 'name', 'deleted_at', DB::raw("'Property' as type"));

            // Combineer de queries met union en sorteer op deleted_at
            $archivedItems = $products->union($categories)->union($properties)
            ->orderBy('deleted_at', 'desc') // Sorteer op deleted_at
            ->paginate(15); // Paginate de resultaten
        $results = [
            'items' => $archivedItems
        ];
        //Log::debug($results['salesChannels']);
        return view('archive.index', $results);
    }
}
