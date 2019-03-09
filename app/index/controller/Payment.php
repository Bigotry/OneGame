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

namespace app\index\controller;

/**
 * 支付相关控制器
 */
class Payment extends IndexBase
{
    
    /**
     * 订单支付状态检查
     */
    public function checkPayStatus($order_sn = '')
    {
        
        echo $this->logicPay->checkPayStatus($order_sn);
    }
    
    /**
     * 服务器通知
     */
    public function notify()
    {
        
        return $this->logicPay->notify();
    }
    
    /**
     * 回调
     */
    public function callback()
    {
        
        return $this->redirect('index/center/index');
    }
}
