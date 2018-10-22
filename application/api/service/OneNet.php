<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/10/22
 * Time: 6:34 PM
 */

namespace app\api\service;


use app\lib\exception\OneNetException;
use think\Exception;

class OneNet
{
    private $header = array();

    public function __construct()
    {
        $header[] = "api-key: MRee0TFqxdtK2bsbyiFLgpmukSY=";
        $header[] = "Content-Type: application/json";
        $header[] = "Host: api.heclouds.com";
        $this->header = $header;
    }

    /**
     * 添加设备
     * @param $params
     * @throws OneNetException
     */
    public function addDevice($params)
    {

        try {
            $add_device_url = config('onenet.add_device_url');
            $output = post($add_device_url, $this->header, $this->preParamsForAddDevice($params));
            $output_array = json_decode($output, true);


        } catch (Exception $e) {
            throw new OneNetException([

            ]);
        }

    }


    /**
     * 获取添加设备的数据
     * @param $params
     * @return false|string
     */
    private function preParamsForAddDevice($params)
    {
        $data['title'] = $params['title'];
        $data['protocol'] = "LWM2M";
        $data['auth_info'] = [$params['imei'] => $params['imsi']];
        return json_encode($data);

    }

}