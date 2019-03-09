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

namespace app\api\controller;

/**
 * 文章接口控制器
 */
class Article extends ApiBase
{
    
    /**
     * 文章分类接口
     */
    public function categoryList()
    {
        
        return $this->apiReturn($this->logicArticle->getArticleCategoryList());
    }
    
    /**
     * 文章列表接口
     */
    public function articleList()
    {
        
        return $this->apiReturn($this->logicArticle->getArticleList($this->param));
    }
}
