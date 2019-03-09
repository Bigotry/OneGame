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

/**
 * 轮播逻辑
 */
class Slider extends AdminBase
{
    
    /**
     * 获取轮播列表
     */
    public function getSliderList($where = [], $field = true, $order = '', $paginate = 0)
    {
        
        return $this->modelSlider->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 轮播信息编辑
     */
    public function sliderEdit($data = [])
    {
        
        $validate_result = $this->validateSlider->scene('edit')->check($data);
        
        if (!$validate_result) : return [RESULT_ERROR, $this->validateSlider->getError()]; endif;
        
        $url = url('sliderList');
        
        $result = $this->modelSlider->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, '轮播' . $handle_text . '，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelSlider->getError()];
    }

    /**
     * 获取轮播信息
     */
    public function getSliderInfo($where = [], $field = true)
    {
        
        return $this->modelSlider->getInfo($where, $field);
    }
    
    /**
     * 轮播删除
     */
    public function sliderDel($where = [])
    {
        
        $result = $this->modelSlider->deleteInfo($where);
        
        $result && action_log('删除', '轮播删除' . '，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '删除成功'] : [RESULT_ERROR, $this->modelSlider->getError()];
    }
}
