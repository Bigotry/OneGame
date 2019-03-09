<?php

namespace app\index\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

/**
 * 计划任务
 */
class Cron extends Command
{
    
    protected function configure()
    {
        $this->setName('cron')->setDescription('执行角色更新计划任务命令');
    }

    protected function execute(Input $input, Output $output)
    {
        
        //开始
        $time = time();
        
        $date = date('y-m-d h:i:s',$time);
        
        $output->writeln("ok : ".$date);
    }
}