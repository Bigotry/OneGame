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

namespace app\index\controller;

use app\common\controller\ControllerBase;

/**
 * 官网控制器
 */
class Website extends ControllerBase
{
    
    public $gameInfo = null;
    
    /**
     * 初始化方法
     */
    public function _initialize()
    {
        
        parent::_initialize();
        
        $game_code = input('game_code/s');
        
        empty($game_code) && $this->error('游戏标识不能为空');
        
        $data = $this->logicWebsite->getWebsiteCommonData($game_code);
        
        $this->assign('data', $data);
        
        $this->assign('game_code', $game_code);
        
        $this->gameInfo = $data['game_info'];
        
        $this->view->engine->layout(false);
        
        set_url();
    }
    
    // 官网首页
    public function index()
    {
        
        $this->assign('article_data', $this->logicWebsite->getArticleData($this->gameInfo));
        
        return $this->fetch('index');
    }
    
    // 官网文章列表
    public function articleList()
    {
        
        $this->assign('article_list_data', $this->logicWebsite->getArticleListData($this->gameInfo, $this->param));
        
        return $this->fetch('article_list');
    }
    
    // 官网文章详情
    public function articleDetails()
    {
        
        $this->assign('article_details_data', $this->logicWebsite->getArticleDetailsData($this->param));
        
        return $this->fetch('article_details');
    }
}
