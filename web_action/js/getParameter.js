/*
	get and send url Parameter
	2013-05-30 by xiaoming
*/

var xmlhttp;
if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
	xmlhttp=new XMLHttpRequest();
}else{// code for IE6, IE5
	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
if(top.location.search){
	xmlhttp.open('GET','http://' + top.location.hostname+ '/' + top.location.search,true);
	xmlhttp.send();
}