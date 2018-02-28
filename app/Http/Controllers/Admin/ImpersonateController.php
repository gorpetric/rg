<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImpersonateController extends Controller
{
    public function store(User $user)
    {
        session()->put('impersonate', $user->id);

        return redirect()->route('home');
    }

    public function destroy()
    {
        if(session()->has('impersonate')) session()->forget('impersonate');

        return redirect()->route('admin.index');
    }
}
