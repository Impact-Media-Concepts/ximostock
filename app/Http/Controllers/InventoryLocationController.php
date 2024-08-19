<?php

namespace App\Http\Controllers;

use App\Models\InventoryLocation;
use App\Models\LocationZone;
use App\Models\WorkSpace;
use App\Rules\ValidWorkspaceKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InventoryLocationController extends Controller
{
    public function index(Request $request){
        $current_workspace = (int) session('active_workspace_id');
        $locations = InventoryLocation::where('work_space_id', $current_workspace)
            ->with('location_zones')
            ->orderBy('updated_at','desc')
            ->paginate(15);
        $result = [
            'locations' => $locations
        ];
        Log::debug($current_workspace);

        return view('inventoryLocation.index', $result);
    }

    public function store(Request $request){
        $current_workspace = (int) session('active_workspace_id');

        Log::debug($current_workspace);

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'zones' => ['nullable', 'array'],
            'zones.*' => ['required', 'string', 'max:255']
        ]);
        
        $location = InventoryLocation::create([
            'name' => $attributes['name'],
            'work_space_id' => $current_workspace
        ]);
        
        foreach($attributes['zones'] as $zone){
            LocationZone::create([
                'name' => $zone,
                'work_space_id' => $current_workspace,
                'inventory_location_id' => $location->id
            ]);
        }

        if($location){
            return response()->json(['message' => 'Location created successfully'], 200);
        }else{
            return response()->json(['error' => 'something went wrong'], 404);
        }
    }

    public function deleteById(Request $request, $location){
        $location = InventoryLocation::findOrFail($location);

        if($location){
            $location->delete();
            return response()->json(['message' => 'Saleschannel deleted successfully'], 200);
        }else{
            return response()->json(['error' => 'Saleschannel not found'], 404);
        }
    }

    public function bulkDelete(Request $request){
        $current_workspace = (int) session('active_workspace_id');

        $attributes = $request->validate([
            'locations' => ['required', 'array'],
            'locations.*' => ['required', 'numeric'] 
        ]);

        InventoryLocation::WhereIn('id', $attributes['locations'])->delete();

        return response()->json(['message' => 'Locations deleted successfully'], 200);

    }

    public function update(Request $request){
        // Retrieve the current workspace ID from the session
        $current_workspace = (int) session('active_workspace_id');
        
        // Validate the incoming request
        $attributes = $request->validate([
            'id' => ['required', 'numeric'],
            'name' => ['required', 'string', 'max:255'],
            'location_zones' => ['nullable', 'array'],
            'location_zones.*.id' => ['nullable', 'numeric'],
            'location_zones.*.name' => ['required', 'string'],
        ]);
        
        // Find the InventoryLocation or fail
        $location = InventoryLocation::findOrFail($attributes['id']);
        
        // Retrieve existing location zones for the inventory location
        $existingZones = LocationZone::where('inventory_location_id', $location->id)->get();

        // Separate the incoming zones into old (with ID) and new (without ID) zones
        $oldZones = [];
        $newZones = [];
        
        foreach ($request['location_zones'] as $zone) {
            if (isset($zone['id'])) {
                $oldZones[$zone['id']] = $zone;  // Use ID as key for easy comparison
            } else {
                $newZones[] = $zone;
            }
        }

        // Convert existing zones into a key-value array for easy look-up
        $existingZonesMap = $existingZones->keyBy('id');

        // Delete zones that are in the database but not in the request
        foreach ($existingZones as $existingZone) {
            if (!array_key_exists($existingZone->id, $oldZones)) {
                $existingZone->delete();  // Soft delete or use forceDelete() if necessary
            }
        }

        // Update existing zones with new data
        foreach ($oldZones as $zoneId => $zoneData) {
            if (isset($existingZonesMap[$zoneId])) {
                $existingZonesMap[$zoneId]->update([
                    'name' => $zoneData['name'],
                ]);
            }
        }

        // Create new zones
        foreach ($newZones as $zoneData) {
            LocationZone::create([
                'work_space_id' => $current_workspace,
                'inventory_location_id' => $location->id,
                'name' => $zoneData['name'],
            ]);
        }

        return response()->json(['message' => 'Location zones updated successfully'], 200);
    }
}
