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
 * 前端模块基类控制器
 */
class IndexBase extends ControllerBase
{
    
    /**
     * 构造方法
     */
    public function __construct()
    {
        
        parent::__construct();
        
        $this->assign('sys_common_data', $this->logicIndexBase->getCommonData());
    }
    
    /**
     * 设置指定标题
     */
    final protected function setTitle($title = '')
    {
        
        $this->assign('ob_title', $title);
    }
}
