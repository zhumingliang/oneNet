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
                if ($msg_arr['status'] == 1) {
                    //发送初始化信息
                    $init = InitT::getInit();
                    $params = [
                        'imei' => $init['imei'],
                        'obj_id' => $init['obj_id'],
                        'obj_inst_id' => $init['obj_inst_id'],
                        'res_id' => $init['res_id'],
                        'X' => $init['x'],
                        'Y' => $init['y'],
                        'threshold' => $init['threshold'],
                        'interval' => $init['interval'],

                    ];
                    self::sendToOneNet($params);
                }

            } else {
                ReceiveT::create($msg_arr);
            }
        } catch (Exception $e) {
            LogT::create(['msg' => '接受数据失败，原因：' . $e->getMessage()]);
        }

        /*
       //if (count($msg_arr) == 1) {
       if (isset($msg_arr['login_type'])) {
           LoginT::create($msg_arr);
       } else {
           ReceiveT::create($msg_arr);
       }

               } else {
                   $data_login = [];
                   $data_receive = [];
                   foreach ($msg_arr as $v) {
                       if (isset($v['login_type'])) {
                           array_push($data_login, $v);

                       } else {
                           array_push($data_receive, $v);
                       }
                   }
                   if (count($data_login)) {
                       $login = new LoginT();
                       $login->saveAll($data_login);

                   }
                   if (count($data_receive)) {
                       $login = new LoginT();
                       $login->saveAll($data_receive);

                   }
               }*/
    }


    /**
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