<?php

namespace App\Http\Controllers;

use App\Models\SalesChannel;
use App\Rules\ValidWorkspaceKeys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
