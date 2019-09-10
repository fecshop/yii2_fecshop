<?php
    use fec\helpers\CRequest;
?>
<h1>测试产品数据安装</h1>
<br/>

<?=  $errorInfo  ?>
<?=  $successInfo  ?>

<br/>
<br/>

<?php echo CRequest::getCsrfInputHtml();  ?>
<p>产品测试数据安装操作：</p>

<p>1.产品测试数据sql安装</p>

<p>2.产品的图片复制</p>

<p>3.<b>请勿重复执行该步骤</b>，操作成功后，请勿刷新页面，因为插入的测试数据多次插入将会报错，
如果您已经执行了插入的sql，点击跳过进入下一步即可。</p>

<p>4.如果您进行了重复操作，请清空数据库的表，从头开始安装</p>

<br/>
<br/>

<button type="button" class="btn btn-default  install-next" >下一步</button>


<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>

<script>
	// add to cart js	
	<?php $this->beginBlock('db_init') ?>
	$(document).ready(function(){
        $(".install-next").click(function(){
            window.location.href="<?= $nextUrl ?>";
        });
		
	});   
	<?php $this->endBlock(); ?>  
	<?php $this->registerJs($this->blocks['db_init'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 
