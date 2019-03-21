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
    
    // 礼包中心首页-页游
    public function index()
    {
        
        set_url();
        
        $this->setTitle('页游-礼包中心');
        
        $this->assign('data', $this->logicGift->getGiftData($this->param));
        
        return $this->fetch('index');
    }
    
    // 礼包中心首页-H5
    public function h5()
    {
        
        set_url();
        
        $this->setTitle('H5手游-礼包中心');
        
        $this->assign('game_category', $this->logicGame->getGameCategory());
        
        $this->assign('gift_list', $this->logicGift->getMobileGiftList($this->param));
        
        return $this->fetch('mobile_gift_list');
    }
    
    // 礼包中心首页-安卓
    public function android()
    {
        
        set_url();
        
        $this->setTitle('安卓手游-礼包中心');
        
        $this->assign('game_category', $this->logicGame->getGameCategory());
        
        $this->assign('gift_list', $this->logicGift->getMobileGiftList($this->param));
        
        return $this->fetch('mobile_gift_list');
    }
    
    // 礼包详情
    public function details()
    {
        
        set_url();
        
        $this->assign('data', $this->logicGift->getGiftDetailsData($this->param));
        
        return $this->fetch('details');
    }
    
    
    // 手游礼包详情
    public function mobileDetails()
    {
        
        set_url();
        
        $this->assign('data', $this->logicGift->getMobileGiftDetailsData($this->param));
        
        return $this->fetch('mobile_details');
    }
    
    // 领取礼包
    public function getGift()
    {
        
        $data = $this->logicGift->getGift($this->param);
        
        $data[0] == RESULT_ERROR ? $this->error($data[1]) : $this->success($data[1]);
    }
    
    // 领取手机礼包
    public function getMobileGift()
    {
        
        $data = $this->logicGift->getMobileGift($this->param);
        
        $data[0] == RESULT_ERROR ? $this->error($data[1]) : $this->success($data[1]);
    }
}
