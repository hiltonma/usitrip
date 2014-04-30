//<![CDATA[


$=function(id){return document.getElementById(id);}

var oldSelectedTabId = null;


initTab=function(t){
  var tab = $(t);
  if (!tab)return;
  var hs = tab.getElementsByTagName('a');
  var l = hs.length;
  for (var i = 0;i < l ;i++ ){
    var a = hs[i];
    a.onclick=function(ev){
      this.blur();//
      toggleTab(this);
    }
    if (hasClass(a.parentNode,"s"))
      toggleTab(a);
    else
     addClass(getTabObj(a.id,"content"),"hidden");
    a.href="javascript:void(0)"
  }
  if (!oldSelectedTabId)
    toggleTab(hs[0]);
}


toggleTab = function(a){
  if (!a)return;
  if (oldSelectedTabId&&oldSelectedTabId==a.id)return;
  else if (oldSelectedTabId){
    removeClass(getTabObj(oldSelectedTabId,"href").parentNode,"s");
    addClass(getTabObj(oldSelectedTabId,"content"),"hidden");
  }
  addClass(a.parentNode, "s");
  oldSelectedTabId = a.id;
  removeClass(getTabObj(a.id,"content"),"hidden");
}


getTabObj=function(id,type){
  var TYPE = {content:"c_",href:"h_"}
  var r = /(c_|h_)/g
  return $(id.replace(r,TYPE[type]));
}

hasClass = function(obj,className){
  if (!obj||!obj.className)return false;
  return new RegExp("\\b"+className+"\\b","g").test(obj.className);
}


addClass = function(obj,className){
  if (!obj)return false;
  obj.className = obj.className + " " + className+" ";
}


removeClass = function(obj,className){
  if (!obj||!obj.className)return false;
  obj.className = obj.className.replace(new RegExp("\\b"+className+"\\b","g"),"").replace(/^\s*|\s$/g,"")
}
//]]>
var curObj= null; 
function document_onclick() { 
if(window.event.srcElement.tagName=='A'||window.event.srcElement.tagName=='TD'){ 
if(curObj!=null) 
curObj.style.background=''; 
curObj=window.event.srcElement;
curObj.style.background='#223C6A'; 
} 
} 

//<![CDATA[
var cur_index=1
var num=4 //该值记录标签的个数
var settime
function GetObj(objName){
	if(document.getElementById){
		return eval('document.getElementById("' + objName + '")');
	}else if(document.layers){
		return eval("document.layers['" + objName +"']");
	}else{
		return eval('document.all.' + objName);
	}
}
function change_Menu(index){
	for(var i=1;i<=num;i++){/* 最多支持8个标签 */
		if(GetObj("con"+i)&&GetObj("m"+i)){
			GetObj("con"+i).style.display = 'none';
			GetObj("m"+i).className = "menu"+i+"Off";
		}
	}
	if(GetObj("con"+index)&&GetObj("m"+index)){
		GetObj("con"+index).style.display = 'block';
		GetObj("m"+index).className = "menu"+index+"On";
	}
	cur_index=index
	if(cur_index<num){
	   cur_index++
	  }
	else{
	    cur_index=1
	  }
		
}
function Menu(c_index){
  clearTimeout(settime)
  change_Menu(c_index)
 }    
   
//]]>

function showdh(n){
	for(var i=1;i<=7;i++){
		document.getElementById("dh"+i).className="unsel"
					
	}
	document.getElementById("dh"+n).className="sel"
}

//<!CDATA[
function g(o){return document.getElementById(o);}
if (document.attachEvent){
  addEvent = function(o,evn,f){o.attachEvent("on"+evn,f)}
}
else if (document.addEventListener){
  addEvent = function(o,evn,f){o.addEventListener(evn,f,false)}
}

function initTab1(nid,cid,action,defaultIndex){
  var ls = g(nid).getElementsByTagName('li');
  var cc = g(cid).childNodes;
  var c = [];
  var index = defaultIndex?defaultIndex:0;
  for (var i = 0 ; i < cc.length ; i ++)if(cc[i].nodeType==1)c.push(cc[i]);
  if (ls.length!=c.length)
    throw({description:'菜单和内容数量不对应'});
  for (var i = 0 ; i < ls.length ; i ++){
    ls[i].index = i;
    if (i==index){
      ls[i].className = 'hovertab';
      c[i].className = 'dis1'
      ls[i].parentNode.last = ls[i];
    }
    addEvent(ls[i],action,function(e){
      var self = window.event?window.event.srcElement:e?e.target:null;
      if (self.parentNode.last){
        self.parentNode.last.className = 'normaltab';
        c[self.parentNode.last.index].className = 'undis1';
      };
      self.className = 'hovertab';
      c[self.index].className = 'dis1';
      self.parentNode.last = self;
    });
  }
}

function show_all(type)
	{
		var tmp_arr = document.getElementsByTagName("tr");
		for(var i = 0; i < tmp_arr.length; i++)
		{
			 if(tmp_arr[i].className == type)
			{
				 tmp_arr[i].style.display = "";
			}
		}
	}
	//Function to collepse all divs
	function hide_all(type)
	{
		var tmp_arr = document.getElementsByTagName("tr");
		for(var i = 0; i < tmp_arr.length; i++)
		{
			 if(tmp_arr[i].className == type)
			{
				 tmp_arr[i].style.display = "none";
			}
		}
	}
	//Function to toggle a div
	function toggel_div(divid)
	{
		if(eval("document.getElementById('" +  divid + "').style.display") == '')
			eval("document.getElementById('" +  divid + "').style.display = 'none'");
		else
			eval("document.getElementById('" +  divid + "').style.display = ''");
	}
	
	function toggel_div_show(divid)
	{
		if(eval("document.getElementById('" +  divid + "').style.display") == 'none'){
			eval("document.getElementById('" +  divid + "').style.display = ''");
		}

	}
	
//]]>
