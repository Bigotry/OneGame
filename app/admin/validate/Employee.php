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
 * 员工管理验证器
 */
class Employee extends AdminBase
{
    
    // 验证规则
    protected $rule =   [
        
        'username'           => 'require|unique:member',
        'password'           => 'require',
    ];

    // 验证提示
    protected $message  =   [
        
        'username.require'            => '用户名不能为空',
        'username.unique'             => '用户名已经存在',
        'password.require'            => '密码不能为空',
    ];

    // 应用场景
    protected $scene = [
        
        'add'  => ['username','password']
    ];
    
}
