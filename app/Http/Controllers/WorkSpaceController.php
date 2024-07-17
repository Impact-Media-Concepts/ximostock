<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkSpace;

class WorkSpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workspaces = WorkSpace::all();
        return view('workspace.index', compact('workspaces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('workspace.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        WorkSpace::create($request->all());

        return redirect()->route('workspaces')
                         ->with('success', 'Workspace created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkSpace $workspace)
    {
        return view('workspace.show', compact('workspace'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkSpace $workspace)
    {
        return view('workspace.edit', compact('workspace'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkSpace $workspace)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $workspace->update($request->all());

        return redirect()->route('workspaces')
                         ->with('success', 'Workspace updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkSpace $workspace)
    {
        $workspace->delete();

        return redirect()->route('workspaces')
                         ->with('success', 'Workspace deleted successfully.');
    }
}
