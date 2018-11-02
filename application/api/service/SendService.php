<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/10/7
 * Time: 11:56 AM
 */

namespace app\api\service;


use app\api\model\InitT;
use app\api\model\LogT;
use app\api\model\PendingSendT;
use app\api\model\SendResT;
use app\lib\enum\CommonEnum;
use app\lib\exception\OneNetException;

class SendService
{
    private $obj_id = '';
    private $obj_inst_id = '';
    private $res_id = '';
    private $X0 = 0;
    private $Y0 = 0;
    private $X1 = 0;
    private $Y1 = 0;
    private $T1 = 0;
    private $T2 = 0;


    public function __construct()
    {
        $this->obj_id = config('onenet.obj_id');
        $this->obj_inst_id = config('onenet.obj_inst_id');
        $this->res_id = config('onenet.res_id');
        $this->timeout = config('onenet.timeout');
        $init = $this->getInit();
        $this->X0 = $init['X0'];
        $this->Y0 = $init['Y0'];
        $this->X1 = $init['X1'];
        $this->Y1 = $init['Y1'];
        $this->T1 = $init['T1'];
        $this->T2 = $init['T2'];
    }

    /**
     * 向传感器发送数据
     * @param $imei
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function sendToOneNet($imei)
    {
        try {
            /**
             * 检测imei是否有待处理请求
             */
            $pending_id = $this->setSendParams($imei);
            if (!$pending_id) {
                return false;
            }

            LogT::create(['msg' => 'sendId:' . $pending_id]);

            /**
             * 发送待处理请求
             */
            $sendParams = self::preParams($imei, $this->X0, $this->Y0, $this->X1, $this->Y1,
                $this->T1, $this->T2);

            LogT::create(['msg' => $sendParams['content']]);

            $output = post($sendParams['url'], $sendParams['header'], $sendParams['content']);
            $output_array = json_decode($output, true);
            //保存发送结果
            $this->saveSendRes($pending_id, $output, $sendParams['content']);
            //判断发送结果-成功则修改记录状态
            if (isset($output_array['errno']) && !$output_array['errno']) {
                PendingSendT::update(['state' => CommonEnum::SUCCESS], ['id' => $pending_id]);
                // self::test($pending_id);

            } else {
                LogT::create(['msg' => 'errno:' . $output_array['errno'] . 'error:' . $output_array['error']]);
            }
            return true;
        } catch (Exception $e) {
            LogT::create(['msg' => $e->getMessage()]);
        }
    }

    /**
     * 检测指定imei是否有待处理发送请求
     * 有，则配置发送数据
     * @param $imei
     * @return bool|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function setSendParams($imei)
    {
        $order = PendingSendT::where('imei', $imei)
            ->order('create_time desc')
            ->find();
        if ($order && $order['state'] == CommonEnum::FAIL) {
            $this->X0 = $order['X0'];
            $this->Y0 = $order['Y0'];
            $this->X1 = $order['X1'];
            $this->Y1 = $order['Y1'];
            $this->T1 = $order['T1'];
            $this->T2 = $order['T2'];
            return $order['id'];
        }
        return false;


    }

    /**
     * 保存待发送请求
     * @param $imei
     * @param $X0
     * @param $Y0
     * @param $X1
     * @param $Y1
     * @return mixed
     * @throws OneNetException
     */
    public function savePendingRecord($imei, $X0, $Y0, $X1, $Y1)
    {
        if ($X0) {
            $this->X0 = $X0;
        }
        if ($Y0) {
            $this->Y0 = $Y0;
        }
        if ($X1) {
            $this->X1 = $X1;
        }
        if ($Y1) {
            $this->Y1 = $Y1;
        }

        $data = [
            'imei' => $imei,
            'X0' => $X0,
            'Y0' => $Y0,
            'X1' => $this->X1,
            'Y1' => $this->Y1,
            'T1' => $this->T1,
            'T2' => $this->T2,
            'state' => CommonEnum::FAIL,
        ];
        $send = PendingSendT::create($data);
        if (!$send->id) {
            throw  new OneNetException(
                [
                    'code' => 401,
                    'msg' => '保存发送指令失败',
                    'errorCode' => 10002
                ]
            );
        }

        return $send->id;
    }

    /**
     * 获取初始值：X1/Y1/T1/T2
     * @return array|null|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getInit()
    {
        return InitT::find();
    }

    /**
     * 准备数据
     * @param $imei
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
        $time_out = config('onenet.time_out');
        $url = sprintf($url, $imei, $this->obj_id, $this->obj_inst_id, $time_out);
        $header[] = "api-key: MRee0TFqxdtK2bsbyiFLgpmukSY=";
        $header[] = "Content-Type: application/json";
        $header[] = "Host: api.heclouds.com";

        $val = [$X0, $Y0, $X1, $Y1, $T1, $T2];
        $val = implode('A', $val);
        $val .= 'AA';
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

    /**
     * 保存发送结果
     * @param $send_id
     * @param $res
     * @param $sendParams
     * @return SendResT
     */
    private function saveSendRes($send_id, $res, $sendParams)
    {
        $data = [
            'send_id' => $send_id,
            'res' => $res,
            'params' => $sendParams

        ];
        $res = SendResT::create($data);
        return $res;
    }

    private function test($pending_id)
    {
        $pending = PendingSendT::where('id', $pending_id)->find();
        if ($pending->T1 > 3) {
            $pending->T1 = ['dec', 1];
        } else {
            $pending->T1 = 10;
        }
        $pending->save();
    }


}