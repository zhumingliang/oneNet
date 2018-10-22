<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/10/21
 * Time: 6:12 PM
 */

namespace app\api\model;


use app\lib\enum\CommonEnum;
use think\Model;

class DeviceT extends Model
{
    public function getList($admin_id, $page, $size)
    {
        return self::where('state', CommonEnum::SUCCESS)
            ->where('admin_id', $admin_id)
            ->hidden(['create_time', 'update_time', 'admin_id'])
            ->paginate($size, false, ['page' => $page]);

    }

}