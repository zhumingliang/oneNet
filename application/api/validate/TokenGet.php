<?php
/**
 * Created by PhpStorm.
 * User: zhumingliang
 * Date: 2018/3/20
 * Time: 下午2:00
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'phone' => 'require|isMobile',
        'pwd' => 'require|isNotEmpty'
    ];

    protected $message = [
        'phone' => '获取Token，需要手机号',
        'passwd' => '获取Token，需要密码'
    ];

}