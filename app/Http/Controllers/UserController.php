<?php
namespace App\Http\Controllers;

use App\Mail\SetupAccount;
use App\Models\User;
use App\Models\WorkSpace;
use App\Rules\ValidWorkspaceKeys;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

    public function destroy(Request $request, User $user){
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/users');
    }

    public function create(Request $request){
        $request->validate([
            'workspace' => ['required', Rule::exists('work_spaces', 'id')]
        ]);
        $workspaces = WorkSpace::all();
        $activeWorkspace = $request['workspace'];

        $attributes = [
            'sidenavActive' => 'users',
            'workspaces' => $workspaces,
            'activeWorkspace' => $activeWorkspace
        ];
        return view('user.create', $attributes);
    }

    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', Rule::in(['manager', 'admin', 'supplier'])],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'workspace'=> ['required']
        ]);
        $password = Str::random(10);
        //create a user generate a password and send an email for them to varify and create a new password.
        $user = User::create([
            'name' => $request['name'],
            'role' => $request['role'],
            'email' => $request['email'],
            'work_space_id' => $request['workspace'],
            'password' => Hash::make($password)
        ]);
        Mail::to($request['email'])->send(new SetupAccount($password, $request['email'], $request['name'], $request['role']));
        return redirect('/products');
    }
}