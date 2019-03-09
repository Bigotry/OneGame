<?php

namespace app\index\controller;

/**
 * 计划任务控制器
 */
class Cron extends IndexBase
{
    
    /**
     * 执行计划任务
     */
    public function exeCron()
    {
        
        $this->logicCron->refreshRole();
    }
}