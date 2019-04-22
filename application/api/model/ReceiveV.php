<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2019-04-21
 * Time: 22:20
 */

namespace app\api\model;


use app\lib\enum\CommonEnum;
use think\Db;

class ReceiveV
{
    public static function subQuery()
    {
        $subQuery = Db::table('onenet_receive_t')
            ->field('imei,MAX(create_time) AS create_time')
            ->where('ds_id', '=', "3300_0_5751")
            ->group('date_format(create_time, "%Y-%m-%d"),imei')
            ->buildSql();
        return $subQuery;
    }

    public static function getList($imei, $startTime, $endTime, $page, $size)
    {
        $time_begin = date("Y-m-d", strtotime($startTime));
        $time_end = addDay(1, $endTime);
        $pagingData = Db::table('onenet_receive_t')
            ->alias('a')
            ->join([self::subQuery() => 'b'], 'a.imei=b.imei and a.create_time=b.create_time')
            ->where('a.imei', '=', $imei)
            ->where('a.state', CommonEnum::SUCCESS)
            ->whereBetweenTime('a.create_time', $time_begin, $time_end)
            ->field('a.id,a.imei,a.value,a.create_time')
            ->order('a.create_time desc')
            ->paginate($size, false, ['page' => $page])->toArray();
        return $pagingData;

    }

    public static function getListForExport($imei, $startTime, $endTime)
    {
        $time_begin = date("Y-m-d", strtotime($startTime));
        $time_end = addDay(1, $endTime);

        $pagingData = Db::table('onenet_receive_t')
            ->alias('a')
            ->join([self::subQuery() => 'b'], 'a.imei=b.imei and a.create_time=b.create_time')
            ->where('a.imei', '=', $imei)
            ->where('a.state', CommonEnum::SUCCESS)
            ->whereBetweenTime('create_time', $time_begin, $time_end)
            ->field('id,imei,create_time,value')
            ->order('create_time desc')
            ->select()->toArray();
        return $pagingData;

    }

    public static function getCurrentValue($imei)
    {

        $info = Db::table('onenet_receive_t')
            ->alias('a')
            ->join([self::subQuery() => 'b'], 'a.imei=b.imei and a.create_time=b.create_time')
            ->where('a.imei', '=', $imei)
            ->where('a.state', CommonEnum::SUCCESS)
            ->order('create_time desc')
           ->find();
        return $info;

    }

}