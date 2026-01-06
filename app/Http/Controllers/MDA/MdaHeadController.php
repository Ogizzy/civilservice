<?php

namespace App\Http\Controllers\MDA;

use App\Models\MDA;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MdaHeadController extends Controller
{
    public function index()
    {
        $mdas = MDA::with('head')->paginate(5, ['*'], 'mdas_page'); // paginate MDAs

        $mdaHeads = User::whereHas('role', function ($q) {
            $q->where('role', 'MDA Head');
        })
            ->with('mda')
            ->paginate(5, ['*'], 'heads_page'); // paginate MDA Heads

        $users = User::whereHas('role', function ($q) {
            $q->where('role', 'MDA Head');
        })->get();

        return view('admin.mda.mda-head.index', compact('mdas', 'mdaHeads', 'users'));
    }

    public function edit(MDA $mda)
    {
        $users = User::whereHas('role', function ($q) {
            $q->where('role', 'MDA Head');
        })->get();

        return view('admin.mda.mda-head.assign', compact('mda', 'users'));
    }


    public function update(Request $request, MDA $mda)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Remove previous head (if any)
        User::where('mda_id', $mda->id)->update(['mda_id' => null]);

        // Assign new head
        $user = User::findOrFail($request->user_id);
        $user->mda_id = $mda->id;
        $user->save();

         $notification = array(
            'message' => 'MDA Head Assigned Successfully.',
            'alert-type' => 'success'
        );


        return redirect()->route('mda-heads.index')->with($notification);
    }
}
