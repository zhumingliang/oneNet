<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/10/22
 * Time: 6:34 PM
 */

namespace app\api\service;


use app\api\model\DeviceT;
use app\api\model\LogT;
use app\lib\enum\CommonEnum;
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
     * @return mixed
     * @throws OneNetException
     */
    public function addDevice($params)
    {
        $sm = new OneNetApi("MRee0TFqxdtK2bsbyiFLgpmukSY", "http://api.heclouds.com");
        $param=$this->preParamsForAddDevice($params);
        $device = $sm->device_add($param);
        var_dump($device);


       /* $add_device_url = config('onenet.add_device_url');
        $output = post($add_device_url, $this->header, $this->preParamsForAddDevice($params));
        $output_array = json_decode($output, true);
        print_r($output_array);
        if (isset($output_array['errno']) && $output_array['errno']) {

            LogT::create(['msg' => 'errno:' . $output_array['errno'] . 'error:' . $output_array['error']]);
            throw new OneNetException([
                'code' => 401,
                'msg' => '创建设备到平台失败失败原因：' . $output_array['error'],
                'errorCode' => 10008
            ]);

        }
        $device_id = $output_array['data']['device_id'];
        //保存到数据库
        $params['device_id'] = $device_id;
        $params['admin_id'] = 1;
        $params['state'] = CommonEnum::SUCCESS;

        $device = DeviceT::create($params);
        if (!$device->id) {
            throw new OneNetException([
                'code' => 401,
                'msg' => '保存设备到数据库失败',
                'errorCode' => 10009
            ]);
        }

        return $device_id;*/


    }

    /**
     * 删除设备
     * @param $device_id
     * @throws OneNetException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */

    public function deleteDevice($device_id)
    {
        //检查设备是欧已经删除
        $device = DeviceT::where('device_id', $device_id)->find();
        if (!$device) {
            throw new OneNetException([
                'code' => 401,
                'msg' => '删除设备不存在',
                'errorCode' => 10010
            ]);
        }
        if ($device['state'] == CommonEnum::FAIL) {
            throw new OneNetException([
                'code' => 401,
                'msg' => '该设备已删除',
                'errorCode' => 10011
            ]);
        }
        $delete_device_url = config('onenet.delete_device_url');
        $delete_device_url = sprintf($delete_device_url, $device_id);
        $output = delete($delete_device_url, $this->header, '');
        $output_array = json_decode($output, true);
        if (isset($output_array['errno']) && !$output_array['errno']) {
            LogT::create(['msg' => 'errno:' . $output_array['errno'] . 'error:' . $output_array['error']]);
            throw new OneNetException([
                'code' => 401,
                'msg' => '设备删除失败失败原因：' . $output_array['error'],
                'errorCode' => 10012
            ]);

        }

        $res = DeviceT::update(['state' => CommonEnum::FAIL], ['device_id' => $device_id])->find();
        if ($res) {
            throw new OneNetException([
                'code' => 401,
                'msg' => '设备删除状态修改失败',
                'errorCode' => 10013
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
        $data['auth_info'] = json_encode([$params['imei'] => $params['imsi']]);
        $data=json_encode($data);
        return $data;


    }

}