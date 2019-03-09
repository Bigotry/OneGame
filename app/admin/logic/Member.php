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
 * 会员逻辑
 */
class Member extends AdminBase
{
    
    /**
     * 获取会员信息
     */
    public function getMemberInfo($where = [], $field = true)
    {
        
        $info = $this->modelMember->getInfo($where, $field);
        
//        $info['leader_nickname'] = $this->modelMember->getValue(['id' => $info['leader_id']], 'nickname');
        
        return $info;
    }
    
    /**
     * 获取会员列表
     */
    public function getMemberList($where = [], $field = true, $order = '', $paginate = DB_LIST_ROWS)
    {
        
        return $this->modelMember->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 导出会员列表
     */
    public function exportMemberList($where = [], $field = 'm.*', $order = '')
    {
        
        $list = $this->getMemberList($where, $field, $order, false);
        
        $titles = "昵称,用户名,邮箱,手机,注册时间";
        
        $keys   = "nickname,username,email,mobile,create_time";
        
        action_log('导出', '导出会员列表');
        
        export_excel($titles, $keys, $list, '会员列表');
    }
    
    /**
     * 获取会员列表搜索条件
     */
    public function getWhere($data = [])
    {
        
        $where = [];
        
        !empty($data['search_data']) && $where['nickname|username|email|mobile'] = ['like', '%'.$data['search_data'].'%'];
        
        $where['id'] = ['neq', SYS_ADMINISTRATOR_ID];
        
//        if (!is_administrator()) {
//            
//            $member = session('member_info');
//            
//            if ($member['is_share_member']) {
//                
//                $ids = $this->getInheritMemberIds(MEMBER_ID);
//                
//                $ids[] = MEMBER_ID;
//                
//                $where['m.leader_id'] = ['in', $ids];
//                
//            } else {
//                
//                $where['m.leader_id'] = MEMBER_ID;
//            }
//        }
        
        return $where;
    }
    
    /**
     * 获取存在继承关系的会员ids
     */
    public function getInheritMemberIds($id = 0, $data = [])
    {
        
        $member_id = $this->modelMember->getValue(['id' => $id, 'is_share_member' => DATA_NORMAL], 'leader_id');
        
        if (empty($member_id)) {
            
            return $data;
        } else {
            
            $data[] = $member_id;
            
            return $this->getInheritMemberIds($member_id, $data);
        }
    }
    
    /**
     * 获取会员的所有下级会员
     */
    public function getSubMemberIds($id = 0, $data = [])
    {
        
        $member_list = $this->modelMember->getList(['leader_id' => $id], 'id', 'id asc', false);
        
        foreach ($member_list as $v)
        {
            
            if (!empty($v['id'])) {
                
                $data[] = $v['id'];
            
                $data = array_unique(array_merge($data, $this->getSubMemberIds($v['id'], $data)));
            }
            
            continue;
        }
            
        return $data;
    }
    
    /**
     * 会员添加到用户组
     */
    public function addToGroup($data = [])
    {
        
        $url = url('memberList');
        
        if (SYS_ADMINISTRATOR_ID == $data['id']) {
            
            return [RESULT_ERROR, '天神不能授权哦~', $url];
        }
        
        $where = ['member_id' => ['in', $data['id']]];
        
        $this->modelAuthGroupAccess->deleteInfo($where, true);
        
        if (empty($data['group_id'])) {
            
            return [RESULT_SUCCESS, '会员授权成功', $url];
        }
        
        $add_data = [];
        
        foreach ($data['group_id'] as $group_id) {
            
            $add_data[] = ['member_id' => $data['id'], 'group_id' => $group_id];
        }
        
        if ($this->modelAuthGroupAccess->setList($add_data)) {
            
            action_log('授权', '会员授权，id：' . $data['id']);
        
            $this->logicAuthGroup->updateSubAuthByMember($data['id']);
            
            return [RESULT_SUCCESS, '会员授权成功', $url];
        } else {
            
            return [RESULT_ERROR, $this->modelAuthGroupAccess->getError()];
        }
    }
    
    /**
     * 会员添加
     */
    public function memberAdd($data = [])
    {
        
        $validate_result = $this->validateMember->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateMember->getError()];
        }
        
        $url = url('memberList');
        
        $data['nickname']  = $data['username'];
        $data['leader_id'] = MEMBER_ID;
        $data['is_inside'] = DATA_NORMAL;
        $data['password_version'] = DATA_NORMAL;
        
        $result = $this->modelMember->setInfo($data);
        
        $result && action_log('新增', '新增会员，username：' . $data['username']);
        
        return $result ? [RESULT_SUCCESS, '会员添加成功', $url] : [RESULT_ERROR, $this->modelMember->getError()];
    }
    
    /**
     * 会员编辑
     */
    public function memberEdit($data = [])
    {
        
        $validate_result = $this->validateMember->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateMember->getError()];
        }
        
        $url = url('memberList');
        
        $result = $this->modelMember->setInfo($data);
        
        $result && action_log('编辑', '编辑会员，id：' . $data['id']);
        
        return $result ? [RESULT_SUCCESS, '会员编辑成功', $url] : [RESULT_ERROR, $this->modelMember->getError()];
    }
    
    /**
     * 设置会员信息
     */
    public function setMemberValue($where = [], $field = '', $value = '')
    {
        
        return $this->modelMember->setFieldValue($where, $field, $value);
    }
    
    /**
     * 会员删除
     */
    public function memberDel($where = [])
    {
        
        $url = url('memberList');
        
        if (SYS_ADMINISTRATOR_ID == $where['id'] || MEMBER_ID == $where['id']) {
            
            return [RESULT_ERROR, '天神和自己不能删除哦~', $url];
        }
        
        $result = $this->modelMember->deleteInfo($where);
                
        $result && action_log('删除', '删除会员，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '会员删除成功', $url] : [RESULT_ERROR, $this->modelMember->getError(), $url];
    }
    
    /**
     * 测试号设置
     */
    public function setTestMember($id = 0, $is_test = 0)
    {
        
        $result = Db::name('member')->where(['id' => $id])->update(['is_test' => $is_test]);

        $result && action_log('设置', '设置测试号' . '，id：' . $id);

        return $result ? [RESULT_SUCCESS, '操作成功'] : [RESULT_ERROR, '操作失败'];
    }
    
    /**
     * 重置密码
     */
    public function resetPassword($data = [])
    {
        
        if (SYS_ADMINISTRATOR_ID == $data['id']) {
            
            return [RESULT_ERROR, '不能设置天神的密码哦'];
        }
        
        $md5_password = data_md5_key($data['password']);
        
        $save_data['password']          = $md5_password;
        $save_data['password_version']  = DATA_NORMAL;
        
        $result = Db::name('member')->where(['id' => $data['id']])->update($save_data);
        
        $result && action_log('设置', '设置会员密码' . '，id：' . $data['id']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', url('member/memberList')] : [RESULT_ERROR, '操作失败'];
    }
    
    /**
     * 订单转移
     */
    public function shiftOrder($data = [])
    {
        
        if (empty($data['conference_id']) || empty($data['employee_id'])) {
            
            return [RESULT_ERROR, '检查公会或员工是否选择'];
        }
        
        if (empty($data['start_time']) || empty($data['end_time'])) {
            
            return [RESULT_ERROR, '请检查时间是否选择完整'];
        }
        
        $map['member_id'] = $data['id'];
        
        $map['create_time'] = [['elt', strtotime($data['end_time'])], ['egt', strtotime($data['start_time'])]];
        
        if (!empty($data['game_id'])) {
            
             $map['game_id']  = $data['game_id'];
        }
        
        if (!empty($data['server_id'])) {
            
             $map['server_id'] = $data['server_id'];
        }
        
        $save_data['conference_id'] = $data['conference_id'];
        $save_data['c_member_id']   = $data['employee_id'];
        
        $result = Db::name('wg_order')->where($map)->update($save_data);
        
        $result && action_log('编辑', '订单数据转移' . '，member_id：' . $data['id'] . '，开始：' . $data['start_time'] . '，结束：' . $data['end_time']);
        
        if ($data['is_shift']) {
            
            $bind_data['conference_id'] = $data['conference_id'];
            $bind_data['employee_id']   = $data['employee_id'];

            $bind_result = Db::name('wg_bind')->where(['member_id' => $data['id']])->update($bind_data);
            
            $player_data['conference_id'] = $data['conference_id'];
            $player_data['c_member_id']   = $data['employee_id'];
            
            $player_result = Db::name('wg_player')->where(['member_id' => $data['id']])->update($player_data);
            
            $bind_result !== false && $player_result !== false && action_log('编辑', '玩家转移' . '，member_id：' . $data['id'] . '，目标员工：' . $data['employee_id']);
        }
        
        return $result !== false ? [RESULT_SUCCESS, '操作成功', url('member/memberList')] : [RESULT_ERROR, '操作失败'];
    }
    
}
