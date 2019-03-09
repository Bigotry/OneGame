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
 * 回收站逻辑
 */
class Trash extends AdminBase
{
    
    /**
     * 获取回收站列表
     */
    public function getTrashList()
    {
        
        $list = [];
        
        $trash_config = parse_config_array('trash_config');
        
        foreach ($trash_config as $k => $v) {
            
            $temp = [];[$v];
            $m = LAYER_MODEL_NAME   . $k;
            $temp['name']           = $k;
            $temp['model_path']     = $this->$m->class;
            $temp['number']         = $this->$m->stat([DATA_STATUS_NAME => DATA_DELETE]);
            
            $list[] = $temp;
        }
        
        return $list;
    }
    
    /**
     * 获取回收站数据列表
     */
    public function getTrashDataList($model_name = '')
    {
        
        $trash_config = parse_config_array('trash_config');
        
        $dynamic_field = $trash_config[$model_name];
        
        $field = 'id,' . TIME_CT_NAME . ','.TIME_UT_NAME.',' . $dynamic_field;
        
        $m = LAYER_MODEL_NAME . $model_name;
        
        $list = $this->$m->getList([DATA_STATUS_NAME => DATA_DELETE], $field, 'id desc');
        
        return compact('list', 'dynamic_field', 'model_name');
    }
    
    /**
     * 彻底删除数据
     */
    public function trashDataDel($model_name = '', $id = 0)
    {
        
        $where = empty($id) ? [DATA_STATUS_NAME => DATA_DELETE] : ['id' => $id];
        
        $m = LAYER_MODEL_NAME . $model_name;
        
        $result = $this->$m->deleteInfo($where, true);
        
        $result && action_log('删除', '删除回收站数据，model_name：' . $model_name .'，id' . $id);
        
        return $result ? [RESULT_SUCCESS, '删除成功'] : [RESULT_ERROR, '删除失败'];
    }
    
    /**
     * 恢复数据
     */
    public function restoreData($model_name = '', $id = 0)
    {
        
        $where = empty($id) ? [DATA_STATUS_NAME => ['neq', DATA_NORMAL]] : ['id' => $id];
        
        $m = LAYER_MODEL_NAME . $model_name;
        
        $result = $this->$m->setFieldValue($where, DATA_STATUS_NAME, DATA_NORMAL);
        
        $result && action_log('恢复', '恢复回收站数据，model_name：' . $model_name .'，id' . $id);
        
        return $result ? [RESULT_SUCCESS, '数据恢复成功'] : [RESULT_ERROR, '恢复失败'];
    }
 
}
