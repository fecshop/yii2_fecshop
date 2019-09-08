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
<p>此步骤执行：</p>

<p>1.产品测试数据sql安装</p>

<p>2.产品的图片复制</p>

<p>3.请勿重复执行该步骤，因为插入的数据只能插入依次，多次插入将会报错，
如果您已经执行了插入的sql，点击跳过进入下一步即可。</p>

<p>4.如果您不想安装测试数据，点击跳过按钮，进入下一步。</p>

<br/>
<br/>

<button type="button" class="btn btn-default  testDataInit">测试产品数据安装</button>

<button type="button" class="btn btn-default  testDataSkip">跳过</button>

<button type="button" class="btn btn-default  install-next" style="display:none;">下一步</button>


<br/>
<br/>
    测试产品数据安装：<span class="logshort" style="display:none;color:#cc0000;">正在进行测试产品数据安装...</span>
    <span class="logshort-complete" style="display:none;color:green;">测试产品数据安装完成，您可以点击下一步按钮，进行下一步的安装操作</span>
<br/>
<textarea class="initLog" style="width:1200px;height:300px;"></textarea>

  
 

<script>
	// add to cart js	
	<?php $this->beginBlock('db_init') ?>
	$(document).ready(function(){
        $(".install-next").click(function(){
            window.location.href="<?= $nextUrl ?>";
        });
        $(".testDataSkip").click(function(){
            window.location.href="<?= $nextUrl ?>";
        });
		$(".testDataInit").click(function(){
            var initUrl = "<?= $initUrl ?>";
            $(".logshort").show();
            $.ajax({
                async:true,
                timeout: 6000,
                type:'post',
                dataType: 'json', 
                data: {},
                url: initUrl,
                success:function(data, textStatus){ 
                    $(".logshort").hide();
                    if(data.status == 'success'){
                        $(".logshort-complete").show();
                        $(".testDataInit").hide();
                        $(".testDataSkip").hide();
                        $(".install-next").show();
                        
                    } else if(data.status == 'fail'){
                        $(".initLog").val(data.info);
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
