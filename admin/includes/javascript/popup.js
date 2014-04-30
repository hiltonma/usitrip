/*!
 *   By Rocky
 *   2010-11-10
 */

// 弹出层
var popup=function(id){return document.getElementById(id)};
function bodySize(){
  var a=new Array();
  a.st = document.body.scrollTop?document.body.scrollTop:document.documentElement.scrollTop;
  a.sl = document.body.scrollLeft?document.body.scrollLeft:document.documentElement.scrollLeft;
  a.sw = document.body.clientWidth ? document.body.clientWidth : document.documentElement.clientWidth;
  a.sh = document.documentElement.clientHeight;
  return a;
}
function centerElement(obj,top){
  var s = bodySize();
  var w = popup(obj).offsetWidth;
  var h = popup(obj).offsetHeight;  
  popup(obj).style.left = parseInt((s.sw - w)/2) + s.sl + "px";
  if(top!="" && top!=null){
    popup(obj).style.top = top + "px";
  }else{
    popup(obj).style.top = parseInt((s.sh - h)/2) + s.st + "px";
  }
}

//显示弹出窗
function popupBg(){
  var _scrollWidth = document.body.clientWidth + "px";
  var _scrollHeight = document.body.clientHeight + "px";
  popup("popupBg").style.width = _scrollWidth;  //设置弹出窗的背景宽度和屏幕的大小一致
  popup("popupBg").style.height = _scrollHeight;
  
  isIE6 = navigator.userAgent.toLowerCase().indexOf("msie 6.0") != -1;//判断浏览器是ie6, 给背景div添加一个iframe，解决下拉菜单select不能被遮盖的问题
  if(isIE6){//ie6的情况
    popup("popupBg").innerHTML="<iframe scrolling='no' height='100%' width='100%' marginwidth='0' marginheight='0' frameborder='0' class='popupBgIframe123' id='popupBgIframe'/></iframe>";
    popup("popupBgIframe").style.width = _scrollWidth;  //设置弹出窗的背景不能遮盖select的bug,加了个iframe的宽度和高度和屏幕的大小一致
    popup("popupBgIframe").style.height = _scrollHeight;
  }
}
function showPopup(popupId,popupCon,top,ScrollOff){


//popupId是弹出层的id,popupCon是弹出层内容部分，popupCon的宽度必须设定，高度可不设定
  popup(popupId).style.display="block";  //显示弹出窗
  popup(popupId).style.width = (popup(popupCon).offsetWidth + 12) + "px";   //设置弹出层的宽度
  popup(popupId).style.height = (popup(popupCon).offsetHeight + 12) + "px";  //设置弹出层的高度
  centerElement(popupId,top);
  popup("popupBg").style.display="block";  //设置弹出窗的背景
  popupBg();


  window.onresize = function() {centerElement(popupId,top); popupBg();}//屏幕改变的时候重新设定悬浮框
  if(ScrollOff!="" && ScrollOff!=null){}else{
  	window.onscroll = function() {
  	  centerElement(popupId,top); 
  	}//上下滚动时悬浮框位置重新设定
  }
}

function closePopup(popupId){
  popup(popupId).style.display='none';
  popup("popupBg").style.display='none';
}

//弹出层顶部拖曳 
Array.prototype.extend = function(C) {
  for (var B = 0, A = C.length; B < A; B++) {
    this.push(C[B]);
  }
  return this;
}
function divDrag() {
  var A, B, popupcn;
  var zIndex = 1000;
  this.dragStart = function(e) {
    e = e || window.event;
    if ((e.which && (e.which != 1)) || (e.button && (e.button != 1))) return;
    var pos = this.popuppos;
    popupcn = this.parent || this;
    if (document.defaultView) {
      _top = document.defaultView.getComputedStyle(popupcn, null).getPropertyValue("top");
      _left = document.defaultView.getComputedStyle(popupcn, null).getPropertyValue("left");
    }
    else {
      if (popupcn.currentStyle) {
        _top = popupcn.currentStyle["top"];
        _left = popupcn.currentStyle["left"];
      }
    }
    pos.ox = (e.pageX || (e.clientX + document.documentElement.scrollLeft)) - parseInt(_left);
    pos.oy = (e.pageY || (e.clientY + document.documentElement.scrollTop)) - parseInt(_top);
    if ( !! A) {
      if (document.removeEventListener) {
        document.removeEventListener("mousemove", A, false);
        document.removeEventListener("mouseup", B, false);
      }
      else {
        document.detachEvent("onmousemove", A);
        document.detachEvent("onmouseup", B);
      }
    }
    A = this.dragMove.create(this);
    B = this.dragEnd.create(this);
    if (document.addEventListener) {
      document.addEventListener("mousemove", A, false);
      document.addEventListener("mouseup", B, false);
    }
    else {
      document.attachEvent("onmousemove", A);
      document.attachEvent("onmouseup", B);
    }
    popupcn.style.zIndex = (++zIndex);
    this.stop(e);
  }
  this.dragMove = function(e) {
    e = e || window.event;
    var pos = this.popuppos;
    popupcn = this.parent || this;
    popupcn.style.top = (e.pageY || (e.clientY + document.documentElement.scrollTop)) - parseInt(pos.oy) + 'px';
    popupcn.style.left = (e.pageX || (e.clientX + document.documentElement.scrollLeft)) - parseInt(pos.ox) + 'px';
    this.stop(e);
  }
  this.dragEnd = function(e) {
    var pos = this.popuppos;
    e = e || window.event;
    if ((e.which && (e.which != 1)) || (e.button && (e.button != 1))) return;
    popupcn = this.parent || this;
    if ( !! (this.parent)) {
      this.style.backgroundColor = pos.color;
    }
    if (document.removeEventListener) {
      document.removeEventListener("mousemove", A, false);
      document.removeEventListener("mouseup", B, false);
    }
    else {
      document.detachEvent("onmousemove", A);
      document.detachEvent("onmouseup", B);
    }
    A = null;
    B = null;
    popupcn.style.zIndex = (++zIndex);
    this.stop(e);
  }
  this.shiftColor = function() {
    this.style.backgroundColor = "#EEEEEE";
  }
  this.position = function(e) {
    var t = e.offsetTop;
    var l = e.offsetLeft;
    while (e = e.offsetParent) {
      t += e.offsetTop;
      l += e.offsetLeft;
    }
    return {
      x: l,
      y: t,
      ox: 0,
      oy: 0,
      color: null
    }
  }
  this.stop = function(e) {
    if (e.stopPropagation) {
      e.stopPropagation();
    } else {
      e.cancelBubble = true;
    }
    if (e.preventDefault) {
      e.preventDefault();
    } else {
      e.returnValue = false;
    }
  }
  this.stop1 = function(e) {
    e = e || window.event;
    if (e.stopPropagation) {
      e.stopPropagation();
    } else {
      e.cancelBubble = true;
    }
  }
  this.create = function(bind) {
    var B = this;
    var A = bind;
    return function(e) {
      return B.apply(A, [e]);
    }
  }
  this.dragStart.create = this.create;
  this.dragMove.create = this.create;
  this.dragEnd.create = this.create;
  this.shiftColor.create = this.create;
  this.initialize = function() {
    for (var A = 0, B = arguments.length; A < B; A++) {
      C = arguments[A];
      if (! (C.push)) {
        C = [C];
      }
      popupC = (typeof(C[0]) == 'object') ? C[0] : (typeof(C[0]) == 'string' ? popup(C[0]) : null);
      if (!popupC) continue;
      popupC.popuppos = this.position(popupC);
      popupC.dragMove = this.dragMove;
      popupC.dragEnd = this.dragEnd;
      popupC.stop = this.stop;
      if ( !! C[1]) {
        popupC.parent = C[1];
        popupC.popuppos.color = popupC.style.backgroundColor;
      }
      if (popupC.addEventListener) {
        popupC.addEventListener("mousedown", this.dragStart.create(popupC), false);
        if ( !! C[1]) {
          popupC.addEventListener("mousedown", this.shiftColor.create(popupC), false);
        }
      }
      else {
        popupC.attachEvent("onmousedown", this.dragStart.create(popupC));
        if ( !! C[1]) {
          popupC.attachEvent("onmousedown", this.shiftColor.create(popupC));
        }
      }
    }
  }
  this.initialize.apply(this, arguments);
}
