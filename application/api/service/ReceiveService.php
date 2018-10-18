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
                        'X0' => $init['x'],
                        'Y0' => $init['y'],
                        'X1' => $init['x'],
                        'Y1' => $init['y'],
                        'T1' => $init['t1'],
                        'T2' => $init['t2'],

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


    /**
     *向传感器发送数据
     * @param $params
     * @return mixed
     */
    public static function sendToOneNet($params)
    {
        try {
            $sendParams = self::preParams($params['imei'], $params['obj_id'], $params['obj_inst_id'],
                $params['res_id'], $params['X0'], $params['Y0'], $params['X1'], $params['Y1'], $params['T1'], $params['T2']);

            $output = self::post($sendParams['url'], $sendParams['header'], $sendParams['content']);
            LogT::create(['msg' => $output]);
            $output_array = json_decode($output, true);
            return $output_array;
        } catch (Exception $e) {

            LogT::create(['msg' => $e->getMessage()]);
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


    /**
     * 准备数据
     * @param $imei
     * @param $obj_id
     * @param $obj_inst_id
     * @param $res_id
     * @param $X0
     * @param $Y0
     * @param $X1
     * @param $Y1
     * @param $T1
     * @param $T2
     * @return array
     */
    private static function preParams($imei, $obj_id, $obj_inst_id, $res_id,
                                      $X0, $Y0, $X1, $Y1, $T1, $T2)
    {

        $X0 = sprintf("%.2f", $X0) * 100;
        $Y0 = sprintf("%.2f", $Y0) * 100;
        $X1 = sprintf("%.2f", $X1) * 100;
        $Y1 = sprintf("%.2f", $Y1) * 100;
        $url = config('onenet.send_url');
        $url = sprintf($url, $imei, $obj_id, $obj_inst_id);

        $header[] = "api-key: MRee0TFqxdtK2bsbyiFLgpmukSY=";
        $header[] = "Content-Type: application/json";
        $header[] = "Host: api.heclouds.com";

        $val = [$X0, $Y0, $X1, $Y1, $T1, $T2];
        $val = implode('A', $val);
        $val .= 'A';
        $content = new \stdClass();
        $param = new \stdClass();
        $param->res_id = $res_id;
        $param->val = $val;
        $content->data = [
            0 => $param
        ];

        $content = json_encode($content);
        return [
            'url' => $url,
            'header' => $header,
            'content' => $content,

        ];
    }

}