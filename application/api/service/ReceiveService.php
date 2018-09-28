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
        //if (count($msg_arr) == 1) {
        if (isset($msg_arr['login_type'])) {
            LoginT::create($msg_arr);
        } else {
            ReceiveT::create($msg_arr);
        }
        /*
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


    public static function sendToOneNet()
    {
        try {
            //$url = 'http://api.heclouds.com/devices/44631936/datapoints?type=1';
            $url = "http://api.heclouds.com/nbiot?imei=865820031313187&obj_id=3300&obj_inst_id=0&mode=2";
            //*****处填写自己的设备ID号
            $header[] = "api-key: MRee0TFqxdtK2bsbyiFLgpmukSY=";
            $header[] = "Content-Type: application/json";
            $header[] = "Host: api.heclouds.com";
            //填写自己的api-key号
            $data = [
                'res_id' => 5750,
                'val' => "0.3A0.2A5A190A"
            ];
            $content = json_encode($data);

            $output = self::post($url, $header, $content);
            TestT::create(['msg' => $output]);
            $output_array = json_decode($output, true);
            print_r($output_array);
        } catch (Exception $e) {

            TestT::create(['msg' => $e->getMessage()]);
        }
    }

    public static function post($url, $header, $content)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //TRUE-->将curl_exec()获取的信息以字符串返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        //启用时会将头文件的信息作为数据流输出
        curl_setopt($ch, CURLOPT_POST, true);
        //启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        if (curl_exec($ch) === false) //curl_error()返回当前会话最后一次错误的字符串
        {
            die("Curlerror: " . curl_error($ch));
        }
        $response = curl_exec($ch);
        //获取返回的文件流
        curl_close($ch);
        return $response;
    }

}