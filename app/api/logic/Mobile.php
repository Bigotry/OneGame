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
            ->field('game_id,game_name as name,game_intro,game_head as img,download_url as url')
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

        $query = Db::name('mg_game')
            ->field('game_id,game_name as name,game_intro,game_head as img,download_url as url')
            ->where('status', '<>', DATA_DELETE);

        if (!empty($group_id)) {
            $query->where('game_category_id', $group_id);
        }

        return $query->select();
    }

    /**
     * 获取游戏提供商url
     * @author Joe <QQ 137294789>
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
        return ['url'=>$url];
    }
}