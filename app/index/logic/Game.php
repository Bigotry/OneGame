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

/**
 * 游戏中心逻辑
 */
class Game extends IndexBase
{
    
    /**
     * 获取页游首页数据
     */
    public function getGameData($param = [])
    {
        
        $data['game_top_list']       =   $this->getGameTop();
        
        $data['game_category_list']  =   $this->getGameCategory();
        
        $data['game_list']           =   $this->getGame($param);
        
        return $data;
    }
    
    /**
     * 游戏排行榜
     */
    public function getGameTop()
    {
        
        $where[DATA_STATUS_NAME]      = ['neq', DATA_DELETE];
        $where['maintain_end_time']   = ['elt', TIME_NOW];
        
        return $this->modelWgGame->getList($where, 'id,game_name,game_code,game_cover', 'sort desc', false, [], null, 9);
    }
    
    /**
     * 分类列表
     */
    public function getGameCategory()
    {
        
        $where[DATA_STATUS_NAME]      = ['neq', DATA_DELETE];
        
        return $this->modelWgCategory->getList($where, 'id,category_name', 'id asc', false);
    }
    
    /**
     * 游戏列表
     */
    public function getGame($param)
    {
        
        $where[DATA_STATUS_NAME]      = ['neq', DATA_DELETE];
        $where['maintain_end_time']   = ['elt', TIME_NOW];
        
        !empty($param['cid'])         && $where['game_category_id']   = (int)$param['cid'];
        !empty($param['keyword'])     && $where['game_name']          = ['like','%'.(string)$param['keyword'].'%'];
        
        return $this->modelWgGame->getList($where, 'id,game_name,game_code,game_cover', 'sort desc', 12);
    }
}
