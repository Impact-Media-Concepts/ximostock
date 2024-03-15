<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Rules\ValidPropertyKeys;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    public function index(){
        return view('property.index',[
            'properties' => Property::all()
        ]);
    }

    public function show(Property $property){
        return view('property.show',[
            'property' => $property
        ]);
    }

    public function update(Property $property){
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
        $attributes = request()->validate([
            'properties' => ['required', 'array'],
            'properties.*' => ['required', 'numeric', Rule::exists('properties', 'id')]
        ]);
        Property::whereIn('id', $attributes['properties'])->delete();
        return redirect('/properties');
    }
}
