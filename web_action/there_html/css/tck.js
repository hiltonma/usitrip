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
leftw=(bodywt-$(".show_1").width())/2;

$(".show_1").css({"left":leftw+"px","top":s_top+20+"px"});
$(".show_1").show(400);})

})