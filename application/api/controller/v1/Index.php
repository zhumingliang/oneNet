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
     * @apiSuccessExample {json} 返回样例:
     * {"total":52,"per_page":"2","current_page":1,"last_page":26,"data":[{"id":3126,"imei":"865820035119960","create_time":"2018-10-19 22:08:28","param":[{"value_name":"X值","value":"-68"},{"value_name":"Y值","value":"-9"},{"value_name":"传感器温度","value":"3459"},{"value_name":"传递参数","value":"0A0A0A0A2A3A"}]},{"id":3117,"imei":"865820035119960","create_time":"2018-10-19 22:01:45","param":[{"value_name":"X值","value":"-68"},{"value_name":"Y值","value":"-9"},{"value_name":"传感器温度","value":"3459"},{"value_name":"传递参数","value":"0A0A0A0A2A3A"}]}]}
     * @apiSuccess (返回参数说明) {int} per_page 每页多少条数据
     * @apiSuccess (返回参数说明) {int} current_page 当前页码
     * @apiSuccess (返回参数说明) {int} last_page 最后页码
     * @apiSuccess (返回参数说明) {obj} data 数据
     * @apiSuccess (返回参数说明) {int} id 数据id
     * @apiSuccess (返回参数说明) {String} imei 设备IMEI
     * @apiSuccess (返回参数说明) {String} create_time 接受数据时间
     * @apiSuccess (返回参数说明) {OBJ} param 接受参数
     * @apiSuccess (返回参数说明) {String} value_name 参数描述
     * @apiSuccess (返回参数说明) {String} value 参数值
     *
     * @param $imei
     * @param $startTime
     * @param $endTime
     * @param int $page
     * @param int $size
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\exception\DbException
     */
    public function getList($imei, $startTime, $endTime, $page = 1, $size = 20)
    {

        (new  OneNetValidate())->scene('list')->goCheck();
        $list = ReceiveService::getList($imei, $startTime, $endTime, $page, $size);
        return json($list);

    }


    /**
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
     * @param $imei
     * @param int $X0
     * @param int $Y0
     * @return \think\response\Json
     * @throws OneNetException
     */
    public function savePendingTest($imei, $X0 = 0, $Y0 = 0)
    {
        (new SendService())->savePendingRecord($imei, $X0, $Y0, 0, 0);
        return json(new SuccessMessage());
    }

    /**
     * @param $imei
     * @param int $X0
     * @param int $Y0
     * @return \think\response\Json
     * @throws OneNetException
     */
    public function savePending($imei, $X0 = 0, $Y0 = 0)
    {
        (new SendService())->savePendingRecord($imei, $X0, $Y0, 0, 0);
        return json(new SuccessMessage());
    }


}
