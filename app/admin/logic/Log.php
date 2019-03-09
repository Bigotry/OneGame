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

use think\Db;

/**
 * 行为日志逻辑
 */
class Log extends AdminBase
{
    
    /**
     * 获取日志列表
     */
    public function getLogList($param = [])
    {
        
//        $sub_member_ids = $this->logicMember->getSubMemberIds(MEMBER_ID);
        
        $where = [];
        
//        $sub_member_ids[] = MEMBER_ID;
        
//        !IS_ROOT && $where['member_id'] = ['in', $sub_member_ids];
        !IS_ROOT && $where['member_id'] = ['neq', SYS_ADMINISTRATOR_ID];
        
        if (!empty($param['name'])) {
            
            $where['name'] = $param['name'];
        }
        
        if (!empty($param['search_data'])) {
            
            $where['username'] = $param['search_data'];
        }
        
        return $this->modelActionLog->getList($where, true, 'create_time desc');
    }
  
    /**
     * 搜索选项数据
     */
    public function getSearchOptionList()
    {
        
        $name_list = Db::name('action_log')->group('name')->field('name')->select();
        
        return $name_list;
    }
  
    /**
     * 日志删除
     */
    public function logDel($where = [])
    {
        
        return $this->modelActionLog->deleteInfo($where) ? [RESULT_SUCCESS, '日志删除成功'] : [RESULT_ERROR, $this->modelActionLog->getError()];
    }
    
    /**
     * 日志添加
     */
    public function logAdd($name = '', $describe = '')
    {
        
        $member_info = session('member_info');
        
        $request = request();
        
        $data['member_id'] = $member_info['id'];
        $data['username']  = $member_info['username'];
        $data['ip']        = $request->ip();
        $data['url']       = $request->url();
        $data['status']    = DATA_NORMAL;
        $data['name']      = $name;
        $data['describe']  = $describe;
        
        $url = url('logList');
        
        return $this->modelActionLog->setInfo($data) ? [RESULT_SUCCESS, '日志添加成功', $url] : [RESULT_ERROR, $this->modelActionLog->getError()];
    }
}
