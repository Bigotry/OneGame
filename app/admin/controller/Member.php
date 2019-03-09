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

namespace app\admin\controller;

/**
 * 会员控制器
 */
class Member extends AdminBase
{

    /**
     * 会员授权
     */
    public function memberAuth()
    {
        
        IS_POST && $this->jump($this->logicMember->addToGroup($this->param));
        
        // 所有的权限组
        $group_list = $this->logicAuthGroup->getAuthGroupList(['member_id' => MEMBER_ID]);
        
        // 会员当前权限组
        $member_group_list = $this->logicAuthGroupAccess->getMemberGroupInfo($this->param['id']);

        // 选择权限组
        $list = $this->logicAuthGroup->selectAuthGroupList($group_list, $member_group_list);
        
        $this->assign('list', $list);
        
        $this->assign('id', $this->param['id']);
        
        return $this->fetch('member_auth');
    }
    
    /**
     * 会员列表
     */
    public function memberList()
    {
        
        $where = $this->logicMember->getWhere($this->param);
        
        $this->assign('list', $this->logicMember->getMemberList($where, true, 'id desc'));
        
        return $this->fetch('member_list');
    }
    
    /**
     * 会员导出
     */
    public function exportMemberList()
    {
        
        $where = $this->logicMember->getWhere($this->param);
        
        $this->logicMember->exportMemberList($where);
    }
    
    /**
     * 会员添加
     */
    public function memberAdd()
    {
        
        IS_POST && $this->jump($this->logicMember->memberAdd($this->param));
        
        return $this->fetch('member_add');
    }
    
    /**
     * 会员编辑
     */
    public function memberEdit()
    {
        
        IS_POST && $this->jump($this->logicMember->memberEdit($this->param));
        
        $info = $this->logicMember->getMemberInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);
        
        return $this->fetch('member_edit');
    }
    
    /**
     * 重置密码
     */
    public function resetPassword()
    {
        
        IS_POST && $this->jump($this->logicMember->resetPassword($this->param));
        
        return $this->fetch('reset_password');
    }
    
    /**
     * 会员订单转移
     */
    public function shiftOrder()
    {
        
        IS_POST && $this->jump($this->logicMember->shiftOrder($this->param));
        
        $this->assign('conference_list', $this->logicConference->getConferenceList([], 'c.*,mm.nickname as m_nickname,ms.nickname as s_nickname', 'c.create_time desc', false));
        
        $this->assign('game_list', $this->logicGame->getGameList([], 'g.*,c.category_name', 'g.create_time desc', false));
        
        return $this->fetch('shift_order');
    }
    
    /**
     * 会员删除
     */
    public function memberDel($id = 0)
    {
        
        return $this->jump($this->logicMember->memberDel(['id' => $id]));
    }
    
    /**
     * 设置测试账号
     */
    public function setTestMember($id = 0, $is_test = 0)
    {
        
        $this->jump($this->logicMember->setTestMember($id, $is_test));
    }
}
