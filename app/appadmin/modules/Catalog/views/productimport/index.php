<?php
use fec\helpers\CRequest;
?>
<style>
    .dialog .pageContent {background:none;}
    .dialog .pageContent .pageFormContent{background:none;}
</style>
<div class="pageContent">
    <div class="pageHeader" style="height:800px">
        <form name="multiform" id="multiform" action="<?= \fec\helpers\CUrl::getCurrentUrl();  ?>" method="POST" enctype="multipart/form-data">
           <?=  CRequest::getCsrfInputHtml();  ?>
		   <input type="hidden" name="task" value="1" />
            <div class="searchBar">
                <fieldset id="fieldset_table_qbe">
                    <legend style="color:#cc0000">产品上传</legend>
                    <div>
                        <table class="searchContent">
                            <tr>
                                <td>
                                    <label class="col-md-2 control-label"><span style="color:red;font-size:20px;position:relative;top:8px;">*</span>文件上传:</label>
                                </td>
                                <td>
                                    <div class="col-md-4">
                                        <input type="hidden" name="file" value=""><input type="file" id="uploadform-file" name="file">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="col-md-2 control-label">下载样例:</label>
                                </td>
                                <td>
                                    <div class="col-md-4">
                                        <a href="/download/wish/flowanalysis.xlsx" >样例 </a>

                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </fieldset>
                <div class="subBar">
                    <ul>
                        <li><div class="buttonActive"><div class="buttonContent"><button class="serachContent" type="submit">提交</button></div></div></li>
                    </ul>
                    <div class="addstatus" style=" float: left;font-weight: bold;padding: 10px 0;">

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var size = 0;
    $('#uploadform-file').change(function(evt){
        var files = evt.target.files;
        size = files[0].size;
    });
    $(document).ready(function(){
        thiscsrf = $(".thiscsrf").val();
        $("#multiform").submit(function(e){
            var formObj = $(this);
            var formURL = formObj.attr("action");
            var formData = new FormData(this);
            file = $('#uploadform-file').val();
           
            if (!file){
                alertMsg.info('请上传文件');
            
            }else if (500000000 < size){
                alertMsg.info('上传文件大小请不要超过500m');
            }else{
                $.ajax({
                    url: formURL,
                    type: 'POST',
                    data:  formData,
                    dataType: 'json',
                    mimeType:"multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data, textStatus, jqXHR)
                    {
                        status = data.status;
                        if(status == "success"){
                            alert('提交成功！');
                        }else{
                            alert(data.content);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        alert("error,请联系管理员");
                    }
                });
            }

            e.preventDefault(); //Prevent Default action.

        });

    });

</script>