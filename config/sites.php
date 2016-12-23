<?php
/**
 * Created by PhpStorm.
 * User: liuzw
 * Date: 2016/12/21
 * Time: 11:52
 */

/**
 * 站点域名配置
 */
$app_env = env('APP_ENV', 'local');

$site = array();

if ($app_env === 'production') {
    $site['site_token'] = md5('wap.weikantou.com');
    $site['site_host'] = 'wap.weikantou.com';
    $site['site_path'] = str_replace('/config', '', str_replace('\\', '/', realpath(__DIR__)));  //当前网站根目录
    $site['api_host'] = 'api.weikantou.com';
} else if ($app_env === 'local' || $app_env === 'test') {
    $site['site_token'] = md5('test.wap.weikantou.com');
    $site['site_host'] = 'test.wap.weikantou.com';
    $site['site_path'] = str_replace('/config', '', str_replace('\\', '/', realpath(__DIR__)));  //当前网站根目录
    $site['api_host'] = 'test.api.weikantou.com';
} elseif ($app_env === 'weitest') {
    $site['site_token'] = md5('weitest.wap.weikantou.com');
    $site['site_host'] = 'weitest.wap.weikantou.com';
    $site['site_path'] = str_replace('/config', '', str_replace('\\', '/', realpath(__DIR__)));  //当前网站根目录
    $site['api_host'] = 'weitest.api.weikantou.com';
}

return $site;
