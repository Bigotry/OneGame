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
 * 官网业务逻辑
 */
class Website extends IndexBase
{
    
    /**
     * 获取官网通用数据
     */
    public function getWebsiteCommonData($game_code = '')
    {
        
        $cache_key = 'cache_website_common_data_game_code_' . $game_code;
        
        $data = cache($cache_key);
        
        if (empty($data)) {
            
            $game_info = $this->modelWgGame->getInfo(['game_code' => $game_code]);
            
            if (empty($game_info)) {
                
                throw_response_exception('游戏不存在', 'html');
            }
            
            $game_info['website_intro_imgs'] = get_picture_array_url(str2arr($game_info['website_intro_imgs']));
            $game_info['website_screenshot'] = get_picture_array_url(str2arr($game_info['website_screenshot']));
            $game_info['website_job_imgs']   = get_picture_array_url(str2arr($game_info['website_job_imgs']));
            $game_info['game_logo_url']      = get_picture_url($game_info['game_logo']);
            $game_info['website_bg_img_url'] = get_picture_url($game_info['website_bg_img']);

            $recommend_server_list = $this->modelWgServer->getList(['game_id' => $game_info['id'], 'status' => DATA_NORMAL], true, 'start_time desc', false, [], '', 12);

            $all_server_list = $this->modelWgServer->getList(['game_id' => $game_info['id'], 'status' => DATA_NORMAL], true, 'start_time desc', false);

            $game_all_list = $this->modelWgGame->getList([], true, 'sort asc', false);

            $data['game_info']              = $game_info;
            $data['recommend_server_list']  = $recommend_server_list;
            $data['all_server_list']        = $all_server_list;
            $data['game_all_list']          = $game_all_list;
            
            cache($cache_key, $data, 60);
        }
        
        $uid = is_login();
        
        if (empty($uid)) {
            
            $login_member = [];
        } else {
            
            $login_member =  $this->modelMember->getInfo(['id' => $uid]);
        }
        
        $data['login_member'] = $login_member;
        
        return $data;
    }
    
    /**
     * 获取官网首页文章数据
     */
    public function getArticleData($info = [])
    {
        
        $article_config = config('website_article_category');
        
        $cache_key = 'cache_website_article_game_id_' . $info['id'];
        
        $data = cache($cache_key);
        
        if (!empty($data)) {
            
            return $data;
        }
        
        $notice     = $this->modelArticle->getList(['game_id' => $info['id'], 'category_id' => $article_config['notice']],      true, 'create_time desc', false, [], '', 6);
        
        $news       = $this->modelArticle->getList(['game_id' => $info['id'], 'category_id' => $article_config['news']],        true, 'create_time desc', false, [], '', 6);
        
        $strategy   = $this->modelArticle->getList(['game_id' => $info['id'], 'category_id' => $article_config['strategy']],    true, 'create_time desc', false, [], '', 6);
        
        $merge      = $this->modelArticle->getList(['game_id' => $info['id'], 'category_id' => $article_config['merge']],       true, 'create_time desc', false, [], '', 6);
        
        $novice     = $this->modelArticle->getList(['game_id' => $info['id'], 'category_id' => $article_config['novice']],      true, 'create_time desc', false, [], '', 6);
        
        $game       = $this->modelArticle->getList(['game_id' => $info['id'], 'category_id' => $article_config['game']],        true, 'create_time desc', false, [], '', 6);
        
        $superior   = $this->modelArticle->getList(['game_id' => $info['id'], 'category_id' => $article_config['superior']],    true, 'create_time desc', false, [], '', 6);
        
        $feature    = $this->modelArticle->getList(['game_id' => $info['id'], 'category_id' => $article_config['feature']],     true, 'create_time desc', false, [], '', 6);
        
        $synthesize = $this->modelArticle->getList(['game_id' => $info['id'], 'category_id' => ['in', [$article_config['notice'],$article_config['news'],$article_config['strategy'],$article_config['merge']]]],true, 'create_time desc', false, [], '', 6);
        
        $select_data = compact('notice','news','strategy','merge','novice','game','superior','feature','synthesize');
        
        cache($cache_key, $select_data, 60);
        
        return $select_data;
    }
    
    // 官网文章列表页
    public function getArticleListData($info = [], $param = [])
    {
        
        empty($param['cid']) && throw_response_exception('文章分类不存在', 'html');
        
        $article_list = $this->modelArticle->getList(['game_id' => $info['id']]);
        
        $category_info = $this->modelArticleCategory->getInfo(['id' => $param['cid']]);
        
        return compact('article_list','category_info');
    }
    
    // 官网文章详情页
    public function getArticleDetailsData($param = [])
    {
        
        empty($param['id']) && throw_response_exception('文章不存在', 'html');
        
        $article_info = $this->modelArticle->getInfo(['id' => $param['id']]);
        
        $category_info = $this->modelArticleCategory->getInfo(['id' => $article_info['category_id']]);
        
        return compact('article_info','category_info');
    }
}
