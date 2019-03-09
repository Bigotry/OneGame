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

use app\common\controller\ControllerBase;
use think\Db;

/**
 * 微端控制器
 */
class Client extends ControllerBase
{
    
    private $gameInfo = null;
    
    /**
     * 初始化方法
     */
    public function _initialize()
    {
        
        parent::_initialize();
        
        $game_code = input('game_code');
        
        empty($game_code) && $this->error('游戏标识不能为空');
        
        $this->gameInfo = Db::name('wg_game')->where(['game_code' => $game_code])->field(true)->find();
        
        empty($this->gameInfo) && $this->error('游戏信息不能为空');
        
        $this->view->engine->layout(false);
    }
    
    
    // 微端登录页面
    public function index()
    {
            
        is_login() && $this->logicLogin->logout();
        
        $game_article_list =  Db::name('article')->where(['game_id' => $this->gameInfo['id']])->field(true)->order('create_time desc')->limit(4)->select();

        $this->assign('game_article_list', $game_article_list);
        $this->assign('game_info', $this->gameInfo);
        
        return $this->fetch();
    }
    
    // 微端登录处理
    public function loginHandle()
    {
        
        $result = $this->logicLogin->loginHandle($this->param);
        
        if (RESULT_SUCCESS == $result[0]) {
            
            $url = url('client/selectServer', ['game_code' => $this->gameInfo['game_code']]);
            
            $this->success('登录成功', $url);
            
        } else {
            
            $this->error($result[1]);
        }
    }
    
    // 服务器选择
    public function selectServer()
    {
        
        $mid = is_login();
        
        !$mid && $this->error('请先登录');
        
        $list = Db::name('wg_server')->where(['game_id' => $this->gameInfo['id'], 'status' => 1])->field(true)->order('create_time desc')->select();
        
        $this->assign('list', $list);
        $this->assign('game_info', $this->gameInfo);
        $this->assign('member', session('member_info'));
        
        $map['member_id']   = $mid;
        $map['game_id']     = $this->gameInfo['id'];
        $map['status']      = DATA_NORMAL;
        
        $game_player_list = Db::name('wg_player')->where($map)->order('create_time desc')->limit(1)->select();
        
        if (!empty($game_player_list[0])) {
            
            $game_server = Db::name('wg_server')->where(['id' => $game_player_list[0]['server_id']])->find();
            
            $this->assign('server_info', $game_server);
        }
        
        return $this->fetch('select_server');
    }
}
