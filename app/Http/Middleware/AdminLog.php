<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Models\Rule;
use App\Service\AdminService;

class AdminLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (config('services.admin_log') === true) {
            $routeName = Route::currentRouteName();
            $admin_need_logs = Cache::remember('admin_need_logs', 86400, function() {
                $needlogs = Rule::where('islog', 1)->where('type', 0)->select('rule')->get()->toArray();
                $admin_need_logs = [];
                foreach ($needlogs as $key => $value) {
                    $admin_need_logs[] = $value['rule'];
                }
                return $admin_need_logs;
            });
            if (in_array($routeName, $admin_need_logs)) {
                AdminService::createAdminLog(\App\Models\AdminLog::TYPE_BEHAVIOR);
            }
        }

        return $response;
    }
}
