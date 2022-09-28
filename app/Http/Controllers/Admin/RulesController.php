<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\AdminCode;
use App\Models\Rule;
use App\Service\RuleService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * 权限
 * Class RulesController
 * @package App\Http\Controllers\Admin
 */
class RulesController extends Controller
{
    public $v = 'admin.rule.';

    /**
     * 权限列表
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $rules = RuleService::getRules();
        return view($this->v . 'index', compact('rules'));
    }

    /**
     * 创建页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $rules = RuleService::getRules();
        return view($this->v . 'create', compact('rules'));
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
            'title' => 'required',
            'pid' => 'required',
            'type' => 'required',
            'level' => 'required'
        ]);
        $error = $validator->errors()->first();
        if ($error){
            return responseError($error);
        }

        $data = Rule::createData($params);
        if ($data['code'] !== AdminCode::SUCCESS_CODE){
            return responseError($data['msg'], $data['code']);
        }
        return responseSuccess();
    }

    /**
     * 编辑页面
     * @param Rule $rule
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Rule $rule)
    {
        $rules = RuleService::getRules();
        $curRule = $rule;
        return view($this->v . 'edit', compact('rules', 'curRule'));
    }

    /**
     * 更新
     * @param Request $request
     * @param Rule $rule
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Rule $rule)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'title' => 'required',
            'pid' => 'required',
            'type' => 'required',
            'level' => 'required'
        ]);
        $error = $validator->errors()->first();
        if ($error){
            return responseError($error);
        }

        $data = Rule::updateData($rule->id, $params);
        if ($data['code'] !== AdminCode::SUCCESS_CODE){
            return responseError($data['msg'], $data['code']);
        }
        return responseSuccess();
    }

    /**
     * 更新记录日志
     * @param Rule $rule
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateIsLog(Rule $rule)
    {
        $rule->islog = 1 - $rule->islog;
        if ($rule->save()){
            return responseSuccess();
        }
        return responseError();
    }

    /**
     * 更新排序
     * @param Rule $rule
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSort(Rule $rule, Request $request)
    {
        $sort = $request->input('sort', Rule::DEFAULT_SORT);
        $rule->sort = $sort;
        if ($rule->save()){
            return responseSuccess();
        }
        return responseError();
    }


    public function destroy(Rule $rule)
    {
        Rule::deleteData($rule->id);
        return responseSuccess();
    }
}
