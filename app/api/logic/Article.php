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

namespace app\api\logic;

use app\common\logic\Article as CommonArticle;

/**
 * 文章接口逻辑
 */
class Article extends ApiBase
{
    
    public static $commonArticleLogic = null;
    
    /**
     * 基类初始化
     */
    public function __construct()
    {
        // 执行父类构造方法
        parent::__construct();
        
        empty(static::$commonArticleLogic) && static::$commonArticleLogic = get_sington_object('Article', CommonArticle::class);
    }
    
    /**
     * 获取文章分类列表
     */
    public function getArticleCategoryList()
    {
        
        return static::$commonArticleLogic->getArticleCategoryList([], 'id,name', 'id desc', false);
    }
    
    /**
     * 获取文章列表
     */
    public function getArticleList($data = [])
    {
        
        $where = [];
        
        !empty($data['category_id']) && $where['a.category_id'] = $data['category_id'];
        
        return static::$commonArticleLogic->getArticleList($where, 'a.id,a.name,a.category_id,a.describe,a.create_time', 'a.create_time desc');
    }
    
    /**
     * 获取文章信息
     */
    public function getArticleInfo($data = [])
    {
        
        $info = static::$commonArticleLogic->getArticleInfo(['a.id' => $data['article_id']], 'a.*,m.nickname,c.name as category_name');
        
        $info['content'] = html_entity_decode($info['content'] );
        
        return $info;
    }
}
