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
 * 执行记录逻辑
 */
class ExeLog extends AdminBase
{
    
    /**
     * 获取记录列表
     */
    public function getLogList($where = [], $field = true, $order = '')
    {
        
        return $this->modelExeLog->getList($where, $field, $order);
    }
  
    /**
     * 日志入库
     */
    public function logImport()
    {
        
        $exe_log_path = "../log/exe_log.php";
        
        $exe_log_array = require $exe_log_path;
        
        if (empty($exe_log_array) || DATA_NORMAL == $exe_log_array) {
            
            return [RESULT_ERROR, '日志文件为空'];
        }
        
        $this->modelExeLog->setList($exe_log_array) && file_put_contents($exe_log_path, '');
        
        return [RESULT_SUCCESS, '日志已入库'];
    }
  
    /**
     * 记录删除
     */
    public function logDel($where = [])
    {
        
        return $this->modelExeLog->deleteInfo($where) ? [RESULT_SUCCESS, '删除成功'] : [RESULT_ERROR, '删除失败'];
    }
}
