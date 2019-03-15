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
 * 个人中心逻辑
 */
class Center extends IndexBase
{
    
    /**
     * 我的游戏数据
     */
    public function getMyGameData($member_id = 0)
    {
        
        $where['member_id'] = $member_id;
        
        $list = $this->modelWgPlayer->getList($where, true, 'login_time desc', 4);
        
        foreach ($list as &$v)
        {
            
            if (empty($v['type'])) {
                
                $wg_game_info       = Db::name('wg_game')->where(['id' => $v['game_id']])->field(true)->find();
                $wg_server_info     = Db::name('wg_server')->where(['id' => $v['server_id']])->field(true)->find();
                $wg_category_info   = Db::name('wg_category')->where(['id' => $wg_game_info['game_category_id']])->field(true)->find();
                
                $v['game_id']       = $wg_game_info['id'];
                $v['game_logo']     = $wg_game_info['game_logo'];
                $v['game_head']     = $wg_game_info['game_head'];
                $v['game_cover']    = $wg_game_info['game_cover'];
                $v['game_name']     = $wg_game_info['game_name'];
                $v['game_code']     = $wg_game_info['game_code'];
                $v['server_name']   = $wg_server_info['server_name'];
                $v['cp_server_id']  = $wg_server_info['cp_server_id'];
                $v['category_name'] = $wg_category_info['category_name'];
                
            } else {
                
                $mg_game_info = Db::name('mg_game')->where(['id' => $v['game_id']])->field(true)->find();
                
                $wg_category_info   = Db::name('wg_category')->where(['id' => $mg_game_info['game_category_id']])->field(true)->find();
                
                $v['game_name']     = $mg_game_info['game_name'];
                $v['game_head']     = $mg_game_info['game_head'];
                $v['game_id']       = $mg_game_info['game_id'];
                $v['category_name'] = $wg_category_info['category_name'];
                $v['game_cover']    = $mg_game_info['game_cover'];
                $v['game_intro']    = $mg_game_info['game_intro'];
            }
        }
        
        
        return $list;
        
        
        
        
        
        
        
        
        
        
        
        
        
        
//        $map['p.member_id'] = $member_id;
//        
//        $this->modelWgPlayer->alias('p');
//        
//        $join = [
//                    [SYS_DB_PREFIX . 'wg_game ga', 'p.game_id = ga.id'],
//                    [SYS_DB_PREFIX . 'wg_category gc', 'ga.game_category_id = gc.id'],
//                    [SYS_DB_PREFIX . 'wg_server s', 'p.server_id = s.id'],
//                ];
//        
//        $field = 'p.*,ga.id as game_id,ga.game_name,ga.game_cover,ga.game_code,s.cp_server_id,s.server_name,gc.category_name';
//        
//        $order = 'p.login_time desc';
//        
//        return  $this->modelWgPlayer->getList($map, $field, $order, 4, $join);
    }
    
    /**
     * 我的礼包
     */
    public function getMyGift($member_id = 0)
    {
        
        $map['gk.member_id'] = $member_id;
        
        $this->modelWgGiftKey->alias('gk');
        
        $join = [
                    [SYS_DB_PREFIX . 'wg_gift gi', 'gk.gift_id = gi.id'],
                    [SYS_DB_PREFIX . 'wg_game ga', 'gi.game_id = ga.id'],
                ];
        
        $field = 'gk.*,gi.gift_name,ga.game_name';
        
        $order = 'gk.get_time desc';
        
        return $this->modelWgGiftKey->getList($map, $field, $order, 10, $join);
    }
    
    /**
     * 修改密码
     */
    public function changePassword($param = [])
    {
        
        if (empty($param['old_password']) || empty($param['password_confirm']) || empty($param['password'])) {
            
            return [RESULT_ERROR, '输入密码不能为空'];
            
        } elseif ($param['password_confirm'] != $param['password']) {
            
            return [RESULT_ERROR, '两次密码输入不一致'];
        }
        
        $member = $this->modelMember->getInfo(['id' => is_login()]);
        
        $update_data['password_version']    = DATA_NORMAL;
        $update_data['password']            = $param['password'];
        
        $this->modelMember->updateInfo(['id' => $member['id']], $update_data);
        
        session('member_info', null);
        session('member_auth', null);
        session('member_auth_sign', null);
        
        return [RESULT_SUCCESS, '密码修改成功', url('login/login')];
    }
    
    
    /**
     * 发送邮箱设置邮件
     */
    public function sendEmail($param = [], $member_info = [])
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
        
        if ($param['email'] == $member_info['email']) {
            
            return [RESULT_ERROR, '当前已是此邮箱'];
        }

        if (empty($member_info['email'])) {
        
            $send_result = send_email_verify($member_info['username'], 'safety/emailverify', $param['email'], 'onegame 邮箱设置', "亲爱的 ：".$member_info['username']." 感谢您使用onegame游戏平台，请点击下方链接完成邮箱设置。", $param);
        
            if ($send_result === true) {

                return [RESULT_SUCCESS, '邮件发送成功，请前往邮箱 '.$param['email'].' 完成邮箱设置'];
            }
            
        } else {
            
            $send_result = send_email_verify($member_info['username'], 'safety/emailverify', $member_info['email'], 'onegame 邮箱修改', "亲爱的 ：".$member_info['username']." 感谢您使用onegame游戏平台，请点击下方链接完成邮箱修改。", $param);

            if ($send_result === true) {

                return [RESULT_SUCCESS, '邮件发送成功，请前往邮箱 '.$member_info['email'].' 完成邮箱修改'];
            }
        }
        
        return [RESULT_ERROR, '系统繁忙，请稍后再试'];
    }
    
    
}
