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

namespace app\api\logic;

use app\api\error\CodeBase;
use app\api\error\Common as CommonError;
use \Firebase\JWT\JWT;

/**
 * 接口基础逻辑
 */
class Common extends ApiBase
{

    /**
     * 登录接口逻辑
     */
    public function login($data = [])
    {
      
        $validate_result = $this->validateMember->scene('login')->check($data);
        
        if (!$validate_result) {
            
            return CommonError::$usernameOrPasswordEmpty;
        }
        
        begin:
        
        $member = $this->logicMember->getMemberInfo(['username' => $data['username']]);

        // 若不存在用户则注册
        if (empty($member))
        {
            $register_result = $this->register($data);
            
            if (!$register_result) {
                
                return CommonError::$registerFail;
            }
            
            goto begin;
        }
        
        if (data_md5_key($data['password']) !== $member['password']) {
            
            return CommonError::$passwordError;
        }
        
        return $this->tokenSign($member);
    }
    
    /**
     * 注册方法
     */
    public function register($data)
    {
        
        $data['nickname']  = $data['username'];
        $data['password']  = data_md5_key($data['password']);

        return $this->logicMember->setInfo($data);
    }
    
    /**
     * JWT验签方法
     */
    public static function tokenSign($member)
    {
        
        $key = API_KEY . JWT_KEY;
        
        $jwt_data = ['member_id' => $member['id'], 'nickname' => $member['nickname'], 'username' => $member['username'], 'create_time' => $member['create_time']];
        
        $token = [
            "iss"   => "OneBase JWT",         // 签发者
            "iat"   => TIME_NOW,              // 签发时间
            "exp"   => TIME_NOW + TIME_NOW,   // 过期时间
            "aud"   => 'OneBase',             // 接收方
            "sub"   => 'OneBase',             // 面向的用户
            "data"  => $jwt_data
        ];
        
        $jwt = JWT::encode($token, $key);
        
        $jwt_data['user_token'] = $jwt;
        
        return $jwt_data;
    }
    
    /**
     * 修改密码
     */
    public function changePassword($data)
    {
        
        $member = get_member_by_token($data['user_token']);
        
        $member_info = $this->logicMember->getMemberInfo(['id' => $member->member_id]);
        
        if (empty($data['old_password']) || empty($data['new_password'])) {
            
            return CommonError::$oldOrNewPassword;
        }
        
        if (data_md5_key($data['old_password']) !== $member_info['password']) {
            
            return CommonError::$passwordError;
        }

        $member_info['password'] = $data['new_password'];
        
        $result = $this->logicMember->setInfo($member_info);
        
        return $result ? CodeBase::$success : CommonError::$changePasswordFail;
    }
    
    /**
     * 友情链接
     */
    public function getBlogrollList()
    {
        
        return $this->modelBlogroll->getList([DATA_STATUS_NAME => DATA_NORMAL], true, 'sort desc,id asc', false);
    }
}
