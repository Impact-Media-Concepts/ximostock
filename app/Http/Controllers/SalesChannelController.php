<?php

namespace App\Http\Controllers;

use App\Models\SalesChannel;
use App\Models\WorkSpace;
use App\Rules\ValidWorkspaceKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class SalesChannelController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'orderby' => ['nullable', 'string', Rule::in(['name', 'channel_type','updated_at'])],
            'order' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
        ]);

        $current_workspace = (int) session('active_workspace_id');
        $request->orderby = $request->orderby ?? 'updated_at';
        $request->order = $request->order ?? 'desc';

        $query = SalesChannel::where('work_space_id', $current_workspace);
        if ($request->orderby && $request->order) {
            $query->orderBy($request->orderby, $request->order);
        }

        $salesChannels = $query->paginate(14);

        // Only return products in JSON if the request is an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'saleschannels' => $salesChannels,
                'orderby' => $request->orderby,
                'order' => $request->order,
            ]);
        }

        $results = [
            'saleschannels' => $salesChannels,
            'orderby' => $request->orderby,
            'order' => $request->order,
        ];
        
        //Log::debug($results['salesChannels']);
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
        $current_workspace = (int) session('active_workspace_id');
        $attributes = $request->validate([
            'name' => ['required', 'string'],
            'type' => ['required', Rule::in(['WooCommerce'])],
            'url' => ['required'],
            'flavicon_url' => ['nullable'],
            'api_key' => ['required'],
            'secret' => ['nullable']
        ]);
        $attributes = $attributes + ['work_space_id' => $current_workspace];

        // Store the sales channel
        $salesChannel = SalesChannel::create($attributes);
        //return responce
        if($salesChannel){
            return response()->json(['message' => 'Saleschannel created successfully'], 200);
        }else{
            return response()->json(['error' => 'data niet goed ingevult'], 404);
        }
    }

    public function show(SalesChannel $salesChannel)
    {
        return view('salesChannel.show', [
            'salesChannel' => $salesChannel
        ]);
    }

    public function update(Request $request, SalesChannel $salesChannel)
    {

        //validate
        $attributes = $request->validate([
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
        //validate
        $attributes = $request->validate([
            'saleschannels' => ['required', 'array'],
            'saleschannels.*' => ['required', 'numeric', Rule::exists('sales_channels', 'id')]
        ]);

        //authorze

        SalesChannel::whereIn('id', $attributes['saleschannels'])->delete();


        return response()->json(['message' => 'Saleschannels deleted successfully'], 200);

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

    public function deleteById(Request $request, $salesChannel){
        $salesChannel = Saleschannel::findOrFail($salesChannel);

        if($salesChannel){
            $salesChannel->delete();
            return response()->json(['message' => 'Saleschannel deleted successfully'], 200);
        }else{
            return response()->json(['error' => 'Saleschannel not found'], 404);
        }
    }

    public function updateById(Request $request, $salesChannelId)
    {

        //validate
        $attributes = $request->validate([
            'name' => ['required', 'string'],
            'url' => ['required'],
            'api_key' => ['required'],
            'secret' => ['nullable']
        ]);
        $salesChannel = Saleschannel::findOrFail($salesChannelId);

        //update
        if($salesChannel){
            $salesChannel->update($attributes);
            return response()->json(['message' => 'Saleschannel updated successfully'], 200);
        }else{
            return response()->json(['error' => 'Saleschannel not found'], 404);
        }
    }
}
