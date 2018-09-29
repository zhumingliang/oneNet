<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/9/29
 * Time: 下午6:44
 */

namespace app\lib\exception;


class OneNetException extends BaseException
{
    public $code = 401;
    public $msg = '存储登录信息失败';
    public $errorCode = 10001;

}