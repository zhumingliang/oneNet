<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/9/25
 * Time: 下午10:49
 */

namespace app\api\service;


use app\api\model\InitT;
use app\api\model\LoginT;
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
            if (isset($msg_arr['login_type'])) {
                if (!LoginT::create($msg_arr)) {
                    LogT::create(['msg' => '存储登录信息失败']);
                }
                /*   if ($msg_arr['status'] == 1) {
                       //发送初始化信息
                       $init = InitT::getInit();
                       $params = [
                           'imei' => $init['imei'],
                           'obj_id' => $init['obj_id'],
                           'obj_inst_id' => $init['obj_inst_id'],
                           'res_id' => $init['res_id'],
                           'X0' => $init['x'],
                           'Y0' => $init['y'],
                           'X1' => $init['x'],
                           'Y1' => $init['y'],
                           'T1' => $init['t1'],
                           'T2' => $init['t2'],

                       ];
                       self::sendToOneNet($params);
                   }*/

            } else {
                $ino = ReceiveT::create($msg_arr);
                if ($ino == "IDLE") {
                    //此时代表设备正处于可接受下发数据状态

                }
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