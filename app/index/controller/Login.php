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

namespace app\index\controller;

use think\Db;

/**
 * 登录注册控制器
 */
class Login extends IndexBase
{
    
    /**
     * 登录
     */
    public function login()
    {
        
        is_login() && $this->redirect('index/index');

        $this->setTitle('用户登录');
        
        return $this->fetch();
    }
    
    /**
     * 登录处理
     */
    public function loginHandle()
    {
        
        $this->jump($this->logicLogin->loginHandle($this->param));
    }
    
    /**
     * 注册
     */
    public function register()
    {
        
        is_login() && $this->redirect('index/index');

        $this->setTitle('用户注册');

        return $this->fetch();
    }
    
    /**
     * 注册处理
     */
    public function registerHandle()
    {
        
        $this->jump($this->logicLogin->registerHandle($this->param));
    }
    
    /**
     * 渠道注册处理
     */
    public function channelRegisterHandle()
    {
        
        $this->jump($this->logicLogin->channelRegisterHandle($this->param));
    }
    
    /**
     * 退出
     */
    public function logout()
    {
        
        $this->logicLogin->logout();
        
        $this->redirect('login/login');
    }
    
    /**
     * 微端QQ登录
     */
    public function clientLogin($game_code = '')
    {
        
        session("client_login_url", url('client/selectServer', ['game_code' => $game_code]));
        
        header("Location:/loginapi/QQAPI/oauth/index.php");
        
        exit();
    }
    
    /**
     * QQ登录
     */
    public function qqLogin()
    {

        require_once("./loginapi/QQAPI/qqConnectcallbackAPI.php");
        
        $qc = new \QC();
        
        $qc->qq_callback();
        
        $qq_openid = $qc->get_openid();
        
        if (empty($qq_openid)) : die('openid is empty'); endif;
        
        $MemberModel = model('Member');
        
        $member = $MemberModel::get(['qqopenid' => $qq_openid]);
        
        // 注册
        if (empty($member)) {
            
            $data['status']         = DATA_NORMAL;
            $data['qqopenid']       = $qq_openid;
            $data['create_time']    = time();
            $data['is_inside']      = DATA_DISABLE;
            
            $uid = Db::name('member')->insertGetId($data);

            $member = $MemberModel::get(['id' => $uid]);
            
            $member->username = 'qq_user_'.$uid;
            $member->nickname = 'qq_user_'.$uid;
        }

        // 登录
        $uid = $member->id;

        // 更新登录信息
        $member->update_time = request()->time();
        $member->ip          = request()->ip();
        
        if ($member->save()) {

            // 自动登录
            $auth = ['member_id' => $member['id'], TIME_UT_NAME => TIME_NOW];

            session('member_info', $member);
            session('member_auth', $auth);
            session('member_auth_sign', data_auth_sign($auth));
            
            $client_login_url = session("client_login_url");
            
            if (!empty($client_login_url)) {
                
                session("client_login_url", null);
                header("Location:$client_login_url");
                
                exit();
            }
            
            $url = 'index/index';

            $__forward__ = get_url();

            if (!empty($__forward__)) {

                header("Location:$__forward__");
                
                exit();
            }

            return $this->redirect($url);
            
        } else {
            
            die('qq login error');
        }
    }
}
