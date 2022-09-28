<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;

class Role extends BaseModel
{
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function admins()
    {
        return $this->belongsToMany(Admin::class, AdminRole::class, 'role_id', 'admin_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rules()
    {
        return $this->belongsToMany(Rule::class, RoleRule::class, 'role_id', 'rule_id');
    }

    /**
     * 判断是否存在
     * @param string $field
     * @param string $val
     * @return bool
     */
    public static function isUnique(string $field, string $val)
    {
        return self::query()->where($field, $val)->exists();
    }

    /**
     * 创建
     * @return string
     */
    public static function createData($params)
    {
        $name = $params['name']??'';
        if(self::isUnique('name',$name)){
            return resultError('该角色已存在');
        }

        try {
            $insert_info = [
                'name' => $name,
            ];
            $insert_result = self::query()->create($insert_info);

            if($insert_result){
                return resultSuccess();
            }
        } catch (\Exception $exception) {
            Log::info('创建角色异常:'.$exception->getMessage());
        }
        return resultError('创建角色失败');
    }


    /**
     * 更新
     * @return string
     */
    public static function updateData($id, $params)
    {
        $name = $params['name']??'';

        $data = self::query()->find($id);
        if(empty($data)){
            return resultError('更新角色不存在');
        }

        $if_repetition = self::query()->where('name', $name)->where('id', '<>', $data->id)->exists();
        if($if_repetition){
            return resultError('角色名已存在');
        }

        try {
            $data->name = $name;
            $data->save();
            return resultSuccess();
        } catch (\Exception $exception) {
            Log::info('更新角色异常:'.$exception->getMessage());
        }
        return resultError('更新角色异常');
    }
}
