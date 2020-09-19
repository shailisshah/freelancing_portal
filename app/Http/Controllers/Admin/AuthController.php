<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Illuminate\Support\MessageBag;

class AuthController extends Controller {
    /* This action perform the user Login */

    public function login(Request $request) {
        $errors = new MessageBag; // initiate MessageBag
        $roles = \App\Models\Roles::where('status', 1)->get();

        if ($request->isMethod('post')) {
            request()->validate([
                'email' => 'required',
                'password' => 'required',
                'role_id' => 'required',
            ]);
            $credentials = $request->only('email', 'password', 'role_id');
            if (Auth::attempt($credentials)) {
                // Authentication passed...
                return Redirect::to("admin/dashboard")->with('status', 'Great! You have Successfully loggedin');
            } else {
                $errors = new MessageBag(['email' => ['Email and/or password invalid.']]);
                return Redirect::back()->withErrors($errors)->withInput();
            }
        }
        return view('admin.login', ['roles' => $roles]);
    }

    /* This action perform the user Registartion */

    public function register(Request $request) {
        $roles = \App\Models\Roles::where('status', 1)->get();
        if ($request->isMethod('post')) {
            request()->validate([
                'name' => 'required|string|max:255',
                'role_id' => 'required',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);
            $data = $request->all();
            $user = User::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'role_id' => $data['role_id'],
                        'password' => bcrypt($data['password']),
            ]);
            $this->guard()->login($user);
            return Redirect::to("admin/dashboard")->with('status', 'Welcome !!!');
        }
        return view('admin.register', ['roles' => $roles]);
    }

    /* This action perform the user logout */

    public function logout(Request $request) {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return Redirect::to("/")->withSuccess('Great! You have Successfully logged out');
    }

    protected function guard() {
        return Auth::guard();
    }

}
