<?php

namespace App\Http\Controllers;

use App\Models\SalesChannel;
use App\Models\WorkSpace;
use App\Rules\ValidWorkspaceKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SalesChannelController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            $request->validate([
                'workspace' => ['required', new ValidWorkspaceKeys]
            ]);
            $workspaces = WorkSpace::all();
            $activeWorkspace = $request['workspace'];
            $workspace = $activeWorkspace;
        } else {
            $workspaces = null;
            $activeWorkspace = null;
            $workspace = Auth::user()->work_space_id;
        }
        
        $results = [
            'activeWorkspace' => $activeWorkspace,
            'workspaces' => $workspaces,
            'sidenavActive' => 'saleschannels',
            'salesChannels' => SalesChannel::where('work_space_id', $workspace)->get()
        ];

        return view('salesChannel.index', $results);
    }

    public function create(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            $request->validate([
                'workspace' => ['required', new ValidWorkspaceKeys]
            ]);
            $workspaces = WorkSpace::all();
            $activeWorkspace = $request['workspace'];
        } else {
            $workspaces = null;
            $activeWorkspace = null;
        }
        
        $results = [
            'activeWorkspace' => $activeWorkspace,
            'workspaces' => $workspaces,
            'sidenavActive' => 'saleschannels'
        ];

        return view('salesChannel.create', $results);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            // Validate for admin
            $attributes = $request->validate([
                'name' => ['required', 'string'],
                'type' => ['required', Rule::in(['WooCommerce'])],
                'url' => ['required'],
                'flavicon_url' => ['nullable'],
                'api_key' => ['required'],
                'secret' => ['nullable'],
                'work_space_id' => ['required', 'numeric', Rule::exists('work_spaces', 'id')]
            ]);
    
            $workspaceId = $attributes['work_space_id'];
        } else {
            // Validate for normal users
            $attributes = $request->validate([
                'name' => ['required', 'string'],
                'type' => ['required', Rule::in(['WooCommerce'])],
                'url' => ['required'],
                'flavicon_url' => ['nullable'],
                'api_key' => ['required'],
                'secret' => ['nullable']
            ]);
            $attributes += ['work_space_id' => Auth::user()->work_space_id];
    
            $workspaceId = Auth::user()->work_space_id;
        }
    
        // Store the sales channel
        SalesChannel::create($attributes);
    
        // Build the redirect URL
        $redirectUrl = '/saleschannels';
        if (Auth::user()->role === 'admin') {
            $redirectUrl .= '?workspace=' . $workspaceId;
        }
    
        // Return the redirect
        return redirect($redirectUrl);
    }

    public function show(SalesChannel $salesChannel)
    {
        return view('salesChannel.show', [
            'salesChannel' => $salesChannel
        ]);
    }

    public function update(SalesChannel $salesChannel)
    {

        //validate
        $attributes = request()->validate([
            'name' => ['required', 'string'],
            'type' => ['required', Rule::in(['WooCommerce'])],
            'url' => ['required'],
            'flavicon_url' => ['nullable'],
            'api_key' => ['required'],
            'secret' => ['nullable']
        ]);

        //update
        $salesChannel->update($attributes);

        //return
        return redirect()->back();
    }

    public function bulkDelete(Request $request)
    {
        //authorze

        //validate
        $attributes = $request->validate([
            'saleschannels' => ['required', 'array'],
            'saleschannels.*' => ['required', 'numeric', Rule::exists('sales_channels', 'id')]
        ]);
        SalesChannel::whereIn('id', $attributes['saleschannels'])->delete();

        return redirect()->back();
    }

    public function archive(Request $request)
    {
        $request->validate([
            'workspace' => ['required', new ValidWorkspaceKeys]
        ]);
        $results = [
            'perPage' => $request->input('perPage', 20),
            'search' => $request['search'],
            'sidenavActive' => 'archive',
            'workspaces' => WorkSpace::all(),
            'activeWorkspace' => $request['workspace'],
            'salesChannels' => SalesChannel::onlyTrashed()->get()
        ];
        return view('salesChannel.archive', $results);
    }

    public function restore(Request $request)
    {
        $attributes = $request->validate([
            'saleschannels' => ['array', 'required'],
            'saleschannels.*' => ['numeric', 'required']
        ]);
        SalesChannel::withTrashed()->whereIn('id', $attributes['saleschannels'])->restore();
        return redirect()->back();
    }

    public function forceDelete(Request $request)
    {
        $attributes = $request->validate([
            'saleschannels' => ['array', 'required'],
            'saleschannels.*' => ['numeric', 'required']
        ]);
        SalesChannel::withTrashed()->whereIn('id', $attributes['saleschannels'])->forceDelete();
        return redirect()->back();
    }
}
