/**
 * pop v1.0
 * http://www.usitrip.com
 *
 * @author Rocky (296456018@qq.com)
 *
 * Create a pop
 * @example new Pop('PopTip','PopCon','PopClose',{scrollY:"true", fixedTop:"50", fixedLeft:"200", fixedId:"abcd", dragId:"drag"});
 *
 * Date: 2011-06-09
 */


var PopGetId = function(id){return document.getElementById(id)};
function Pop(popId,popCon,closeId,options){
    this.popId = popId;
    this.popCon = popCon;
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

Pop.prototype={
	init: function(){
	    var that=this;
	    
        PopGetId(this.popId).style.display="block";
        PopGetId(this.popId).style.width = (PopGetId(this.popCon).offsetWidth + 12) + "px"; 
        PopGetId(this.popId).style.height = (PopGetId(this.popCon).offsetHeight + 12) + "px";
        this.centerElement();
        
        //显示背景
	    if(!PopGetId("popBg")){
	        var popBg = document.createElement("div");
	        popBg.id ="popBg";
	        document.body.appendChild(popBg);
	    };
        PopGetId("popBg").style.display="block";  
	    this.popBg();

        window.onresize = function(){
			that.centerElement();
			that.popBg();
		};
		
		if(this.scrollY == "true"){
  	        window.onscroll = function(){
  	            that.centerElement();
  	            that.popBg();
  	        };
        };
        
        //Esc 关闭弹出层
        this.keyDown();
        
        //拖动层
        if(this.dragId != null){
            this.dragBox(this.dragId,this.popId);
        };
        
        //关闭按钮关闭弹出层
        PopGetId(this.closeId).onclick = function(){
            PopGetId(that.popId).style.display = "none";
            PopGetId("popBg").style.display = "none";
        };
    },
    
    //设置背景
    popBg: function(){
        var _scrollWidth = document.body.scrollWidth;
        var _scrollHeight = document.body.scrollHeight;
        PopGetId("popBg").style.width = _scrollWidth + "px";
        PopGetId("popBg").style.height = _scrollHeight + "px";

        var isIE6 = !-[1,] && !window.XMLHttpRequest;
        if(isIE6){
            PopGetId("popBg").innerHTML="<iframe scrolling='no' height='100%' width='100%' marginwidth='0' marginheight='0' frameborder='0' class='popBgIframe123' id='popBgIframe'/></iframe>";
            PopGetId("popBgIframe").style.width = _scrollWidth + "px"; 
            PopGetId("popBgIframe").style.height = _scrollHeight + "px";
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
        var w = PopGetId(this.popId).offsetWidth;
        var h = PopGetId(this.popId).offsetHeight;
        PopGetId(this.popId).style.left = parseInt((s.sw - w)/2) + s.sl + "px";
        
        if(this.fixedLeft != null && this.fixedId != null){
            PopGetId(this.popId).style.left = this.getElementPos(this.fixedId).x + parseInt(this.fixedLeft) + "px";
        }else if(this.fixedLeft != null && this.fixedId == null){
            PopGetId(this.popId).style.left = parseInt(this.fixedLeft) + "px";
        }else{
            if(s.sw < w){
                PopGetId(this.popId).style.left = 0 + "px";
            }else{
                PopGetId(this.popId).style.left = parseInt((s.sw - w)/2) + s.sl + "px";
            }
        };
        
        if(this.fixedTop != null || this.fixedId != null){
            var _top = 0,_topInt = 0;
            if(this.fixedId != null){
                _top = this.getElementPos(this.fixedId).y;
            };
            if(this.fixedTop != null){
                _topInt = parseInt(this.fixedTop);
            };
            PopGetId(this.popId).style.top = _top + _topInt + "px";
        }else{
            if(s.sh < h){
                PopGetId(this.popId).style.top = 0 + "px";
            }else{
                PopGetId(this.popId).style.top = parseInt((s.sh - h)/2) + s.st + "px";
            }
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
			var	sw = parseInt(PopGetId(that.popId).offsetWidth),
				sh = parseInt(PopGetId(that.popId).offsetHeight);
			var _ID = PopGetId(that.popId);
			moveX = e.clientX - _ID.offsetLeft;
			moveY = e.clientY - _ID.offsetTop;
			
			document.onmousemove = function(e){
			    if(drag){
			        PopGetId(that.dragId).style.background = "#f5f5f5";
                    PopGetId(that.popId).style.zIndex = parseInt(new Date().getTime() / 1000);
                    e = window.event?window.event:e;
			        window.getSelection ? window.getSelection().removeAllRanges() : document.selection.empty(); //禁止拖放对象文本被选择的方法
			        document.body.setCapture && PopGetId(that.popId).setCapture(); // IE下鼠标超出视口仍可被监听
		            var _x = e.clientX - moveX;
		            var _y = e.clientY - moveY;
		            if(_x < 0){
		                PopGetId(that.popId).style.left = 0 + "px";
		            }else{
		                PopGetId(that.popId).style.left = _x < (cw-sw) ? _x + "px":(cw-sw) + "px";
		            }
			        PopGetId(that.popId).style.top = _y + "px";
				};
			};
			document.onmouseup = function(){
			    PopGetId(that.dragId).style.background = "#fff";
				drag = false;
				document.body.releaseCapture && PopGetId(that.popId).releaseCapture();
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
                PopGetId(that.popId).style.display='none';
                PopGetId("popBg").style.display='none';
            };
        };
    }
}