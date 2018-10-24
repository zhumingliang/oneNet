<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/10/21
 * Time: 6:46 PM
 */

namespace app\api\service;


use app\api\model\ReceiveT;

class DeviceService
{

    public function getCurrentValue($imei)
    {
        $x = ReceiveT::getCurrentX($imei);
        $y = ReceiveT::getCurrentY($imei);

        return [
            'x' => $x ? $x['value'] / 100 : 0,
            'y' => $y ? $y['value'] / 100 : 0
        ];

    }

    public function addDevice($params)
    {


    }
}