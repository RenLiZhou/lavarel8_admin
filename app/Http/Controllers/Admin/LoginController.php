<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\AdminException;
use App\Service\AdminService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * 登录
 * Class LoginController
 * @package App\Http\Controllers\Admin
 */
class LoginController extends Controller
{
    public $v = 'admin.login.';

    /**
     * 登录页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if (Auth::guard('admin')->check()){
            return redirect()->route('admin.index');
        }
        return view($this->v . 'login');
    }

    /**
     * 登录
     * @param Request $request
     * @param AdminService $adminService
     * @return \Illuminate\Http\JsonResponse
     * @throws AdminException
     */
    public function signIn(Request $request)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, [
            'username' => 'required',
            'password' => 'required',
            'code' => 'required|captcha'
        ],
        [
            'code.captcha' => '验证码错误',
        ])) {
            return responseError($message);
        }

        $remeber = $request->filled('remember') ? true : false;
        AdminService::auth($request->username, $request->password, $remeber);
        return responseSuccess();
    }

    /**
     * 退出
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logOut()
    {
        AdminService::cleanAdminData();
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

}
