<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\InitT;
use app\api\model\ReceiveT;
use app\api\model\LogT;
use app\api\service\ReceiveService;
use app\api\service\SendService;
use app\api\service\Util;
use app\api\validate\OneNetValidate;
use app\lib\exception\OneNetException;
use app\lib\exception\SuccessMessage;

class Index extends BaseController
{
    /**
     * 处理oneNet推送消息
     * @throws \Exception
     */
    public function index()
    {

        $raw_input = file_get_contents('php://input');
        $resolved_body = Util::resolveBody($raw_input);

        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            //初始化验证
            echo $resolved_body;

        } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (!$resolved_body) {
                LogT::create(['msg' => "数据为空"]);

            } else {
                //接受post数据
                ReceiveService::save($resolved_body);
            }

        }


    }


    /**
     * @api {GET} /api/v1/receive/list 获取数据列表
     * @apiGroup  API
     * @apiVersion 1.0.1
     * @apiDescription 根据设备IMEI号，开始时间和截止时间获取历史数据
     * @apiExample {get}  请求样例:
     * http://oil.mengant.cn/api/v1/receive/list?imei=865820031313187&startTime=2018-09-20&endTime=2018-10-01&page=1&size=2
     * @apiParam (请求参数说明) {String} imei  设备IMEI号
     * @apiParam (请求参数说明) {String} startTime   开始时间
     * @apiParam (请求参数说明) {String} endTime  截止时间
     * @apiParam (请求参数说明) {String} page   当前页数
     * @apiParam (请求参数说明) {String} size   每页条数
     *
     * @apiSuccessExample {json} 返回样例:
     * {"total":784,"per_page":"2","current_page":1,"last_page":392,"data":[{"id":920,"at":"1537991802230","imei":"865820031313187","type":1,"ds_id":"3316_0_5700","value":"4.82","dev_id":"44631936","create_time":"2018-09-27 03:56:42"},{"id":919,"at":"1537991759033","imei":"865820031313187","type":1,"ds_id":"3300_0_5750","value":"0.1A0.2A5A180A","dev_id":"44631936","create_time":"2018-09-27 03:55:59"}]}
     * @apiSuccess (返回参数说明) {int} total 数据总数
     * @apiSuccess (返回参数说明) {int} per_page 每页多少条数据
     * @apiSuccess (返回参数说明) {int} current_page 当前页码
     * @apiSuccess (返回参数说明) {int} last_page 最后页码
     * @apiSuccess (返回参数说明) {obj} data 数据
     *
     * @param $equipmentId
     * @param $startTime
     * @param $endTime
     * @param int $page
     * @param int $size
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\exception\DbException
     */
    public function getList($equipmentId, $startTime, $endTime, $page = 1, $size = 20)
    {

        (new  OneNetValidate())->scene('list')->goCheck();
        $list = ReceiveService::getList($equipmentId, $startTime, $endTime, $page, $size);
        return json($list);

    }


    /**
     * @api {GET} /api/v1/receive/recent 获取指定设备最近一条数据
     * @apiGroup  API
     * @apiVersion 1.0.1
     * @apiDescription 根据设备IMEI号，获取最近一条设备数据
     * @apiExample {get}  请求样例:
     * http://oil.mengant.cn/api/v1/receive/recent?imei=865820031313187
     * @apiParam (请求参数说明) {String} imei  设备IMEI号
     * @apiSuccessExample {json} 返回样例:
     * {"id":920,"at":"1537991802230","imei":"865820031313187","type":1,"ds_id":"3316_0_5700","value":"4.82","dev_id":"44631936","create_time":"2018-09-27 03:56:42"}
     *
     * @param $equipmentId
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRecent($equipmentId)
    {
        (new  OneNetValidate())->scene('recent')->goCheck();
        $data = ReceiveT::getRecent($equipmentId);
        return json($data);

    }


    /**
     * @api {GET} /api/v1/send/pending/test 缓存发送信息-测试接口
     * @apiGroup  API
     * @apiVersion 1.0.1
     * @apiDescription 测试接口：将发送信息缓存，等待设备下一次启动时发送
     * @apiExample {get}  请求样例:
     * http://oil.mengant.cn/api/v1/receive/send?imei=865820031289270&X0=0.1&Y0=0.2
     *
     * @apiParam (请求参数说明) {String} imei  设备IMEI号
     * @apiParam (请求参数说明) {float} X0  X维度的初始值
     * @apiParam (请求参数说明) {float} Y0  Y维度的初始值
     * @param $imei
     * @param int $X0
     * @param int $Y0
     * @return \think\response\Json
     * @throws OneNetException
     */
    public function savePendingTest($imei, $X0 = 0, $Y0 = 0)
    {
        (new SendService())->savePendingRecord($imei, $X0, $Y0);
        return json(new SuccessMessage());
    }

    /**
     * @api {POST} /api/v1/send/pending 缓存发送信息
     * @apiGroup  API
     * @apiVersion 1.0.1
     * @apiDescription 测试接口：将发送信息缓存，等待设备下一次启动时发送
     * @apiExample {post}  请求样例:
     *    {
     *       "imei": 1,
     *       "X0": 1.1,
     *       "Y0": 2.22,
     *     }
     * @apiParam (请求参数说明) {String} imei  设备IMEI号
     * @apiParam (请求参数说明) {float} X0  X维度的初始值
     * @apiParam (请求参数说明) {float} Y0  Y维度的初始值
     * @apiSuccessExample {json} 返回样例:
     *{"msg":"ok","errorCode":0}
     * @apiSuccess (返回参数说明) {int} error_code 错误码： 0表示操作成功无错误
     * @apiSuccess (返回参数说明) {String} msg 信息描述
     *
     * @param $imei
     * @param int $X0
     * @param int $Y0
     * @return \think\response\Json
     * @throws OneNetException
     */
    public function savePending($imei, $X0 = 0, $Y0 = 0)
    {
        (new SendService())->savePendingRecord($imei, $X0, $Y0);
        return json(new SuccessMessage());
    }


}
