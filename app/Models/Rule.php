<?php

namespace App\Models;

use App\Exceptions\AdminException;
use App\Service\AdminService;
use Illuminate\Support\Facades\Log;

class Rule extends BaseModel
{
    protected $guarded = ['id'];

    const UNSAVE_LOG = 0; //不记录
    const SAVE_LOG = 1; //记录

    const TYPE_AUTH = 0; //权限
    const TYPE_MENU_AUTH = 1;

    const DEFAULT_SORT = 100;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, RoleRule::class, 'rule_id', 'role_id');
    }

    /**
     * 创建
     * @param $params
     * @return \Arrar|array
     */
    public static function createData($params)
    {
        try {
            $insert_info = [
                'title' => $params['title'],
                'href' => $params['href']??'',
                'rule' => $params['rule']??'',
                'pid' => $params['pid'],
                'type' => $params['type']??self::TYPE_AUTH,
                'level' => $params['level']??0,
                'icon' => $params['icon']??'',
                'sort' => $params['sort']??self::DEFAULT_SORT,
                'islog' => $params['islog']??self::UNSAVE_LOG,
            ];
            $insert_result = self::query()->create($insert_info);

            if($insert_result){
                return resultSuccess();
            }
        } catch (\Exception $exception) {
            Log::info('创建权限异常:'.$exception->getMessage());
        }
        return resultError('创建权限失败');
    }

    /**
     * 更新
     * @param $id
     * @param $params
     * @return \Arrar|array
     */
    public static function updateData($id, $params)
    {
        try {
            $update_info = [
                'title' => $params['title'],
                'href' => $params['href']??'',
                'rule' => $params['rule']??'',
                'pid' => $params['pid']??0,
                'type' => $params['type']??self::TYPE_AUTH,
                'level' => $params['level']??0,
                'icon' => $params['icon']??'',
                'sort' => $params['sort']??self::DEFAULT_SORT,
                'islog' => $params['islog']??self::UNSAVE_LOG,
            ];
            $update_result = self::query()
                ->where('id', $id)
                ->update($update_info);

            if($update_result){
                AdminService::cleanAdminData();
                return resultSuccess();
            }
        } catch (\Exception $exception) {
            Log::info('更新权限异常:'.$exception->getMessage());
        }
        return resultError('更新权限失败');
    }

    /**
     * 删除
     * @return string
     */
    public static function deleteData($id)
    {
        $rule = self::find($id);

        $son_count = self::where('pid', $rule->id)->count();
        if ($son_count > 0){
            throw new AdminException('该权限下面有子级，不能删除');
        }

        $rule->roles()->detach();
        $res = $rule->delete();
        if (!$res){
            throw new AdminException('删除失败');
        }
        AdminService::cleanAdminData();
    }
}
