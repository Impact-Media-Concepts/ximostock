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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $users = User::all();

        return view('user.index', compact('users'));
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
        //mail user to nptify acautn deleted
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/users');
    }

    public function create(Request $request){
        $roles = ['admin', 'manager', 'supplier'];
        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Log the incoming request data
        Log::info('Store method called with request data: ', $request->all());

        // Validate the request data
        $validatedData = $request->validate([
            'work_space_id' => 'nullable|exists:work_spaces,id',
            'name' => 'required|string|max:255',
            'role' => 'required|in:admin,manager,supplier',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Log the validated data
            Log::info('Validated data: ', $validatedData);

            // Create a new user
            $user = new User();
            $user->work_space_id = $request->work_space_id;
            $user->name = $request->name;
            $user->role = $request->role;
            $user->email = $request->email;
            $user->email_verified_at = $request->email_verified_at;
            $user->password = bcrypt($request->password);
            $user->remember_token = $request->remember_token;
            $user->save();

            // Log the created user
            Log::info('User created successfully: ', $user->toArray());

            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error creating user: ', ['error' => $e->getMessage()]);

            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred while creating the user.']);
        }
    }
}