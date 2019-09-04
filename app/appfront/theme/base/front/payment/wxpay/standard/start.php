<!-- shortcut -->
<div class="shortcut">
    <div class="w">
        <a class="s-logo" href="<?= Yii::$service->url->homeUrl()  ?>" target="_blank">
            <img width="170" height="28" alt="fecshop收银台" src="<?= Yii::$service->image->getImgUrl('appfront/custom/logo.png'); ?>"  />
        </a>
        <ul class="s-right">
            <?php if($customer_email): ?>
            <li id="loginbar" class="s-item fore1">
                
                <a href="<?= Yii::$service->url->getUrl('customer/account/index')  ?>" target="_blank" class="link-user">
                    <?=  $customer_email ?>
                </a>&nbsp;&nbsp;
                <a href="<?= Yii::$service->url->getUrl('/customer/account/logout') ?>" class="link-logout">
                    退出
                </a>
                
            </li>
            <li class="s-div">|</li>
            <li class="s-item fore2">
                <a class="op-i-ext" href="<?= Yii::$service->url->getUrl('/customer/order') ?>">
                    我的订单
                </a>
            </li>
            
            <?php endif; ?>
        </ul>
        <span class="clr"></span>
    </div>
</div>
 

<div class="main">
    <div class="w">
        <div class="order clearfix order-init order-init-oldUser-noQrcode">
            <div class="o-left">
                <h3 class="o-title">
                    订单提交成功，请尽快付款！订单号：<?= $increment_id  ?>
                </h3>
                <p class="o-tips">
                    <span class="o-tips-time" id="deleteOrderTip">
                    </span>
                </p>
            </div>
            <!-- 订单信息 end --><!-- 订单金额 -->
            <div class="o-right" style="float:left">
                <div class="o-price" style="text-align:left;">
                    <em>应付金额</em>
                    <strong><?= $total_amount ?></strong>
                    <em>元</em>
                </div>
                <div class="o-detail"  style="float:center">
                    <!--
                    <a onclick="javascript:" href="javascript:;">
                        订单详情
                        <i></i>
                    </a>
                    -->
                </div>
            </div>
            <!-- 订单金额 end -->
            <div class="o-list j_orderList" id="listPayOrderInfo"><!-- 单笔订单 -->
                <!-- 多笔订单 end -->
            </div>
        </div>

        <!-- order 订单信息 end -->
        <!-- payment 支付方式选择 -->
        <div class="payment">
            <!-- 微信支付 -->
            <div class="pay-weixin">
                <div class="p-w-hd">微信扫码支付</div>
                <div class="p-w-bd" style="position:relative">
                    <div class="j_weixinInfo" style="position:absolute; top: -36px; left: 130px;">
                        请在<?= ceil($expireTime / 60) ?>分钟内完成扫码支付（这里需要使用真实的微信账户，测试后不能退款，因此，建议您
                        使用该产品测试支付：<a href="https://fecshop.appfront.fancyecommerce.com/cn/3232-86679774" target="_blank">微信支付测试产品</a>）
                        <span class="j_qrCodeCountdown font-bold font-red"></span>
                    </div>
                    <div class="p-w-box">
                        <div class="pw-box-hd">
                            <img id="weixinImageURL" src="<?= $scan_code_img_url ?>" width="298" height="298">
                        </div>
                        <div class="pw-retry j_weixiRetry" style="display: none;">
                            <a class="ui-button ui-button-gray j_weixiRetryButton" href="javascript:getWeixinImage2();">
                                获取失败 点击重新获取二维码  
                            </a>
                        </div>
                        <div class="pw-box-ft" style="height:25px;padding:8px 0 8px 125px;background:url(i/icon-red.png) 50px 8px no-repeat #ff7674">
                            <p>请用微信扫码支付！</p>
                        </div>
                    </div>
                    <div class="p-w-sidebar"></div>
                </div>
            </div>
            <!-- 微信支付 end -->
            <!-- payment-change 变更支付方式 -->
            <div class="payment-change">
                <a class="pc-wrap" onclick="window.history.go(-1)">
                    <i class="pc-w-arrow-left">&lt;</i>
                    <strong>选择其他支付方式</strong>
                </a>
            </div>
            <!-- payment-change 变更支付方式 end -->
        </div>
        <!-- payment 支付方式选择 end -->
    </div>
</div>


<!-- 收银台 footer -->
<div class="p-footer">
    <div class="pf-wrap w">
        <div class="pf-line">
            <span class="pf-l-copyright">Copyright ?2016-2017  fecshop 版权所有</span>
            <img width="185" height="20" src="<?= Yii::$service->image->getImgUrl('appfront/custom/logo.png'); ?>">
        </div>
    </div>
</div>       

<script type="text/javascript">
var out_trade_no = "<?= $increment_id ?>";
var timeForAjaxBegin = 4000; // ajax首次获取支付状态的毫秒
var timeForAjaxcycle = 4000; // ajax循环获取支付状态的毫秒间隔
var timeForAjaxcycleMaxCount = 100; // ajax循环的最大次数。
function queryOrderBankState(){
    if(count > timeForAjaxcycleMaxCount){ //设置查询多少次交易结果
        clearInterval(qrTimer);
        alert('支付超时，请重新下单支付！');
        window.location.href = '<?= Yii::$service->url->getUrl('checkout/onepage');  ?>';
    }
    count++;
    $.ajax({
        type: "GET",
        url: "<?= $trace_success_url ?>", //fecshop微信支付服务中判断是否交易成功的接口
        data: {"out_trade_no": out_trade_no },
        dataType: "json",
        timeout: 4000,
        success: function(result) {
            if(result.code == 200){
                //直接跳到成功页
                //window.location.href = history.go(-1);
                window.location.href = '<?= Yii::$service->url->getUrl('/payment/success') ?>';
               // window.location.href = 'payment/success';
            }else
            {
               // alert('else');
            }
        },
        error: function(xhr){
        	 //alert('error');
		}
    });
}

var count = 0, qrTimer = null;
var qrcodeImageURL = "";
//alert('aa');
setTimeout(function () {
    if (!qrcodeImageURL) {
   //   if(true){
    	//alert('bb');
        qrTimer = setInterval(queryOrderBankState, timeForAjaxcycle);
    }
}, timeForAjaxBegin); //多少秒后开始执行

</script>