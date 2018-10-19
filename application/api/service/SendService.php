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
    private $obj_id = '';
    private $obj_inst_id = '';
    private $res_id = '';


    public function __construct()
    {
        $this->obj_id = config('onenet.obj_id');
        $this->obj_inst_id = config('onenet.obj_inst_id');
        $this->res_id = config('onenet.res_id');
    }


    public function saveSendToCache($params)
    {


    }

    /**
     *向传感器发送数据
     * @param $params
     * @return mixed
     * @throws ParameterException
     */
    public function sendToOneNet($params)
    {
        try {
            //$params = self::checkParams($params);
            $sendParams = self::preParams($params['imei'], $params['X0'], $params['Y0'], $params['X1'], $params['Y1'],
                $params['T1'], $params['T2']);

            $output = post($sendParams['url'], $sendParams['header'], $sendParams['content']);
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
    private function preParams($imei, $X0, $Y0, $X1, $Y1, $T1, $T2)
    {

        $X0 = sprintf("%.2f", $X0) * 100;
        $Y0 = sprintf("%.2f", $Y0) * 100;
        $X1 = sprintf("%.2f", $X1) * 100;
        $Y1 = sprintf("%.2f", $Y1) * 100;
        $url = config('onenet.send_url');
        $url = sprintf($url, $imei, $this->obj_id, $this->obj_inst_id);
        $header[] = "api-key: MRee0TFqxdtK2bsbyiFLgpmukSY=";
        $header[] = "Content-Type: application/json";
        $header[] = "Host: api.heclouds.com";

        $val = [$X0, $Y0, $X1, $Y1, $T1, $T2];
        $val = implode('A', $val);
        $val .= 'A';
        $content = new \stdClass();
        $param = new \stdClass();
        $param->res_id = $this->res_id;
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