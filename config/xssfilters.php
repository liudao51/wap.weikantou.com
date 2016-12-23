<?php
/**
 * Created by PhpStorm.
 * User: liuzw
 * Date: 2016/12/21
 * Time: 11:52
 */

/**
 * xss跨站攻击需要过滤的文件配置
 */
$xssfilters = array(
    '--',
    '<!',
);

return $xssfilters;
