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

namespace app\admin\logic;

use app\index\logic\Index;
use think\Db;

/**
 * 游戏信息相关逻辑
 */
class Game extends AdminBase
{
    
    /**
     * 游戏分类列表
     */
    public function getCategoryList($where = [], $field = true, $order = '', $paginate = 0)
    {
        
        return $this->modelWgCategory->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 获取分类信息
     */
    public function getCategoryInfo($where = [], $field = true)
    {
        
        return $this->modelWgCategory->getInfo($where, $field);
    }
    
    /**
     * 分类信息编辑
     */
    public function categoryEdit($data = [])
    {
        
        $validate_result = $this->validateGameCategory->scene('edit')->check($data);
        
        if (!$validate_result) : return [RESULT_ERROR, $this->validateGameCategory->getError()]; endif;
        
        $result = $this->modelWgCategory->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, '游戏分类' . $handle_text . '，category_name：' . $data['category_name']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', url('categoryList')] : [RESULT_ERROR, $this->modelWgCategory->getError()];
    }
    
    /**
     * 分类信息删除
     */
    public function categoryDel($where = [])
    {
        
        $result = $this->modelWgCategory->deleteInfo($where);
        
        $result && action_log('删除', '游戏分类删除' . '，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '删除成功'] : [RESULT_ERROR, $this->modelWgCategory->getError()];
    }
    
    /**
     * 游戏列表
     */
    public function getGameList($where = [], $field = 'g.*,c.category_name', $order = 'g.create_time desc', $paginate = 0)
    {
        
        $this->modelWgGame->alias('g');
        
        $join = [
                    [SYS_DB_PREFIX . 'wg_category c', 'c.id = g.game_category_id'],
                ];
        
        $where['g.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
        
        return $this->modelWgGame->getList($where, $field, $order, $paginate, $join);
    }
    
    /**
     * 获取游戏信息
     */
    public function getGameInfo($where = [], $field = true)
    {
        
        $info = $this->modelWgGame->getInfo($where, $field);
        
        !empty($info['website_intro_imgs']) && $info['website_intro_imgs_array'] = str2arr($info['website_intro_imgs']);
        !empty($info['website_screenshot']) && $info['website_screenshot_array'] = str2arr($info['website_screenshot']);
        !empty($info['website_job_imgs'])   && $info['website_job_imgs_array']   = str2arr($info['website_job_imgs']);
        !empty($info['maintain_end_time'])  ?  $info['maintain_end_time']        = format_time($info['maintain_end_time'], 'Y-m-d H:i') : $info['maintain_end_time'] = '';
        
        return $info;
    }
    
    /**
     * 游戏信息编辑
     */
    public function gameEdit($data = [])
    {
        
        $validate_result = $this->validateGame->scene('edit')->check($data);
        
        if (!$validate_result) : return [RESULT_ERROR, $this->validateGame->getError()]; endif;
        
        !empty($data['maintain_end_time']) && $data['maintain_end_time'] = strtotime($data['maintain_end_time']);
        
        $result = $this->modelWgGame->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, '游戏信息' . $handle_text . '，id：' . $data['id']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', url('gameList')] : [RESULT_ERROR, $this->modelWgGame->getError()];
    }
    
    /**
     * 游戏删除
     */
    public function gameDel($where = [])
    {
        
        $result = $this->modelWgGame->deleteInfo($where);
        
        $result && action_log('删除', '游戏信息删除' . '，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '删除成功'] : [RESULT_ERROR, $this->modelWgGame->getError()];
    }
    
    
    /**
     * 获取区服列表
     */
    public function getServerList($where = [], $field = 's.*,g.game_name', $order = 's.create_time desc', $paginate = 0)
    {
        
        
        $this->modelWgServer->alias('s');
        
        $join = [
                    [SYS_DB_PREFIX . 'wg_game g', 's.game_id = g.id'],
                ];
        
        $where['s.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
        
        return $this->modelWgServer->getList($where, $field, $order, $paginate, $join);
    }
    
    /**
     * 获取区服选择项文本
     */
    public function getServerOptions($where = [])
    {
        
        $list = $this->modelWgServer->getList($where, true, 'create_time desc', false);
        
        $text = '<option value="0">不限区服</option>';
        
        foreach ($list as $info)
        {
            
            $text .= '<option value="'.$info['id'].'">' . $info['server_name'] . '</option>';
        }
        
        return $text;
    }
    
    /**
     * 获取最新角色选择项文本
     */
    public function getNewRoleOptions($role_id = 0)
    {
        
        $order_info = $this->modelWgOrder->getInfo(['role_id' => $role_id], 'id,role_id,member_id,game_id,server_id');
        
        if (empty($order_info)) {
            
            return "<option value=''>请选择角色</option>";
        }
        
        $index_logic = new Index();
        
        $text = $index_logic->getRoleOption($order_info['game_id'], $order_info['server_id'], $order_info['member_id']);
        
        return $text;
    }
    
    /**
     * 更新角色信息
     */
    public function updateRole($id = 0, $new_role_id = 0)
    {
        
        if (empty($id) || empty($new_role_id)) {
            
            return [RESULT_ERROR, '参数错误'];
        }
        
        $order_info = $this->modelWgOrder->getInfo(['id' => $id], 'id,role_id,member_id,game_id,server_id,conference_id,c_member_id');
        
        $game_info = $this->modelWgGame->getInfo(['id' => $order_info['game_id']]);
        
        $driver = SYS_DRIVER_DIR_NAME . ucfirst($game_info['game_code']);
        
        $server_info = $this->modelWgServer->getInfo(['id' => $order_info['server_id']]);
        
        $roles = $this->serviceWebgame->$driver->roles($order_info['member_id'], $server_info['cp_server_id']);
        
        $role_name = '';
        $role_level = '';
        
        foreach ($roles as $r)
        {
            if ($r['role_id'] == $new_role_id) {
                
                $role_name  = $r['nickname'];
                $role_level = $r['level'];
                break;
            }
        }
        
        $old_role_id = $order_info['role_id'];
        
        if ($new_role_id == $old_role_id) {
            
            $data = [];
            
            $data['game_id']        = $order_info['game_id'];
            $data['server_id']      = $order_info['server_id'];
            $data['member_id']      = $order_info['member_id'];
            $data['game_id']        = $order_info['game_id'];
            $data['conference_id']  = $order_info['conference_id'];
            $data['c_member_id']    = $order_info['c_member_id'];
            
            $player_id = Db::name('wg_player')->where($data)->value('id');
            
            if (empty($player_id)) {
                
                $data['create_date']    = date("Y-m-d");
                $data['create_month']   = date("Y-m");
                $data['create_time']    = time();
                
                $p_id = Db::name('wg_player')->insertGetId($data);
                
                $role_id = Db::name('wg_role')->where(['role_id' => $old_role_id])->value('id');
                
                if (empty($role_id)) {
                    
                    $role_data = [];
                    
                    $role_data['player_id'] = $p_id;
                    $role_data['role_id'] = $new_role_id;
                    $role_data['role_level'] = $role_level;
                    $role_data['role_name'] = $role_name;
                    $role_data['create_time'] = time();
                    
                    Db::name('wg_role')->insert($role_data);
                } else {
                    
                    Db::name('wg_role')->where(['role_id' => $old_role_id])->update(['role_id' => $new_role_id, 'role_name' => $role_name]);
                }
                
                return [RESULT_SUCCESS, '更新成功'];
            } else {
                
                $role_id = Db::name('wg_role')->where(['role_id' => $old_role_id])->value('id');
                
                if (empty($role_id)) {
                    
                    $role_data = [];
                    
                    $role_data['player_id'] = $player_id;
                    $role_data['role_id'] = $new_role_id;
                    $role_data['role_level'] = $role_level;
                    $role_data['role_name'] = $role_name;
                    $role_data['create_time'] = time();
                    
                    Db::name('wg_role')->insert($role_data);
                } else {
                    
                    Db::name('wg_role')->where(['role_id' => $old_role_id])->update(['role_id' => $new_role_id, 'role_name' => $role_name]);
                }
                
                return [RESULT_SUCCESS, '更新成功'];
            }
            
//            return [RESULT_ERROR, '当前已是此角色'.$player_id];
        }
        
        // 事务
        $func = function () use ($old_role_id, $new_role_id, $role_name) {

                Db::name('wg_order')->where(['role_id' => $old_role_id])->update(['role_id' => $new_role_id]);
                Db::name('wg_role')->where(['role_id' => $old_role_id])->update(['role_id' => $new_role_id, 'role_name' => $role_name]);
            };

        $result = closure_list_exe([$func]);
        
        $result && action_log('更新角色', '更新角色信息，member_id：'.$order_info['member_id'] . '，old_role_id：'.$old_role_id . '，'.'new_role_id：'.$new_role_id);

        return $result ? [RESULT_SUCCESS, '更新成功'] : [RESULT_ERROR, '更新失败'];
    }
    
    /**
     * 获取区服信息
     */
    public function getServerInfo($where = [], $field = true)
    {
        
        $info = $this->modelWgServer->getInfo($where, $field);
        
        !empty($info['maintain_end_time'])  ?  $info['maintain_end_time']   = format_time($info['maintain_end_time'], 'Y-m-d H:i')  : $info['maintain_end_time']    = '';
        !empty($info['start_time'])         ?  $info['start_time']          = format_time($info['start_time'], 'Y-m-d H:i')         : $info['start_time']           = '';
        
        return $info;
    }
    
    /**
     * 区服信息编辑
     */
    public function serverEdit($data = [])
    {
        
        $validate_result = $this->validateServer->scene('edit')->check($data);
        
        if (!$validate_result) : return [RESULT_ERROR, $this->validateServer->getError()]; endif;
        
        !empty($data['maintain_end_time'])  && $data['maintain_end_time']   = strtotime($data['maintain_end_time']);
        !empty($data['start_time'])         && $data['start_time']          = strtotime($data['start_time']);
        
        $result = $this->modelWgServer->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, '游戏区服' . $handle_text . '，server_name：' . $data['server_name']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', url('serverList')] : [RESULT_ERROR, $this->modelWgServer->getError()];
    }
    
    /**
     * 区服信息删除
     */
    public function serverDel($where = [])
    {
        
        $result = $this->modelWgServer->deleteInfo($where);
        
        $result && action_log('删除', '区服删除' . '，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '删除成功'] : [RESULT_ERROR, $this->modelWgServer->getError()];
    }
}
