<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index(Request $request){
        $request->validate([
            'orderby' => ['nullable', 'string', Rule::in(['name', 'company_name', 'website', 'phone_number','updated_at'])],
            'order' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
        ]);

        $current_workspace = (int) session('active_workspace_id');
        $request->orderby = $request->orderby ?? 'updated_at';
        $request->order = $request->order ?? 'desc';
        $query = Supplier::where('work_space_id', $current_workspace);

        if ($request->orderby && $request->order) {
            $query->orderBy($request->orderby, $request->order);
        }

        $suppliers = $query->paginate(12);

        $result = [
            'suppliers' => $suppliers,
            'orderby' => $request->orderby,
            'order' => $request->order,
        ];

        return view('supplier.index', $result);
    }

    public function deleteById(Request $request, $id){
        $supplier = Supplier::find($id);
        if(!$supplier){
            return response()->json(['message' => 'Supplier not found'], 404);
        }
        $supplier->delete();
        return response()->json(['message' => 'Supplier created successfully'], 200);
    }

    public function bulkDelete(Request $request){
        //validate
        $attributes = $request->validate([
            'suppliers' => ['required', 'array'],
            'suppliers.*' => ['required', 'numeric', Rule::exists('suppliers', 'id')]
        ]);
        $deletedCount = Supplier::whereIn('id', $attributes['suppliers'])->delete();
        if ($deletedCount === count($attributes['suppliers'])) {
            return response()->json(['message' => 'Suppliers deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to delete all suppliers'], 500);
        }
    }

    public function update(Request $request){
        Log::debug("test");
        $attributes = $request->validate([
            'id' => ['required', 'numeric', Rule::exists('suppliers', 'id')],
            'name' => ['required', 'string'],
            'company_name' => ['required', 'string'],
            'website' => ['required', 'url'],
            'phone_number' => ['required', 'numeric'],
        ]);
        Log::debug($attributes);

        $supplier = Supplier::find($attributes['id']);

        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }
        $supplier->name = $attributes['name'];
        $supplier->company_name = $attributes['company_name'];
        $supplier->website = $attributes['website'];
        $supplier->phone_number = $attributes['phone_number'];
        $supplier->save();
        return response()->json(['message' => 'Supplier updated successfully'], 200);
    }

    public function store(Request $request){
        $attributes = $request->validate([
            'name' => ['required', 'string'],
            'company_name' => ['required', 'string'],
            'website' => ['required', 'url'],
            'phone_number' => ['required', 'numeric'],
        ]);

        $attributes['work_space_id'] = (int) session('active_workspace_id');
        $supplier = Supplier::create($attributes);

        return response()->json(['message' => 'Supplier created successfully'], 200);
    }
}
