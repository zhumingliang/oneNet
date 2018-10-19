<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * @param $url
 * @param $header
 * @param $content
 * @return mixed
 */
function post($url, $header, $content)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //TRUE-->将curl_exec()获取的信息以字符串返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    //启用时会将头文件的信息作为数据流输出
    curl_setopt($ch, CURLOPT_POST, true);
    //启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
    if (curl_exec($ch) === false) //curl_error()返回当前会话最后一次错误的字符串
    {
        die("Curlerror: " . curl_error($ch));
    }
    $response = curl_exec($ch);
    //获取返回的文件流
    curl_close($ch);
    return $response;
}
