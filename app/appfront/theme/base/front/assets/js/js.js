$(document).ready(function(){
	currentBaseUrl = $(".currentBaseUrl").val();
	$(".top_currency .currency_list ul li").click(function(){
		currency = $(this).attr("rel");
		
		htmlobj=$.ajax({url:currentBaseUrl+"/cms/home/changecurrency?currency="+currency,async:false});
		//alert(htmlobj.responseText);
		location.reload() ;
	});
	$(".top_lang .store_lang").click(function(){
		//http = document.location.protocol+"://";
		currentStore = $(".current_lang").attr("rel");
		changeStore = $(this).attr("rel");
		currentUrl = window.location.href;
		redirectUrl = currentUrl.replace("://"+currentStore,"://"+changeStore);
		//alert(redirectUrl);
		//alert(2);
		location.href=redirectUrl;
	});
	
	// ajax get account login info
	
	loginInfoUrl = currentBaseUrl+"/customer/ajax";
	logoutUrl 	 = $(".logoutUrl").val();
	product_id   = $(".product_view_id").val();
	product_id	 = product_id ? product_id : null;
	jQuery.ajax({
		async:true,
		timeout: 6000,
		dataType: 'json', 
		type:'get',
		data: {
			'currentUrl':window.location.href,
			'product_id':product_id
		},
		url:loginInfoUrl,
		success:function(data, textStatus){ 
			welcome = $('.welcome_str').val();
			logoutStr = $('.logoutStr').val();
			if(data.loginStatus){
				customer_name = data.customer_name;
				str = '<span id="welcome">'+welcome+' '+customer_name+',</span>';
				str += '<span id="js_isNotLogin">';
				str += '<a href="'+logoutUrl+'" rel="nofollow">'+logoutStr+'</a>';
				str += '</span>';
				$(".login-text").html(str);
			}
			if(data.favorite){
				$(".myFavorite_nohove").addClass("act");
				$(".myFavorite_nohove a").addClass("act");
			}
			if(data.favorite_product_count){
				$("#js_favour_num").html(data.favorite_product_count);
			}
			if(data.csrfName && data.csrfVal && data.product_id){
				$(".product_csrf").attr("name",data.csrfName);
				$(".product_csrf").val(data.csrfVal);
			}
			if(data.cart_qty){
				$("#js_cart_items").html(data.cart_qty);
			}
			
			
		},
		error:function (XMLHttpRequest, textStatus, errorThrown){}
	});
	
	$("#goTop").click(function(){
		$("html,body").animate({scrollTop:0},"slow");
	});
	
	$("#goBottom").click(function(){
		var screenb = $(document).height(); 
								
		$("html,body").animate({scrollTop:screenb},"slow");
	});
	
});

