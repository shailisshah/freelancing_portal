<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Illuminate\Support\MessageBag;
use Socialite;
use Exception;
use App\Services\SocialFacebookAccountService;
use Laravel\Socialite\Contracts\User as ProviderUser;
use App\Notifications\MailAfterRegistartion;

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
	    //sent welcome mail vaya Laravel notify
            $user->notify(new MailAfterRegistartion());
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

    public function redirectToGoogle(Request $request) {
        session(['hidden_role_id' => $request->hidden_role_id]);
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        try {

            $user = Socialite::driver('google')->user();
            $role_id = session('hidden_role_id');

            $finduser = User::where('google_id', $user->id)->first();

            if ($finduser) {

                $this->guard()->login($finduser);

                return Redirect::to("admin/dashboard")->with('status', 'Welcome !!!');
            } else {

                $newUser = User::create([
                            'name' => $user->name,
                            'email' => $user->email,
                            'google_id' => $user->id,
                            'role_id' => $role_id,
                            'profile_pic' => $user->avatar_original,
                ]);


                $this->guard()->login($newUser);
                $newUser->notify(new MailAfterRegistartion());

                return Redirect::to("admin/dashboard")->with('status', 'Welcome !!!');
            }
        } catch (Exception $e) {
            return redirect('/')->with('status', 'Something Went Wrong!!!!');
        }
    }

    /**
     * Create a redirect method to facebook api.
     *
     * @return void
     */
    public function redirectToFacebook(Request $request) {
        session(['hidden_role_id_fb' => $request->hidden_role_id_fb]);
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
     */
    public function handleFacebookCallback($provider) {
        try {
            $user = Socialite::driver($provider)->user();
            $input['name'] = $user->getName();
            $input['email'] = $user->getEmail();
            $input['provider'] = $provider;
            $input['provider_id'] = $user->getId();
            $input['role_id'] = session('hidden_role_id_fb');
            $input['profile_pic'] = $user->profileUrl;

            $checkIfExist = User::where('provider', $input['provider'])
                    ->where('provider_id', $input['provider_id'])
                    ->first();
            if ($checkIfExist) {
                $this->guard()->login($checkIfExist);
                return Redirect::to("admin/dashboard#")->with('status', 'Welcome !!!');
            }
            $authUser = User::create($input);
            $authUser->notify(new MailAfterRegistartion());
            $this->guard()->login($authUser);
            return Redirect::to("admin/dashboard#")->with('status', 'Welcome !!!');
        } catch (Exception $e) {
            return redirect('/#')->with('status', 'Something Went Wrong!!!!');
        }
    }

}
