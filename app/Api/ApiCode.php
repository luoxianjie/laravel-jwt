<?php

namespace App\Api;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/31
 * Time: 17:20
 */

class ApiCode
{
    /**
     * 系统状态吗
     */
    const STATE_OK        = 200;
    const PARAM_ERROR     = 400;
    const AUTH_FAIL       = 401;
    const NOT_FOUND       = 404;

    /**
     *系统提示信息
     */
    public $state = [
        self::STATE_OK      => '请求成功!',
        self::PARAM_ERROR   => '参数有误!',
        self::AUTH_FAIL     => '认证失败!',
        self::NOT_FOUND     => '路由不存在'
    ];

}