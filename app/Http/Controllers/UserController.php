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
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $roles = ['admin', 'manager', 'supplier'];
        return view('user.index', compact('users', 'roles'));
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

    public function destroy(Request $request, int $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
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

    public function edit(User $user)
    {
        $roles = ['admin', 'manager', 'supplier'];
        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request)
    {
        Log::info($request->all());


        if (Auth::user()->role === 'admin') {
            $validatedData = $request->validate([
                'id' => 'required|exists:users,id',
                'work_space_id' => 'nullable|exists:work_spaces,id',
                'name' => 'required|string|max:255',
                'role' => 'required|in:admin,manager,supplier',
                'email' => 'required|email',
                'password' => 'nullable|string|min:8|confirmed',
            ]);
        } else {
            $validatedData = $request->validate([
                'id' => 'required|exists:users,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'password' => 'nullable|string|min:8|confirmed',
            ]);
        }
        Log::info($validatedData);

        

        Log::info($validatedData);

        $user = User::find($validatedData['id']);

        if (!$user) {
            return redirect()->back()->withErrors(['error' => 'User not found.']);
        }

        if (Auth::user()->role === 'admin') {
            $user->work_space_id = $validatedData['work_space_id'];
            $user->role = $validatedData['role'];
        }

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if (isset($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }

        $user->save();
        
        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);

    }

    public function theme(Request $request) {
        $user = $request->user();
        return view('user.theme', compact('user'));
    }

    public function updateThemeById(Request $request, int $id) {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'primary_color' => 'required|string|max:255',
            'secondary_color' => 'required|string|max:255',
            'background_color' => 'required|string|max:255',
            'text_color' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->primary_color = $request->primary_color;
        $user->secondary_color = $request->secondary_color;
        $user->background_color = $request->background_color;
        $user->text_color = $request->text_color;

        $user->save();

        return response()->json(['message' => 'Theme updated successfully'], 200);
    }


}