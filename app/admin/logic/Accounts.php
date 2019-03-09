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
 * 账目逻辑
 */
class Accounts extends AdminBase
{
    
    /**
     * 订单列表
     */
    public function getOrderList($param = [])
    {
        
        $map['status']          = $scope_map['wo.status']   =  DATA_NORMAL;
        $map['is_admin']        = $scope_map['wo.is_admin'] = DATA_DISABLE;
        
        $map['pay_status']      = $scope_map['wo.pay_status'] = DATA_NORMAL;
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $conference_info = $this->modelWgConference->getInfo(['member_id' => MEMBER_ID]);
            
            $map['conference_id'] = $scope_map['wo.conference_id'] = $conference_info['id'];
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_employee'))) {
            
            $map['c_member_id'] = $scope_map['wo.c_member_id'] = MEMBER_ID;
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_agency'))) {
            
            $conference_list = $this->modelWgConference->getList(['source_member_id' => MEMBER_ID], 'id', '', false);
            
            $conference_list_ids = array_extract($conference_list);
            
            !empty($conference_list_ids) ? $map['conference_id'] = $scope_map['wo.conference_id'] = ['in', $conference_list_ids] : $map['conference_id'] = -1;
        }
        
        if (!empty($param['search_data'])) {
            
            if ($param['search_type'] == 'role') {
                
                    $role_map['role_name'] = ['like', (string)$param['search_data'] .'%'];

                    $role_list = $this->modelWgRole->getList($role_map, true, '', false);

                    if (!empty($role_list)) {

                        $role_id_list = array_extract($role_list, 'role_id');

                        !empty($role_id_list) && $map['role_id'] = $scope_map['wo.role_id'] = ['in', $role_id_list];
                    } else {
                        $map['role_id'] = $scope_map['wo.role_id'] = -1;
                    }
            }
            
            if ($param['search_type'] == 'username') {
                
                $m_map['username'] = $param['search_data'];
                
                $member_list = $this->modelMember->getList($m_map, 'id', '', false);
                
                if (!empty($member_list)) {
                    $member_id_list = array_extract($member_list, 'id');
                    !empty($member_id_list) && $map['member_id'] = $scope_map['wo.member_id'] = ['in', $member_id_list];
                } else {
                    $map['member_id'] = $scope_map['wo.member_id'] = -1;
                }
            }
            
            if ($param['search_type'] == 'order_sn') {
                
                $map['order_sn'] = $scope_map['wo.order_sn'] = $param['search_data'];
            }
            
            if ($param['search_type'] == 'ip') {
                
                $map['ip'] = $scope_map['wo.ip'] = $param['search_data'];
            }
            
            if ($param['search_type'] == 'employee') {
                
                $employee_map['username'] = $param['search_data'];
                
                $employee_info = $this->modelMember->getInfo($employee_map, 'id');
                
                $bind_member_list = $this->modelWgBind->getList(['employee_id' => $employee_info['id'], 'is_check' => DATA_NORMAL], 'member_id', '', false);
                
                if (!empty($bind_member_list)) {
                    
                    $member_ids = array_extract($bind_member_list, 'member_id');
                    
                    !empty($member_ids) ? $map['member_id'] = ['in', $member_ids] : $map['member_id'] = -1;
                }
            }
        }
        
        if (!empty($param['o_scope']) && $param['o_scope'] == 'nb') {
            
            $map['c_member_id'] = $scope_map['wo.c_member_id'] = DATA_DISABLE;
        }
        
        if (!empty($param['o_scope']) && $param['o_scope'] == 'nr') {
            
            $scope_map['wr.role_id']  = array('exp',' IS NULL');
            
            $scope_list  = Db::name('wg_order')
                        ->alias('wo')
                        ->join('wg_role wr','wo.role_id = wr.role_id','left')
                        ->where($scope_map)
                        ->field('wo.id,wr.role_id,wo.role_id as old_role_id')
                        ->group('old_role_id')
                        ->select();
            
            if (empty($scope_list)) {
                
                $map['role_id'] = -1;
            } else {
                
                $map['role_id'] = ['in', array_extract($scope_list, 'old_role_id')];
            }
        }
        
        if (!empty($param['o_scope']) && $param['o_scope'] == 'wf') {
            
            $map['pay_status'] = $scope_map['wo.pay_status'] = DATA_DISABLE;
        }
        
        if (!empty($param['game_id']))   { $map['game_id']   = $param['game_id'];   }
        if (!empty($param['server_id'])) { $map['server_id'] = $param['server_id']; }
        
        
        if (!empty($param['begin_date']) && !empty($param['end_date'])) {
            
            $range_date_arr = get_date_from_range($param['begin_date'] , $param['end_date']);
            
            $map['create_date'] = ['in', $range_date_arr];
        }
        
        
        $game_list      = $this->modelWgGame->getList(['status' => DATA_NORMAL], 'id,game_name', '', false);
        $server_list    = $this->modelWgServer->getList(['status' => DATA_NORMAL], 'id,server_name', '', false);
        
        $game_list_to_id_key    = array_extract_map($game_list);
        $server_list_to_id_key  = array_extract_map($server_list);
        
        $count = Db::name('wg_order')->where($map)->count('id');
        
        $order = 'id desc';
        
        if (!empty($param['order_field'])) {
            
            $order = empty($param['order_val']) ? $param['order_field'] . ' asc' : $param['order_field'] . ' desc';
        }
        
        $id_list = Db::name('wg_order')->where($map)->field('id')->order($order)->paginate(DB_LIST_ROWS, $count, ['query' => request()->param()]);
        
        $ids = array_extract($id_list);
        
        $list = [];
        
        $total_money = 0.00;
        
        if (!empty($ids)) {
            
            $o_where['id'] = ['in', $ids];
            
            $field = "id,order_sn,pay_name,pay_time,pay_status,member_id,role_id,order_money,order_status,create_time,conference_id,c_member_id,game_id,server_id,create_date,ip";
            
            $list = Db::name('wg_order')->where($o_where)->field($field)->order($order)->select();
            
            $total_money = Db::name('wg_order')->where($map)->sum('order_money');
            
            foreach ($list as &$v)
            {
                $v['create_time']       = format_time($v['create_time']);
                $v['username']          = get_username($v['member_id']);
                $v['c_username']        = get_username($v['c_member_id']);
                $v['conference_name']   = get_conference_name($v['conference_id']);
                $v['role_name']         = get_role_name($v['role_id']);
                $v['game_name']         = $game_list_to_id_key[$v['game_id']]['game_name'];
                
                $server_name = "已关服";

                !empty($server_list_to_id_key[$v['server_id']]['server_name']) && $server_name = $server_list_to_id_key[$v['server_id']]['server_name'];
                
                $v['server_name']       = $server_name;
            }
        
        }
        
        return ['render' => $id_list, 'list' => $list, 'total_money' => $total_money];
    }
    
    
    /**
     * 充值排行榜
     */
    public function getPayToppingList($param = [])
    {
        
        $map['status']          = DATA_NORMAL;
        $map['is_admin']        = DATA_DISABLE;
        
        $map['pay_status']      = DATA_NORMAL;
        $map['order_status']    = DATA_NORMAL;
        $map['is_admin']        = DATA_DISABLE;
        
        if (!empty($param['game_id']))   { $map['game_id']   = $param['game_id'];   }
        if (!empty($param['server_id'])) { $map['server_id'] = $param['server_id']; }
        
        
        if (!empty($param['begin_date']) && !empty($param['end_date'])) {
            
            $range_date_arr = get_date_from_range($param['begin_date'] , $param['end_date']);
            
            $map['create_date'] = ['in', $range_date_arr];
        }
        
        $group_by = 'role_id';
        
        if (!empty($param['game_id']) && empty($param['server_id'])) {
            
            $map['game_id'] = $param['game_id'];
        }
        
        if (!empty($param['game_id']) && !empty($param['server_id'])) {
            
            $map['server_id'] = $param['server_id'];
        }
        
        $game_list      = $this->modelWgGame->getList(['status' => DATA_NORMAL], 'id,game_name', '', false);
        $server_list    = $this->modelWgServer->getList(['status' => DATA_NORMAL], 'id,server_name', '', false);
        
        $game_list_to_id_key    = array_extract_map($game_list);
        $server_list_to_id_key  = array_extract_map($server_list);
        
        $field = 'id,order_sn,pay_name,pay_time,pay_status,member_id,role_id,order_money,order_status,create_time,conference_id,c_member_id,game_id,server_id,create_date,ip,sum(order_money) as group_order_money';
        
        $order = 'group_order_money desc';
        
        $data_list = $this->modelWgOrder->getList($map, $field, $order, DB_LIST_ROWS, [], $group_by);
        
        foreach ($data_list as &$v)
        {
            
            $v['username']          = get_username($v['member_id']);
            $v['c_username']        = get_username($v['c_member_id']);
            $v['conference_name']   = get_conference_name($v['conference_id']);
            $v['role_name']         = get_role_name($v['role_id']);
            $v['game_name']         = $game_list_to_id_key[$v['game_id']]['game_name'];
            
            $server_name = "已关服";
            
            !empty($server_list_to_id_key[$v['server_id']]['server_name']) && $server_name = $server_list_to_id_key[$v['server_id']]['server_name'];
            
            $v['server_name']       = $server_name;
        }
        
        return $data_list;
    }
    
    
    /**
     * 公会结算
     */
    public function getConferenceAccounts($param = [])
    {
        
        $map = [];
        $c_map = [];
        
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
            
            $g_map['pay_status']      = DATA_NORMAL;
            $g_map['order_status']    = DATA_NORMAL;
            $g_map['is_admin']        = DATA_DISABLE;
            
            $pay_money = Db::name('wg_order')->where($g_map)->sum('order_money');
            
            empty($pay_money) && $pay_money = 0.00;
            
            $info['pay_money']  = $pay_money;
        }
        
        $data_statistics = [];
        
        $s_map['create_date']   = ['in', $range_date_arr];
        $s_map['conference_id'] = ['neq', 0];
        
        $s_map['pay_status']      = DATA_NORMAL;
        $s_map['order_status']    = DATA_NORMAL;
        $s_map['is_admin']        = DATA_DISABLE;
            
        $pay_money = Db::name('wg_order')->where($s_map)->sum('order_money');

        empty($pay_money) && $pay_money = 0.00;
        
        $data_statistics['pay_money_total']       = $pay_money;
        
        return compact('data_list', 'data_statistics');
    }
    
    
    /**
     * 导出公会结算
     */
    public function exportConferenceAccounts($param = [])
    {
        
        $map = [];
        $c_map = [];
        
        if (!empty($param['begin_date']) && !empty($param['end_date'])) {
            
            $range_date_arr = get_date_from_range($param['begin_date'] , $param['end_date']);
            
            $file_name = "公会结算明细_".$param['begin_date'].'_'.$param['end_date'];
        } else {
            
            $range_date_arr = get_date_from_range(date("Y-m-d") , date("Y-m-d"));
            
            $file_name = "公会结算明细_".$range_date_arr[0];
        }
        
        $date_map['create_date'] = ['in', $range_date_arr];
        
        $this->modelWgConference->alias('c');
        
        $join = [
                    [SYS_DB_PREFIX . 'wg_order o', 'o.conference_id = c.id'],
                ];
        
        $c_map['o.create_date'] = ['in', $range_date_arr];
        
        $data_list = $this->modelWgConference->getList($c_map, "c.*,o.create_date,sum(order_money) as group_order_money", 'group_order_money desc', false, $join, 'o.conference_id');
        
        foreach ($data_list as &$info)
        {
           
            $date_map['conference_id'] = $info['id'];
            
            $g_map = array_merge($date_map, $map);
            
            $g_map['pay_status']      = DATA_NORMAL;
            $g_map['order_status']    = DATA_NORMAL;
            $g_map['is_admin']        = DATA_DISABLE;
            
            $pay_money = Db::name('wg_order')->where($g_map)->sum('order_money');
            
            empty($pay_money) && $pay_money = 0.00;
            
            $info['pay_money']  = $pay_money;
            
            $info['dividend_money']  = $pay_money/100*$info['ratio'];
            $info['bank_account']    = $info['bank_account'].' ';
            $info['ratio']           = $info['ratio'].'%';
        }
        
        $titles = "公会,充值金额,分成比例,分成金额,开户人,开户行,账号";
        
        $keys   = "conference_name,pay_money,ratio,dividend_money,account_holder,opening_bank,bank_account";
        
        action_log('导出', $file_name);
        
        export_excel($titles, $keys, $data_list, $file_name);
    }
    
    /**
     * 补充订单
     */
    public function replenishOrder($param = [])
    {
        
        $map['pay_status']   = DATA_NORMAL;
        $map['order_status'] = DATA_DISABLE;
        $map['id']           = $param['id'];
        
        $order_info = $this->modelWgOrder->getInfo($map);
        
        if (empty($order_info)) {
            
            return [RESULT_ERROR, '订单不符合补单规则'];
        }
        
        $game_info   = $this->modelWgGame->getInfo(['id' => $order_info['game_id']]);
        $server_info = $this->modelWgServer->getInfo(['id' => $order_info['server_id']]);

        $order_info['cp_server_id'] = $server_info['cp_server_id'];

        $select_driver = SYS_DRIVER_DIR_NAME . $game_info['game_code'];

        $api_result = $this->serviceWebgame->$select_driver->pay($order_info);

        if ($api_result) {
            
            $bd_result = $this->modelWgOrder->updateInfo(['id' => $param['id'], 'order_status' => DATA_DISABLE], ['order_status' => DATA_NORMAL]);
            
            action_log('补单', '补单成功，订单号：' . $order_info['order_sn']);
            
            return $bd_result ? [RESULT_SUCCESS, '补单成功', url('accounts/orderList')] : [RESULT_ERROR, '补单失败'];
        } else {
            
            action_log('补单', '补单异常，订单号：' . $order_info['order_sn']);
            
            return [RESULT_ERROR, '补单接口异常'];
        }
    }
}
