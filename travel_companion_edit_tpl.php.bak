<div id="EditCompanion" class="center_pop" style="display:<?='none'?>; text-align:left">
<form action="" method="post" name="CompanionForm" id="CompanionForm" onSubmit="Submit_Companion_Update('CompanionForm'); return false" >
<table style="float:left" cellSpacing=0 cellPadding=0 width="100%" border=0>
<tr><td align=right class="table_input_box"><A style="FLOAT: right" href="javascript:closeDiv('EditCompanion')" title="<?php echo db_to_html("关闭")?>"><IMG src="image/close_1.gif" border=0></A></td></tr>
<tr>
    <td align="center" style="font-weight: normal;" id="table_input_box" class="table_input_box">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
</table>
</form>
<script type="text/javascript">
/*function formCallback(result, form) {
	window.status = "valiation callback for form '" + form.id + "': result = " + result;
}

var valid = new Validation('EditCompanion', {immediate : true,useTitles:true, onFormValidate : formCallback});
	
Validation.add('validate-email-confirm-que', 'Your confirmation email does not match your first email, please try again.', function(v){
		return (v == $F('customers_email'));
});*/
</script>
<?php
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>

<script type="text/javascript">
var action_onmove = true;
function change_spiffycalendar_style(num){
	var rslt = navigator.appVersion.indexOf('MSIE 6.0');
	var date_select_layer = document.getElementById('date_select_layer');
	if(date_select_layer!=null){
		if(num=='YES' && action_onmove == true){
			if(rslt == -1){
				date_select_layer.innerHTML = '<div id="spiffycalendar" style="z-index:1000;margin-left:-102px; position:fixed;"></div>';
			}
			action_onmove = false;
		}else if(num=='NO' && action_onmove == false){
			//alert(date_select_layer.innerHTML);
			date_select_layer.innerHTML = '<div id="spiffycalendar" style="z-index:1000;margin-left:-102px;"></div>';
			action_onmove = true;
		}
	}
}
function Submit_Companion_Update(Form_id) {
	
	var From_ = document.getElementById(Form_id);
	var error_msn = '';
	var error = false;
	
	for(i=0; i<From_.length; i++){
	
		if(From_.elements[i]!=null){
			if(From_.elements[i].value.length < 1 && From_.elements[i].className.search(/required/g)!= -1){
				error = true;
				error_msn +=  "* " + From_.elements[i].title + "\n\n";
			}
		}
	}
	
	if(error==true){
		alert(error_msn);
		return false;
	}else{
		var loading_img = document.getElementById("loading_img");
		loading_img.style.display = '';
		var form = From_;
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_travel_companion_bbs_edit.php','action=confirm_update')) ?>");
		var aparams=new Array();  //创建一个阵列存表单所有元素和值
	
		for(i=0; i<form.length; i++){
			
			if(form.elements[i].type=="radio"){	//处理单选按钮值
				var a = '';
				if(form.elements[i].checked){
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
	
	
		ajax.open("POST", url, true); 
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		ajax.send(post_str);

		ajax.onreadystatechange = function() { 
			if (ajax.readyState == 4 && ajax.status == 200 ) { 
				var error_regxp = /(.*\[ERROR\])|(\[\/ERROR\].*[:space:]*.*)/g;
				if(ajax.responseText.search(/(\[ERROR\].+\[\/ERROR\])/g)!=-1){
					alert(ajax.responseText.replace(error_regxp,''));
				}
	
				var success_regxp = /(.*\[SUCCESS\])|(\[\/SUCCESS\].*[:space:]*.*)/g;
				if(ajax.responseText.search(/(\[SUCCESS\]\d+\[\/SUCCESS\])/g)!=-1){		
					alert("<?php echo db_to_html('信息更新成功！');?>");
					var t_cid = form.elements['t_companion_id'].value;
					document.getElementById('travel_title_'+t_cid).innerHTML = form.elements['t_companion_title'].value;
					document.getElementById('travel_customers_name_'+t_cid).innerHTML = form.elements['customers_name'].value;
					var gender ='';
					for(i=0; i<form.elements['t_gender'].length; i++){
						if(form.elements['t_gender'][i].checked==true){
							gender = form.elements['t_gender'][i].value;
							break;
						}
					}
					if(gender=='1'){
						document.getElementById('travel_gender_'+t_cid).innerHTML = '<?= db_to_html('先生')?>';
					}
					if(gender=='2'){
						document.getElementById('travel_gender_'+t_cid).innerHTML = '<?= db_to_html('女士')?>';
					}

					closeDiv('EditCompanion');

				}
				
			}
			
		}

	}
}
</script>

</div>