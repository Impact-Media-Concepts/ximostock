<?php

namespace App\Http\Controllers;

use App\Models\InventoryLocation;
use App\Models\LocationZone;
use App\Models\WorkSpace;
use App\Rules\ValidWorkspaceKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryLocationController extends Controller
{
    public function index(Request $request){
        $current_workspace = (int) session('active_workspace_id');
        
        $locations = InventoryLocation::where('work_space_id', $current_workspace)->paginate(10);

        $result = [
            'locations' => $locations
        ];

        return view('inventoryLocation.index', $result);
    }

    public function show(Request $request, InventoryLocation $location){
        if (Auth::user()->role === 'admin') {
            $request->validate([
                'workspace' => ['required', new ValidWorkspaceKeys]
            ]);
            $workspaces = WorkSpace::all();
            $activeWorkspace = $request['workspace'];
        }else{
            $workspaces = null;
            $activeWorkspace = null;
        }
        return view('inventoryLocation.show',[
            'sidenavActive' => 'locations',
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace,
            'location' => $location
        ]);
    }

    public function create(Request $request){
        if (Auth::user()->role === 'admin') {
            $request->validate([
                'workspace' => ['required', new ValidWorkspaceKeys]
            ]);
            $workspaces = WorkSpace::all();
            $activeWorkspace = $request['workspace'];
        }else{
            $workspaces = null;
            $activeWorkspace = null;
        }

        return view('inventoryLocation.create',[
            'sidenavActive' => 'locations',
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace
        ]);
    }

    public function store(Request $request){
        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'zones' => ['nullable', 'array'],
            'zones.*' => ['required', 'string', 'max:255']
        ]);
        
        $location = InventoryLocation::create([
            'name' => $attributes['name'],
            'work_space_id' => Auth::user()->work_space_id
        ]);
        foreach($attributes['zones'] as $zone){
            LocationZone::create([
                'name' => $zone,
                'work_space_id' => Auth::user()->work_space_id,
                'inventory_location_id' => $location->id
            ]);
        }
        return redirect('/locations');
    }
}
