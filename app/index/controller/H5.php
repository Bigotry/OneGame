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
        
        $this->setTitle('H5手游');
        
        $this->assign('data', $this->logicGame->getH5GameData($this->param));
        
        return $this->fetch('index');
    }
}
