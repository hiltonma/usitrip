//显示子级菜单
//.childNodes[0]
//.parentNode

jQuery(document).ready(function(){
	$('ul [class="ul_hide"]').hide();
	
	//显示与隐藏子级菜单
	$("#dir_menu_tree div").click( function(){
		if($(this).parent().parent().find("ul").length != 0){
			if($(this).parent().parent().find("ul").eq(0).css("display") == "none"){
				$(".ul_hide").slideUp();
				$(".div_add").removeClass("div_select");
				$(".div_add").removeClass("div_cut");
				
				$(this).addClass("div_select");
				$(this).addClass("div_cut");
				$(this).parent().parent().find("ul").slideDown(300);
			}else{
				$(this).removeClass("div_select");
				$(this).removeClass("div_cut");
				
				$(this).parent().parent().find("ul").slideUp(300);
			}
		};
	});
	

	//点击连接时在右边的框架显示
	$("a").click(function(){
		if($(this).parent().parent().find("ul").length != 0){
			if($(this).parent().parent().parent().find("ul").eq(0).css("display") == "none"){
				$(".ul_hide").slideUp();
				$(".div_add").removeClass("div_select");
				$(".div_add").removeClass("div_cut");
				
				$(this).parent().addClass("div_select");
				$(this).parent().addClass("div_cut");
				$(this).parent().parent().parent().find("ul").slideDown(300);
			}else{
				$(".div_add").removeClass("div_select");
				$(".div_add").removeClass("div_cut");
				$(this).parent().parent().parent().find("ul").slideUp(300);
			}
			
		}
		
		var mzmain = parent.document.getElementById("MzMain");
		//var mzmain = window.parent.frames['MzMain'];
		if(mzmain!=null){
			mzmain.src=this.href;
			return false;
		}
		
		
	});
	
	// 控制左边菜单的高度自适应 ---by Rocky
	$(window).bind('load scroll resize', function(){
		$(".LeftContentBox").height($(window).height() - 30);
        $(".LeftContent").height($(window).height() - 45);
	});
	
});