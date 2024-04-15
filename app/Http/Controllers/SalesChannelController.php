<?php

namespace App\Http\Controllers;

use App\Models\SalesChannel;
use App\Rules\ValidWorkspaceKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SalesChannelController extends Controller
{
    public function index(){
        if(Auth::user()->role === 'admin'){
            $attributes = request()->validate([
                'workspace' => ['required', new ValidWorkspaceKeys]
            ]);

            $workspace = $attributes['workspace'];
        }else{ 
            $workspace = Auth::user()->work_space_id;
        }

        return view('salesChannel.index',[
            'salesChannels' => SalesChannel::where('work_space_id', $workspace)->get()
        ]);
    }

    public function create(){
        return view('salesChannel.create');
    }

    public function store(){
        //authroize

        //validate
        $attributes = request()->validate([
            'name' => ['required', 'string'],
            'type' => ['required', Rule::in(['WooCommerce'])],
            'url' => ['required'],
            'flavicon_url' => ['nullable'],
            'api_key' => ['required'],
            'secret' => ['nullable']
        ]);
        $attributes += ['work_space_id' => Auth::user()->work_space_id]; // temperary
        //store
        SalesChannel::create($attributes);

        //return
        return redirect('/saleschannels');
    }
}
