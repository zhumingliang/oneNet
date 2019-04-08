<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/10/21
 * Time: 6:46 PM
 */

namespace app\api\service;


use app\api\model\ListV;
use app\api\model\PendingSendT;
use app\api\model\ReceiveT;

class DeviceService
{
    /**
     * 获取设备实时的值
     * @param $imei
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCurrentValue($imei)
    {
        $info = ListV::where('imei', $imei)->order('create_time desc')->find();
        if (!$info) {
            return [
                'create_time' =>0,
                'x' =>0,
                'y' => 0
            ];
        }
        $data = $info['value'];
        $data_arr = explode('|', $data);
        return [
            'create_time' => $info['create_time'],
            'x' => $data_arr[1] / 100,
            'y' => $data_arr[2] / 100
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