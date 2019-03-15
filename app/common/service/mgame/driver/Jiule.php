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

namespace app\common\service\mgame\driver;

use app\common\service\mgame\Driver;
use app\common\service\Mgame;
use think\Db;

/**
 * 久乐手游服务驱动
 */
class Jiule extends Mgame implements Driver
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
     * 获取手游列表
     */
    public function gameList()
    {
        
        $data = exec_get_request("http://h5.zyttx.com/api/applistV2?type=0&page=1&pagesize=10000&starttime=0&endtime=0");
        
        $game_data = json_decode($data, true);
        
        $category_index_arr['角色扮演'] = 2;
        $category_index_arr['动作过关'] = 3;
        $category_index_arr['梦幻回合'] = 5;
        $category_index_arr['仙侠即时'] = 6;
        $category_index_arr['经营策略'] = 7;
        $category_index_arr['卡牌三国'] = 8;
        $category_index_arr['魔幻动漫'] = 9;
        $category_index_arr['休闲竞技'] = 10;
        $category_index_arr['放置挂机'] = 11;
        $category_index_arr['其他']     = 12;
        
        $game_type_index_arr[1] = 1;
        $game_type_index_arr[4] = 0;
        
        foreach ($game_data['items'] as $v)
        {
            
            $update_data = [];
            
            $update_data['game_name'] = $v['name'];
            
            $mg_game_info = Db::name('mg_game')->where($update_data)->field(true)->find();
            
            $update_data['game_category_id']    = $category_index_arr[$v['categoryName']];
            $update_data['game_type']           = $game_type_index_arr[$v['dtype']];
            $update_data['game_intro']          = $v['brief'];
            $update_data['game_cover']          = $v['screenshotsUrl'];
            $update_data['game_head']           = $v['iconUrl'];
            $update_data['version']             = $v['minVersion'];
            $update_data['describe_text']       = $v['description'];
            $update_data['download_url']        = $v['downloadUrl'];
            $update_data['game_id']             = $v['id'];
            
            if (empty($mg_game_info)) {
                
                $update_data['status']             = 1;
                $update_data['update_time']        = time();
                $update_data['create_time']        = time();
                
                Db::name('mg_game')->insert($update_data);
            } else {
                
                $update_data['id'] = $mg_game_info['id'];
                
                Db::name('mg_game')->update($update_data);
            }
        }
        
    }
}
