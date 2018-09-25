<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/9/25
 * Time: 下午10:49
 */

namespace app\api\service;


use app\api\model\LoginT;
use app\api\model\ReceiveT;
use app\api\model\TestT;

class ReceiveService
{
    /**
     * 存储上传数据
     * @param $msg_arr
     * @throws \Exception
     */
    public static function save($msg_arr){
        TestT::create(['msg'=> json_encode($msg_arr)]);
        TestT::create(['msg'=> count($msg_arr)]);

        if (count($msg_arr) == 1) {
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
        }
    }

}