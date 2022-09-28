<?php

namespace App\Http\Controllers\Admin;

use App\Service\AdminService;
use App\Service\SysService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * 主页
 * Class IndexController
 * @package App\Http\Controllers\Admin
 */
class IndexController extends Controller
{
    public $v = 'admin.index.';

    /**
     * 主页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        $menu = AdminService::getAdminMenu($admin->id);
        return view($this->v . 'index', ['admin' => $admin, 'menu' => json_encode($menu)]);
    }

    /**
     * 刷新缓存
     * @return \Illuminate\Http\JsonResponse
     */
    public function flushCache()
    {
        $admin = Auth::guard('admin')->user();
        AdminService::getAdminMenu($admin->id,true);
        AdminService::getAdminRules($admin->id,true);
        SysService::getSystemInfo(true);
        return responseSuccess();
    }

    /**
     * 清理缓存
     * @return \Illuminate\Http\JsonResponse
     */
    public function cleanCache()
    {
        AdminService::cleanAdminData();
        SysService::cleanSysInfo();
        return responseSuccess();
    }

    /**
     * 403页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function forbidden()
    {
        return view($this->v . 'forbidden');
    }

    /**
     * 主页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function first()
    {
        $sysinfo = SysService::getSystemInfo();
        return view($this->v . 'first', compact('sysinfo'));
    }

}
