<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
use yii\helpers\Html;
use fec\helpers\CRequest;
use fec\helpers\CUrl;
use fecadmin\models\AdminRole;

/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
?>
<style>
    .checker {
        float: left;
    }

    .dialog .pageContent {
        background: none;
    }

    .dialog .pageContent .pageFormContent {
        background: none;
    }

    .edit_p {
        display: block;
        height: 35px;
    }

    .edit_p label {
        float: left;
        line-height: 20px;
        min-width: 110px;
    }

    .edit_p input {
        width: 700px;
    }

    .tabsContent .tabsContent .edit_p label {
        min-width: 104px;
    }

    .edit_p .tier_price input {
        width: 100px;
    }

    .tier_price table thead tr th {
        background: #ddd none repeat scroll 0 0;
        border: 1px solid #ccc;
        padding: 4px 10px;
        width: 100px;
    }

    .tier_price table tbody tr td {
        background: #fff;
        border-right: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        padding: 3px;
        width: 100px;
    }

    .custom_option_list table thead tr th {
        background: #ddd none repeat scroll 0 0;
        border: 1px solid #ccc;
        padding: 4px 10px;
        width: 100px;
    }

    .custom_option_list table tbody tr td {
        background: #fff;
        border-right: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        padding: 3px;
        width: 100px;
    }

    .edit_p .tier_price input.tier_qty {
        width: 30px;
    }

    .custom_option {
        padding: 10px 5px;
    }

    .custom_option span {
        margin: 0 2px 0 10px;
    }

    .custom_option .nps {
        float: left;
        margin: 0 0 10px 0
    }

    .custom_option_img_list img {
        cursor: pointer;
    }
</style>

<script>

    $(document).ready(function () {
        $(document).off("change").on("change", ".attr_group", function () {
            //alert(2222);
            options = {};
            val = $(this).val();
            pm = "?attr_group=" + val;
            currentPrimayInfo = $(".primary_info").val();
            currentPrimayInfo = currentPrimayInfo ? '&' + currentPrimayInfo : '';
            url = '<?= CUrl::getUrl("catalog/productinfo/manageredit"); ?>' + pm + currentPrimayInfo;
            $.pdialog.reload(url, options);
        });
    });


    function getCategoryData(product_id, i) {
        $.ajax({
            url: '<?= CUrl::getUrl("catalog/productinfo/getproductcategory", ['product_id' => $product_id]); ?>',
            async: false,
            timeout: 80000,
            dataType: 'json',
            type: 'get',
            data: {
                'product_id': product_id,
            },
            success: function (data, textStatus) {
                if (data.return_status == "success") {
                    jQuery(".category_tree").html(data.menu);
                    // $.fn.zTree.init($(".category_tree"), subMenuSetting, json);
                    if (i) {
                        $("ul.tree", ".dialog").jTree();
                    }
                }
            },
            error: function () {
                alert('加载分类信息出错');
            }
        });
    }

    function thissubmit(thiss) {
        // product image
        main_image_image = $('.productimg input[type=radio]:checked').val();
        main_image_label = $('.productimg input[type=radio]:checked').parent().parent().find(".image_label").val();
        main_image_sort_order = $('.productimg input[type=radio]:checked').parent().parent().find(".sort_order").val();
        main_image_is_thumbnails = $('.productimg input[type=radio]:checked').parent().parent().find(".is_thumbnails").val();
        main_image_is_detail = $('.productimg input[type=radio]:checked').parent().parent().find(".is_detail").val();
        //alert(main_image_image+main_image_label+main_image_sort_order);
        if (main_image_image) {
            image_main = main_image_image + '#####' + main_image_label + '#####' + main_image_sort_order + '#####' + main_image_is_thumbnails + '#####' + main_image_is_detail;
            $(".tabsContent .image_main").val(image_main);
        } else {
            alert('您至少上传并选择一张主图');
            //DWZ.ajaxDone;
            return false;
        }
        image_gallery = '';
        $('.productimg input[type=radio]').each(function () {
            if (!$(this).is(':checked')) {
                gallery_image_image = $(this).val();
                gallery_image_label = $(this).parent().parent().find(".image_label").val();
                gallery_image_sort_order = $(this).parent().parent().find(".sort_order").val();
                gallery_image_is_thumbnails = $(this).parent().parent().find(".is_thumbnails").val();
                gallery_image_is_detail = $(this).parent().parent().find(".is_detail").val();
                //alert(gallery_image_image+gallery_image_label+gallery_image_sort_order);
                image_gallery += gallery_image_image + '#####' + gallery_image_label + '#####' + gallery_image_sort_order + '#####' + gallery_image_is_thumbnails + '#####' + gallery_image_is_detail + '|||||';
            }
        });
        $(".tabsContent .image_gallery").val(image_gallery);
        //custom_option
        i = 0;
        custom_option = new Object();
        jQuery(".custom_option_list tbody tr").each(function () {
            option_header = new Object();
            $(this).find("td").each(function () {
                rel = $(this).attr("rel");

                if (rel != 'image') {
                    if (rel) {
                        option_header[rel] = $(this).html();
                    }
                } else {
                    rel = $(this).find("img").attr("rel");
                    option_header['image'] = rel;
                }

            });
            custom_option[i] = option_header;
            i++;
        });

        custom_option = JSON.stringify(custom_option);
        //alert(custom_option);
        jQuery(".custom_option_value").val(custom_option);

        cate_str = "";
        jQuery(".category_tree div.ckbox.checked").each(function () {
            cate_id = jQuery(this).find("input").val();
            cate_str += cate_id + ",";
        });


        jQuery(".category_tree div.ckbox.indeterminate").each(function () {
            cate_id = jQuery(this).find("input").val();
            cate_str += cate_id + ",";
        });

        jQuery(".inputcategory").val(cate_str);

        tier_price_str = "";
        $(".tier_price table tbody tr").each(function () {
            tier_qty = $(this).find(".tier_qty").val();
            tier_price = $(this).find(".tier_price").val();
            if (tier_qty && tier_price) {
                tier_price_str += tier_qty + '##' + tier_price + "||";
            }
        });
        //alert(tier_price_str);
        jQuery(".tier_price_input").val(tier_price_str);
        //alert($(".tier_price_input").val());
        return validateCallback(thiss, dialogAjaxDoneCloseAndReflush);
    }
</script>

<div class="pageContent">
<!--    <form id="form1" method="post" action="<?/*= $importUrl */?>" class="pageForm required-validate"
          onsubmit="return validateCallback(this,dialogAjaxDone);">-->
        <form id="form1" method="post" action="<?= $importUrl ?>" class="pageForm required-validate" enctype="multipart/form-data" onsubmit="return iframeCallback(this);">
        <div class="pageFormContent" layouth="56">
            <p>模板下载：<a target="_blank" href="./template/batch_import.xlsx" style="color: red">点击下载</a></p>
            <p>
                <label>
                    &nbsp;</label>
                <input id="file_upload" name="file_upload" type="file" multiple="true"/>

                <script type="text/javascript">
                    $(function () {
                        //alert("aaaa");
                        $('#file_upload').uploadify({
                            'formData': {
                                'folder': 'UploadFile'
                            },
                            'swf': '/js/uploadify/uploadify.swf',
                            'uploader': '/Handler/UploadHandler.ashx',
                            'onUploadSuccess': function (file, data, response) {
                                $("#excel").attr("value", data);
//                                alert(file);
                                alert(data);
                                alert(response);
                            }


                        });
                    });
                </script>
            </p>
        </div>
        <div class="formBar">
            <ul>
                <li>
                    <div class="buttonActive">
                        <div class="buttonContent">
                            <button type="submit">
                                导入
                            </button>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="button">
                        <div class="buttonContent">
                            <button type="button" class="close">
                                取消
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>	

