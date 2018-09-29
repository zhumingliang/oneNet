<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/9/29
 * Time: 下午11:49
 */

namespace app\api\model;


use think\Model;

class InitT extends Model
{


    public static function getInit()
    {
        $obj = self::find()->toArray();
        return $obj;


    }

}