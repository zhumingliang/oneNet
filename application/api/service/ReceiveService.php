<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/9/25
 * Time: 下午10:49
 */

namespace app\api\service;


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
            if ($ino == "IDLE") {
                //此时代表设备正处于可接受下发数据状态
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
        $list = ReceiveT::getList($imei, $startTime, $endTime, $page, $size);
        return $list;

    }


}