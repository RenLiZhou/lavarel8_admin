<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\AdminCode;
use App\Models\Role;
use App\Service\AdminService;
use App\Service\RoleService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * 角色
 * Class RolesController
 * @package App\Http\Controllers\Admin
 */
class RolesController extends Controller
{
    public $v = 'admin.role.';

    /**
     * 列表
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $roles = Role::get();
        return view($this->v . 'index', compact('roles'));
    }

    /**
     * 保存
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'name' => 'required'
        ]);
        $error = $validator->errors()->first();
        if ($error){
            return responseError($error);
        }

        $data = Role::createData($params);
        if ($data['code'] !== AdminCode::SUCCESS_CODE){
            return responseError($data['msg'], $data['code']);
        }
        return responseSuccess();
    }

    /**
     * 更新
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Role $role)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'name' => 'required'
        ]);
        $error = $validator->errors()->first();
        if ($error){
            return responseError($error);
        }

        $data = Role::updateData($role->id, $params);
        if ($data['code'] !== AdminCode::SUCCESS_CODE){
            return responseError($data['msg'], $data['code']);
        }
        return responseSuccess();
    }

    /**
     * 删除
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        $role->admins()->detach();
        $role->rules()->detach();
        $role->delete();
        return responseSuccess();
    }

    /**
     * 设置规则页面
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function setRules(Role $role)
    {
        $rules = RoleService::ztreeRules($role);
        return view($this->v . 'set', ['role' => $role, 'rules' => json_encode($rules)]);
    }

    /**
     * 设置规则
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function settedRules(Request $request, Role $role)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'rules' => 'required'
        ]);
        $error = $validator->errors()->first();
        if ($error){
            return responseError($error);
        }

        $result = $role->rules()->sync($params['rules']);
        if (!$result){
            return responseError();
        }
        //清除缓存
        AdminService::cleanAdminData();
        return responseSuccess();
    }
}
