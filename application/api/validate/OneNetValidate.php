<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/9/29
 * Time: 下午10:56
 */

namespace app\api\validate;


class OneNetValidate extends BaseValidate
{

    protected $rule = [
        'X' => 'require|isNotEmpty',
        'Y' => 'require|isNotEmpty',
        'threshold' => 'require|isNotEmpty',
        'interval' => 'require|isNotEmpty',
        'equipmentId' => 'require|isNotEmpty',
        'ds_id' => 'require|isNotEmpty',
        'imei' => 'require|isNotEmpty',
        'startTime' => 'require|isNotEmpty',
        'endTime' => 'require|isNotEmpty',
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger',
    ];

    protected $scene = [
        'send' => ['ds_id', 'imei', 'X', 'Y', 'threshold', 'interval'],
        'list' => ['equipmentId', 'startTime', 'endTime', 'page', 'size'],
        'recent' => ['equipmentId']
    ];

}