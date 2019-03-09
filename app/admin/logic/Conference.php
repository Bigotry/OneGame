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
 * 公会相关逻辑
 */
class Conference extends AdminBase
{
    
    /**
     * 获取公会列表
     */
    public function getConferenceList($where = [], $field = 'c.*,mm.nickname as m_nickname,ms.nickname as s_nickname,mm.username', $order = 'c.create_time desc', $paginate = 0)
    {
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $where['c.member_id'] = MEMBER_ID;
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_employee'))) {
            
            $c_list = $this->modelWgConferenceMember->getList(['member_id' => MEMBER_ID], 'conference_id', '', false);
            
            if (!empty($c_list)) {
                
                $conference_ids = array_extract($c_list, 'conference_id');
                
                !empty($conference_ids) && $where['c.id'] = ['in', $conference_ids];
                
            } else {
                $where['c.id'] = -1;
            }
        }

        
        $this->modelWgConference->alias('c');
        
        $join = [
                    [SYS_DB_PREFIX . 'member mm', 'mm.id = c.member_id'],
                    [SYS_DB_PREFIX . 'member ms', 'ms.id = c.source_member_id', 'left'],
                ];
        
        $where['c.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
        
        return $this->modelWgConference->getList($where, $field, $order, $paginate, $join);
    }
    
    /**
     * 导出公会列表
     */
    public function getExportConferenceList()
    {
        
        $list = $this->modelWgConference->getList([], true, 'id desc', false);
        
        foreach ($list as &$v)
        {
            $v['contact_mobile'] .= ' ';
            $v['qq'] .= ' ';
            $v['bank_account'] .= ' ';
        }
        
        $titles = "公会名称,联系人,联系电话,QQ,开户人,开户行,卡号";
        $keys   = "conference_name,contact_name,contact_mobile,qq,account_holder,opening_bank,bank_account";
        
        action_log('导出', '导出公会资料');
        
        export_excel($titles, $keys, $list, 'conference_list');
    }
    
    /**
     * 获取员工列表
     */
    public function getEmployeeList($param = [])
    {
        
        $where = [];
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $conference_info = $this->modelWgConference->getInfo(['member_id' => MEMBER_ID]);
            
            $where['cm.conference_id'] = $conference_info['id'];
        }
        
        !empty($param['search_data']) && $where['m.nickname|m.username'] = ['like', '%'.$param['search_data'].'%'];
        
        $this->modelWgConferenceMember->alias('cm');
        
        $join = [
                    [SYS_DB_PREFIX . 'wg_conference c', 'c.id = cm.conference_id'],
                    [SYS_DB_PREFIX . 'member m', 'm.id = cm.member_id'],
                ];
        
        $where['cm.' . DATA_STATUS_NAME]    = ['neq', DATA_DELETE];
        
        $field = 'c.conference_name,cm.*,m.nickname,m.username,m.mobile';
        
        return $this->modelWgConferenceMember->getList($where, $field, 'cm.create_time desc', DB_LIST_ROWS, $join);
    }
    
    /**
     * 公会添加
     */
    public function conferenceAdd($data = [])
    {
        
        if (!$this->validateConference->scene('add')->check($data)) {
            
            return [RESULT_ERROR, $this->validateConference->getError()];
        }
        
        $data['bank_account'] = preg_replace('/[ ]/', '', $data['bank_account']);
        
        if (!check_bankcard($data['bank_account'])) {
            
            return [RESULT_ERROR, '银行卡格式不正确'];
        }
        
        $func = function () use ($data) {
            
                    $member['nickname']         = $member['username'] = $data['username'];
                    $member['password']         = data_md5_key($data['password']);
                    $member['leader_id']        = MEMBER_ID;
                    $member['is_inside']        = DATA_NORMAL;
                    $member['password_version'] = DATA_NORMAL;
                    
                    $data['member_id']          = $this->modelMember->insertGetId($member);
                    
                    $data['source_member_id']   = DATA_DISABLE;
                    
                    if(check_group(MEMBER_ID, config('auth_group_id_agency'))) {

                        $data['source_member_id']   = MEMBER_ID;
                    }
                    
                    action_log('新增', '新增会员，username：' . $member['username']);
            
                    $this->modelWgConference->setInfo($data) && action_log('新增', '新增公会，conference_name：' . $data['conference_name']);
                    
                    $access_data['member_id'] = $data['member_id'];
                    $access_data['group_id'] = config('auth_group_id_manage');
                    
                    $this->modelAuthGroupAccess->setInfo($access_data);
                };
        
        return closure_list_exe([$func]) ? [RESULT_SUCCESS, '操作成功', url('conferenceList')] : [RESULT_ERROR, '公会添加失败'];
    }
    
    /**
     * 公会编辑
     */
    public function conferenceEdit($data = [])
    {
        
        if (!$this->validateConference->scene('edit')->check($data)) {
            
            return [RESULT_ERROR, $this->validateConference->getError()];
        }
        
        $data['bank_account'] = preg_replace('/[ ]/', '', $data['bank_account']);
        
        if (!check_bankcard($data['bank_account'])) {
            
            return [RESULT_ERROR, '银行卡格式不正确'];
        }
        
        $result = $this->modelWgConference->setInfo($data);
        
        $result && action_log('编辑', '编辑公会，conference_name：' . $data['conference_name']);
            
        return $result ? [RESULT_SUCCESS, '操作成功', url('conferenceList')] : [RESULT_ERROR, $this->modelWgConference->getError()];
    }
    
    /**
     * 获取公会信息
     */
    public function getConferenceInfo($where = [], $field = true)
    {
        
        return $this->modelWgConference->getInfo($where, $field);
    }
    
    /**
     * 获取公会员工信息
     */
    public function getConferenceMemberInfo($where = [], $field = true)
    {
        
        return $this->modelWgConferenceMember->getInfo($where, $field);
    }
    
    /**
     * 员工新增
     */
    public function employeeAdd($data = [])
    {
        
        if (!$this->validateEmployee->scene('add')->check($data)) {
            
            return [RESULT_ERROR, $this->validateEmployee->getError()];
        }
        
        $func = function () use ($data) {
            
                    $member['nickname']         = $member['username'] = $data['username'];
                    $member['password']         = data_md5_key($data['password']);
                    $member['leader_id']        = MEMBER_ID;
                    $member['is_inside']        = DATA_NORMAL;
                    $member['password_version'] = DATA_NORMAL;
                    
                    !empty($data['mobile']) && $member['mobile'] = $data['mobile'];
                    
                    $conference_data['member_id']          = $this->modelMember->insertGetId($member);
                    $conference_data['conference_id']      = $data['conference_id'];
                    
                    action_log('新增', '新增会员，username：' . $member['username']);
                    
                    $this->modelWgConferenceMember->setInfo($conference_data) && action_log('新增', '新增公会员工，username：' . $member['username']);
                    
                    $access_data['member_id'] = $conference_data['member_id'];
                    $access_data['group_id']  = config('auth_group_id_employee');
                    
                    $this->modelAuthGroupAccess->setInfo($access_data);
                };
        
        return closure_list_exe([$func]) ? [RESULT_SUCCESS, '操作成功', url('employeeList')] : [RESULT_ERROR, '员工添加失败'];
    }
    
    /**
     * 员工编辑
     */
    public function employeeEdit($data = [])
    {
        
        $member_data = [];
        
        $cm_info = $this->modelWgConferenceMember->getInfo(['id' => $data['id']]);
        
        if (!empty($data['nickname']))  { $member_data['nickname'] = $data['nickname']; }
        if (!empty($data['mobile']))    { $member_data['mobile']   = $data['mobile']; }
        if (!empty($data['password']))  { $member_data['password'] = data_md5_key($data['password']); $member_data['password_version']  = DATA_NORMAL;}
        
        !empty($member_data) && Db::name('member')->where(['id' => $cm_info['member_id']])->update($member_data);
        
        if ($data['conference_id'] != $cm_info['conference_id'] && !check_group(MEMBER_ID, config('auth_group_id_manage')) && !check_group(MEMBER_ID, config('auth_group_id_employee'))) {
        
            // 事务
            $func = function () use ($data, $cm_info) {
                    
                    $up_data['conference_id'] = $data['conference_id'];
                    
                    Db::name('wg_conference_member')->where(['member_id' => $cm_info['member_id']])->update($up_data);
                    Db::name('wg_bind')->where(['employee_id' => $cm_info['member_id']])->update($up_data);
                    Db::name('wg_code')->where(['member_id' => $cm_info['member_id']])->update($up_data);
                    Db::name('wg_order')->where(['c_member_id' => $cm_info['member_id']])->update($up_data);
                    Db::name('wg_player')->where(['c_member_id' => $cm_info['member_id']])->update($up_data);
                };
                
            $result = closure_list_exe([$func]);
            
            $result ? action_log('事务', '迁移公会事务成功，member_id：' . $cm_info['member_id']) : action_log('事务', '迁移公会事务失败，member_id：' . $cm_info['member_id']);
            
            return $result ? [RESULT_SUCCESS, '操作成功', url('employeeList')] : [RESULT_ERROR, '操作失败'];
        }
        
        action_log('编辑', '员工信息编辑，id：' . $data['id']);
        
        return [RESULT_SUCCESS, '操作成功', url('employeeList')];
    }
    
    
    /**
     * 获取链接列表
     */
    public function getLinkList()
    {
        
        $where = [];
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $conference_info = $this->modelWgConference->getInfo(['member_id' => MEMBER_ID]);
            
            $where['wcf.id'] = $conference_info['id'];
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_employee'))) {
            
            $where['wcd.member_id'] = MEMBER_ID;
        }
        
        $this->modelWgCode->alias('wcd');
        
        $join = [
                    [SYS_DB_PREFIX . 'wg_conference wcf', 'wcd.conference_id = wcf.id'],
                    [SYS_DB_PREFIX . 'member m', 'm.id = wcd.member_id'],
                    [SYS_DB_PREFIX . 'wg_game g', 'g.id = wcd.game_id'],
                ];
        
        $where['wcd.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
        
        $field = 'wcf.conference_name,m.username,g.game_name,wcd.*';
        
        return $this->modelWgCode->getList($where, $field, 'wcd.create_time desc', DB_LIST_ROWS, $join);
    }
    
    /**
     * 链接新增
     */
    public function linkAdd($data = [])
    {
        
        if (empty($data['conference_id']) || empty($data['member_id'])) {
            
            return [RESULT_ERROR, '请检查公会或员工是否选择'];
        }
        
        $check_info = $this->modelWgCode->getInfo($data);
        
        if (!empty($check_info)) {
            
            return [RESULT_ERROR, '员工游戏链接已存在'];
        }
        
        begin:
        
        $code = rand_code(8);
        
        $info = $this->modelWgCode->getInfo(['code' => $code]);
        
        if (!empty($info)) {
            
            goto begin;
        }
            
        $data['code']   = $code;
        $data['status'] = DATA_NORMAL;

        $result = $this->modelWgCode->setInfo($data);
        
        $result && action_log('新增', '新增链接，code：' . $code);
            
        return $result ? [RESULT_SUCCESS, '操作成功', url('linkList')] : [RESULT_ERROR, $this->modelWgCode->getError()];
    }
    
    /**
     * 获取员工选择项文本
     */
    public function getEmployeeOptions($where = [])
    {
        
        $this->modelWgConferenceMember->alias('cm');
        
        $join = [
                    [SYS_DB_PREFIX . 'member m', 'm.id = cm.member_id'],
                ];
        
        $where['cm.' . DATA_STATUS_NAME]    = ['neq', DATA_DELETE];
        
        if(check_group(MEMBER_ID, config('auth_group_id_employee'))) {
            
            $where['cm.member_id'] = MEMBER_ID;
        }
        
        $field = 'cm.*,m.username';
        
        $list = $this->modelWgConferenceMember->getList($where, $field, 'cm.create_time desc', false, $join);
        
        $text = '<option value="0">—请选择员工—</option>';
        
        foreach ($list as $info)
        {
            
            $text .= '<option value="'.$info['member_id'].'">' . $info['username'] . '</option>';
        }
        
        return $text;
    }
    
    /**
     * 获取绑定列表
     */
    public function getBindList()
    {
        
        $where = [];
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $conference_info = $this->modelWgConference->getInfo(['member_id' => MEMBER_ID]);
            
            $where['conference_id'] = $conference_info['id'];
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_employee'))) {
            
            $where['employee_id'] = MEMBER_ID;
        }
        
        $count = Db::name('wg_bind')->where($where)->count('id');
        
        $id_list = Db::name('wg_bind')->where($where)->field('id')->order('id desc')->paginate(DB_LIST_ROWS, $count, ['query' => request()->param()]);
        
        $ids = array_extract($id_list);
        
        $list = [];
        
        if (!empty($ids)) {
            
            $b_where['id'] = ['in', $ids];
            
            $list = Db::name('wg_bind')->where($b_where)->field('id,conference_id,employee_id,member_id,game_id,create_time,status,is_check,check_member_id')->order('id desc')->select();
            
            foreach ($list as &$v)
            {
                $v['employee_username']         = get_username($v['employee_id']);
                $v['username']                  = get_username($v['member_id']);
                $v['conference_name']           = Db::name('wg_conference')->where(['id' => $v['conference_id']])->value('conference_name');
                $v['game_name']                 = Db::name('wg_game')->where(['id' => $v['game_id']])->value('game_name');
                $v['create_time']               = format_time($v['create_time']);
                $v['check_username']            = get_username($v['check_member_id']);
            }
        }
        
        return ['render' => $id_list, 'list' => $list];
    }
    
    /**
     * 绑定新增
     */
    public function bindAdd($data = [])
    {
        
        if (empty($data['conference_id']) || empty($data['employee_id'])) { return [RESULT_ERROR, '请检查公会或员工是否选择']; }
        
        if (empty($data['username'])) { return [RESULT_ERROR, '请输入需要绑定的玩家账号']; }
        
        $member_info = $this->modelMember->getInfo(['username' => $data['username']]);
        
        if (empty($member_info)) { return [RESULT_ERROR, '玩家账号不存在']; }
        
        unset($data['username']);
        
        $data['member_id'] = $member_info['id'];
        
        $bind_info = $this->modelWgBind->getInfo(['member_id' => $member_info['id'], 'game_id' => $data['game_id']]);
        
        if (!empty($bind_info)) { return [RESULT_ERROR, '绑定信息已存在']; }
            
        $data['status'] = DATA_NORMAL;

        $result = $this->modelWgBind->setInfo($data);
        
        $result && action_log('新增', '新增绑定，username：' . $member_info['username']);
            
        return $result ? [RESULT_SUCCESS, '申请成功', url('bindList')] : [RESULT_ERROR, $this->modelWgBind->getError()];
    }
    
    /**
     * 绑定审核
     */
    public function bindCheck($is_check = 0, $id = 0)
    {
        
        $bind_info = $this->modelWgBind->getInfo(['id' => $id]);
        
        if ($bind_info['is_check'] != 0) : return [RESULT_ERROR, '请勿重复审核']; endif;
        
        if ($is_check == 2) {
            
            $result = Db::name('wg_bind')->where(['id' => $id])->update(['is_check' => 2, 'check_member_id' => MEMBER_ID]);
            
            $result && action_log('审核', '绑定审核未通过' . '，id：' . $id);
            
            return $result ? [RESULT_SUCCESS, '操作成功'] : [RESULT_ERROR, '操作失败'];
        } else {
        
            $func = function () use ($bind_info) {
                
                        $bd_data['conference_id']   = $bind_info['conference_id'];
                        $bd_data['c_member_id']     = $bind_info['employee_id'];
                
                        Db::name('wg_player')->where(['member_id' => $bind_info['member_id'], 'game_id' => $bind_info['game_id']])->update($bd_data);
                        Db::name('wg_order')->where(['member_id' => $bind_info['member_id'], 'game_id' => $bind_info['game_id']])->update($bd_data);
                        
                        Db::name('wg_bind')->where(['id' => $bind_info['id']])->update(['is_check' => 1, 'check_member_id' => MEMBER_ID]);
                    };

            $result = closure_list_exe([$func]);
                    
            $result && action_log('审核', '绑定审核通过' . '，id：' . $id);
                    
            return $result ? [RESULT_SUCCESS, '操作成功', url('bindList')] : [RESULT_ERROR, '操作失败'];
        }
    }
    
    /**
     * 批量绑定审核通过
     */
    public function bindAllCheck()
    {
        
        $list = Db::name('wg_bind')->where(['is_check' => DATA_DISABLE])->select();
        
        foreach ($list as $bind_info)
        {
            
            $func = function () use ($bind_info) {
                
                        $bd_data['conference_id']   = $bind_info['conference_id'];
                        $bd_data['c_member_id']     = $bind_info['employee_id'];
                
                        Db::name('wg_player')->where(['member_id' => $bind_info['member_id'], 'game_id' => $bind_info['game_id']])->update($bd_data);
                        Db::name('wg_order')->where(['member_id' => $bind_info['member_id'], 'game_id' => $bind_info['game_id']])->update($bd_data);
                        
                        Db::name('wg_bind')->where(['id' => $bind_info['id']])->update(['is_check' => 1, 'check_member_id' => MEMBER_ID]);
                    };

            $result = closure_list_exe([$func]);
                    
            $result && action_log('审核', '绑定审核通过' . '，id：' . $bind_info['id']);
        }
        
        action_log('审核', '绑定批量审核通过');
        
        return [RESULT_SUCCESS, '操作成功', url('bindList')];
    }
    
    
    /**
     * 游戏元宝池列表
     */
    public function getGameGold($param = [])
    {
        
        $map['pay_status']      = DATA_NORMAL;
        $map['order_status']    = DATA_NORMAL;
        $map['is_admin']        = DATA_DISABLE;
        $map['conference_id']   = $param['id'];

        $list = $this->modelWgGame->getList([], true, 'sort desc');
        
        foreach ($list as &$info)
        {
            
            $map['game_id'] = $info['id'];
            
            $pay_money = Db::name('wg_order')->where($map)->sum('order_money');

            empty($pay_money) && $pay_money = 0.00;
            
            $info['total_money'] = $pay_money;
            
            $limit_map = [];
            
            $limit_map['conference_id']   = $param['id'];
            $limit_map['game_id']         = $info['id'];
            
            $money = Db::name('wg_conference_limit')->where($limit_map)->sum('money');
            
            empty($money) && $money = 0.00;
            
            $info['use_money'] = $money;
            $info['residue_money'] = $pay_money-$money;
        }
        
        return $list;
    }
    
    /**
     * 区服元宝池列表
     */
    public function getServerGold($param = [])
    {
        
        $map['pay_status']      = DATA_NORMAL;
        $map['order_status']    = DATA_NORMAL;
        $map['is_admin']        = DATA_DISABLE;
        $map['conference_id']   = $param['conference_id'];

        $list = $this->modelWgServer->getList(['game_id' => $param['id']], true, 'start_time desc');
        
        foreach ($list as &$info)
        {
            
            $map['server_id'] = $info['id'];
            
            $pay_money = Db::name('wg_order')->where($map)->sum('order_money');

            empty($pay_money) && $pay_money = 0.00;
            
            $info['total_money'] = $pay_money;
            
            $limit_map = [];
            
            $limit_map['conference_id']   = $param['conference_id'];
            $limit_map['server_id']       = $info['id'];
            
            $money = Db::name('wg_conference_limit')->where($limit_map)->sum('money');
            
            empty($money) && $money = 0.00;
            
            $info['use_money'] = $money;
            $info['residue_money'] = $pay_money-$money;
        }
        
        return $list;
    }
    
    /**
     * 元宝池额度扣除
     */
    public function deductGold($param = [])
    {
        
        $map['pay_status']      = DATA_NORMAL;
        $map['order_status']    = DATA_NORMAL;
        $map['is_admin']        = DATA_DISABLE;
        $map['conference_id']   = $param['conference_id'];
        $map['server_id']       = $param['server_id'];
        
        $pay_money = Db::name('wg_order')->where($map)->sum('order_money');
        
        $limit_map = [];

        $limit_map['conference_id']   = $param['conference_id'];
        $limit_map['server_id']       = $param['server_id'];

        $money = Db::name('wg_conference_limit')->where($limit_map)->sum('money');
        
        if (empty($param['money']) || empty($param['server_id'])) {
            
            return [RESULT_ERROR, '区服与金额数据不能为空'];
        }
        
        if (!is_numeric($param['money'])) {
            
            return [RESULT_ERROR, '请检查金额输入是否为数字'];
        }
        
        if (($pay_money - $money) < $param['money']) {
            
            return [RESULT_ERROR, '元宝池额度不足'];
        }
        
        $add_data['conference_id']  =  $param['conference_id'];
        $add_data['member_id']      =  MEMBER_ID;
        $add_data['game_id']        =  $param['game_id'];
        $add_data['server_id']      =  $param['server_id'];
        $add_data['money']          =  $param['money'];
        $add_data['use']            =  $param['use'];
        $add_data['create_time']    =  TIME_NOW;
        $add_data['status']         =  DATA_NORMAL;

        $result = Db::name('wg_conference_limit')->insert($add_data);
        
        $result && action_log('元宝池', '元宝池额度扣除：' . $param['money'] . '，conference_id：' . $param['conference_id']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', url('conference/serverGold',['id' => $param['game_id'], 'conference_id' => $param['conference_id']])] : [RESULT_ERROR, '操作失败'];
    }
}
