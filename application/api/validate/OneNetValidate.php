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
        'X0' => 'require|isNotEmpty',
        'Y0' => 'require|isNotEmpty',
        'X1' => 'require|isNotEmpty',
        'Y1' => 'require|isNotEmpty',
        'T1' => 'require|isNotEmpty',
        'T2' => 'require|isNotEmpty',
        'equipmentId' => 'require|isNotEmpty',
        'ds_id' => 'require|isNotEmpty',
        'imei' => 'require|isNotEmpty',
        'startTime' => 'require|isNotEmpty',
        'endTime' => 'require|isNotEmpty',
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger',
    ];

    protected $scene = [
        'send' => ['ds_id', 'imei', 'X0', 'Y0', 'X1', 'Y1', 'T1', 'T2'],
        'list' => ['equipmentId', 'startTime', 'endTime', 'page', 'size'],
        'recent' => ['equipmentId']
    ];

}