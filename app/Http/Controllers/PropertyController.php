<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Rules\ValidPropertyKeys;
use Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    public function index()
    {
        return view('property.index', [
            'sidenavActive' => 'properties',
            'properties' => Property::where('work_space_id', Auth::user()->work_space_id)->get(),
        ]);
    }

    public function show(Property $property)
    {
        return view('property.show', [
            'sidenavActive' => 'properties',
            'property' => $property
        ]);
    }

    public function update(Property $property)
    {
        Gate::authorize('update-property', [$property]);

        $attributes = request()->validate([
            'options' => ['nullable', 'array'],
            'options.*' => ['required', 'string'],
            'name' => ['required', 'string']
        ]);
        $options = [];

        foreach ($attributes['options'] as $option) {
            array_push($options, $option);
        }
        $type = $property->type;
        $values = json_encode(['type' => $type, 'options' => $options]);

        $property->update([
            'name' => $attributes['name'],
            'values' => $values
        ]);
        return redirect()->back();
    }

    public function bulkDelete()
    {
        $request = request();
        Gate::authorize('bulk-property', [$request['properties']]);

        $attributes = $request->validate([
            'properties' => ['required', 'array'],
            'properties.*' => ['required', 'numeric', Rule::exists('properties', 'id')]
        ]);
        Property::whereIn('id', $attributes['properties'])->delete();
        return redirect('/properties');
    }

    public function create()
    {
        return view('property.create', []);
    }

    public function store()
    {
        $request = request();
        //authorize
        
        //validate
        $attributes = $request->validate([
            'name' => ['required', 'max:255'],
            'type' => ['required', Rule::in(['singleselect', 'multiselect', 'number', 'bool', 'text'])],
            'options' => ['nullable', 'array'],
            'options.*' => ['required', 'max:255'],
        ]);
        if (!isset($attributes['options'])) {
            $attributes['options'] = [];
        }

        //store
        $values = json_encode(['type' => $attributes['type'], 'options' => $attributes['options']]);
        Property::create([
            'name' => $attributes['name'],
            'work_space_id' => Auth::user()->work_space_id,
            'values' => $values
        ]);

        return redirect('/properties');
    }
}
