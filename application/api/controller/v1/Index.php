<?php

namespace app\api\controller\v1;

use app\api\model\ReceiveT;
use app\api\model\TestT;
use app\api\service\ReceiveService;
use app\api\service\Util;

class Index
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
                TestT::create(['msg' => "数据为空"]);

            } else {
                //接受post数据
                ReceiveService::save($resolved_body);
            }

        }


    }


    /**
     *获取数据列表
     * @param $imei
     * @param $startTime
     * @param $endTime
     * @param int $page
     * @param int $size
     * @return \think\response\Json
     * @throws \think\exception\DbException
     */
    public function getList($imei, $startTime, $endTime, $page = 1, $size = 20)
    {

        $list = ReceiveService::getList($imei, $startTime, $endTime, $page, $size);
        return json($list);

    }


    /**
     * 获取最近一条数据
     * @param $imei
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRecent($imei)
    {
        $data = ReceiveT::getRecent($imei);
        return json($data);

    }


}
