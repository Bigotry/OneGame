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
 * 文章验证器
 */
class Article extends AdminBase
{
    
    // 验证规则
    protected $rule =   [
        'name'          => 'require',
        'content'       => 'require',
        'category_id'   => 'require',
    ];

    // 验证提示
    protected $message  =   [
        'name.require'         => '文章标题不能为空',
        'content.require'      => '文章内容不能为空',
        'category_id.require'  => '文章分类必须选择',
    ];
    
    // 应用场景
    protected $scene = [
        'edit'  =>  ['name', 'content', 'category_id']
    ];
}
