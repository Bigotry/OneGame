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
 * 前端模块个人中心基类控制器
 */
class CenterBase extends IndexBase
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
}
