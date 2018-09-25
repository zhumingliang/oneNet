<?php

namespace app\api\controller\v1;

use app\api\model\TestT;
use app\api\service\Util;

class Index
{
    public function index()
    {

        $raw_input = file_get_contents('php://input');
        /**
         * 第二步直接解析body，如果是第一次验证签名则raw_input为空，由resolveBody方法自动判断，依赖$_GET
         * Step2, directly to resolve the body, if it is the first time to verify the signature, the raw_input is empty, by the resolveBody method to automatically determine, it's relied on $ _GET
         */
        $resolved_body = Util::resolveBody($raw_input);
        var_dump($resolved_body);
        $data['msg'] = $resolved_body;
        TestT::create($data);

        echo $resolved_body;

    }


}
