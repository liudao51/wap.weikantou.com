<?php
/**
 * Created by PhpStorm.
 * User: liuzw
 * Date: 2016/12/21
 * Time: 10:41
 */

namespace App\Libs;

/**
 * Api工具包
 *
 * Class Apikit
 * @package App\Libs
 */
class Apikit
{
    /**
     * api接口请求(http)
     *
     * @param string $action api请求路由
     * @param array $data 请求数据
     * @param array $header 请求的头部数据
     * @param int $timeout 请求超时时间(默认30秒)
     * @return object|null
     */
    public static function api_post($action, $data = array(), $header = array(), $timeout = 30)
    {
        if (!Toolkit::is_string($action) || !Toolkit::is_array($data) || !Toolkit::is_array($header) || !Toolkit::is_integer($timeout)) {
            return null;
        }

        return self::curl_post($action, $data, $header, $timeout, 0);
    }

    /**
     * api接口请求(https)
     *
     * @param string $action api请求路由
     * @param array $data 请求数据
     * @param array $header 请求的头部数据
     * @param int $timeout 请求超时时间(默认30秒)
     * @return object|null
     */
    public static function api_post_ssl($action, $data = array(), $header = array(), $timeout = 30)
    {
        if (!Toolkit::is_string($action) || !Toolkit::is_array($data) || !Toolkit::is_array($header) || !Toolkit::is_integer($timeout)) {
            return null;
        }

        return self::curl_post($action, $data, $header, $timeout, 1);
    }

    /**
     * api接口请求
     *
     * @param string $action api请求路由
     * @param array $data 请求数据
     * @param array $header 请求的头部数据
     * @param int $timeout 请求超时时间(默认30秒)
     * @param int $protocol 请求协议(0:http, 1:https)
     * @return object|null
     */
    private static function curl_post($action, $data = array(), $header = array(), $timeout = 30, $protocol = 0)
    {
        if (!Toolkit::is_string($action) || !Toolkit::is_array($data) || !Toolkit::is_array($header) || !Toolkit::is_integer($timeout) || !Toolkit::is_integer($protocol)) {
            return null;
        }
        if (($action = trim($action)) == '') {
            return null;
        }

        /*if (substr($action, 1) == '/' || substr($action, 1) == '\\') {
            $action = substr($action, 1, count($action) - 1);
        }*/
        $action = (Toolkit::is_string($action) && ($action = trim($action)) != '') ? preg_replace('/(\/){2,}/', '/', str_replace('\\', '/', $action)) : '';
        $action = ltrim(trim($action, '/'), 'wap/');
        $action = 'wap/' . $action;
        $url = config('sites.api_host') . '/' . $action;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($data) && count($data) > 0) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        if (!empty($header) && count($header) > 0) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        } else {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-Forwarded-For:' . Toolkit::get_client_ip()));  //默认以json格式请求
        }
        if ($protocol == 1) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  //是否检测服务器的证书是否由正规浏览器认证过的授权CA颁发的
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  //是否检测服务器的域名与证书上的是否一致
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');  //证书类型: "PEM" (default)、"DER"、"ENG"
            curl_setopt($curl, CURLOPT_SSLCERT, '/data/cert/php.pem');  //证书存放路径
            curl_setopt($curl, CURLOPT_SSLCERTPASSWD, '123456');  //证书密码
            curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');  //私钥类型："PEM" (default)、"DER"、"ENG"
            curl_setopt($curl, CURLOPT_SSLKEY, '/data/cert/php_private.pem');  //私钥存放路径
        }

        $http_response = curl_exec($curl);
        //$curl_info = curl_getinfo($curl);

        curl_close($curl);

        $post_data = json_decode($http_response, false);

        if (!isset($post_data) || empty($post_data)) {
            return null;
        }

        return $post_data;
    }

    /**
     * blade前端模板请求资源生成(基于网站根目录,即public目录)
     *
     * @param string $files 资源列表(','分隔)
     * @param string $type 资源类型(可选值:'css','js')
     * @param string $catalog 资源目录(默认值：'/')
     * @return string
     */
    public static function require_resources($files, $type, $catalog = '/')
    {
        $files = Toolkit::is_string($files) ? trim($files) : '';
        if ($files === '') {
            return '';
        }

        $type = Toolkit::is_string($type) ? trim($type) : '';
        if ($type === '') {
            return '';
        }

        $catalog = Toolkit::is_string($catalog) ? trim($catalog) : '';
        if ($catalog === '') {
            return '';
        }

        $resourceData = '';
        $resourceHost = 'http://' . config('sites.site_host');
        $theme = Toolkit::is_string($theme = config('view.theme', 'default')) ? trim($theme) : 'default';

        $resourceReplace_list = array('{@theme}' => $theme, '{@themePath}' => 'theme/' . $theme);  //路径($catalog,$files)中需要被替换的值
        $xssFilter_list = config('xssfilters');  //xss跨站攻击需要过滤的文件($files)
        $fileFilter_list = config('filefilters');  //正式环境需要被过滤的文件($files)
        $fileVersion_list = config('versions');  //文件版本列表

        //替换$catalog中$resourceReplace_list值
        $catalog_matches = array();
        if (($catalog_matches_counts = preg_match_all("/\{@[A-Za-z0-9]+\}/", $catalog, $catalog_matches, PREG_PATTERN_ORDER)) > 0) {
            foreach ($catalog_matches[0] as $catalog_matches_item) {
                if (array_key_exists($catalog_matches_item, $resourceReplace_list)) {
                    $catalog = str_replace($catalog_matches_item, $resourceReplace_list[$catalog_matches_item], $catalog);
                }
            }
        }
        //$catalog格式为：'abc/123'(即非'/'开头,非'/'结尾)
        $catalog = (Toolkit::is_string($catalog) && ($catalog = trim($catalog)) != '') ? preg_replace('/(\/){2,}/', '/', str_replace('\\', '/', $catalog)) : '';
        $catalog = trim($catalog, '/');

        $file_list = explode(',', $files); //文件列表

        foreach ($file_list as $file) {

            //替换$file中$resourceReplace_list值
            $file_matches = array();
            if (($file_matches_counts = preg_match_all("/\{@[A-Za-z0-9]+\}/", $file, $file_matches, PREG_PATTERN_ORDER)) > 0) {
                foreach ($file_matches[0] as $file_matches_item) {
                    if (array_key_exists($file_matches_item, $resourceReplace_list)) {
                        $file = str_replace($file_matches_item, $resourceReplace_list[$file_matches_item], $file);
                    }
                }
            }
            //$file：'abc/123.js'(即非'/'开头,非'/'结尾)
            $file = (Toolkit::is_string($file) && ($file = trim($file)) != '') ? preg_replace('/(\/){2,}/', '/', str_replace('\\', '/', $file)) : '';
            $file = rtrim(trim($file, '/'), '&');

            //文件加上版本号
            if (preg_match("/(\/)?([A-Za-z0-9-_]+)(\.)([A-Za-z0-9-_.]+)(\?)?/", $file, $matches)) {
                $short_file = rtrim(trim($matches[0], '/'), '?');  //从完整文件名(abc/123.js?x=1)中,截取短文件名(123.js)
                if (array_key_exists($short_file, $fileVersion_list) && isset($fileVersion_list[$short_file])) {
                    //给文件加上版本号(abc/123.js?x=1&v=20170208 或 abc/123.js?v=20170208)
                    $file = str_contains($file, '?') ? ($file . "&v=" . $fileVersion_list[$short_file]) : ($file . "?v=" . $fileVersion_list[$short_file]);
                }
            }

            //$file_path：'abc/123.js?x=1&v=20170208'(即非'/'开头,非'/'结尾)
            $file_path = $catalog . '/' . $file;
            $file_path = (Toolkit::is_string($file_path) && ($file_path = trim($file_path)) != '') ? preg_replace('/(\/){2,}/', '/', str_replace('\\', '/', $file_path)) : '';
            $file_path = trim($file_path, '/');

            //最终的文件路径
            $file = $resourceHost . '/' . $file_path;

            if (env('APP_ENV') === 'production') {
                $file = (str_contains($file, $fileFilter_list) || str_contains($file, $xssFilter_list)) ? '' : $file;
            }
            if ($file === '') {
                continue;
            }

            switch ($type) {
                case 'css': {
                    $resourceData .= "\r\n<link rel=\"stylesheet\" href=\"{$file}\" />";
                    break;
                }
                case 'js': {
                    $resourceData .= "\r\n<script type=\"text/javascript\" src=\"{$file}\"></script>";
                    break;
                }
            }
        }

        return $resourceData;
    }

    /**
     * 网站请求网址生成
     *
     * @param string $suburl 子目录
     * @param string $type 网址类型 (可选值:'site','api';  默认值:'site')
     * @return string
     */
    public static function require_url($suburl, $type = 'site')
    {
        $suburl = Toolkit::is_string($suburl) ? trim($suburl) : '';
        if ($suburl === '') {
            return '';
        }

        $type = Toolkit::is_string($type) ? trim($type) : '';
        if ($type === '') {
            return '';
        }

        $url = '';
        $site_host = 'http://' . config('sites.site_host');
        $api_host = 'http://' . config('sites.api_host');


        $suburl = (Toolkit::is_string($suburl) && ($suburl = trim($suburl)) != '') ? preg_replace('/(\/){2,}/', '/', str_replace('\\', '/', $suburl)) : '';
        $suburl = ltrim($suburl, '/');

        switch ($type) {
            case 'site': {
                $url = $site_host . '/' . $suburl;
                break;
            }
            case 'api': {
                $url = $api_host . '/' . $suburl;
                break;
            }
        }

        return $url;
    }

    /**
     * 网站请求网址生成
     *
     * @param string $blade 模板路径
     * @return string
     */
    public static function include_blade($blade)
    {
        $blade = Toolkit::is_string($blade) ? str_replace('.', '/', trim($blade)) : '';
        if ($blade === '') {
            return '';
        }

        $theme = Toolkit::is_string($theme = config('view.theme', 'default')) ? trim($theme) : 'default';
        //$app_root_path = base_path();  //当前网站根目录（调用laravel帮助函数）
        $app_root_path = config('sites.site_path');  //当前网站根目录（调用配置文件的配置）
        $file_path = "{$app_root_path}/resources/views/{$theme}/{$blade}.blade.php";

        $file_content = '';
        if (file_exists($file_path)) {
            $fp = fopen($file_path, "r");
            $buffer = 1024;  //每次读取1024字节
            while (!feof($fp)) {  //循环读取，直至读取完整个文件
                $file_content .= fread($fp, $buffer);
            }
        }

        return $file_content;
    }
}