<?php

namespace App\Service;

use App\Events\AdminLoginEvent;
use App\Exceptions\AdminException;
use App\Models\Admin;
use App\Models\AdminLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class AdminService
{
    const RULE_CACHE_KEY = 'rules_cache_';
    const MENU_CACHE_KEY = 'menu_cache_';

    /**
     * 后台用户登录
     * @param string $username
     * @param string $password
     * @param bool $remember
     * @throws AdminException
     */
    public static function auth(string $username, string $password, bool $remember)
    {
        $admin = Admin::where('username', $username)->first();
        $error = '用户名或密码错误';
        $error2 = '该用户未启用';

        if (null === $admin) throw new AdminException($error);
        if (Admin::NOT_ACTIVE === $admin->status) throw new AdminException($error2);
        $res = password_verify($password, $admin->password);
        if ($res === false) throw new AdminException($error);

        Auth::guard('admin')->login($admin, $remember);
        AdminService::cacheRules($admin->id);
        //记录日志
        self::createAdminLog(AdminLog::TYPE_LOGIN);
    }

    /**
     * 创建管理员日志
     * @param int $type
     */
    public static function createAdminLog(int $type)
    {
        $routeName = Route::currentRouteName();
        $data = [
            'route_name' => Route::currentRouteName(),
            'ip' => request()->getClientIp(),
            'url' => request()->path().( !empty($routeName) ? '|'.$routeName : '' ),
            'method' => request()->getMethod(),
            'param' => json_encode(request()->all())
        ];
        event(new AdminLoginEvent($type, Auth::guard('admin')->id(), $data));

    }

    /**
     * 获取管理员权限
     * @param int $adminId
     * @param bool $isNew
     * @return mixed
     */
    public static function getAdminRules(int $adminId, $isNew = false)
    {
        $key = self::RULE_CACHE_KEY.$adminId;
        if ($isNew || !Cache::has($key)) self::cacheRules($adminId);
        return Cache::get($key);
    }

    /**
     * 缓存管理员权限
     * @param int $adminId
     */
    public static function cacheRules(int $adminId)
    {
        $key = self::RULE_CACHE_KEY.$adminId;
        $rules = self::getRules($adminId);
        Cache::put($key, $rules);
    }

    /**
     * 查询用户需认证的权限
     * @param int $adminId
     * @return array
     */
    public static function getRules(int $adminId)
    {
        return DB::table('admin_roles as ur')->leftJoin('role_rules as rl', 'ur.role_id', '=', 'rl.role_id')
            ->leftJoin('rules as r', 'rl.rule_id', '=', 'r.id')
            ->where('ur.admin_id', $adminId)
            ->where('r.rule', '<>', '')
            ->distinct()
            ->pluck('r.rule')
            ->toArray();
    }

    /**
     * 获取管理员菜单
     * @param int $adminId
     * @param bool $isNew
     * @return mixed
     */
    public static function getAdminMenu(int $adminId, $isNew = false)
    {
        $key = self::MENU_CACHE_KEY.$adminId;
        if ($isNew || !Cache::has($key)){
            self::cacheMenu($adminId);
        }
        return Cache::get($key);
    }

    /**
     * 缓存管理员菜单
     * @param int $adminId
     * @return mixed
     */
    public static function cacheMenu(int $adminId)
    {
        $key = self::MENU_CACHE_KEY.$adminId;
        $menu = self::getMenu($adminId);
        Cache::put($key, $menu);
    }

    /**
     * 查询管理员菜单
     * @param int $adminId
     * @return array
     */
    public static function getMenu(int $adminId)
    {
        // 获取该用户拥有的需要认证的菜单
        $menu = DB::table('admin_roles as ur')
            ->leftJoin('role_rules as rl', 'ur.role_id', '=', 'rl.role_id')
            ->leftJoin('rules as r', 'rl.rule_id', '=', 'r.id')
            ->where('ur.admin_id', $adminId)
            ->where('r.type', 1)
            ->select('r.id', 'r.title', 'r.href', 'r.rule', 'r.pid', 'r.icon', 'r.sort', 'r.level')
            ->get();
        $menu = json_decode(json_encode($menu), true);
        usort($menu, function ($a, $b) {
            return $a['sort'] <=> $b['sort'];
        });
        return $menu;
    }

    /**
     * 清除管理员菜单权限缓存
     */
    public static function cleanAdminData()
    {
        $admin_id = Auth::guard('admin')->id();
        Cache::forget(self::RULE_CACHE_KEY.$admin_id);
        Cache::forget(self::MENU_CACHE_KEY.$admin_id);
    }

}
