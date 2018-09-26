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
Route::post('api/:version/receive/send', 'api/:version.Index/send');



