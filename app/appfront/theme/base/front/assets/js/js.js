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
	$(".product_sort").change(function(){	
		url = $(this).find("option:selected").attr('url');
		window.location.href = url;
	});
	$(".product_num_per_page").change(function(){
		url = $(this).find("option:selected").attr('url');
		window.location.href = url;
	});
	
	$(".filter_attr_info a").click(function(){
		
		if($(this).hasClass("checked")){
			$(this).removeClass("checked");
		}else{
			$(this).parent().find("a.checked").removeClass("checked");
			$(this).addClass("checked");
		}
	});
	
});