$(function(){
	$(".righttxt").hover(function(e){
		$(this).css({"background":"#F8F5F5"});
	},function(e){$(this).css({"background":""});})


	$(".boxtop").hover(function(e){
		$(this).css({"background":"#F8F5F5"});
 },function(e){$(this).css({"background":""});})



var x = jQuery('div.content').offset();

jQuery('#fixed-top1').css('left',jQuery('div.content').width()+x.left+10+'px');
})


window.onresize = function(){
	var x = jQuery('div.content').offset();

jQuery('#fixed-top1').css('left',jQuery('div.content').width()+x.left+10+'px');
}

