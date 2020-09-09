<?php
/**
 * Created by PhpStorm.
 * User: longqiang.chen
 * Date: 2020/9/9
 * Time: 18:49
 */

if (!function_exists('env')) {
    function env($key)
    {
        if (function_exists('putenv')) {
            return getenv($key);
        } else {
            return $_ENV[$key];
        }
    }
}

if (!function_exists('api_return')) {
    function api_return($code, $data = [], $msg = '')
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:POST,GET,OPTIONS');
        header('Access-Control-Allow-Credentials:true');
        exit(json_encode([
            'code' => $code,
            'data' => $data,
            'msg' => $msg,
        ]));
    }
}

if (!function_exists('api_success')) {
    function api_success($data = [])
    {
        api_return(0, $data, 'ok');
    }
}

if (!function_exists('api_error')) {
    function api_error($msg = 'error', $data = [])
    {
        api_return(1, $data, $msg);
    }
}

if (!function_exists('now_datetime')) {
    function now_datetime()
    {
        return date('Y-m-d H:i:s');
    }
}

if (!function_exists('v')) {
    function v($data)
    {
        echo '<pre>';
        var_dump($data);
        echo PHP_EOL;
    }
}

if (!function_exists('p')) {
    function p($data)
    {
        echo '<pre>';
        print_r($data);
        echo PHP_EOL;
    }
}

if (!function_exists('pe')) {
    function pe($data)
    {
        p($data);
        exit;
    }
}

if (!function_exists('ve')) {
    function ve($data)
    {
        v($data);
        exit;
    }
}