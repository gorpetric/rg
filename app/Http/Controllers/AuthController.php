<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        if(!Auth::attempt($request->only(['email', 'password']))) {
            return redirect()->back()->withInput($request->only(['email']))->withErrors([
                'password' => 'Kriva lozinka',
            ]);
        }

        return redirect()->route('home');
    }

    public function changePassword()
    {
        return view('auth.changepassword');
    }

    public function postChangePassword(Request $request)
    {
        $this->validate($request, [
            'old' => 'required',
            'new' => 'required',
        ]);

        $user = Auth::user();

        if(!Hash::check($request->old, $user->password)) {
            return redirect()->back()->withErrors([
                'old' => 'Stara lozinka nije točna.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new),
        ]);
        $user->touch();

        logdb('User changed password', [
            'user' => auth()->user()->id,
        ]);

        return redirect()->route('home');
    }
}
