<?php
require('includes/application_top.php');


//$admin_list = $salestrack->admin_list();/*获取后台用户列表,用于显示列表及人员名字匹配*/
$action=$_GET['action'];
if($action=="add"){
	require('includes/classes/salestrack.php');	//载入销售跟踪的类文件
	$salestrack = new salestrack;
	$insert_id=$salestrack->addnew($_POST);
	if((int)($insert_id)){
	  echo 'success';
	  echo '<script language="javascript" type="text/javascript">window.opener.location.href=window.opener.location.href;window.close();</script>';
	  exit();
	}
}
/*
if($_POST['action']=="add"){
	$insert_id = $guestbooks->insert_or_update($_POST,'insert');
	if((int)$insert_id){
		tep_redirect('guestbook.php');
	}
}*/
if(!tep_not_null($_POST['is_important'])){ $is_important='0'; }
if(!tep_not_null($_POST['pay_status'])){ $pay_status='0'; }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?>----销售跟踪---增加记录----内部使用</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script language="javascript" type="text/javascript">
</script>
<style type="text/css">
.tbList { border:1px solid #CCCCCC; border-collapse:collapse;}
.tbList th{ background-color:#006699; color:#FFFFFF; font-weight:bolder; font-size:90%; border:1px solid #CCCCCC; padding:3px;}
.tbList td{ border:1px solid #CCCCCC; padding:3px; font-size:90%;}
.tbList td span.imp2{color:#FF0000; font-weight:bolder;}
.tbList td span.imp1{color:#FF0000; font-weight:normal;}
.tbList td span.imp0{color:#000000; font-weight:normal;}
.tbList tr.bc{ background-color:#EEEEEE}
.tbList .finish{color:#0000FF;}
.tbList .remark{color:#666666; font-weight:normal; font-size:80%;}

ul.admin_list{float:left;}
ul.admin_list li{float:left; width:180px; margin:5px 5px;}
ul.admin_list a,ul.admin_list a:visited{padding:3px;}
ul.admin_list a:hover{background-color:#0000FF; color:#FFFFFF;}

input.formbox{width:200px;}
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
/*
 * aben,2012-3-23
 * 如果加载了hearer.php,则就不用再写下面的echo $messageStack->output();和加载includes/big5_gb-min.js
 * */
//require(DIR_WS_INCLUDES . 'header.php'); 
  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
?>
<script type="text/javascript" src="includes/big5_gb-min.js"></script>
<!-- header_eof //-->
<script language="javascript" type="text/javascript">
function checkForm(){
  var c_name=$('#customer_name').val();
  var c_tel=$('#customer_tel').val();
  var c_mobile=$('#customer_mobile').val();
  var c_email=$('#customer_email').val();
  var c_qq=$('#customer_qq').val();
  var c_msn=$('#customer_msn').val();
  var c_skype=$('#customer_skype').val();
  var c_info=$('#customer_info').val();
  if(c_name.length<2){alert('Error:\n\n请输入客人姓名'); return false;}
  if(c_tel.length==0 && c_mobile.length==0 && c_email.length==0 && c_qq.length==0 && c_msn.length==0 && c_skype.length==0){alert('Error:\n\n客人电话,手机,E-mail,QQ,MSN,SKYPE至少要填写一个'); return false;}
  if(c_email.length>0){
	  var reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	  if(!reg.test(c_email)){ alert('Error:\n\n email格式不正确'); return false; }	  
  }
  if(c_info.length<2){alert('Error:\n\n请输入客人咨询内容'); return false;}
}
</script>
<!-- body //-->
<h3>新增销售跟踪</h3>
    <form name="form1" method="post" action="?action=add" onsubmit="return checkForm()">
      <table class="tbList" border="0" align="center" bgcolor="#FFFFFF">
        <tr> 
          <td width="100" height="20" align="right">紧急度:</td>
          <td align="left">
            <?php echo tep_draw_radio_field('is_important','2','','',' id="is_important2"');?>
            <label for="is_important2" style="font-weight:bolder; color:#FF0000">非常紧急</label>
            <?php echo tep_draw_radio_field('is_important','1','','',' id="is_important1"');?>
            <label for="is_important1" style="font-weight:normal; color:#FF0000;">紧急</label>
            <?php echo tep_draw_radio_field('is_important','0','','',' id="is_important0"');?>
            <label for="is_important0">普通</label>
          </td>
        </tr>
        <tr> 
          <td align="right">付款状态:</td>
          <td align="left">
            <?php echo tep_draw_radio_field('pay_status','0','','',' id="payStatus0"');?>
            <label for="payStatus0" style="color:#FF0000">未付款</label>
            <?php echo tep_draw_radio_field('pay_status','1','','',' id="payStatus1"');?>
            <label for="payStatus1" style="color:#0000FF">已付款</label>
		  </td>
        </tr>
        <tr> 
          <td align="right">客人姓名:</td>
          <td align="left">
          <?php echo tep_draw_input_field('customer_name','','id="customer_name" class="formbox"');?>
          <span style="color:#FF0000">*</span></td>
        </tr>
        <tr> 
          <td align="right">客人电话:</td>
          <td align="left">
          <?php echo tep_draw_input_field('customer_tel','','id="customer_tel" class="formbox" style="ime-mode:disabled"');?>
          <span class="remark">客人电话,手机,E-mail,QQ,MSN,SKYPE至少要填写一个</span></td>
        </tr>
        <tr> 
          <td align="right">手机:</td>
          <td align="left">
          <?php echo tep_draw_input_field('customer_mobile','','id="customer_mobile" class="formbox" style="ime-mode:disabled"');?>
          </td>
        </tr>
        <tr> 
          <td align="right"><span style="color:#0000FF;">E-mail</span>:</td>
          <td align="left">
          <?php echo tep_draw_input_field('customer_email','','id="customer_email" class="formbox" style="ime-mode:disabled"');?>
          </td>
        </tr>
        <tr> 
          <td align="right">QQ:</td>
          <td align="left">
          <?php echo tep_draw_input_field('customer_qq','','id="customer_qq" class="formbox" style="ime-mode:disabled"');?>
          </td>
        </tr>
        <tr> 
          <td align="right">MSN:</td>
          <td align="left">
          <?php echo tep_draw_input_field('customer_msn','','id="customer_msn" class="formbox" style="ime-mode:disabled"');?>
          </td>
        </tr>
        <tr> 
          <td align="right">SKYPE:</td>
          <td align="left">
          <?php echo tep_draw_input_field('customer_skype','','id="customer_skype" class="formbox" style="ime-mode:disabled"');?>
          </td>
        </tr>
        <tr> 
          <td align="right">计划参团时间:</td>
          <td align="left">
          <?php echo tep_draw_input_field('customer_plan_tdate','','id="customer_plan_tdate" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" readonly=""');?>
          <span class="remark">点击选择日期</span></td>
        </tr>
        <tr> 
          <td align="right">下次联系时间:</td>
          <td align="left">
          <?php echo tep_draw_input_field('next_condate','','id="next_condate" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" readonly=""');?>
          <span class="remark">点击选择日期</span></td>
        </tr>
		<tr> 
		  <td align="right" valign="top"><span style="color:#0000FF;">团号</span>:</td>
		  <td align="left">
		  <?php echo tep_draw_input_field('code','','id="code" class="formbox" style="ime-mode:disabled"');?>		  
		  <span class="remark">(多个团号之间请以英文逗号(,)隔开, 中文逗号(，)无效. 团号务必用半角英文)</span>
		  </td>
		</tr>
        <tr> 
          <td align="right" valign="top">客户咨询内容:</td>
          <td align="left">详细内容:<br/>
          <?php echo tep_draw_textarea_field('customer_info', '','80', '5', '',' id="customer_info"')?>
          <span style="color:#FF0000">*</span></td>
        </tr>
        <tr> 
          <td align="right" valign="top">订单号:</td>
          <td align="left">
          <?php echo tep_draw_input_num_en_field('orders_id','','id="orders_id" class="formbox"')?>                   
		  <span class="remark">(填写后,系统可以在订单付款后自动修改销售跟踪的付款状态)</span>
		  </td>
        </tr>
        <tr> 
          <td colspan="2" align="center" valign="top"><input type="submit" id="submit" value="提交" />&nbsp;<input onclick="javascript:window.close()" type="button" value="返回列表" /></td>
        </tr>
      </table>
    </form>

<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>