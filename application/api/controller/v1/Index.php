<?php

namespace app\api\controller\v1;

use app\api\model\TestT;
use app\api\service\Util;

class Index
{
    public function index()
    {
        /**
         * *************************************
         * 一个简单的示例开始
         * A simple example begins
         * *************************************
         */
        /**
         * 第一步需要获取HTTP body的数据
         * Step1, get the HTTP body's data
         */
        $raw_input = file_get_contents('php://input');
        /**
         * 第二步直接解析body，如果是第一次验证签名则raw_input为空，由resolveBody方法自动判断，依赖$_GET
         * Step2, directly to resolve the body, if it is the first time to verify the signature, the raw_input is empty, by the resolveBody method to automatically determine, it's relied on $ _GET
         */
        $resolved_body = Util::resolveBody($raw_input);
        /**
         * 最后得到的$resolved_body就是推送过后的数据
         * At last, var $resolved_body is the data that is pushed
         */


        $data['msg'] = $resolved_body;
        TestT::create($data);

        echo $resolved_body;
        /**
         * *************************************
         * 一个简单的示例结束
         * A simple example ends
         ***************************************/

    }


}
