<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Password;
use App\Notifications\MailResetPasswordToken;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller {
    /* This action send the password reset link to user on email */

    public function sendPasswordResetToken(Request $request) {
        if ($request->isMethod('post')) {
            $errors = new MessageBag; // initiate MessageBag

            request()->validate([
                'email' => 'required|email',
            ]);

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                $errors = new MessageBag(['email' => ['Invalid Email.']]);
                return Redirect::back()->withErrors($errors)->withInput();
            }

            //create a new token to be sent to the user. 
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => str_random(60), //change 60 to any length you want
                'created_at' => Carbon::now()
            ]);

            $tokenData = DB::table('password_resets')
                            ->where('email', $request->email)->first();

            $token = $tokenData->token;
            $email = $request->email;

            $user->notify(new MailResetPasswordToken($token));
            return Redirect::to("admin/forgot-password")->with('status', 'Password Reset Token has sent successfully !!!');
        }
        return view('admin.forgotpassword');
    }

    public function resetPassword(Request $request, $token) {
        if ($request->isMethod('post')) {
            request()->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:6',
            ]);
            $password = $request->password;
            $tokenData = DB::table('password_resets')->where('token', $token)->first();

            $user = User::where('email', $tokenData->email)->first();
            if (!$user)
                return redirect()->to('/'); //or wherever you want

            $user->password = Hash::make($password);
            $user->update();

            //do we log the user directly or let them login and try their password for the first time ? if yes 
            Auth::login($user);

            // If the user shouldn't reuse the token later, delete the token 
            DB::table('password_resets')->where('email', $user->email)->delete();
            return Redirect::to("admin/dashboard")->with('status', 'Password changed successfully !!!');
        }
        return view('admin.reset', ['token' => $token]);
    }

}
