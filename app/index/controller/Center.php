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
 * 个人中心控制器
 */
class Center extends CenterBase
{
    
    // 个人中心首页我的游戏
    public function index()
    {
        
        // 我的游戏数据
        $this->assign('list', $this->logicCenter->getMyGameData(is_login()));
        
        $this->setTitle('我的游戏');
        
        return $this->fetch('index');
    }
    
    // 修改密码
    public function changePassword()
    {
        
        IS_POST && $this->jump($this->logicCenter->changePassword($this->param));
        
        $this->setTitle('密码修改');
        
        return $this->fetch('change_password');
    }
    
    // 账号安全
    public function safety()
    {
        
        $this->setTitle('账号安全');
        
        $member_id = is_login();
        
        $member_info = $this->logicMember->getMemberInfo(['id' => $member_id]);
        
        $this->assign('member_info', $member_info);
        
        return $this->fetch('safety');
    }
    
    // 设置邮箱
    public function setEmail()
    {
        
        $member_id = is_login();
        
        $member_info = $this->logicMember->getMemberInfo(['id' => $member_id]);
        
        IS_POST && $this->jump($this->logicCenter->sendEmail($this->param, $member_info));
        
        $this->setTitle('设置邮箱');
        
        $this->assign('member_info', $member_info);
        
        return $this->fetch('set_email');
    }
    
    // 礼包
    public function gift()
    {
        
        $this->assign('list', $this->logicCenter->getMyGift(is_login()));
        
        $this->setTitle('我的礼包');
        
        return $this->fetch('gift_list');
    }
    
    // 礼包
    public function mobileGift()
    {
        
        $this->assign('list', $this->logicCenter->getMyMobileGift(is_login()));
        
        $this->setTitle('我的礼包');
        
        return $this->fetch('gift_list');
    }
    
    /**
     * 充值中心
     */
    public function pay($pay_code = '')
    {
        
        $this->assign('data', $this->logicPay->payInitData($pay_code));
        
        $this->setTitle('充值中心');
        
        return $this->fetch();
    }
    
    /**
     * 支付处理
     */
    public function payHandle()
    {
        
        $result = $this->logicPay->payHandle($this->param);
        
        if (RESULT_ERROR == $result[0]) {
            
            $this->error($result[1]);
        } else {
            
            echo $result[1];
        }
    }
}
