<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Property;
use App\Models\SalesChannel;
use App\Models\InventoryLocation;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $validatedRequest = $request->validate([
            'orderby' => ['nullable', 'string', Rule::in(['name', 'company_name', 'website', 'phone_number', 'deleted_at', 'itemtype'])],
            'order' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
            'checked_types' => ['nullable', 'array'],
            'checked_types.*' => ['required', Rule::in(['Supplier', 'Product', 'Category', 'Property', 'Saleschannel', 'Location'])]
        ]);

        $current_workspace = (int) session('active_workspace_id');
        $orderby = $request->orderby ?? 'deleted_at';
        $order = $request->order ?? 'desc';

        // Define the types and corresponding models
        $typesConfig = [
            'Product' => Product::class,
            'Category' => Category::class,
            'Property' => Property::class,
            'Saleschannel' => SalesChannel::class,
            'Location' => InventoryLocation::class,
            'Supplier' => Supplier::class,
        ];

        // Get the checked types, allow empty but don't filter in that case
        $checkedTypes = $validatedRequest['checked_types'] ?? [];

        // Initialize the query builder
        $archivedItemsQuery = null;

        if (!empty($checkedTypes)) {
            // Build the query for each selected type
            foreach ($checkedTypes as $type) {
                $model = $typesConfig[$type];
                $query = $model::onlyTrashed()
                    ->select('id', 'name', 'deleted_at', DB::raw("'$type' as itemtype"))
                    ->where('work_space_id', $current_workspace);

                if ($type === 'Product') {
                    $query->select('id', DB::raw('title as name'), 'deleted_at', DB::raw("'$type' as itemtype"));
                }

                // Combine the queries
                $archivedItemsQuery = $archivedItemsQuery ? $archivedItemsQuery->union($query) : $query;
            }
        } else {
            // If no types are selected, fetch all items from all types without filtering
            foreach ($typesConfig as $type => $model) {
                $query = $model::onlyTrashed()
                    ->select('id', 'name', 'deleted_at', DB::raw("'$type' as itemtype"))
                    ->where('work_space_id', $current_workspace);

                if ($type === 'Product') {
                    $query->select('id', DB::raw('title as name'), 'deleted_at', DB::raw("'$type' as itemtype"));
                }

                // Combine the queries
                $archivedItemsQuery = $archivedItemsQuery ? $archivedItemsQuery->union($query) : $query;
            }
        }

        // Apply ordering and paginate
        $archivedItems = $archivedItemsQuery
            ->orderBy($orderby, $order)
            ->paginate(12);

        // Prepare the types data for the checkboxes
        $types = [];
        foreach ($typesConfig as $key => $model) {
            $types[strtolower($key)] = ['name' => $key, 'value' => in_array($key, $checkedTypes)];
        }

        $results = [
            'items' => $archivedItems,
            'orderby' => $orderby,
            'order' => $order,
            'types' => $types,
        ];

        return view('archive.index', $results);
    }

    public function restore(Request $request)
    {
        $values = $request->validate([
            'id' => ['required', 'numeric'],
            'type' => ['required', Rule::in(['Property', 'Product', 'Category', 'Saleschannel', 'Location'])]
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
            case 'Location':
                $location = InventoryLocation::onlyTrashed()->findOrFail($values['id']);
                if($location){
                    $location->restore();
                    return response()->json(['message' => 'Location restored successfully'], 200);
                }
                break;
            default:
                return response()->json(['message' => 'Bad request'], 400);
        }
    }

    public function forceDelete(Request $request)
    {
        $values = $request->validate([
            'id' => ['required', 'numeric'],
            'type' => ['required', Rule::in(['Property', 'Product', 'Category', 'Saleschannel', 'Location'])]
        ]);

        //TODO authorize

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
            case 'Location':
                $location = InventoryLocation::onlyTrashed()->findOrFail($values['id']);
                if($location){
                    $location->forceDelete();
                    return response()->json(['message' => 'Location destroyed successfully'], 200);
                }
                break;
            default:
                return response()->json(['message' => 'Bad request'], 400);
        }
    }

    public function bulkRestore(Request $request)
    {
        $values = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'numeric'],
            'items.*.itemtype' => ['required', Rule::in(['Property', 'Product', 'Category', 'Saleschannel', 'Location', 'Supplier'])]
        ]);

        // Groepeer de items per type
        $groupedItems = collect($values['items'])->groupBy('itemtype');

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
                    SalesChannel::onlyTrashed()->whereIn('id', $ids)->restore();
                    break;
                case 'Location':
                    InventoryLocation::onlyTrashed()->whereIn('id', $ids)->restore();
                    break;
                case 'Supplier':
                    Supplier::onlyTrashed()->whereIn('id', $ids)->restore();
                    break;
            }
        }

        return response()->json(['message' => 'Items successfully restored'], 200);
    }

    public function bulkForceDelete(Request $request)
    {
        $values = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'numeric'],
            'items.*.itemtype' => ['required', Rule::in(['Property', 'Product', 'Category', 'Saleschannel', 'Location', 'Supplier'])]
        ]);

        // Groepeer de items per type
        $groupedItems = collect($values['items'])->groupBy('itemtype');

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
                    SalesChannel::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                    break;
                case 'Location':
                    InventoryLocation::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                    break;
                case 'Supplier':
                    Supplier::onlyTrashed()->whereIn('id', $ids)->forceDelete();
                    break;
            }
        }

        return response()->json(['message' => 'Items successfully deleted permanently'], 200);
    }
}
