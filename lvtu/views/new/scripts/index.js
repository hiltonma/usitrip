// JavaScript Document
$(document).ready(function(){
	$(".J-npc").hover(function(){
		$(this).find(".npc_bg").stop().show().animate({opacity:0.8},500);
		return false;
	},function(){
		$(this).find(".npc_bg").stop().show().animate({opacity:0},200);
	});
});
