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

namespace app\common\logic;

/**
 * SEO逻辑
 */
class Seo extends LogicBase
{
    
    /**
     * 获取SEO列表
     */
    public function getSeoList($where = [], $field = true, $order = '', $paginate = 0)
    {
        
        return $this->modelSeo->getList($where, $field, $order, $paginate);
    }
    
    /**
     * SEO信息编辑
     */
    public function seoEdit($data = [])
    {
        
        $validate_result = $this->validateSeo->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateSeo->getError()];
        }
        
        $url = url('seoList');
        
        $result = $this->modelSeo->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, 'SEO' . $handle_text . '，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelSeo->getError()];
    }

    /**
     * 获取SEO信息
     */
    public function getSeoInfo($where = [], $field = true)
    {
        
        return $this->modelSeo->getInfo($where, $field);
    }
    
    /**
     * SEO删除
     */
    public function seoDel($where = [])
    {
        
        $result = $this->modelSeo->deleteInfo($where);
        
        $result && action_log('删除', 'SEO删除' . '，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '删除成功'] : [RESULT_ERROR, $this->modelSeo->getError()];
    }
}
