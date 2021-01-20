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

if (!function_exists('lc_curl')) {
    function lc_curl($url, $option = [], &$optResponse = []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        if(stripos($url, "https:") === 0){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        if (isset($option['post'])) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $option['post']);
        }
        if (isset($option['debug']) && $option['debug'] === true) {
            curl_setopt($ch, CURLOPT_VERBOSE, true); // curl debug
            curl_setopt($ch, CURLOPT_STDERR, fopen('/tmp/curl_debug.log', 'w+')); // curl debug
        }
        foreach ($option as $k => $v) {
            if (is_int($k)) {
                curl_setopt($ch, $k, $v);
            }
        }
        $resp = curl_exec($ch);
        if ($resp === false) {
            $msg = sprintf('curl_errno: %s, curl_error: %s, url: %s', curl_errno($ch), curl_error($ch), $url);
            throw new \Exception($msg, -1);
        }
        if (isset($option['curl_getinfo'])) {
            $opt_response['curl_getinfo'] = curl_getinfo($ch);
        }
        curl_close($ch);
        return $resp;
    }
}