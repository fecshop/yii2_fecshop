<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use fec\helpers\CRequest;
/** 
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
?>
<div class="pageContent" >
	<form method="post" action="<?=  Yii::$service->url->getUrl('catalog/categoryupload/managerupload') ?>" enctype="multipart/form-data" class="pageForm required-validate" onsubmit="return iframeCallback(this);">
		<div class="pageFormContent" layoutH="56">
			<?=  CRequest::getCsrfInputHtml();  ?>
            <fieldset id="fieldset_table_qbe">
					<legend style="color:#888">分类文件上传</legend>
                    <div style="padding:10px;">
                        <input name="file" type="file"  style="float:left"/>
                        <br><br><br>
                        <div style="line-height:30px;">
                            1.此部分功能为通过excel文件，批量上传分类，您可以点击<a style="color:blue" href="<?= Yii::$service->url->getUrl('download/fecshop_category_upload.zip') ?>" target="_blank">这里下载</a>
                            Excel样式
                            <br/>
                            1.1 上面下载是一个例子，里面包含excel文件和图片文件夹，您需要将图片文件夹上传到 @appimage/common/media/catalog/category/
                            <br/>
                            1.2. excel文件里面有一行数据，是一个例子
                            <br/>
                            1.3 README.txt是一个关于excel文件的说明，请仔细阅读
                            <br/>
                            
                            1.4 文件上传后，系统会通过category_id查找，是否存在存在分类，如果存在，则更新，如果不存在则添加。
                            <br/>
                            
                            1.5 注意，category_id不能为空，否则无法导入
                        </div>
                        <div style="line-height:30px;">    
                            2.按照格式填写Excel产品数据
                        </div >
                        <div style="line-height:30px;">    
                            3.选择文件，提交即可
                            
                        </div>
                        <div style="line-height:30px;">    
                            
                            4.更详细的关于分类上传，参看文档: <a  style="color:blue" href="http://www.fecmall.com/doc/fecshop-guide/instructions/cn-2.0/guide-fecmall_category_product_mutil_upload.html">Fecmall分类产品Excel表格批量上传文档</a>
                        </div>
                    </div>
                    
            </fieldset>
            
		</div>
		
        <div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li>
                    <div class="buttonActive"><div class="buttonContent"><button onclick="func('accept')"  value="accept" name="accept" type="submit">上传</button></div></div>
                </li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
        
	</form>
</div>