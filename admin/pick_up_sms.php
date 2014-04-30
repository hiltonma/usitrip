<?php
require('includes/application_top.php');
require('includes/classes/PickUpSms.class.php');
$pickupsms = new PickUpSms;
$action = ($_POST['action'] ? $_POST['action'] : ($_GET['action'] ? $_GET['action'] : ''));
switch($action){
	case 'submit_sms':	//提交供应商接机短信内容
		$json = array();
		$now_time = date('Y-m-d H:i:s');
		if($login_groups_id != "1" && strtotime($now_time) >= strtotime(date('Y-m-d 17:59:00'))){	//要预留1分钟给系统
			$json['result'] = 'error';
			$json['errortext'] = iconv('gb2312','utf-8//IGNORE','您今天发迟了，现在是：'.$now_time.'。请明天下午6点(洛杉矶时间)以前再发！');
		}else{	//写信息
			$ajax = true;
			$result = $pickupsms->submit_sms((int)$_POST['agency_id'], ajax_to_general_string($_POST['sms_content']));
			if((int)$result){
				$json['result'] = 'success';
			}else{
				$json['result'] = 'error';
				$json['errortext'] = iconv('gb2312','utf-8//IGNORE','没有更新！');
			}
		}
		echo json_encode($json);
		exit;
	break;
	case 'send_pick_up_sms':	//手动发送接机导游短信
		$json = array();
		if(PickUpSms::cron_send_pick_up_sms()){
			$json['result'] = 'success';
		}
		echo json_encode($json);
		exit;
	break;
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
	<?php if(strtolower(CHARSET)=="big5"){?>
	var onblur0 = 'obj.value = traditionalized(obj.value); ';
	<?php }else{?>
	var onblur0 = 'obj.value = simplized(obj.value); ';
	<?php }?>
function submit_sms(form){
	var fm = form;
	if(fm.elements['sms_content'].value==''){ alert('短信内容不可为空！'); return false; };
	var sButton = jQuery(fm).find('button');
	jQuery(sButton).attr('disabled', true);
	var url = window.location.href.replace(/\?.+/,'');
	jQuery.post(url,{ action:'submit_sms', agency_id:fm.elements['agency_id'].value, sms_content: fm.elements['sms_content'].value },function(json){
		if(json['result']=='error'){
			alert(json['errortext']);
		}else if(json['result']=='success'){
			alert('提交成功！');
			window.location.reload();
		}else{
			alert('未知错误！');
		}
		jQuery(sButton).attr('disabled', false);
		
	},'json');
}

//手动发送接机导游短信
function send_pick_up_sms(btn){
	var url = window.location.href.replace(/\?.+/,'');
	jQuery(btn).attr('disabled', true);
	jQuery(btn).html('发送中，请稍后……');
	jQuery.post(url,{'action':'send_pick_up_sms'},function(json){
		if(json['result'] == 'success'){
			jQuery(btn).html('发送完毕');
		}else{
			jQuery(btn).attr('disabled', false);
			jQuery(btn).html('发送失败，重新发送？');
		}
	},'json');
}
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">上传接机导游信息</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
      	<td>
      		<fieldset>
      			<legend align="left"> 上传接机导游信息 </legend>
      			<div class="col_b3 col_red">
				提示：短信将于出团前一天的18:00(洛杉矶时间) 自动发送
				<?php if($login_groups_id=="1"){?><button type="button" onClick="send_pick_up_sms(this);" >手动发送</button><?php }?>
				</div>
      			<table id="dataTable" border="0" cellspacing="1" cellpadding="0">
      				<tr class="dataTableHeadingRow">
      					<td align="center" nowrap="nowrap" class="dataTableHeadingContent" height="30">供应商ID号</td>
      					<td align="center" nowrap="nowrap" class="dataTableHeadingContent">短信内容</td>
      					<td align="center" nowrap="nowrap" class="dataTableHeadingContent"><?php echo date('d/m',strtotime('-1day'));?> 发送的接机信息</td>
      					<td align="center" nowrap="nowrap" class="dataTableHeadingContent">状态</td>
      					<td align="center" nowrap="nowrap" class="dataTableHeadingContent"><?php echo date('d/m');?> 发送的接机信息</td>
      					<td align="center" nowrap="nowrap" class="dataTableHeadingContent">状态</td>
      					</tr>
      				<?php
			$allows_agency = $pickupsms->allows_agency;
			for($i=0,$n=sizeof($allows_agency); $i<$n; $i++){
				$tody_sms = $pickupsms->get_tody_pick_up_sms($allows_agency[$i]);
				$yesterday_sms = $pickupsms->get_yesterday_pick_up_sms($allows_agency[$i]);
				
				$_class = ($_class == 'dataTableRow' ? 'dataTableRow1' : 'dataTableRow');
			?>
      				<tr class="<?php echo $_class;?>">
      					<td height="25" class="dataTableContent"><?php echo $allows_agency[$i];?>&nbsp;</td>
      					<td nowrap class="dataTableContent" >
      						<form method="post" enctype="multipart/form-data" onSubmit="submit_sms(this); return false;">
      							<input type="hidden" name="agency_id" value="<?php echo $allows_agency[$i];?>">
      							<?php echo tep_draw_input_field('sms_content',$tody_sms['sms_content'],'size="100" maxlength="100" title="每条短信长度请控制在59个中文之内" ');?><button type="submit">Update</button>
      							</form>
      						</td>
      					<td nowrap class="dataTableContent" title="显示昨天下午6点发送的信息"><?php echo tep_db_output($yesterday_sms['sms_content']);?>&nbsp;</td>
      					<td nowrap class="dataTableContent"><?php echo ($yesterday_sms['sent_status']=='1'?'已发送':'');?>&nbsp;</td>
      					<td nowrap class="dataTableContent" title="显示今天下午6点将发送的信息"><?php echo tep_db_output($tody_sms['sms_content']);?>&nbsp;</td>
      					<td nowrap class="dataTableContent"><?php echo ($tody_sms['sent_status']=='1'?'已发送':'');?>&nbsp;</td>
      					</tr>
      				<?php }?>
      				</table>
      			</fieldset>		</td>
      	</tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
