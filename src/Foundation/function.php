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

if (!function_exists('array_set')) {
    function array_set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
        return $array;
    }
}

if (!function_exists('array_exists')) {
    function array_exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }
        return array_key_exists($key, $array);
    }
}

if (!function_exists('array_accessible')) {
    function array_accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }
}

if (!function_exists('value_callable')) {
    function value_callable($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('array_get')) {
    function array_get($array, $key, $default = null)
    {
        if (!array_accessible($array)) {
            return value_callable($default);
        }
        if (is_null($key)) {
            return $array;
        }
        if (array_exists($array, $key)) {
            return $array[$key];
        }
        if (strpos($key, '.') === false) {
            return $array[$key] ?? value_callable($default);
        }
        foreach (explode('.', $key) as $segment) {
            if (array_accessible($array) && array_exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return value_callable($default);
            }
        }
        return $array;
    }
}
