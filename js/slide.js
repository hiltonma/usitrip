// 签证首页滑动板块
$(function(){
	$(".visa_list > li:not(:first)").hide();  
	$(".visa_list li").click(function(){
			if($(this).next().css('display') == "none"){
				$(this).parents(".visa_list")
					   .find("li").removeClass("current")
					   .end()
					   .find("div").stop(true,true).slideUp("slow");
				$(this).addClass("current")
					   .next("div").slideDown("slow");
			}
	})	
})