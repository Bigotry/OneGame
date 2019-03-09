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

namespace app\admin\validate;

/**
 * 游戏信息验证器
 */
class Game extends AdminBase
{
    
    // 验证规则
    protected $rule =   [
        
        'game_name'             => 'require|unique:wg_game',
        'game_code'             => 'require|alpha|unique:wg_game',
        'game_currency_ratio'   => 'require|number',
        'sort'                  => 'require|number',
    ];

    // 验证提示
    protected $message  =   [
        
        'game_name.require'             => '游戏名称不能为空',
        'game_name.unique'              => '游戏名称已存在',
        'game_code.require'             => '游戏标识不能为空',
        'game_code.unique'              => '游戏标识已存在',
        'game_code.alpha'               => '游戏标识必须为字母',
        'game_currency_ratio.require'   => '游戏币比例不能为空',
        'game_currency_ratio.number'    => '游戏币比例必须为数字',
        'sort.require'                  => '排序值不能为空',
        'sort.number'                   => '排序值必须为数字',
    ];

    // 应用场景
    protected $scene = [
        
        'edit' =>  ['game_name','game_code','game_currency_ratio','sort'],
    ];
    
}
