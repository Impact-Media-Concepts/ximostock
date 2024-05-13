<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WorkSpace;
use App\Rules\ValidWorkspaceKeys;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request){
        $request->validate([
            'workspace' => ['required', new ValidWorkspaceKeys]
        ]);
        $workspaces = WorkSpace::all();
        $activeWorkspace = $request['workspace'];

        $attributes = [
            'sidenavActive' => 'users',
            'users' => User::with('workspace')->get(),
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace
        ];
        return view('user.index', $attributes);
    }

    public function show(Request $request, User $user){
        $request->validate([
            'workspace' => ['required', Rule::exists('work_spaces', 'id')]
        ]);
        $workspaces = WorkSpace::all();
        $activeWorkspace = $request['workspace'];

        $attributes = [
            'sidenavActive' => 'users',
            'user' => $user,
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace
        ];
        return view('user.show', $attributes);
    }
}
