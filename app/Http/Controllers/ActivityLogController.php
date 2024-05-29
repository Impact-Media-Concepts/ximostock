<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\WorkSpace;
use App\Rules\ValidWorkspaceKeys;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'workspace' => ['required', new ValidWorkspaceKeys]
        ]);
        $workspaces = WorkSpace::all();
        $activeWorkspace = $request['workspace'];
        // Fetch all activity log entries
        $activities = Activity::latest()->paginate(40);

        $results = [
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace,
            'activities'=> $activities,
            'sidenavActive' => 'activityLog'
        ];
        // Pass the activities to the view
        return view('activitylog.index', $results);
    }
}
