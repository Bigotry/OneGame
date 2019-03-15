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

use think\Db;

/**
 * 礼包相关逻辑
 */
class Gift extends AdminBase
{
    
    /**
     * 获取礼包列表
     */
    public function getGiftList($where = [], $field = true, $order = 'create_time desc', $paginate = 0)
    {
        
        $list = $this->modelWgGift->getList($where, $field, $order, $paginate);
        
        foreach ($list as &$info)
        {
            
            $number = $this->modelWgGiftKey->stat(['gift_id' => $info['id'], 'is_get' => DATA_DISABLE]);
            
            $info['gift_inventory'] = empty($number) ? 0 : $number;
            
            if (empty($info['type'])) {
                
                $info['game_name'] = Db::name('wg_game')->where(['id' => $info['game_id']])->value('game_name');
                
            } else {
                
                $info['game_name'] = Db::name('mg_game')->where(['id' => $info['game_id']])->value('game_name');
            }
        }
        
        return $list;
    }
    
    /**
     * 获取礼包信息
     */
    public function getGiftInfo($where = [], $field = true)
    {
        
        return $this->modelWgGift->getInfo($where, $field);
    }
    
    /**
     * 礼包信息编辑
     */
    public function giftEdit($data = [])
    {
        
        $validate_result = $this->validateGift->scene('edit')->check($data);
        
        if (!$validate_result) : return [RESULT_ERROR, $this->validateGift->getError()]; endif;
        
        $result = $this->modelWgGift->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, '礼包信息' . $handle_text . '，gift_name：' . $data['gift_name']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', url('giftList')] : [RESULT_ERROR, $this->modelWgGift->getError()];
    }
    
    /**
     * 礼包删除
     */
    public function giftDel($where = [])
    {
        
        $result = $this->modelWgGift->deleteInfo($where);
        
        $result && action_log('删除', '礼包删除' . '，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '删除成功'] : [RESULT_ERROR, $this->modelWgGift->getError()];
    }
    
    /**
     * 获取库存列表
     */
    public function getInventoryList($param = [])
    {
        
        $this->modelWgGiftKey->alias('wgk');
        
        $where['wgk.' . DATA_STATUS_NAME]   = ['neq', DATA_DELETE];
        $where['wgk.gift_id']               = $param['id'];
        
        $join = [
                    ['wg_gift wg' , 'wg.id = wgk.gift_id'],
                    ['wg_game ga' , 'ga.id = wg.game_id'],
                    ['member me' ,  'me.id = wgk.member_id', 'left'],
                ];
        
        $field = 'wgk.*,ga.game_name,wg.gift_name,me.nickname';
        
        return $this->modelWgGiftKey->getList($where, $field, 'wgk.is_get asc,wgk.create_time asc', DB_LIST_ROWS, $join);
    }
    
    /**
     * 礼包KEY新增
     */
    public function giftAddKey($data = [])
    {
        
        $validate_result = $this->validateGift->scene('add_key')->check($data);
        
        if (!$validate_result) : return [RESULT_ERROR, $this->validateGift->getError()]; endif;
        
        $result = $this->modelWgGiftKey->setInfo($data);
        
        $result && action_log('新增', '礼包KEY新增' . '，key：' . $data['key']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', url('inventoryList', ['id' => $data['gift_id']])] : [RESULT_ERROR, $this->modelWgGiftKey->getError()];
    }
    
    /**
     * 礼包KEY批量导入
     */
    public function giftImportKey($data = [])
    {
        
        $file_info = Db::name('file')->where(['id' => $data['key_file']])->field('path,url')->find();
        
        $excel_data = get_excel_data(ROOT_PATH . 'public' . DS . 'upload/file/'.$file_info['path']);
        
        if (empty($excel_data)) : return [RESULT_ERROR, '表格数据不能为空']; endif;
        
        $data_all = [];
        
        foreach ($excel_data as $info)
        {
            !empty($info[0]) && $data_all[] = ['gift_id' => $data['gift_id'], 'key' => $info[0]];
        }
        
        $result = $this->modelWgGiftKey->setList($data_all);
        
        $result && action_log('导入', '礼包KEY导入' . '，gift_id：' . $data['gift_id']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', url('inventoryList', ['id' => $data['gift_id']])] : [RESULT_ERROR, $this->modelWgGiftKey->getError()];
    }
}
