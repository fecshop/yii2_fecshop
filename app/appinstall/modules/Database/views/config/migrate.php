<?php
    use fec\helpers\CRequest;
?>
<h1>Mysql数据库表初始化Migrate</h1>
<br/>

<?=  $errorInfo  ?>
<?=  $successInfo  ?>

<br/>
<br/>

<p>此步骤执行：</p>

<p>1.Fecmall 数据库表的migrate，进行数据库的初始化</p>

<p>2.初始化完成后，mysql的表以及索引，将会被建立, 以及初始数据</p>

<p>3.您需要等待一段时间，等待sql的执行完成</p>

<br/>
<br/>
<form action="" method="post">
    <?php echo CRequest::getCsrfInputHtml();  ?>
    <input type="hidden" value="1" name="isPost" />	
    <button type="submit" class="btn btn-default  dbInit">进行数据表初始化</button>
    <span class="d_info" style="margin-left:20px;display:none; color: #cc0000">数据库migrate初始化中，请耐心等待，在该过程中请勿刷新页面...</span>
</form>

<script>
	// add to cart js	
	<?php $this->beginBlock('dbInit') ?>
	$(document).ready(function(){
        $(".dbInit").click(function(){
            $(".d_info").show();
        });
        
	});   
	<?php $this->endBlock(); ?>  
	<?php $this->registerJs($this->blocks['dbInit'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 
