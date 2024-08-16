<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index(Request $request){
        $current_workspace = (int) session('active_workspace_id');

        $suppliers = Supplier::where('work_space_id', $current_workspace)->paginate(12);

        $result = [
            'suppliers' => $suppliers,
        ];



        return view('supplier.index', $result);
    }
}
