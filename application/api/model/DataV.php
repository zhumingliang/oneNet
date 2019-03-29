<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/10/24
 * Time: 12:29 AM
 */

namespace app\api\model;


use think\Model;

class DataV extends Model
{
    public static function getList($imei, $startTime, $endTime, $page, $size)
    {
        $time_begin = date("Y-m-d", strtotime($startTime));
        $time_end = addDay(1, $endTime);

        $pagingData = self::where('imei', '=', $imei)
            ->whereBetweenTime('create_time', $time_begin, $time_end)
            ->field('id,imei,create_time')
            ->order('create_time desc')
            ->paginate($size, false, ['page' => $page])->toArray();
        return $pagingData;

    }


    public static function getListForExport($imei, $startTime, $endTime)
    {
        $time_begin = date("Y-m-d", strtotime($startTime));
        $time_end = addDay(1, $endTime);

        $pagingData = self::where('imei', '=', $imei)
           // ->whereBetweenTime('create_time', $time_begin, $time_end)
            ->field('id,imei,create_time')
            ->order('create_time desc')
            ->select()->toArray();
        return $pagingData;

    }
}