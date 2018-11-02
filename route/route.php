<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

Route::rule('api/:version/index', 'api/:version.Index/index');
Route::get('api/:version/receive/list', 'api/:version.Index/getList');
Route::get('api/:version/receive/recent', 'api/:version.Index/getRecent');
Route::get('api/:version/receive/export', 'api/:version.Index/exportData');
Route::get('api/:version/receive/send', 'api/:version.Index/send');
Route::get('api/:version/send/pending/test', 'api/:version.Index/savePendingTest');
Route::get('api/:version/send/test', 'api/:version.Index/sendTest');
Route::post('api/:version/send/pending', 'api/:version.Index/savePending');


Route::get('api/:version/devices', 'api/:version.Device/getList');
Route::get('api/:version/device/init', 'api/:version.Device/deviceInit');
Route::get('api/:version/device/init/info', 'api/:version.Device/getInit');
Route::get('api/:version/device/init/res', 'api/:version.Device/getInitRes');
Route::get('api/:version/device/current', 'api/:version.Device/getCurrentValue');
Route::get('api/:version/device/save', 'api/:version.Device/addDevice');
Route::get('api/:version/device/delete', 'api/:version.Device/deleteDevice');



