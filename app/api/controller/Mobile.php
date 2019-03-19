<?php
/**
 * Created by PhpStorm.
 * User: Joe <>
 * Date: 2019/3/18
 * Time: 20:47
 */

namespace app\api\controller;


use think\Request;

class Mobile extends ApiBase
{
    /**
     * 手机主页页面
     * @author Joe <QQ 137294789>
     * @return mixed
     */
    public function index()
    {
        return $this->apiReturn(

            $this->logicMobile->index()

        );
    }

    /**
     * 手机游戏分类列表
     * @author Joe <QQ 137294789>
     * @return mixed
     */
    public function getGameGroup()
    {
        return $this->apiReturn(

            $this->logicMobile->getCtegory()

        );
    }

    /**
     * 获取游戏列表
     * @author Joe <QQ 137294789>
     * @param Request $request in group_id 游戏分类ID
     * @return array
     */
    public function getGameList(Request $request)
    {
        return $this->apiReturn(

            $this->logicMobile->getGameList($request)

        );
    }
}