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
 * 开始游戏
 */
class Play extends ControllerBase
{
    
    /**
     * 构造方法
     */
    public function __construct()
    {
        
        parent::__construct();
        
        set_url();
        
        !is_login() && $this->redirect('login/login');
    }
    
    /**
     * 游戏入口
     */
    public function index($game_code = '', $sid = 0, $client = '')
    {
        
        $result = $this->logicPlay->login($game_code, $sid, $client);
        
        if (RESULT_ERROR == $result[0]) {
            
            empty($result[2]) ? $this->error($result[1]) : $this->error($result[1], $result[2]);
        }
        
        $this->assign('url', $result[1]);
        
        $this->view->engine->layout(false);
        
        return $this->fetch();
    }
    
}
