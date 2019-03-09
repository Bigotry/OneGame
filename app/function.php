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

// 扩展函数文件，系统研发过程中需要的函数建议放在此处，与框架相关函数分离

use think\Db;

/**
 * 格式化 0:否 1:是
 */
function whether_text($data = 0)
{
    
    return empty($data) ? '否' : '是';
}

 /**
  * 生成随机字符串
  * @param int       $length  要生成的随机字符串长度
  * @param string    $type    随机码类型：0，数字+大小写字母；1，数字；2，小写字母；3，大写字母；4，特殊字符；-1，数字+大小写字母+特殊字符
  */
 function rand_code($length = 5, $type = 0)
{
     
    $arr = array(1 => "0123456789", 2 => "abcdefghijklmnopqrstuvwxyz", 3 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", 4 => "~@#$%^&*(){}[]|");
    if ($type == 0) {
        array_pop($arr);
        $string = implode("", $arr);
    } elseif ($type == "-1") {
        $string = implode("", $arr);
    } else {
        $string = $arr[$type];
    }
    $count = strlen($string) - 1;
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $string[rand(0, $count)];
    }
    return $code;
 }
 
//获取注册推广code
function get_register_code()
{
    
    $register_code_session = session('register_code');

    if (!empty($register_code_session)) {
        
        return $register_code_session;
    }
    
    $register_code_cookie = cookie('register_code');

    if (!empty($register_code_cookie)) {
        
        return $register_code_cookie;
    }
    
    return '';
}

//根据参数拼接get curl URL
function exec_get_request($url)
{
    
    //初始化
    $ch = curl_init();

    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    //执行并获取HTML文档内容
    $result = curl_exec($ch);

    //释放curl句柄
    curl_close($ch);
    
    return $result;
}

//根据参数拼接post curl URL
function exec_post_request($url, $fields)
{
    
    if(empty($url)){ return false;}
    
    $fields_string = '';
    
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    rtrim($fields_string,'&');
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

// 获取通知订单号
function get_order_sn()
{
   
    $where['service_name']  = 'Pay';
    $where['status']        = DATA_NORMAL;

    $pay_types = Db::name('driver')->where($where)->select();
    
    $order_sn = null;
    
    foreach ($pay_types as $v)
    {
        
        $model = model($v['driver_name'], 'service\\pay\\driver');
        
        $order_sn = $model->getOrderSn();
        
        if (!empty($order_sn)) {  break; }
    }
    
    return $order_sn;
}

// 获取用户名并缓存
function get_username($member_id = 0)
{
    
    $cache_key = 'cache_get_username_id_' . $member_id;

    $username = cache($cache_key);
    
    if (!empty($username)) {
        
        return $username;
    }
    
    $data = Db::name('member')->where(['id' => $member_id])->value('username', '');
    
    if (!empty($data)) {
        
        cache($cache_key, $data, 86400);
    }
    
    return $data;
}

// 获取公会名并缓存
function get_conference_name($conference_id = 0)
{
    
    $cache_key = 'cache_get_conference_name_id_' . $conference_id;

    $conference_name = cache($cache_key);
    
    if (!empty($conference_name)) {
        
        return $conference_name;
    }
    
    $data = Db::name('wg_conference')->where(['id' => $conference_id])->value('conference_name', '');
    
    if (!empty($data)) {
        
        cache($cache_key, $data, 86400);
    }
    
    return $data;
}

// 获取角色名并缓存
function get_role_name($role_id = 0)
{
    
    $cache_key = 'cache_get_role_name_id_' . md5($role_id);

    $role_name = cache($cache_key);
    
    if (!empty($role_name)) {
        
        return $role_name;
    }
    
    $data = Db::name('wg_role')->where(['role_id' => $role_id])->value('role_name','');
    
    if (!empty($data)) {
        
        cache($cache_key, $data, 86400);
    }
    
    return $data;
}

// 验证登录风险，密码联系错误5次 限制1分钟
function check_login_safety($username = '', $number = 5)
{

    $cache_key = 'cache_check_login_safety_' . $username;

    $error_number = cache($cache_key);
    
    if (!empty($error_number) && $error_number >= $number) {
        
        return false;
    }
    
    return true;
}

// 密码登录错误cache记录
function check_login_safety_err($username = '')
{

    $cache_key = 'cache_check_login_safety_' . $username;

    $error_number = (int)cache($cache_key);
    
    if (empty($error_number)) {
        
        cache($cache_key, 1, 60);
    } else {
        
        cache($cache_key, ++$error_number, 60);
    }
}

// 密码登录错误记录解除
function check_login_safety_ok($username = '')
{

    $cache_key = 'cache_check_login_safety_' . $username;

    cache($cache_key, null);
}

// 验证银行卡卡号
function check_bankcard($card_number)
{
 
    if (is_numeric($card_number) && strlen($card_number) > 10){
        
        return true;
    } else {
        return false;
    }
}

// 获取游戏code并缓存
function get_game_code($game_id = 0)
{
    
    $cache_key = 'cache_get_game_code_id_' . $game_id;

    $game_code = cache($cache_key);
    
    if (!empty($game_code)) {
        
        return $game_code;
    }
    
    $data = Db::name('wg_game')->where(['id' => $game_id])->value('game_code', '');
    
    if (!empty($data)) {
        
        cache($cache_key, $data);
    }
    
    return $data;
}

// 获取游戏厂家区服ID并缓存
function get_cp_server_id($server_id = 0)
{
    
    $cache_key = 'cache_get_cp_server_id_' . $server_id;

    $cp_server_id = cache($cache_key);
    
    if (!empty($cp_server_id)) {
        
        return $cp_server_id;
    }
    
    $data = Db::name('wg_server')->where(['id' => $server_id])->value('cp_server_id', 0);
    
    if (!empty($data)) {
        
        cache($cache_key, $data);
    }
    
    return $data;
}

// 通过IP获取位置并缓存
function get_position($ip)
{
 
    $cache_key = 'cache_get_position_ip_' . $ip;
    
    $position = cache($cache_key);
    
    if (!empty($position)) {
        
        return $position;
    }
    
    $info = Db::name('ip')->where(['ip' => $ip])->field('area,isp')->find();
    
    if (!empty($info)) {
        
        $position = $info['area']."_".$info['isp'];
       
        cache($cache_key, $position);
       
        return $position;
    }
    
    $res1 = json_decode(file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=$ip"),true);
    
    if ($res1["code"]==0){
//    if (false){
       
        $position = $res1['data']["country"].$res1['data'][ "region"].$res1['data']["city"]."_".$res1['data'][ "isp"];

        $insert_data['area'] = $res1['data']["country"].$res1['data'][ "region"].$res1['data']["city"];
        $insert_data['isp']  = $res1['data']["isp"];
        $insert_data['ip'] = $ip;
        
    }else{
        
        $position = "未知";
        $insert_data['isp']  = $position;
        $insert_data['area'] = $position;
        $insert_data['ip'] = $ip;
    }
    
    cache($cache_key, $position);
    
    !empty($insert_data) && Db::name('ip')->insert($insert_data);
    
    return $position;
}