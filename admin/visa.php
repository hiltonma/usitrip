<?php
require('includes/application_top.php');
require('includes/classes/visa.php');

$visa = new visa();

//后台管理员下单给路嘉,并返回订单号
if($_GET['action'] == 'order')
{
//	$postdata=false;
//	$postdata['vis_tag_name']=$_POST['vis_tag_name'];
//	$postdata['orders_id']=$_POST['orders_id'];
//	$postdata['tdate']=$_POST['tdate'];
//	$postdata['vis_to_date']=$_POST['vis_to_date'];
//	$postdata['vis_req_date']=$_POST['vis_req_date'];	

	if( (int)$_POST['visa_random_number'] == (int)$_SESSION['visa_random_number'])
	{
		echo 'ERROR: re-submit';
		exit();
	}
	
	$_SESSION['visa_random_number']=(int)$_POST['visa_random_number'];
	
	$visa_id = $visa->visa_order_admin($_POST,$login_id);
	if ($visa_id ==0)
	{
		echo 'error';
	}
	else
	{
		echo '<br/><br/>';
		echo '签证订单号是: <span style="font-size:24px; font-bolder:bolder; font-family:Arial; color:#0000FF;">'.$visa_id .'</span>';
		echo '<hr/><br/>订单提交成功后,请勿刷新此页面,否则会造成重复下单.<br/><br/>'.date('Y-m-d H:i:s');
		echo '<br/><br/><a href="edit_orders.php?language=sc&oID='.$_POST['orders_id'].'&action=edit">返回订单详情页面</a>';
	}
	exit();
}

//后台管理员手动更新visa订单列表到走四方数据库
if($_GET['action'] == 'updatelistfromlujia')
{
	$rt = $visa->visa_update_order_list_fromlujia();
	if(true == $rt['result'])
	{
		echo '一共更新了'.$rt['inserted_count'].'笔订单资料<hr/>'.date('Y-m-d H:i:s');
		echo '<br/><a href="javascript: history.go(-1)">返  回</a>';
	}
	else
	{
		echo '<span style="color:#FF0000">ERROR:<br/>'.$rt['error_msg'].'</span><hr/>'.date('Y-m-d H:i:s');
	}
	exit();
}

//后台管理员去visa系统查看客人的订单列表
if($_GET['action'] == 'admin_goto_view_customer_visa_order')
{
	$ORD_ID = $_GET['ORD_ID'];
	
	$visa_info = $visa->admin_goto_view_customer_visa_order($ORD_ID);
	
	if(is_array($visa_info))
	{
		$url = VISA_DOMAIN . $visa_info['URL_VISA_ORDER_LIST'];
		//echo $url;
		tep_redirect($url);
	}
	else
	{
		echo 'error:会员账号找不到';
	}

	exit();
}


$_SESSION['visa_random_number']=0;

$download = (int)$_GET['download'];

if($download == "1"){
	$filename = 'visa_order_'.date("YmdHis").'.xls';
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
}

if($download != "1"){
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?>----VISA后台下单/订单查看----内部使用</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<!--<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>//-->
<script type="text/javascript" src="includes/javascript/jquery-1.4.1.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script language="javascript" type="text/javascript">
</script>
<style type="text/css">
.tbList { border:1px solid #CCCCCC; border-collapse:collapse; font-size:14px;}
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

a:hover{font-weight:normal; color:#FF0000;}
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php 
	require(DIR_WS_INCLUDES . 'header.php');
}
//默认是后台下签证订单
if($_GET['action'] == 'list'){
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
<h2>在这里下单,默认是已收款的, 请确认已经收到签证的费用后再下单.</h2>
签证订单列表(这里只显示后台下单的历史记录):
<table class="tbList">
  <tr><th>VISA订单号</th><th>访美目的</th><th>下单时提交的信息</th><th>下单人</th><th>建立时间</th></tr>
<?php
$orders_id = $_GET['orders_id'];
$data = $visa->get_visa_order_list($orders_id);


$n = ($data == false) ? 0 : count($data);

for($i = 0; $i < $n; $i++)
{
?>
  <tr>
	<td><?php echo $data[$i]['visa_orderid'];?></td>
	<td><?php echo $data[$i]['vis_tag_name'];?></td>
	<td><?php echo $data[$i]['orders_info'];?></td>
	<td><?php echo $data[$i]['admin_job_number'];?></td>
	<td><?php echo $data[$i]['add_date'];?></td>
  </tr>
<?php
}
;?>
</table>
<?php
//print_r(preg_split("//",'请选择访美目的',-1, PREG_SPLIT_NO_EMPTY));
$data = false;
$data = $visa->prepare_info_for_visa($orders_id);
if (!is_array($data)){
	echo 'Error: order not list';
	exit();
}

$products= $visa ->get_visa_product_list();

?>
<script type="text/javascript" language="javascript">
function checkForm()
{
  var TAG_NAME=document.getElementById("vis_tag_name").options[document.getElementById("vis_tag_name").selectedIndex].value;
  if(TAG_NAME.length==0){ alert("请选择访美目的"); return false; }

  var tdate=document.getElementById("tdate").value;
  var VIS_TO_DATE=document.getElementById("vis_to_date").value;
  var VIS_REQ_DATE=document.getElementById("vis_req_date").value;
  if(tdate.length==0){ alert("请选择出团日期"); return false; }
  if(VIS_TO_DATE.length==0){ alert("请选择预计赴美日期"); return false; }
  if(VIS_REQ_DATE.length==0){ alert("请选择希望签证日期"); return false; }
  if(!confirm("您确认要提交吗?")){ return false; }
}
</script>
<br/>
<?php if ($n <= 0) {?>
<form action="visa.php?action=order" method="post" name="form1" onSubmit="return checkForm()">

<input name="visa_random_number" type="hidden" value="<?php echo rand(1,65535); ?>">
<input name="customers_id" type="hidden" value="<?php echo $data['customers_id'];?>"/>
<table width="775" border="1" cellpadding="0" cellspacing="0" class="tbList">
<tr><td width="116">访美目的:</td>
<td width="647">

<select name="vis_tag_name" id="vis_tag_name" style="width:400px;">
  <option>-------------</option>
  <?php
  foreach($products AS $key=>$values)
  {
  ?>
  <option value="<?php echo $values['visa_vis_tag_name'].','.$values['visa_srv_unid'];?>"><?php echo $values['visa_purpose'].'___________￥'.$values['visa_product_price'];?></option>
  <?php
  }
  ?>
</select>
<span style="color:#FF0000">*</span></td></tr>
<tr><td>订单号:</td><td><input name="orders_id" type="text" value="<?php echo $orders_id;?>" readonly=""/>
</td>
</tr>
<tr>
	<td>客户姓名:</td>
	<td><span style="color:#FF0000">以下列出了本订单的所有行程人员,请按需选择. 选中后,可以修改</span>
	<?php
	$i_guest=0;
	if (is_array($data)) {
	foreach($data['products'] AS $key=>$value)
	{
	?>
	<br/><b><?php echo $value['products_model'].' '.$value['products_name']; ?></b>
	<br/>
		<?php  
		$guest_name_arr = false;
		$guest_name_arr = $visa ->order_guest_name_fromstring_toarray( $value['guest_name'] );
		foreach($guest_name_arr AS $key2=>$value2)
		{
			//print_r($value2);
			if(count($value2[1])>0){
		?>
			<input type="checkbox" onClick="document.getElementById('guest_name_<?php echo $i_guest;?>').disabled=!this.checked;">
			<input name="guest_name[]" type="text" value="<?php echo $value2[1]?>" id="guest_name_<?php echo $i_guest;?>" disabled="disabled"><br/>
		<?php
				$i_guest +=1;
			}
		}
		?>
	<?php
	}
	}
	?>

	</td>
</tr>
<tr><td>出发日期:</td>
	<td><input name="tdate" id="tdate" type="text" value="<?php echo date("Y-m-d",strtotime($data['tdate']));?>"
	readonly="" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"/><span style="color:#FF0000">*</span>
	</td></tr>
<tr>
	<td>预计赴美日期：</td>
	<td><input name="vis_to_date" id="vis_to_date" type="text" value="<?php echo date("Y-m-d",strtotime($data['tdate']));?>"
 readonly="" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"/><span style="color:#FF0000">*</span>
	</td>
</tr>
<tr>
	<td>希望签证日期：</td>
	<td><input name="vis_req_date" id="vis_req_date" type="text" value="" readonly=""
	onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"/><span style="color:#FF0000">*</span>
	</td>
</tr>
<tr><td></td><td><input name="submit" type="submit" value="submit" /></td></tr>
</table>
</form>
<?php
}
}

if($_GET['action'] == 'vieworderlist'){
	$updatetimestring = $visa->get_updatetimestring_list();
	if(is_array($updatetimestring))
	{
?>
<form name="form1" action="visa.php?action=vieworderlist" method="get">
<?php echo tep_draw_hidden_field('action',$_GET['action']);?>
<fieldset>
	<legend>显示更新时间列表</legend>
    更新时间点: 
	<?php 
	echo tep_draw_pull_down_menu('updatetimestring',  $updatetimestring,'','id="updatetimestring" '); ?>
	<input type="submit" value="Query">
</fieldset>

</form>
<?php
		$updatetimestring = tep_db_prepare_input($_GET['updatetimestring']);
		if (strlen($_GET['updatetimestring'])>0 )
		{
			$data_ordermain = $visa->get_visa_ordermain_list($updatetimestring);
			//print_r($data_ordermain);
			if(is_array($data_ordermain))
			{
?>
				<table class="tbList">
					<tr>
						<th title="ORD_ID">VISA订单号</th>
						<th>VISA信息</th>
						<th>走四方订单号</th><th>会员email</th><th>会员姓名</th>
						<!--<th>ORD_USR_STA_TAG</th><th>ORD_ADM_STA_TAG</th>//-->
						<th title="ORD_PAY_TAG">付款方式</th><th title="ORD_PRICE">订单价格</th><th title="ORD_PAY_MONEY">已付金额</th>
						<!--<th>ORD_INV_TITLE<br/>发票抬头</th><th>ORD_USR_NAME</th><th>ORD_USR_PHONE</th>	//-->				
						<th title="ORD_EXT1">访美目的</th><th title="ORD_EXT2">预计赴美日期</th><th title="ORD_EXT3">希望签证日期</th>
						<!--<th title="ORD_DATE">下单日期</th>//--><th title="ORD_CDATE">下单时间</th><th title="ORD_MDATE">订单修改时间</th>
					</tr>
<?
				foreach($data_ordermain AS $keys=>$values)
				{
					$visa_order_user_info=$visa->get_order_user_info_by_visa_order_id($data_ordermain[$keys]['ORD_ID'],$data_ordermain[$keys]['USR_ID']);
?>
					<tr>
						<td><a href="?action=admin_goto_view_customer_visa_order&ORD_ID=<?php echo $data_ordermain[$keys]['ORD_ID'];?>" target="_blank"><?php echo $data_ordermain[$keys]['ORD_ID'];?></a></td>
						<td>
						<?php 
						$visa_status_name = $visa->get_visa_to_embassy_status_name($data_ordermain[$keys]['ORD_ID']);
						if (strlen($visa_status_name)>0){?>
						<a href="?action=view_visa_to_embassy_info&ORD_ID=<?php echo $data_ordermain[$keys]['ORD_ID'];?>"><?php echo $visa_status_name;?></a>
						<?php }?>
						</td>
						<td>
						<?php if(!empty($visa_order_user_info['orders_id'])){ ?>
						<a href="edit_orders.php?oID=<?php echo $visa_order_user_info['orders_id'];?>&action=edit" target="_blank"><?php echo $visa_order_user_info['orders_id'];?></a>
						<?php }?>
						</td>
						<td><?php echo $visa_order_user_info['customers_email_address'];?></td>
						<td><?php echo $visa_order_user_info['customers_name'];?></td>
						<!--<td><?php echo $data_ordermain[$keys]['ORD_USR_STA_TAG'];?></td><td><?php echo $data_ordermain[$keys]['ORD_ADM_STA_TAG'];?></td>//-->
						<td><?php echo $visa->match_visa_ORD_PAY_TAG($data_ordermain[$keys]['ORD_PAY_TAG']); ?></td>
						<td><?php echo $data_ordermain[$keys]['ORD_PRICE'];?></td><td><?php echo $data_ordermain[$keys]['ORD_PAY_MONEY'];?></td>
						<!--<td><?php echo $data_ordermain[$keys]['ORD_INV_TITLE'];?></td><td><?php echo $data_ordermain[$keys]['ORD_USR_NAME'];?></td><td><?php echo $data_ordermain[$keys]['ORD_USR_PHONE'];?></td>//-->					
						<td><?php echo $data_ordermain[$keys]['ORD_EXT1'];?></td><td><?php echo $data_ordermain[$keys]['ORD_EXT2'];?></td><td><?php echo $data_ordermain[$keys]['ORD_EXT3'];?></td>
						<!--<td><?php echo $data_ordermain[$keys]['ORD_DATE'];?></td>//--><td><?php echo $data_ordermain[$keys]['ORD_CDATE'];?></td><td><?php echo $data_ordermain[$keys]['ORD_MDATE'];?></td>
					</tr>
<?php
				}
?>
				</table>
<?
			}
			else
			{
				echo 'ERROR: no data.';
			}
		}
	}
	else
	{
		echo 'ERROR: no data found.';
	}	
}
?>

<?php
if($_GET['action'] == 'search' || !isset($_GET['action'])){
	
	if($download != "1"){
?>

<form name="visa_search" id="visa_search" method="get" action="visa.php?action=search">
<input name="action" type="hidden" value="search" />
<span style="font-weight:bolder; color:#0033FF">签证订单搜索</span>
<table border="0" cellpadding="8" style="border:1px solid #CCCCCC; background-color: #EEEEEE; font-size:12px; text-align:center;">
	<tr>
		<td>走四方订单号<br/><?php echo tep_draw_input_field('orders_id','','style="ime-mode:none; width:60px;"');?></td>
		<td>签证订单号<br/><?php echo tep_draw_input_field('visa_orderid','','style="ime-mode:none; width:60px;"');?></td>
		<td>付款方式<br/>
			<?php echo tep_draw_pull_down_menu('ORD_PAY_TAG', $visa->show_ORD_PAY_TAG(),'',' onchange="this.form.submit()"',false);?>
		</td>
		<td>订单状态<br/>
			<?php echo tep_draw_pull_down_menu('ORD_ADM_STA_TAG', $visa->show_ORD_ADM_STA_TAG(),'',' onchange="this.form.submit()"',false);?>
		</td>
		<td>签证状态<br/>
			<?php echo tep_draw_pull_down_menu('VIS_STATUS', $visa->show_VIS_STATUS(),'',' onchange="this.form.submit()"',false);?>		
		</td>
		<td>付款状态<br/>
			<?php echo tep_draw_pull_down_menu('vis_pay_status', array(array("id"=>"","text"=>"----"),array("id"=>"1","text"=>"已付"),array("id"=>"0","text"=>"未付")),'',' onchange="this.form.submit()"',false);?>
		</td>
		<td>付款时间<br/>
		<?php echo tep_draw_input_field('pay_date_start','','style="ime-mode:none; width:80px;" readonly="" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" title="开始"');?>
		-<?php echo tep_draw_input_field('pay_date_end','','style="ime-mode:none; width:80px;" readonly="" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" title="结束"');?>
		</td>
		<td>客人姓名<br/><?php echo tep_draw_input_field('customers_name','','style="width:60px;"');?></td>
		<td>客人电话<br/><?php echo tep_draw_input_field('customers_telephone','','style="ime-mode:none; width:80px;"');?></td>
		<!--<td>客人邮箱:<?php echo tep_draw_input_field('customers_email_address','','style="ime-mode:none; width:80px;"');?></td>//-->
		<td><input name="" type="submit" vlaue="QUERY" /></td>
		<td><input name="" type="button" value="reset" onclick='jQuery("input[type=\"text\"]").val("");jQuery("select").val("");'></td>
		<td>
		<label style="cursor:pointer; color:#0066FF; border-bottom:1px solid #0033FF"><input name="download" type="checkbox" value="1" onClick="this.checked=true; this.form.submit(); this.checked=false;" style="display:none;">下载到本地</label>
		</td>
	</tr>
</table>
</form>
<?php 

$visa_com_not_done = $visa->visa_comunication_status_detial();


?>
<table class="tbList">
<tr>
<td width="200">路嘉未回复:<br/>
<?php
$arr_temp = $visa_com_not_done['lujia_not_replied'];
for($i=0,$n=count($arr_temp); $i<$n; $i++){
?>
	<a href="?action=communication&visa_order_id=<?php echo $arr_temp[$i]['visa_order_id'];?>" target="_blank"><?php echo $arr_temp[$i]['visa_order_id'];?>(<?php echo $arr_temp[$i]['e_count'];?>)</a>
<?php } ?>
</td>
<td width="200">路嘉未读:<br/>
<?php
$arr_temp = $visa_com_not_done['lujia_not_read'];
for($i=0,$n=count($arr_temp); $i<$n; $i++){
?>
	<a href="?action=communication&visa_order_id=<?php echo $arr_temp[$i]['visa_order_id'];?>" target="_blank"><?php echo $arr_temp[$i]['visa_order_id'];?>(<?php echo $arr_temp[$i]['e_count'];?>)</a>
<?php }?>
</td>
<td width="200">走四方未回复:<br/>
<?php
$arr_temp = $visa_com_not_done['usitrip_not_replied'];
for($i=0,$n=count($arr_temp); $i<$n; $i++){
?>
	<a href="?action=communication&visa_order_id=<?php echo $arr_temp[$i]['visa_order_id'];?>" target="_blank"><?php echo $arr_temp[$i]['visa_order_id'];?>(<?php echo $arr_temp[$i]['e_count'];?>)</a>
<?php }?>
</td>
<td width="200">走四方未读:<br/>
<?php
$arr_temp = $visa_com_not_done['usitrip_not_read'];
for($i=0,$n=count($arr_temp); $i<$n; $i++){
?>
	<a href="?action=communication&visa_order_id=<?php echo $arr_temp[$i]['visa_order_id'];?>" target="_blank"><?php echo $arr_temp[$i]['visa_order_id'];?>(<?php echo $arr_temp[$i]['e_count'];?>)</a>
<?php }?>
</td>
</tr>
</table>


<?php
	}
	
	if (is_array($_GET)){
?>

<?php if($download!="1"){?>
<div style="text-align:center;">总计: <span style="color:#FF9900; font-weight:bolder; font-size:24px; font-family:Arial, Helvetica, sans-serif;" id="price_total"></span></div>
<?php }?>
<table class="tbList" id="tbList">
  <tr role="head">
	<th width="70" title="ORD_ID" sort="true">签证订单号</th>
	<th width="70" sort="true">走四方订单号</th>
	<th width="70" sort="true">会员姓名</th>
	<th width="70" title="ORD_EXT1" sort="true">访美目的</th>
	<th width="70">下单工号</th>
	<th width="70">签证状态</th>
	<th width="70" sort="true">签证订单状态</th>	
		
	<th title="ORD_EXT2" sort="true">预计赴美日期</th><th title="ORD_EXT3" sort="true">希望签证日期</th>
	<th title="ORD_CDATE" sort="true">下单时间</th>	
	
	<th width="70" title="ORD_PAY_TAG" sort="true">付款方式</th>
	<th width="70" title="ORD_PRICE" sort="true">订单价格</th>
	<th width="70" title="ORD_PAY_MONEY" sort="true">付款状态</th>	
	<th width="70" sort="true">付款时间</th>
	<th width="200">路嘉最新留言</th>
	<!--<th title="ORD_MDATE" sort="true">订单修改时间</th>//-->
  </tr>
<?php
		$sql = '';
		$orders_id = (int)$_GET['orders_id'];
		$visa_orderid = (int)$_GET['visa_orderid'];
		$ORD_PAY_TAG = tep_db_input($_GET['ORD_PAY_TAG']);
		$ORD_ADM_STA_TAG = tep_db_input($_GET['ORD_ADM_STA_TAG']);
		$VIS_STATUS = tep_db_input($_GET['VIS_STATUS']);
		$customers_name = tep_db_input($_GET['customers_name']);
		$customers_telephone = tep_db_input($_GET['customers_telephone']);
		$customers_email_address = tep_db_input($_GET['customers_email_address']);
		//数据表查询
		$fields = 'b.ORD_ID,b.USR_ID ';
		$tables = ' visa_order_ordermain_from_lujia AS b ';
		$where = ' 1 ';
		$groupby = ' GROUP BY b.ORD_ID ';
		$orderby = '';
		
		if(!empty($orders_id) || !empty($visa_orderid)){
			
			
			if (!empty($orders_id)){
				//$sql2 = 'SELECT DISTINCT b.ORD_ID,b.USR_ID FROM visa_orders_byadmin AS a,visa_order_ordermain_from_lujia AS b WHERE a.orders_id=\''.$orders_id.'\' AND a.visa_orderid = b.ORD_ID';
				$tables.= ', visa_orders_byadmin AS a ';
				$where .= ' AND a.orders_id=\''.(int)$orders_id.'\' AND a.visa_orderid = b.ORD_ID ';
			}else{			
				//$sql2 = 'SELECT ORD_ID,USR_ID FROM visa_order_ordermain_from_lujia WHERE ORD_ID='.$visa_orderid.' LIMIT 0,1';
				$where .= ' AND ORD_ID='.(int)$visa_orderid.' ';
			}
		
		}else{
			
			$tables.= ', customers AS a ';
			$where .= ' AND b.USR_ID=a.customers_id ';
			
			$sql = 'SELECT customers_id FROM customers WHERE 1 ';
			if(!empty($customers_name)){
				//$sql .= ' AND (customers_firstname LIKE \'%'.$customers_name.'%\' OR customers_lastname LIKE \'%'.$customers_name.'%\')';
				$where .= ' AND (a.customers_firstname LIKE \'%'.$customers_name.'%\' OR a.customers_lastname LIKE \'%'.$customers_name.'%\')';
			}
			if(!empty($customers_email_address)){
				//$sql .= ' AND customers_email_address LIKE \'%'.$customers_email_address.'%\'';
				$where .= ' AND a.customers_email_address LIKE \'%'.$customers_email_address.'%\'';
			}
			if(!empty($customers_telephone)){
				//$sql .= ' AND customers_telephone LIKE \'%'.$customers_telephone.'\'%';
				$where .= ' AND a.customers_telephone LIKE \'%'.$customers_telephone.'\'%';
			}
			
			//这句SQL语句有严重问题
			//$sql2 = 'SELECT DISTINCT ORD_ID,USR_ID FROM visa_order_ordermain_from_lujia AS b,('.$sql.') AS a WHERE b.USR_ID=a.customers_id ';
			
			if(!empty($ORD_PAY_TAG)){
				if ($ORD_PAY_TAG=="lujia_all"){
					//$sql2 .= ' AND b.ORD_PAY_TAG!=\'PAY_AGENT\'';
					$where.= ' AND b.ORD_PAY_TAG!=\'PAY_AGENT\'';
				}else{
					//$sql2 .= ' AND b.ORD_PAY_TAG=\''.$ORD_PAY_TAG.'\'';
					$where.= ' AND b.ORD_PAY_TAG=\''.$ORD_PAY_TAG.'\'';
				}				
			}	
			
			if (!empty($ORD_ADM_STA_TAG)){
				//$sql2 .= ' AND b.ORD_ADM_STA_TAG=\''.$ORD_ADM_STA_TAG.'\'';
				$where .= ' AND b.ORD_ADM_STA_TAG=\''.$ORD_ADM_STA_TAG.'\'';
			}
			
			$orderby = ' ORDER BY b.visa_order_ordermain_id DESC, b.ORD_ID DESC';
			
		}
		
			
		$pay_date_start = strtotime($_GET['pay_date_start']);
		//echo '<br/>'.$pay_date_start;
		$pay_date_end = strtotime($_GET['pay_date_end']);
		$sql2 = 'SELECT '.$fields.' FROM '.$tables.' WHERE '.$where.$groupby.$orderby;
		//echo $sql2.'<hr>';
		$price_total = 0;
		$query_numrows = 0;
		if($download != "1"){
			$_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $sql2, $query_numrows);
		}
		$sql_query2 = tep_db_query($sql2);
		//echo $sql2;

		while($rows2 = tep_db_fetch_array($sql_query2)){
			$show_flag = 1;//是否显示.因为下面要用到比较复杂的条件查询...
			$data2 = $visa->get_visa_order_info_by_visa_order_id($rows2['ORD_ID']);
			$vis_pay_status = $_GET['vis_pay_status'];

			if ($vis_pay_status == '1'){
				if ($data2['ORD_PAY_MONEY'] < $data2['ORD_PRICE']) { $show_flag = 0; }				
			}
			if ($vis_pay_status == '0'){
				if ($data2['ORD_PAY_MONEY'] >= $data2['ORD_PRICE']) { $show_flag = 0; }		
			}
			
			//$visa_status_name = $visa->get_visa_to_embassy_status_name($rows2['ORD_ID']);
			
			if($show_flag == 1){
				$visa_VIS_STATUS = $visa->get_visa_to_embassy_status($rows2['ORD_ID']);
				if ( (!empty($VIS_STATUS)) && ($VIS_STATUS != $visa_VIS_STATUS) ){	$show_flag = 0;	}
			}
			
			/*付款时间段查询条件*/
			$visa_order_pay_date = $visa->get_visa_order_pay_date($rows2['ORD_ID']);
			$visa_order_pay_date_2 = strtotime($visa_order_pay_date);
			//echo '<br/>'.$rows2['ORD_ID'].' : '.$visa_order_pay_date;
			
			if( (!empty($pay_date_start)) || (!empty($pay_date_end))  )
			{
				
				if ( empty($visa_order_pay_date_2) )
				{
					$show_flag = 0;
				}
				else
				{
					if( (!empty($pay_date_start)) )
					{
						if( (!empty($pay_date_end)) )
						{
							if( $visa_order_pay_date_2 >= $pay_date_start && $visa_order_pay_date_2 <= $pay_date_end ){ $show_flag = 1; }
							else{ $show_flag = 0; }
						}
						else
						{
							if( $visa_order_pay_date_2 >= $pay_date_start ){ $show_flag = 1; }
							else{ $show_flag = 0; }
						}
					}
					else
					{
						if( (!empty($pay_date_end)) )
						{
							if( $visa_order_pay_date_2 <= $pay_date_end ){ $show_flag = 1; }
							else{ $show_flag = 0; }
						}				
					}
				}
			}
			
			if($show_flag == 1)
			{			
				$visa_order_user_info=$visa->get_order_user_info_by_visa_order_id($rows2['ORD_ID'], $rows2['USR_ID']);				
			?>
			<tr>
				<td>
				<?php if($can_edit_visa_orders === true){?>
				<a href="<?php echo tep_href_link('visa.php','action=admin_goto_view_customer_visa_order&ORD_ID='.$rows2['ORD_ID']);?>" target="_blank"><?php echo $rows2['ORD_ID'];?></a>
				<?php }else{?>
				<?= $rows2['ORD_ID'];?>
				<?php }?>
				</td>
				<td>
				<?php if(!empty($visa_order_user_info['orders_id'])){ ?>
				<a href="<?php echo tep_href_link('edit_orders.php','oID='.$visa_order_user_info['orders_id'].'&action=edit');?>" target="_blank"><?php echo $visa_order_user_info['orders_id'];?></a>
				<?php }?>
				</td>
				<td><?php echo $visa_order_user_info['customers_name'];?></td>
				<td><?php echo $data2['ORD_EXT1'];?></td>
				<td><?php 
				// by lwkai add 2012-08-20
				$sql_temp = "select a.admin_job_number from admin as a,visa_orders_byadmin as vob where a.admin_id = vob.login_id and vob.visa_orderid=" . $rows2['ORD_ID'];
				$result = tep_db_query($sql_temp);
				$num = tep_db_num_rows($result);
				if ($num > 0){
					$row = tep_db_fetch_array($result);
					echo $row['admin_job_number'];
				}
				// add end
				?></td>
				<td>
				<?php				
				if (strlen($visa_VIS_STATUS)>0){?>
				<a href="<?php echo tep_href_link('visa.php','action=view_visa_to_embassy_info&ORD_ID='.$rows2['ORD_ID']);?>" target="_blank">
				<?php echo $visa->match_visa_to_embassy_status_name($visa_VIS_STATUS);?></a>
				<?php }?>
				</td>			
				<td><?php 
				switch($data2['ORD_ADM_STA_TAG']){
					case 'ORD_ADM_CON':
						echo '<font style="color:#00f;font-weight:bold;">' . $visa->match_visa_order_status_name($data2['ORD_ADM_STA_TAG']) . '</font>';
						break;
					case 'ORD_ADM_OK':
						echo '<font style="color:#F00;font-weight:bold;">' . $visa->match_visa_order_status_name($data2['ORD_ADM_STA_TAG']) . '</font>';
						break;
					default:
						echo $visa->match_visa_order_status_name($data2['ORD_ADM_STA_TAG']);
				}
				?></td>				
				<td><?php echo $data2['ORD_EXT2'];?></td>
				<td><?php echo $data2['ORD_EXT3'];?></td>
				<td><?php echo date_format(date_create($data2['ORD_CDATE']),'Y-m-d');?></td>
				
				<td><?php
				echo $visa->match_visa_ORD_PAY_TAG($data2['ORD_PAY_TAG']);
				?>
				</td>
				<td><?php echo $data2['ORD_PAY_MONEY'];?></td>				
				<td title="已付: <?php echo $data2['ORD_PAY_MONEY'];?>"><?php if ($data2['ORD_PAY_MONEY']>= $data2['ORD_PRICE']){ echo '已付款'; }else{ echo '未付款';} ?></td>
				<td><?php echo $visa_order_pay_date;?></td>
				<td>
				<?php
				$visa_com_last_message = false;
				$visa_com_last_message = $visa->visa_com_get_lujia_last_mseeage_by_visa_order_id($rows2['ORD_ID']);
				if(is_array($visa_com_last_message)){
				?>
				<a href="?action=communication&visa_order_id=<?php echo $rows2['ORD_ID'];?>" target="_blank"><?php echo tep_db_output($visa_com_last_message['message']);?></a>
				<?php 				
				}elseif($can_edit_visa_orders === true){
				?>
				<a href="?action=communication&visa_order_id=<?php echo $rows2['ORD_ID'];?>" target="_blank">去留言</a>&gt;&gt;
				<?php }
				?>
				</td>
			</tr>				
			<?php
				$price_total += $data2['ORD_PRICE'];
			}
		}
	?>

</table>

<?php if(is_object($_split)){?>
<table border="0" cellspacing="0" cellpadding="2" >
  <tr>
	<td class="smallText" valign="top"><?php echo $_split->display_count($query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
	<td class="smallText" align="right"><?php echo $_split->display_links($query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?>&nbsp;</td>
  </tr>
</table>
<?php }?>
			
<div style="text-align:center;">总计: <span style="color:#FF9900; font-weight:bolder; font-size:24px; font-family:Arial, Helvetica, sans-serif;"><?php echo $price_total;?></span></div>
<?php if($download!="1"){?>
<script>jQuery("#price_total").html('<?php echo $price_total;?>');</script>
<?php }?>
<?php if($download!="1"){?>
<script language="javascript" type="text/javascript">
jQuery("#tbList tr:even").css("background-color","#EEEEEE"); 
</script>
<script type="text/javascript" src="includes/javascript/jquery-plugin-TableSort/TableSort.js"></script>
<script language="javascript" type="text/javascript">
	jQuery("#tbList").sorttable({
		ascImgUrl: "includes/javascript/jquery-plugin-TableSort/bullet_arrow_up.png",
		descImgUrl: "includes/javascript/jquery-plugin-TableSort/bullet_arrow_down.png",
		ascImgSize: "8px",
		descImgSize: "8px",
		onSorted: function (cell) {  }
	});

</script>
<?php }?>
	<?php
	}
?>
<?php
}?>

<?php
//==================================================签证信息=============================================================
if($_GET['action'] == 'view_visa_to_embassy_info')
{
?>
<table class="tbList" id="tbList">
  <tr>
	<th title="VIS_CDATE">创建时间</th>
	<th title="VIS_MDATE">修改时间</th>
	<th title="VIS_STATUS">签证状态</th>
	<th title="VIS_XML">注册内容</th>
	<th title="VIS_IMG">注册用照片</th>
	<th title="VIS_PRT">确认页</th>
	<th title="VIS_PRT1">注册内容截图</th>
	<th title="ROB_APP_ID">确认页编号</th>
  </tr>
<?php
	$ORD_ID = $_GET['ORD_ID'];
	$sql = 'SELECT VIS_CDATE,VIS_MDATE,VIS_STATUS, VIS_XML,VIS_IMG,VIS_PRT,VIS_PRT1,ROB_APP_ID FROM `visa_to_embassy_info` WHERE FRO_ORD_ID='.$ORD_ID;
	//echo $sql;
	$sql_query = tep_db_query($sql);
	$VIS_XML_i = 0;
	while($rows =  tep_db_fetch_array($sql_query))
	{
		$VIS_XML_i +=1;
?>
  <tr>
  	<td><?php echo $rows['VIS_CDATE'];?></td>
	<td><?php echo $rows['VIS_MDATE'];?></td>
	<td><?php echo $visa->match_visa_to_embassy_status_name($rows['VIS_STATUS']);?></td>
	<td>
	<a href="javascript:void(0)" onClick="document.getElementById('VIS_XML_<?php echo $VIS_XML_i;?>').style.display='block';" style="color:#FF6600">+显示</a>
	<div id="VIS_XML_<?php echo $VIS_XML_i;?>" style="display:none;">
	<?php
	 	//echo $rows['VIS_XML'];
		$VIS_XML = str_replace('&gt;','>',str_replace('&lt;','<',iconv('gb2312','utf-8',$rows['VIS_XML'])));
		$VIS_XML_arr0 = xml2array('',1,'tag',$VIS_XML);
		$VIS_XML_arr = $visa->iconv_array_charencoding('utf-8','gb2312',$VIS_XML_arr0);
		//print_r($VIS_XML_arr);
		

echo('<br/><b>第1节 介绍页</b><br/>');
echo '<br/>提交申请的地点:'.$VIS_XML_arr['user']['TargetSiteCd'];
echo('<br/><b>第2节 个人信息</b><br/>');
echo '<br/>中文全名:'.$VIS_XML_arr['user']['FULL_NAME_NATIVE'];
echo '<br/>姓氏:'.$VIS_XML_arr['user']['SURNAME_ZH'].$VIS_XML_arr['user']['SURNAME'];
echo '<br/>名字:'.$VIS_XML_arr['user']['GIVEN_NAME_ZH'].$VIS_XML_arr['user']['GIVEN_NAME'];
echo '<br/>您是否有曾用名:'.$VIS_XML_arr['user']['OtherNames'];
echo '<br/> -曾用姓氏:'.$VIS_XML_arr['user']['ALIAS_SURNAME_ZH'].$VIS_XML_arr['user']['ALIAS_SURNAME'];
echo '<br/> -曾用名字:'.$VIS_XML_arr['user']['ALIAS_GIVEN_NAME_ZH'].$VIS_XML_arr['user']['ALIAS_GIVEN_NAME'];
echo '<br/>您的名字有相应的电码吗:'.$VIS_XML_arr['user']['TelecodeQuestion'];
echo '<br/> -姓氏的电码:'.$VIS_XML_arr['user']['TelecodeSURNAME_ZH'].$VIS_XML_arr['user']['TelecodeSURNAME'];
echo '<br/> -名字的电码:'.$VIS_XML_arr['user']['TelecodeGIVEN_NAME_ZH'].$VIS_XML_arr['user']['TelecodeGIVEN_NAME'];
echo '<br/>性别:'.$VIS_XML_arr['user']['GENDER'];
echo '<br/>婚姻状况(M:Married (已婚),S:Single (单身),W:Widowed (丧偶),D:Divorced (离异),L:Legally Separated (合法分居)):'.$VIS_XML_arr['user']['MARITAL_STATUS'];
echo '<br/>出生日期:'.$VIS_XML_arr['user']['862550547'];
echo '<br/>出生地城市(中文):'.$VIS_XML_arr['user']['POB_CITY_ZH'];
echo '<br/>出生地城市(英文):'.$VIS_XML_arr['user']['POB_CITY'];
echo '<br/>出生地州名/省份(中文):'.$VIS_XML_arr['user']['POB_ST_PROVINCE_ZH'];
echo '<br/>出生地州名/省份(英文):'.$VIS_XML_arr['user']['POB_ST_PROVINCE'];
echo '<br/>出生地国家 :'.$VIS_XML_arr['user']['POB_CNTRY'];
echo('<br/><b>第3节 个人身份信息</b><br/>');
echo '<br/>国籍 :'.$VIS_XML_arr['user']['APP_NATL'];
echo '<br/>是否拥有一个以上国籍 :'.$VIS_XML_arr['user']['APP_OTH_NATL_IND'];
echo '<br/> -其他国籍 :'.$VIS_XML_arr['user']['OTHER_NATL'];
echo '<br/> -您是否持有其他国籍的护照:'.$VIS_XML_arr['user']['OTHER_PPT_IND'];
echo '<br/>持有其他国籍的护照的护照号码:'.$VIS_XML_arr['user']['OTHER_PPT_NUM'];
echo '<br/>身份证件号码 :'.$VIS_XML_arr['user']['APP_NATIONAL_ID'];
echo '<br/>美国社会安全号1:'.$VIS_XML_arr['user']['APP_SSN1'];
echo '<br/>美国社会安全号1:'.$VIS_XML_arr['user']['APP_SSN2'];
echo '<br/>美国社会安全号1:'.$VIS_XML_arr['user']['APP_SSN3'];
echo '<br/>美国纳税人身份号码:'.$VIS_XML_arr['user']['APP_TAX_ID'];
echo('<br/><b>第4节 家庭地址和电话信息</b><br/>');
echo '<br/>家庭街道地址（第一行） :'.$VIS_XML_arr['user']['ADDR_LN1_ZH'];
echo '<br/>家庭街道地址（第一行） :'.$VIS_XML_arr['user']['ADDR_LN1'];
echo '<br/>家庭街道地址（第二行）:'.$VIS_XML_arr['user']['ADDR_LN2_ZH'];
echo '<br/>家庭街道地址（第二行）:'.$VIS_XML_arr['user']['ADDR_LN2'];
echo '<br/>城市 :'.$VIS_XML_arr['user']['ADDR_CITY_ZH'];
echo '<br/>城市 :'.$VIS_XML_arr['user']['ADDR_CITY'];
echo '<br/>省市 :'.$VIS_XML_arr['user']['ADDR_STATE_ZH'];
echo '<br/>省市 :'.$VIS_XML_arr['user']['ADDR_STATE'];
echo '<br/>邮政编码:'.$VIS_XML_arr['user']['ADDR_POSTAL_CD'];
echo '<br/>国家 :'.$VIS_XML_arr['user']['ADDR_COUNTRY'];
echo '<br/>您的邮寄地址是否与家庭地址相同？ :'.$VIS_XML_arr['user']['MailingAddrSame'];
echo '<br/> -邮寄地址（第一行:'.$VIS_XML_arr['user']['MAILING_ADDR_LN1_ZH'].$VIS_XML_arr['user']['MAILING_ADDR_LN1'];
echo '<br/> -邮寄地址（第二行） :'.$VIS_XML_arr['user']['MAILING_ADDR_LN2_ZH'].$VIS_XML_arr['user']['MAILING_ADDR_LN2'];
echo '<br/> -邮寄城市 :'.$VIS_XML_arr['user']['MAILING_ADDR_CITY_ZH'].$VIS_XML_arr['user']['MAILING_ADDR_CITY'];
echo '<br/> -邮寄省市:'.$VIS_XML_arr['user']['MAILING_ADDR_STATE_ZH'].$VIS_XML_arr['user']['MAILING_ADDR_STATE'];
echo '<br/> -邮寄地址邮编:'.$VIS_XML_arr['user']['MAILING_ADDR_POSTAL_CD'];
echo '<br/> -邮寄国家:'.$VIS_XML_arr['user']['MailCountry'];
echo '<br/>家庭电话 :'.$VIS_XML_arr['user']['HOME_TEL'];
echo '<br/>单位电话:'.$VIS_XML_arr['user']['BUS_TEL'];
echo '<br/>手机:'.$VIS_XML_arr['user']['MOBILE_TEL'];
echo '<br/>电子邮件:'.$VIS_XML_arr['user']['EMAIL_ADDR'];
echo('<br/><b>第5节 护照信息</b><br/>');
echo '<br/>护照/旅行证件种类 :'.$VIS_XML_arr['user']['PPT_TYPE'];
echo '<br/>护照号 :'.$VIS_XML_arr['user']['PPT_NUM'];
echo '<br/>护照本编号:'.$VIS_XML_arr['user']['PPT_BOOK_NUM'];
echo '<br/>护照签发国家 :'.$VIS_XML_arr['user']['PPT_ISSUED_CNTRY'];
echo '<br/>护照签发地城市 :'.$VIS_XML_arr['user']['PPT_ISSUED_IN_CITY_ZH'].$VIS_XML_arr['user']['PPT_ISSUED_IN_CITY'];
echo '<br/>省份 :'.$VIS_XML_arr['user']['PPT_ISSUED_IN_STATE_ZH'].$VIS_XML_arr['user']['PPT_ISSUED_IN_STATE'];
echo '<br/>签发国家 :'.$VIS_XML_arr['user']['PPT_ISSUED_IN_CNTRY'];
echo '<br/>签发日期:'.$VIS_XML_arr['user']['20754705'];
echo '<br/>有效期:'.$VIS_XML_arr['user']['1661928093'];
echo '<br/>护照被偷过？ :'.$VIS_XML_arr['user']['LOST_PPT_IND'];
echo '<br/> -被偷护照号码,如果忘了可不填:'.$VIS_XML_arr['user']['LOST_PPT_NUM'];
echo '<br/> -护照签发国家/机关:'.$VIS_XML_arr['user']['LOST_PPT_NATL'];
echo '<br/> -丢失原因:'.$VIS_XML_arr['user']['LOST_PPT_EXPL'];
echo('<br/><b>第6节 旅行信息</b><br/>');
echo '<br/>签证类型:'.$VIS_XML_arr['user']['PurposeOfTrip'];
echo '<br/>签证具体说明 :'.$VIS_XML_arr['user']['OtherPurpose'];
echo '<br/>是否制定了明确的旅行计划 :'.$VIS_XML_arr['user']['SpecificTravel'];
echo '<br/> -(否)计划到达时间:'.$VIS_XML_arr['user']['-1028787741'];
echo '<br/> -(否)计划停留时间:'.$VIS_XML_arr['user']['TRAVEL_LOS'];
echo '<br/> -(否)计划在美停留时间:'.$VIS_XML_arr['user']['TRAVEL_LOS_CD'];
echo '<br/> -到达美国日期(1):'.$VIS_XML_arr['user']['785637561'];
echo '<br/> -到达航班:'.$VIS_XML_arr['user']['ArriveFlight'];
echo '<br/> -到达城市:'.$VIS_XML_arr['user']['ArriveCity_ZH'].$VIS_XML_arr['user']['ArriveCity'];
echo '<br/> -离开美国日期(1):'.$VIS_XML_arr['user']['-428343276'];
echo '<br/> -离开航班:'.$VIS_XML_arr['user']['DepartFlight'];
echo '<br/> -离开城市:'.$VIS_XML_arr['user']['DepartCity_ZH'].$VIS_XML_arr['user']['DepartCity'];
echo '<br/> -请提供您在美期间计划访问的地点名称:'.$VIS_XML_arr['user']['SPECTRAVEL_LOCATION_ZH'].$VIS_XML_arr['user']['SPECTRAVEL_LOCATION'];
echo '<br/>支付行程的组织（S,O,C） :'.$VIS_XML_arr['user']['WhoIsPaying'];
echo '<br/>在美停留的住址-街道1 :'.$VIS_XML_arr['user']['StreetAddress1_ZH'].$VIS_XML_arr['user']['StreetAddress1_ZH'];
echo '<br/>在美停留的住址-街道2 :'.$VIS_XML_arr['user']['StreetAddress2_ZH'].$VIS_XML_arr['user']['StreetAddress2'];
echo '<br/>在美停留的住址-城市 :'.$VIS_XML_arr['user']['CITY_ZH'].$VIS_XML_arr['user']['CITY'];
echo '<br/>在美停留的住址-州 :'.$VIS_XML_arr['user']['TravelState'];
echo '<br/>邮政编码:'.$VIS_XML_arr['user']['ZIPCode'];
echo('<br/><b>第7节 随行人员信息</b><br/>');
echo '<br/>是否有人与你同行:'.$VIS_XML_arr['user']['OtherPersonsTravelingWithYou'];
echo '<br/> -您此行作为一个团或组织的成员:'.$VIS_XML_arr['user']['GroupTravel'];
echo '<br/> -随行人员的姓氏:'.$VIS_XML_arr['user']['TRAV_COMP_SURNAME_ZH'].$VIS_XML_arr['user']['TRAV_COMP_SURNAME'];
echo '<br/> -随行人员的名字:'.$VIS_XML_arr['user']['TRAV_COM_GIVEN_NAME_ZH'].$VIS_XML_arr['user']['TRAV_COM_GIVEN_NAME'];
echo '<br/> -随行人员和您的关系:'.$VIS_XML_arr['user']['TRAV_COMP_Relationship'];
echo '<br/> -组成员:'.$VIS_XML_arr['user']['GroupName_ZH'].$VIS_XML_arr['user']['GroupName'];

echo('<br/><b>第8节 以往赴美旅行信息</b><br/>');
echo '<br/>曾经去过美国 :'.$VIS_XML_arr['user']['PREV_US_TRAVEL_IND'];
echo '<br/> -到达日期(1):'.$VIS_XML_arr['user']['-511388955'];
echo '<br/> -停留时间:'.$VIS_XML_arr['user']['PREV_US_VISIT_LOS'];
echo '<br/> -时间类型:'.$VIS_XML_arr['user']['PREV_US_VISIT_LOS_CD'];
echo '<br/> -曾经获取过美国驾照:'.$VIS_XML_arr['user']['PREV_US_DRIVER_LIC_IND'];
echo '<br/> -驾驶执照的号码:'.$VIS_XML_arr['user']['US_DRIVER_LICENSE'];
echo '<br/> -驾驶执照所属的州:'.$VIS_XML_arr['user']['US_DRIVER_LICENSE_STATE'];
echo '<br/>曾经获得美国签证 :'.$VIS_XML_arr['user']['PREV_VISA_IND'];
echo '<br/> -上一次获得美国签证的日期:'.$VIS_XML_arr['user']['-947244896'];
echo '<br/> -签证号码:'.$VIS_XML_arr['user']['PREV_VISA_FOIL_NUMBER'];
echo '<br/> -您此次是否申请同类签证 :'.$VIS_XML_arr['user']['PREV_VISA_SAME_TYPE_IND'];
echo '<br/> -您此次是否在签发您上次赴美签证的相同国家再次申请，并且这个国家是否为你的主要居住国家？:'.$VIS_XML_arr['user']['PREV_VISA_SAME_CNTRY_IND'];
echo '<br/> -您是否留取过十指指纹？:'.$VIS_XML_arr['user']['PREV_VISA_TEN_PRINT_IND'];
echo '<br/> -您的美国签证是否曾经遗失或者被盗:'.$VIS_XML_arr['user']['PREV_VISA_LOST_IND'];
echo '<br/> -您的美国签证是否曾经被注销或撤销过？:'.$VIS_XML_arr['user']['PREV_VISA_CANCELLED_IND'];
echo '<br/>   -原因解释:'.$VIS_XML_arr['user']['PREV_VISA_CANCELLED_EXPL_ZH'];
echo '<br/>您是否曾经被拒签、被拒绝入境美国，或者在入境时被撤回您的入境申请？ :'.$VIS_XML_arr['user']['PREV_VISA_REFUSED_IND'];
echo '<br/>曾有人在公民及移民服务局为您申请过移民吗？ :'.$VIS_XML_arr['user']['IV_PETITION_IND'];

	?>
	<div style="margin-top:30px; color:#FF0000">-------------------栏位太多,待整理----------------</div>
	</div>
	</td>
	<td><?php if(strlen($rows['VIS_IMG'])>0){?><a href="/<?php echo $rows['VIS_IMG'];?>" target="_blank">注册用照片</a><?php }?></td>
	<td><?php if(strlen($rows['VIS_PRT'])>0){?><a href="/<?php echo $rows['VIS_PRT'];?>" target="_blank">确认页</a><?php }?></td>
	<td><?php if(strlen($rows['VIS_PRT1'])>0){?><a href="/<?php echo $rows['VIS_PRT1'];?>" target="_blank">注册内容截图</a><?php }?></td>
	<td><?php echo $rows['ROB_APP_ID'];?></td>
  </tr>
<?php
	}
?>
</table>
<?php 
}
?>

<?php
//=========================================与路嘉交流==============================================================================
if($_GET['action'] == 'communication')
{
	$visa_order_id = (int)$_GET['visa_order_id'];
	if($visa_order_id>0)
	{
?>
<script language="javascript" type="text/javascript">
function fn_visa_msg_read(id)
{
	var url = "visa.php?action=communication_read&id="+id;;

	jQuery.get(url, {}, function(data){
		if (data.substring(0,5).toUpperCase()=="ERROR"){ alert(data); }	else{ alert("操作成功"); window.location.href = window.location.href; }
	}); 
}
</script>
	签证订单 (订单号 <b><?php echo $visa_order_id;?></b>) 与路嘉交流的内容:
	<div>
	<?php
	$data2 = $visa->get_visa_order_info_by_visa_order_id($visa_order_id);
	$visa_VIS_STATUS = $visa->get_visa_to_embassy_status($visa_order_id);
	?>
	<table class="tbList">
		<tr>
			<th>订单号</th>
			<th>客户姓名</th>
			<th>访美目的</th>
			<th>签证状态</th>
			<th>付款状态</th>
			<th>预计赴美日期</th>
			<th>希望签证日期</th>
		</tr>
		<tr>
			<td><?php echo $visa_order_id;?></td>
			<td><?php echo $data2['ORD_USR_NAME'];?></td>
			<td><?php echo $data2['ORD_NAME'];?></td>
			<td><?php echo $visa->match_visa_to_embassy_status_name($rows['VIS_STATUS']);?></td>
			<td><?php if ($data2['ORD_PAY_MONEY']>= $data2['ORD_PRICE']){ echo '已付款'; }else{ echo '未付款';} ?></td>
			<td><?php echo $data2['ORD_EXT2'];?></td>
			<td><?php echo $data2['ORD_EXT3'];?></td>
		</tr>
	</table>
	</div>

	<div style="width:1100px;">
	<div style="float:left; width:500px; height:20px; background-color:#FFFFFF; overflow:hidden; border:1px solid #CCCCCC; padding:5px; margin:2px; text-align:center; font-weight:bolder; font-size:16px;">走四方</div>
	<div style="float:left; width:500px; height:20px; background-color:#CCFFFF; overflow:hidden; border:1px solid #CCCCCC; padding:5px; margin:2px; text-align:center; font-weight:bolder; font-size:16px;">路嘉</div>
	<?php
//	$sql = 'SELECT GROUP_CONCAT(a1.id2) AS ids FROM( SELECT CONCAT(CAST(a.visa_order_com_id AS char),\',\',CAST(IFNULL(b.visa_order_com_id,\'\') AS char)) AS id2  FROM(   select visa_order_com_id,visa_order_com_parent_id FROM visa_order_communication WHERE visa_order_id='.$visa_order_id.' ) AS a LEFT JOIN(   select visa_order_com_id,visa_order_com_parent_id FROM visa_order_communication WHERE visa_order_id='.$visa_order_id.' ) AS b ON a.visa_order_com_id = b.visa_order_com_parent_id ) AS a1';
//	$sql_query =  tep_db_query($sql);
//	while($rows1 = tep_db_fetch_array($sql_query))
//	{
//		$data = str_replace(',,',',',$rows1);
//	}
//	$data_str = $data['ids'].']';
//	$data_str = str_replace(',]','',$data_str);
//	$data_str = str_replace(']','',$data_str);
//	$data1 = split(',',$data_str);
//	$data2 = array_unique($data1);
//	
//	//$visa_order_com = $visa->visa_order_com_get_lists($visa_order_id);
//	//tep_get_admin_customer_name
//	foreach($data2 AS $key=>$value)
//	{
//		echo $value.'<br/>';
//	}
//	exit();

	$sql = 'SELECT * FROM visa_order_communication WHERE visa_order_id='.$visa_order_id.' ORDER BY CASE visa_order_com_root_id WHEN 0 THEN visa_order_com_id ELSE visa_order_com_root_id END DESC, visa_order_com_parent_id ASC, add_date ASC';
	$sql_query = tep_db_query($sql);
	
	$admin_id_temp = -1;
	$last_from = '';
	$last_from_temp = '';
	
	while($rows = tep_db_fetch_array($sql_query))
	{
		if ($rows['admin_id']>0) { 
			$last_from_temp = 'usi'; 
		} 
		else {
			$last_from_temp = 'lujia'; 
		}
	?>
	<?php
		if ((int)$rows['visa_order_com_root_id']==0) {
			$last_from = '';
	?>
	<div style="line-height:5px; height:15px; float:left; width:95%; background-color: #CCCCCC;">
		<a href="javascript:void(0)" style="float:right;">隐藏</a>
	</div>
	<?php }?>
	<div>
		<?php if( ($rows['admin_id']==0 && (int)$rows['visa_order_com_root_id']==0) || ($last_from_temp == $last_from ) ){?>
		<div style="float:left; width:500px; height:86px; overflow:hidden; border:1px solid #FFFFFF; padding:5px; margin:2px;"></div>
		<?php }?>
		
		<div style="float:left; width:500px; height:86px; overflow:hidden; border:1px solid #CCCCCC; padding:5px; margin:2px; 
		<?php if($rows['admin_id']==0){ ?> background-color:#CCFFFF;<?php }?>
		<?php if ((int)$_GET['visa_order_com_parent_id']==(int)$rows['visa_order_com_id']){ ?> background-color:#FFCC33;<?php }?>
		">
			<div>
				<span style="color:#999999;"><?php if($rows['admin_id']>0){ echo tep_get_admin_customer_name($rows['admin_id']);}else{ echo '<b>'.tep_db_output($rows['sender_name']).'</b>';}?></span>
				<?php echo tep_db_output($rows['title']);?><br/>
				<div style=" padding:5px 3px; height:50px; overflow:auto;"><?php echo tep_db_output( $rows['message']);?>	</div>
			</div>
			<div style="">
				<span style="float:right;color:#666666;" title="添加时间">时间:<?php echo $rows['add_date'];?></span>
			<?php
			if($rows['admin_id']==0){ $to_name = '走四方'; }else{ $to_name = '路嘉'; }
			
			if ($rows['need_reply']=='1')
			{ 				
				if($rows['is_replied']=='1'){ echo '<span style="color:#0000FF">'.$to_name.'已回复</span>'; }
				else{ echo '<span style="color:#FF0000">'.$to_name.'未回复</span>'; }			
			?>
			<a href="<?php 
			echo '?action=communication&visa_order_id='.$visa_order_id;
			
			if ($rows['visa_order_com_root_id']==0) { echo '&visa_order_com_root_id='.$rows['visa_order_com_id']; }
			else { echo '&visa_order_com_root_id='.$rows['visa_order_com_root_id']; }
			
			echo '&visa_order_com_parent_id='.$rows['visa_order_com_id'];
			echo '#a_form_add';
			?>">
				<?php 
				if($can_edit_visa_orders === true){
					if( $rows['admin_id']>0) {
						//if ($rows['is_replied']=='1'){ echo '追加';} else { echo '回复'; }
						echo '追加';
					}else{
						if ($rows['is_replied']=='1'){ echo '追加';} else { echo '回复'; }
					}
				}
				?>
			</a>
			<?php 
			}
							
			if($rows['is_read']=='0'){ 
				echo '<span style="color:#FF0000">';
				if ($rows['admin_id']>0){ echo '路嘉';}else{ echo '走四方';}
				echo '未读</span>'; 
			}				
			
			if(($rows['admin_id']==0) && ($rows['is_read']=='0') && $can_edit_visa_orders === true){?>
			<input name="" type="button" value="我已读" onClick="fn_visa_msg_read(<?php echo $rows['visa_order_com_id'];?>)" style="font-size:12px; padding:0;">
			<?php
			}
			?>
			
			
			</div>
			

		</div>
	</div>
	<?php
		if ($rows['admin_id']>0) { 
			$last_from = 'usi';
		} 
		else {
			$last_from = 'lujia';
		}
	}
	?>
	</div>
	<?php if($can_edit_visa_orders === true){?>
	<div style="width:1000px; float:left; background-color:;">
	<a name="a_form_add"></a>
	<a href="?action=communication&visa_order_id=<?php echo $visa_order_id;?>">给路嘉新增留言</a>
	<?php
	$data =false;
	$is_reply = false;
	$visa_order_com_parent_id = (int)$_GET['visa_order_com_parent_id'];
	$visa_order_com_root_id = (int)$_GET['visa_order_com_root_id'];
	if ($visa_order_com_parent_id>0){
		$sql = 'SELECT title,message FROM visa_order_communication WHERE visa_order_com_id='.$visa_order_com_parent_id;
		$is_reply = true;
		$sql_query = tep_db_query($sql);
		while($rows =  tep_db_fetch_array($sql_query))
		{
			$data = $rows ;
		}
	}
	?>
	<form name="form1" id="form1" action="?action=communication_add&visa_order_id=<?php echo $visa_order_id;?>&visa_order_com_root_id=<?php echo $visa_order_com_root_id?>&visa_order_com_parent_id=<?php echo $visa_order_com_parent_id?>" method="post" style=" margin-top:10px;">
	
	<table class="tbList">
		<tr>
			<td width="100" align="right">主题:</td>
			<td width="700">
			<?php if ($is_reply == true){?>			
			<input name="title" type="text" style="width:300px;" value="<?php echo 're:'.$data['title'];?>">			
			<?php
			}else{ 
			?>
			<select name="title" style="width:300px;">
				<option value="填写表格">填写表格</option>
				<option value="表格审核">表格审核</option>
				<option value="提交成功">提交成功</option>
				<option value="预约面签">预约面签</option>
				<option value="材料准备">材料准备</option>
				<option value="陪签安排">陪签安排</option>
				<option value="签证结果">签证结果</option>
			</select>
			<?php
			}
			?>
		<span style="color:#FF0000">*</span></td>
		</tr>
		<?php if ($is_reply == true){?>
		<tr>
			<td align="right">内容:</td><td><?php echo $data['message'];?></td>
		</tr>
		<?php } ?>
		<tr>
			<td align="right"><?php if ($is_reply == true){ echo '回复的';}?>内容:</td>
			<td><textarea name="message" cols="50" rows="3"></textarea><span style="color:#FF0000">*</span></td>
		</tr>
		<tr>
			<td align="right">是否需要回复:</td>
			<td>
			<label><input name="need_reply" type="radio" value="1" <?php if ($is_reply <> true){?>checked="checked"<?php }?>>是的,需要对方回复</label>
			<label><input name="need_reply" type="radio" value="0" <?php if ($is_reply == true){?>checked="checked"<?php }?>>否,不需要回复</label>
			</td>
		</tr>
		<tr><td></td><td><input name="" type="submit" value="<?php if ($is_reply == true){ echo '回复';}else{ echo '发送';}?>"></td></tr>				
	</table>
	</form>
	</div>
	<?php }?>
<?php
	}
	else
	{
		echo 'visa order id error';
	}
	exit();
}

//给路嘉新增留言
if($_GET['action'] == 'communication_add')
{
	$visa_order_id = (int)$_GET['visa_order_id'];
	$visa_order_com_root_id = (int)$_GET['visa_order_com_root_id'];
	$visa_order_com_parent_id = (int)$_GET['visa_order_com_parent_id'];
	if($visa_order_id>0)
	{
		$data = false;
		
		$data['admin_id'] = $login_id;
		
		$data['title'] = tep_db_prepare_input($_POST['title']);
		$data['message'] = tep_db_prepare_input($_POST['message']);
		$data['need_reply'] = (int)$_POST['need_reply'];
		$data['visa_order_id'] = $visa_order_id;
		$data['add_date'] = date('Y-m-d H:i:s');	
		$data['visa_order_com_root_id'] = $visa_order_com_root_id;
		$data['visa_order_com_parent_id'] = $visa_order_com_parent_id;

		if(tep_not_null($data['title']) && tep_not_null($data['message']) )
		{
			if( $visa_order_com_parent_id >0 )
			{
				$sql = 'UPDATE visa_order_communication SET is_replied=\'1\',is_read=\'1\',read_date=\''.date('Y-m-d H:i:s').'\' WHERE visa_order_com_id='.$visa_order_com_parent_id.' AND admin_id=0  AND is_replied=\'0\'';
				tep_db_query($sql);
			}
			
			tep_db_fast_insert('visa_order_communication',$data);
?>
		<script language="javascript" type="text/javascript">
		alert("添加成功");
		window.location.href = "visa.php?action=communication&visa_order_id=<?php echo $visa_order_id; ?>";
		</script>
<?php
		}
		else
		{
			echo '<script>alert("ERROR: content empty.."); window.history.go(-1);</script>';
		}
		
	}	
}

//我已阅读留言之数据操作
if($_GET['action'] == 'communication_read')
{

	$id = (int)$_GET['id'];

	if($id>0)
	{	
		$sql = 'UPDATE visa_order_communication SET is_read=\'1\',read_date=\''.date('Y-m-d H:i:s').'\' WHERE visa_order_com_id='.$id.' AND is_read=\'0\'';
		tep_db_query($sql);
	}
	else
	{
		echo 'ERROR: parameter lost';
		exit();	
	}
}


?>


<?php
if($download != "1")
{
?>
<script language="javascript" type="text/javascript">
jQuery("#tbList tr:even").css("background-color","#EEEEEE"); 
</script>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<?php
}
?>
