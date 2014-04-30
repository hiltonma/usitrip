document.domain = "usitrip.com";

var Browser = {
	/*@cc_on
	isIE : true,
	@*/
	isFF : window.navigator.appName.indexOf("Netscape") != -1 ? true : false
};

//创建XMLHttp对象 start
var XMLHttp = {
    _objPool: [],
   
    _getInstance: function ()
    {
        for (var i = 0; i < this._objPool.length; i ++)
        {
            if (this._objPool[i].readyState == 0 || this._objPool[i].readyState == 4)
            {
                return this._objPool[i];
            }
        }
   
        // IE5中不支持push方法
        this._objPool[this._objPool.length] = this._createObj();
   
        return this._objPool[this._objPool.length - 1];
    },
   
    _createObj: function ()
    {
		if(window.XMLHttpRequest)
		{
			try{
				xmlhttp = new XMLHttpRequest();
			}catch(e){xmlhttp = null;}
		}
		if(Browser.isIE&&window.ActiveXObject)
		{
			var Version = [
				"Msxml2.XMLHTTP.6.0","Msxml2.XMLHTTP.5.0","Msxml2.XMLHTTP.4.0",
				"Msxml2.XMLHTTP.3.0","Msxml2.XMLHTTP.2.6","Msxml2.XMLHTTP",
				"Microsoft.XMLHTTP.1.0","Microsoft.XMLHTTP.1","Microsoft.XMLHTTP"
			];
			for(var i = 0;i < Version.length;i++)
			{
				try{
					xmlhttp = new ActiveXObject(Version[i]);
					break;
				}catch(e){xmlhttp = null;}
			}
		}
		return xmlhttp;
    },
   
    // 发送请求(方法[post,get], 地址, 数据, 回调函数)
    sendReq: function (method, url, data, callback)
    {
        var objXMLHttp = this._getInstance();
		if (objXMLHttp == null) {
			alert('不能创建XMLHTTP对象！');
			return;
		}
        
            try
            {
                // 加随机数防止缓存
                if (url.indexOf("?") > 0)
                {
                    url += "&randnumforajaxaction=" + Math.random();
                }
                else
                {
                    url += "?randnumforajaxaction=" + Math.random();
                }
				//alert(method + ' ---- ' + url );
				//method = 'POST';
                objXMLHttp.open(method, url, true);
                // 设定请求编码方式
                objXMLHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                objXMLHttp.send(data);
                objXMLHttp.onreadystatechange = function ()
                {
                    if (objXMLHttp.readyState == 4 && (objXMLHttp.status == 200 || objXMLHttp.status == 304))
                    {
                        callback(objXMLHttp);
                    }
                }
            }
            catch(e)
            {
                alert(e);
            }

    }
};
//创建XMLHttp对象 end

function HttpRequest()
{
	this.async = true;
	this.cache = false;
	this.xmlhttp  =null;
}
HttpRequest.prototype = {
	getAjax : function()
	{
		if(window.XMLHttpRequest)
		{
			try{
				this.xmlhttp = new XMLHttpRequest();
			}catch(e){this.xmlhttp = null;}
		}
		if(Browser.isIE&&window.ActiveXObject)
		{
			var Version = [
				"Msxml2.XMLHTTP.6.0","Msxml2.XMLHTTP.5.0","Msxml2.XMLHTTP.4.0",
				"Msxml2.XMLHTTP.3.0","Msxml2.XMLHTTP.2.6","Msxml2.XMLHTTP",
				"Microsoft.XMLHTTP.1.0","Microsoft.XMLHTTP.1","Microsoft.XMLHTTP"
			];
			for(var i = 0;i < Version.length;i++)
			{
				try{
					this.xmlhttp = new ActiveXObject(Version[i]);
					break;
				}catch(e){this.xmlhttp = null;}
			}
		}
	},
	send:function(object,url,callback)
	{

		this.getAjax();
		if(!this.xmlhttp) return;
		this.xmlhttp.open(object ? "post" : "get",url,this.async ? true : false);
		this.xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		if(!this.cache)
		{
			this.xmlhttp.setRequestHeader("No-Cache","1");
			this.xmlhttp.setRequestHeader("Pragma","no-cache");
			this.xmlhttp.setRequestHeader("Cache-Control","no-cache");
			this.xmlhttp.setRequestHeader("Expire","0");
			this.xmlhttp.setRequestHeader("Last-Modified","Wed, 1 Jan 1997 00:00:00 GMT");
			this.xmlhttp.setRequestHeader("If-Modified-Since","-1");
		}
		if(this.async)
		{
			var current = this;
			this.callback = callback;
			this.xmlhttp.onreadystatechange = function(){current.stateChange();}
		}
		else
		{
			if(typeof(callback) == "function")
			{
				callback(this.xmlhttp);
			}
			else if(callback != "")
			{
				eval(callback);
			}
		}
		this.xmlhttp.send(object);
	},
	stateChange : function()
	{
		if(this.xmlhttp.readyState == 4)
		{
			if(this.xmlhttp.status == 200 || this.xmlhttp.status == 0)
			{
				if(this.callback != null)
				{
					if(typeof(this.callback) == "function")
					{
						this.callback(this.xmlhttp);
					}
					else if(this.callback != "")
					{
						eval(this.callback);
					}
				}
			}
		}
	}
};

//ajax 提交GET数据
function ajax_get_submit(Str,url,success_msm,success_go_to,replace_id){
	var url = url;
	XMLHttp.sendReq('GET', url, null, ajax_get_return_data);
}

function ajax_get_return_data(obj){
		var error_regxp = /(.*\[ERROR\])|(\[\/ERROR\].*[:space:]*.*)/g;
		var success_regxp = /(.*\[SUCCESS\])|(\[\/SUCCESS\].*[:space:]*.*)/g;

		if(obj.responseText.search(/(\[ERROR\].+\[\/ERROR\])/g)!=-1){
			alert(obj.responseText.replace(error_regxp,''));
		}else if(obj.responseText.search(/(\[JS\].+\[\/JS\])/g)!=-1){
			eval(obj.responseText.replace(/(.*\[JS\])|(\[\/JS\].*[:space:]*.*)/g, ''));
		}else if(obj.responseText.search(/(\[SUCCESS\]\d+\[\/SUCCESS\])/g)!=-1){		
			if(success_msm!=""){
				alert(success_msm);
			}else{
				alert("本次操作成功！");
			}
			if(success_go_to!=""){
				location = success_go_to;
			}
		}
	}


function auto_update_langding_page_price(){
	var span = document.getElementsByTagName("i");
	var s = document.getElementsByTagName("s");
	var p_ids = '';
	for(i=0; i<span.length; i++){
		if(span[i].id.indexOf('new_price_')>-1){
			p_ids += (span[i].id.replace(/new_price_/,'')) + ',';
		}
	}
	var host = window.location.host;
	var http = (("https:" == document.location.protocol) ? " https://" : " http://");
	var url = http + host + '/ajax_landing_page.php?action=process&p_ids='+p_ids;
	var success_msm = "";
	var success_go_to = "";
	ajax_get_submit(p_ids,url,success_msm,success_go_to);
}

auto_update_langding_page_price();