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

namespace app\admin\controller;

/**
 * 游戏数据统计分析控制器
 */
class Analyze extends AdminBase
{
    
    /**
     * 游戏注册记录
     */
    public function registerList()
    {
        
        $this->assign('list', $this->logicAnalyze->getRegisterList($this->param));
        
        $this->assign('game_list', $this->logicGame->getGameList([], 'g.*,c.category_name', 'g.sort desc', false));

        !empty($this->param['game_id']) && $this->assign('server_list', $this->logicGame->getServerList(['game_id' => $this->param['game_id']], 's.*,g.game_name', 's.create_time desc', false));
        
        return $this->fetch('register_list');
    }
    
    /**
     * 每日汇总记录
     */
    public function everydayList()
    {
        
        $data = $this->logicAnalyze->getEverydayList($this->param);
        
        if (is_string($data)) {
            
            $this->error($data);
        } else {
            
            $this->assign('list', $data);

            return $this->fetch('everyday_list');
        }
    }
    
    /**
     * 游戏汇总
     */
    public function gameList()
    {
        
        $this->assign('list', $this->logicAnalyze->getGameList($this->param));
        
        return $this->fetch('game_list');
    }
    
    /**
     * 区服汇总
     */
    public function serverList()
    {
        
        $this->assign('list', $this->logicAnalyze->getServerList($this->param));
        
        return $this->fetch('server_list');
    }
    
    /**
     * 公会汇总
     */
    public function conferenceList()
    {
        
        $this->assign('list', $this->logicAnalyze->getConferenceList($this->param));
        
        return $this->fetch('conference_list');
    }
    
    /**
     * 员工统计
     */
    public function employeeList()
    {
        
        $this->assign('list', $this->logicAnalyze->getEmployeeList($this->param));
        
        return $this->fetch('employee_list');
    }
    
    /**
     * 获取区服选择项
     */
    public function getServerOptions($game_id = 0)
    {
        
        $data['content'] = $this->logicGame->getServerOptions(['game_id' => $game_id]);
        
        return $data;
    }
}
