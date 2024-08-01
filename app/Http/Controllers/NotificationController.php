<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\ValidWorkspaceKeys;
use App\Models\WorkSpace;


class NotificationController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            $request->validate([
                'workspace' => ['required', new ValidWorkspaceKeys]
            ]);
            $workspaces = WorkSpace::all();
            $activeWorkspace = $request['workspace'];
            $workspaceId = $request['workspace'];
        } else {
            $workspaces = null;
            $activeWorkspace = null;
            $workspaceId = Auth::user()->work_space_id;
        }
        $toProcessSales = Sale::where('status', 'waiting_for_location')
            ->whereHas('product', function ($query) use ($workspaceId) {
                $query->where('work_space_id', $workspaceId);
            })
            ->get();
        $errorSales = Sale::where('status', 'error')
        ->whereHas('product', function ($query) use ($workspaceId) {
            $query->where('work_space_id', $workspaceId);
        })
        ->get();

        return view('notification.index', [
            'sidenavActive' => 'notifications',
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace,
            'toProcessSales' => $toProcessSales,
            'errorSales' => $errorSales
        ]);
    }
}
