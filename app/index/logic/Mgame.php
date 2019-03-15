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

use app\index\logic\Play;
use think\Db;

/**
 * 手游业务逻辑
 */
class Mgame extends IndexBase
{
    
    /**
     * 手游开始
     */
    public function play($gid)
    {
        
        $driver = SYS_DRIVER_DIR_NAME . ucfirst('Jiule');
        
        $p = new Play();
        
        $game_info = $this->modelMgGame->getInfo(['game_id' => $gid]);
        
        $player_info = $p->savePlayer(is_login(), $game_info, [], 1);
        
        $p->checkBinding($player_info);
        
        return $this->serviceMgame->$driver->play($gid);
    }
    
    /**
     * 手游列表
     */
    public function getGameList($param)
    {
        
        $where = [];
        
        if (!empty($param['cid'])) {
            
            $where['game_category_id'] = $param['cid'];
        }
        
        return $this->modelMgGame->getList($where, true, 'is_recommend desc,is_hot desc,create_time desc', 30);
    }
    
    /**
     * 安装包下载
     */
    public function download($id = 0)
    {
        
        $game_info = $this->modelMgGame->getInfo(['id' => $id]);
        
        $code = get_register_code();
        
        $member_id = is_login();
        
        $conference_id = 0;
        $c_member_id   = 0;
        
        if (!empty($code)) {
            
            $code_info = $this->modelWgCode->getInfo(['code' => $code]);
            
            $b_map['member_id']     = $member_id;
            $b_map['game_id']       = $code_info['game_id'];
            
            $b_info = Db::name('wg_bind')->where($b_map)->find();
            
            $conference_id  = $code_info['conference_id'];
            $c_member_id    = $code_info['member_id'];
            
            if (empty($b_info)) {
                
                $b_map['conference_id'] = $code_info['conference_id'];
                $b_map['employee_id']   = $code_info['member_id'];
                $b_map['create_time'] = TIME_NOW;
                $b_map['update_time'] = TIME_NOW;
                $b_map['is_check']    = 1;
                $b_map['type']        = 1;
                Db::name('wg_bind')->insert($b_map);
            }
            
            session('register_code', null); cookie('register_code', null);
        } else {
            
            $bind_info = $this->modelWgBind->getInfo(['member_id' => $member_id]);
            
            if (!empty($bind_info)) {
                $conference_id  = $bind_info['conference_id'];
                $c_member_id    = $bind_info['employee_id'];
            }
        }
        
        $add_data['game_id']        = $id;
        $add_data['member_id']      = $member_id;
        $add_data['conference_id']  = $conference_id;
        $add_data['c_member_id']    = $c_member_id;
        $add_data['create_date']    = date("Y-m-d");
        $add_data['create_month']   = date("Y-m");
        
        $info = Db::name('mg_download_log')->where($add_data)->field(true)->find();
        
        if (empty($info)) {
            $add_data['create_time']    = time();
            $add_data['update_time']    = time();
            $add_data['status']         = 1;

            Db::name('mg_download_log')->insert($add_data);
        }
        
        header('location:'.$game_info['download_url']);
        exit;
    }
}
