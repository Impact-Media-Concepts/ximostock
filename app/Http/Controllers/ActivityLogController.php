<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\WorkSpace;
use App\Rules\ValidWorkspaceKeys;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'orderby' => ['nullable', 'string', Rule::in(['name', 'subject_type', 'created_at', 'event'])],
            'order' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
        ]);
        $current_workspace = (int) session('active_workspace_id');
        $request->orderby = $request->orderby ?? 'updated_at';
        $request->order = $request->order ?? 'desc';

        // Fetch all activity log entries
        $query = Activity::where(function($query) use ($current_workspace) {
            $query->whereJsonContains('properties->old->work_space_id', $current_workspace)
                ->orWhereJsonContains('properties->attributes->work_space_id', $current_workspace);
        });

        // Check if we need to sort by name
        if ($request->orderby === 'name') {
            $query->leftJoin('users', 'activity_log.causer_id', '=', 'users.id')
                ->select('activity_log.*', 'users.name as user_name') // Get user name for sorting
                ->orderBy('user_name', $request->order);
        } else {
            // Order by other fields if not sorting by user name
            $query->orderBy($request->orderby, $request->order);
        }

        // Paginate the results
        $activities = $query->paginate(14);

        // Replace 'causer_id' with the full user object or "System"
        $activities->getCollection()->transform(function ($activity) {
            if (!$activity->causer) {
                $activity->causer = (object) ['name' => 'System']; // Create a "System" user object
            } else {
                $activity->causer = $activity->causer; // Get the full causer object
            }
            return $activity;
        });

        $activities->appends($_GET)->links();

        $results = [
            'activityLogs' => $activities,
            'orderby' => $request->orderby,
            'order' => $request->order,
        ];
        // Pass the activities to the view
        return view('activitylog.index', $results);
    }
}
