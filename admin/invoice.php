<?php
/*
  $Id: invoice.php,v 1.2 2004/03/13 15:09:11 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $customer_number_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($_GET['order_id'])) . "'");
  $customer_number = tep_db_fetch_array($customer_number_query);
/*
  if ($customer_number['customers_id'] != $customer_id) {
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }
*/
  $payment_info_query = tep_db_query("select payment_info from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($_GET['order_id'])) . "'");
  $payment_info = tep_db_fetch_array($payment_info_query);
  $payment_info = $payment_info['payment_info'];

//  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_INVOICE);

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = tep_db_prepare_input($_GET['oID']);
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");

  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE . ' - ' . TITLE_PRINT_ORDER . $oID; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="print.css">
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">


<!-- body_text //-->
<table width="600" border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td align="center" class="main"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td valign="top" align="left" class="main"><script language="JavaScript">
  if (window.print) {
    document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo IMAGE_BUTTON_PRINT; ?></a></center>');
  }
  else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
        </script></td>
        <td align="right" valign="bottom" class="main"><p align="right" class="main"><a href="javascript:window.close();"><img src='images/close_window.jpg' border=0></a></p></td>
      </tr>
    </table></td>
  </tr>
  <tr align="left">
    <td class="titleHeading"><?php echo tep_draw_separator('pixel_trans.gif', '1', '25'); ?></td>
  </tr>
  <tr align="left">
    <td>
	
  
  
 <?php
//service@usitrip.com

 ob_start();
 include(dirname(__FILE__).'/invoice_email.php'); 
 //$email_order = ob_get_contents();
 $email_order = ob_get_clean();
 echo $email_order;

if(isset($_GET['sendmail']) && $_GET['sendmail']=='true'){

	$emp_email = $order->customer['email_address'];
	$to_name = $order->customer['name'];
	
	if(tep_not_null($email_list)){
		$emp_email = $email_list;
		$to_name = $email_list;
	}
	
	$email_subject = STORE_OWNER." 为您发送的 Invoice ";
	
					
	$to_email_address = $emp_email;
	$email_subject = $email_subject;
	if($_POST['is_sendPagecontent']==1){
		$email_text = preg_replace('/[[:space:]]+/',' ',$email_order);
	}else{
		$email_text = $_POST['mailContent']."<hr>";
	}
	$email_text .= email_track_code('Invoice',$to_email_address,(int)$oID );
	
	$from_email_name = STORE_OWNER;
	$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
	if(!tep_not_null($to_email_address)) die("没收件人邮件的邮箱地址！");
	tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true');
	tep_db_query("update " . TABLE_ORDERS . " set `invoice_issendmail`='1', `invoice_sendtime`=now(), `invoice_sendadmin`='{$login_id}' where orders_id = '". $oID . "'");
	echo '<div align="center"><font color="#FF0000">Invoice mail has been successfully send to customer</font></div>';
}

?>
  
  
	</td>
  </tr>
  
	<tr>
	  <td align="left" >
<script type="text/javascript">
function $(element){
	if (arguments.length > 1){
		for (var i = 0, elements = [], length = arguments.length; i < length; i++)
		elements.push($(arguments[i]));
		return elements;
	}
	if (typeof element == 'string'){
		if (document.getElementById){
			element = document.getElementById(element);
		}else if (document.all){
			element = document.all[element];
		}else if (document.layers){
			element = document.layers[element];
		}
	}
	return element;
}
function addEvent( obj, type, fn ) {
	if (obj.addEventListener) {
		obj.addEventListener( type, fn, false );
		EventCache.add(obj, type, fn);
	}else if (obj.attachEvent) {
		obj["e"+type+fn] = fn;
		obj[type+fn] = function() { obj["e"+type+fn]( window.event ); }
		obj.attachEvent( "on"+type, obj[type+fn] );
		EventCache.add(obj, type, fn);
	}else {
		obj["on"+type] = obj["e"+type+fn];
	}
}
var EventCache = function(){
	var listEvents = [];
	return {
		listEvents : listEvents,
		add : function(node, sEventName, fHandler){
			listEvents.push(arguments);
		},
		flush : function(){
			var i, item;
			for(i = listEvents.length - 1; i >= 0; i = i - 1){
				item = listEvents[i];
				if(item[0].removeEventListener){
					item[0].removeEventListener(item[1], item[2], item[3]);
				};
				if(item[1].substring(0, 2) != "on"){
					item[1] = "on" + item[1];
				};
				if(item[0].detachEvent){
					item[0].detachEvent(item[1], item[2]);
				};
				item[0][item[1]] = null;
			};
		}
	};
}();
addEvent(window,'unload',EventCache.flush);
var UploadFile={};
UploadFile.addFile=function(ulId,filename,maxfile){
    UploadFile.addInputFile(ulId,filename,maxfile);
};
UploadFile.addInputFile=function(ulId,filename,maxfile){
    var ul = $(ulId);
	
    if(ul != null){
		if(ul.getElementsByTagName('li').length>=maxfile){
			alert('最多只能有 '+maxfile+' 个附件！');return false;
		}
        var li = document.createElement("li");
		var fileinput = document.createElement("input");
		fileinput.name = filename;
		fileinput.type = "file";
		var delbt = document.createElement("a");
		delbt.href = "javascript:void(0);";
		delbt.innerHTML = "&#171;删除";
		addEvent(delbt,'click',function(){UploadFile.delInputFile(ul,li);});
        li.appendChild(fileinput);
        li.appendChild(delbt);
        ul.appendChild(li);
    }
};
UploadFile.delInputFile=function(ul,li) {
    if (ul != null && li != null) {
		ul.removeChild(li);
    }
};
</script>
<script language="javascript" src="/admin/includes/big5_gb-min.js"></script>
      <form action="<?php echo tep_href_link(FILENAME_ORDERS_INVOICE, 'oID='.$oID.'&sendmail=true' )?>" method="post" enctype="multipart/form-data" name="myform">
      <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#000000">
	    <tr>
	      <td width="25%" valign="top" bgcolor="#FFFFFF">发票附件：</td>
	      <td width="75%" bgcolor="#FFFFFF">
          <ul style="list-style-type:decimal; margin:0" id="MailAttachment_List">
	        <li><input type="file" name="Mail_Attachment[]">&nbsp;<input type="button" value="增加附件" onClick="javascript:UploadFile.addFile('MailAttachment_List','Mail_Attachment[]',5);"></li>
		  </ul>
	      </td>
	      </tr>
	    <tr>
	      <td valign="top" bgcolor="#FFFFFF">是否发送旧版发票：</td>
	      <td bgcolor="#FFFFFF"><p>
	        <label>
	          <input type="radio" name="is_sendPagecontent" value="1" id="is_sendPagecontent_0" checked>
	          是</label>
	        <label>
	          <input type="radio" name="is_sendPagecontent" value="0" id="is_sendPagecontent_1">
	          否</label>
	        <br>
	        </p></td>
	      </tr>
	    <tr>
	      <td valign="top" bgcolor="#FFFFFF">邮件内容：<br>
<span style="color:#999">(不发送旧版发票时的内容)</span></td>
	      <td bgcolor="#FFFFFF">
			<?php
			echo tep_draw_textarea_field("mailContent", "", 45, 5);
			?>
	        </td>
	      </tr>
	    <tr>
	      <td colspan="2" align="center" bgcolor="#FFFFFF"><input type='image' src="/admin/includes/languages/schinese/images/buttons/button_send_mail.gif" border="0" title=" Send Invoice to mail "></td>
	      </tr>
      </table>
      </form>
      </td>
    </tr>
  
</table>
<!-- body_text_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
