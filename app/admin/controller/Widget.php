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
use app\common\controller\ControllerBase;

/**
 * Widget控制器
 */
class Widget extends ControllerBase
{
    
    /**
     * 获取region选项信息
     */
    public function getOptions()
    {
        
        $where['upid']      = input('upid', DATA_DISABLE);
        $where['level']     = input('level', DATA_NORMAL);
        
        $select_id = input('select_id', DATA_DISABLE);
        
        $list = $this->logicRegion->getRegionList($where);
        
        switch ($where['level'])
        {
            case 1: $default_option_text = "---请选择省份---"; break;
            case 2: $default_option_text = "---请选择城市---"; break;
            case 3: $default_option_text = "---请选择区县---"; break;
            default: $this->error('省市县 level 不存在');
        }
        
        $data = $this->logicRegion->combineOptions($select_id, $list, $default_option_text);
        
        return $this->result($data);
    }
    
}
