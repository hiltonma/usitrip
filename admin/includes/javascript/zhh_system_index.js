function ddd(obj,obj2, sType) { 
	var oDiv = document.getElementById(obj); 
	var oDiv2 = document.getElementById(obj2); 
	if (sType == 'show') { oDiv.style.display = 'block';oDiv2.className = 'changeXtSelect';} 
	if (sType == 'hide') { oDiv.style.display = 'none';oDiv2.className = 'changeXt';} 
} 

jQuery(document).ready(function(){
	//左边按钮
	$("#LeftShowtit").click(function(){
		if(this.className!="LeftShowselect"){
			$("#LeftShow_content").hide(0);
			this.className="LeftShowselect";
		}else{
			$("#LeftShow_content").show(0);
			this.className="LeftShow";
		}
	});
	//顶端按钮
	$("#TopShowtit").click(function(){
		if(this.className!="TopShowselect"){
			$("#TopShow_content").hide(0);
			this.className="TopShowselect";
		}else{
			$("#TopShow_content").show(0);
			this.className="TopShow";
		}
	});

	//处理在IE6下面li hover的问题
	if(document.all){  
	  sfHover = function() {
	  var sfEls = document.getElementById("nav").getElementsByTagName("LI");
	  for (var i=0; i<sfEls.length; i++) {
	  sfEls[i].onmouseover=function() {
	  this.className+=" sfhover";
	  }
	  sfEls[i].onmouseout=function() {
	  this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
	  }
	  }
	  }
	  if (window.attachEvent) window.attachEvent("onload", sfHover);
	}
	

});