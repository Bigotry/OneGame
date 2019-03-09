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
 * 礼包相关控制器
 */
class Gift extends AdminBase
{
    
    /**
     * 礼包列表
     */
    public function giftList()
    {
        
        $this->assign('list', $this->logicGift->getGiftList());
        
        return $this->fetch('gift_list');
    }
    
    /**
     * 礼包编辑
     */
    public function giftEdit()
    {
        
        IS_POST && $this->jump($this->logicGift->giftEdit($this->param));
        
        !empty($this->param['id']) && $this->assign('info', $this->logicGift->getGiftInfo(['id' => $this->param['id']]));
        
        $this->assign('game_list', $this->logicGame->getGameList([], 'g.*,c.category_name', 'g.sort desc', false));
        
        return $this->fetch('gift_edit');
    }
    
    /**
     * 礼包删除
     */
    public function giftDel($id = 0)
    {
        
        $this->jump($this->logicGift->giftDel(['id' => $id]));
    }
    
  
    /**
     * 库存列表
     */
    public function inventoryList()
    {
        
        $this->assign('list', $this->logicGift->getInventoryList($this->param));
        
        return $this->fetch('inventory_list');
    }
    
    /**
     * 礼包KEY新增
     */
    public function giftAddKey()
    {
        
        IS_POST && $this->jump($this->logicGift->giftAddKey($this->param));
        
        return $this->fetch('gift_add_key');
    }
    
    /**
     * 礼包KEY批量导入
     */
    public function giftImportKey()
    {
        
        IS_POST && $this->jump($this->logicGift->giftImportKey($this->param));
        
        return $this->fetch('gift_import_key');
    }
}