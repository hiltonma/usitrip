function SetFocus() {
	/*
  if (document.forms.length > 0) {
    var field = document.forms[0];
    for (i=0; i<field.length; i++) {
      if ( (field.elements[i].type != "image") &&
           (field.elements[i].type != "hidden") &&
           (field.elements[i].type != "reset") &&
           (field.elements[i].type != "submit") ) {

        document.forms[0].elements[i].focus();

        if ( (field.elements[i].type == "text") ||
             (field.elements[i].type == "password") )
          document.forms[0].elements[i].select();

        break;
      }
    }
  }
  */
}

function rowOverEffect(object) {
  if (object.className == 'dataTableRow') object.className = 'dataTableRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'dataTableRowOver') object.className = 'dataTableRow';
}
function toggel_div(divid)
    {
        if(eval("document.getElementById('" +  divid + "').style.display") == '')
            eval("document.getElementById('" +  divid + "').style.display = 'none'");
        else
            eval("document.getElementById('" +  divid + "').style.display = ''");
    }
	
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
	window.alert("Can not create XMLHttpRequest object instance.");
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
			alert("success");
		}
		if(success_go_to!=""){
			location = success_go_to;
		}
	}
}

//ajax 提交POST数据
function ajax_post_submit(url,form_id,success_msm,success_go_to, replace_id){
	var _ajax = false;
	if(window.XMLHttpRequest) {
		 _ajax = new XMLHttpRequest();
	}
	else if (window.ActiveXObject) {
		try {
				_ajax = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
		try {
				_ajax = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!_ajax) {
		window.alert("<?php echo db_to_html('不能创建XMLHttpRequest对象实例.')?>");
	}
	var form = document.getElementById(form_id);
	var aparams=new Array();  //创建一个阵列存表单所有元素和值

	for(i=0; i<form.length; i++){
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
	_ajax.open("POST", url, true); 
	_ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
	_ajax.send(post_str);

	_ajax.onreadystatechange = function() { 
		if (_ajax.readyState == 4 && _ajax.status == 200 ) { 
//alert(_ajax.responseText);
			var error_regxp = /(.*\[ERROR\])|(\[\/ERROR\].*[:space:]*.*)/g;
			var success_regxp = /(.*\[SUCCESS\])|(\[\/SUCCESS\].*[:space:]*.*)/g;
			if(_ajax.responseText.search(/(\[ERROR\].+\[\/ERROR\])/g)!=-1){
				alert(_ajax.responseText.replace(error_regxp,''));
			}else if(_ajax.responseText.search(/(\[JS\].+\[\/JS\])/g)!=-1){
				eval(_ajax.responseText.replace(/(.*\[JS\])|(\[\/JS\].*[:space:]*.*)/g, ''));
			}else if(_ajax.responseText.search(/(\[SUCCESS\]\d+\[\/SUCCESS\])/g)!=-1){		
				if(success_msm!=""){
					alert(success_msm);
				}
				if(success_go_to!=""){
					location = success_go_to;
				}
			}else if(typeof(replace_id)!="undefined"){
				var Replace_ID = document.getElementById(replace_id);
				if(Replace_ID!=null){
					Replace_ID.innerHTML = _ajax.responseText;
				}
			}
			
		}
		
	}

}