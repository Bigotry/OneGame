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

namespace app\api\error;

class Game
{
    
    public static $paramError               = [API_CODE_NAME => 1020001, API_MSG_NAME => '请求参数格式不正确'];
    public static $getRolesError            = [API_CODE_NAME => 1020002, API_MSG_NAME => '获取角色信息异常'];
}
