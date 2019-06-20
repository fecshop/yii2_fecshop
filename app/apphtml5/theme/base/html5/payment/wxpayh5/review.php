
<div style="padding:1.6rem">
    <div align="center">
        错误： <?= $errors ?>
    </div>
    <div align="center" style="margin-top:3.6rem">
        <button style="width:210px; height:30px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >重新支付</button>
    </div>

</div>


<script>
function callpay()
	{
		window.location.href="<?=  Yii::$service->url->getUrl('payment/wxpayh5/start'); ?>";
	}

</script>