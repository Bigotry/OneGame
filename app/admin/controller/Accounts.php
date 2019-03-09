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
 * 账目控制器
 */
class Accounts extends AdminBase
{
    
    /**
     * 订单列表
     */
    public function orderList()
    {
        
        $this->assign('data', $this->logicAccounts->getOrderList($this->param));
        
        $this->assign('game_list', $this->logicGame->getGameList([], 'g.*,c.category_name', 'g.sort desc', false));

        !empty($this->param['game_id']) && $this->assign('server_list', $this->logicGame->getServerList(['game_id' => $this->param['game_id']], 's.*,g.game_name', 's.create_time desc', false));
        
        $this->assign('is_operation', check_group(MEMBER_ID, config('auth_group_id_operation')));
        
        return $this->fetch('order_list');
    }
    
    /**
     * 充值排行榜
     */
    public function payToppingList()
    {
        
        $this->assign('list', $this->logicAccounts->getPayToppingList($this->param));
        
        $this->assign('game_list', $this->logicGame->getGameList([], 'g.*,c.category_name', 'g.sort desc', false));

        !empty($this->param['game_id']) && $this->assign('server_list', $this->logicGame->getServerList(['game_id' => $this->param['game_id']], 's.*,g.game_name', 's.create_time desc', false));
        
        return $this->fetch('pay_topping_list');
    }
    
    
    /**
     * 公会结算
     */
    public function conferenceAccounts()
    {
        
        $this->assign('list', $this->logicAccounts->getConferenceAccounts($this->param));
        
        return $this->fetch('conference_accounts');
    }
    
    /**
     * 公会结算导出
     */
    public function conferenceAccountsExport()
    {
        
        $this->logicAccounts->exportConferenceAccounts($this->param);
    }
    
    
    /**
     * 补充订单
     */
    public function replenishOrder()
    {
        
        $this->jump($this->logicAccounts->replenishOrder($this->param));
    }
    
    /**
     * 获取新角色选择项
     */
    public function getNewRoleOptions($role_id = 0)
    {
        
        $data['content'] = $this->logicGame->getNewRoleOptions($role_id);
        
        return $data;
    }
    
    /**
     * 更新角色信息
     */
    public function updateRole($id = 0, $new_role_id = 0)
    {
        
        $this->jump($this->logicGame->updateRole($id, $new_role_id));
    }
}
