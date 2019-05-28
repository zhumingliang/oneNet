<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-04-02
 * Time: 11:22
 */

namespace app\api\model;


use think\Model;

class ListV extends Model
{
    public static function getList($imei, $startTime, $endTime, $page, $size)
    {
        //$time_begin = date("Y-m-d", strtotime($startTime));
       // $time_end = addDay(1, $endTime);

        $pagingData = self::where('imei', '=', $imei)
            ->where('state', 1)
            ->whereBetweenTime('create_time', $time_begin, $time_end)
            ->whereNotLike('value','%x%')
            ->field('id,imei,value,create_time')
            ->order('create_time desc')
            ->paginate($size, false, ['page' => $page])->toArray();
        return $pagingData;

    }


    public static function getListForExport($imei, $startTime, $endTime)
    {
        $time_begin = date("Y-m-d", strtotime($startTime));
        $time_end = addDay(1, $endTime);

        $pagingData = self::where('imei', '=', $imei)
            ->whereBetweenTime('create_time', $time_begin, $time_end)
            ->field('id,imei,create_time,value')
            ->whereNotLike('value','%x%')
            ->order('create_time desc')
            ->select()->toArray();
        return $pagingData;

    }


    public static function getToday($imei)
    {
        $pagingData = self::where('imei', '=', $imei)
            ->whereTime('create_time', 'today')
            ->field('id,imei,create_time,value')
            ->order('create_time desc')
            ->find();
        return $pagingData;
    }

}