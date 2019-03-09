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

namespace app\index\logic;

/**
 * 文章逻辑
 */
class Article extends IndexBase
{
    
    /**
     * 获取首页数据
     */
    public function getArticleData($param = [])
    {
        
        $data['category_list'] = $this->getArticleCategoryList();
        
        $data['article_list']  = $this->getArticleList($param);
        
        return $data;
    }
    
    /**
     * 获取文章详情
     */
    public function getArticleInfo($where = [], $field = true)
    {
        
        $info = $this->modelArticle->getInfo($where, $field);
        
        if (empty($info)) {

            throw_response_exception('文章不存在', 'html');
        }
        
        return $info;
    }
    
    /**
     * 获取文章数据
     */
    public function getArticleList($param = [])
    {
        
        $where[DATA_STATUS_NAME]      = ['neq', DATA_DELETE];
        
        !empty($param['cid']) && $where['category_id'] = $param['cid'];
        
        return $this->modelArticle->getList($where, 'id,category_id,name,create_time', 'create_time desc');
    }
    
    /**
     * 获取分类数据
     */
    public function getArticleCategoryList()
    {
        
        $where[DATA_STATUS_NAME]      = ['neq', DATA_DELETE];
        
        return $this->modelArticleCategory->getList($where, 'id,name', 'id', false);
    }
}
