
$=function(id){return document.getElementById(id);};var oldSelectedTabId=null;initTab=function(t){var tab=$(t);if(!tab)return;var hs=tab.getElementsByTagName('a');var l=hs.length;for(var i=0;i<l;i++){var a=hs[i];a.onclick=function(ev){this.blur();toggleTab(this);}
if(hasClass(a.parentNode,"s"))
toggleTab(a);else
addClass(getTabObj(a.id,"content"),"hidden");a.href="javascript:void(0)";}
if(!oldSelectedTabId){toggleTab(hs[0]);}}
toggleTab=function(a){if(!a)return;if(oldSelectedTabId&&oldSelectedTabId==a.id)return;else if(oldSelectedTabId){removeClass(getTabObj(oldSelectedTabId,"href").parentNode,"s");addClass(getTabObj(oldSelectedTabId,"content"),"hidden");}
addClass(a.parentNode,"s");oldSelectedTabId=a.id;removeClass(getTabObj(a.id,"content"),"hidden");}
getTabObj=function(id,type){var TYPE={content:"c_",href:"h_"};var r=/(c_|h_)/g;return $(id.replace(r,TYPE[type]));}
hasClass=function(obj,className){if(!obj||!obj.className)return false;return new RegExp("\\b"+className+"\\b","g").test(obj.className);}
addClass=function(obj,className){if(!obj)return false;obj.className=obj.className+" "+className+" ";}
removeClass=function(obj,className){if(!obj||!obj.className)return false;obj.className=obj.className.replace(new RegExp("\\b"+className+"\\b","g"),"").replace(/^\s*|\s$/g,"");}
var curObj=null;function document_onclick(){if(window.event.srcElement.tagName=='A'||window.event.srcElement.tagName=='TD'){if(curObj!=null)
curObj.style.background='';curObj=window.event.srcElement;curObj.style.background='#223C6A';}}
var cur_index=1;var num=4;var settime;function GetObj(objName){if(document.getElementById){return eval('document.getElementById("'+objName+'")');}else if(document.layers){return eval("document.layers['"+objName+"']");}else{return eval('document.all.'+objName);}}
function change_Menu(index){for(var i=1;i<=num;i++){if(GetObj("con"+i)&&GetObj("m"+i)){GetObj("con"+i).style.display='none';GetObj("m"+i).className="menu"+i+"Off";}}
if(GetObj("con"+index)&&GetObj("m"+index)){GetObj("con"+index).style.display='block';GetObj("m"+index).className="menu"+index+"On";}
cur_index=index;if(cur_index<num){cur_index++;}else{cur_index=1;}}
function Menu(c_index){clearTimeout(settime);change_Menu(c_index);}
function showdh(n){for(var i=1;i<=7;i++){document.getElementById("dh"+i).className="unsel";}
document.getElementById("dh"+n).className="sel";}
function g(o){return document.getElementById(o);}
if(document.attachEvent){addEvent=function(o,evn,f){o.attachEvent("on"+evn,f);};}else if(document.addEventListener){addEvent=function(o,evn,f){o.addEventListener(evn,f,false);};}
function initTab1(nid,cid,action,defaultIndex){var ls=g(nid).getElementsByTagName('li');var cc=g(cid).childNodes;var c=[];var index=defaultIndex?defaultIndex:0;for(var i=0;i<cc.length;i++)if(cc[i].nodeType==1)c.push(cc[i]);if(ls.length!=c.length)
throw({description:'ÆÉµÈî£ßù¡õ¡õ½²ÏéÇÚÜí'});for(var i=0;i<ls.length;i++){ls[i].index=i;if(i==index){ls[i].className='hovertab';c[i].className='dis1';ls[i].parentNode.last=ls[i];}
addEvent(ls[i],action,function(e){var self=window.event?window.event.srcElement:e?e.target:null;if(self.parentNode.last){self.parentNode.last.className='normaltab';c[self.parentNode.last.index].className='undis1';};self.className='hovertab';c[self.index].className='dis1';self.parentNode.last=self;});}}
function show_all(type)
{var tmp_arr=document.getElementsByTagName("tr");for(var i=0;i<tmp_arr.length;i++)
{if(tmp_arr[i].className==type)
{tmp_arr[i].style.display="";}}}
function hide_all(type)
{var tmp_arr=document.getElementsByTagName("tr");for(var i=0;i<tmp_arr.length;i++)
{if(tmp_arr[i].className==type)
{tmp_arr[i].style.display="none";}}}
function toggel_div(divid)
{if(eval("document.getElementById('"+divid+"').style.display")=='')
eval("document.getElementById('"+divid+"').style.display = 'none'");else
eval("document.getElementById('"+divid+"').style.display = ''");}
function toggel_div_show(divid)
{if(eval("document.getElementById('"+divid+"').style.display")=='none'){eval("document.getElementById('"+divid+"').style.display = ''");}}
var XMLHttpRequestObject=createXMLHttpRequestObject();function createXMLHttpRequestObject()
{var XMLHttpRequestObject=false;try
{XMLHttpRequestObject=new XMLHttpRequest();}
catch(e)
{var aryXmlHttp=new Array("MSXML2.XMLHTTP","Microsoft.XMLHTTP","MSXML2.XMLHTTP.6.0","MSXML2.XMLHTTP.5.0","MSXML2.XMLHTTP.4.0","MSXML2.XMLHTTP.3.0");for(var i=0;i<aryXmlHttp.length&&!XMLHttpRequestObject;i++)
{try
{XMLHttpRequestObject=new ActiveXObject(aryXmlHttp[i]);}
catch(e){document.write("createXMLHttpRequestObject: XMLHttpRequestObject Error");}}}
if(!XMLHttpRequestObject)
{alert("Error: failed to create the XMLHttpRequest object.");}
else
{return XMLHttpRequestObject;}}
function checkFormInput(keyEvent,dataSource,idForm)
{keyEvent=(keyEvent)?keyEvent:window.event;input=(keyEvent.target)?keyEvent.target:keyEvent.srcElement;if(keyEvent.type=="checkbox")
{keyEvent.value=keyEvent.checked;}
else if(keyEvent.type=="radio")
{keyEvent.value=keyEvent.checked;if(keyEvent.value)
{for(i=0;i<document.getElementById(idForm).elements.length-1;i++)
{if(document.getElementById(idForm).elements[i].name==keyEvent.name)
{document.getElementById(idForm).elements[i].value=document.getElementById(idForm).elements[i].checked;}}}}}
function LTrim(value){var re=/\s*((\S+\s*)*)/;return value.replace(re,"$1");}
function RTrim(value){var re=/((\s*\S+)*)\s*/;return value.replace(re,"$1");}
function trim(value){return LTrim(RTrim(value));}
function clearForm(formIdent)
{var form,elements,i,elm;form=document.getElementById?document.getElementById(formIdent):document.forms[formIdent];if(document.getElementsByTagName)
{elements=form.getElementsByTagName('input');for(i=0,elm;elm=elements.item(i++);)
{if(elm.getAttribute('type')=="text")
{elm.value='';}}
elements=form.getElementsByTagName('textarea');for(i=0,elm;elm=elements.item(i++);)
{elm.innerHTML='';}}
else
{elements=form.elements;for(i=0,elm;elm=elements[i++];)
{if(elm.type=="text")
{elm.value='';}}}}
function sendFormData(idForm,dataSource,divID,ifLoading)
{var postData='';var strReplaceTemp;if(XMLHttpRequestObject)
{XMLHttpRequestObject.open("POST",dataSource);XMLHttpRequestObject.setRequestHeader("Method","POST "+dataSource+" HTTP/1.1");XMLHttpRequestObject.setRequestHeader("Content-Type","application/x-www-form-urlencoded");XMLHttpRequestObject.onreadystatechange=function()
{if(XMLHttpRequestObject.readyState==4&&XMLHttpRequestObject.status==200)
{try
{var objDiv=document.getElementById(divID);var response=XMLHttpRequestObject.responseText;splt=response.split("|###|");if(trim(splt[0])=="review_new_added"){document.getElementById(trim(splt[0])).innerHTML=splt[1]+document.getElementById(trim(splt[0])).innerHTML;objDiv.innerHTML=splt[2];if(trim(splt[3])=="success"){objDiv.innerHTML=splt[2];fadeOut('success_review_fad_out_id',20,5000);document.getElementById("write_review_form_id").style.display="none";}else{objDiv.innerHTML=splt[2];}
clearForm('product_reviews_write');try{document.getElementById("noreview_id_div").style.display="none";}catch(e){}}else if(trim(splt[0])=="question_new_added"){document.getElementById(trim(splt[0])).innerHTML=splt[1]+document.getElementById(trim(splt[0])).innerHTML;if(trim(splt[3])=="success"){objDiv.innerHTML=splt[2];fadeOut('success_qa_fad_out_id',20,5000);document.getElementById("ask_question_form_id").style.display="none";}else{objDiv.innerHTML=splt[2];}
clearForm('product_queston_write');try{document.getElementById("noquestion_id_div").style.display="none";}catch(e){}}else if(trim(splt[0])=="question_answer_new_added"){fadeOut('success_qa_ans_fad_out_id',20,5000);objDiv.innerHTML=splt[1];}else{objDiv.innerHTML=XMLHttpRequestObject.responseText;}}
catch(e){document.write("sendFormData: getElementById(divID) Error");}}
else
{if(ifLoading)
{try
{var objDiv=document.getElementById(divID);objDiv.innerHTML="<span style='margin-left:15px;'><img src=ajaxtabs/loading.gif></span> <span class=sp6>Please wait...</span>";}
catch(e){document.write("sendFormData->ifLoading: getElementById(divID) Error");}}}}
for(i=0;i<document.getElementById(idForm).elements.length-1;i++)
{strReplaceTemp=document.getElementById(idForm).elements[i].name.replace(/\[\]/i,"");postData+="&aryFormData["+strReplaceTemp+"][]="+document.getElementById(idForm).elements[i].value.replace(/&/g,"@@amp;");}
postData+="&parm="+new Date().getTime();try
{XMLHttpRequestObject.send(postData);}
catch(e){document.write("sendFormData: XMLHttpRequestObject.send Error");}}}
function setOpacity(id,level){var element=document.getElementById(id);element.style.display='inline';element.style.zoom=1;element.style.opacity=level;element.style.MozOpacity=level;element.style.KhtmlOpacity=level;element.style.filter="alpha(opacity="+(level*100)+");";}
function fadeIn(id,steps,duration,interval,fadeOutSteps,fadeOutDuration){var fadeInComplete;for(i=0;i<=1;i+=(1/steps)){setTimeout("setOpacity('"+id+"', "+i+")",i*duration);fadeInComplete=i*duration;}
setTimeout("fadeOut('"+id+"', "+fadeOutSteps+", "+fadeOutDuration+")",fadeInComplete+interval);}
function fadeOut(id,steps,duration){var fadeOutComplete;for(i=0;i<=1;i+=(1/steps)){setTimeout("setOpacity('"+id+"', "+(1-i)+")",i*duration);fadeOutComplete=i*duration;}
setTimeout("fadeHide('"+id+"')",fadeOutComplete);}
function fadeHide(id){document.getElementById(id).style.display='none';}
function adv_search_seo_url(fname,defaultUrl){var qstring=defaultUrl;var argList="";for(i=0;i<document.forms[fname].elements.length;i++){if((null!=document.forms[fname])&&(null!=document.forms[fname].elements[i])&&(""!=document.forms[fname].elements[i].name)&&(""!=document.forms[fname].elements[i].value)){argList=argList+'/'+document.forms[fname].elements[i].name+'/'+
document.forms[fname].elements[i].value.replace(/\//g,"slash");}}
if(argList.match(/\//g)){}
qstring=qstring+argList;if(qstring.match(/\?\&/g)){qstring=qstring.replace(/\?\&/g,"?");qstring=qstring.replace(/\?\&/g,"?");}
window.location=qstring;return false;}
function showToolTip(e,text){if(document.all)e=event;var obj=document.getElementById('bubble_tooltip');var obj2=document.getElementById('bubble_tooltip_content');obj2.innerHTML=text;obj.style.display='block';var st=Math.max(document.body.scrollTop,document.documentElement.scrollTop);if(navigator.userAgent.toLowerCase().indexOf('safari')>=0)st=0;var leftPos=e.clientX-100;if(leftPos<0)leftPos=0;obj.style.left=leftPos+'px';obj.style.top=e.clientY-obj.offsetHeight-1+st+'px';}
function hideToolTip()
{document.getElementById('bubble_tooltip').style.display='none';}