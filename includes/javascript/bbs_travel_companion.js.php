<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
$is_js_file = false;	//如果为false将以php的格式一行一行列到页面
if($base_php_self == "javascript.php"){
	$is_js_file = true;
}
if($is_js_file==false){
?>
<script type="text/javascript"><!--
<?php
}
?>

function MM_jumpMenu(selObj,parameter){ /* v3.0 */
  var location_val = "<?php echo preg_replace($p,$r,tep_href_link_noseo('bbs_travel_companion_rightindex.php'))?>";
  if(parameter!=''){ parameter = '&'+parameter; }
  var link_symbol = '?';
  if(location_val.indexOf('?')>-1){
  	link_symbol = '&';
  }
  if(selObj.options[selObj.selectedIndex].value!=''){
  	eval("self.location='"+location_val+link_symbol+selObj.id+'='+selObj.options[selObj.selectedIndex].value+parameter+"'");
  }

}

function MM_jumpMenu_hoistory(selObj){
  var location_val = "<?php echo preg_replace($p,$r,tep_href_link_noseo('bbs_travel_companion_rightindex.php'))?>";
  var link_symbol = '?';
  if(location_val.indexOf('?')>-1){
  	link_symbol = '&';
  }
  if(selObj.options[selObj.selectedIndex].value!=''){
  	eval("self.location='"+location_val+link_symbol+'TcPath='+selObj.options[selObj.selectedIndex].value+"'");
  }
}

/* 显示并指定回帖表单 */
function show_and_hidden(id_name,parent_id){
	var id = document.getElementById(id_name);
	id.elements['parent_id'].value = parent_id;
	id.elements['parent_type'].value = '0';
	id.elements['t_companion_content'].focus();
	id.elements['t_companion_content'].value = "";
	var bbs_content = document.getElementById('bbs_content_' + parent_id);
	if(bbs_content==null){
		bbs_content = document.getElementById('root_tiezi_post');
	}
	VarQuoteReply = document.getElementById('QuoteReply');
	VarQuoteReply.innerHTML= '<div style="padding:10px 5px 5px 5px; background: #F5F5F5 none repeat scroll 0 0"><?php echo db_to_html('回复：')?>'+bbs_content.innerHTML+'</div>';
	if(id.style.display=='none'){
		id.style.display='';
	}else{
		/* id.style.display='none'; */
	}
}

/* 转换空格值 */
function br2nl(value) {
	return value.replace(/<br \/>|<br>|<br\/>/ig, "\n");
}

/* 引用帖子内容 */
function quote_bbs(id_name,parent_id){
	var id = document.getElementById(id_name);
	id.elements['parent_id'].value = parent_id;
	id.elements['parent_type'].value = '1';
	id.elements['t_companion_content'].focus();
	id.elements['t_companion_content'].value = "";
	
	var tiezi_post = document.getElementById('tiezi_post_' + parent_id)
	VarQuoteReply = document.getElementById('QuoteReply');
	VarQuoteReply.innerHTML= '<div class="yingyong"><?php echo db_to_html('引用：')?>'+tiezi_post.innerHTML+'</div>';
	if(id.style.display=='none'){
		id.style.display='';
	}else{
		/* id.style.display='none'; */
	}
}

/* 检测并提交回帖 */
function Submit_Companion_Re(From_id){

	var Companion = document.getElementById(From_id);
	var error_msn = '';
	var error = false;
	for(i=0; i<Companion.length; i++){
	
		if(Companion.elements[i]!=null){
			if(Companion.elements[i].value.length < 1 && Companion.elements[i].className.search(/required/g)!= -1){
				error = true;
				error_msn +=  "* " + Companion.elements[i].title + "\n\n";
			}
		}
	}
	
	if(error==true){
		alert(error_msn);
		return false;
	}else{
		var form = Companion;
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_travel_companion_re.php','action=process')) ?>");
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
					alert("<?php echo db_to_html('信息添加成功！');?>");
					/* 到达最后一页 */
					location = "<?php echo preg_replace($p,$r,tep_href_link_noseo('bbs_travel_companion_content.php','t_companion_id='.$t_companion_id.'&TcPath='.$TcPath.'&page=10000')); ?>";
				}
				
			}
			
		}

	}
}


<?php
if($is_js_file==false){
?>
//--></script>
<?php
}
?>
