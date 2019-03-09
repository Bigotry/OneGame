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

use app\common\logic\LogicBase;

/**
 * Index基础逻辑
 */
class IndexBase extends LogicBase
{
    
    /**
     * 获取通用数据
     */
    public function getCommonData()
    {
        
        $cache_key = 'cache_index_common_data';
        
        $data = cache($cache_key);
        
        if (empty($data)) {
            
            $web_site_logo_url = get_picture_url(config('web_site_logo'));

            $link_list = $this->modelBlogroll->getList([], true, 'sort desc', false);

            $this->modelWgGame->alias('g');

            $join = [
                        [SYS_DB_PREFIX . 'wg_category c', 'g.game_category_id = c.id'],
                    ];

            $where['g.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
            $where['g.maintain_end_time']   = ['elt', TIME_NOW];
            $where['g.is_hot']              = ['eq', DATA_NORMAL];

            $field = 'g.id,g.game_logo,g.game_name,g.game_code,g.game_head,g.game_cover,g.endways_cover,c.category_name';

            $hot_game_list = $this->modelWgGame->getList($where, $field, 'g.sort desc', false, $join, null, 15);

            $data['web_site_logo_url']  = $web_site_logo_url;
            $data['link_list']          = $link_list;
            $data['hot_game_list']      = $hot_game_list;
            
            cache($cache_key, $data, 60);
        }
        
        $member_id = is_login();
        
        $member_id && $data['login_member_info'] = $this->modelMember->getInfo(['id' => $member_id], 'id,nickname,username,email,mobile');
        
        return $data;
    }

}
