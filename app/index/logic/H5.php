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

namespace app\index\logic;

/**
 * 开始H5手游业务逻辑
 */
class H5 extends IndexBase
{
    
    /**
     * 手游开始
     */
    public function play($gid)
    {
        
        $driver = SYS_DRIVER_DIR_NAME . ucfirst('Jiule');
        
        return $this->serviceH5game->$driver->play($gid);
    }
    
    /**
     * 手游列表
     */
    public function gameList($param)
    {
        
        $driver = SYS_DRIVER_DIR_NAME . ucfirst('Jiule');
        
        return $this->serviceH5game->$driver->gameList($param);
    }
}
