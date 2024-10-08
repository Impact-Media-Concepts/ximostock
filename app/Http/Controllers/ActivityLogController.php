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
        $workspaces = WorkSpace::all();
        $activeWorkspace = $request['workspace'];
        // Fetch all activity log entries
        $activities = Activity::latest()->paginate(14);
        $results = [
            'activities' => $activities,
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace,
        ];
        // Pass the activities to the view
        return view('activitylog.index', $results);
    }
}
