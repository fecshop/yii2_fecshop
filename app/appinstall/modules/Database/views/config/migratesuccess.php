<?php
    use fec\helpers\CRequest;
?>
<h1>Mysql数据库表初始化完成页面</h1>
<br/>

<?=  $errorInfo  ?>
<?=  $successInfo  ?>

<br/>
<br/>

<p>mysql migrate 数据库初始化完成，您可以进行产品测试数据的安装，
点击按钮<span style="color:#cc0000">安装产品测试数据</span>即可。
</p>

<p>
如果您不想安装产品测试数据，请<span style="color:#cc0000">点击</span>跳过按钮
</p>

<br/>
<br/>
<button type="button" class="btn btn-default  dbNext">安装产品测试数据</button>
<button type="button" class="btn btn-default  dbSkip">跳过</button>

<div class="loadingInfo" style="display:none;">
产品测试数据安装中，请耐心等待，在该过程中请勿刷新页面...
</div>
<script>
	// add to cart js	
	<?php $this->beginBlock('dbNext') ?>
	$(document).ready(function(){
        $(".dbNext").click(function(){
            $(".loadingInfo").show();
            window.location.href="<?= $nextUrl ?>";
        });
        
        $(".dbSkip").click(function(){
            window.location.href="<?= $skipUrl ?>";
        });
        
	});   
	<?php $this->endBlock(); ?>  
	<?php $this->registerJs($this->blocks['dbNext'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 
