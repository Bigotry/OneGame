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
 * 公会相关控制器
 */
class Conference extends AdminBase
{
    
    /**
     * 公会列表
     */
    public function conferenceList()
    {
        
        $where = [];
        
        !empty($this->param['search_data']) && $where['c.conference_name'] = ['like', $this->param['search_data'].'%'];
        
        $this->assign('list', $this->logicConference->getConferenceList($where));
        
        return $this->fetch('conference_list');
    }
    
    /**
     * 导出公会列表
     */
    public function exportConferenceList()
    {
        
        $this->logicConference->getExportConferenceList();
    }
    
    /**
     * 公会添加
     */
    public function conferenceAdd()
    {
        
        IS_POST && $this->jump($this->logicConference->conferenceAdd($this->param));
        
        return $this->fetch('conference_add');
    }
    
    /**
     * 公会编辑
     */
    public function conferenceEdit()
    {
        
        IS_POST && $this->jump($this->logicConference->conferenceEdit($this->param));
        
        !empty($this->param['id']) && $this->assign('info', $this->logicConference->getConferenceInfo(['id' => $this->param['id']]));
        
        return $this->fetch('conference_edit');
    }
    
    /**
     * 员工列表
     */
    public function employeeList()
    {
        
        $this->assign('list', $this->logicConference->getEmployeeList($this->param));
        
        return $this->fetch('employee_list');
    }
    
    /**
     * 员工添加
     */
    public function employeeAdd()
    {
        
        IS_POST && $this->jump($this->logicConference->employeeAdd($this->param));
        
        $this->assign('conference_list', $this->logicConference->getConferenceList([], 'c.*,mm.nickname as m_nickname,ms.nickname as s_nickname', 'c.create_time desc', false));
        
        return $this->fetch('employee_add');
    }
    
    /**
     * 员工编辑
     */
    public function employeeEdit()
    {
        
        IS_POST && $this->jump($this->logicConference->employeeEdit($this->param));
        
        $info = $this->logicMember->getMemberInfo(['username' => $this->param['username']]);
        
        $this->assign('info', $info);
        
        $cm_info = $this->logicConference->getConferenceMemberInfo(['id' => $this->param['id']]);
        
        $this->assign('cm_info', $cm_info);
        
        $this->assign('conference_list', $this->logicConference->getConferenceList([], 'c.*,mm.nickname as m_nickname,ms.nickname as s_nickname', 'c.create_time desc', false));
        
        return $this->fetch('employee_edit');
    }
    
    /**
     * 链接列表
     */
    public function linkList()
    {
        
        $this->assign('list', $this->logicConference->getLinkList());
        
        return $this->fetch('link_list');
    }
    
    /**
     * 链接添加
     */
    public function linkAdd()
    {
        
        IS_POST && $this->jump($this->logicConference->linkAdd($this->param));
        
        $this->assign('conference_list', $this->logicConference->getConferenceList([], 'c.*,mm.nickname as m_nickname,ms.nickname as s_nickname', 'c.create_time desc', false));
        
        $this->assign('game_list', $this->logicGame->getGameList([], 'g.*,c.category_name', 'g.create_time desc', false));
        
        return $this->fetch('link_add');
    }
    
    /**
     * 获取员工选择项
     */
    public function getEmployeeOptions($conference_id = 0)
    {
        
        $where['cm.conference_id'] = $conference_id;
        
        $data['content'] = $this->logicConference->getEmployeeOptions($where);
        
        return $data;
    }
    
    /**
     * 绑定列表
     */
    public function bindList()
    {
        
        $this->assign('list', $this->logicConference->getBindList());
        
        return $this->fetch('bind_list');
    }
    
    /**
     * 游戏元宝池列表
     */
    public function gameGold()
    {
        
        $this->assign('list', $this->logicConference->getGameGold($this->param));
        
        return $this->fetch('game_gold');
    }
    
    /**
     * 区服元宝池列表
     */
    public function serverGold()
    {
        
        $this->assign('list', $this->logicConference->getServerGold($this->param));
        
        return $this->fetch('server_gold');
    }
    
    /**
     * 绑定添加
     */
    public function bindAdd()
    {
        
        IS_POST && $this->jump($this->logicConference->bindAdd($this->param));
        
        $this->assign('conference_list', $this->logicConference->getConferenceList([], 'c.*,mm.nickname as m_nickname,ms.nickname as s_nickname', 'c.create_time desc', false));
        
        $this->assign('game_list', $this->logicGame->getGameList([], 'g.*,c.category_name', 'g.create_time desc', false));
        
        return $this->fetch('bind_add');
    }
    
    /**
     * 绑定审核
     */
    public function bindCheck($is_check = 0, $id = 0)
    {
        
        $this->jump($this->logicConference->bindCheck($is_check, $id));
    }
    
    /**
     * 批量绑定审核通过
     */
    public function bindAllCheck()
    {
        
        $this->jump($this->logicConference->bindAllCheck());
    }
    
    /**
     * 扣除元宝池额度
     */
    public function deductGold()
    {
        
        IS_POST && $this->jump($this->logicConference->deductGold($this->param));
        
        $server_info = $this->logicGame->getServerInfo(['id' => $this->param['id']]);
        
        $server_list = $this->logicGame->getServerList(['s.game_id' => $server_info['game_id']], 's.*,g.game_name', 's.create_time desc', false);
        
        $this->assign('game_list', $this->logicGame->getGameList([], 'g.*,c.category_name', 'g.create_time desc', false));
        $this->assign('server_list', $server_list);
        $this->assign('server_info', $server_info);
        
        return $this->fetch('deduct_gold');
        
    }
}
