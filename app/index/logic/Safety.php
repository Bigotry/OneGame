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

use think\Db;

/**
 * 安全业务逻辑
 */
class Safety extends IndexBase
{
    
    /**
     * 找回密码
     */
    public function retrievePassword($param = [])
    {
        
        if(!captcha_check($param['verify'], '', config('captcha'))){
            
            return [RESULT_ERROR, '验证码错误或失效']; 
        }
        
        if (empty($param['username'])) {
            
            return [RESULT_ERROR, '账号不能为空'];
        }
        
        $username = (string)$param['username'];
        
        $member = $this->modelMember->getInfo(['username' => $username], 'password,email,create_time');
        
        if (empty($member)) { return [RESULT_ERROR, '账号不存在或被禁用']; }
        
        if (empty($member['email'])) { return [RESULT_ERROR, '账号未绑定邮箱请联系客服']; }
        
        $send_result = send_email_verify($username, 'safety/setpassword', $member['email'], 'onegame 找回密码', "亲爱的 ：$username 感谢您使用onegame游戏平台，请点击下方链接完成密码找回。");
        
        if ($send_result === true) {
            
            return [RESULT_SUCCESS, '邮件发送成功，请前往绑定邮箱 '.$member['email'].' 进行密码找回'];
        }
        
        return [RESULT_ERROR, '系统繁忙，请稍后再试'];
    }
    
    /**
     * 邮箱验证
     */
    public function emailVerify($param = [])
    {
        
        $rule = [
            'email' => 'require|email|unique:member',
        ];

        $msg = [
            'email.require'   => '邮箱不能为空',
            'email.email'     => '邮箱格式不正确',
            'email.unique'    => '邮箱已被占用',
        ];

        $validate = new \think\Validate($rule, $msg);

        if(!$validate->check(['email' => $param['email']])){

            return [RESULT_ERROR, $validate->getError()];
        }
        
        $result = verify_email_sign($param['sign']);
        
        if ($result !== true) {
            
            return [RESULT_ERROR, '链接签名不正确'];
        }
        
        $data_str = base64_decode($param['sign']);

        $data = str2arr($data_str);
        
        $update_result = Db::name('member')->where(['username' => $data[0]])->update(['email' => $param['email']]);
        
        return $update_result ? [RESULT_SUCCESS, '操作成功', url('center/safety')] : [RESULT_ERROR, '操作失败'];
    }
    
    /**
     * 设置密码
     */
    public function setPassword($param = [])
    {
        
        if (empty($param['password']) || empty($param['confirm_password'])) {
            
            return [RESULT_ERROR, '密码不能为空'];
        }
        
        if ($param['password'] != $param['confirm_password']) {
            
            return [RESULT_ERROR, '两次密码输入不一致'];
        }
        
        $username = session($param['sign']);
        
        if (empty($username)) {
            
            return [RESULT_ERROR, '非法操作'];
        }
        
        $md5_password = data_md5_key($param['password']);
        
        $save_data['password']          = $md5_password;
        $save_data['password_version']  = DATA_NORMAL;
        
        $result = Db::name('member')->where(['username' => $username])->update($save_data);
        
        return $result ? [RESULT_SUCCESS, '操作成功', url('login/login')] : [RESULT_ERROR, '操作失败'];
    }
    
}
