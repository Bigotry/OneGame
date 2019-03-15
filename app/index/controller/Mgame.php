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

/**
 * 手游控制器
 */
class Mgame extends IndexBase
{
    
    // 游戏中心首页
    public function index()
    {
        
        set_url();
        
        $this->setTitle('OneGame - 手游');
        
        $this->assign('data', $this->logicMgame->getGameList($this->param));
        
        $this->assign('game_category', $this->logicGame->getGameCategory());
        
        return $this->fetch('jiule_index');
    }
    
    // 开始游戏
    public function play($gid = 0)
    {
        
        set_url();
        
        !is_login() && $this->redirect('login/login');
        
        $this->setTitle('OneGame - 手游');
        
        $this->assign('play_url', $this->logicMgame->play($gid));
        
        return $this->fetch('jiule_play');
    }
    
    // 安装包下载
    public function download($id = 0)
    {
        
        !is_login() && $this->redirect('login/login');
        
        $this->logicMgame->download($id);
    }
}
