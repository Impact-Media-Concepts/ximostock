<?php

namespace App\Http\Controllers;

use App\Models\ProductProperty;
use App\Models\Property;
use App\Models\WorkSpace;
use App\Rules\ValidWorkspaceKeys;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;


class PropertyController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            $request->validate([
                'workspace' => ['required', new ValidWorkspaceKeys]
            ]);
            $workspaces = WorkSpace::all();
            $properties = Property::where('work_space_id', $request['workspace'])->get();
            $activeWorkspace = $request['workspace'];
            
        }else{
            $workspaces = null;
            $activeWorkspace = null;
            $properties = Property::where('work_space_id', Auth::user()->work_space_id)->get();
        }


        return view('property.index', [
            'sidenavActive' => 'properties',
            'properties' => $properties,
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace
        ]);
    }

    public function show(Request $request, Property $property)
    {
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

        return view('property.show', [
            'sidenavActive' => 'properties',
            'property' => $property,
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace
        ]);
    }

    public function update(Property $property)
    {
        $attributes = request()->validate([
            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'string'],
            'name' => ['required', 'string']
        ]);
        $options = [];
        if ($property->type === 'multiselect' || $property->type === 'singleselect') {
            foreach ($attributes['options'] as $option) {
                array_push($options, $option);
            }


            //correct the values of all linked products to match the new options
            for ($x = 0; $x < Count($property->options); $x++) {
                if ($property->options[$x] != $options[$x]) {
                    //get all ProductsProperies. 
                    //if options[x] === null remove the ProductProperty
                    //if not alter the value
                    $productProperties = ProductProperty::where('property_id', $property->id)->whereJsonContains('property_value', ['value' => $property->options[$x]])->get();
                    foreach ($productProperties as $productProperty) {
                        if ($options[$x] == null) {
                            // remove the option if no value left remove the entry
                            $propValue = json_decode($productProperty->property_value);
                            $propValue = (array)$propValue;
                            if ($property->type === 'multiselect') {
                                $propValue = array_filter($propValue['value'], function ($value) use ($property, $x) {
                                    return $value != $property->options[$x];
                                });
                            } else {
                                $propValue = [];
                            }

                            if (!Count($propValue)) {
                                $productProperty->delete();
                            } else {
                                sort($propValue);
                                $productProperty->property_value = json_encode(['value' => $propValue]);
                                $productProperty->update();
                            }
                        } else {
                            //alter the value of the entry to the new one
                            $productProperty->property_value = str_replace($property->options[$x], $options[$x], $productProperty->property_value);
                            $productProperty->update();
                        }
                    }
                }
            }


            //prepare json values
            $options = array_filter($options, function ($value) {
                return $value != null;
            });

            $options = array_values($options);
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
        Gate::authorize('bulkDelete', [Property::class, $request['properties']]);

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
