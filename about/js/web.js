function closediv(mc){
$(mc).hide(400);
}

function mrfd(){
$(".leftdh").css({"top":"590px"});
}
$(function(){

$(".leftdh").css({"left":((($("body").width()-$(".conter").width())/2)-161)+"px"});

var bodywt=$("body").width();
$(".show1").click(function(){
	
	s_top = document.documentElement.scrollTop;
	if(!s_top){
		s_top = document.body.scrollTop;
	}
	var add = 50;
	if (s_top<120) {
		add = 150;
	}
	leftw=(bodywt-$(".show_1").width())/2;
	
	$(".show_1").css({"left":leftw+"px","top":s_top+add+"px"});
	$(".show_1").show(400);
})


$(".show2").click(function(){

	s_top = document.documentElement.scrollTop;
	if(!s_top){
		s_top = document.body.scrollTop;
	}
	var add = 50;
	if (s_top<120) {
		add = 150;
	}
	leftw=(bodywt-$(".show_2").width())/2;
	
	$(".show_2").css({"left":leftw+"px","top":s_top+add+"px"});
	$(".show_2").show(400);
})

$(".show3").click(function(){

s_top = document.documentElement.scrollTop;
if(!s_top){
s_top = document.body.scrollTop;
}
leftw=(bodywt-$(".show_3").width())/2;

$(".show_3").css({"left":leftw+"px","top":s_top+50+"px"});
$(".show_3").show(400);
})


$(".show4").click(function(){

s_top = document.documentElement.scrollTop;
if(!s_top){
s_top = document.body.scrollTop;
}
leftw=(bodywt-$(".show_4").width())/2;

$(".show_4").css({"left":leftw+"px","top":s_top+20+"px"});
$(".show_4").show(400);
})







})



