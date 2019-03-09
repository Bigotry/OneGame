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
 * SEO验证器
 */
class Seo extends AdminBase
{
    
    // 验证规则
    protected $rule =   [
        
        'name'              => 'require|unique:seo',
        'url'               => 'require|unique:seo',
        'seo_title'         => 'require',
        'seo_keywords'      => 'require',
        'seo_description'   => 'require',
        'sort'              => 'require|number',
    ];

    // 验证提示
    protected $message  =   [
        
        'name.require'              => '名称不能为空',
        'name.unique'               => '名称已存在',
        'url.require'               => 'URL不能为空',
        'url.unique'                => 'URL已存在',
        'seo_title.require'         => 'SEO标题不能为空',
        'seo_keywords.require'      => 'SEO关键字不能为空',
        'seo_description.require'   => 'SEO描述不能为空',
        'sort.require'              => '排序值不能为空',
        'sort.number'               => '排序值必须为数字'
    ];

    // 应用场景
    protected $scene = [
        
        'edit' =>  ['name','url','seo_title','seo_keywords','seo_description','sort'],
    ];
    
}
