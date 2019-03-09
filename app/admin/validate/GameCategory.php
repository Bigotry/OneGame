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
 * 游戏分类验证器
 */
class GameCategory extends AdminBase
{
    
    // 验证规则
    protected $rule =   [
        
        'category_name' => 'require|unique:wg_category',
    ];

    // 验证提示
    protected $message  =   [
        
        'category_name.require' => '游戏分类名称不能为空',
        'category_name.unique'  => '游戏分类名称已存在',
    ];

    // 应用场景
    protected $scene = [
        
        'edit' =>  ['category_name'],
    ];
    
}
