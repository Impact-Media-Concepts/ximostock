<?php

namespace App\Http\Controllers;

use App\Models\ProductProperty;
use App\Models\Property;
use App\Models\SalesChannel;
use App\Models\WorkSpace;
use App\Rules\ValidWorkspaceKeys;
use App\WooCommerceManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Spatie\Activitylog\Facades\LogBatch;


class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'orderby' => ['nullable', 'string', Rule::in(['name', 'channel_type','updated_at'])],
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

    public function update(Property $property) //dit werkt nu zo omdat wij opties als json opslaan in de database en niet in een aparte tabel
    {
        $attributes = request()->validate([
            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'string'],
            'name' => ['required', 'string']
        ]);

        LogBatch::startBatch();
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
        $wooCommerce = new WooCommerceManager;
        $wooCommerce->updatePropertyToSaleschannels($property);
        LogBatch::endBatch();

        return redirect()->back();
    }

    public function bulkDelete(Request $request)
    {
        Gate::authorize('bulkDelete', [Property::class, $request['properties']]);
        LogBatch::startBatch();
        $attributes = $request->validate([
            'properties' => ['required', 'array'],
            'properties.*' => ['required', 'numeric', Rule::exists('properties', 'id')]
        ]);
        $woocommerce = new WooCommerceManager;
        $woocommerce->deleteProperties($attributes['properties']);
        Property::whereIn('id', $attributes['properties'])->delete();
        LogBatch::endBatch();
        return redirect('/properties');
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
