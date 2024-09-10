<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkSpace;

class SetActiveWorkspace
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (Auth::check()) {
            $workspaceId = session('active_workspace_id');
            if($workspaceId === null) {
                $role = $user->role;
                if($role === "admin") {
                    $workspace = WorkSpace::first();
                    $workspaceId = $workspace->id;
                    session(['active_workspace_id' => $workspaceId]);
                } else {
                    session(['active_workspace_id' => $user->work_space_id]);
                    return redirect()->route('dashboard');
                }
            }
        }
        return $next($request);
    }
}
