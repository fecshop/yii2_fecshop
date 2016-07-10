$(document).ready(function(){
	baseurl = $(".baseurl").val();
	$(".top_currency .currency_list ul li").click(function(){
		currency = $(this).attr("rel");
		
		htmlobj=$.ajax({url:baseurl+"/cms/home/changecurrency?currency="+currency,async:false});
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
	
});