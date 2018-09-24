<?php
/**
 * Created by PhpStorm.
 * User: zhumingliang
 * Date: 2018/4/24
 * Time: 下午10:59
 */

namespace app\api\validate;


class TimeBetweenMust extends BaseValidate
{
    protected $rule = [
        'time_begin' => 'require|date',
        'time_end' => 'require|date',
    ];

    protected $message = [
        'time_begin' => '查询开始时间必须为有效时间',
        'time_end' => '查询结束时间必须为有效时间'
    ];

}