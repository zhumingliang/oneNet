<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/10/21
 * Time: 6:46 PM
 */

namespace app\api\service;


use app\api\model\PendingSendT;
use app\api\model\ReceiveT;

class DeviceService
{
    /**
     * 获取设备实时的值
     * @param $imei
     * @return array
     */
    public function getCurrentValue($imei)
    {
        $x = ReceiveT::getCurrentX($imei);
        $y = ReceiveT::getCurrentY($imei);

        return [
            'create_time'=>$x['create_time'],
            'x' => $x ? $x['value'] / 100 : 0,
            'y' => $y ? $y['value'] / 100 : 0
        ];

    }

    /**
     * 获取设备初始化设置的值
     * @param $imei
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getInitInfo($imei)
    {
        $info = PendingSendT::where('imei', '=', $imei)
            ->order('create_time desc')
            ->find();

        return $info;

    }

    public function addDevice($params)
    {


    }
}