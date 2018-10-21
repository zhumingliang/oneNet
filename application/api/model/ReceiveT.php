<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/9/25
 * Time: 下午10:37
 */

namespace app\api\model;


use think\Model;

class ReceiveT extends Model
{
    /**
     * 根据时间选择器获取列表
     * @param $imei
     * @param $startTime
     * @param $endTime
     * @param $page
     * @param $size
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public static function getList($imei, $startTime, $endTime, $page, $size)
    {
        $time_begin = date("Y-m-d", strtotime($startTime));
        $time_end = date("Y-m-d", strtotime($endTime));
        $pagingData = self::where('imei', '=', $imei)
            ->whereBetweenTime('create_time', $time_begin, $time_end)
            ->hidden(['update_time'])
            ->order('create_time desc')
            ->paginate($size, false, ['page' => $page]);
        return $pagingData;

    }

    /**
     * @param $imei
     * @return array|null|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getRecent($imei)
    {
        $data = self::where('imei', '=', $imei)
            ->order('create_time desc')
            ->hidden(['update_time'])
            ->find();

        return $data;
    }


    public static function getCurrentX($imei)
    {
        $x = self::where('imei', $imei)
            ->where('ds_id', '3300_0_5700')
            ->field('value')
            ->order('id desc')
            ->find();
        return $x;

    }

    public static function getCurrentY($imei)
    {
        $y = self::where('imei', $imei)
            ->where('ds_id', '3300_0_5601')
            ->field('value')
            ->order('id desc')
            ->find();
        return $y;

    }


}