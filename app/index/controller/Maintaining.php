<?php

namespace app\index\controller;

use think\Controller;

/**
 * 系统维护中
 */
class Maintaining extends Controller
{
    
    /**
     * 系统维护中
     */
    public function index()
    {
        
        $this->view->engine->layout(false);
        
        return $this->fetch();
    }
}