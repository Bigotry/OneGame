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
 * 友情链接控制器
 */
class Blogroll extends AdminBase
{
    
    /**
     * 友情链接列表
     */
    public function blogrollList()
    {
        
        $this->assign('list', $this->logicBlogroll->getBlogrollList());
        
        return $this->fetch('blogroll_list');
    }
    
    /**
     * 友情链接添加
     */
    public function blogrollAdd()
    {
        
        IS_POST && $this->jump($this->logicBlogroll->blogrollEdit($this->param));
        
        return $this->fetch('blogroll_edit');
    }
    
    /**
     * 友情链接编辑
     */
    public function blogrollEdit()
    {
        
        IS_POST && $this->jump($this->logicBlogroll->blogrollEdit($this->param));
        
        $info = $this->logicBlogroll->getBlogrollInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);
        
        return $this->fetch('blogroll_edit');
    }
    
    /**
     * 友情链接删除
     */
    public function blogrollDel($id = 0)
    {
        
        $this->jump($this->logicBlogroll->blogrollDel(['id' => $id]));
    }
}
