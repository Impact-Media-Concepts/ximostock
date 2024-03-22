<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Rules\ValidPropertyKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    public function index(){
        return view('property.index',[
            'sidenavActive' => 'properties',
            'properties' => Property::where('work_space_id', Auth::user()->work_space_id)->get(),
        ]);
    }

    public function show(Property $property){
        return view('property.show',[
            'sidenavActive' => 'properties',
            'property' => $property
        ]);
    }

    public function update(Property $property){
        Gate::authorize('update-property',[$property]);

        $attributes = request()->validate([
            'options' => ['nullable', 'array'],
            'options.*' => ['required', 'string'],
            'name' => ['required', 'string']
        ]);
        $options = [];
        
        foreach ($attributes['options'] as $option){
            array_push($options, $option);
        }
        $type = $property->type;
        $values = json_encode(['type'=>$type, 'options' => $options]);

        $property->update([
            'name' => $attributes['name'],
            'values' => $values
        ]);
        return redirect()->back();
    }

    public function bulkDelete(){
        $request = request();
        Gate::authorize('bulk-property', [$request['properties']]);

        $attributes = $request->validate([
            'properties' => ['required', 'array'],
            'properties.*' => ['required', 'numeric', Rule::exists('properties', 'id')]
        ]);
        Property::whereIn('id', $attributes['properties'])->delete();
        return redirect('/properties');
    }
}
