<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */

    protected $guard = 'admin';

    public function login(Request $request)
    {

        // Validate info with following rules
        $rules = array(
            'username' => 'required',
            'password' => 'required|min:5'
        );

        // Run the validation
        $validator = validator($request->all(), $rules);

        //If validator fails redirect back to form
        if ($validator->fails())
        {
            return redirect()->view('auth.login')
                ->withErrors($validator)                    //Send all errors back to login page
                ->withInput(Input::except('password'));     //Send back input besides password
        } else
        {
            //Remember me ?
            $remember = Input::get('remember');

            //Create our user data for auth
            $userdata = array(
                'username'  => Input::get('username'),
                'password'  => Input::get('password')
            );

            //Attempt to login
            Auth::attempt($userdata, $remember);

            if(!Auth::check())
                abort(403, 'Unauthorized action.');

            //Validation correct
            return redirect()->route('home');

        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
