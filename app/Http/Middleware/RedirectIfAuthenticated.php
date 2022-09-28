<?php

namespace App\Http\Middleware;

use App\Common\Enum\AdminCode;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard($guard)->check()) {
            if($guard == 'admin'){
                if ($request->expectsJson()){
                    return responseError(AdminCode::getMsg('notLogin'), AdminCode::getCode('notLogin'));
                }
                return redirect()->route('admin.login');  // 处理登录
            }else{
                return redirect()->route('/');
            }
        }

        return $next($request);
    }
}
