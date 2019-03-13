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

namespace app\common\service\h5game\driver;

use app\common\service\h5game\Driver;
use app\common\service\H5game;

/**
 * 久乐手游服务驱动
 */
class Jiule extends H5game implements Driver
{
    
    /**
     * 驱动基本信息
     */
    public function driverInfo()
    {
        
        return ['driver_name' => '久乐手游驱动', 'driver_class' => 'Jiule', 'driver_describe' => '久乐手游驱动', 'author' => 'Bigotry', 'version' => '1.0'];
    }
    
    /**
     * 获取驱动参数
     */
    public function getDriverParam()
    {
        
        return ['channel_id' => '渠道ID', 'secret_key' => '密钥'];
    }
    
    /**
     * 获取配置信息
     */
    public function config()
    {
        
        return $this->driverConfig('Jiule');
    }
    
    /**
     * 手游开始
     */
    public function play($gid)
    {
        
        $db_config = $this->driverConfig('Jiule');
        
        $parameter['game_id']   = $gid;
        $parameter['account']   = 'bbs';
        $parameter['password']  = '123456';
        $parameter['username']  = $db_config['channel_id'] . '_' . is_login();
        $parameter['timestamp'] = time();
        
        ksort($parameter);
        
        $sign = md5(implode('', $parameter) . $db_config['secret_key']);
        
        $parameter['sign'] = $sign;
        
        return "http://".$db_config['channel_id'].".h5.zyttx.com/api/playGameOAuth.html?" . http_build_query($parameter);
    }
    
    /**
     * 手游列表
     */
    public function gameList($param)
    {
        
        if (empty($param['page'])) {
            
            $page = 1;
        } else {
            $page = $param['page'];
        }
        
        $type = '';
        
        if (!empty($param['type'])) {
            
            $type = $param['type'];
            
            if  ('动作格斗' == $type) {
                
                $type = "动作过关";
            }
            if  ('未归类' == $type) {
                
                $type = "其他";
            }
        }
        
        $game_list_data = exec_get_request("http://h5.zyttx.com/api/applistV2?type=1&page=$page&pagesize=30&starttime=0&endtime=0&categoryName=".$type);
        
        $data['game_data'] = json_decode($game_list_data, true);
        
        if  ('动作过关' == $type) {

            $type = "动作格斗";
        }
        
        if  ('其他' == $type) {

            $type = "未归类";
        }
        
        $data['prev_url'] = url('h5/index', ['page' => $page-1, 'type' => $type]);
        $data['next_url'] = url('h5/index', ['page' => $page+1, 'type' => $type]);
        
        return $data;
    }
}
