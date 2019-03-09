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
 * 支付相关逻辑
 */
class Pay extends LogicBase
{
    
    /**
     * 获取支付中心页面初始化数据
     */
    public function payInitData($pay_code = '')
    {
        
        $where['service_name']  = 'Pay';
        $where['status']        = DATA_NORMAL;
        
        $pay_list = $this->modelDriver->getList($where, true, 'id asc', false);
        
        $select_code = empty($pay_code) ? 'Alipay' : ucfirst($pay_code);
        
        foreach ($pay_list as &$v)
        {
            
            $v['driver_name'] == $select_code ? $v['is_select'] = DATA_NORMAL : $v['is_select'] = DATA_DISABLE;
            
            $config = unserialize($v['config']);
            
            $v['icon'] = $config['icon'];
            
            unset($config);
            
            $model = model($v['driver_name'], 'service\\pay\\driver');
            
            $v['driver_info'] = $model->driverInfo();
        }
        
        $game_list = $this->modelWgGame->getList(['status' => DATA_NORMAL], 'id,game_name,game_code,game_cover', 'sort desc', false);
        
        $select_driver = SYS_DRIVER_DIR_NAME . $select_code;
        
        $pay_info = $this->servicePay->$select_driver->driverInfo();
        
        return compact('pay_list','game_list','pay_info');
    }
    
    /**
     * 支付处理
     */
    public function payHandle($param = [])
    {
        
        if (!IS_POST) {
            
            return [RESULT_ERROR, '非法请求！'];
        }
        
        if (empty($param['game_id']) || empty($param['server_id']) || empty($param['price']) || !is_numeric($param['game_id']) || !is_numeric($param['server_id']) || !is_numeric($param['price']) || empty($param['role_id'])) {

            return [RESULT_ERROR, '支付请求数据不完整！'];
        }
        
        $pay_min_money = (int)config('pay_min_money');
        
        if ($param['price'] < $pay_min_money) {

            return [RESULT_ERROR, '最低充值'.$pay_min_money.'元喔！'];
        }
        
        $order_info = $this->createOrder(is_login(), $param['role_id'], $param['game_id'], $param['server_id'], $param['pay_code'], $param['price']);
        
        if ($order_info) {

            $web_site_name = config('web_site_name');
            
            $order_info['subject']  = $web_site_name;
            $order_info['body']     = $web_site_name;
            $order_info['show_url'] = DOMAIN;

            $select_driver = SYS_DRIVER_DIR_NAME . $order_info['pay_code'];
            
            $html = $this->servicePay->$select_driver->getPayCode($order_info);
            
            return [RESULT_SUCCESS, $html];
            
        } else {
            
            return [RESULT_ERROR, '系统繁忙，请稍后再试！'];
        }
    }
    
    /**
     * 支付成功通知
     */
    public function notify()
    {
        
        $order_sn = get_order_sn();

        $info = $this->modelWgOrder->getInfo(['order_sn' => $order_sn]);

        empty($info) && die('不存在订单号');
        
        $select_driver = SYS_DRIVER_DIR_NAME . $info['pay_code'];

        $result = $this->servicePay->$select_driver->notify();
        
        $result && $this->paymentSuccess($info);

        $xml_success = "<xml>
                            <return_code><![CDATA[SUCCESS]]></return_code>
                            <return_msg><![CDATA[OK]]></return_msg>
                        </xml>";

        $xml_fail    = "<xml>
                            <return_code><![CDATA[FAIL]]></return_code>
                            <return_msg><![CDATA[失败]]></return_msg>
                        </xml>";
        
        if ($result) {
            
            exit($xml_success);
        } else {
            
            exit($xml_fail);
        }
    }
    
    /**
     * 支付成功业务处理
     */
    public function paymentSuccess($order_info = [])
    {
        
        $order['pay_status']  = DATA_NORMAL;
        $order['pay_time']    = TIME_NOW;
        $order['update_time'] = TIME_NOW;
        
        $pay_order_result = $this->modelWgOrder->updateInfo(['order_sn' => $order_info['order_sn'], 'pay_status' => DATA_DISABLE], $order);
        
        if ($pay_order_result) {
            
            $game_info   = $this->modelWgGame->getInfo(['id' => $order_info['game_id']]);
            $server_info = $this->modelWgServer->getInfo(['id' => $order_info['server_id']]);
            
            $order_info['cp_server_id'] = $server_info['cp_server_id'];
            
            $select_driver = SYS_DRIVER_DIR_NAME . $game_info['game_code'];

            $api_result = $this->serviceWebgame->$select_driver->pay($order_info);
            
            $api_result && $this->modelWgOrder->updateInfo(['order_sn' => $order_info['order_sn'], 'pay_status' => DATA_NORMAL, 'order_status' => DATA_DISABLE], ['order_status' => DATA_NORMAL]);
        }
        
        return $pay_order_result;
    }
    
    /**
     * 检查订单是否支付
     */
    public function checkPayStatus($order_sn = '')
    {
        
        $info = $this->modelWgOrder->getInfo(['order_sn' => $order_sn]);
        
        return $info['pay_status'] === DATA_NORMAL ? 'succeed' : 'fail';
    }
    
    /**
     * 创建订单号
     */
    public function createOrderSn()
    {
        
        $order_sn = date("YmdHis").rand(10000,99999);

        $info = $this->modelWgOrder->getInfo(['order_sn' => $order_sn]);
        
        if (empty($info)) {

            return $order_sn;
        } else {

            return $this->createOrderSn();
        }
    }
    
    /**
     * 创建订单
     */
    public function createOrder($member_id = 0, $role_id = '', $game_id = 0, $server_id = 0, $pay_code = '', $order_money = 0, $is_admin = 0)
    {
        
        $order_sn = $this->createOrderSn();
        
        $driver = SYS_DRIVER_DIR_NAME . ucfirst($pay_code);
        
        $driver_info = $this->servicePay->$driver->driverInfo();
        
        $data['order_sn']       = $order_sn;
        $data['game_id']        = $game_id;
        $data['server_id']      = $server_id;
        $data['role_id']        = $role_id;
        $data['member_id']      = $member_id;
        $data['pay_code']       = $driver_info['driver_class'];
        $data['pay_name']       = $driver_info['driver_name'];
        $data['pay_time']       = 0;
        $data['pay_status']     = 0;
        $data['order_money']    = $order_money;
        $data['order_status']   = 0;
        $data['is_admin']       = 0;
        $data['create_time']    = TIME_NOW;
        $data['update_time']    = TIME_NOW;
        $data['status']         = DATA_NORMAL;
        $data['create_date']    = date("Y-m-d");
        $data['create_month']   = date("Y-m");
        $data['ip']             = request()->ip();
        $data['is_admin']       = $is_admin;
        
        $map['member_id'] =  $member_id;
        $map['game_id']   =  $game_id;
        $map['status']    =  DATA_NORMAL;
        $map['is_check']  =  DATA_NORMAL;

        $bind_info = $this->modelWgBind->getInfo($map);

        if (!empty($bind_info))
        {
            $data['c_member_id']    = $bind_info['employee_id'];
            $data['conference_id']  = $bind_info['conference_id'];
        }
        
        $id = $this->modelWgOrder->addInfo($data);

        return $id ? $data : false;
    }
}
