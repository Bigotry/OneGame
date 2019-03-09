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

namespace app\admin\controller;

/**
 * 轮播控制器
 */
class Slider extends AdminBase
{
    
    /**
     * 轮播列表
     */
    public function sliderList()
    {
        
        $this->assign('list', $this->logicSlider->getSliderList());
        
        return $this->fetch('slider_list');
    }
    
    /**
     * 轮播添加
     */
    public function sliderAdd()
    {
        
        IS_POST && $this->jump($this->logicSlider->sliderEdit($this->param));
        
        return $this->fetch('slider_edit');
    }
    
    /**
     * 轮播编辑
     */
    public function sliderEdit()
    {
        
        IS_POST && $this->jump($this->logicSlider->sliderEdit($this->param));
        
        $info = $this->logicSlider->getSliderInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);
        
        return $this->fetch('slider_edit');
    }
    
    /**
     * 轮播删除
     */
    public function sliderDel($id = 0)
    {
        
        $this->jump($this->logicSlider->sliderDel(['id' => $id]));
    }
}
