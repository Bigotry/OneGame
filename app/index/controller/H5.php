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
class H5 extends IndexBase
{
    
    // 游戏中心首页
    public function index()
    {
        
        set_url();
        
        $this->setTitle('OneGame - H5手游');
        
        $data = $this->logicH5->gameList($this->param);
        
        if (empty($data['game_data']['items']) && $this->param['page'] != 1) {
            
            $type = empty($this->param['type']) ? '' : $this->param['type'];
            
            return $this->redirect('h5/index', ['page' => $this->param['page']-1, 'type' => $type]);
        }
        
        $this->assign('game_list_data', $data['game_data']);
        $this->assign('prev_url', $data['prev_url']);
        $this->assign('next_url', $data['next_url']);
        
        return $this->fetch('jiule_index');
    }
    
    // 开始游戏
    public function play($gid = 0)
    {
        
        set_url();
        
        !is_login() && $this->redirect('login/login');
        
        $this->setTitle('OneGame - H5手游');
        
        $this->assign('play_url', $this->logicH5->play($gid));
        
        return $this->fetch('jiule_play');
    }
}
