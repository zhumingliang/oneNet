<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/9/25
 * Time: 下午10:49
 */

namespace app\api\service;


use app\api\model\DataV;
use app\api\model\ReceiveT;
use app\api\model\LogT;
use think\Exception;

class ReceiveService
{
    /**
     * 存储上传数据
     * @param $msg_arr
     * @throws \Exception
     */
    public static function save($msg_arr)
    {
        try {
            $ino = ReceiveT::create($msg_arr);
            if ($ino->value == "IDLE") {
                //此时代表设备正处于可接受下发数据状态
                LogT::create(['msg' => '检测该设备是否有待处理初始化，IMEI：' . $ino->imei]);
                $imei = $ino->imei;
                (new SendService())->sendToOneNet($imei);

            }
        } catch (Exception $e) {
            LogT::create(['msg' => '接受数据失败，原因：' . $e->getMessage()]);
        }


    }


    /**
     * 获取指定设备信息
     * @param $imei
     * @param $startTime
     * @param $endTime
     * @param $page
     * @param $size
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public static function getList($imei, $startTime, $endTime, $page, $size)
    {
        $list = DataV::getList($imei, $startTime, $endTime, $page, $size);
        $data = $list['data'];
        $value_list = self::getValueData($data);

        foreach ($data as $k => $v) {

            $param = self::getParams($v['id'], $value_list);

            if (!$param['res']) {
                unset($data[$k]);
            } else {
                $data[$k]['param'] = $param['param'];

            }
        }
        $list['data'] = array_values($data);
        return $list;


    }


    /**
     * 获取所有待处理数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private static function getValueData($data)
    {
        $id_arr = array();
        foreach ($data as $k => $v) {
            $id = $v['id'];
            for ($i = 1; $i < 5; $i++) {
                array_push($id_arr, $id + $i);
            }

        }
        $id_arr_in = implode(',', $id_arr);
        $data_list = ReceiveT::whereIn('id', $id_arr_in)->field('id,ds_id,value,imei')
            ->order('id')
            ->select()->toArray();

        return $data_list;

    }


    private static function getParams($id, $list_value)
    {

        $arr = array();
        $begin = 0;
        foreach ($list_value as $k2 => $v2) {
            if ($v2['id'] > $id && $v2['id'] < $id + 5) {
                if ($begin == 0) {
                    if ($v2['ds_id'] != "3300_0_5700") {
                        break;
                    }

                }

                array_push(
                    $arr, ['value_name' => self::getValueNameAttr($v2['ds_id']),
                    'value' => self::prifixValue($v2['ds_id'], $v2['value'])
                ]);
                $begin = 1;
            }


        }

        $res = count($arr) ? true : false;
        return [
            'res' => $res,
            'param' => $arr
        ];

    }

    private static function prefixList($list)
    {
        foreach ($list as $k => $v) {
            $list[$k]['value_name'] = self::getValueNameAttr($v['ds_id']);
        }

        return $list;
    }

    private static function getValueNameAttr($ds_id)
    {
        $status = [
            '3303_0_5700' => 'deviceTemperature',
            '3303_0_5601' => 'CPU温度',
            '3303_0_5701' => 'deviceState',
            '3300_0_5700' => 'angleX',
            '3300_0_5601' => 'angleY',
            '3300_0_5750' => '传递参数',
            '3316_0_5700' => 'power',
            '3316_0_5701' => '模块信号',
        ];
        if (key_exists($ds_id, $status)) {
            return $status[$ds_id];
        }
        return '未知';

    }

    private static function prifixValue($ds_id, $value)
    {
        if ($ds_id == '3300_0_5700' || $ds_id == '3300_0_5601') {
            return $value / 100;
        }

        return $value;
    }

}