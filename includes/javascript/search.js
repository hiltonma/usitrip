

/*  lwkai add */
var lwkai = {
	Browser : {},
	ShowCount : [],
	init : function(){
		try{  
            var idSeed = 0,  
    	    ua = navigator.userAgent.toLowerCase(),  
			check = function(r){  
				return r.test(ua);  
			},  
			DOC = document,  
			isStrict = DOC.compatMode == "CSS1Compat",  
			isOpera = check(/opera/),  
			isChrome = check(/\bchrome\b/),  
			isWebKit = check(/webkit/),  
			isSafari = !isChrome && check(/safari/),  
			isSafari2 = isSafari && check(/applewebkit\/4/), // unique to Safari 2  
			isSafari3 = isSafari && check(/version\/3/),  
			isSafari4 = isSafari && check(/version\/4/),  
			isIE = !isOpera && check(/msie/),  
			isIE7 = isIE && check(/msie 7/),  
			isIE8 = isIE && check(/msie 8/),  
			isIE6 = isIE && !isIE7 && !isIE8,  
			isGecko = !isWebKit && check(/gecko/),  
			isGecko2 = isGecko && check(/rv:1\.8/),  
			isGecko3 = isGecko && check(/rv:1\.9/),  
			isBorderBox = isIE && !isStrict,  
			isWindows = check(/windows|win32/),  
			isMac = check(/macintosh|mac os x/),  
			isAir = check(/adobeair/),  
			isLinux = check(/linux/),  
			isIpad = check(/ipad/),  
			isSecure = /^https/i.test(window.location.protocol);  
			this.Browser = {  
				isOpera:isOpera,  
				isIE:isIE,  
				isIE6:isIE6,  
				isFirefox:isGecko,  
				isSafari:isSafari,  
				isChrome:isChrome,  
				isIpad:isIpad  
			}  
		}catch(e){}
	},
	/**
	 *  针对弹窗IE6下拉不能挡住的问题
	 *  在IE6 下会动态创建一个IFRAME 
	 *  因此在下面写了个隐藏层的方法 用来处理这个IFRAME
	 *  弹出层此方法会设置为DISPLAY = BLOCK
	 *  注意如果�出框中有所有�容都��示,要先�置成�示後再�用此方法,否�IFRAME出�高度不全
	 *  @param Obj String 弹出层的ID 
	 */
	maskSelect : function(Obj,clearObj){
		if (clearObj != undefined) {
			this.clearMaskSelect(clearObj);
		}
		var divRef = jQuery('#' + Obj);
		if (lwkai.Browser.isIE6) {
			var iframeName = Obj + '_iframe';
			var ifrRef = jQuery('#' + iframeName);
			if (ifrRef.length == 0) {
				divRef.parent().append('<iframe id="' + iframeName + '" style="display:none;top:0px;left:0px;position:absolute;width:0px;height:0px;" frameBorder="0" scrolling="no"></iframe>');
			}
			ifrRef = jQuery('#' + iframeName);
			divRef.css('display','block');
			var height = document.getElementById(Obj).offsetHeight;
			var width = document.getElementById(Obj).offsetWidth;
			ifrRef.css({'width':width + 'px','height':height + 'px','top':divRef.css('top'),'left':divRef.css('left'),'display':'block','zIndex':divRef.css('zIndex') - 1});
			var sel = document.getElementsByTagName("select");
			for(var i = 0; i < sel.length; i ++){
				sel[i].style.visibility = 'hidden';
			}
			this.ShowCount.push(Obj); //保存弹出层的ID 用来判断是否没有弹出框了 因为这里有两个
		} else {
			divRef.css('display','block');
		}
		
		
	},
	/**
	 * 某些情况下会改变弹出框的大小
	 * 这里对iframe重设大小
	 * param Obj String 变化大小的浮动层ID
	 */
	resizeMaskSelect : function(Obj) {
		if (lwkai.Browser.isIE6) {
			var ifrRef = jQuery('#' + Obj + '_iframe');
			if (ifrRef.length != 0 && ifrRef.css('display') == 'block') {
				var height = document.getElementById(Obj).offsetHeight;
				var width = document.getElementById(Obj).offsetWidth;
				ifrRef.css({'width':width + 'px','height':height + 'px'});
			}
		}
	},
	indexOf : function(arr,str) {
		for(var i = 0; i < arr.length; i++) {
			if (arr[i] == str) {
				return i;
			}
		}
	},
	/**
	 *  IE6弹出框iframe挡select 这里对iframe处理
	 *  同时隐藏弹出框
	 *  @param Obj String 弹出层的ID
	 */
	clearMaskSelect : function(Obj,clearObj) {
		if (lwkai.Browser.isIE6) {
			if (Obj == '' || Obj == undefined) return;
			var iframeName = Obj + '_iframe';
			var ifrRef = jQuery('#' + iframeName);
			if (ifrRef.length > 0) {
				//jQuery('#' + Obj).parent().find('#' + iframeName).css('display','none');
				jQuery('#' + Obj).parent().find('#' + iframeName).remove();
			}
			var index = this.indexOf(this.ShowCount,Obj); //查找当前需要清除的弹出层在保存的弹出层中 是否存在
			if (index != -1) {
				this.ShowCount.splice(index,1);  // 移除当前隐藏层ID在保存的显示层名数组中
			}
			if (this.ShowCount.length == 0) { //如果所有的显示层都隐藏了.则放出所有下拉
				var sel = document.getElementsByTagName("select");
				for(var i = 0; i < sel.length; i ++){
					sel[i].style.visibility = 'visible';
				}
			}
		}
		jQuery('#' + Obj).css('display', 'none');
	},
	tabs : function (tabTit,on,tabCon,callback){
		/*jQuery(tabCon).each(function(){
	  		jQuery(this).children().eq(0).show();
	  	});
		jQuery(tabTit).each(function(){
	  		jQuery(this).children().eq(0).addClass(on);
	  	});*/
     	jQuery(tabTit).children().hover(function(){
        	jQuery(this).addClass(on).siblings().removeClass(on);
			jQuery('.tabCon').show();
         	var index = jQuery(tabTit).children().index(this);
         	jQuery(tabCon).children().eq(index).show().siblings().hide();
			if (callback != undefined) {
				//alert(jQuery(this).parent().parent().parent().attr('id'));
				callback(jQuery(this).parent().parent().parent().attr('id'));
			}
    	});
    },
	/**
	 *  检测输入框的值是否为空或者是默认设置值
	 *  如果用户没输入任何字符或者是输入的空格
	 *  则显示默认字符
	 *  如果设置DefClass 并且 输入框内是默认值 则应用该Class 否则不应用
	 *  [注] 默认字符放在对象上 请自定义 deftxt 属性 值为默认字符串
	 *  @param Obj string 需要检测的对象ID
	 *  @param DefClass  可选 默认状态下的Class
	 *  示例:
	 * <input id="test1" type="text" class="input colred" deftxt="请输入关键字" value="" />
	 * 
	 *    lwkai.inputDefTxt('test1','colred');
	 * 
	 */
	inputDefTxt : function(Obj,DefClass){
		var inputTxt = jQuery('#' + Obj);
		var defTxt = inputTxt.attr('deftxt');
		var val = inputTxt.val();
		val = val ? val.replace(/^\s+/g,'').replace(/\s+$/g,'') : '';
		if (val == '') {
			inputTxt.val(defTxt);
			DefClass ? inputTxt.addClass(DefClass) : '';
		}
	},
	
	/**
	 * 写入COOKIE到客户端
	 */
	setCookie : function(name, value, add, expire, lang) {
		add  = !!add;
		expire = expire ? expire : 0;
		var temp = value.split(',');
		var writes = false;
		for(var i = 0, len = temp.length; i < len; i++) {
			var temp2 = temp[i].split(':');
			if (!!temp2[1]) {
				writes = true;
				break;
			}
		}
		if (!writes) return;
		var exp = new Date();
		var finds = false;
		exp.setTime(exp.getTime()+expire*1000);//10
		if (add == true) {
			var temp = this.trim(this.getCookie(name,lang));
			var temp_arr = this.getLast(temp,9);
			var new_arr = [];
			for(var i = 0, len = temp_arr.length; i < len; i++) {
				if (temp_arr[i] == value) {
					finds = true;
					break;
				}
			}
			if (finds == false && temp != '') {
				value = temp + '|' + value;
			}
		}
		value = simplized(value);
		if (finds == false) {
			document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
		}
	},
	
	trim : function(str){
		str = str.replace(/^\s+/,'').replace(/\s+$/,'');
		return str;
	},
	
	getCookie : function(name,lang) {
		var strcookie = document.cookie ;
		//alert(strcookie);
		var value = '';
		arrcookie = strcookie.split(";") ;
		for(var i=0;i<arrcookie.length;i++){
			var arr=arrcookie[i].split("=");
			//alert(arrcookie[i]);
			if(name == this.trim(arr[0])){
				value=unescape(arr[1]);
				 break;
			}
		}
		return this.change_charset(value,lang);
	},
	
	change_charset : function (str,lang){
		return lang == 'gb2312' ? simplized(str) : traditionalized(str);
	},
	
	getLast : function(obj,len) {
		var temp_arr = obj.split('|');
		var rtn_arr = [];
		for (var i = temp_arr.length - 1,j = 0; i > -1; i--,j++) {
			rtn_arr[rtn_arr.length] = temp_arr[i];
			if (j >= len) break;
		}
		return rtn_arr;
	}
};
lwkai.init();
/* end lwkai */