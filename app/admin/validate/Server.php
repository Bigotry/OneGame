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

namespace app\admin\validate;

/**
 * 游戏区服验证器
 */
class Server extends AdminBase
{
    
    // 验证规则
    protected $rule =   [
        
        'server_name'   => 'require',
        'cp_server_id'  => 'require|number',
    ];

    // 验证提示
    protected $message  =   [
        
        'server_name.require'   => '区服名称不能为空',
        'cp_server_id.require'  => '服务器ID不能为空',
        'cp_server_id.number'   => '服务器ID必须为数字',
    ];

    // 应用场景
    protected $scene = [
        
        'edit' =>  ['server_name','cp_server_id'],
    ];
    
}
