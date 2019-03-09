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

namespace app\common\behavior;

use think\Hook;

/**
 * 初始化钩子信息行为
 */
class InitHook
{

    /**
     * 行为入口
     */
    public function run()
    {
        
        $cache_key = 'cache_init_hook_list';
        
        $addon_list = cache($cache_key);
        
        if (empty($addon_list)) {
            
            $hook  = model(SYS_COMMON_DIR_NAME . SYS_DS_PROS . ucwords(SYS_HOOK_DIR_NAME));

            $list = $hook->column('id,name,addon_list');

            foreach ($list as $v) {

              $addon_list[$v['name']] = get_addon_class($v['addon_list']);  
            }
            
            cache($cache_key, $addon_list, 60);
        }
        
        Hook::import($addon_list);
    }
}
