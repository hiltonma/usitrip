<?php
/* 全局的js代码，全站适用 */

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

function session_win() {
  window.open("<?php echo tep_href_link(FILENAME_INFO_SHOPPING_CART); ?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
}
function sel_log_cre(){
	var login_box = document.getElementById('login_box');
	var create_box = document.getElementById('create_box');
	var radio_l = document.getElementById('radio_l');
	var radio_c = document.getElementById('radio_c');
	var BreadTop = document.getElementById('BreadTop');
	if(radio_c.checked==true){
		create_box.style.display="";
		login_box.style.display="none";
		BreadTop.innerHTML = "<?php echo db_to_html('完全免费注册')?>";
	}
	if(radio_l.checked==true){
		create_box.style.display="none";
		login_box.style.display="";
		BreadTop.innerHTML = "<?php echo db_to_html('登录');?>";
	}
}

/* 验证注册信息 */
function check_field(form_obj, field_name, notice_id){
	var form_obj = document.getElementById(form_obj);
        if(form_obj!=null){
            var Ok_image = '<img src="image/right_icon.gif"/>';
            var notice_id = document.getElementById(notice_id);
            var error_msn ='';
            var f_obj = form_obj.elements[field_name];
            if(f_obj!=null){
                var check_china_str = false;
                if(f_obj.name=='firstname'){
                        if(f_obj.value.length < <?php echo (int)ENTRY_FIRST_NAME_MIN_LENGTH;?>){
                                error_msn = "<?php echo ENTRY_FIRST_NAME_ERROR?>";
                        }else if(check_china_str==true && f_obj.value.search(/^[^\x00-\xff]+$/g)=="-1"){
                                error_msn = "<?php echo ENTRY_FIRST_NAME_ERROR_ONLYCHINA?>";
                        }
                }
                if(f_obj.name=='email_address'){
                        if(f_obj.value.length < <?php echo (int)ENTRY_EMAIL_ADDRESS_MIN_LENGTH;?>){
                                error_msn = "<?php echo ENTRY_EMAIL_ADDRESS_ERROR?>";
                        }else if(f_obj.value.search(/\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/g)=="-1"){
                                error_msn = "<?php echo ENTRY_EMAIL_ADDRESS_CHECK_ERROR?>";
                        }else{
                    check_account(f_obj.value);
                        }
                }
                if(f_obj.name=='c_email_address'){
                        if(f_obj.value != form_obj.elements['email_address'].value || f_obj.value.length < 1){
                                error_msn = "<?php echo ENTRY_CONFIRM_EMAIL_ADDRESS_CHECK_ERROR?>";
                        }
                }
                if(f_obj.name=='password'){
                        if(f_obj.value.length < <?php echo (int)ENTRY_PASSWORD_MIN_LENGTH?>){
                                error_msn = "<?php echo ENTRY_PASSWORD_ERROR?>";
                        }
                }
                if(f_obj.name=='confirmation'){
                        if(f_obj.value != form_obj.elements['password'].value || f_obj.value.length < 1){
                                error_msn = "<?php echo ENTRY_PASSWORD_ERROR_NOT_MATCHING?>";
                        }
                }
                if(f_obj.name=='visual_verify_code'){
                    if(f_obj.value != form_obj.elements['visual_verify_code'].value || f_obj.value.length < 1){
                            error_msn = "<?php echo db_to_html("请填写图片中显示的字符")?>";
                    }
         	   }
                if(f_obj.name=='country'){
                        if(f_obj.value < 1){
                                error_msn = "<?php echo ENTRY_COUNTRY_ERROR?>";
                        }
                }
                //积分会员卡登录BEGIN
                if(f_obj.name=='pc_change_password'){
                    if(f_obj.value.length < <?php echo (int)ENTRY_PASSWORD_MIN_LENGTH?>){
                            error_msn = "<?php echo ENTRY_PASSWORD_ERROR?>";
                    }
            	}
                if(f_obj.name=='pc_confirm_password'){
                	 if(f_obj.value != form_obj.elements['pc_change_password'].value || f_obj.value.length < 1){
                         error_msn = "<?php echo ENTRY_PASSWORD_ERROR_NOT_MATCHING?>";
                 }
            	}
                //积分会员卡登录END
                if(f_obj.name=='telephone'){
                   check_phone(f_obj.value);
                        if(f_obj.value.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH?>){
                                error_msn = "<?php echo ENTRY_TELEPHONE_NUMBER_ERROR?>";
                        }else if(f_obj.value.search(/^\d+$/g)=="-1"){
                                error_msn = "<?php echo ENTRY_TELEPHONE_NUMBER_ERROR_1?>";
                        }else if(form_obj.elements['country'].value=='44' && f_obj.value.length < 10){
                                error_msn = "<?php echo db_to_html('如果您填写的是座机号码，请在前面添加区号')?>";
                        }else{
                                check_phone(f_obj.value);
                        }
                }
                if(f_obj.name=='read_agreement'){
                if(f_obj.checked != true && f_obj.type =='checkbox'){
                                error_msn = "<?php echo db_to_html('您必需同意走四方客户协议的条款。')?>";
                        }
                }
                if(f_obj.name=='yanzhengma'){
                   if(f_obj.value ==''){
                            error_msn ="<?php echo db_to_html('验证码不能为空')?>";
                   }else{
                        check_yanzhengma(f_obj.value,document.getElementById('telephone').value);
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
                        if(notice_id!=null){
                                notice_id.innerHTML = Ok_image;
                                notice_id.className = "create_default";
                        }
                        return true;
                }
            }
         }
}

function check_form(form_obj){

	var form_ = form_obj;
	var Submit = true;
	var submit_msn = document.getElementById('submit_msn');
	var Fields = new Array();
	Fields[0] = 'firstname';
	Fields[1] = 'email_address';
	Fields[2] = 'c_email_address';
	Fields[3] = 'password';
	Fields[4] = 'confirmation';
	Fields[5] = 'country';
	Fields[6] = 'telephone';
	Fields[7] = 'read_agreement';
	Fields[8] = 'visual_verify_code';
	var yanzhengma_check = document.getElementById('yanzhengma');
	if(yanzhengma_check!=null){ Fields[9] = 'yanzhengma';}
	for(var i=0; i<Fields.length; i++){
		if(check_field(form_, Fields[i], 'chk-' + Fields[i]) != true){
			Submit = false;
		}
	}
	if(Submit==true){
		if(submit_msn != null){
			submit_msn.style.display = '';
		}
		
		form_.submit();
	}
}
<?php
/* tom added 获取随机短信验证码 */
?>
function get_rndpwd(){
     /* window.open("get_rndpwd.php?phone="+document.getElementById('telephone').value, "width=300,height=200,menubar=no,scrollbars=no,resizable=no,toolbar=no"); */
     var msg_pwd = document.getElementById("pwd_send");
     if(document.getElementById('telephone').value!=''&&document.getElementById('firstname').value!=''&&document.getElementById('create_email_address').value!=''&&document.getElementById('c_email_address').value!=''&&document.getElementById('create_password').value!=''&&document.getElementById('confirmation').value!=''){
                 var url = url_ssl("get_rndpwd.php?phone=")+document.getElementById('telephone').value;
                 ajax.open('GET',url,true);
                 ajax.onreadystatechange = function(){
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
                               var msn = "<?php echo db_to_html('您的验证码已经发送，请稍侯……')?>";
                               msg_pwd.className = "validation-sms_send";
                               msg_pwd.innerHTML = msn;
                        }else{
                            msg_pwd.className = "validation-advice";
                            msg_pwd.innerHTML = ajax.responseText;
                        }
                     }
                }
                ajax.send(null);
     }else{
         msg_pwd.className = "validation-advice";
         error_msn ="<?php echo db_to_html('请确认你已经输入了姓名，邮箱，密码')?>";
         msg_pwd.innerHTML = error_msn;

     }
}
<?php
/* 老客户推荐部分js start */
if($Old_Customer_Testimonials_Action==true){
?>
function Show_Old_Customer_Input(num){
	var label_old_customer_obj = document.getElementById('label_old_customer_email_address');
	if(label_old_customer_obj!=null){
		if(num==1){
			label_old_customer_obj.style.display = '';
			label_old_customer_obj.focus();
		}else{
			label_old_customer_obj.style.display = 'none';
		}
	}
}
<?php
}
/* 老客户推荐部分js end */
?>

<?php
if($is_js_file==false){
?>
//--></script>
<?php
}
?>

<?php include(dirname(__FILE__).'/get_country_tel_code.js.php');?>