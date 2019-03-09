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
 * 礼包中心控制器
 */
class Gift extends IndexBase
{
    
    // 礼包中心首页
    public function index()
    {
        
        set_url();
        
        $this->setTitle('礼包中心');
        
        $this->assign('data', $this->logicGift->getGiftData($this->param));
        
        return $this->fetch('index');
    }
    
    // 礼包详情
    public function details()
    {
        
        set_url();
        
        $this->assign('data', $this->logicGift->getGiftDetailsData($this->param));
        
        return $this->fetch('details');
    }
    
    // 领取礼包
    public function getGift()
    {
        
        $data = $this->logicGift->getGift($this->param);
        
        $data[0] == RESULT_ERROR ? $this->error($data[1]) : $this->success($data[1]);
    }
}
