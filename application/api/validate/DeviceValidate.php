<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/10/21
 * Time: 7:03 PM
 */

namespace app\api\validate;


class DeviceValidate extends BaseValidate
{

    protected $rule = [
        'X0' => 'require|isNotEmpty',
        'Y0' => 'require|isNotEmpty',
        'X1' => 'require|isNotEmpty',
        'Y1' => 'require|isNotEmpty',
        'imei' => 'require',
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger',
        'id' => 'isPositiveInteger',

    ];

    protected $scene = [
        'init' => ['X0', 'Y0', 'X1', 'Y1', 'imei'],
        'imei' => ['imei'],
        'id'=>['id']
    ];
}