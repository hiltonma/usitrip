function getObjStyle(a){var b;if(document.getElementById){if(null==document.getElementById(a)){return null}b=document.getElementById(a).style}else{if(document.layers){b=document.divId}else{if(null==document.all.divId){return null}b=document.all.divId.style}}return b}function getObj(a){var b;if(document.getElementById){b=document.getElementById(a)}else{if(document.layers){b=document.objId}else{b=document.all.objId}}return b}function showdiv3(a){showhidediv(obrev,"visible")}function hidediv3(a){showhidediv(obrev,"hidden")}function showhidediv3(obref,state){if(document.all){eval("document.all."+obref+".style.visibility = state")}if(document.layers){document.layers[obref].visibility=state}if(document.getElementById&&!document.all){obj=document.getElementById(obref);obj.style.visibility=state}}function showhidediv(a,b){if(document.getElementById){document.getElementById(a).style.visibility=b}}function hidediv(a){showhidediv(a,"hidden")}function showdiv(a){showhidediv(a,"visible")}function cityFocus(a,b,c){initSmartBox(a,c,b,20,175)}function oneWayToggle(){var a=(document.frm_flight.oneway[1].checked?"hidden":"visible");document.getElementById("returnDateTD").style.visibility=a;document.getElementById("return_time").style.visibility=a}function show_hide_div(a){if(document.getElementById(a).style.display=="none"){document.getElementById(a).style.display="block"}else{document.getElementById(a).style.display="none"}}function site(a){if(a=="one"){document.getElementById("one").className="button_tol";document.getElementById("two").className="button_fl";document.getElementById("three").className="button_fl";document.getElementById("tools_contain").style.display="";document.getElementById("hotels_contain").style.display="none";document.getElementById("f_contain").style.display="none"}else{if(a=="two"){document.getElementById("two").className="button_tol";document.getElementById("one").className="button_fl";document.getElementById("three").className="button_fl";document.getElementById("hotels_contain").style.display="";document.getElementById("tools_contain").style.display="none";document.getElementById("f_contain").style.display="none"}else{document.getElementById("one").className="button_fl";document.getElementById("two").className="button_fl";document.getElementById("three").className="button_tol";document.getElementById("f_contain").style.display="";document.getElementById("tools_contain").style.display="none";document.getElementById("hotels_contain").style.display="none"}}}function site_home(a){if(a=="one"){document.getElementById("one").className="button_tab";document.getElementById("two").className="button_tab1";document.getElementById("three").className="button_tab1";document.getElementById("tools_contain").style.display="";document.getElementById("hotels_contain").style.display="none";document.getElementById("f_contain").style.display="none"}else{if(a=="two"){document.getElementById("two").className="button_tab";document.getElementById("one").className="button_tab1";document.getElementById("three").className="button_tab1";document.getElementById("hotels_contain").style.display="";document.getElementById("tools_contain").style.display="none";document.getElementById("f_contain").style.display="none"}else{document.getElementById("one").className="button_tab1";document.getElementById("two").className="button_tab1";document.getElementById("three").className="button_tab";document.getElementById("f_contain").style.display="";document.getElementById("tools_contain").style.display="none";document.getElementById("hotels_contain").style.display="none"}}}function createRequestObjectAjax(){var b;var a=navigator.appName;if(a=="Microsoft Internet Explorer"){b=new ActiveXObject("Microsoft.XMLHTTP")}else{b=new XMLHttpRequest()}return b}var httpobjajax=createRequestObjectAjax();function search_tour_ajax_column_left(c,b){try{httpobjajax.open("get","search_create_ajax.php?categories_urlname="+c+"&level_no="+b+"&action_attributes=columnleft");httpobjajax.onreadystatechange=hendleInfo_search_tour_ajax;httpobjajax.send(null)}catch(a){}}function search_tour_ajax(c,b){try{httpobjajax.open("get","search_create_ajax.php?categories_urlname="+c+"&level_no="+b+"&action_attributes=true");httpobjajax.onreadystatechange=hendleInfo_search_tour_ajax;httpobjajax.send(null)}catch(a){}}function hendleInfo_search_tour_ajax(){if(httpobjajax.readyState==4){var a=httpobjajax.responseText;if(document.getElementById("search_tour_category_response")!=null){document.getElementById("search_tour_category_response").innerHTML=a}}}function swapLayer(d,c){var b=document.getElementById(d);var a=document.getElementById(c);if(b==null||a==null){return}if(b.style.display=="none"){b.style.display="block";a.style.display="none"}else{b.style.display="none";a.style.display="block"}}function xwzImgRollOver(b,c,a){if(c==true){if(b.getAttribute("xwzAlreadySrc")==null){b.setAttribute("xwzAlreadySrc",b.src)}b.src=a}else{if(b.getAttribute("xwzAlreadySrc")!=null){b.src=b.getAttribute("xwzAlreadySrc")}}}function xwzRollingImageTrans(c,d,b,a){this.Index=0;this.ListItem=new Array(0);this.Name=c;this.Thumbnail=d;this.tmRotate=null;this.nInterval=4500;this.eventName=b;this.winTarget=a;if(window.xwzRollObject==null){window.xwzRollObject=new Array(0)}window.xwzRollObject[this.Name]=this;this.install=function(){window.document.images[this.Name].onclick=this.goLink;if(this.ListItem.length==0){return}this.tmRotate=setTimeout("window.xwzRollObject['"+this.Name+"'].rotateTrans()",this.nInterval);var f=document.getElementsByName(this.Thumbnail);for(var e=0;e<f.length;e++){if(this.eventName=="over"){f[e].onmouseover=new Function("window.xwzRollObject['"+this.Name+"'].alterImage("+e+")")}else{f[e].onclick=new Function("window.xwzRollObject['"+this.Name+"'].alterImage("+e+")")}}};this.addItem=function(e,f,j,g){var h={Link:"",ImgSrc:"",DefIcon:"",OvrIcon:""};h.Link=e;h.ImgSrc=f;h.DefIcon="";h.OvrIcon="";this.ListItem[this.ListItem.length]=h};this.alterImage=function(e){var f=document.getElementsByName(this.Thumbnail);if(this.Index==e){return}if(this.ListItem[this.Index].DefIcon!=""){f[this.Index].src=this.ListItem[this.Index].DefIcon}this.Index=e;this.imgTrans();clearTimeout(this.tmRotate);this.tmRotate=null;this.tmRotate=setTimeout("window.xwzRollObject['"+this.Name+"'].rotateTrans()",this.nInterval)};this.goLink=function(){var e=this.getAttribute("name");var f=window.xwzRollObject[e];clearTimeout(f.tmRotate);f.tmRotate=null;f.winTarget="_blank";if(f.winTarget==""||f.winTarget==null){window.location.href=f.ListItem[f.Index].Link}else{window.open(f.ListItem[f.Index].Link,f.winTarget)}};this.rotateTrans=function(){var e=document.getElementsByName(this.Thumbnail);var f=this.ListItem[this.Index];if(f.DefIcon!=""){e[this.Index].src=f.DefIcon}this.Index+=1;if(this.Index>=this.ListItem.length){this.Index=0}this.imgTrans();clearTimeout(this.tmRotate);this.tmRotate=null;this.tmRotate=setTimeout("window.xwzRollObject['"+this.Name+"'].rotateTrans()",this.nInterval)};this.imgTrans=function(){var f=document.getElementsByName(this.Thumbnail);var h=this.ListItem[this.Index];if(h.OvrIcon!=null&&h.OvrIcon!=""){f[this.Index].src=h.OvrIcon}try{document.images[this.Name].filters[0].apply();document.images[this.Name].src=h.ImgSrc;document.images[this.Name].filters[0].play()}catch(g){document.images[this.Name].src=h.ImgSrc}}}function xwzRollingMarqTrans(a,b){this.Name=a;this.Index=0;this.ListItem=new Array(0);this.tmRotate=null;this.nInterval=4500;this.eventName=b;if(window.xwzMarqObject==null){window.xwzMarqObject=new Array(0)}window.xwzMarqObject[this.Name]=this;this.install=function(){if(this.ListItem.length==0){return}this.tmRotate=setTimeout("window.xwzMarqObject['"+this.Name+"'].rotateTrans()",this.nInterval);for(var c=0;c<this.ListItem.length;c++){if(this.eventName=="over"){this.ListItem[c].Img.onmouseover=new Function("window.xwzMarqObject['"+this.Name+"'].alterTrans("+c+")")}else{this.ListItem[c].Img.onclick=new Function("window.xwzMarqObject['"+this.Name+"'].alterTrans("+c+")")}}};this.addItem=function(c,e,g,d){var f={Objects:null,Imgs:null,DefaultSrc:"",OverSrc:""};f.Object=c;f.Img=e;f.DefaultSrc=g;f.OverSrc=d;this.ListItem[this.ListItem.length]=f};this.alterTrans=function(c){if(this.Index==c){return}var d=this.ListItem[this.Index];if(d.DefaultSrc!=""){d.Img.src=d.DefaultSrc}this.Index=c;this.objTrans();clearTimeout(this.tmRotate);this.tmRotate=null;this.tmRotate=setTimeout("window.xwzMarqObject['"+this.Name+"'].rotateTrans()",this.nInterval)};this.rotateTrans=function(){var c=this.ListItem[this.Index];if(c.DefaultSrc!=""){c.Img.src=c.DefaultSrc}this.Index+=1;if(this.Index>=this.ListItem.length){this.Index=0}this.objTrans();clearTimeout(this.tmRotate);this.tmRotate=null;this.tmRotate=setTimeout("window.xwzMarqObject['"+this.Name+"'].rotateTrans()",this.nInterval)};this.objTrans=function(){var f=this.ListItem[this.Index];if(f.Img!=null&&f.OverSrc!=""){f.Img.src=f.OverSrc}for(var c=0;c<this.ListItem.length;c++){this.ListItem[c].Object.style.display="none"}try{f.Object.filters[0].apply();f.Object.style.display="";f.Object.filters[0].play()}catch(d){f.Object.style.display=""}}}function verticalWheel(c,a,b){if(window.xwzWheelMarq==null){window.xwzWheelMarq=new Array(0)}xwzWheelMarq[c]={install:function(h,d,e){this.id=h;this.div=document.getElementById("ID_DIV_KEYWORD");this.table=document.getElementById("ID_TABLE_KEYWORD");if(this.div==null){return}this.div.style.cssText="height:"+d+";overflow:hidden;position:relative;cursor:pointer;clip:rect(0 auto "+this.height+" 0);left:0;top:0";this.div.parentNode.style.position="relative";this.div.parentNode.onmouseover=function(){xwzWheelMarq[h].table.style.visibility="visible";xwzWheelMarq[h].nPause=true};this.div.parentNode.onmouseout=function(){xwzWheelMarq[h].table.style.visibility="hidden";xwzWheelMarq[h].nPause=false};this.index=0;this.height=d;this.items=new Array(0);this.tmID=null;this.nPause=false;this.nSec=e;var g=this.table.tBodies[0].rows;for(var f=0;f<g.length;f++){this.items[f]=document.createElement("DIV");this.items[f].innerHTML=g[f].innerHTML;this.items[f].style.padding="3";this.items[f].style.width="100%";this.items[f].style.height=this.height;this.items[f].style.position="absolute";this.items[f].style.top=this.height*f;this.div.appendChild(this.items[f]);g[f].cells[0].style.cssText="padding-left:5px;border-bottom:#CACACA 1px dotted;";g[f].onmouseover=function(){this.style.backgroundColor="#FDF1F0"};g[f].onmouseout=function(){this.style.backgroundColor=""};if(f>=g.length-1){g[f].cells[0].style.border=""}}},doWheel:function(){var g=this.items[this.index];var f=50;var d=this.index+1>=this.items.length?0:this.index+1;clearTimeout(this.tmID);this.tmID=null;if(this.nPause!=true){for(var e=0;e<this.items.length;e++){this.items[e].style.top=parseInt(this.items[e].style.top)-1}if(parseInt(g.style.top)<=this.height*-1){g.style.top=this.height*(this.items.length-1);this.index=this.index+1>=this.items.length?0:this.index+1;f=this.nSec}}else{if(parseInt(g.style.top)<(this.height/2)*-1){g.style.top=this.height*(this.items.length-1);this.index=this.index+1>=this.items.length?0:this.index+1}for(var e=0;e<this.items.length;e++){this.items[e].style.top=this.height*((this.items.length-this.index+e)%this.items.length)}f=10}this.tmID=setTimeout("xwzWheelMarq['"+this.id+"'].doWheel()",f)}};xwzWheelMarq[c].install(c,a,b);xwzWheelMarq[c].tmID=setTimeout("xwzWheelMarq['"+c+"'].doWheel()",b)}var isStart=true;var nn;var tt;var bPlay=new Image;bPlay.src="image/bu_pla.gif";var bPause=new Image;bPause.src="image/bu_pau.gif";nn=1;function resetPlay(){isStart=true;var c=document.getElementById("top_slider");var b=c.getElementsByTagName("img");for(i=0;i<b.length;i++){if(b[i].src==bPlay.src){b[i].src=bPause.src}}}function playorpau(a){if(a.src=="image/bu_pau.gif"){a.src=bPlay.src;isStart=false}else{a.src=bPause.src;isStart=true}}function pre_img(){resetPlay();nn--;if(nn<1){nn=5}setFocus(nn)}function next_img(){resetPlay();nn++;if(nn>5){nn=1}setFocus(nn)}function change_img(){if(isStart){nn++;if(nn>5){nn=1}setFocus(nn)}else{tt=setTimeout("change_img()",100)}}function setFocus(a){if(tt){clearTimeout(tt)}nn=a;selectLayer1(a);tt=setTimeout("change_img()",8000)}function selectLayer1(a){switch(a){case 1:document.getElementById("bbs_s1").style.display="block";document.getElementById("bbs_s2").style.display="none";document.getElementById("bbs_s3").style.display="none";document.getElementById("bbs_s4").style.display="none";document.getElementById("bbs_s5").style.display="none";break;case 2:document.getElementById("bbs_s1").style.display="none";document.getElementById("bbs_s2").style.display="block";document.getElementById("bbs_s3").style.display="none";document.getElementById("bbs_s4").style.display="none";document.getElementById("bbs_s5").style.display="none";break;case 3:document.getElementById("bbs_s1").style.display="none";document.getElementById("bbs_s2").style.display="none";document.getElementById("bbs_s3").style.display="block";document.getElementById("bbs_s4").style.display="none";document.getElementById("bbs_s5").style.display="none";break;case 4:document.getElementById("bbs_s1").style.display="none";document.getElementById("bbs_s2").style.display="none";document.getElementById("bbs_s3").style.display="none";document.getElementById("bbs_s4").style.display="block";document.getElementById("bbs_s5").style.display="none";break;case 5:document.getElementById("bbs_s1").style.display="none";document.getElementById("bbs_s2").style.display="none";document.getElementById("bbs_s3").style.display="none";document.getElementById("bbs_s4").style.display="none";document.getElementById("bbs_s5").style.display="block";break}}function getid(a){return(typeof a=="object")?a:document.getElementById(a)}function getNames(d,b,a){var c=getid(d).getElementsByTagName(a);var e=new Array();for(i=0;i<c.length;i++){if(c[i].getAttribute("name")==b){e[e.length]=c[i]}}return e}function fiterplay(g,b,d,a,e,c){var f=getNames(g,a,d);for(i=0;i<f.length;i++){if(i==b){f[i].className=e}else{f[i].className=c}}}function play(obj,num){var s=getid("simg");var b=getid("bimg");var i=getid("info");try{with(b){filters[0].apply();fiterplay(b,num,"div","barf","dis_home","undis_home");fiterplay(s,num,"div","barf","f1","");fiterplay(i,num,"div","barf","dis_home","undis_home");filters[0].play()}}catch(e){fiterplay(b,num,"div","barf","dis_home","undis_home");fiterplay(s,num,"div","barf","f1","");fiterplay(i,num,"div","barf","dis_home","undis_home")}}function clearAuto(){clearInterval(autoStart)}function setAuto(){autoStart=setInterval("auto(n)",6000)}function auto(){n++;if(n>(x.length-1)){n=0;scnum++;if(scnum>3){}}play(x[n],n)}function set_value_to_hidden_var(b,c,a,d){if(b=="sort"){if(c=="3a"){document.getElementById("sort_label_asc").className="sort_label_asc_sel";document.getElementById("sort_label_desc").className="sort_label_desc"}else{if(c=="3d"){document.getElementById("sort_label_asc").className="sort_label_asc";document.getElementById("sort_label_desc").className="sort_label_desc_sel"}}}if(d=="vp"){if(b=="products_durations"){if(c=="4-4"){document.getElementById("pd4").className="duration_label_sel";document.getElementById("pd56").className="duration_label";document.getElementById("pd7").className="duration_label";document.getElementById("pdall").className="duration_label"}else{if(c=="5-6"){document.getElementById("pd4").className="duration_label";document.getElementById("pd56").className="duration_label_sel";document.getElementById("pd7").className="duration_label";document.getElementById("pdall").className="duration_label"}else{if(c=="7-"){document.getElementById("pd4").className="duration_label";document.getElementById("pd56").className="duration_label";document.getElementById("pd7").className="duration_label_sel";document.getElementById("pdall").className="duration_label"}else{if(c==" "){document.getElementById("pd4").className="duration_label";document.getElementById("pd56").className="duration_label";document.getElementById("pd7").className="duration_label";document.getElementById("pdall").className="duration_label_sel"}}}}}document.sort_order_vacktion_package.elements[b+"1"].value=c;sendFormData("sort_order_vacktion_package","product_listing_index_vackation_packages.php?cPath="+a+"&addhash=true","div_product_vackation_packages",true)}else{if(b=="products_durations"){if(c=="0-1"){document.getElementById("pd01").className="duration_label_sel";document.getElementById("pd12").className="duration_label";document.getElementById("pd3").className="duration_label";document.getElementById("pdall").className="duration_label"}else{if(c=="1-2"){document.getElementById("pd01").className="duration_label";document.getElementById("pd12").className="duration_label_sel";document.getElementById("pd3").className="duration_label";document.getElementById("pdall").className="duration_label"}else{if(c=="3-"){document.getElementById("pd01").className="duration_label";document.getElementById("pd12").className="duration_label";document.getElementById("pd3").className="duration_label_sel";document.getElementById("pdall").className="duration_label"}else{if(c==" "){document.getElementById("pd01").className="duration_label";document.getElementById("pd12").className="duration_label";document.getElementById("pd3").className="duration_label";document.getElementById("pdall").className="duration_label_sel"}}}}}document.sort_order.elements[b].value=c;sendFormData("sort_order","product_listing_index_products_ajax.php?cPath="+a+"&addhash=true","div_product_listing",true)}}function attraction_selected(b,a){for(i=1;i<=a;i++){document.getElementById("left_topatt_"+i).className=""}document.getElementById("left_topatt_"+b).className="s"}var vin=5;function showdiv_destinations_listing(name,maxdata){name=name+"_"+vin;vin=vin+5;var obj=(document.getElementById)?document.getElementById(name):eval("document.all[name]");if(obj.style.display=="none"){obj.style.display=""}else{obj.style.display="none"}if(vin>=maxdata){var obj=(document.getElementById)?document.getElementById("more_div_destinations_link"):eval("document.all['more_div_destinations_link']");obj.style.display="none"}}function showdiv_dept_cities_listing(name,maxdata){name=name+"_"+vin;vin=vin+5;var obj=(document.getElementById)?document.getElementById(name):eval("document.all[name]");if(obj.style.display=="none"){obj.style.display=""}else{obj.style.display="none"}if(vin>=maxdata){var obj=(document.getElementById)?document.getElementById("more_div_dept_cities_link"):eval("document.all['more_div_dept_cities_link']");obj.style.display="none"}}jQuery(window).bind("scroll resize",function(){if(Math.max(document.documentElement.scrollTop,document.body.scrollTop)>0){jQuery("#GoToTop").show()}else{jQuery("#GoToTop").hide()}});
