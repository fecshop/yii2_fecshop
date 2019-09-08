<?php
    use fec\helpers\CRequest;
?>
<h1>Mysql数据库表初始化</h1>
<br/>

<?=  $errorInfo  ?>
<?=  $successInfo  ?>

<br/>
<br/>

<?php echo CRequest::getCsrfInputHtml();  ?>
<p>此步骤执行：</p>

<p>1.Fecmall 数据库表的migrate，进行数据库的初始化</p>

<p>2.初始化完成后，mysql的表以及索引，将会被建立, 以及初始数据</p>

<br/>
<br/>

<button type="button" class="btn btn-default  dbInit">进行数据表初始化</button>

<button type="button" class="btn btn-default  install-next" style="display:none;">下一步</button>


<br/>
<br/>
    数据库初始化Log：<span class="logshort" style="display:none;color:#cc0000;">正在进行数据库初始化...</span>
    <span class="logshort-complete" style="display:none;color:green;">数据库初始化完成，您可以点击下一步按钮，进行下一步的安装操作</span>
<br/>
<textarea class="initLog" style="width:1200px;height:300px;"></textarea>


 

<script>
	// add to cart js	
	<?php $this->beginBlock('db_init') ?>
	$(document).ready(function(){
        $(".install-next").click(function(){
            window.location.href="<?= $nextUrl ?>";
        });
		$(".dbInit").click(function(){
            var initUrl = "<?= $initUrl ?>";
            $(".logshort").show();
            $.ajax({
                async:true,
                timeout: 6000,
                type:'post',
                data: {},
                url: initUrl,
                success:function(data, textStatus){ 
                    $(".logshort").hide();
                    $(".initLog").val(data);
                    // #21 /www/web/develop/fecshop/appfront/web/install.php(20): yii\base\Application->run()
                    if (data.indexOf('appfront/web/install.php') != -1) {
                        // 存在错误
                    } else {
                        // 不存在错误, 显示跳转下一步的按钮
                        $(".install-next").show();
                        $(".logshort-complete").show();
                        
                        $(".dbInit").hide();
                    }
                    
                },
                error:function (XMLHttpRequest, textStatus, errorThrown){
                    $(".logshort").hide();
                    $(".initLog").val(XMLHttpRequest.responseText);
                }
            });
                
		});
	});   
	<?php $this->endBlock(); ?>  
	<?php $this->registerJs($this->blocks['db_init'],\yii\web\View::POS_END);//将编写的js代码注册到页面底部 ?>
</script> 
