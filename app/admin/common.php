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

use think\Db;
use app\admin\logic\Log as LogicLog;

/**
 * 记录行为日志
 */
function action_log($name = '', $describe = '')
{

    $logLogic = get_sington_object('logLogic', LogicLog::class);
    
    $logLogic->logAdd($name, $describe);
}

/**
 * 清除登录 session
 */
function clear_login_session()
{
    
    session('member_info',      null);
    session('member_auth',      null);
    session('member_auth_sign', null);
}


/**
 * 检查会员是否属于某权限组
 */
function check_group($member_id = 0, $group_id = 0)
{
    
    $info = Db::name('auth_group_access')->where(['member_id' => $member_id, 'group_id' => $group_id])->find();
    
    return empty($info) ? false : true;
}