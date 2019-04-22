<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Env;

// +----------------------------------------------------------------------
// | Workerman设置 仅对 php think worker:server 指令有效
// +----------------------------------------------------------------------
return [
    // 扩展自身需要的配置
    'protocol' => 'http', // 协议 支持 tcp udp unix http websocket text
    'host' => '0.0.0.0', // 监听地址
    'port' => 2345, // 监听端口
    'socket' => '', // 完整监听地址
    'context' => [], // socket 上下文选项
    'worker_class' => '', // 自定义Workerman服务类名 支持数组定义多个服务
    // 支持workerman的所有配置参数
    'name' => 'thinkphp',
    'count' => 4,
    'daemonize' => false,
    'pidFile' => Env::get('runtime_path') . 'worker.pid',

    // 支持事件回调
    // onWorkerStart
    'onWorkerStart' => function ($worker) {

    },
    // onWorkerReload
    'onWorkerReload' => function ($worker) {

    },
    // onConnect
    'onConnect' => function ($connection) {

    },
    // onMessage
    'onMessage' => function ($connection, $data) {
        $msg = $data['post']['msg'];
        /* $raw_input = file_get_contents('php://input');
         \app\api\model\LogT::create(['msg' => "数据为空---" . json_encode($raw_input)]);*/
        // $resolved_body = \app\api\service\Util::resolveBody($raw_input);
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            //初始化验证
            $connection->send(\think\facade\Request::param('msg'));

        } else if ($_SERVER['REQUEST_METHOD'] == "POST") {
            \app\api\service\ReceiveService::save($msg);
            $connection->send('success');

        }
    },
    // onClose
    'onClose' => function ($connection) {

    },
    // onError
    'onError' => function ($connection, $code, $msg) {
        echo "error [ $code ] $msg\n";
    },
];
