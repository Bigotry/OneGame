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
 * 礼包中心逻辑
 */
class Gift extends IndexBase
{
    
    /**
     * 获取首页数据
     */
    public function getGiftData($param = [])
    {
        
        $data['game_list'] = $this->getGameList();
        
        $data['gift_list'] = $this->getGiftList($param);

        return $data;
    }
    
    /**
     * 获取礼包详情数据
     */
    public function getGiftDetailsData($param = [])
    {
        
        $data['gift_info']  = $this->getGiftInfo($param);
        
        $data['game_info']  = $this->modelWgGame->getInfo(['id' => $data['gift_info']['game_id']]);
        
        $data['other_gift'] = $this->getOtherGift($param, $data['gift_info']['game_id']);
        
        return $data;
    }
    
    /**
     * 其他礼包
     */
    public function getOtherGift($param = [], $game_id = 0)
    {
        
        $this->modelWgGift->alias('gi');
        
        $join = [
                    [SYS_DB_PREFIX . 'wg_game ga', 'gi.game_id = ga.id'],
                ];
        
        $where['gi.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
        
        $where['gi.id']         = ['neq', $param['id']];
        $where['gi.game_id']    = $game_id;
        
        $other_list = $this->modelWgGift->getList($where, 'gi.*,ga.game_head', 'gi.create_time desc', false, $join, null, 8);
        
        foreach ($other_list as &$v)
        {
            
            $inventory_number = $this->modelWgGiftKey->stat(['gift_id' => $v['id']]);
            
            if (empty($inventory_number)) {
                
                $v['inventory_number'] = 0;
            } else {
                
                $v['inventory_number'] = $inventory_number;
            }
        }
        
        return $other_list;
    }
    
    /**
     * 礼包信息
     */
    public function getGiftInfo($param = [])
    {
        
        $gift_info = $this->modelWgGift->getInfo(['id' => $param['id']]);
        
        if (empty($gift_info)) {

            throw_response_exception('礼包不存在', 'html');
        }
        
        $gift_map['is_get']  = DATA_DISABLE;
        $gift_map['gift_id'] = $param['id'];
        
        $inventory_number = $this->modelWgGiftKey->stat($gift_map);
        $all_inventory_number = $this->modelWgGiftKey->stat(['gift_id' => $param['id']]);
        
        if (empty($inventory_number) || empty($all_inventory_number)) {
            
            $inventory_number = 0;
            $percent = 0;
            
        } else {
            
            $percent = ceil(($inventory_number/$all_inventory_number)*100);
        }
        
        $gift_info['inventory_number'] = $inventory_number;
        $gift_info['percent'] = $percent;
        
        return $gift_info;
    }
    
    /**
     * 游戏列表
     */
    public function getGameList()
    {
        
        $where[DATA_STATUS_NAME]      = ['neq', DATA_DELETE];
        
        return $this->modelWgGame->getList($where, 'id,game_name', 'sort desc,create_time desc', false);
    }
    
    /**
     * 领取礼包
     */
    public function getGift($param = [])
    {
        
        $mid = is_login();
        
        if (empty($mid)) { return [RESULT_ERROR, '请先登录后再领取']; }
        
        $key_where['is_get']   = DATA_DISABLE;
        $key_where['gift_id']  = $param['id'];
        
        $info = $this->modelWgGiftKey->getInfo($key_where);
        
        if (empty($info)) { return [RESULT_ERROR, '礼包已经领完啦'];}
        
        $exist_map['gift_id']   = $param['id'];
        $exist_map['member_id'] = $mid;
        
        $exist_info = $this->modelWgGiftKey->getInfo($exist_map);
        
        if (!empty($exist_info)) { return [RESULT_ERROR, '您已经领取过此礼包啦'];}
        
        $data['member_id'] = $mid;
        $data['is_get'] = 1;
        $data['get_time'] = time();
        
        $update_res = $this->modelWgGiftKey->updateInfo(['id' => $info['id'], 'is_get' => DATA_DISABLE], $data);
        
        if (!$update_res) { return [RESULT_ERROR, '系统繁忙，请稍后再试']; }
        
        return [RESULT_SUCCESS, $info['key']];
    }
    
    /**
     * 礼包列表
     */
    public function getGiftList($param = [])
    {
        
        $this->modelWgGift->alias('gi');
        
        $join = [ [SYS_DB_PREFIX . 'wg_game ga', 'ga.id = gi.game_id'] ];
        
        $where['gi.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
        
        $field = 'gi.id,gi.gift_name,gi.gift_describe,gi.create_time,ga.game_logo,ga.game_head,ga.game_code';
        
        !empty($param['gid'])         && $where['gi.game_id']   = (int)$param['gid'];
        !empty($param['keyword'])     && $where['gi.gift_name']    = ['like','%'.(string)$param['keyword'].'%'];
        
        $list = $this->modelWgGift->getList($where, $field, 'gi.create_time desc', 5, $join);
        
        $list_ids = array_extract($list);
        
        $key_where['is_get']   = DATA_DISABLE;
        $key_where['gift_id']  = ['in', $list_ids];
        
        $list_number = $this->modelWgGiftKey->where($key_where)->group("gift_id")->field("gift_id,count('id') as number")->select();
        
        foreach ($list as &$info)
        {
            foreach ($list_number as $number_info)
            {
                $info['id'] != $number_info['gift_id'] ?: $info['number'] = $number_info['number'];
            }
        }
        
        return $list;
    }
    
}
