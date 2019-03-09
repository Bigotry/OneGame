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

use think\Db;

 /**
  * 记录执行URL
  */
function set_url($default_url = '')
{
    
    $url = empty($default_url) ? $_SERVER['REQUEST_URI'] : $default_url;
    
    (defined('URL') && in_array(URL, config('forward_url_list'))) && cookie('__forward__', $url);
}

 /**
  * 获取记录URL
  */
function get_url($default_url = '')
{
    
    $url = cookie('__forward__');
    
    if (!empty($url)) {
        
        cookie('__forward__', null);
        
        return $url;
    }
    
    return $default_url;
}

 /**
  * 发送带跳转链接的邮箱验证邮件
  */
function send_email_verify($username = '', $jump_url = '', $address = '', $title = '', $message = '', $param = [])
{
    
    if (empty($username) || empty($jump_url) || empty($address) || empty($title) || empty($message)) {
        
        return false;
    }
    
    $member_info = Db::name('member')->where(['username' => $username])->field('id,password,create_time')->find();
    
    if (empty($member_info)) {
        
        return false;
    }
    
    $sign = base64_encode($username . ',' .md5($username . $member_info['password'] . $member_info['create_time']) . ',' . $jump_url);

    $param['sign'] = $sign;
    
    $url = url($jump_url, $param, true, true);
    
    $message .= "<br/><br/>" . $url;
    
    return send_email($address, $title, $message);
}


 /**
  * 发送带跳转链接的邮箱验证邮件
  */
function verify_email_sign($sign = '')
{
    
    if (empty($sign)) {
        
        return false;
    }
    
    $data_str = base64_decode($sign);
    
    $data = str2arr($data_str);
    
    if (empty($data[0]) || empty($data[1]) || empty($data[2])) {
        
        return false;
    }
    
    $member_info = Db::name('member')->where(['username' => $data[0]])->field('id,password,create_time')->find();
    
    if (empty($member_info)) {
        
        return false;
    }
    
    $parse_sign = base64_encode($data[0] . ',' .md5($data[0] . $member_info['password'] . $member_info['create_time']) . ',' . $data[2]);
    
    if ($parse_sign != $sign) {
        
        return false;
    }
    
    session($parse_sign, $data[0]);
    
    return true;
}