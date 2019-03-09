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

namespace app\index\controller;

/**
 * 文章控制器
 */
class Article extends IndexBase
{
    
    // 文章首页
    public function index()
    {
        
        set_url();
        
        $this->setTitle('新闻资讯');
        
        $this->assign('data', $this->logicArticle->getArticleData($this->param));
        
        return $this->fetch('index');
    }
    
    // 文章详情
    public function details($id = 0)
    {
        
        set_url();
        
        $info = $this->logicArticle->getArticleInfo(['id' => $id]);
        
        $info['content'] = html_entity_decode($info['content']);
        
        $this->assign('info', $info);
        
        $this->setTitle('文章详情 - ' . $info['name']);
        
        return $this->fetch('details');
    }
}
