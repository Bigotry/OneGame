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

namespace app\common\service;

/**
 * 页游服务
 */
class Webgame extends ServiceBase implements BaseInterface
{
    
    /**
     * 服务基本信息
     */
    public function serviceInfo()
    {
        
        return ['service_name' => '页游服务', 'service_class' => 'Webgame', 'service_describe' => '系统页游服务，用于整合多个页游平台', 'author' => 'Bigotry', 'version' => '1.0'];
    }

}
