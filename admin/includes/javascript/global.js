$(function(){
    //leftCon auto height
    $(window).bind('load scroll resize', function(){
		$("#LeftCon").height($(window).height() - 39);
        $("#LeftConBox").height($(window).height() - 51);
	});
	
	
	//ChangeSys
	$("#ChangeSys").mouseover(function(){
	    $("#ChangeSysCon").show();
	    $(".changeSysTitle").addClass("changeSysTitleHover");
	    
	});
	$("#ChangeSys").mouseleave(function(){
	    $("#ChangeSysCon").hide();
	    $(".changeSysTitle").removeClass("changeSysTitleHover");
	});
	
	$("#ChangeSysCon li").mouseover(function(){
	    $(this).addClass("hover");
	});
	$("#ChangeSysCon li").mouseleave(function(){
	    $(this).removeClass("hover");
	});
	
	
	//leftIframe
	$("#LeftConBox a:first").addClass("active");
	$("#LeftConBox a").click(function(){
	    $("#LeftConBox a").removeClass("active");
	    $(this).addClass("active");
	});

	//topSwitch
	$("#HeaderSwitch").toggle(function(){
		$("#Header").hide();
		$(this).addClass("topSwitchDown");
	},function(){
		$("#Header").show();
		$(this).removeClass("topSwitchDown");
	});

	//leftSwitch
	$("#LeftSwitch").toggle(function(){
		$("#Left").hide();
		$(this).addClass("leftSwitchRight");
	},function(){
		$("#Left").show();
		$(this).removeClass("leftSwitchRight");
	});
	
	//conSwitch
	$(".conSwitchH").click(function(){
	    $(".conSwitchCon:eq("+$(".conSwitchH").index(this)+")").slideToggle();
	    $(this).toggleClass("conSwitchHShow");
	});

});