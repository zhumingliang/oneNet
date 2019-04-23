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
use app\api\model\ReceiveV;

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
        $res = [
            'create_time' => 0,
            'x' => 0,
            'y' => 0
        ];
        $info = ListV::getToday($imei);
        if (!count($info)) {
            return $res;
        }

        foreach ($info as $k => $v) {

            $data = $v['value'];
            if (strstr($data, 'x')) {
                continue;
            }
            $data_arr = explode('|', $data);
            $res = [
                'create_time' => $v['create_time'],
                'x' => is_numeric($data_arr[1]) ? $data_arr[1] / 100 : 0,
                'y' => is_numeric($data_arr[2]) ? $data_arr[2] / 100 : 0
            ];
            break;

        }

        return $res;

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