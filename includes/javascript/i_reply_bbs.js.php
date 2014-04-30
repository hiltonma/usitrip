<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
$is_js_file = false;	/* 如果为false将以php的格式一行一行列到页面 */
if($base_php_self == "javascript.php"){
	$is_js_file = true;
}
if($is_js_file==false){
?>
<script type="text/javascript"><!--
<?php
}
?>

function SubmitReply(obj){
	if(obj.elements['t_c_reply_content'].value==""){
		alert("<?php echo db_to_html('内容不能为空！')?>");
		return false;
	}
	var form = obj;
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_travel_companion_re.php','action=confirm_update')) ?>");
	var aparams=new Array();  /* 创建一个阵列存表单所有元素和值 */
	for(i=0; i<form.length; i++){
		var sparam=encodeURIComponent(form.elements[i].name);  /* 取得表单元素名 */
		sparam+="=";     /* 名与值之间用"="号连接 */
		
		if(form.elements[i].type=="radio"){	/* 处理单选按钮值 */
			var a = a;
			if(form.elements[i].checked){
				a = form.elements[i].value;
			}
			sparam+=encodeURIComponent(a);   /* 获得表单元素值 */
		}else{
			sparam+=encodeURIComponent(form.elements[i].value);   /* 获得表单元素值1 */
		}
		
		aparams.push(sparam);   /* push是把新元素添加到阵列中去 */
	}
	var post_str = aparams.join("&");		/* 使用&将各个元素连接 */
	post_str += "&ajax=true";


	ajax.open("POST", url, true); 
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
	ajax.send(post_str);

	ajax.onreadystatechange = function() { 
		if (ajax.readyState == 4 && ajax.status == 200 ) { 
/* alert(ajax.responseText); */
			var error_regxp = /(.*\[ERROR\])|(\[\/ERROR\].*[:space:]*.*)/g;
			if(ajax.responseText.search(/(\[ERROR\].+\[\/ERROR\])/g)!=-1){
				alert(ajax.responseText.replace(error_regxp,''));
				if(ajax.responseText.search(/(\[JS\].+\[\/JS\])/g)!=-1){
					
					eval(ajax.responseText.replace(/(.*\[JS\])|(\[\/JS\].*[:space:]*.*)/g, ''));
				}
			}

			var success_regxp = /(.*\[SUCCESS\])|(\[\/SUCCESS\].*[:space:]*.*)/g;
			if(ajax.responseText.search(/(\[SUCCESS\]\d+\[\/SUCCESS\])/g)!=-1){		
				/* alert("<?php echo db_to_html('信息成功更新！');?>"); */
				/* 到达最后一页 */
				/* location = ""; */
				var reply_content = document.getElementById("reply_content_"+obj.elements['t_c_reply_id'].value);
				reply_content.innerHTML = nl2br(obj.elements['t_c_reply_content'].value);
				show_edit(obj,reply_content);
			}
			
		}
		
	}
}

function show_edit(obj,obj1){
	if(obj!=null && obj1!=null){
		if(obj.style.display=='none'){
			obj.style.display='';
			obj1.style.display='none';
			
		}else{
			obj.style.display='none';
			obj1.style.display='';
		}
	}
}

/* 转换空格值 */
function nl2br(value) {
	return value.replace(/\n/ig, "<br>");
}

<?php
if($is_js_file==false){
?>
//--></script>
<?php
}
?>