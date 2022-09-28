<?php

namespace App\Common\Enum;

use App\Common\Enum\CommonCode;

class AdminCode extends CommonCode
{
    const DATA = [
        'notLogin' => [
            'code' => 1001,
            'msg' => '您未登录',
        ],
        'notPermission' => [
            'code' => 1002,
            'msg' => '没有权限',
        ],
        'missingParam' => [
            'code' => 1003,
            'msg' => '参数缺失',
        ],
        'serviceExceptions' => [
            'code' => 1004,
            'msg' => '服务异常',
        ],
        'loginHasExpired' => [
            'code' => 1005,
            'msg' => '登录已失效',
        ]
    ];

    /**
     * 错误码转化
     * @param string $key
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getCode(string $key)
    {
        if(!empty($key) && isset(self::DATA[$key])){
            return self::DATA[$key]['code'];
        }
        return self::ERROR_CODE;

    }

    /**
     * 错误码转化
     * @param string $key
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getMsg(string $key)
    {
        if(!empty($key) && isset(self::DATA[$key])){
            return self::DATA[$key]['msg'];
        }
        return self::ERROR;
    }

}
