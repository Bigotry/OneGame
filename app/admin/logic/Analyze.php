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

namespace app\admin\logic;

use think\Db;

/**
 * 游戏统计逻辑
 */
class Analyze extends AdminBase
{
    
    /**
     * 游戏注册记录
     */
    public function getRegisterList($param = [])
    {
        
        $map = [];
        
        $map['type'] = 0;
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $conference_info = $this->modelWgConference->getInfo(['member_id' => MEMBER_ID]);
            
            $map['conference_id'] = $conference_info['id'];
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_employee'))) {
            
            $map['c_member_id'] = MEMBER_ID;
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_agency'))) {
            
            $conference_list = $this->modelWgConference->getList(['source_member_id' => MEMBER_ID], 'id', '', false);
            
            $conference_list_ids = array_extract($conference_list);
            
            !empty($conference_list_ids) ? $map['conference_id'] = ['in', $conference_list_ids] : $map['conference_id'] = -1;
        }
        
        if (!empty($param['search_data'])) {
            
            if ($param['search_type'] == 'role') {
                
                    $role_map['role_name'] = ['like', (string)$param['search_data'] .'%'];

                    $role_list = $this->modelWgRole->getList($role_map, 'player_id', '', false);

                    if (!empty($role_list)) {

                        $player_id_list = array_extract($role_list, 'player_id');

                        !empty($player_id_list) && $map['id'] = ['in', $player_id_list];
                    } else {
                        $map['id'] = -1;
                    }
            }
            
            if ($param['search_type'] == 'username') {
                
                $map['member_id'] = Db::name('member')->where(['username' => $param['search_data']])->value('id');
            }
        }
        
        if (!empty($param['game_id']))   { $map['game_id']   = $param['game_id'];   }
        if (!empty($param['server_id'])) { $map['server_id'] = $param['server_id']; }
        
        $count = Db::name('wg_player')->where($map)->count('id');
        
        $id_list = Db::name('wg_player')->where($map)->field('id')->order('id desc')->paginate(DB_LIST_ROWS, $count, ['query' => request()->param()]);
        
        $ids = array_extract($id_list);
        
        $list = [];
        
        if (!empty($ids)) {
            
            $p_where['id']      = ['in', $ids];
            $p_where['type']    = 0;
            
            $list = Db::name('wg_player')->where($p_where)->field('id,game_id,server_id,member_id,login_ip,login_time,create_time,update_time,conference_id,c_member_id,create_date,login_ip')->order('id desc')->select();
            
            $game_list      = $this->modelWgGame->getList(['status' => DATA_NORMAL], 'id,game_name,game_code', '', false);
            $server_list    = $this->modelWgServer->getList([], 'id,server_name,cp_server_id', '', false);

            $game_list_to_id_key    = array_extract_map($game_list);
            $server_list_to_id_key  = array_extract_map($server_list);

            foreach ($list as &$v)
            {

                $v['create_time']       = format_time($v['create_time']);
                $v['username']          = get_username($v['member_id']);
                $v['c_username']        = get_username($v['c_member_id']);
                $v['conference_name']   = get_conference_name($v['conference_id']);
                $v['roles']             = '';
                $v['game_name']         = $game_list_to_id_key[$v['game_id']]['game_name'];
                $v['game_code']         = $game_list_to_id_key[$v['game_id']]['game_code'];
                
                $cp_server_id = 0;

                !empty($server_list_to_id_key[$v['server_id']]['cp_server_id']) && $cp_server_id = $server_list_to_id_key[$v['server_id']]['cp_server_id'];

                if (empty($cp_server_id)) {

                    $v['cp_server_id']      = '0';
                    $v['server_name']       = '已关服';
                    $v['roles']             = '已关服';
                    continue;
                    
                } else {
                
                    $v['cp_server_id']      = $server_list_to_id_key[$v['server_id']]['cp_server_id'];
                    $v['server_name']       = $server_list_to_id_key[$v['server_id']]['server_name'];
                }
                
                if (!file_exists(PATH_SERVICE . 'webgame' . DS . SYS_DRIVER_DIR_NAME . DS . ucfirst($v['game_code']) . EXT)) {

                    continue;
                }

                $cache_key = 'cache_roles_data_pid_' . $v['id'];

                $roles = null;

                $roles = cache($cache_key);

                if (empty($roles)) {

                    $roles = Db::name('wg_role')->where(['player_id' => $v['id']])->field('role_id,role_level,role_name')->select();

                    !empty($roles) && cache($cache_key, $roles, 60);
                }

                $i = 0;

                $role_info_str = '';

                foreach ($roles as $role_info)
                {

                      if ($i > 0) {

                          $role_info_str .=  '<br/>角色ID：' . $role_info['role_id'] . ' | ' .  '角色名：'.$role_info['role_name'] . ' | 等级：' . $role_info['role_level'];
                      } else {

                          $role_info_str .=  '角色ID：' . $role_info['role_id'] . ' | ' .  '角色名：'.$role_info['role_name'] . ' | 等级：' . $role_info['role_level'];
                      }

                      $i++;
                }

                $v['roles'] = $role_info_str;
            }
        }
        

        
        return ['render' => $id_list, 'list' => $list];
    }
    
    /**
     * 手游注册记录
     */
    public function getMregisterList($param = [])
    {
        
        $map = [];
        
        $map['type'] = 1;
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $conference_info = $this->modelWgConference->getInfo(['member_id' => MEMBER_ID]);
            
            $map['conference_id'] = $conference_info['id'];
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_employee'))) {
            
            $map['c_member_id'] = MEMBER_ID;
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_agency'))) {
            
            $conference_list = $this->modelWgConference->getList(['source_member_id' => MEMBER_ID], 'id', '', false);
            
            $conference_list_ids = array_extract($conference_list);
            
            !empty($conference_list_ids) ? $map['conference_id'] = ['in', $conference_list_ids] : $map['conference_id'] = -1;
        }
        
        if (!empty($param['search_data'])) {
            
            $map['member_id'] = Db::name('member')->where(['username' => $param['search_data']])->value('id');
        }
        
        if (!empty($param['game_id']))   { $map['game_id']   = $param['game_id'];   }
        
        $count = Db::name('wg_player')->where($map)->count('id');
        
        $id_list = Db::name('wg_player')->where($map)->field('id')->order('id desc')->paginate(DB_LIST_ROWS, $count, ['query' => request()->param()]);
        
        $ids = array_extract($id_list);
        
        $list = [];
        
        if (!empty($ids)) {
            
            $p_where['id']      = ['in', $ids];
            $p_where['type']    = 1;
            
            $list = Db::name('wg_player')->where($p_where)->field('id,game_id,server_id,member_id,login_ip,login_time,create_time,update_time,conference_id,c_member_id,create_date,login_ip')->order('id desc')->select();
            
            $game_list              = $this->modelMgGame->getList(['status' => DATA_NORMAL], 'id,game_name', '', false);
            $game_list_to_id_key    = array_extract_map($game_list);

            foreach ($list as &$v)
            {

                $v['create_time']       = format_time($v['create_time']);
                $v['username']          = get_username($v['member_id']);
                $v['c_username']        = get_username($v['c_member_id']);
                $v['conference_name']   = get_conference_name($v['conference_id']);
                $v['game_name']         = $game_list_to_id_key[$v['game_id']]['game_name'];
            }
        }
        
        return ['render' => $id_list, 'list' => $list];
    }
    
    /**
     * 每日汇总记录
     */
    public function getEverydayList($param = [])
    {
        
        $role_map = [];
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $conference_info = $this->modelWgConference->getInfo(['member_id' => MEMBER_ID]);
            
            $role_map['conference_id'] = $conference_info['id'];
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_employee'))) { $role_map['c_member_id'] = MEMBER_ID; }
        
        if(check_group(MEMBER_ID, config('auth_group_id_agency'))) {
            
            $conference_list = $this->modelWgConference->getList(['source_member_id' => MEMBER_ID], 'id', '', false);
            
            $conference_list_ids = array_extract($conference_list);
            
            !empty($conference_list_ids) ? $role_map['conference_id'] = ['in', $conference_list_ids] : $role_map['conference_id'] = -1;
        }
        
        if (!empty($param['begin_date']) && !empty($param['end_date'])) {
            
            $range_date_arr = get_date_from_range($param['begin_date'] , $param['end_date']);
            
             krsort($range_date_arr);
        } else {
            
            $range_date_arr = get_date_from_range(date("Y-m-d") , date("Y-m-d"));
        }
        
        if(count($range_date_arr) > 31) {
            
            return '日期跨度不能超过1个月哦~';
        }
        
        $data_list = [];
        
        $data_statistics = [];
        
        $data_statistics['register_number_total'] = 0;
        $data_statistics['register_ip_total']     = 0;
        $data_statistics['pay_number_total']      = 0;
        $data_statistics['pay_money_total']       = 0.00;
        
        foreach ($range_date_arr as $date)
        {
            
            $map = $role_map;
            $map['status']      = DATA_NORMAL;
            $map['create_date'] = $date;
            $map['type']        = 0;
            
            $register_number = $this->modelWgPlayer->stat($map);
            
            $register_ip_number = Db::name('wg_player')->where($map)->group('login_ip')->count('id');
            
            $map['pay_status']      = DATA_NORMAL;
            $map['order_status']    = DATA_NORMAL;
            $map['is_admin']        = DATA_DISABLE;
            
            $pay_number = Db::name('wg_order')->where($map)->group('member_id')->count('id');
            
            $pay_money = Db::name('wg_order')->where($map)->sum('order_money');
            
            empty($pay_money) && $pay_money = 0.00;
            
            $data['date']                   = $date;
            $data['register_number']        = $register_number;
            $data['register_ip_number']     = $register_ip_number;
            $data['pay_number']             = $pay_number;
            $data['pay_money']              = $pay_money;
            
            $data_list[] = $data;
            
            $data_statistics['register_number_total']  += $data['register_number'];
            $data_statistics['register_ip_total']      += $data['register_ip_number'];
            $data_statistics['pay_number_total']       += $data['pay_number'];
            $data_statistics['pay_money_total']        += $data['pay_money'];
        }
        
        return compact('data_list', 'data_statistics');
    }
    
    /**
     * 每日汇总记录
     */
    public function getMeverydayList($param = [])
    {
        
        $role_map = [];
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $conference_info = $this->modelWgConference->getInfo(['member_id' => MEMBER_ID]);
            
            $role_map['conference_id'] = $conference_info['id'];
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_employee'))) { $role_map['c_member_id'] = MEMBER_ID; }
        
        if(check_group(MEMBER_ID, config('auth_group_id_agency'))) {
            
            $conference_list = $this->modelWgConference->getList(['source_member_id' => MEMBER_ID], 'id', '', false);
            
            $conference_list_ids = array_extract($conference_list);
            
            !empty($conference_list_ids) ? $role_map['conference_id'] = ['in', $conference_list_ids] : $role_map['conference_id'] = -1;
        }
        
        if (!empty($param['begin_date']) && !empty($param['end_date'])) {
            
            $range_date_arr = get_date_from_range($param['begin_date'] , $param['end_date']);
            
             krsort($range_date_arr);
        } else {
            
            $range_date_arr = get_date_from_range(date("Y-m-d") , date("Y-m-d"));
        }
        
        if(count($range_date_arr) > 31) {
            
            return '日期跨度不能超过1个月哦~';
        }
        
        $data_list = [];
        
        $data_statistics = [];
        
        $data_statistics['register_number_total'] = 0;
        $data_statistics['register_ip_total']     = 0;
        $data_statistics['pay_number_total']      = 0;
        $data_statistics['pay_money_total']       = 0.00;
        $data_statistics['download_total']        = 0;
        
        foreach ($range_date_arr as $date)
        {
            
            $map = $role_map;
            $map['status']      = DATA_NORMAL;
            $map['create_date'] = $date;
            
            $data['download_number'] = Db::name('mg_download_log')->where($map)->count('id');
            
            $map['type']        = 1;
            
            $register_number = $this->modelWgPlayer->stat($map);
            
            $register_ip_number = Db::name('wg_player')->where($map)->group('login_ip')->count('id');
            
            $map['pay_status']      = DATA_NORMAL;
            $map['order_status']    = DATA_NORMAL;
            $map['is_admin']        = DATA_DISABLE;
            
            $pay_number = Db::name('wg_order')->where($map)->group('member_id')->count('id');
            
            $pay_money = Db::name('wg_order')->where($map)->sum('order_money');
            
            empty($pay_money) && $pay_money = 0.00;
            
            $data['date']                   = $date;
            $data['register_number']        = $register_number;
            $data['register_ip_number']     = $register_ip_number;
            $data['pay_number']             = $pay_number;
            $data['pay_money']              = $pay_money;
            
            $data_list[] = $data;
            
            $data_statistics['register_number_total']  += $data['register_number'];
            $data_statistics['register_ip_total']      += $data['register_ip_number'];
            $data_statistics['pay_number_total']       += $data['pay_number'];
            $data_statistics['pay_money_total']        += $data['pay_money'];
            $data_statistics['download_total']         += $data['download_number'];
        }
        
        return compact('data_list', 'data_statistics');
    }
    
    /**
     * 游戏汇总记录
     */
    public function getGameList($param = [])
    {
        
        $map = [];
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $conference_info = $this->modelWgConference->getInfo(['member_id' => MEMBER_ID]);
            
            $map['conference_id'] = $conference_info['id'];
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_agency'))) {
            
            $conference_list = $this->modelWgConference->getList(['source_member_id' => MEMBER_ID], 'id', '', false);
            
            $conference_list_ids = array_extract($conference_list);
            
            !empty($conference_list_ids) ? $role_map['conference_id'] = ['in', $conference_list_ids] : $role_map['conference_id'] = -1;
        }
        
        if (!empty($param['begin_date']) && !empty($param['end_date'])) {
            
            $range_date_arr = get_date_from_range($param['begin_date'] , $param['end_date']);
        } else {
            
            $range_date_arr = get_date_from_range(date("Y-m-d") , date("Y-m-d"));
        }
        
        $map['create_date'] = ['in', $range_date_arr];
        
        $game_list = $this->modelWgGame->getList([], 'id,game_name', 'sort desc');
        
        foreach ($game_list as &$info)
        {
            
            $g_map = array_merge([], $map);
            
            $g_map['game_id']   = $info['id'];
            $g_map['type']      = 0;
            
            $register_number = $this->modelWgPlayer->stat($g_map);
            
            $register_ip_number = Db::name('wg_player')->where($g_map)->group('login_ip')->count('id');
            
            $g_map['pay_status']      = DATA_NORMAL;
            $g_map['order_status']    = DATA_NORMAL;
            $g_map['is_admin']        = DATA_DISABLE;
            
            $pay_number = Db::name('wg_order')->where($g_map)->group('member_id')->count('id');
            
            $pay_money = Db::name('wg_order')->where($g_map)->sum('order_money');
            
            empty($pay_money) && $pay_money = 0.00;
            
            $info['register_number']        = $register_number;
            $info['register_ip_number']     = $register_ip_number;
            $info['pay_number']             = $pay_number;
            $info['pay_money']              = $pay_money;
        }
        
        return $game_list;
    }
    
    
    /**
     * 游戏汇总记录
     */
    public function getMgameList($param = [])
    {
        
        $map = [];
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $conference_info = $this->modelWgConference->getInfo(['member_id' => MEMBER_ID]);
            
            $map['conference_id'] = $conference_info['id'];
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_agency'))) {
            
            $conference_list = $this->modelWgConference->getList(['source_member_id' => MEMBER_ID], 'id', '', false);
            
            $conference_list_ids = array_extract($conference_list);
            
            !empty($conference_list_ids) ? $role_map['conference_id'] = ['in', $conference_list_ids] : $role_map['conference_id'] = -1;
        }
        
        if (!empty($param['begin_date']) && !empty($param['end_date'])) {
            
            $range_date_arr = get_date_from_range($param['begin_date'] , $param['end_date']);
        } else {
            
            $range_date_arr = get_date_from_range(date("Y-m-d") , date("Y-m-d"));
        }
        
        $map['create_date'] = ['in', $range_date_arr];
        
        $game_list = $this->modelMgGame->getList([], 'id,game_name', 'sort desc');
        
        foreach ($game_list as &$info)
        {
            
            $g_map = array_merge([], $map);
            
            $g_map['game_id']   = $info['id'];
            
            $download_number = Db::name('mg_download_log')->where($g_map)->count('id');
            
            $g_map['type']      = 1;
            
            $register_number = $this->modelWgPlayer->stat($g_map);
            
            $register_ip_number = Db::name('wg_player')->where($g_map)->group('login_ip')->count('id');
            
            $g_map['pay_status']      = DATA_NORMAL;
            $g_map['order_status']    = DATA_NORMAL;
            $g_map['is_admin']        = DATA_DISABLE;
            
            $pay_number = Db::name('wg_order')->where($g_map)->group('member_id')->count('id');
            
            $pay_money = Db::name('wg_order')->where($g_map)->sum('order_money');
            
            empty($pay_money) && $pay_money = 0.00;
            
            $info['register_number']        = $register_number;
            $info['register_ip_number']     = $register_ip_number;
            $info['pay_number']             = $pay_number;
            $info['pay_money']              = $pay_money;
            $info['download_number']        = $download_number;
        }
        
        return $game_list;
    }
    
    /**
     * 区服汇总记录
     */
    public function getServerList($param = [])
    {
        
        $map = [];
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $conference_info = $this->modelWgConference->getInfo(['member_id' => MEMBER_ID]);
            
            $map['conference_id'] = $conference_info['id'];
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_agency'))) {
            
            $conference_list = $this->modelWgConference->getList(['source_member_id' => MEMBER_ID], 'id', '', false);
            
            $conference_list_ids = array_extract($conference_list);
            
            !empty($conference_list_ids) ? $role_map['conference_id'] = ['in', $conference_list_ids] : $role_map['conference_id'] = -1;
        }
        
        if (!empty($param['begin_date']) && !empty($param['end_date'])) {
            
            $range_date_arr = get_date_from_range($param['begin_date'] , $param['end_date']);
        } else {
            
            $range_date_arr = get_date_from_range(date("Y-m-d") , date("Y-m-d"));
        }
        
        $map['create_date'] = ['in', $range_date_arr];
        $map['game_id']     = $param['game_id'];
        
        $server_list = $this->modelWgServer->getList(['status' => DATA_NORMAL], 'id,server_name', 'id desc');
        
        foreach ($server_list as &$info)
        {
            
            $g_map = array_merge([], $map);
            
            $g_map['server_id'] = $info['id'];
            
            $register_number = $this->modelWgPlayer->stat($g_map);
            
            $register_ip_number = Db::name('wg_player')->where($g_map)->group('login_ip')->count('id');
            
            $g_map['pay_status']      = DATA_NORMAL;
            $g_map['order_status']    = DATA_NORMAL;
            $g_map['is_admin']        = DATA_DISABLE;
            
            $pay_number = Db::name('wg_order')->where($g_map)->group('member_id')->count('id');
            
            $pay_money = Db::name('wg_order')->where($g_map)->sum('order_money');
            
            empty($pay_money) && $pay_money = 0.00;
            
            $info['register_number']        = $register_number;
            $info['register_ip_number']     = $register_ip_number;
            $info['pay_number']             = $pay_number;
            $info['pay_money']              = $pay_money;
        }
        
        return $server_list;
    }
    
    /**
     * 员工统计
     */
    public function getEmployeeList($param = [])
    {
        
        $map = [];
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $conference_info = $this->modelWgConference->getInfo(['member_id' => MEMBER_ID]);
            
            $map['conference_id'] = $conference_info['id'];
        } else {
            
            $map['conference_id'] = ['neq', 0];
        }
        
        $s_map = $map;
        
        if (!empty($param['begin_date']) && !empty($param['end_date'])) {
            
            $range_date_arr = get_date_from_range($param['begin_date'] , $param['end_date']);
        } else {
            
            $range_date_arr = get_date_from_range(date("Y-m-d") , date("Y-m-d"));
        }
        
        $date_map['create_date'] = ['in', $range_date_arr];
        
        $this->modelWgConferenceMember->alias('cm');
        
        $join = [
                    [SYS_DB_PREFIX . 'member m', 'cm.member_id = m.id'],
                ];
        
        $field = 'cm.*,m.username,m.id';
        
        $order = 'cm.create_time desc';
        
        $data_list = $this->modelWgConferenceMember->getList($map, $field, $order, DB_LIST_ROWS, $join);
        
        foreach ($data_list as &$info)
        {
           
            $date_map['c_member_id'] = $info['member_id'];
            
            $g_map = array_merge($date_map, $map);
            
            $g_map['type'] = 0;
            
            $register_number = $this->modelWgPlayer->stat($g_map);
            
            $register_ip_number = Db::name('wg_player')->where($g_map)->group('login_ip')->count('id');
            
            $g_map['pay_status']      = DATA_NORMAL;
            $g_map['order_status']    = DATA_NORMAL;
            $g_map['is_admin']        = DATA_DISABLE;
            
            $pay_number = Db::name('wg_order')->where($g_map)->group('member_id')->count('id');
            
            $pay_money = Db::name('wg_order')->where($g_map)->sum('order_money');
            
            empty($pay_money) && $pay_money = 0.00;
            
            $info['register_number']        = $register_number;
            $info['register_ip_number']     = $register_ip_number;
            $info['pay_number']             = $pay_number;
            $info['pay_money']              = $pay_money;
        }
        
        $data_statistics = [];
        
        $s_map['create_date'] = ['in', $range_date_arr];
        
        $s_map['type']  = 0;
        
        $data_statistics['register_number_total'] = $this->modelWgPlayer->stat($s_map);
        $data_statistics['register_ip_total']     = Db::name('wg_player')->where($s_map)->group('login_ip')->count('id');
        
        $s_map['pay_status']      = DATA_NORMAL;
        $s_map['order_status']    = DATA_NORMAL;
        $s_map['is_admin']        = DATA_DISABLE;
            
        $data_statistics['pay_number_total']      = Db::name('wg_order')->where($s_map)->group('member_id')->count('id');
        
        $pay_money = Db::name('wg_order')->where($s_map)->sum('order_money');

        empty($pay_money) && $pay_money = 0.00;
        
        $data_statistics['pay_money_total']       = $pay_money;
        
        return compact('data_list', 'data_statistics');
    }
    
    /**
     * 员工统计
     */
    public function getMemployeeList($param = [])
    {
        
        $map = [];
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $conference_info = $this->modelWgConference->getInfo(['member_id' => MEMBER_ID]);
            
            $map['conference_id'] = $conference_info['id'];
        } else {
            
            $map['conference_id'] = ['neq', 0];
        }
        
        $s_map = $map;
        
        if (!empty($param['begin_date']) && !empty($param['end_date'])) {
            
            $range_date_arr = get_date_from_range($param['begin_date'] , $param['end_date']);
        } else {
            
            $range_date_arr = get_date_from_range(date("Y-m-d") , date("Y-m-d"));
        }
        
        $date_map['create_date'] = ['in', $range_date_arr];
        
        $this->modelWgConferenceMember->alias('cm');
        
        $join = [
                    [SYS_DB_PREFIX . 'member m', 'cm.member_id = m.id'],
                ];
        
        $field = 'cm.*,m.username,m.id';
        
        $order = 'cm.create_time desc';
        
        $data_list = $this->modelWgConferenceMember->getList($map, $field, $order, DB_LIST_ROWS, $join);
        
        foreach ($data_list as &$info)
        {
           
            $date_map['c_member_id'] = $info['member_id'];
            
            $g_map = array_merge($date_map, $map);
            
            $download_number = Db::name('mg_download_log')->where($g_map)->count('id');
            
            $g_map['type'] = 1;
            
            $register_number = $this->modelWgPlayer->stat($g_map);
            
            $register_ip_number = Db::name('wg_player')->where($g_map)->group('login_ip')->count('id');
            
            $g_map['pay_status']      = DATA_NORMAL;
            $g_map['order_status']    = DATA_NORMAL;
            $g_map['is_admin']        = DATA_DISABLE;
            
            $pay_number = Db::name('wg_order')->where($g_map)->group('member_id')->count('id');
            
            $pay_money = Db::name('wg_order')->where($g_map)->sum('order_money');
            
            empty($pay_money) && $pay_money = 0.00;
            
            $info['register_number']        = $register_number;
            $info['register_ip_number']     = $register_ip_number;
            $info['pay_number']             = $pay_number;
            $info['pay_money']              = $pay_money;
            $info['download_number']        = $download_number;
        }
        
        $data_statistics = [];
        
        $s_map['create_date'] = ['in', $range_date_arr];
        
        $data_statistics['download_total'] = Db::name('mg_download_log')->where($s_map)->count('id');
        
        $s_map['type']  = 1;
        
        $data_statistics['register_number_total'] = $this->modelWgPlayer->stat($s_map);
        $data_statistics['register_ip_total']     = Db::name('wg_player')->where($s_map)->group('login_ip')->count('id');
        
        $s_map['pay_status']      = DATA_NORMAL;
        $s_map['order_status']    = DATA_NORMAL;
        $s_map['is_admin']        = DATA_DISABLE;
            
        $data_statistics['pay_number_total']      = Db::name('wg_order')->where($s_map)->group('member_id')->count('id');
        
        $pay_money = Db::name('wg_order')->where($s_map)->sum('order_money');

        empty($pay_money) && $pay_money = 0.00;
        
        $data_statistics['pay_money_total']       = $pay_money;
        
        return compact('data_list', 'data_statistics');
    }
    
    /**
     * 公会统计
     */
    public function getConferenceList($param = [])
    {
        
        $map = [];
        $c_map = [];
        
        if(check_group(MEMBER_ID, config('auth_group_id_agency'))) {
            
            $conference_list = $this->modelWgConference->getList(['source_member_id' => MEMBER_ID], 'id', '', false);
            
            $conference_list_ids = array_extract($conference_list);
            
            !empty($conference_list_ids) ? $c_map['c.id'] = ['in', $conference_list_ids] : $c_map['c.id'] = -1;
        }
        
        if (!empty($param['begin_date']) && !empty($param['end_date'])) {
            
            $range_date_arr = get_date_from_range($param['begin_date'] , $param['end_date']);
        } else {
            
            $range_date_arr = get_date_from_range(date("Y-m-d") , date("Y-m-d"));
        }
        
        $date_map['create_date'] = ['in', $range_date_arr];
        
        $this->modelWgConference->alias('c');
        
        $join = [
                    [SYS_DB_PREFIX . 'wg_order o', 'o.conference_id = c.id'],
                ];
        
        $c_map['o.create_date'] = ['in', $range_date_arr];
        
        $data_list = $this->modelWgConference->getList($c_map, "c.*,o.create_date,sum(order_money) as group_order_money", 'group_order_money desc', DB_LIST_ROWS, $join, 'o.conference_id');
        
        foreach ($data_list as &$info)
        {
           
            $date_map['conference_id'] = $info['id'];
            
            $g_map = array_merge($date_map, $map);
            
            $g_map['type'] = 0;
            
            $register_number = $this->modelWgPlayer->stat($g_map);
            
            $register_ip_number = Db::name('wg_player')->where($g_map)->group('login_ip')->count('id');
            
            $g_map['pay_status']      = DATA_NORMAL;
            $g_map['order_status']    = DATA_NORMAL;
            $g_map['is_admin']        = DATA_DISABLE;
            
            $pay_number = Db::name('wg_order')->where($g_map)->group('member_id')->count('id');
            
            $pay_money = Db::name('wg_order')->where($g_map)->sum('order_money');
            
            empty($pay_money) && $pay_money = 0.00;
            
            $info['register_number']        = $register_number;
            $info['register_ip_number']     = $register_ip_number;
            $info['pay_number']             = $pay_number;
            $info['pay_money']              = $pay_money;
        }
        
        $data_statistics = [];
        
        $s_map['create_date']   = ['in', $range_date_arr];
        $s_map['conference_id'] = ['neq', 0];
        $s_map['type']          = 0;
        
        if(check_group(MEMBER_ID, config('auth_group_id_agency'))) {
            
            $conference_list = $this->modelWgConference->getList(['source_member_id' => MEMBER_ID], 'id', '', false);
            
            $conference_list_ids = array_extract($conference_list);
            
            !empty($conference_list_ids) ? $s_map['conference_id'] = ['in', $conference_list_ids] : $s_map['conference_id'] = -1;
        }
        
        $data_statistics['register_number_total'] = $this->modelWgPlayer->stat($s_map);
        $data_statistics['register_ip_total']     = Db::name('wg_player')->where($s_map)->group('login_ip')->count('id');
        
        $s_map['pay_status']      = DATA_NORMAL;
        $s_map['order_status']    = DATA_NORMAL;
        $s_map['is_admin']        = DATA_DISABLE;
            
        $data_statistics['pay_number_total']      = Db::name('wg_order')->where($s_map)->group('member_id')->count('id');
        
        $pay_money = Db::name('wg_order')->where($s_map)->sum('order_money');

        empty($pay_money) && $pay_money = 0.00;
        
        $data_statistics['pay_money_total']       = $pay_money;
        
        return compact('data_list', 'data_statistics');
    }
    
    /**
     * 公会统计
     */
    public function getMconferenceList($param = [])
    {
        
        $map = [];
        $c_map = [];
        
        if(check_group(MEMBER_ID, config('auth_group_id_agency'))) {
            
            $conference_list = $this->modelWgConference->getList(['source_member_id' => MEMBER_ID], 'id', '', false);
            
            $conference_list_ids = array_extract($conference_list);
            
            !empty($conference_list_ids) ? $c_map['c.id'] = ['in', $conference_list_ids] : $c_map['c.id'] = -1;
        }
        
        if (!empty($param['begin_date']) && !empty($param['end_date'])) {
            
            $range_date_arr = get_date_from_range($param['begin_date'] , $param['end_date']);
        } else {
            
            $range_date_arr = get_date_from_range(date("Y-m-d") , date("Y-m-d"));
        }
        
        $date_map['create_date'] = ['in', $range_date_arr];
        
        $this->modelWgConference->alias('c');
        
        $join = [
                    [SYS_DB_PREFIX . 'wg_order o', 'o.conference_id = c.id'],
                ];
        
        $c_map['o.create_date'] = ['in', $range_date_arr];
        
        $data_list = $this->modelWgConference->getList($c_map, "c.*,o.create_date,sum(order_money) as group_order_money", 'group_order_money desc', DB_LIST_ROWS, $join, 'o.conference_id');
        
        foreach ($data_list as &$info)
        {
           
            $date_map['conference_id'] = $info['id'];
            
            $g_map = array_merge($date_map, $map);
            
            $download_number = Db::name('mg_download_log')->where($g_map)->count('id');
            
            $g_map['type'] = 1;
            
            $register_number = $this->modelWgPlayer->stat($g_map);
            
            $register_ip_number = Db::name('wg_player')->where($g_map)->group('login_ip')->count('id');
            
            $g_map['pay_status']      = DATA_NORMAL;
            $g_map['order_status']    = DATA_NORMAL;
            $g_map['is_admin']        = DATA_DISABLE;
            
            $pay_number = Db::name('wg_order')->where($g_map)->group('member_id')->count('id');
            
            $pay_money = Db::name('wg_order')->where($g_map)->sum('order_money');
            
            empty($pay_money) && $pay_money = 0.00;
            
            $info['register_number']        = $register_number;
            $info['register_ip_number']     = $register_ip_number;
            $info['pay_number']             = $pay_number;
            $info['pay_money']              = $pay_money;
            $info['download_number']        = $download_number;
        }
        
        $data_statistics = [];
        
        $s_map['create_date']   = ['in', $range_date_arr];
        $s_map['conference_id'] = ['neq', 0];
        
        $data_statistics['download_total'] = Db::name('mg_download_log')->where($s_map)->count('id');
        
        $s_map['type']          = 1;
        
        if(check_group(MEMBER_ID, config('auth_group_id_agency'))) {
            
            $conference_list = $this->modelWgConference->getList(['source_member_id' => MEMBER_ID], 'id', '', false);
            
            $conference_list_ids = array_extract($conference_list);
            
            !empty($conference_list_ids) ? $s_map['conference_id'] = ['in', $conference_list_ids] : $s_map['conference_id'] = -1;
        }
        
        $data_statistics['register_number_total'] = $this->modelWgPlayer->stat($s_map);
        $data_statistics['register_ip_total']     = Db::name('wg_player')->where($s_map)->group('login_ip')->count('id');
        
        $s_map['pay_status']      = DATA_NORMAL;
        $s_map['order_status']    = DATA_NORMAL;
        $s_map['is_admin']        = DATA_DISABLE;
            
        $data_statistics['pay_number_total']      = Db::name('wg_order')->where($s_map)->group('member_id')->count('id');
        
        $pay_money = Db::name('wg_order')->where($s_map)->sum('order_money');

        empty($pay_money) && $pay_money = 0.00;
        
        $data_statistics['pay_money_total']       = $pay_money;
        
        return compact('data_list', 'data_statistics');
    }
}
