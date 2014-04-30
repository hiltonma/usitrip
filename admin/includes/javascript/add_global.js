///以下是通用型的
function Check_Onfocus(obj){
	if(obj.value==obj.title){
		obj.value="";
		//obj.className='input_search2';
		obj.style.color = "#353535";
	}
}
function Check_Onblur(obj){
	if(obj.value==""){
		obj.value=obj.title;
		obj.style.color = "#BBBBBB";
	}
}
function url_ssl(url){
	var SSL_ = false;
	if(document.URL.search(/^https:\/\//)>-1){
		SSL_ = true;
	}
	var new_url = url;
	if(SSL_==true){
		new_url = url.replace(/^http:\/\//,"https://");
	}else{
		new_url = url.replace(/^https:\/\//,"http://");
	}
	return new_url;
}
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
        if (window.XMLHttpRequest)
        {
            var objXMLHttp = new XMLHttpRequest();

        }
        else
        {
            var MSXML = ['MSXML2.XMLHTTP.5.0', 'MSXML2.XMLHTTP.4.0',
                  'MSXML2.XMLHTTP.3.0', 'MSXML2.XMLHTTP', 'Microsoft.XMLHTTP'];
            for(var n = 0; n < MSXML.length; n ++)
            {
                try
                {
                    var objXMLHttp = new ActiveXObject(MSXML[n]);
                    break;
                }
                catch(e)
                {
                }
            }
         }

        // mozilla某些版本没有readyState属性
        if (objXMLHttp.readyState == null)
        {
            objXMLHttp.readyState = 0;

            objXMLHttp.addEventListener("load", function ()
                {
                    objXMLHttp.readyState = 4;

                    if (typeof objXMLHttp.onreadystatechange == "function")
                    {
                        objXMLHttp.onreadystatechange();
                    }
                },  false);
        }

        return objXMLHttp;
    },

    // 发送请求(方法[post,get], 地址, 数据, 回调函数)
    sendReq: function (method, url, data, callback)
    {
        var objXMLHttp = this._getInstance();

        with(objXMLHttp)
        {
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

                open(method, url, true);

                // 设定请求编码方式
                setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                send(data);
                onreadystatechange = function ()
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
    }
};
//创建XMLHttp对象 end
//ajax 提交GET数据
function ajax_get_submit(url,success_msm,success_go_to,replace_id){
	var url = url;
	XMLHttp.sendReq('GET', url, null, ajax_get_return_data);
	/*
	ajax.open("GET", url, true);
	ajax.send(null);
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200 ) {
			var error_regxp = /(.*\[ERROR\])|(\[\/ERROR\].*[:space:]*.*)/g;
			var success_regxp = /(.*\[SUCCESS\])|(\[\/SUCCESS\].*[:space:]*.*)/g;
			if(ajax.responseText.search(/(\[ERROR\].+\[\/ERROR\])/g)!=-1){
				alert(ajax.responseText.replace(error_regxp,''));
			}else if(ajax.responseText.search(/(\[JS\].+\[\/JS\])/g)!=-1){
				eval(ajax.responseText.replace(/(.*\[JS\])|(\[\/JS\].*[:space:]*.*)/g, ''));
			}else if(ajax.responseText.search(/(\[SUCCESS\]\d+\[\/SUCCESS\])/g)!=-1){
				if(success_msm!=""){
					alert(success_msm);
				}
				if(success_go_to!=""){
					location = success_go_to;
				}
			}else if(typeof(replace_id)!="undefined"){
				var Replace_ID = document.getElementById(replace_id);
				if(Replace_ID!=null){
					Replace_ID.innerHTML = ajax.responseText;
				}
			}
		}
	}*/
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
			alert('本次操作成功');
		}
		if(success_go_to!=""){
			location = success_go_to;
		}
	}else if(typeof(replace_id)!="undefined"){
		var Replace_ID = document.getElementById(replace_id);
		if(Replace_ID!=null){
			Replace_ID.innerHTML = obj.responseText;
		}
	}else{
		//alert(obj.responseText);
	}

}

//ajax 提交POST数据
function ajax_post_submit(url,form_id,success_msm,success_go_to, replace_id){

	var form = document.getElementById(form_id);
	var aparams=new Array();  //创建一个阵列存表单所有元素和值

	for(var i=0; i<form.length; i++){
		if(form.elements[i].type=="radio" || form.elements[i].type=="checkbox" ){	//处理单选、复选按钮值
			var a = '';
			if(form.elements[i].checked == true){
				var sparam=encodeURIComponent(form.elements[i].name);  //取得表单元素名
				sparam+="=";     //名与值之间用"="号连接
				a = form.elements[i].value;
				sparam+=encodeURIComponent(a);   //获得表单元素值
				aparams.push(sparam);   //push是把新元素添加到阵列中去
			}
		}else{
			var sparam=encodeURIComponent(form.elements[i].name);  //取得表单元素名
			sparam+="=";     //名与值之间用"="号连接
			sparam+=encodeURIComponent(form.elements[i].value);   //获得表单元素值1
			aparams.push(sparam);   //push是把新元素添加到阵列中去
		}
	}
	var post_str = aparams.join("&");		//使用&将各个元素连接
	post_str += "&ajax=true";

	// 加随机数防止缓存
	if (url.indexOf("?") > 0)
	{
		url += "&randnumforajaxaction=" + Math.random();
	}
	else
	{
		url += "?randnumforajaxaction=" + Math.random();
	}
	ajax.open("POST", url, true);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send(post_str);

	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200 ) {
//alert(ajax.responseText);
			var error_regxp = /(.*\[ERROR\])|(\[\/ERROR\].*[:space:]*.*)/g;
			var success_regxp = /(.*\[SUCCESS\])|(\[\/SUCCESS\].*[:space:]*.*)/g;
			if(ajax.responseText.search(/(\[ERROR\].+\[\/ERROR\])/g)!=-1){
				alert(ajax.responseText.replace(error_regxp,''));
			}else if(ajax.responseText.search(/(\[JS\].+\[\/JS\])/g)!=-1){
				eval(ajax.responseText.replace(/(.*\[JS\])|(\[\/JS\].*[:space:]*.*)/g, ''));
			}else if(ajax.responseText.search(/(\[SUCCESS\]\d+\[\/SUCCESS\])/g)!=-1){
				if(success_msm!=""){
					alert(success_msm);
				}
				if(success_go_to!=""){
					location = success_go_to;
				}
			}else if(typeof(replace_id)!="undefined"){
				var Replace_ID = document.getElementById(replace_id);
				if(Replace_ID!=null){
					Replace_ID.innerHTML = ajax.responseText;
				}
			}

		}

	}

}

//创建XMLHttp对象 end

//创建ajax对象
var ajax = false;
if(window.XMLHttpRequest) {
	 ajax = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
	try {
			ajax = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
	try {
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e) {}
	}
}
if (!ajax) {
	window.alert("不能创建XMLHttpRequest对象实例");
}

//自动区分https和http取值
function url_ssl(url){
	var SSL_ = false;
	if(document.URL.search(/^https:\/\//)>-1){
		SSL_ = true;
	}
	var new_url = url;
	if(SSL_==true){
		new_url = url.replace(/^http:\/\//,"https://");
	}else{
		new_url = url.replace(/^https:\/\//,"http://");
	}
	return new_url;
}

function write_success_notes(out_time, notes_contes, gotourl){	//消息发成功到后的提示层消息或到新页面
	var Notes = document.getElementById("OutTimeNotes");
	if(Notes==null){ alert("No OutTimeNotes");}
	var Content = document.getElementById("OutTimeNotesContent");
	if(Content==null){ alert("No OutTimeNotesContent");}
	Content.innerHTML = notes_contes;
	showDiv(Notes.id);
	OutTimeGoto(gotourl,out_time);
	if(typeof(gotourl)!='undefined' && gotourl!=""){
	}else{
		var out_num = out_time*1000;
		window.setTimeout('closeDiv("'+Notes.id+'")',out_num);
	}
}
///显示n秒后关闭当前层或到达新页面 end

