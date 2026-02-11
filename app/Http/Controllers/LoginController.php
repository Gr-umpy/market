<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function show() {
        return view('login');
    }
    public function login(LoginRequest $request)
    {
        if(Auth::attempt($request->validated())) {
            return redirect()->intended(route('home'));
        }

        return redirect()->route('login.show')->withInput();
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.show');
    }

    public function showRegister() {
        return view('register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }
}
