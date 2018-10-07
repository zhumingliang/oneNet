<?php

namespace app\api\controller\v2;


use app\api\controller\BaseController;
use app\api\service\SendService;
use app\api\validate\OneNetValidate;
use app\lib\exception\OneNetException;
use app\lib\exception\SuccessMessage;

class Index extends BaseController
{
    /**
     * @api {GET} /api/v2/receive/send  向设备发送指令
     * @apiGroup  API
     * @apiVersion 1.0.2
     * @apiDescription 根据设备IMEI号，获取最近一条设备数据
     * @apiExample {get}  请求样例:
     * http://oil.mengant.cn/api/v1/receive/send?ds_id=3300_0_5700&imei=865820031289270&X=0.1&Y=0.2&threshold=5&interval=180
     *
     * @apiParam (请求参数说明) {String} ds_id  设备参数组：obj_id/obj_inst_id/res_id 三个参数用"_"连接
     * @apiParam (请求参数说明) {String} imei  设备IMEI号
     * @apiParam (请求参数说明) {float} X  X倾角
     * @apiParam (请求参数说明) {float} Y  Y倾角
     * @apiParam (请求参数说明) {int} threshold  警告阀值
     * @apiParam (请求参数说明) {int} interval  测量间隔 单位S
     *
     * @apiSuccessExample {json} 返回样例:
     *{"msg":"ok","errorCode":0}
     * @apiSuccess (返回参数说明) {int} error_code 错误码： 0表示操作成功无错误
     * @apiSuccess (返回参数说明) {String} msg 信息描述
     * @throws OneNetException
     * @throws \app\lib\exception\ParameterException
     */
    public function send()
    {
        (new  OneNetValidate())->scene('send')->goCheck();
        $param = $this->request->param();
        $res = SendService::sendToOneNet($param);
        if ($res['errno'] != 0) {
            throw  new OneNetException(['code' => 401,
                'msg' => '发送数据失败，失败原因：' . $res['error'],
                'errorCode' => 10002]);

        }
        return json(new SuccessMessage());
    }


}
