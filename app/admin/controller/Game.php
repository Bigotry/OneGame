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
 * 游戏信息相关控制器
 */
class Game extends AdminBase
{
    
    /**
     * 游戏分类列表
     */
    public function categoryList()
    {
        
        $this->assign('list', $this->logicGame->getCategoryList());
        
        return $this->fetch('category_list');
    }
    
    /**
     * 游戏分类编辑
     */
    public function categoryEdit()
    {
        
        IS_POST && $this->jump($this->logicGame->categoryEdit($this->param));
        
        !empty($this->param['id']) && $this->assign('info', $this->logicGame->getCategoryInfo(['id' => $this->param['id']]));
        
        return $this->fetch('category_edit');
    }
    
    /**
     * 游戏分类删除
     */
    public function categoryDel($id = 0)
    {
        
        $this->jump($this->logicGame->categoryDel(['id' => $id]));
    }
    
    /**
     * 游戏列表
     */
    public function gameList()
    {
        
        $this->assign('list', $this->logicGame->getGameList());
        
        return $this->fetch('game_list');
    }

    /**
     * 游戏编辑
     */
    public function gameEdit()
    {
        
        IS_POST && $this->jump($this->logicGame->gameEdit($this->param));
        
        !empty($this->param['id']) && $this->assign('info', $this->logicGame->getGameInfo(['id' => $this->param['id']]));
        
        $this->assign('category_list', $this->logicGame->getCategoryList([], true, 'id', false));
        
        return $this->fetch('game_edit');
    }
    
    /**
     * 游戏删除
     */
    public function gameDel($id = 0)
    {
        
        $this->jump($this->logicGame->gameDel(['id' => $id]));
    }
    
    /**
     * 区服列表
     */
    public function serverList()
    {
        
        $this->assign('list', $this->logicGame->getServerList());
        
        return $this->fetch('server_list');
    }
    
    /**
     * 区服编辑
     */
    public function serverEdit()
    {
        
        IS_POST && $this->jump($this->logicGame->serverEdit($this->param));
        
        !empty($this->param['id']) && $this->assign('info', $this->logicGame->getServerInfo(['id' => $this->param['id']]));
        
        $this->assign('game_list', $this->logicGame->getGameList([], 'g.*,c.category_name', 'g.sort desc', false));
        
        return $this->fetch('server_edit');
    }
    
    /**
     * 区服删除
     */
    public function serverDel($id = 0)
    {
        
        $this->jump($this->logicGame->serverDel(['id' => $id]));
    }
}