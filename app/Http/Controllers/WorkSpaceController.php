<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkSpace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WorkSpaceController extends Controller
{
    protected $currentUser;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentUser = Auth::user();
            return $next($request);
        });
    }

    

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUser = $this->currentUser;
        $workspaces = $this->currentUser->role === 'admin' 
            ? WorkSpace::all() 
            : WorkSpace::where('id', $this->currentUser->work_space_id)->get();
        
            
        return view('workspace.index', compact('workspaces', 'currentUser'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ($this->currentUser->role !== 'admin') {
            return $this->unauthorizedResponse();
        }
        return view('workspace.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $workspace = WorkSpace::create($request->all());

            return response()->json(
                [
                    'message' => 'Workspace has been added succesfully',
                    'workspace' => $workspace
                ], 
            200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();

            return response()->json(['message' => 'Add request has failed'], 500);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(WorkSpace $workspace)
    {
        if ($this->isAuthorized($workspace)) {
            return view('workspace.show', compact('workspace'));
        }

        return $this->unauthorizedResponse();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkSpace $workspace)
    {
        if ($this->isAuthorized($workspace)) {
            return view('workspace.edit', compact('workspace'));
        }

        return $this->unauthorizedResponse();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkSpace $workspace)
    {
        if ($this->isAuthorized($workspace)) {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $workspace->update($request->all());

            return redirect()->route('workspaces')
                             ->with('success', 'Workspace updated successfully.');
        }

        return $this->unauthorizedResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkSpace $workspace)
    {
        if (!$workspace) {
            return response()->json(['message' => 'Workspace not found'], 404);
        }

        $workspace->delete();
        return response()->json(['message' => 'Workspace deleted successfully'], 200);
    }

    public function switch(Request $request)
    {
        $workspaceId = $request->input('workspace_id');
        $workspace = WorkSpace::find($workspaceId);
        if ($workspace && $this->isAuthorized($workspace)) {
            session(['active_workspace_id' => $workspaceId]);
            return redirect()->back()->with('success', 'Workspace switched successfully.');
        }
        return redirect()->back()->with('error', 'Workspace not found.');
    }

    /**
     * Check if the current user is authorized to access the workspace.
     */
    protected function isAuthorized(WorkSpace $workspace)
    {
        return $this->currentUser->role === 'admin' || $workspace->id === $this->currentUser->work_space_id;
    }

    /**
     * Return an unauthorized response.
     */
    protected function unauthorizedResponse()
    {
        return redirect()->route('dashboard')
                         ->with('errors', 'Unauthorized response');
    }


}
