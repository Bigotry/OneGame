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

namespace app\index\validate;

/**
 * 注册验证器
 */
class Register extends IndexBase
{
    
    // 验证规则
    protected $rule =   [
        
        'username'  => 'require|length:6,20|alphaNum|unique:member',
        'password'  => 'require|confirm|length:6,20',
    ];
    
    // 验证提示
    protected $message  =   [
        
        'username.require'   => '用户名不能为空',
        'username.length'    => '用户名长度为6-20位',
        'username.alphaNum'  => '用户名只能包含字母与数字',
        'username.unique'    => '用户名已经存在',
        'password.require'   => '密码不能为空',
        'password.confirm'   => '两次密码输入不一致',
    ];

    // 应用场景
    protected $scene = [
        
        'index'   =>  ['username','password'],
    ];
}
