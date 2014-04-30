<?php
require('includes/application_top.php');
require('includes/classes/payment_history_accounts_remark.php');
$account_remark = new payment_history_accounts_remark();

if($_GET['ajax']=="true"){
	switch($_GET['action']){
		//设置已经审核start{
		case 'set_audited':
			tep_db_query('update orders_payment_history set audited="1", audited_time="'.date("Y-m-d H:i:s").'", audited_admin_id="'.(int)$login_id.'" where orders_payment_history_id="'.(int)$_POST['orders_payment_history_id'].'" ');
			echo 'ok';
			exit;
		break;
		//设置已经审核end}
		//设置已经确认start{
		case 'set_has_checked':
			tep_db_query('update orders_payment_history set has_checked="1", checked_time="'.date("Y-m-d H:i:s").'", checked_admin_id="'.(int)$login_id.'" where orders_payment_history_id="'.(int)$_POST['orders_payment_history_id'].'" and has_checked!="1" ');
			if(tep_db_affected_rows()){
				echo 'ok';
			}
			exit;
		break;
		//设置已经确认end}
		//插入财务备注的记录 start {
		case 'addremark':
			$inserted_id = $account_remark->add_remark($_POST['orders_payment_history_id'], iconv("utf-8","gb2312",$_POST['remark']), $login_id);
			if ((int)$inserted_id>0){
				echo 'success';
			}else{
				echo 'error: 插入失败';
			}
			exit();
		break;
		//插入财务备注的记录 end }
		case 'showhistory':	//显示财务备注的历史记录
			$data = $account_remark->show_history($_GET['orders_payment_history_id'],false); 
			$rt = '';
			if(is_array($data))
			{
				$rt = '<table border="1" cellspacing="0" cellpadding="0" style="border-colllapse:collapse;"><tr><th width="160">时间</th><th width="120">工号</th><th width="300">备注内容</th></tr>';
				foreach($data AS $key=>$value)
				{
					$rt .= '<tr><td>'.$value['add_date'].'</td><td>'.$value['admin_job_number'].'</td><td>'.tep_db_output($value['remark']).'</td></tr>';
				}
				$rt .= '</table>';
			}
			echo $rt;
			exit();
		break;
	}
}

//取得退款流水账信息 start{
$tables = ' orders_refund r LEFT JOIN (orders o, orders_total ot, `orders_payment_history` oph ) ON (o.orders_id=r.orders_id and r.orders_id=oph.orders_id and oph.orders_value <0 and ot.orders_id=o.orders_id and ot.class="ot_total" )';
$fields = ' oph.*, r.orders_id, o.orders_owners, o.customers_id, o.customers_name, o.orders_status, ot.value ';
$where  = ' 1 ';
$group_by = ' ';

if($_GET['search']=="1"){ //搜索处理
	
	//订单号
	if(tep_not_null($_GET['orders_id'])){
		$where.= ' and r.orders_id Like("'.(int)$_GET['orders_id'].'%") ';
	}
	//所属客服工号
	if(tep_not_null($_GET['orders_owners'])){
		$where.= ' and FIND_IN_SET("'.(int)$_GET['orders_owners'].'", o.orders_owners) ';
	}
	//顾客姓名
	if(tep_not_null($_GET['customers_name'])){
		$where.= ' and o.customers_name Like ("%'.tep_db_prepare_input(tep_db_output($_GET['customers_name'])).'%") ';
	}
	//退款方式
	if(tep_not_null($_GET['orders_pay_method'])){
		$where.= ' and oph.payment_method Like ("'.tep_db_prepare_input(tep_db_output($_GET['orders_pay_method'])).'") ';
	}
	//退款时间
	if(tep_not_null($_GET['add_date_start'])){
		$where.= ' and oph.add_date >="'.date("Y-m-d H:i:s",strtotime($_GET['add_date_start'])).'" ';
	}
	if(tep_not_null($_GET['add_date_end'])){
		$where.= ' and oph.add_date <="'.date("Y-m-d 23:59:59",strtotime($_GET['add_date_end'])).'" ';
	}
	//出团时间
	$_DISTINCT = '';	//有出团时间的统计要添加DISTINCT关键字
	//已到账
	if($_GET['has_checked']=="1"){
		$where.= ' and oph.has_checked ="1" ';
	}elseif($_GET['has_checked']=="2"){
		$where.= ' and oph.has_checked !="1" ';
	}
	//已审核
	if($_GET['audited']=="1"){
		$where.= ' and oph.audited ="1" ';
	}elseif($_GET['audited']=="2"){
		$where.= ' and oph.audited !="1" ';
	}
	
	if(tep_not_null($_GET['customers_email'])){
		$where.=' and o.customers_email_address like "%'.$_GET['customers_email'].'%" ';
	}
	//订单下单类型
	if(isset($_GET['is_other_owner']) && $_GET['is_other_owner']!==''){
		$where.=' and o.is_other_owner in('.tep_db_prepare_input(tep_db_output(rawurldecode($_GET['is_other_owner']))).') ';
	}
	
}

if(!tep_not_null($_GET['order']) && $_GET['order']!='desc' && $_GET['order']!='asc'){
	$_GET['order'] = 'desc';
}
$order_by = 'oph.has_checked ASC, r.last_time '.$_GET['order'];
switch($_GET['sort']){
	case 'orders_id': $order_by = ' r.orders_id '.$_GET['order']; $order_id_class = $_GET['order']; break;
	case 'orders_value': $order_by = ' oph.orders_value '.$_GET['order']; $orders_value_class = $_GET['order']; break;
	case 'add_date': $order_by = ' oph.add_date '.$_GET['order']; $add_date_class = $_GET['order']; break;
	
}

$sql_str = 'SELECT '.$fields.' FROM '.$tables.' WHERE '.$where . $group_by.' ORDER BY '.$order_by;
//金额合计
$TotalAmountRow = tep_db_fetch_array(tep_db_query('SELECT SUM(oph.orders_value) as total FROM '.$tables.' WHERE '.$where));
$TotalAmount = $TotalAmountRow['total'];

//echo $sql_str; exit;
//取得退款流水账信息 end}

//下载到本地 start{
if(tep_not_null($_GET['download']) && $_GET['download']=="1"){
	$filename = date("YmdHis").'.xls';
	header("Content-type: text/html; charset=utf-8");	//用utf-8格式下载才行
	//header("Content-type: text/x-csv");
	header("Content-type: application/vnd.ms-excel");
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header("Content-Transfer-Encoding:binary");
	header("Content-Disposition: attachment;filename=".$filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
	header("HTTP/1.0 200 OK");
	header("Status: 200 OK");
	ob_start();
	
	//echo '"订单号","顾客姓名","退款金额","退款方式","退款时间","所属客服工号","出团时间","备注","订单状态","操作员","退款状态"'."\n";
	echo '<table border="1" cellpadding="0" cellspacing="0">';
	echo '<tr><td>订单号</td><td>顾客姓名</td><td>退款金额($)</td><td>退款方式</td><td>退款时间</td><td>所属客服工号</td><td>备注</td><td>订单状态</td><td>操作员</td><td>退款状态</td><td>机票</td><td>订制团</td><td>团款</td></tr>';

	$sql = tep_db_query($sql_str);
	while($rows = tep_db_fetch_array($sql)){
		$job_19 = tep_get_order_owner_jobs_id($rows['orders_id']);
		
		echo '<tr><td>'.$rows['orders_id'].'</td><td>'.tep_db_output($rows['customers_name']).'</td><td>'.$rows['orders_value'].'</td><td>'.tep_db_output($rows['payment_method']).'</td><td>'.$rows['add_date'].'</td><td>'.($job_19=='19' ? $job_19.',':'').$rows['orders_owners'].'</td><td>'.tep_db_output($rows['comment']).'</td><td>'.tep_db_output(tep_get_orders_status_name($rows['orders_status'])).'</td><td>'.tep_db_output(tep_get_admin_customer_name($rows['admin_id'])).'</td><td>'.(($rows['has_checked']=="1") ? '已退款':' ').'</td><td>'.tep_db_output($rows['comment_flights']).'</td><td>'.tep_db_output($rows['comment_individuation']).'</td><td>'.tep_db_output($rows['comment_other']).'</td></tr>';
	}
	echo '<tr><td colspan="14">合计：'.$TotalAmount.'</td></tr>';
	echo '</table>';
	echo iconv('gb2312','utf-8//IGNORE',ob_get_clean());
	//excel文件输出结束
	//require(DIR_WS_INCLUDES . 'application_bottom.php');
	exit;
}
//下载到本地 end}
$rows_total = 0;
$dis_row_num = 10;
$splits = new splitPageResults($_GET['page'], $dis_row_num, $sql_str, $rows_total);
$sql = tep_db_query($sql_str);

//取得所有退款方式 start{
$orders_pay_methods_sql = tep_db_query('SELECT * FROM `orders_pay_methods` WHERE 1 ');
$orders_pay_methods_options = array();
$orders_pay_methods_options[] = array('id'=>"",'text'=>"----不限----");
while($orders_pay_methods_rows = tep_db_fetch_array($orders_pay_methods_sql)){
	$orders_pay_methods_options[] = array('id'=>$orders_pay_methods_rows['pay_method'],'text'=>$orders_pay_methods_rows['pay_method']);
}

//取得所有退款方式 end}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-blink.js"></script>
<script type="text/javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>

<link rel="stylesheet" href="includes/javascript/jquery-plugin-boxy/css/common.css" type="text/css" />
<link rel="stylesheet" href="includes/javascript/jquery-plugin-boxy/css/boxy.css" type="text/css" />
<script type="text/javascript" src="includes/javascript/jquery-plugin-boxy/jquery.boxy.js"></script>

<script type="text/javascript">
function set_has_checked(ordersPaymentHistoryId,userId,orderId,_type,RefundAmount){
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('accounts_refund.php','ajax=true&action=set_has_checked')) ?>");
	var td = "#button_td_"+ordersPaymentHistoryId;
	var but = td+" button";
	jQuery(but).attr('disabled',true);
	jQuery.post(url, {"orders_payment_history_id": ordersPaymentHistoryId, "user_id":userId, "order_id":orderId}, function (data, textStatus){
		if(data == 'ok'){
			jQuery(td).text('已退款');
			//确认后自动带到订单页面发邮件去
			var send_mail_type = '';
			var windowsTips = '已退款，去给客户发邮件(部分退款)';
			if(_type=='full'){
				send_mail_type = 'full';
				windowsTips = '已退款，去给客户发邮件(全额退款)';
			}
			window.open('<?php echo preg_replace($p,$r,tep_href_link_noseo('edit_orders.php','sendmail_for_refund=1'));?>'+'&send_mail_type='+ send_mail_type +'&oID='+ orderId +'&action=edit&RefundAmount='+ RefundAmount +'#emailEditBox',windowsTips);
		}else{
			alert('功能异常，请联系技术部！');
		}
	});
}

function set_audited(ordersPaymentHistoryId){
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('accounts_refund.php','ajax=true&action=set_audited')) ?>");
	var td = "#audited_button_td_"+ordersPaymentHistoryId;
	var but = td+' button';
	jQuery(but).attr('disabled',true);
	jQuery.post(url, {"orders_payment_history_id": ordersPaymentHistoryId}, function (data, textStatus){
		if(data == 'ok'){
			jQuery(td).text('已审核');
		}else{
			alert('功能异常，请联系技术部！');
		}
	});
}

function add_account_remark(obj,id)
{
	if("none" == jQuery("#input_remark_"+id).css("display"))
	{
		jQuery("#input_remark_"+id).css("display","block");
		obj.innerHTML="保存";
	}
	else
	{
		//ajax		
		var url="accounts_refund.php?ajax=true&action=addremark";
		var remark=jQuery("#input_remark_"+id).val();		//alert(remark);return false;
		if (remark.length<1){ alert("未填写内容"); return false; }
		if (remark.length>100){ alert("内容长度不能超过100个字"); return false;}
		jQuery.post(url, {"orders_payment_history_id": id,"remark":remark}, function (data, textStatus){
			if('success' == data ){
				alert("ok");
				jQuery("#input_remark_"+id).css("display","none");
				var text=jQuery("#input_remark_"+id).val();
				jQuery("#div_remark_"+id).text(text);
				obj.innerHTML="添加";
			}
			else
			{
				alert(data);
			}
		});	
	}
}

var allDialogs = [];
function show_account_remark(obj,id)
{
	var url="accounts_refund.php";
	jQuery.get(url, {"ajax":"true","action":"showhistory","orders_payment_history_id": id}, function (data, textStatus){
		if( data.length>0 ){
				var options = {modal:true};
				options = jQuery.extend({title: "财务备注之历史记录"}, options || {});
				var dialog = new Boxy(data, options);
				allDialogs.push(dialog);
		}
		else
		{
			
		}

	});	
}
//更新其他备注信息
jQuery(document).ready(function (){
	jQuery('button.btnCommentUpdate').click(function(){
		var top_this = this;
		jQuery(top_this).attr('disabled',true);
		var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('accounts_refund.php','action=update_other_comment&ajax=true')) ?>");;
		var orders_payment_history_id  = jQuery(this).attr('v');
		var inputs = jQuery('#ulComment_' + orders_payment_history_id + ' input');
		var data = 'orders_payment_history_id=' + orders_payment_history_id;
		jQuery(inputs).each(function(i){
			data += '&'+encodeURIComponent(jQuery(this).attr('name'))+'=' + encodeURIComponent(jQuery(this).val());
		});
		jQuery.post(url,data,function(text){
			if(text=='ok'){ alert('其他备注更新成功！'); }else{ alert('更新失败！'); }
			jQuery(top_this).attr('disabled',false);
		},'text');
	});
});
</script>
<style>
.dataTableContentUl { padding:0;}
.dataTableContentUl li{height:23px; width:152px;}
.dataTableContentUl li input{width:100px; display:block; float:right;}
.dataTableContentUl span{width:50px; display:block; float:left; text-align:right;}

</style>
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
            <td class="pageHeading">客户退款报表</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <!--search form start-->
		  <fieldset>
		  <legend align="left"> <a href="javascript:void(0);" onClick="$('#form_search').toggle();" title="点击可收缩或展开搜索栏内容">搜索栏</a> </legend>
		  <?php echo tep_draw_form('form_search', 'accounts_refund.php', tep_get_all_get_params(array('page','y','x', 'action')), 'get',' id="form_search" '); ?>
		  
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><table border="0" cellspacing="0" cellpadding="0" style="margin:10px;">
                  <tr>
                    <td height="25" align="right" class="main">订单号：</td>
                    <td class="main" align="left"><?php echo tep_draw_input_num_en_field('orders_id');?>&nbsp;</td>
                    <td class="main" align="right">所属客服工号：</td>
                    <td class="main" align="left"><?php echo tep_draw_input_num_en_field('orders_owners');?>&nbsp;</td>
                    <td class="main" align="right">顾客姓名：</td>
                    <td class="main" align="left"><?php echo tep_draw_input_field('customers_name');?>&nbsp;</td>
                    <td colspan="4" align="right" class="main">退款方式：</td>
                    <td align="left" class="main"><?php echo tep_draw_pull_down_menu('orders_pay_method',$orders_pay_methods_options,'','style="width:200px;"');?>&nbsp;</td>
                  </tr>
                  <tr>
                  	<td height="25" align="right" class="main">退款时间：</td>
                  	<td class="main" align="left">
					<?php echo tep_draw_input_num_en_field('add_date_start','','style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="textTime"');?>
					&nbsp;
					至
					&nbsp;
					<?php echo tep_draw_input_num_en_field('add_date_end','','style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="textTime"');?>
					
					</td>
                  	<td class="main" align="right">&nbsp;</td>
                  	<td class="main" align="left">&nbsp;&nbsp;</td>
                  	<td class="main" align="right">&nbsp;退款状态：</td>
                  	<td class="main" align="left">
					<?php echo tep_draw_pull_down_menu('has_checked',array(array('id'=>'', 'text'=>'----不限----'), array('id'=>'1', 'text'=>'已退款'), array('id'=>'2', 'text'=>'待退款')),$_GET['has_checked'],'style="width:138px;"');?>
					
					</td>
                  	<td colspan="4" align="right" class="main">&nbsp;</td>
                  	<td class="main" align="left">&nbsp;</td>
                  	</tr>
                  <tr>
                  	<td class="main" align="right">下单人E_mail：</td>
                  	<td class="main" align="left"><?php echo tep_draw_input_field('customers_email');?></td>
                  	<td class="main" align="right">订单下单类型：</td>
                  	<td class="main" align="left">
					<?php echo tep_draw_pull_down_menu('is_other_owner',array(array('id'=>'', 'text'=>'----不限----'), array('id'=>'1', 'text'=>'客人自行下单(无销售跟踪和工号链接)'), array('id'=>'3,2,0', 'text'=>'普通订单')),$_GET['is_other_owner']);?>
					</td>
                  	<td class="main" align="right">是否已审：</td>
                  	<td>
					<?php echo tep_draw_pull_down_menu('audited',array(array('id'=>'', 'text'=>'----不限----'), array('id'=>'1', 'text'=>'已审核'), array('id'=>'2', 'text'=>'未审')),$_GET['audited'],'style="width:138px;"');?>
					</td>
                  	<td colspan="4" align="right" class="main">&nbsp;</td>
                  	<td align="right" class="main">&nbsp;</td>
                  	</tr>
                  <tr>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="left">&nbsp;<input name="Send" type="submit" value="Send" style="width:100px; height:30px; margin-top:10px;">
                      <input type="hidden" name="search" value="1">                      &nbsp;[<a href="<?php echo tep_href_link('accounts_refund.php')?>">清除</a>] [<a target="_blank" href="<?php echo tep_href_link('accounts_refund.php','download=1&'.tep_get_all_get_params(array('page','y','x', 'action', 'download')));?>">下载到本地</a>]
					</td>
                    <td>&nbsp;</td>
                    <td class="main" align="right">&nbsp;</td>
                    <td class="main" align="left">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="4" align="right" class="main">&nbsp;</td>
                    <td align="right" class="main">&nbsp;</td>
                    </tr>
                  <tr>
                  	<td class="main" align="right">&nbsp;</td>
                  	<td colspan="10" align="left" class="main">
					<?php
					if($login_id=='19'){
						echo $sql_str;
					}
					?>
					</td>
                  	</tr>
                  <tr>
                  	<td class="main" align="right">&nbsp;</td>
                  	<td colspan="10" align="left" class="main"><b style="font-size:14px;">汇总金额：$<?php echo $TotalAmount;?></b></td>
                  	</tr>
                </table></td>
			  </tr>
			</table>

		  <?php echo '</form>';?>
		  </fieldset>
		  <!--search form end-->
		  </td>
      </tr>
      <tr>
        <td>
		<fieldset>
		  <legend align="left"> 记录显示栏 </legend>
		  
		  <?php
		  //排序连接参数设置
		  $_sort_get_params = '&order='.($_GET['order']=="asc" ? 'desc' : 'asc').'&'.tep_get_all_get_params(array('page','y','x', 'action', 'sort', 'order'));
		  ?>

		<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="1" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" nowrap="nowrap" height="32"><a href="<?php echo tep_href_link('accounts_refund.php','sort=orders_id'.$_sort_get_params);?>" class="<?php echo $order_id_class;?>">订单号</a></td>
                <td class="dataTableHeadingContent" nowrap="nowrap">顾客姓名</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">订单金额</td>
                <td class="dataTableHeadingContent" nowrap="nowrap"><a href="<?php echo tep_href_link('accounts_refund.php','sort=orders_value'.$_sort_get_params);?>" class="<?php echo $orders_value_class;?>">退款金额($)</a></td>
                <td class="dataTableHeadingContent" nowrap="nowrap">退款方式</td>
				<td class="dataTableHeadingContent" nowrap="nowrap"><a href="<?php echo tep_href_link('accounts_refund.php','sort=add_date'.$_sort_get_params);?>" class="<?php echo $add_date_class;?>">退款时间</a></td>
                <td class="dataTableHeadingContent" nowrap="nowrap">所属客服工号</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">备注</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">订单状态</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">操作员</td>
                <td class="dataTableHeadingContent" nowrap="nowrap">退款状态</td>
				<td class="dataTableHeadingContent" nowrap="nowrap">是否已审</td>
				<td class="dataTableHeadingContent" nowrap="nowrap">财务备注</td>
            	</tr>
            <?php
			  $l = 0;
				  while($rows = tep_db_fetch_array($sql)){
				  $l++;
				  $background = '';
				  //if($l%1==0){ $background = 'background-color:#F0F0F0'; }
				  $orders_value_style = '';
				  if($rows['orders_value']<0){
					  $orders_value_style = ' style="color:#FF0000" ';
				  }
			?>
			  <tr class="dataTableRow" style="cursor:auto; <?php echo $background;?> " onMouseOut="rowOutEffect(this)" onMouseMove="rowOverEffect(this)">
                <td valign="middle" class="dataTableContent" height="30">
				<a target="_blank" href="<?php echo tep_href_link('edit_orders.php','oID='.(int)$rows['orders_id'].'&action=edit');?>"><?php echo $rows['orders_id'];
                                                            //结伴同游
                                                            if (is_travel_comp((int) $rows['orders_id']) > 0) {
                                                                echo '<br><b style="color:#FF9900">结伴同游</b> ';
                                                            }
                                                            //结伴同游 end
                                                            //团购标记
                                                            if (have_group_buy((int) $rows['orders_id']) > 0) {
                                                                echo '<br><b style="color:#006699">团体预定</b> ';
                                                            }
                                                            //团购标记 end?>
				</a>
				</td>
                <td class="dataTableContent"><a target="_blank" href="<?php echo tep_href_link('customers.php','cID='.$rows['customers_id'].'&action=edit');?>"><?php echo tep_db_output($rows['customers_name']);?></a></td>
                <td class="dataTableContent"><?php echo $rows['value'] ? '$'.number_format($rows['value'], 2, '.', '') : ''?></td>
                <td class="dataTableContent" <?php echo $orders_value_style;?>><?php echo ($rows['orders_value'] ? '$'.$rows['orders_value']:'')?></td>
                <td class="dataTableContent"><?php echo tep_db_output($rows['payment_method'])?></td>
                <td class="dataTableContent"><?php echo $rows['add_date']?></td>
                <td class="dataTableContent"><?php $job_19 = tep_get_order_owner_jobs_id($rows['orders_id']); if($job_19=='19'){ echo '<p style="color:#F00">'.$job_19.'</p>'; } echo  $rows['orders_owners']?></td>
                <td class="dataTableContent"><?php echo nl2br(tep_db_output($rows['comment']));?></td>
                <td class="dataTableContent"><?php echo tep_get_orders_status_name($rows['orders_status'])?></td>
                <td class="dataTableContent"><?php echo tep_get_admin_customer_name($rows['admin_id']);?></td>
                <td class="dataTableContent" style="color:#009900;" id="button_td_<?php echo (int)$rows['orders_payment_history_id'];?>">
				<?php
				if($rows['orders_payment_history_id']){
					echo (($rows['has_checked']=="1") ? '<span title="确认时间：'.$rows['checked_time'].'">'.tep_get_job_number_from_admin_id($rows['checked_admin_id']).'已退款</span>':'<button title="若已给客人退款请点击此按钮" type="button" onClick="set_has_checked('.(int)$rows['orders_payment_history_id'].',' . (int)$rows['customers_id'] . ',' . (int)$rows['orders_id'] . ', \''.(abs((int)$rows['orders_value'])==(int)$rows['value'] ? 'full':'').'\',\''.rawurlencode('$'.$rows['orders_value']).'\');">待退款</button>');
				}
				?>
				</td>
				<td class="dataTableContent" style="color:#009900;" id="audited_button_td_<?= $rows['orders_payment_history_id'];?>">
				<?php
				if($rows['orders_payment_history_id']){	
					echo ($rows['audited'] ? '<span title="'.$rows['audited_time'].'">'.tep_get_job_number_from_admin_id($rows['audited_admin_id']).'已审核</span>': '<button type="button" onClick="set_audited('.$rows['orders_payment_history_id'].')">审核</button>');
				}
				?>
				</td>
				<td class="dataTableContent">
					<div id="div_remark_<?php echo $rows['orders_payment_history_id'];?>">
				<?php
					$last_remark = $account_remark->show_history($rows['orders_payment_history_id'],true);
					if(is_array($last_remark)) echo nl2br(tep_db_output($last_remark[0]['remark'])); //tep_db_output()
				?>
					</div>
					<?php 
						echo tep_draw_textarea_field('', 'virtual', '20', '2', '', 'id="input_remark_'.$rows['orders_payment_history_id'].'"  style="display:none;"');
					?>
					
					<a href="javascript:void(0)" onClick="add_account_remark(this,<?php echo $rows['orders_payment_history_id'];?>)"/>添加</a>				
					<?php if (is_array($last_remark)) {?>
						<a href="javascript:void(0)" onClick="show_account_remark(this,<?php echo $rows['orders_payment_history_id'];?>)"/>more&gt;&gt;</a>
					<?php }?>
					
				</td>
				</tr>
			  <?php }?>
            </table></td>
          </tr>
        </table>
		
		</fieldset>
		</td>
      </tr>
	  <tr>
	  <table border="0" width="100%" cellspacing="0" cellpadding="2">
	  <tr>
		<td class="smallText" valign="top"><?php echo $splits->display_count($rows_total, $dis_row_num, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
		<td class="smallText" align="right"><?php echo $splits->display_links($rows_total, $dis_row_num, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>
	  </tr>
	</table>
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
