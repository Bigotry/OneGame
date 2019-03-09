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
 * 礼包验证器
 */
class Gift extends AdminBase
{
    
    // 验证规则
    protected $rule =   [
        
        'gift_name'     => 'require|unique:wg_gift',
        'key'           => 'require|unique:wg_gift_key',
    ];

    // 验证提示
    protected $message  =   [
        
        'gift_name.require'     => '礼包名称不能为空',
        'gift_name.unique'      => '礼包名称已经存在',
        'key.require'           => '礼包KEY不能为空',
        'key.unique'            => '礼包KEY已经存在',
    ];

    // 应用场景
    protected $scene = [
        
        'edit'      =>  ['gift_name'],
        'add_key'   =>  ['key'],
    ];
    
}
