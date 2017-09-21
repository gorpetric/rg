<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.index', compact('users'));
    }

    public function syncUserRoles(Request $request, User $user)
    {
        if($user->id == Auth::user()->id && !$request['role_admin']) {
            return redirect()->back();
        }

        $newRoles = [];
        foreach(array_keys(array_except($request->all(), '_token')) as $key) {
            $newRoles[] = substr($key, 5); // remove 'role_' with substr 5
        }

        $user->syncRoles($newRoles);
        return redirect()->back();
    }
}
