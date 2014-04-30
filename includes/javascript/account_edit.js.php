<?php
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

//验证验证码
function check_field(form_obj, field_name, notice_id){
	var form_obj = document.getElementById(form_obj);
	var Ok_image = '<img src="image/right_icon.gif"/>';
	var notice_id = document.getElementById(notice_id);
	var error_msn ='';
	var f_obj = form_obj.elements[field_name];
	var check_china_str = false;

	if(f_obj.name=='yanzhengma2'&&document.getElementById('confirmphone').value!=''){
	   if(f_obj.value ==''){
	   	    error_msn ="<?php echo db_to_html('验证码不能为空')?>";
	   }else{
	   	check_yanzhengma(f_obj.value,document.getElementById('confirmphone').value);
	   }
        }
        
        if(error_msn!=''){
                    if(notice_id!=null){
                            notice_id.innerHTML = error_msn;
                            notice_id.className = "validation-advice";
                    }else{
                            alert(error_msn);
                    }
                    return false;
            }else{
                    if(notice_id!=null&&document.getElementById('confirmphone').value!=''){
                            notice_id.innerHTML = Ok_image;
                            notice_id.className = "create_default";
                    }
                    return true;
            }
}

function check_form(form_obj){
	var form_ = form_obj;
	var Submit = true;
	var submit_msn = document.getElementById('submit_msn2');
	var Fields = new Array();
	Fields[0] = 'yanzhengma2';

	if(Submit==true){
		if(submit_msn != null){
			submit_msn.style.display = '';
		}
	     form_.submit();
	}
}



function get_rndpwd_edit(){
     //window.open("get_rndpwd.php?phone="+document.getElementById('telephone').value, "width=300,height=200,menubar=no,scrollbars=no,resizable=no,toolbar=no");
     var msg_pwd = document.getElementById("pwd_send_edit");
     var url = url_ssl("get_rndpwd.php?phone=")+document.getElementById('confirmphone').value;
     ajax.open('GET',url,true);
     ajax.onreadystatechange = function(){
     if (ajax.readyState == 4 && ajax.status == 200){
           if (ajax.readyState == 4 && ajax.status == 200){
               if(ajax.responseText.search(/4/)!=-1){
                         var error_msn = "<?php echo db_to_html('该手机号已经注册，请输入其他手机')?>";
                         msg_pwd.className = "validation-advice";
                         msg_pwd.innerHTML = error_msn;
                }else if(ajax.responseText.search(/2/)!=-1){
                         var error_msn = "<?php echo db_to_html('发送失败！')?>";
                         msg_pwd.className = "validation-advice";
                         msg_pwd.innerHTML = error_msn;
                }else if(ajax.responseText.search(/3/)!=-1){
                       var error_msn = "<?php echo db_to_html('你输入的手机号有误！')?>";
                       msg_pwd.className = "validation-advice";
                       msg_pwd.innerHTML = error_msn;
                }else if(ajax.responseText.search(/000/)!=-1){
                    msg_pwd.className = "validation-sms_send";
                    msg_pwd.innerHTML = "<?php echo db_to_html('随机短信验证码已经发送到你的手机！')?>";
                }else{
					var error_msn = "<?php echo db_to_html('短信发送失败！')?>";
                    msg_pwd.className = "validation-advice";
                    msg_pwd.innerHTML = error_msn;
				}
           }
     }
    }
  ajax.send(null);
}

function check_yanzhengma(yanzhengma,telephone){
	var url="check_new_account_ajax.php?yanzhengma="+yanzhengma+"&telephone="+telephone;
	ajax.open('GET',url,true);
	ajax.send(null);
	ajax.onreadystatechange = function(){
			if (ajax.readyState == 4 && ajax.status == 200 ) {
				if(ajax.responseText.search(/2/)!=-1){

                                        var error_msn = "<?php echo db_to_html('验证码错误')?>";
					if(document.getElementById("chk-yanzhengma2")){
						document.getElementById("chk-yanzhengma2").innerHTML=error_msn;
						document.getElementById("chk-yanzhengma2").className="validation-advice";
						document.getElementById("chk-yanzhengma2").style.display ="";
					}else{
                                         alert(yanzhengma2 + error_msn);
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

