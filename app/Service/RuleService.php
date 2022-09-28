<?php

namespace App\Service;


use App\Models\Rule;
use Illuminate\Support\Facades\Auth;

class RuleService
{
    /**
     * 获取规则
     * @return array
     */
    public static function getRules()
    {
        $rules = Rule::query()->orderBy('sort', 'asc')->get()->toArray();
        return self::tree($rules);
    }

    /**
     * 树形结构
     * @param $data
     * @param int $pid
     * @param int $lvl
     * @return array
     */
    public static function tree($data, $pid = 0, $lvl = 0)
    {
        $arr = [];
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pid) {
                $lefthtml = '';
                if ($lvl == 1) $lefthtml = '|-';
                if ($lvl == 2) $lefthtml = '|--';
                $v['ltitle'] = $lefthtml . $v['title'];
                $arr[] = $v;
                unset($data[$k]);
                $arr = array_merge($arr, self::tree($data, $v['id'], $lvl + 1));
            }
        }
        return $arr;
    }

    /**
     * 判断权限
     * @param $rulestr
     * @return bool
     */
    public static function judgeAuth($rulestr){
        $adminId = Auth::guard('admin')->id();
        $rules = AdminService::getAdminRules($adminId);
        if (!in_array($rulestr, $rules)){
            return false;
        }
        return true;
    }

}
