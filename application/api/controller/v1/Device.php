<?php
/**
 * Created by PhpStorm.
 * User: mingliang
 * Date: 2018/10/21
 * Time: 4:51 PM
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\DeviceT;
use app\api\model\PendingSendT;
use app\api\service\DeviceService;
use app\api\service\OneNet;
use app\api\service\SendService;
use app\api\validate\DeviceValidate;
use app\lib\exception\SuccessMessage;

class Device extends BaseController
{
    /**
     * @api {POST} /api/v1/device/save 添加设备
     * @apiGroup  API
     * @apiVersion 1.0.1
     * @apiDescription  将设备保存到平台
     * @apiExample {post}  请求样例:
     *    {
     *       "imei": "865820035119960",
     *       "imsi": "865820035129960",
     *       "title": "设备1号"
     *     }
     * @apiParam (请求参数说明) {String} imei  设备IMEI号
     * @apiParam (请求参数说明) {String} imsi  设备IMSI号
     * @apiParam (请求参数说明) {String} title  设备名称
     * @apiSuccessExample {json} 返回样例:
     *{"device_id":"1231231"}
     * @apiSuccess (返回参数说明) {String} device_id 设备在平台id
     *
     * @return \think\response\Json
     * @throws \app\lib\exception\OneNetException
     * @throws \app\lib\exception\ParameterException
     */
    public function addDevice()
    {
        (new DeviceValidate())->scene('save')->goCheck();
        $params = $this->request->param();
        (new OneNet())->addDevice($params);
        return json(new SuccessMessage());


    }

    public function updateDevice()
    {

    }

    /**
     * @api {POST} /api/v1/device/delete 删除设备
     * @apiGroup  API
     * @apiVersion 1.0.1
     * @apiDescription  将指定设备从平台删除
     * @apiExample {post}  请求样例:
     *    {
     *       "device_id": "121"
     *     }
     * @apiParam (请求参数说明) {String} device_id  设备在平台ID
     * @apiSuccessExample {json} 返回样例:
     * {"msg": "ok","error_code": 0}
     * @apiSuccess (返回参数说明) {int} error_code 错误代码 0 表示没有错误
     * @apiSuccess (返回参数说明) {String} msg 操作结果描述
     * @apiSuccess (返回参数说明) {int} id 初始化信息缓存id
     *
     * @param $device_id
     * @return \think\response\Json
     * @throws \app\lib\exception\OneNetException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function deleteDevice($device_id)
    {

        (new DeviceValidate())->scene('save')->goCheck();
        (new OneNet())->deleteDevice($device_id);
        return json(new SuccessMessage());
    }

    /**
     * @api {GET} /api/v1/devices 获取设备列表
     * @apiGroup  API
     * @apiVersion 1.0.1
     * @apiDescription 获取设备列表
     * @apiExample {get} 请求样例:
     * http://oil.mengant.cn/api/v1/devices?page=1&size=2
     * @apiParam (请求参数说明) {String} page   当前页数
     * @apiParam (请求参数说明) {String} size   每页条数
     * @apiSuccessExample {json} 返回样例:
     * {"total":1,"per_page":"5","current_page":1,"last_page":1,"data":[{"id":2,"name":"铁塔一号","imei":"865820035119960","state":1}]}
     * @apiSuccess (返回参数说明) {int} total 数据总数
     * @apiSuccess (返回参数说明) {int} per_page 每页多少条数据
     * @apiSuccess (返回参数说明) {int} current_page 当前页码
     * @apiSuccess (返回参数说明) {int} last_page 最后页码
     * @apiSuccess (返回参数说明) {obj} data 数据
     * @apiSuccess (返回参数说明) {int} id  设备id
     * @apiSuccess (返回参数说明) {String} name 设备名称
     * @apiSuccess (返回参数说明) {String} imei 设备IMEI
     * @apiSuccess (返回参数说明) {int} state 设备状态：1 | 正常；2 | 停用
     * @param $page
     * @param $size
     * @return \think\Paginator
     */
    public function getList($page, $size)
    {
        $admin_id = 1;
        $list = (new DeviceT())->getList($admin_id, $page, $size);
        return json($list);

    }

    /**
     * @api {POST} /api/v1/device/init 设备初始化
     * @apiGroup  API
     * @apiVersion 1.0.1
     * @apiDescription  将发送信息缓存，等待设备下一次启动时发送
     * @apiExample {post}  请求样例:
     *    {
     *       "imei": "865820035119960",
     *       "X0": 1.1,
     *       "Y0": 2.22,
     *       "X1": 2.22,
     *       "Y1": 2.22,
     *     }
     * @apiParam (请求参数说明) {String} imei  设备IMEI号
     * @apiParam (请求参数说明) {float} X0  X维度的初始值
     * @apiParam (请求参数说明) {float} Y0  Y维度的初始值
     * @apiParam (请求参数说明) {float} X1  X维度的报警阀值
     * @apiParam (请求参数说明) {float} Y1  Y维度的报警阀值
     * @apiSuccessExample {json} 返回样例:
     *{"id":1}
     * @apiSuccess (返回参数说明) {int} id 初始化信息缓存id
     */
    public function deviceInit()
    {
        (new DeviceValidate())->scene('init')->goCheck();
        $params = $this->request->param();
        $id = (new SendService())->savePendingRecord($params['imei'], $params['X0'],
            $params['Y0'], $params['X1'], $params['Y1']);
        return json([
            'id' => $id
        ]);

    }

    /**
     * @api {GET} /api/v1/device/current 获取指定设备X,Y实时数据
     * @apiGroup  API
     * @apiVersion 1.0.1
     * @apiDescription 根据设备IMEI号，获取最近一条设备数据
     * @apiExample {get}  请求样例:
     * http://oil.mengant.cn/api/v1/device/current?imei=865820035119960
     * @apiParam (请求参数说明) {String} imei  设备IMEI号
     * @apiSuccessExample {json} 返回样例:
     * {"x":"0","y":"-79","create_time":"2018-11-09 09:50 49"}
     * @apiSuccess (返回参数说明) {int} x X轴实时数据
     * @apiSuccess (返回参数说明) {int} y Y轴实时数据
     * @apiSuccess (返回参数说明) {String} create_time 创建时间
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function getCurrentValue()
    {
        (new DeviceValidate())->scene('imei')->goCheck();
        $imei = $this->request->param('imei');
        $info = (new DeviceService())->getCurrentValue($imei);
        return json($info);


    }

    /**
     * @api {GET} /api/v1/device/init/res 获取设备初始化结果
     * @apiGroup  API
     * @apiVersion 1.0.1
     * @apiDescription 根据设备IMEI号，获取最近一条设备数据
     * @apiExample {get}  请求样例:
     * http://oil.mengant.cn/api/v1/device/init/res?id=1
     * @apiParam (请求参数说明) {String} id  初始化订单id
     * @apiSuccessExample {json} 返回样例:
     * {"state":2}
     * @apiSuccess (返回参数说明) {int} state 初始化结果：1 | 成功；2 | 待处理; 3 | 失败
     *
     */
    public function getInitRes()
    {
        (new DeviceValidate())->scene('id')->goCheck();
        $id = $this->request->param('id');
        $state = PendingSendT::where('id', $id)
            ->field('state')
            ->find();
        return json($state);
    }

    /**
     * @api {GET} /api/v1/device/init/info 根据设备IMEI号获取设备初始化数据
     * @apiGroup  API
     * @apiVersion 1.0.1
     * @apiDescription 根据设备IMEI号获取设备初始化数据
     * @apiExample {get}  请求样例:
     * http://oil.mengant.cn/api/v1/device/init/info?imei=865820035119960
     * @apiParam (请求参数说明) {String} imei  设备IMEI号
     * @apiSuccessExample {json} 返回样例:
     * {"id":16,"imei":"865820035119960","X0":-437,"Y0":-12,"X1":20,"Y1":20,"T1":180,"T2":2,"create_time":"2018-10-24 18:20:30","update_time":"2018-10-24 23:44:57","state":1}
     * @apiSuccess (返回参数说明) {int} id 记录id
     * @apiSuccess (返回参数说明) {float} X0  X维度的初始值
     * @apiSuccess (返回参数说明) {float} Y0  Y维度的初始值
     * @apiSuccess (返回参数说明) {float} X1  X维度的报警阀值
     * @apiSuccess (返回参数说明) {float} Y1  Y维度的报警阀值
     * @apiSuccess (返回参数说明) {int} T1 每检测几次就上传数据
     * @apiSuccess (返回参数说明) {int} T2 多长时间检测一次数据
     * @apiSuccess (返回参数说明) {String} create_time 创建时间
     * @apiSuccess (返回参数说明) {String} update_time 初始化时间
     * @apiSuccess (返回参数说明) {int} state 初始化状态：1 | 成功；2 | 待处理
     * @param $imei
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getInit($imei)
    {
        $info = (new  DeviceService())->getInitInfo($imei);
        return json($info);

    }



}