<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Validator;
use DB;
use BackAuth;
use Route;

class AuthController extends Controller {

    protected $redirect = 'DashboardController@index';

    public function displayLoginForm() {
//        echo password_hash('admin', PASSWORD_DEFAULT);
//        exit;
        if (self::check()) {
            // If authenticated user redirect to BackEnd
            return redirect(action(config('app.backend_namespace') . $this->redirect));
        }
        return view('backend.auth.login');
    }

    public function login() {
        $Request = request();
        // Validate Request
        $validationRules = array(
            'email' => 'required|email',
            'password' => 'required',
        );
        $validator = Validator::make($Request->all(), $validationRules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Authenticate
        $Model = new \App\Models\Admins;
        $record = $Model->where('email', '=', $Request['email'])->first();
        if (password_verify($Request['password'], $record['password'])) {
            // Set session parameters
            $parameters = array(
                'admin' => $record['email'],
                'idle_time' => time(),
            );
            session($parameters);
            return redirect(action(config('app.backend_namespace') . $this->redirect));
        } else {
            return back()->with('login_failure', trans('form.login.failure'));
        }
    }

    public function logout() {
        return self::doLogout();
    }

    public static function doLogout() {
        session()->flush();
        return redirect(action(config('app.backend_namespace') . 'AuthController@displayLoginForm'));
    }

    public static function check() {
        return ( session('admin') ) ? TRUE : FALSE;
    }

}
