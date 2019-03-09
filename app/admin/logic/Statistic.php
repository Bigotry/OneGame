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

/**
 * 统计分析逻辑
 */
class Statistic extends AdminBase
{
    
    /**
     * 会员增长
     */
    public function memberGrowth()
    {
        
        $cache_data = cache('cache_member_growth_data');
        
        if (!empty($cache_data)) {
            
            return $cache_data;
        }
        
        $data = $this->getMemberGrowthStruct();

        $list = $this->getMemberGrowthData($data);

        $result = $this->formatMemberGrowthData($data, $list);
        
        // 缓存一天
        cache('cache_member_growth_data', $result, 86400);
        
        return $result;
    }
    
    /**
     * 格式化会员统计数据
     */
    public function formatMemberGrowthData($data, $list)
    {
        
        foreach ($list as $v) {
            
            switch (date("Y-m-d",strtotime($v[TIME_CT_NAME]))) {
                case $data[0][0]    : $data[0][1]++;  break;
                case $data[1][0]    : $data[1][1]++;  break;
                case $data[2][0]    : $data[2][1]++;  break;
                case $data[3][0]    : $data[3][1]++;  break;
                case $data[4][0]    : $data[4][1]++;  break;
                case $data[5][0]    : $data[5][1]++;  break;
                case $data[6][0]    : $data[6][1]++;  break;
                case $data[7][0]    : $data[7][1]++;  break;
                case $data[8][0]    : $data[8][1]++;  break;
                case $data[9][0]    : $data[9][1]++;  break;
                case $data[10][0]   : $data[10][1]++; break;
                case $data[11][0]   : $data[11][1]++; break;
                case $data[12][0]   : $data[12][1]++; break;
                case $data[13][0]   : $data[13][1]++; break;
                case $data[14][0]   : $data[14][1]++; break;
            }
        }
        
        return $data;
    }
    
    /**
     * 获取会员增长数据15天数据
     */
    public function getMemberGrowthData($data)
    {
        
        $s_time = strtotime($data[0][0]);
        $e_time = strtotime($data[14][0]) + 86400 - 1;
        
        $where['status']      = DATA_NORMAL;
        $where['is_inside']   = DATA_DISABLE;
        $where['create_time'] = [['elt', $e_time], ['egt', $s_time]];
        
        $list = $this->modelMember->getList($where, 'id,create_time,is_inside,status', '', false);
        
        return $list;
    }
    
    /**
     * 获取会员增长数据15天结构
     */
    public function getMemberGrowthStruct()
    {
        
        $s_date = date("Y-m-d",strtotime("-15 day"));
        $e_date = date("Y-m-d",strtotime("-1 day"));
        
        $date_array = get_date_from_range($s_date, $e_date);
        
        $data_struct = [];
        
        foreach ($date_array as $v) {
            
            $data_struct[] = [$v, DATA_DISABLE];
        }
        
        return $data_struct;
    }
    
    /**
     * 执行速度
     */
    public function exeSpeed()
    {
        
        $cache_data = cache('cache_exe_speed_data');
        
        if (!empty($cache_data)) { return $cache_data; }
        
        // 取最近的1万条执行记录进行速度分析
        $list = $this->modelExeLog->getList(['status' => DATA_NORMAL], 'id,exe_time', 'id desc', false, [], '', 10000);

        $data = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0];

        foreach ($list as $v)
        {
            switch (true)
            {
                case $v['exe_time'] > 5     : $data[7]++; break;
                case $v['exe_time'] > 2     : $data[6]++; break;
                case $v['exe_time'] > 1     : $data[5]++; break;
                case $v['exe_time'] > 0.5   : $data[4]++; break;
                case $v['exe_time'] > 0.2   : $data[3]++; break;
                case $v['exe_time'] > 0.1   : $data[2]++; break;
                case $v['exe_time'] > 0.05  : $data[1]++; break;
                case $v['exe_time'] > 0     : $data[0]++; break;
            }
        }
        
        // 缓存一天
        cache('cache_exe_speed_data', $data, 86400);
        
        return $data;
    }
    
    /**
     * 访客浏览器与操作系统统计
     */
    public function performerFacility()
    {
        
        $cache_data = cache('cache_performer_facility_data');
        
        if (!empty($cache_data)) {
            
            return $cache_data;
        }
        
        $browser_list = $this->modelExeLog->getList(['status' => DATA_NORMAL], 'browser as name,count(id) as value', '', false, [], 'browser');
        
        $browser_name_data = array_extract($browser_list, 'name');
        
        $system_list = $this->modelExeLog->getList(['status' => DATA_NORMAL], 'exe_os as name,count(id) as value', '', false, [], 'exe_os');
        
        $system_name_data = array_extract($system_list, 'name');
        
        $data = compact('browser_list', 'browser_name_data', 'system_list', 'system_name_data');
        
        // 缓存一天
        cache('cache_performer_facility_data', $data, 86400);
        
        return $data;
    }
    
    /**
     * 后台会员权限等级树结构
     */
    public function getMemberTree()
    {
        
        $cache_data = cache('cache_member_tree_data');
        
        if (!empty($cache_data)) {
            
            return $cache_data;
        }
        
        $list = $this->modelMember->getList(['status' => DATA_NORMAL, 'is_inside' => DATA_NORMAL], 'id,username,status,leader_id,is_inside', '', false);
        
        $list_tree = list_to_tree($list, 'id', 'leader_id', 'children', DATA_DISABLE, ['username' => 'name', 'leader_id' => 'value']);
        
        $data = $this->compositionMemberTreeData($list_tree);
        
        // 缓存一天
        cache('cache_member_tree_data', $data[0], 86400);
        
        return $data[0];
    }
    
    /**
     * 递归组装权限等级统计数据
     */
    public function compositionMemberTreeData($list_tree = [])
    {
        
        $data = [];
        
        $kk = 'children';
        
        foreach ($list_tree as $k => $v)
        {
            
            is_object($v) && $v = $v->toArray();
            
            $data[$k]['name']       =& $v['username'];
            $data[$k][$kk]          =& $v[$kk];
            
            !empty($v[$kk]) && $data[$k][$kk] = $this->compositionMemberTreeData($v[$kk]);
        }
        
        return $data;
    }
}
