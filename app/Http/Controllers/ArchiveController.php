<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category; 
use App\Models\Product;
use App\Models\Property;
use App\Models\SalesChannel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;


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

    public function restore(Request $request){
        $values = $request->validate([
            'id' => ['required', 'numeric'],
            'type' => ['required', Rule::in(['Property', 'Product', 'Category'])]
        ]);

        Log::debug($values);
        
        switch ($values['type']) {
            case 'Property':
                $property = Property::onlyTrashed()->findOrFail($values['id']);
                if($property){
                    $property->restore();
                    return response()->json(['message' => 'Property restored successfully'], 200);
                }
                break;
            case 'Product':
                $product = Product::onlyTrashed()->findOrFail($values['id']);
                if($product){
                    $product->restore();
                    return response()->json(['message' => 'Product restored successfully'], 200);
                }
                break;
            case 'Category':
                $category = Category::onlyTrashed()->findOrFail($values['id']);
                if($category){
                    $category->restore();
                    return response()->json(['message' => 'Category restored successfully'], 200);
                }
                break;
            default:
                return response()->json(['message' => 'bad request'], 400);
            break;
        }
        

    }
}
