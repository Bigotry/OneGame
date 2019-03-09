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
 * 前台账号安全控制器
 */
class Safety extends IndexBase
{
    
    // 找回密码
    public function retrievePassword()
    {
        
        IS_POST && $this->jump($this->logicSafety->retrievePassword($this->param));
        
        $this->setTitle('找回密码');
        
        return $this->fetch('retrieve_password');
    }
    
    // 邮箱验证
    public function emailVerify()
    {
        
        $this->jump($this->logicSafety->emailVerify($this->param));
    }
    
    // 设置新密码
    public function setPassword($sign = '')
    {
        
        IS_POST && $this->jump($this->logicSafety->setPassword($this->param));
        
        $result = verify_email_sign($sign);
        
        if ($result !== true) {
            
            $this->jump(RESULT_ERROR, '链接签名不正确');
        }
        
        $this->setTitle('设置新密码');
        
        $this->assign('sign', $sign);
        
        return $this->fetch('set_password');
    }
  
}
