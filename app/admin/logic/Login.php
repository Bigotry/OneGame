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

use think\helper\Hash;

/**
 * 登录逻辑
 */
class Login extends AdminBase
{
    
    /**
     * 登录处理
     */
    public function loginHandle($username = '', $password = '', $verify = '')
    {
        
        $validate_result = $this->validateLogin->scene('admin')->check(compact('username','password','verify'));
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateLogin->getError()];
        }
        
        $member = $this->logicMember->getMemberInfo(['username' => $username]);
        
        if (empty($member)) {
            
            return [RESULT_ERROR, '用户账号不存在'];
        }
        
        $is_safety = check_login_safety($username);
        
        if (!$is_safety) {
            
            return [RESULT_ERROR, '密码输入频繁请稍后再试'];
        }
        
        // 旧版系统与新版系统密码验证
        if (!((empty($member['password_version']) && Hash::check($password, $member['password'])) || (DATA_NORMAL == $member['password_version'] && data_md5_key($password) == $member['password']))) {
            
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

        $auth = ['member_id' => $member['id'], TIME_UT_NAME => TIME_NOW];

        session('member_info', $member);
        session('member_auth', $auth);
        session('member_auth_sign', data_auth_sign($auth));

        action_log('登录', '登录操作，username：'. $username);

        return [RESULT_SUCCESS, '登录成功', url('admin/index/index')];
    }
    
    /**
     * 注销当前用户
     */
    public function logout()
    {
        
        clear_login_session();
        
        return [RESULT_SUCCESS, '注销成功', url('login/login')];
    }
    
    /**
     * 清理缓存
     */
    public function clearCache()
    {
        
        if (!is_administrator()) {
            
            return [RESULT_ERROR, '未授权操作'];
        }
        
        \think\Cache::clear();
        
        return [RESULT_SUCCESS, '清理成功', url('index/index')];
    }
}
