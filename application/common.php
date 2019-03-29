<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * @param $url
 * @param $header
 * @param $content
 * @return mixed
 */
function post($url, $header, $content)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //TRUE-->将curl_exec()获取的信息以字符串返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    //启用时会将头文件的信息作为数据流输出
    curl_setopt($ch, CURLOPT_POST, true);
    //启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
    if (curl_exec($ch) === false) //curl_error()返回当前会话最后一次错误的字符串
    {
        die("Curlerror: " . curl_error($ch));
    }
    $response = curl_exec($ch);
    //获取返回的文件流
    curl_close($ch);
    return $response;
}

function get($url, $header, $content)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

    if (curl_exec($ch) === false) //curl_error()返回当前会话最后一次错误的字符串
    {
        die("Curlerror: " . curl_error($ch));
    }
    $response = curl_exec($ch);
    //获取返回的文件流
    curl_close($ch);
    return $response;
}


function delete($url, $header, $content)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

    if (curl_exec($ch) === false) //curl_error()返回当前会话最后一次错误的字符串
    {
        die("Curlerror: " . curl_error($ch));
    }
    $response = curl_exec($ch);
    //获取返回的文件流
    curl_close($ch);
    return $response;
}


/**
 * 导出数据到CSV文件
 * @param array $list 数据
 * @param array $title 标题
 * @param string $filename CSV文件名
 */
function put_csv($list, $title, $filename)
{
    try {
        $file_name = $filename;
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $file_name);
        header('Cache-Control: max-age=0');
        $file = fopen('php://output', "a");
        $limit = 1000;
        $calc = 0;
        foreach ($title as $v) {
            $tit[] = iconv('UTF-8', 'GB2312//IGNORE', $v);
        }

        fputcsv($file, $tit);
        foreach ($list as $v) {

            $calc++;
            if ($limit == $calc) {
                ob_flush();
                flush();
                $calc = 0;
            }
            foreach ($v as $t) {
                $t = is_numeric($t) ? $t . "\t" : $t;
                $tarr[] = iconv('UTF-8', 'GB2312//IGNORE', $t);
            }
            fputcsv($file, $tarr);
            unset($tarr);
        }
        unset($list);
        fclose($file);
        exit();
    } catch (\think\Exception $e) {
        echo $e->getMessage();
    }
}


function export_csv_1($data = [], $header_data = [], $file_name = '')
{
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $file_name);
    if (!empty($header_data)) {
        echo iconv('utf-8', 'gbk//TRANSLIT', '"' . implode('","', $header_data) . '"' . "\n");
    }
    foreach ($data as $key => $value) {
        $output = array();
        $output[] = $value['id'];
        $output[] = $value['name'];
        echo iconv('utf-8', 'gbk//TRANSLIT', '"' . implode('","', $output) . "\"\n");
    }
}


/**
 * 导出CSV文件
 * @param array $data 数据
 * @param array $header_data 首行数据
 * @param string $file_name 文件名称
 * @return string
 */
function export_csv_2($data = [], $header_data = [], $file_name = '')
{
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename=' . $file_name);
    header('Cache-Control: max-age=0');
    $fp = fopen('php://output', 'a');
    if (!empty($header_data)) {
        foreach ($header_data as $key => $value) {
            $header_data[$key] = iconv('utf-8', 'gbk', $value);
        }
        fputcsv($fp, $header_data);
    }
    $num = 0;
    //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
    $limit = 100000;
    //逐行取出数据，不浪费内存
    $count = count($data);
    if ($count > 0) {
        for ($i = 0; $i < $count; $i++) {
            $num++;
            //刷新一下输出buffer，防止由于数据过多造成问题
            if ($limit == $num) {
                ob_flush();
                flush();
                $num = 0;
            }
            $row = $data[$i];
            foreach ($row as $key => $value) {
                $row[$key] = iconv('utf-8', 'gbk', $value);
            }
            fputcsv($fp, $row);
        }
    }
    fclose($fp);
}

function addDay($count, $time_old)
{
    $time_new = date('Y-m-d', strtotime('+' . $count . ' day',
        strtotime($time_old)));
    return $time_new;

}





