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

namespace app\common\service\webgame;

use app\common\service\BaseInterface;

/**
 * 页游服务驱动
 */
interface Driver extends BaseInterface
{
    
    /**
     * 获取驱动参数
     */
    public function getDriverParam();
    
    /**
     * 获取基本信息
     */
    public function driverInfo();
    
    /**
     * 配置信息
     */
    public function config();
    
    /**
     * 页游登录
     */
    public function login($mid, $sid, $is_client);
    
    /**
     * 页游角色列表
     */
    public function roles($mid, $sid);
    
    /**
     * 页游充值
     */
    public function pay($order);
    
}
