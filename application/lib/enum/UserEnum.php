<?php
/**
 * Created by PhpStorm.
 * User: zhumingliang
 * Date: 2018/4/16
 * Time: 上午9:56
 */

namespace app\lib\enum;


class UserEnum
{
    //用户所属球馆为空
    const USER_ARENA_IS_NULL = 1;

    //用户所属球馆不为空
    const USER_ARENA_IS_OK = 2;

    //管理员
    const USER_GRADE_ADMIN=1;

    //录入员
    const USER_GRADE_IN=2;

    //录出员
    const USER_GRADE_OUT=3;

    //账号正常
    const USER_STATE_OK=1;

    //账号停用
    const USER_STATE_STOP=2;


}