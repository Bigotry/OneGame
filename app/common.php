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

/**
 * 应用公共（函数）文件
 */

use think\Db;
use think\Response;
use think\exception\HttpResponseException;


// +---------------------------------------------------------------------+
// | 系统相关函数
// +---------------------------------------------------------------------+

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 */
function is_login()
{
    
    $member = session('member_auth');
    
    if (empty($member)) {
        
        return DATA_DISABLE;
    } else {
        
        return session('member_auth_sign') == data_auth_sign($member) ? $member['member_id'] : DATA_DISABLE;
    }
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string 
 */
function data_md5($str, $key = 'OneBase')
{
    
    return '' === $str ? '' : md5(sha1($str) . $key);
}

/**
 * 使用上面的函数与系统加密KEY完成字符串加密
 * @param  string $str 要加密的字符串
 * @return string 
 */
function data_md5_key($str, $key = '')
{
    
    if (is_array($str)) {
        
        ksort($str);

        $data = http_build_query($str);
        
    } else {
        
        $data = (string) $str;
    }
    
    return empty($key) ? data_md5($data, SYS_ENCRYPT_KEY) : data_md5($data, $key);
}

/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 */
function data_auth_sign($data)
{
    
    // 数据类型检测
    if (!is_array($data)) {
        
        $data = (array)$data;
    }
    
    // 排序
    ksort($data);
    
    // url编码并生成query字符串
    $code = http_build_query($data);
    
    // 生成签名
    $sign = sha1($code);
    
    return $sign;
}

/**
 * 检测当前用户是否为管理员
 * @return boolean true-管理员，false-非管理员
 */
function is_administrator($member_id = null)
{
    
    $return_id = is_null($member_id) ? is_login() : $member_id;
    
    return $return_id && (intval($return_id) === SYS_ADMINISTRATOR_ID);
}

/**
 * 获取单例对象
 */
function get_sington_object($object_name = '', $class = null)
{

    $request = request();
    
    $request->__isset($object_name) ?: $request->bind($object_name, new $class());
    
    return $request->__get($object_name);
}

/**
 * 获取插件类的类名
 * @param strng $name 插件名
 */
function get_addon_class($name = '')
{
    
    $lower_name = strtolower($name);
    
    $class = SYS_ADDON_DIR_NAME. SYS_DS_CONS . $lower_name . SYS_DS_CONS . $name;
    
    return $class;
}

/**
 * 钩子
 */
function hook($tag = '', $params = [])
{
    
    \think\Hook::listen($tag, $params);
}

/**
 * 插件显示内容里生成访问插件的url
 * @param string $url url
 * @param array $param 参数
 */
function addons_url($url, $param = array())
{

    $parse_url  =  parse_url($url);
    $addons     =  $parse_url['scheme'];
    $controller =  $parse_url['host'];
    $action     =  $parse_url['path'];

    /* 基础参数 */
    $params_array = array(
        'addon_name'      => $addons,
        'controller_name' => $controller,
        'action_name'     => substr($action, 1),
    );

    $params = array_merge($params_array, $param); //添加额外参数
    
    return url('addon/execute', $params);
}

/**
 * 插件对象注入
 */
function addon_ioc($this_class, $name, $layer)
{
    
    !str_prefix($name, $layer) && exception('逻辑与模型层引用需前缀:' . $layer);

    $class_arr = explode(SYS_DS_CONS, get_class($this_class));

    $sr_name = sr($name, $layer);

    $class_logic = SYS_ADDON_DIR_NAME . SYS_DS_CONS . $class_arr[DATA_NORMAL] . SYS_DS_CONS . $layer . SYS_DS_CONS . $sr_name;

    return get_sington_object(SYS_ADDON_DIR_NAME . '_' . $layer . '_' . $sr_name, $class_logic);
}

/**
 * 获得浏览器
 */
function browser_info()
{
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        
        $br = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MSIE/i', $br)) {
            $br = 'MSIE';
        } else if (preg_match('/Firefox/i', $br)) {
            $br = 'Firefox';
        } else if (preg_match('/Chrome/i', $br)) {
            $br = 'Chrome';
        } else if (preg_match('/Safari/i', $br)) {
            $br = 'Safari';
        } else if (preg_match('/Opera/i', $br)) {
            $br = 'Opera';
        } else {
            $br = 'Other';
        }
        
        return $br;
    } else {
        
        return 'unknow';
    }
}

/**
 * 抛出响应异常
 */
function throw_response_exception($data = [], $type = 'json')
{
    
    $response = Response::create($data, $type);

    throw new HttpResponseException($response);
}

/**
 * 写入执行信息记录
 */
function write_exe_log($begin = 'app_begin', $end = 'app_end', $type = 0)
{
    
    if (empty(config('is_write_exe_log'))) { return false; }
    
    $source_url = empty($_SERVER["HTTP_REFERER"]) ? '未知来源' : $_SERVER["HTTP_REFERER"];
    
    $exe_log['ip']              = request()->ip();
    $exe_log['exe_url']         = request()->url();
    $exe_log['exe_time']        = debug($begin, $end);
    $exe_log['exe_memory']      = debug($begin, $end, 'm');
    $exe_log['exe_os']          = get_os();
    $exe_log['source_url']      = $source_url;
    $exe_log['session_id']      = session_id();
    $exe_log['browser']         = browser_info();
    $exe_log['status']          = DATA_NORMAL;
    $exe_log['create_time']     = TIME_NOW;
    $exe_log['update_time']     = TIME_NOW;
    $exe_log['type']            = $type;
    $exe_log['login_id']        = is_login();
    
    $exe_log_path = "../log/exe_log.php";
    
    file_exists($exe_log_path) && $now_contents = file_get_contents($exe_log_path);
    
    $arr = var_export($exe_log, true);
    
    empty($now_contents) ? $contents = "<?php\nreturn array (".$arr.");\n" : $contents = str_replace(');', ','. $arr . ');', $now_contents);
    
    file_put_contents($exe_log_path, $contents);
}

/**
 * 获得操作系统
 */
function get_os()
{
    if (!empty($_SERVER['HTTP_USER_AGENT'])) {
        $os = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/win/i', $os)) {
            $os = 'Windows';
        } else if (preg_match('/mac/i', $os)) {
            $os = 'MAC';
        } else if (preg_match('/linux/i', $os)) {
            $os = 'Linux';
        } else if (preg_match('/unix/i', $os)) {
            $os = 'Unix';
        } else if (preg_match('/bsd/i', $os)) {
            $os = 'BSD';
        } else {
            $os = 'Other';
        }
        return $os;
    } else {
        return 'unknow';
    }
}

/**
 * 获取访问token
 */
function get_access_token()
{

    return md5('OneBase' . date("Ymd") . API_KEY);
}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    
    for ($i = 0; $size >= 1024 && $i < 5; $i++) {
        
        $size /= 1024;
    }
    
    return round($size, 2) . $delimiter . $units[$i];
}


// +---------------------------------------------------------------------+
// | 数组相关函数
// +---------------------------------------------------------------------+

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0)
{
    
    // 创建Tree
    $tree = [];
    
    if (!is_array($list)) {
        
        return false;
    }
    
    // 创建基于主键的数组引用
    $refer = [];

    foreach ($list as $key => $data) {

        $refer[$data[$pk]] =& $list[$key];
    }

    foreach ($list as $key => $data) {

        // 判断是否存在parent
        $parentId =  $data[$pid];

        if ($root == $parentId) {

            $tree[] =& $list[$key];

        } else if (isset($refer[$parentId])){

            is_object($refer[$parentId]) && $refer[$parentId] = $refer[$parentId]->toArray();
            
            $parent =& $refer[$parentId];

            $parent[$child][] =& $list[$key];
        }
    }
    
    return $tree;
}

/**
 * 分析数组及枚举类型配置值 格式 a:名称1,b:名称2
 * @return array
 */
function parse_config_attr($string)
{
    
    $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
    
    if (strpos($string, ':')) {
        
        $value = [];
        
        foreach ($array as $val) {
            
            list($k, $v) = explode(':', $val);
            
            $value[$k] = $v;
        }
        
    } else {
        
        $value = $array;
    }
    
    return $value;
}

/**
 * 解析数组配置
 */
function parse_config_array($name = '')
{
    
    return parse_config_attr(config($name));
}

/**
 * 将二维数组数组按某个键提取出来组成新的索引数组
 */
function array_extract($array = [], $key = 'id')
{
    
    $count = count($array);
    
    $new_arr = [];
     
    for($i = 0; $i < $count; $i++) {
        
        if (!empty($array) && !empty($array[$i][$key])) {
            
            $new_arr[] = $array[$i][$key];
        }
    }
    
    return $new_arr;
}

/**
 * 根据某个字段获取关联数组
 */
function array_extract_map($array = [], $key = 'id'){
    
    
    $count = count($array);
    
    $new_arr = [];
     
    for($i = 0; $i < $count; $i++) {
        
        $new_arr[$array[$i][$key]] = $array[$i];
    }
    
    return $new_arr;
}

/**
 * 页面数组提交后格式转换 
 */
function transform_array($array)
{

    $new_array = array();
    $key_array = array();

    foreach ($array as $key=>$val) {

        $key_array[] = $key;
    }

    $key_count = count($key_array);

    foreach ($array[$key_array[0]] as $i => $val) {
        
        $temp_array = array();

        for( $j=0;$j<$key_count;$j++ ){

            $key = $key_array[$j];
            $temp_array[$key] = $array[$key][$i];
        }

        $new_array[] = $temp_array;
    }

    return $new_array;
}

/**
 * 页面数组转换后的数组转json
 */
function transform_array_to_json($array)
{
    
    return json_encode(transform_array($array));
}

/**
 * 关联数组转索引数组
 */
function relevance_arr_to_index_arr($array)
{
    
    $new_array = [];
    
    foreach ($array as $v)
    {
        
        $temp_array = [];
        
        foreach ($v as $vv)
        {
            $temp_array[] = $vv;
        }
        
        $new_array[] = $temp_array;
    }
    
    return $new_array;
}

/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 * @param  array  $arr  要连接的数组
 * @param  string $glue 分割符
 * @return string
 */
function arr2str($arr, $glue = ',')
{
    
    return implode($glue, $arr);
}


// +---------------------------------------------------------------------+
// | 字符串相关函数
// +---------------------------------------------------------------------+

/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 * @param  string $str  要分割的字符串
 * @param  string $glue 分割符
 * @return array
 */
function str2arr($str, $glue = ',')
{
    
    return explode($glue, $str);
}

/**
 * 字符串替换
 */
function sr($str = '', $target = '', $content = '')
{
    
    return str_replace($target, $content, $str);
}

/**
 * 字符串前缀验证
 */
function str_prefix($str, $prefix)
{
    
    return strpos($str, $prefix) === DATA_DISABLE ? true : false;
}

// +---------------------------------------------------------------------+
// | 文件相关函数
// +---------------------------------------------------------------------+

/**
 * 获取目录下所有文件
 */
function file_list($path = '')
{
    
    $file = scandir($path);
    
    foreach ($file as $k => $v) {
        
        if (is_dir($path . SYS_DS_PROS . $v)) {
            
            unset($file[$k]);
        }
    }
    
    return array_values($file);
}

/**
 * 将数据保存为PHP文件，用于调试，默认将调试数据输出到log目录下
 */
function sf($arr = [], $fpath = 'debug')
{
    
    $data = "<?php\nreturn ".var_export($arr, true).";\n?>";
    
    $obj = get_sington_object('obIdWork', \ob\IdWork::class);
    
    file_put_contents('../log/' . $fpath . '_' . $obj->nextId() . EXT, $data);
}

/**
 * 获取目录列表
 */
function get_dir($dir_name)
{
    
    $dir_array = [];
    
    if (false != ($handle = opendir($dir_name))) {
        
        $i = 0;
        
        while (false !== ($file = readdir($handle))) {
            
            if ($file != "." && $file != ".."&&!strpos($file,".")) {
                
                $dir_array[$i] = $file;
                
                $i++;
            }
        }
        
        closedir($handle);
    }
    
    return $dir_array;
}

/**
 * 获取图片url
 */
function get_picture_url($id = 0)
{
    
    $cache_key = 'cache_picture_url_id_' . $id;

    $url = cache($cache_key);
    
    if (empty($url)) {
        
        $info = Db::name('picture')->where(['id' => $id])->field('path,url')->find();

        if (!empty($info['url'])) {

            $url = config('static_domain') . SYS_DS_PROS . $info['url'];

        } elseif (!empty($info['path'])) {

            $url = URL_ROOT . '/upload/picture/'.$info['path'];
        } else {

            $url =  URL_ROOT . '/static/module/admin/img/onimg.png';
        }
        
        cache($cache_key, $url, 60);
    }
    
    return $url;
}

/**
 * 批量获取图片url
 */
function get_picture_array_url($id_arr = [])
{
    
    $list = Db::name('picture')->where(['id' => ['in', $id_arr]])->field('path,url')->select();

    $url_array = [];
    
    foreach ($list as $v)
    {
        
        if (!empty($v['url'])) {

            $url_array[] = config('static_domain') . SYS_DS_PROS . $v['url'];
            
        } elseif (!empty($v['path'])) {
        
            $url_array[] = URL_ROOT . '/upload/picture/'.$v['path'];
        } else {
            
            $url_array[] = URL_ROOT . '/static/module/admin/img/onimg.png';
        }
    }
    
    return $url_array;
}

/**
 * 获取文件url
 */
function get_file_url($id = 0)
{
    
    $info = Db::name('file')->where(['id' => $id])->field('path,url')->find();

    if (!empty($info['url'])) {

        return config('static_domain') . SYS_DS_PROS . $info['url'];
    }

    if (!empty($info['path'])) {

        return URL_ROOT . '/upload/file/'.$info['path'];
    }

    return '暂无文件';
}

/**
 * 删除所有空目录 
 * @param String $path 目录路径 
 */
function rm_empty_dir($path)
{
    
    if (!(is_dir($path) && ($handle = opendir($path))!==false)) {
        
        return false;
    }
      
    while(($file = readdir($handle))!==false)
    {

        if (!($file != '.' && $file != '..')) {
            
           continue;
        }
        
        $curfile = $path . SYS_DS_PROS . $file;// 当前目录

        if (!is_dir($curfile)) {
            
           continue;  
        }

        rm_empty_dir($curfile);

        if (count(scandir($curfile)) == 2) {
            
            rmdir($curfile);
        }
    }

    closedir($handle); 
}


// +---------------------------------------------------------------------+
// | 时间相关函数
// +---------------------------------------------------------------------+

/**
 * 时间戳格式化
 * @param int $time
 * @return string 完整的时间显示
 */
function format_time($time = null, $format='Y-m-d H:i:s')
{
    
    if (null === $time) {
        
        $time = TIME_NOW;
    }
    
    return date($format, intval($time));
}

/**
 * 获取当前时间的日期
 * @param int|string $time
 * @return string 月份-日
 */
function format_month_day($time = null)
{
    
    if (is_string($time)) {
        
        $time = strtotime($time);
    }
    
    return format_time($time, 'm-d');
}

/**
 * 获取指定日期段内每一天的日期
 * @param Date $startdate 开始日期
 * @param Date $enddate  结束日期
 * @return Array
 */
function get_date_from_range($startdate, $enddate)
{
    
  $stimestamp = strtotime($startdate);
  $etimestamp = strtotime($enddate);
  
  // 计算日期段内有多少天
  $days = ($etimestamp-$stimestamp)/86400+1;
  
  // 保存每天日期
  $date = [];
  
  for($i=0; $i<$days; $i++) {
      
      $date[] = date('Y-m-d', $stimestamp+(86400*$i));
  }
  
  return $date;
}

// +---------------------------------------------------------------------+
// | 其他函数
// +---------------------------------------------------------------------+

/**
 * 通过类创建逻辑闭包
 */
function create_closure($object = null, $method_name = '', $parameter = [])
{
    
    $func = function() use($object, $method_name, $parameter) {
        
                return call_user_func_array([$object, $method_name], $parameter);
            };
            
    return $func;
}

/**
 * 通过闭包控制缓存
 */
function auto_cache($key = '', $func = null, $time = 3)
{
    
    $result = cache($key);
    
    if (empty($result)) {
        
        $result = $func();
        
        !empty($result) && cache($key, $result, $time);
    }
    
    return $result;
}

/**
 * 通过闭包列表控制事务
 */
function closure_list_exe($list = [])
{
    
    Db::startTrans();
    
    try {
        
        foreach ($list as $closure) {
            
            $closure();
        }
        
        Db::commit();
        
        return true;
    } catch (\Exception $e) {
        
        Db::rollback();
        
        throw $e;
    }
}
