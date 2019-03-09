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
 * 游戏接口控制器
 */
class Game extends ApiBase
{
    
    /**
     * 角色信息
     */
    public function roles()
    {
        
        return $this->apiReturn($this->logicGame->getRoles($this->param));
    }
}
