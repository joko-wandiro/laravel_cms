<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use BackAuth;
use Route;

class BackEndAuthenticate {

    protected $expire = 1800;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        // Redirect unauthenticated user to Login Page
        if (!BackAuth::check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect(action(config('app.backend_namespace') . 'AuthController@displayLoginForm'));
            }
        }
        // Automatically logout user while idle time reach expire time
        $currentTime = time();
        $idleTime = session('idle_time');
        if (($currentTime - $idleTime) > $this->expire) {
            return BackAuth::doLogout();
        }
        // Update idle time
        session(array('idle_time' => $currentTime));
        return $next($request);
    }

}
