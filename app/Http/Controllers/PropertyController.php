<?php

namespace App\Http\Controllers;
use App\Models\ProductProperty;
use App\Models\Property;
use App\Models\SalesChannel;
use App\Models\WorkSpace;
use App\Rules\ValidWorkspaceKeys;
use App\SalesChannelManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Spatie\Activitylog\Facades\LogBatch;
use Illuminate\Support\Facades\Log;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'orderby' => ['nullable', 'string', Rule::in(['name', 'type','updated_at'])],
            'order' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
        ]);
        $current_workspace = (int) session('active_workspace_id');
        $request->orderby = $request->orderby ?? 'updated_at';
        $request->order = $request->order ?? 'desc';

        $query = Property::where('work_space_id', $current_workspace);
        if ($request->orderby && $request->order) {
            $query->orderBy($request->orderby, $request->order);
        }

        $properties = $query->paginate(14);
        $properties->appends($_GET)->links();


        // Zet de options JSON om in een array
        $properties->getCollection()->transform(function ($property) {
            // Als de options JSON bevat, decodeer het naar een array
            $property->options = json_decode($property->options)->options;
            return $property;
        });

        // Only return properties in JSON if the request is an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'properties' => $properties,
                'orderby' => $request->orderby,
                'order' => $request->order,
            ]);
        }

        $results = [
            'properties' => $properties,
            'orderby' => $request->orderby,
            'order' => $request->order,
        ];

        return view('property.index', $results);
    }

    #region update

    public function update(Request $request, $propertyId) // update function
    {
        Log::debug("updating property");

        $attributes = request()->validate([
            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'string'],
            'name' => ['required', 'string']
        ]);
        LogBatch::startBatch();

        try {
            $property = Property::find($propertyId);
            $property->update([
                'name' => $attributes['name'],
                'options' => json_encode(['options' => $attributes['options']])
            ]);
            return response()->json(['message' => 'Supplier updated successfully'], 200);

        } catch (Exception $e) {
            Log::error('Error updating property: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to update property.']);
        }finally{
            LogBatch::endBatch();
        }
    }

    #endregion

    public function bulkDelete(Request $request)
    {
        // Gate::authorize('bulkDelete', [Property::class, $request['properties']]);
        LogBatch::startBatch();
        Log::debug("bulk delete properties");
        $attributes = $request->validate([
            'properties' => ['required', 'array'],
            'properties.*' => ['required', 'numeric', Rule::exists('properties', 'id')]
        ]);
        Log::debug( $attributes['properties']);
        Log::debug("validated properties");
        $woocommerce = new SalesChannelManager;
        $properties = Property::whereIn('id', $attributes['properties'])->get();

        Log::debug($properties);

        $woocommerce->deleteProperties($properties);
        Property::whereIn('id', $attributes['properties'])->delete();
        LogBatch::endBatch();
        return response()->json(['message' => 'Properties deleted successfully'], 200);
    }

    public function deleteById(Request $request, $id)
    {
        //Gate::authorize('delete', $property);
        $property = Property::find($id);
        LogBatch::startBatch();
        if($property){
            $woocommerce = new SalesChannelManager;
            $woocommerce->deleteProperty($property);
            $property->delete();
            LogBatch::endBatch();
            return response()->json(['message' => 'Property deleted successfully'], 200);
        }else{
            LogBatch::endBatch();
            return response()->json(['message' => 'Property not found'], 404);

        }
    }

    public function store(Request $request)
    {
        $current_workspace = (int) session('active_workspace_id');
        //validate
        $attributes = $request->validate([
            'name' => ['required', 'string'],
            'type' => ['required', Rule::in(['singleselect', 'multiselect', 'number', 'bool', 'text'])],
            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'string'],
        ]);

        if (!isset($attributes['options'])) {
            $attributes['options'] = [];
        }

        //store
        $options = json_encode(['options' => $attributes['options']]);
        Property::create([
            'name' => $attributes['name'],
            'work_space_id' => $current_workspace,
            'type' => $attributes['type'],
            'options' => $options
        ]);

        return response()->json(['message' => 'Property created successfully'], 200);
    }
}
