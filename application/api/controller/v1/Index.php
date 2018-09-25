<?php

namespace app\api\controller\v1;

use app\api\model\TestT;
use app\api\service\ReceiveService;
use app\api\service\Util;

class Index
{
    /**
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
            if (!$resolved_body){
                TestT::create(['msg'=>"数据为空"]);

            }else{
                //接受post数据
                ReceiveService::save($resolved_body);
            }

        }


    }


}
