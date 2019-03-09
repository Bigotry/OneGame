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
 * 公会信息验证器
 */
class Conference extends AdminBase
{
    
    // 验证规则
    protected $rule =   [
        
        'conference_name'    => 'require|unique:wg_conference',
        'contact_name'       => 'require',
        'contact_mobile'     => 'require',
        'account_holder'     => 'require',
        'opening_bank'       => 'require',
        'bank_account'       => 'require',
        'username'           => 'require|unique:member',
        'password'           => 'require|confirm',
        'ratio'              => 'require|between:0,100'
    ];

    // 验证提示
    protected $message  =   [
        
        'conference_name.require'     => '公会名称不能为空',
        'conference_name.unique'      => '公会名称已经存在',
        'contact_name.require'        => '联系人不能为空',
        'contact_mobile.require'      => '联系电话不能为空',
        'account_holder.require'      => '开户人不能为空',
        'opening_bank.require'        => '开户行不能为空',
        'bank_account.require'        => '银行账户不能为空',
        'username.require'            => '用户名不能为空',
        'username.unique'             => '用户名已经存在',
        'password.require'            => '密码不能为空',
        'password.confirm'            => '两次密码输入不一致',
        'ratio.require'               => '分成比例不能为空',
        'ratio.between'               => '分成比例数据不正确',
    ];

    // 应用场景
    protected $scene = [
        
        'add'  => ['conference_name','contact_name','contact_mobile','account_holder','opening_bank','bank_account','username','password','ratio'],
        'edit' => ['conference_name','contact_name','contact_mobile','account_holder','opening_bank','bank_account','ratio']
    ];
    
}
