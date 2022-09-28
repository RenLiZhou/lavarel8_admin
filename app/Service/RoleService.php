<?php

namespace App\Service;

use App\Models\Role;
use App\Models\Rule;

class RoleService
{
    /**
     * 树形结构
     * @return array
     */
    public static function ztreeRules($role)
    {
        $curRules = $role->rules()->pluck('rules.id')->toArray();
        $allRules = Rule::select('id', 'title', 'pid')->orderBy('sort', 'asc')->get()->toArray();

        $rules = self::buildZtreeData($allRules, $curRules);
        $rules[] = [
            "id"=>0,
            "pid"=>0,
            "title"=>"全部",
            "open"=>true
        ];
        return $rules;
    }

    /**
     * 树形结构
     * @param array $data
     * @param array $checked
     * @param int $pid
     * @return array
     */
    public static function buildZtreeData(array $data, array $checked, $pid = 0)
    {
        $arr = [];
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pid) {
                if (in_array($v['id'], $checked)) {
                    $v['checked'] = true;
                }
                $v['open'] = true;
                $arr[] = $v;
                unset($data[$k]);
                $arr = array_merge($arr, self::buildZtreeData($data, $checked, $v['id']));
            }
        }
        return $arr;
    }

}
