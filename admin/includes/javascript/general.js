function addEvent()
{
var ni = document.getElementById('myDiv');
var numi = document.getElementById('theValue');
var num = (document.getElementById("theValue").value -1)+ 2;
numi.value = num;
var divIdName = "my"+num+"Div";
var newdiv = document.createElement('div');
newdiv.setAttribute("id",divIdName);
newdiv.innerHTML = "<table width='100%'  border='0' cellspacing='3' cellpadding='3'><tr><td valign='top'><input type='file' name='image_introfile[]'></td><td valign='top'><textarea name='cat_intro_alt_introfile[]' rows='3' cols='28' >&nbsp;</textarea></td><td  valign='top'><input type='text' name='cat_intro_sort_order[]' size='10'  value='1'></td><td valign='top'><a href=\"javascript:;\" onclick=\"removeEvent(\'"+divIdName+"\')\">Remove</a></td></tr></table>";
ni.appendChild(newdiv);
}

function removeEvent(divNum)
{
var d = document.getElementById('myDiv');
var olddiv = document.getElementById(divNum);
d.removeChild(olddiv);
}



function SetFocus() {
//  if (document.forms.length > 0) {
//    var field = document.forms[0];
//    for (i=0; i<field.length; i++) {
//      if ( (field.elements[i].type != "image") &&
//           (field.elements[i].type != "hidden") &&
//           (field.elements[i].type != "reset") &&
//           (field.elements[i].type != "submit") ) {
//
//        document.forms[0].elements[i].focus();
//
//        if ( (field.elements[i].type == "text") ||
//             (field.elements[i].type == "password") )
//          document.forms[0].elements[i].select();
//
//        break;
//      }
//    }
//  }
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