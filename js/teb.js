// tab切换效果
$(function(){
	$("#faq_visa_cont > div").not(":first").hide();
		$("#faq_visa_tit > li").hover(function(){
			$(this).addClass("cur").siblings().removeClass("cur");
			var index = $("#faq_visa_tit > li").index(this);
			$("#faq_visa_cont > div").eq(index).show().siblings().hide();
	})
});