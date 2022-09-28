<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\AdminException;
use App\Models\Role;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * 管理员
 * Class AdminsController
 * @package App\Http\Controllers\Admin
 */
class AdminsController extends Controller
{
    public $v = 'admin.admins.';

    /**
     * 管理员列表
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $name = $request->input('name');
        $per_page = $request->input('per_page',10);

        $admins = Admin::query()
            ->with('roles')
            ->when(!empty($name), function ($query) use ($name){
                $query->where('username', 'like', $name . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate($per_page);

        $search_data = [
            'name' => $name,
            'per_page' => $per_page
        ];
        return view($this->v . 'index', compact('search_data', 'admins'));
    }

    /**
     * 创建管理员页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $roles = Role::get();
        return view($this->v . 'create', compact('roles'));
    }

    /**
     * 保存管理员
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AdminException
     */
    public function store(Request $request)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, [
            'role_id' => 'required',
            'username' => 'required|alpha_dash|max:64|unique:admins',
            'password' => 'required|confirmed|alpha_dash|min:6',
            'email' => 'nullable|email',
            'mobile' => 'nullable|regex:/^1[3-9]\d{9}$/'
        ],[
            'role_id.required' => '用户组不能为空'
        ])) {
            return responseError($message);
        }

        $res = Admin::createAdmin($params);
        if (!$res){
            return responseError();
        }
        return responseSuccess();
    }

    /**
     * 删除管理员
     * @param Admin $admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Admin $admin)
    {
        $admin->roles()->detach();
        $admin->delete();
        return responseSuccess();
    }

    /**
     * 编辑管理员页面
     * @param $uid
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $roles = Role::get();
        $admin = Admin::query()->with('roles')->find($id);
        return view($this->v . 'edit', compact('roles', 'admin'));
    }

    /**
     * 更新管理员
     * @param Request $request
     * @param Admin $admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Admin $admin)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, [
            'role_id' => 'required',
            'username' => 'required|alpha_dash|max:64',
            'email' => 'email|nullable',
            'mobile' => 'regex:/^1[3-9]\d{9}$/|nullable'
        ],[
            'role_id.required' => '用户组不能为空'
        ])) {
            return responseError($message);
        }

        $res = Admin::updateAdmin($params, $admin);
        if (!$res){
            return responseError();
        }
        return responseSuccess();
    }

    /**
     * 编辑管理员页面
     * @param Admin $admin
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function editPwd(Admin $admin)
    {
        return view($this->v . 'editPwd', compact('admin'));
    }

    /**
     * 更新管理员
     * @param Request $request
     * @param Admin $admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePwd(Request $request, Admin $admin)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|alpha_dash|min:6',
        ]);
        $error = $validator->errors()->first();
        if ($error){
            return responseError($error);
        }

        $res = Admin::updatePwd($request->password, $admin);
        if (!$res){
            return responseError();
        }
        return responseSuccess();
    }

    /**
     * 更新管理员状态
     * @param Request $request
     * @param Admin $admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Admin $admin)
    {
        $admin->status = 1 - $admin->status;
        if ($admin->save()){
            return responseSuccess();
        }
        return responseError();
    }
}
