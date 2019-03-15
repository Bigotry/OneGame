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

// 前端配置文件

empty(STATIC_DOMAIN) ? $static = [] :  $static['__STATIC__'] = STATIC_DOMAIN . SYS_DS_PROS . SYS_STATIC_DIR_NAME;

return [
    
    'template' => ['layout_on' =>  true, 'layout_name' => 'layout'],
    
    // 视图输出字符串内容替换
    'view_replace_str' => $static,
    
    // 需记录上一步的URL列表
    'forward_url_list' => [
        'index/index',
        'game/index',
        'gift/index',
        'gift/details',
        'article/index',
        'article/details',
        'website/index',
        'website/articlelist',
        'website/articledetails',
        'play/index',
        'center/pay',
        'center/index',
        'mgame/index',
        'mgame/play',
    ],
    
    // 单页文章配置
    'single_article' => [
        'about'         => 1,  //关于我们
        'contact'       => 2,  //联系我们
        'recruit'       => 3,  //诚聘英才
        'cooperation'   => 4,  //商务合作
        'tutelage'      => 5,  //家长监护
    ],
    
    // 官网文章分类ID配置
    'website_article_category' => [
        'notice'        => 10,  //公告
        'news'          => 7,   //新闻资讯
        'strategy'      => 9,   //攻略
        'merge'         => 17,  //合区
        'novice'        => 12,  //新手指南
        'game'          => 13,  //游戏指南
        'superior'      => 14,  //高手进阶
        'feature'       => 15,  //特色系统
    ],
];
