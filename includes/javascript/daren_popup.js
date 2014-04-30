/**
 * popup v1.0
 * http://www.usitrip.com
 *
 * @author Rocky (296456018@qq.com)
 *
 * Create a popup
 * @example new Popup('PopupTip','PopupCon','PopupClose',{scrollY:"true", fixedTop:"50", fixedLeft:"200", fixedId:"abcd", dragId:"drag"});
 *
 * Date: 2011-03-31
 */


var PopGetId = function(id){return document.getElementById(id)};
function Popup(popupId,popupCon,closeId,options){
    this.popupId = popupId;
    this.popupCon = popupCon;
    this.closeId = closeId;
    if(options){
        this.scrollY = options.scrollY;
        this.fixedId = options.fixedId;
        this.fixedTop = options.fixedTop;
        this.fixedLeft = options.fixedLeft;
        this.dragId = options.dragId;
    }
    
    this.init();
}

Popup.prototype={
	init: function(){
	    var that=this;
	    
        PopGetId(this.popupId).style.display="block";
        PopGetId(this.popupId).style.width = (PopGetId(this.popupCon).offsetWidth + 12) + "px"; 
        PopGetId(this.popupId).style.height = (PopGetId(this.popupCon).offsetHeight + 12) + "px";
        
        this.centerElement();
        //显示背景
	    //if(!PopGetId("popupBg")){
	   //     var popupBg = document.createElement("div");
	   //     popupBg.id ="popupBg";
	    //    document.body.appendChild(popupBg);
	   // };
        //PopGetId("popupBg").style.display="block";  
	    //this.popupBg();

       // window.onresize = function(){
		//	that.centerElement();
			//that.popupBg();
		//};
		
		if(this.scrollY == "true"){
  	        window.onscroll = function(){
  	            that.centerElement();
  	            //that.popupBg();
  	        };
        };
        
        //Esc 关闭弹出层
        this.keyDown();
        
        //拖动层
        /*if(this.dragId != null){
            this.dragBox(this.dragId,this.popupId);
        };*/
        
        //关闭按钮关闭弹出层
        PopGetId(this.closeId).onclick = function(){
            PopGetId(that.popupId).style.display = "none";
            //PopGetId("popupBg").style.display = "none";
        };
    },
    
    //设置背景
    popupBg: function(){
        var _scrollWidth = document.body.scrollWidth;
        var _scrollHeight = document.body.scrollHeight;
        PopGetId("popupBg").style.width = _scrollWidth + "px";
        PopGetId("popupBg").style.height = _scrollHeight + "px";

        var isIE6 = !-[1,] && !window.XMLHttpRequest;
        if(isIE6){
            PopGetId("popupBg").innerHTML="<iframe scrolling='no' height='100%' width='100%' marginwidth='0' marginheight='0' frameborder='0' class='popupBgIframe123' id='popupBgIframe'/></iframe>";
            PopGetId("popupBgIframe").style.width = _scrollWidth + "px"; 
            PopGetId("popupBgIframe").style.height = _scrollHeight + "px";
        };
    },
    
    //获取窗口参数
    bodySize: function(){
        var a=new Array();
        a.st = document.body.scrollTop?document.body.scrollTop:document.documentElement.scrollTop;
        a.sl = document.body.scrollLeft?document.body.scrollLeft:document.documentElement.scrollLeft;
        a.sw = document.documentElement.clientWidth;
        a.sh = document.documentElement.clientHeight;
        return a;
    },
    
    //位置定位
    centerElement: function(){
        var s = this.bodySize();
        var w = PopGetId(this.popupId).offsetWidth;
        var h = PopGetId(this.popupId).offsetHeight;
        PopGetId(this.popupId).style.left = parseInt((s.sw - w)/2) + s.sl + "px";
        
        if(this.fixedLeft != null && this.fixedId != null){
            PopGetId(this.popupId).style.left = this.getElementPos(this.fixedId).x + parseInt(this.fixedLeft) + "px";
        }else if(this.fixedLeft != null && this.fixedId == null){
            PopGetId(this.popupId).style.left = parseInt(this.fixedLeft) + "px";
        }else{
            PopGetId(this.popupId).style.left = parseInt((s.sw - w)/2) + s.sl + "px";
        };
        

        if(this.fixedTop != null || this.fixedId != null){
            var _top = 0,_topInt = 0;
            if(this.fixedId != null){
                _top = this.getElementPos(this.fixedId).y;
            };
            if(this.fixedTop != null){
                _topInt = parseInt(this.fixedTop);
            };
            PopGetId(this.popupId).style.top = _top + _topInt + "px";
        }else{
            PopGetId(this.popupId).style.top = parseInt((s.sh - h)/2) + s.st + "px";
        };
    },
	
	//拖动部分
	dragBox: function(){
        var moveX = 0,moveY = 0,drag = false;
        PopGetId(this.dragId).style.cursor = "move";
        var that = this;
		PopGetId(this.dragId).onmousedown = function(e){
		    drag = true;
			e = window.event?window.event:e;
			var cw = document.documentElement.clientWidth,
				ch = document.documentElement.clientHeight;
			var	sw = parseInt(PopGetId(that.popupId).offsetWidth),
				sh = parseInt(PopGetId(that.popupId).offsetHeight);
			var _ID = PopGetId(that.popupId);
			moveX = e.clientX - _ID.offsetLeft;
			moveY = e.clientY - _ID.offsetTop;
			
			document.onmousemove = function(e){
			    if(drag){
			        PopGetId(that.dragId).style.background = "#f5f5f5";
                    e = window.event?window.event:e;
				    window.getSelection ? window.getSelection().removeAllRanges() : document.selection.empty();
				    var _x = e.clientX - moveX;
				    var _y = e.clientY - moveY;
				    if ( _x > 0 &&( _x + sw < cw) && _y > 0 && (_y + sh  < ch) ) {
				        PopGetId(that.popupId).style.left = _x + "px";
				        PopGetId(that.popupId).style.top = _y + "px";
				    };
				};
			};
			document.onmouseup = function(){
			    PopGetId(that.dragId).style.background = "#fff";
				drag = false;
			};
		};
	},

    //定位坐标
    getElementPos: function(elementId) {
        var ua = navigator.userAgent.toLowerCase();
        var isOpera = (ua.indexOf('opera') != -1);
        var isIE = (ua.indexOf('msie') != -1 && !isOpera);
        var el = document.getElementById(elementId);
        if (el.parentNode === null || el.style.display == 'none') {
	        return false;
        };
        var parent = null;
        var pos = [];
        var box;
        if (el.getBoundingClientRect){
	        box = el.getBoundingClientRect();
	        var scrollTop = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
	        var scrollLeft = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft);
	        return {
		        x: box.left + scrollLeft,
		        y: box.top + scrollTop
	        };
        }else if (document.getBoxObjectFor){
	        box = document.getBoxObjectFor(el);
	        var borderLeft = (el.style.borderLeftWidth) ? parseInt(el.style.borderLeftWidth) : 0;
	        var borderTop = (el.style.borderTopWidth) ? parseInt(el.style.borderTopWidth) : 0;
	        pos = [box.x - borderLeft, box.y - borderTop];
        }else{
	        pos = [el.offsetLeft, el.offsetTop];
	        parent = el.offsetParent;
	        if (parent != el) {
		        while (parent) {
			        pos[0] += parent.offsetLeft;
			        pos[1] += parent.offsetTop;
			        parent = parent.offsetParent;
		        };
	        };
	        if (ua.indexOf('opera') != -1 || (ua.indexOf('safari') != -1 && el.style.position == 'absolute')) {
		        pos[0] -= document.body.offsetLeft;
		        pos[1] -= document.body.offsetTop;
	        };
        };
        if (el.parentNode) {
	        parent = el.parentNode;
        } else {
	        parent = null;
        };
        while (parent && parent.tagName != 'BODY' && parent.tagName != 'HTML') {
	        pos[0] -= parent.scrollLeft;
	        pos[1] -= parent.scrollTop;
	        if (parent.parentNode) {
		        parent = parent.parentNode;
	        }else{
		        parent = null;
	        };
        };
        return {
	        x: pos[0],
	        y: pos[1]
        };
    },
    
    //Esc 操作
    keyDown: function(){
        var that = this;
        document.onkeydown = function(e) {
            e = e || event;
            if(e.keyCode == 27){
                PopGetId(that.popupId).style.display='none';
                //PopGetId("popupBg").style.display='none';
            };
        };
    }
}