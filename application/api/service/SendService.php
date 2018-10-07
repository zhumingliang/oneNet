<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/10/7
 * Time: 11:56 AM
 */

namespace app\api\service;


use app\api\model\LogT;
use app\lib\exception\ParameterException;

class SendService
{

    /**
     *向传感器发送数据
     * @param $params
     * @return mixed
     * @throws ParameterException
     */
    public static function sendToOneNet($params)
    {
        try {
            $params = self::checkParams($params);
            $sendParams = self::preParams($params['imei'], $params['obj_id'], $params['obj_inst_id'],
                $params['res_id'], $params['X'], $params['Y'], $params['threshold'], $params['interval']);

            $output = self::post($sendParams['url'], $sendParams['header'], $sendParams['content']);
            LogT::create(['msg' => $output]);
            $output_array = json_decode($output, true);
            return $output_array;
        } catch (Exception $e) {

            LogT::create(['msg' => $e->getMessage()]);
        }
    }

    /**
     * 检测ds_id 是否合法
     * @param $params
     * @return array
     * @throws ParameterException
     */
    private static function checkParams($params)
    {
        $ds_id = $params['ds_id'];
        $ds_arr = explode('_', $ds_id);
        if (!count($ds_arr)) {
            throw  new ParameterException();
        }

        $params['obj_id'] = $ds_arr[0];
        $params['obj_inst_id'] = $ds_arr[1];
        $params['res_id'] = $ds_arr[2];
        return $params;

    }

    private static function post($url, $header, $content)
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
     * @param string $imei
     * @param int $obj_id
     * @param int $obj_inst_id
     * @param int $res_id
     * @param $X
     * @param $Y
     * @param $threshold
     * @param $interval
     * @return array
     */
    private static function preParams($imei, $obj_id, $obj_inst_id, $res_id,
                                      $X, $Y, $threshold, $interval)
    {
        $url = config('onenet.send_url');
        $url = sprintf($url, $imei, $obj_id, $obj_inst_id);

        $header[] = "api-key: MRee0TFqxdtK2bsbyiFLgpmukSY=";
        $header[] = "Content-Type: application/json";
        $header[] = "Host: api.heclouds.com";

        $val = [$X, $Y, $threshold, $interval];
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