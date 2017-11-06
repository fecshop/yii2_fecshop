<?php
require_once "../lib/WxPay.Api.php";
//require_once "../lib/WxPay.MicroPay.php";


if(isset($_REQUEST["bill_date"]) && $_REQUEST["bill_date"] != ""){
	$bill_date = $_REQUEST["bill_date"];
    $bill_type = $_REQUEST["bill_type"];
	$input = new WxPayDownloadBill();
	$input->SetBill_date($bill_date);
	$input->SetBill_type($bill_type);
	$file = WxPayApi::downloadBill($input);
	echo $file;
	//TODO 对账单文件处理
    exit(0);
}
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title>微信支付样例-查退款单</title>
</head>
<body>  
	<form action="#" method="post">
        <div style="margin-left:2%;">对账日期：</div><br/>
        <input type="text" style="width:96%;height:35px;margin-left:2%;" name="bill_date" /><br /><br />
        <div style="margin-left:2%;">账单类型：</div><br/>
        <select style="width:96%;height:35px;margin-left:2%;" name="bill_type">
		  <option value ="ALL">所有订单信息</option>
		  <option value ="SUCCESS">成功支付的订单</option>
		  <option value="REFUND">退款订单</option>
		  <option value="REVOKED">撤销的订单</option>
		</select><br /><br />
       	<div align="center">
			<input type="submit" value="下载订单" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" />
		</div>
	</form>
</body>
</html>