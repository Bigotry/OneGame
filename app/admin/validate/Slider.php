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
 * 轮播验证器
 */
class Slider extends AdminBase
{
    
    // 验证规则
    protected $rule =   [
        
        'name'              => 'require|unique:slider',
        'url'               => 'require',
        'sort'              => 'require|number',
    ];

    // 验证提示
    protected $message  =   [
        
        'name.require'              => '轮播名称不能为空',
        'name.unique'               => '轮播名称已存在',
        'url.require'               => '轮播URL不能为空',
        'sort.require'              => '排序值不能为空',
        'sort.number'               => '排序值必须为数字'
    ];

    // 应用场景
    protected $scene = [
        
        'edit' =>  ['name','url','sort'],
    ];
    
}
