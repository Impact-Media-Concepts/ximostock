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
            ->select('id', DB::raw('title as name'), 'deleted_at', DB::raw("'Product' as type"))
            ->where('work_space_id', $current_workspace);

            // Haal de categorieën op die gearchiveerd zijn
            $categories = Category::onlyTrashed()
            ->select('id', 'name', 'deleted_at', DB::raw("'Category' as type"))
            ->where('work_space_id', $current_workspace);        

            // Haal de properties op die gearchiveerd zijn
            $properties = Property::onlyTrashed()
            ->select('id', 'name', 'deleted_at', DB::raw("'Property' as type"))
            ->where('work_space_id', $current_workspace);

            // Haal de verkoopkanalen op die gearchiveerd zijn
            $properties = SalesChannel::onlyTrashed()
            ->select('id', 'name', 'deleted_at', DB::raw("'Saleschannel' as type"))
            ->where('work_space_id', $current_workspace);

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
            'type' => ['required', Rule::in(['Property', 'Product', 'Category', 'Saleschannel'])]
        ]);

        //TODO authorize
        
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
            case 'Saleschannel':
                $saleschannel = SalesChannel::onlyTrashed()->findOrFail($values['id']);
                if($saleschannel){
                    $saleschannel->restore();
                    return response()->json(['message' => 'Saleschannel restored successfully'], 200);
                }
                break;
            default:
                return response()->json(['message' => 'bad request'], 400);
            break;
        }
    }

    public function forceDelete(Request $request){
        $values = $request->validate([
            'id' => ['required', 'numeric'],
            'type' => ['required', Rule::in(['Property', 'Product', 'Category', 'Saleschannel'])]
        ]);

        //TODO authorise
        

        switch ($values['type']) {
            case 'Property':
                $property = Property::onlyTrashed()->findOrFail($values['id']);
                if($property){
                    $property->forceDelete();
                    return response()->json(['message' => 'Property destroyed successfully'], 200);
                }
                break;
            case 'Product':
                $product = Product::onlyTrashed()->findOrFail($values['id']);
                if($product){
                    $product->forceDelete();
                    return response()->json(['message' => 'Product destroyed successfully'], 200);
                }
                break;
            case 'Category':
                $category = Category::onlyTrashed()->findOrFail($values['id']);
                if($category){
                    $category->forceDelete();
                    return response()->json(['message' => 'Category destroyed successfully'], 200);
                }
                break;
            case 'Saleschannel':
                $saleschannel = SalesChannel::onlyTrashed()->findOrFail($values['id']);
                if($saleschannel){
                    $saleschannel->forceDelete();
                    return response()->json(['message' => 'Saleschannel destroyed successfully'], 200);
                }
                break;
            default:
                return response()->json(['message' => 'bad request'], 400);
            break;
        }
    }

    public function bulkRestore(Request $request){
        Log::debug( $request);

        $values = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'numeric'],
            'items.*.type' => ['required', Rule::in(['Property', 'Product', 'Category', 'Saleschannel'])]
        ]);

        // Groepeer de items per type
        $groupedItems = collect($values['items'])->groupBy('type');

        // Herstel items per type in één query
        foreach ($groupedItems as $type => $items) {
            $ids = collect($items)->pluck('id')->toArray();

            switch ($type) {
                case 'Product':
                    Product::onlyTrashed()->whereIn('id', $ids)->restore();
                    break;
                case 'Category':
                    Category::onlyTrashed()->whereIn('id', $ids)->restore();
                    break;
                case 'Property':
                    Property::onlyTrashed()->whereIn('id', $ids)->restore();
                    break;
                case 'Saleschannel':
                    Saleschannel::onlyTrashed()->whereIn('id', $ids)->restore();
                    break;
            }
        }


        return response()->json(['message' => 'succesfull restore'], 200);
    }

    public function bulkForceDelete(Request $request)
    {
        $values = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'numeric'],
            'items.*.type' => ['required', Rule::in(['Property', 'Product', 'Category', 'Saleschannel'])]
        ]);

        // Groepeer de items per type
        $groupedItems = collect($values['items'])->groupBy('type');

        // Force delete items per type in één query
        foreach ($groupedItems as $type => $items) {
            $ids = collect($items)->pluck('id')->toArray();

            switch ($type) {
                case 'Product':
                    Product::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                    break;
                case 'Category':
                    Category::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                    break;
                case 'Property':
                    Property::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                    break;
                case 'Saleschannel':
                    Saleschannel::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                    break;
            }
        }

        return response()->json(['message' => 'Items successfully deleted permanently'], 200);
    }


}
