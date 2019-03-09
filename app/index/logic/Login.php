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

namespace app\index\logic;

use think\helper\Hash;

/**
 * 登录注册业务逻辑
 */
class Login extends IndexBase
{
    
    /**
     * 登录处理
     */
    public function loginHandle($param = [])
    {
        
        if (empty($param['username']) || empty($param['password'])) {
            
            return [RESULT_ERROR, '账号或密码不能为空']; 
        }
        
        $username = (string)$param['username']; $password = (string)$param['password'];
        
        $validate_result = $this->validateLogin->scene('index')->batch()->check(compact('username','password'));
        
        if (!$validate_result) {  return [RESULT_ERROR, $this->validateLogin->getError()]; }
        
        $member = $this->modelMember->getInfo(['username' => $username]);
        
        if (empty($member)) { return [RESULT_ERROR, '账号不存在或被禁用']; }
        
        $is_safety = check_login_safety($username);
        
        if (!$is_safety) {
            
            return [RESULT_ERROR, '密码输入频繁请稍后再试'];
        }
        
        // 旧版系统与新版系统密码验证
        if (!(data_md5_key($password) == $member['password'])) {
            
            check_login_safety_err($username);
            
            return [RESULT_ERROR, '登录密码错误'];
        }
        
        check_login_safety_ok($username);
        
        $update_data[TIME_UT_NAME]  = TIME_NOW;
        $update_data['ip']          = request()->ip();
        
        if (empty($member['password_version'])) {
            
            $update_data['password_version']    = DATA_NORMAL;
            $update_data['password']            = $password;
        }
        
        $this->modelMember->updateInfo(['id' => $member['id']], $update_data);

        $this->login($member);
        
        return [RESULT_SUCCESS, '登录成功', get_url(url('index/index'))];
    }
    
    /**
     * 注册处理
     */
    public function registerHandle($param = [])
    {
        
        $validate_result = $this->validateRegister->scene('index')->batch()->check($param);
        
        if (!$validate_result) {  return [RESULT_ERROR, $this->validateRegister->getError()]; }
        
        $member = $this->modelMember->getInfo(['username' => $param['username']]);
        
        if (!empty($member)) { return [RESULT_ERROR, '此账号已存在']; }
        
        $param[TIME_UT_NAME]        = TIME_NOW;
        $param['ip']                = request()->ip();
        $param['password_version']  = DATA_NORMAL;
        $param['nickname']          = $param['username'];
        
        if (!empty(cookie('check_register_ip_'.md5($param['ip'])))) {
            
            return [RESULT_ERROR, '注册频繁 请稍后再试...'];
        }
        
        $result = $this->modelMember->setInfo($param);

        if (!$result) { return [RESULT_ERROR, '注册失败，请稍后再试']; }
        
        $this->login($this->modelMember->getInfo(['username' => $param['username']]));
        
        cookie('check_register_ip_'.md5($param['ip']), time(), 60);
        
        return [RESULT_SUCCESS, '注册成功', get_url(url('index/index'))];
    }
    
    /**
     * 登录
     */
    public function login($member = [])
    {
        
        $auth = ['member_id' => $member['id'], TIME_UT_NAME => TIME_NOW];

        session('member_info', $member);
        session('member_auth', $auth);
        session('member_auth_sign', data_auth_sign($auth));
    }
    
    /**
     * 退出
     */
    public function logout()
    {
        
        session('member_info', null);
        session('member_auth', null);
        session('member_auth_sign', null);
    }
    
    /**
     * 渠道注册处理
     */
    public function channelRegisterHandle($param = [])
    {
        
        if (empty($param['username']) || empty($param['password'])) {
            
            return [RESULT_ERROR, '用户名或密码不能为空'];
        }
        
        $member_info = $this->modelMember->getInfo(['username' => $param['username']]);
        
        if (!empty($member_info)) {
            
            return [RESULT_ERROR, '用户名已存在'];
        }
        
        $preg ='/^[a-zA-Z][a-zA-Z0-9]{4,18}$/';

        if (!preg_match($preg, $param['username']))
        {
            return [RESULT_ERROR, '用户名格式不正确'];
        }
        
        $code = get_register_code();
        
        $where['code'] = $code;
        
        $code_info = $this->modelWgCode->getInfo($where);
        
        $game_info = $this->modelWgGame->getInfo(['id' => $code_info['game_id']]);
        
        $param[TIME_UT_NAME]        = TIME_NOW;
        $param['ip']                = request()->ip();
        $param['password_version']  = DATA_NORMAL;
        $param['nickname']          = $param['username'];
        
        if (!empty(cookie('check_register_ip_'.md5($param['ip'])))) {
            
            return [RESULT_ERROR, '注册频繁 请稍后再试...'];
        }
        
        $result = $this->modelMember->setInfo($param);

        if (!$result) { return [RESULT_ERROR, '注册失败，请稍后再试']; }
        
        cookie('check_register_ip_'.md5($param['ip']), time(), 60);
        
        $new_member = $this->modelMember->getInfo(['username' => $param['username']]);
        
        $this->login($new_member);
        
        $channel_param = session('channel_param');
        
        if (!empty($channel_param)) {
            
            $channel_param['member_id'] = $new_member['id'];
            $channel_param['username']  = $new_member['username'];
     
            $model = model(ucwords($channel_param['channel']), 'channel');
            
            return $model->register($channel_param);
        }
        
        $channel_sid = session('channel_sid');
        
        session('channel_sid', null);
        
        if (empty($channel_sid)) {
            
            return [RESULT_SUCCESS, '注册成功', url('play/index',['game_code' => $game_info['game_code']])];
        }
        
        return [RESULT_SUCCESS, '注册成功', url('play/index',['game_code' => $game_info['game_code'], 'sid' => $channel_sid])];
    }
}
