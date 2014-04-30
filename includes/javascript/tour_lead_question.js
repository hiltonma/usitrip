<script language="javascript" charset="gb2312"><!--
function checkForm() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";


 if (document.product_queston_write.lead_fname.value == "") {
    error_message = error_message + "* 请输入您的姓氏.\n";
    error = 1;
  }
  
  if (document.product_queston_write.lead_lname.value == "") {
    error_message = error_message + "* 请输入您的名字.\n";
    error = 1;
  }
  
 
 
     
  
  if (document.product_queston_write.lead_email.value == "") {
    error_message = error_message + "* 请输入您的E-mail地址.\n";
    error = 1;
  }else{
    if (document.product_queston_write.lead_email.value != document.product_queston_write.c_lead_email.value) {
    error_message = error_message + "* 确认E-mail地址必须和您的E-mail地址吻合.\n";
    error = 1;
	}  
  }
  

  if (!validEmail(document.product_queston_write.lead_email.value) && document.product_queston_write.lead_email.value != "")
{
		
		error_message = error_message + "* 请输入正确有效的邮箱地址.\n";
    	error = 1;
}

 if (document.product_queston_write.lead_dayphone.value == "") {
    error_message = error_message + "* 请输入您的日间联系电话.\n";
    error = 1;
  }
  
   if (document.product_queston_write.lead_eveningphone.value == "") {
    error_message = error_message + "* 请输入您的晚间联系电话.\n";
    error = 1;
  }
  
    if (document.product_queston_write.lead_comment.value == "") {
    error_message = error_message + "* 请输入您的问题和留言.\n";
    error = 1;
  }
  
  
 
  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
function validEmail(strEmail)
{	
	
	
    if (strEmail.search(/[A-Za-z0-9\._]+\@[A-Za-z0-9\-]+\.[A-Za-z0-9\.]/gi) != -1)
		return true;
    else
        return false; 
}	
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
