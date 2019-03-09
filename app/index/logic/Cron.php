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

namespace app\index\logic;

use think\Db;

/**
 * 计划任务业务
 */
class Cron extends IndexBase
{
    
    /**
     * 刷角色信息
     * 5分钟
     */
    public function refreshRole()
    {
        
        $time = time();
        
        echo '执行开始时间：'.date('y-m-d H:i:s', $time);
        
        $start  = $time - 300;
        $end    = $time;
        
        $map['login_time']  = [['egt',$start], ['elt',$end], 'and'];
        $map['status'] = 1;
        
        $player_list = Db::name('wg_player')->where($map)->field('id,game_id,server_id,member_id,update_time')->order('update_time asc')->limit(30)->select();
        
        $roles_data_all = [];
        
        foreach ($player_list as $info)
        {
            
            $game_code = ucfirst(get_game_code($info['game_id']));
            
            $driver = SYS_DRIVER_DIR_NAME . $game_code;
            
            if (!class_exists('app\common\service\webgame\driver\\' . $game_code)) {

                continue;
            }
            
            Db::name('wg_player')->where(['id' => $info['id']])->update(['update_time' => $time]);
            
            $driver = SYS_DRIVER_DIR_NAME . $game_code;
            
            $roles = $this->serviceWebgame->$driver->roles($info['member_id'], get_cp_server_id($info['server_id']));
            
            foreach ($roles as $r)
            {
                
                $data = [];
                $data['player_id']  = $info['id'];
                $data['role_id']    = $r['role_id'];
                $data['role_level'] = $r['level'];
                $data['role_name']  = $r['nickname'];
                
                $data['update_time']= time();
                
                $role_name = Db::name('wg_role')->where(['role_id' => $r['role_id']])->value('role_name');
                
                if (!empty($role_name) && $r['nickname'] == $role_name) {
                    
                    continue;
                }
                
                if (empty($role_name)) {
                    
                    $data['create_time']= time();
                    
                    $roles_data_all[] = $data;
                } else {
                    
                    Db::name('wg_role')->where(['player_id' => $info['id'], 'role_id' => $r['role_id']])->update($data);
                }
                
                dump($data);
            }
        }
        
        !empty($roles_data_all) && Db::name('wg_role')->insertAll($roles_data_all);
        
        echo '，执行结束时间：'.date('y-m-d H:i:s', $time);
    }
}
