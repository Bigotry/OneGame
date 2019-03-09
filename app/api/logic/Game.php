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

namespace app\api\logic;

use app\api\error\Game as GameError;

/**
 * 游戏接口逻辑
 */
class Game extends ApiBase
{
    
    /**
     * 角色信息
     */
    public function getRoles($param = [])
    {
        
        if (!is_numeric($param['member_id']) || !is_numeric($param['cp_server_id']) || empty($param['channel']) || empty($param['game_code'])) {
            
            return GameError::$paramError;
        }

        try {

            $model = model(ucwords($param['channel']), 'channel');

            return $model->role($param);

        } catch (Exception $ex) {

            return GameError::$getRolesError;
        }
    }
}
