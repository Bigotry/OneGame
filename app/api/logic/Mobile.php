<?php
/**
 * Created by PhpStorm.
 * User: Joe <QQ 137294789>
 * Date: 2019/3/18
 * Time: 20:49
 */

namespace app\api\logic;


use app\index\logic\Play;
use think\Db;
use think\Request;

class Mobile extends ApiBase
{

    /**
     * 获取我的游戏记录
     * @author Joe <QQ 137294789>
     * @param Request $request in member_id
     * @return array
     */
    public function myGameList($request)
    {
        $member_id = $request->param('member_id');
        $page = $request->param('page');
        $rows = $request->param('rows');
        $query = $this->modelWgPlayer->alias('p')
            ->field('g.game_id,g.game_name as name,g.game_intro,
            g.game_head as img,download_url as url,g.game_type')
            ->join('mg_game g', 'p.game_id=g.id')
            ->page($page, $rows)
            ->where('p.member_id', $member_id);
        return ['list' => $query->select()];
    }

    /**
     * 获取礼包列表
     * @author Joe <QQ 137294789>
     * @param Request $request
     * @return mixed
     */
    public function getGiftList($request)
    {
        $group_id = $request->param('group_id');
        $page = $request->param('page');
        $rows = $request->param('rows');
        $query = $this->modelMgGift
            ->alias('gift')
            ->field('gift.*,game.game_category_id,log.key,game.game_type,game.download_url as url')
            ->where('gift.status', '<>', DATA_DELETE)
            ->page($page, $rows)
            ->join('mg_game game', 'gift.game_id=game.id')
            ->join('mg_gift_log log', 'log.gift_id=gift.id', 'left');

        if (!empty($group_id)) {
            $query->where('game.game_category_id', $group_id);
        }
        return ['list' => $query->select()];
    }


    /**
     * 领取礼包
     * @param Request $request in member_id=用户id gift_id=礼包id
     * @return array
     */
    public function getMobileGift($request)
    {
        $member_id = $request->param('member_id');
        $gift_id = $request->param('gift_id');

        $key_where['id'] = $gift_id;

        $info = $this->modelMgGift->getInfo($key_where);

        if (empty($member_id)) {
            return [RESULT_ERROR, '未知用户'];
        }

        if (empty($info['number'])) {
            return [RESULT_ERROR, '礼包已经领完啦'];
        }

        $exist_map['gift_id'] = $gift_id;
        $exist_map['member_id'] = $member_id;

        $exist_info = $this->modelMgGiftLog->getInfo($exist_map);

        if (!empty($exist_info)) {
            return [RESULT_ERROR, '您已经领取过此礼包啦 Key是' . $exist_info['key']];
        }

        $driver = SYS_DRIVER_DIR_NAME . ucfirst('Jiule');

        $result = $this->serviceMgame->$driver->getGift($member_id, $gift_id);

        if (false == $result) {
            return [RESULT_ERROR, '系统繁忙，请稍后再试'];
        }

        return [RESULT_SUCCESS, $result];
    }


    /**
     * 获取游戏分类
     * @author Joe <QQ 137294789>
     * @return mixed
     */
    public function getCtegory()
    {
        $category = $this->modelWgCategory
            ->field('id,category_name as title')
            ->select();
        return $category;
    }

    /**
     * 获取游戏列表
     * @author Joe <QQ 137294789>
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getIndexGameList()
    {
        $gameList = Db::name('mg_game')
            ->field('game_id,game_name as name,game_intro,game_head as img,download_url as url,game_type')
            ->where('status', '<>', DATA_DELETE)
            //->where('is_recommend', 1)
            ->select();
        return $gameList;
    }

    /**
     * 手机端主页数据填充
     * @author Joe <QQ 137294789>
     * @return array
     */
    public function index()
    {
        $ctegory = $this->getCtegory();
        $game_list = $this->getIndexGameList();
        return [
            'ctegory' => $ctegory,
            'game_list' => $game_list
        ];
    }

    /**
     * 获取游戏列表
     * @author Joe <QQ 137294789>
     * @param $request Request in group_id 游戏分类id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getGameList($request)
    {
        $group_id = $request->param('group_id');
        $page = $request->param('page');
        $rows = $request->param('rows');
        $query = Db::name('mg_game')
            ->field('game_id,game_name as name,game_intro,game_head as img,download_url as url,game_type')
            ->where('status', '<>', DATA_DELETE);
        if (!empty($group_id)) {
            $query->where('game_category_id', $group_id);
        }
        $list = $query->page($page, $rows)->select();
        return ['list' => $list];
    }

    /**
     * 获取游戏提供商url
     * @param Request $request in game_id
     * @return mixed
     */
    public function play($request)
    {
        $game_id = $request->param('game_id');
        $member_id = $request->param('member_id');


        $driver = SYS_DRIVER_DIR_NAME . ucfirst('Jiule');
        $play = new Play();
        $game_info = $this->modelMgGame->getInfo(['game_id' => $game_id]);


        $player_info = $play->savePlayer($member_id, $game_info, [], 1);

        $play->checkBinding($player_info);

        $url = $this->serviceMgame->$driver->play($game_id);
        return ['url' => $url];
    }


    /**
     * 下载游戏
     * @param $request in game_id & member_id
     */
    public function downGame($request)
    {
        $game_id = $request->param('game_id');
        $member_id = $request->param('member_id');

        $game_info = $this->modelMgGame->getInfo(['id' => $game_id]);
        //推广代码
        $code = get_register_code();

        $conference_id = 0;
        $c_member_id = 0;
        if (!empty($code)) {
            $code_info = $this->modelWgCode->getInfo(['code' => $code]);
            $b_map['member_id'] = $member_id;
            $b_map['game_id'] = $code_info['game_id'];
            $b_info = Db::name('wg_bind')->where($b_map)->find();
            $conference_id = $code_info['conference_id'];
            $c_member_id = $code_info['member_id'];
            if (empty($b_info)) {
                $b_map['conference_id'] = $code_info['conference_id'];
                $b_map['employee_id'] = $code_info['member_id'];
                $b_map['create_time'] = TIME_NOW;
                $b_map['update_time'] = TIME_NOW;
                $b_map['is_check'] = 1;
                $b_map['type'] = 1;
                Db::name('wg_bind')->insert($b_map);
            }
            session('register_code', null);
            cookie('register_code', null);
        } else {

            $bind_info = $this->modelWgBind->getInfo(['member_id' => $member_id]);

            if (!empty($bind_info)) {
                $conference_id = $bind_info['conference_id'];
                $c_member_id = $bind_info['employee_id'];
            }
        }

        $add_data['game_id'] = $game_id;
        $add_data['member_id'] = $member_id;
        $add_data['conference_id'] = $conference_id;
        $add_data['c_member_id'] = $c_member_id;
        $add_data['create_date'] = date("Y-m-d");
        $add_data['create_month'] = date("Y-m");

        $info = Db::name('mg_download_log')->where($add_data)->field(true)->find();

        if (empty($info)) {
            $add_data['create_time'] = time();
            $add_data['update_time'] = time();
            $add_data['status'] = 1;

            Db::name('mg_download_log')->insert($add_data);
        }
        return ['code' => 'success'];
    }
}