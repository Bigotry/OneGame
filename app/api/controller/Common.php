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

namespace app\api\controller;

/**
 * 公共基础接口控制器
 */
class Common extends ApiBase
{
    
    /**
     * 登录接口
     */
    public function login()
    {
        
        return $this->apiReturn($this->logicCommon->login($this->param));
    }
    
    /**
     * 注册接口
     */
    public function register()
    {
        
        return $this->apiReturn($this->logicCommon->register($this->param));
    }
    
    /**
     * 修改密码接口
     */
    public function changePassword()
    {
        
        return $this->apiReturn($this->logicCommon->changePassword($this->param));
    }
}
