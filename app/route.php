<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

/**
 * 路由文件
 */

return [
    
    'g/:game_code'      => 'website/index',
    'channel/:code'     => 'index/channel',
    'paytop'            => 'index/paytop',
    'client/:game_code' => 'client/index',
    'center/pay'        => 'center/pay'
];
