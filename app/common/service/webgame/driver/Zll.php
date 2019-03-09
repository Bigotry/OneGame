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

namespace app\common\service\webgame\driver;

use app\common\service\webgame\Driver;
use app\common\service\Webgame;
use think\Db;

/**
 * 醉玲珑页游服务驱动
 */
class Zll extends Webgame implements Driver
{
    
    /**
     * 驱动基本信息
     */
    public function driverInfo()
    {
        
        return ['driver_name' => '醉玲珑驱动', 'driver_class' => 'Zll', 'driver_describe' => '醉玲珑页游驱动', 'author' => 'Bigotry', 'version' => '1.0'];
    }
    
    /**
     * 获取驱动参数
     */
    public function getDriverParam()
    {
        
        return ['login_key' => '醉玲珑登录KEY', 'pay_key' => '醉玲珑充值KEY'];
    }
    
    /**
     * 获取配置信息
     */
    public function config()
    {
        
        return $this->driverConfig('Zll');
    }
    
    /**
     * 醉玲珑登录
     */
    public function login($mid = 0, $sid = 0, $is_client = '')
    {

    }
    
    /**
     * 醉玲珑角色列表
     */
    public function roles($mid, $sid)
    {
   
    }
   
    /**
     * 醉玲珑充值
     */
    public function pay($order)
    {
     
    }
}
