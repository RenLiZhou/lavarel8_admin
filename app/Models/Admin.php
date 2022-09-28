<?php

namespace App\Models;

use App\Exceptions\AdminException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authuser;
use Illuminate\Support\Facades\Log;

class Admin extends Authuser
{
    protected $guarded = ['id'];
    protected $hidden = ['password', 'remember_token'];

    const ACTIVE       = 1;
    const NOT_ACTIVE   = 0;

    const PWD_COST = 12;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('exist', function(Builder $builder) {
            $builder->where('status', '>=', 0);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, AdminRole::class, 'admin_id', 'role_id');
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
     * 更新密码
     * @param string $password
     * @param Admin $admin
     * @return bool
     */
    public static function updatePwd(string $password, Admin $admin) : bool
    {
        $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => self::PWD_COST]);
        if ($password === $admin->password) return true;
        $admin->password = $password;
        return $admin->save();
    }

    /**
     * 创建管理员
     * @param array $data
     * @return bool
     * @throws AdminException
     */
    public static function createAdmin(array $params) : bool
    {
        $data = [
            'username' => $params['username'],
            'mobile' => $params['mobile']??'',
            'email' => $params['email']??'',
            'status' => self::ACTIVE,
            'password' => password_hash($params['password'], PASSWORD_DEFAULT, ['cost' => self::PWD_COST]),
            'created_at' => Carbon::now()->toDateTimeString()
        ];
        if(self::isUnique('username', $data['username'])){
            throw new AdminException('该账号名已被使用');
        }
        if(!empty($data['mobile']) && self::isUnique('mobile', $data['mobile'])){
            throw new AdminException('该手机号已被使用');
        }
        if(!empty($data['email']) && self::isUnique('email', $data['email'])){
            throw new AdminException('该邮箱已被使用');
        }

        $admin = self::query()->create($data);
        if ($admin) {
            $admin->roles()->sync($params['role_id']);
            return true;
        }
        return false;
    }

    /**
     * 更新管理员
     * @param array $data
     * @param Admin $admin
     * @return bool
     * @throws AdminException
     */
    public static function updateAdmin(array $params, Admin $admin) : bool
    {
        if ($admin->username != $params['username'] && self::isUnique('username', $params['username'])) {
            throw new AdminException('该账号名已被使用');
        }
        if (!empty($params['email']) && $params['email'] != $admin->email && self::isUnique('email', $params['email'])) {
            throw new AdminException('该邮箱已被占用');
        }
        if (!empty($params['mobile']) && $params['mobile'] != $admin->mobile && self::isUnique('mobile', $params['mobile'])) {
            throw new AdminException('该手机号被占用');
        }

        $data = [
            'username' => $params['username'],
            'mobile' => $params['mobile']??'',
            'email' => $params['email']??'',
        ];
        try {
            $res = $admin->update($data);
            if ($res) {
                $admin->roles()->sync($params['role_id']);
                return true;
            }
        }catch (\Exception $exception){
            Log::info("更新管理员异常：".$exception->getMessage());
        }
        return false;
    }


}
