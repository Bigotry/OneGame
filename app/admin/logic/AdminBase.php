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

use app\common\logic\LogicBase;
use think\Db;

/**
 * Admin基础逻辑
 */
class AdminBase extends LogicBase
{

    /**
     * 权限检测
     */
    public function authCheck($url = '', $url_list = [])
    {

        $pass_data = [RESULT_SUCCESS, '权限检查通过'];
        
        $allow_url = config('allow_url');
        
        $allow_url_list  = parse_config_attr($allow_url);
        
        if (IS_ROOT) {
            
            return $pass_data;
        }
        
        $s_url = strtolower($url);
        
        if (!empty($allow_url_list)) {
            
            foreach ($allow_url_list as $v) {
                
                if (strpos($s_url, strtolower($v)) !== false) {
                    
                    return $pass_data;
                }
            }
        }
        
        $result = in_array($s_url, array_map("strtolower", $url_list)) ? true : false;
        
        !('admin/index/index' == $s_url && !$result) ?: clear_login_session();
        
        return $result ? $pass_data : [RESULT_ERROR, '未授权操作'];
    }
    
    /**
     * 获取过滤后的菜单树
     */
    public function getMenuTree($menu_list = [], $url_list = [])
    {
        
        foreach ($menu_list as $key => $menu_info) {
            
            list($status, $message) = $this->authCheck(strtolower(MODULE_NAME . SYS_DS_PROS . $menu_info['url']), $url_list);
            
            [$message];
            
            if ((!IS_ROOT && RESULT_ERROR == $status) || !empty($menu_info['is_hide'])) {
                
                unset($menu_list[$key]);
            }
        }
        
        return $this->getListTree($menu_list);
    }
    
    /**
     * 获取列表树结构
     */
    public function getListTree($list = [])
    {
        
        if (is_object($list)) {
           
            $list = $list->toArray();
        }
        
        return list_to_tree(array_values($list), 'id', 'pid', 'child');
    }
    
    /**
     * 通过完整URL获取检查标准URL
     */
    public function getCheckUrl($full_url = '')
    {
        
        $temp_url = sr($full_url, URL_ROOT);

        $url_array_tmp = explode(SYS_DS_PROS, $temp_url); 
        
        if(strpos($url_array_tmp[3], '.')){

            $action_arr = explode('.', $url_array_tmp[3]); 

            $url_array_tmp[3] = $action_arr[0];
        }
        
        return $url_array_tmp[1] . SYS_DS_PROS . $url_array_tmp[2] . SYS_DS_PROS . $url_array_tmp[3];
    }
    
    /**
     * 过滤页面内容
     */
    public function filter($content = '', $url_list = [])
    {
        
        $results = [];
        
        preg_match_all('/<ob_link>.*?<\/ob_link>/', $content, $results);
        
        foreach ($results[0] as $a)
        {
            
            $match_results = []; 
            
            preg_match_all('/href="(.+?)"|url="(.+?)"/', $a, $match_results);
            
            $full_url = '';
            
            if (empty($match_results[1][0]) && empty($match_results[2][0])) {
                
                continue;
            } elseif (!empty($match_results[1][0])) {
                
                $full_url = $match_results[1][0];
            } else {
                
                $full_url = $match_results[2][0];
            }
            
            if (!empty($full_url)) {
               
                $result = $this->authCheck($this->getCheckUrl($full_url), $url_list);

                $result[0] != RESULT_SUCCESS && $content = sr($content, $a);
            }
        }
        
        return $content;
    }
    
    /**
     * 最近1年的月份统计数据
     */
    public function getIndexData()
    {
        
        $create_month_list = Db::name('wg_player')->group('create_month')->order('create_month desc')->limit(12)->field('create_month')->select();
        
        krsort($create_month_list);
        
        $month_array = array_extract(array_values($create_month_list), 'create_month');
        
        $role_map = [];
        
        if(check_group(MEMBER_ID, config('auth_group_id_manage'))) {
            
            $conference_info = $this->modelWgConference->getInfo(['member_id' => MEMBER_ID]);
            
            $role_map['conference_id'] = $conference_info['id'];
        }
        
        if(check_group(MEMBER_ID, config('auth_group_id_employee'))) { $role_map['c_member_id'] = MEMBER_ID; }
        
        if(check_group(MEMBER_ID, config('auth_group_id_agency'))) {
            
            $conference_list = $this->modelWgConference->getList(['source_member_id' => MEMBER_ID], 'id', '', false);
            
            $conference_list_ids = array_extract($conference_list);
            
            !empty($conference_list_ids) ? $role_map['conference_id'] = ['in', $conference_list_ids] : $role_map['conference_id'] = -1;
        }

        $cache_key = "admin_index_stat_".date("Ymd").'_'.md5(serialize($role_map));
        
        $cache_data = cache($cache_key);
        
        if (!empty($cache_data)) {
            
            return $cache_data;
        }
        
        $data = [];
        
        foreach ($month_array as $month)
        {
            
            $map = $role_map;
            $map['status']       = DATA_NORMAL;
            $map['create_month'] = $month;
            
            $register_number = $this->modelWgPlayer->stat($map);
            
            $register_ip_number = Db::name('wg_player')->where($map)->group('login_ip')->count('id');
            
            $map['pay_status']      = DATA_NORMAL;
            $map['order_status']    = DATA_NORMAL;
            $map['is_admin']        = DATA_DISABLE;
            
            $pay_number = Db::name('wg_order')->where($map)->group('member_id')->count('id');
            
            $pay_money = Db::name('wg_order')->where($map)->sum('order_money');
            
            empty($pay_money) && $pay_money = 0.00;
            
            $data['month'][]                  = $month;
            $data['register_number'][]        = $register_number;
            $data['register_ip_number'][]     = $register_ip_number;
            $data['pay_number'][]             = $pay_number;
            $data['pay_money'][]              = $pay_money;
        }
        
        empty($data['month'])               && $data['month'][] = 0;
        empty($data['register_number'])     && $data['register_number'][] = 0;
        empty($data['register_ip_number'])  && $data['register_ip_number'][] = 0;
        empty($data['pay_number'])          && $data['pay_number'][] = 0;
        empty($data['pay_money'])           && $data['pay_money'][] = 0;
        
        $return_data = array_map("json_encode", $data);
        
        !empty($return_data) && cache($cache_key, $return_data, 86400);
        
        return $return_data;
    }
    
    /**
     * 数据状态设置
     */
    public function setStatus($model = null, $param = null)
    {
        
        if (empty($model) || empty($param)) {
           
            return [RESULT_ERROR, '非法操作'];
        }
        
        $status = (int)$param[DATA_STATUS_NAME];
        
        $model_str = LAYER_MODEL_NAME . $model;
        
        $obj = $this->$model_str;
        
        is_array($param['ids']) ? $ids = array_extract((array)$param['ids'], 'value') : $ids[] = (int)$param['ids'];
        
        $result = $obj->setFieldValue(['id' => ['in', $ids]], DATA_STATUS_NAME, $status);
        
        $result && action_log('数据状态', '数据状态调整' . '，model：' . $model . '，ids：' . arr2str($ids) . '，status：' . $status);
        
        return $result ? [RESULT_SUCCESS, '操作成功'] : [RESULT_ERROR, $obj->getError()];
    }
    
    /**
     * 数据排序设置
     */
    public function setSort($model = null, $param = null)
    {
        
        $model_str = LAYER_MODEL_NAME . $model;
        
        $obj = $this->$model_str;
        
        $result = $obj->setFieldValue(['id' => (int)$param['id']], 'sort', (int)$param['value']);
        
        $result && action_log('数据排序', '数据排序调整' . '，model：' . $model . '，id：' . $param['id'] . '，value：' . $param['value']);
        
        return $result ? [RESULT_SUCCESS, '操作成功'] : [RESULT_ERROR, $obj->getError()];
    }
    
    /**
     * 快捷操作
     */
    public function speedySetValue($model = '', $id = 0, $field = '', $value = 0)
    {
        
        if (empty($model) || empty($field)) {
           
            return [RESULT_ERROR, '非法操作'];
        }
        
        $model_str = LAYER_MODEL_NAME . $model;
        
        $obj = $this->$model_str;
        
        $result = $obj->setFieldValue(['id' => (int)$id], $field, $value);
        
        return $result ? [RESULT_SUCCESS, '操作成功'] : [RESULT_ERROR, $obj->getError()];
    }
}
