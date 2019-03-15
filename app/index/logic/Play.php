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
 * 开始游戏业务逻辑
 */
class Play extends IndexBase
{
    
    /**
     * 进入游戏
     */
    public function login($game_code = '', $sid = 0, $client = '')
    {
        
        $game_info = $this->modelWgGame->getInfo(['game_code' => $game_code]);
        
        $check_game_result = $this->checkGame($game_info);
        
        if (is_string($check_game_result)) {
            
            $url = url('website/index', array('game_code' => $game_code));
            
            return [RESULT_ERROR, $check_game_result, $url];
        }
        
        $check_server_result = $this->checkServer($game_info, $sid);
        
        if (is_string($check_server_result)) {
            
            $url = url('website/index', array('game_code' => $game_code));
            
            return [RESULT_ERROR, $check_server_result, $url];
        }
        
        $driver = SYS_DRIVER_DIR_NAME . ucfirst($game_code);
        
        $mid = is_login();
        
        $url = $this->serviceWebgame->$driver->login($mid, $check_server_result['cp_server_id'], $client);
        
        $player_info = $this->savePlayer($mid, $game_info, $check_server_result);
        
        $this->checkBinding($player_info);
        
        return [RESULT_SUCCESS, $url];
    }
    
    /**
     * 检查游戏
     */
    public function checkGame($game_info = [])
    {
        
        if (empty($game_info)) {
            
            return "游戏暂未接入";
        }
        
        $member = session('member_info');
        
        if (!empty($game_info['maintain_end_time']) && empty($member['is_test'])) {
            
            $game_start_time = strtotime($game_info['maintain_end_time']);
            
            if (time() < $game_start_time) {

                return "游戏未开或全服维护中，请关注游戏开服或维护通知";
            }
        }
        
        return true;
    }
    
    /**
     * 检查服务器
     */
    public function checkServer($game_info = [], $sid = 0)
    {
        
        $server_info = null;
        
        if (empty($sid)) {
            
            
            $server_list = $this->modelWgServer->getList(['game_id' => $game_info['id'], 'status' => DATA_NORMAL], true, 'start_time desc', false, [], '', 1);
            
            if (empty($server_list[0])) {

                return "此游戏暂无开服信息，请关注开服或维护通知";
            }
            
            $server_info = $server_list[0];
        } else {
            
            $server_info = $this->modelWgServer->getInfo(['game_id' => $game_info['id'], 'cp_server_id' => $sid, 'status' => DATA_NORMAL]);
            
            if (empty($server_info)) {

                return "此游戏暂无此服，请关注开服或维护通知";
            }
        }
        
        $member = session('member_info');

        $time = time();
        
        if ($time < $server_info['start_time'] && empty($member['is_test'])) {

            return "暂未开服或维护中，请关注开服或维护通知";
        }
            
        $driver = SYS_DRIVER_DIR_NAME . ucfirst($game_info['game_code']);
        
        if (!class_exists('app\common\service\webgame\driver\\' . ucfirst($game_info['game_code']))) {

            return "游戏SDK暂未接入，请联系技术支持。";
        }
        
        $roles = $this->serviceWebgame->$driver->roles($member['id'], $server_info['cp_server_id']);
        
        if (empty($roles) && $time > ($server_info['start_time'] + (3600 * 48 - 1)) && empty($member['is_test'])) {

            return "该服已经停止新角色注册，请选择新服进行游戏";
        }
        
        return $server_info;
    }
    
    /**
     * 保存玩家信息
     */
    public function savePlayer($mid = 0, $game_info = [], $server_info = [], $type = 0)
    {
        
        $data['member_id']  = $mid;
        $data['game_id']    = $game_info['id'];
        
        !empty($server_info) && $data['server_id']  = $server_info['id'];
        
        $player_info = $this->modelWgPlayer->getInfo($data);
        
        $data['login_ip']       = request()->ip();
        $data['login_time']     = time();
        
        if (empty($player_info)) {
            
            $data['create_date']    = date("Y-m-d");
            $data['create_month']   = date("Y-m");
            $data['create_time']    = time();
            $data['type']           = $type;
            
            $pid = Db::name('wg_player')->insertGetId($data);
        } else {
            
            Db::name('wg_player')->where(['id' => $player_info['id']])->update($data);
            
            $pid = $player_info['id'];
        }
        
        return $this->modelWgPlayer->getInfo(['id' => $pid]);
    }
    
    /**
     * 检查绑定信息
     */
    public function checkBinding($player_info = [])
    {
        
        $code = get_register_code();
        
        if (empty($player_info['register_code']) && !empty($code)) {
            
            $code_info = $this->modelWgCode->getInfo(['code' => $code]);
            
            $data['register_code']  = $code;
            $data['conference_id']  = $code_info['conference_id'];
            $data['c_member_id']    = $code_info['member_id'];
            
            Db::name('wg_player')->where(['id' => $player_info['id']])->update($data);
            
            $b_map['member_id']     = $player_info['member_id'];
            $b_map['game_id']       = $code_info['game_id'];
            
            $b_info = Db::name('wg_bind')->where($b_map)->find();
            
            if (empty($b_info)) {
                
                $b_map['conference_id'] = $code_info['conference_id'];
                $b_map['employee_id']   = $code_info['member_id'];
                $b_map['create_time'] = TIME_NOW;
                $b_map['update_time'] = TIME_NOW;
                $b_map['is_check']    = 1;
                $b_map['type']        = $player_info['type'];
                Db::name('wg_bind')->insert($b_map);
            }
            
            session('register_code', null); cookie('register_code', null);
        }
    }
}
