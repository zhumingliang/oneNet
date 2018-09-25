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

class ReceiveService
{
    /**
     * 存储上传数据
     * @param $msg_arr
     * @throws \Exception
     */
    public static function save($msg_arr)
    {
        if (count($msg_arr) == 1) {
            if (key_exists('login_type', $msg_arr)) {
                LoginT::create($msg_arr);

            } else {
                ReceiveT::create($msg_arr);
            }

        } else {
            $data_login = [];
            $data_receive = [];
            foreach ($msg_arr as $v) {
                if (key_exists('login_type', $v)) {
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