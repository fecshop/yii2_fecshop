<?php

/*
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */

ini_set('date.timezone', 'Asia/Shanghai');
//error_reporting(E_ERROR);
class PayNotifyCallBack extends WxPayNotify
{
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        \Yii::info("query:" . json_encode($result), 'fecshop_debug');
        if (array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS") {
                
            return true;
        }
        
        return false;
    }
    
    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        \Yii::info("call back:" . json_encode($data), 'fecshop_debug');
        $notfiyOutput = [];
        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            
            return false;
        }
        //查询订单，判断订单真实性
        if (!$this->Queryorder($data["transaction_id"])) {
            $msg = "订单查询失败";
            
            return false;
        }
        $arr = $this->getDataArray($data);
        
        return \Yii::$service->payment->wxpay->ipnUpdateOrder($arr);
    }
    
    public function getDataArray($data)
    {
        $arr = [];
        foreach ($data as $k => $v) {
            $arr[$k] = $v;
        }
        
        return $arr;
    }
}
