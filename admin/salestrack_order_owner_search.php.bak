<?php
require('includes/application_top.php');
require('includes/classes/salestrack.php');	//载入销售跟踪的类文件
$salestrack = new salestrack;
if($salestrack->viewall==false ){
	//tep_redirect('salestrack_list.php?login_id='.$salestrack->login_id);
	tep_redirect('salestrack_list.php');
}

$admin_list = $salestrack->admin_list();/*获取后台用户列表,用于显示列表及人员名字匹配*/

$orders_id = $_GET['orders_id'];
$orders_id = (int)$orders_id;

if($orders_id == 0) exit();

$action = $_GET['action'];
if('editowner' == $action)
{
	$orders_owners = tep_db_prepare_input($_POST['orders_owners']);
	$salestrack->edit_orders_owners($orders_id,$orders_owners,$login_id);
	echo 'success';
	exit();
}

if('showownerhistory' == $action)
{
	$data = false;
	$data = $salestrack->show_edit_history($orders_id); //print_r($data); //exit();
	if(is_array($data))
	{
		$rt = '<table border="1" cellspacing="0" cellpadding="0" style="border-colllapse:collapse;"><tr><th></th><th width="160">订单归属客服</th><th width="160">添加时间</th><th>记录添加人员</th></tr>';
		foreach($data AS $key=>$value)
		{
			$rt .= '';
			if($value['is_deleted'] == 0) {	$rt .= '<tr><td></td>';}	else { $rt .= '<tr style="color:#999999"><td>已删除</td>'; }
			$rt .= '<td>'.$salestrack->get_admin_name($value['owner_login_id'],$admin_list).'</td>';
			$rt .= '<td>'.$value['add_date'].'</td>';
			if($value['add_login_id'] == 0)
			{
				$rt .= '<td>系统</td>';
			}
			else
			{
				$rt .= '<td>'.$salestrack->get_admin_name($value['add_login_id'],$admin_list).'</td></tr>';
			}
		}
		$rt .= '</table>';
		echo $rt;
	}
	exit();
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?>----销售跟踪--订单归属查询----内部使用</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>

<link rel="stylesheet" href="includes/javascript/jquery-plugin-boxy/css/common.css" type="text/css" />
<link rel="stylesheet" href="includes/javascript/jquery-plugin-boxy/css/boxy.css" type="text/css" />
<script type="text/javascript" src="includes/javascript/jquery-plugin-boxy/jquery.boxy.js"></script>

<script language="javascript" type="text/javascript">
var allDialogs = [];
function edit_owner(orders_id)
{
	var orders_owners = jQuery("#orders_owners").val();
	if(orders_owners.length<1){ alert("未指定订单归属人");return false; }
	if(!confirm("确定要修改订单归属者吗?")){ alert("您已经取消了操作"); return false;}
	var url = "salestrack_order_owner_search.php?action=editowner&orders_id="+orders_id;
	jQuery("#btn_edit_owners").attr("disabled","disabled"); 
	jQuery.post(url,{"orders_owners":orders_owners},function(data){
		if( data.length>0 ){ alert("修改成功"); window.location.href=window.location.href; }
	});
}
function show_orders_owners_edit_history(orders_id)
{
	var url = "salestrack_order_owner_search.php";
	url=url+"?action=showownerhistory&orders_id="+orders_id;	//window.open(url); return false;//alert(url); //return false;
	jQuery.get(url, {"action":"showownerhistory","orders_id":orders_id}, function (data, textStatus){
		if( data.length>0 ){
				var options = {modal:true};
				options = jQuery.extend({title: "订单归属之修改历史记录"}, options || {});
				var dialog = new Boxy(data, options);
				allDialogs.push(dialog);
		}
		else
		{
			alert("error");
		}
	});	
}
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

ul.admin_list{float:left;}
ul.admin_list li{float:left; width:180px; margin:5px 5px;}
ul.admin_list a,ul.admin_list a:visited{padding:3px;}
ul.admin_list a:hover{background-color:#0000FF; color:#FFFFFF;}
span.datetime{ font-size:12px; color:#666666; }
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table>
  <tr>
    <td width="500" valign="top">
    <form action="salestrack_order_owner_search.php" method="get">
      <fieldset>
        <legend>订单归属查询---请输入完整的订单号</legend>
                         订单号: <?php echo tep_draw_input_field('orders_id','',' style="ime-mode:none;"');?>
         <input type="submit" value="Search"/>
		 <br/><br/>
			<label style="color:#FF0000; font-size:12px;">
			<?php echo tep_draw_checkbox_field('refix','true');?>
			重新计算订单归属(不管现在有没有订单归属,都重新计算)</label>
		 
		 <!--<div style="font-size:14px; color:#999999; padding:5px 0;">&nbsp;如果该订单没有还没有归属,查询前会自动绑定然后再显示.</div>//-->
      </fieldset>
    </form>
    </td>
    <td width="500" valign="top">

    <fieldset>
    	<legend>编辑订单归属</legend>
    	订单归属人工号: <input type="text" style="ime-mode:disabled" name="orders_owners" id="orders_owners" /><input type="button" id="btn_edit_owners" value="修改" onClick="edit_owner(<?php echo $orders_id;?>)"/>
    	<br/><label style="color:#FF0000; font-size:12px;">多个人员请以英文的逗号(,)分开</label>
    	<br/><a href="javascript:void(0)" onClick="show_orders_owners_edit_history(<?php echo $orders_id;?>)">查看修改历史记录</a>
    </fieldset>

    </td>
  </tr>
</table>
<?php 

if($orders_id>0)
{
	$refix = false;
	if($_GET['refix'] == 'true') 
	{
		$refix = true;
		$salestrack->fixed_orders_owners($orders_id,$refix);/*重新计算订单归属与否*/
		sleep(2);/*为了让存储过程能够执行完*/
	}
	
	$data=$salestrack->getinfo_forOrdersOwner_check($orders_id);
	//print_r($data);
	?>   
	<table class="tbList" width="600">
	<tr>
		<th width="200">邮箱</th><th width="160">下单时间</th><th width="60">比率</th><th width="100">所属客服(姓名,工号)</th><th width="80">定单归属</th>
	</tr>
	<tr>
		<td><?php echo $data['orders_main']['customers_email_address'];?></td>
		<td><?php echo $data['orders_main']['date_purchased'];?></td>
		<td><?php echo $data['orders_main']['orders_owner_commission'];?></td>
		<td><?php echo $salestrack->get_admin_name($data['orders_main']['orders_owner_admin_id'],$admin_list);?></td>
		<td><?php echo $data['orders_main']['orders_owners'];?></td>
	</tr>
	</table>   
	<table class="tbList" width="600">
	<tr>
		<th width="40">线路</th><th width="500">线路名称</th><th width="60">团号</th>
	</tr>
	<?php 
	$n=count($data['orders_code']);
	for($i=0;$i<$n;$i++){
	?>
	<tr>
		<td><?php echo $data['orders_code'][$i]['products_id'];?></td>
		<td><?php echo $data['orders_code'][$i]['products_name'];?></td>
		<td><?php echo $data['orders_code'][$i]['products_model'];?></td>
	</tr>
	<?php 
	}
	?>
	</table>
	销售跟踪记录:(包含邮箱<?php echo $data['orders_main']['customers_email_address'];?>的销售跟踪记录) 
	<table class="tbList">
	<tr>
		<th width="80">销售</th>  
		<th width="160">添加时间</th> 
		<th width="100">客户姓名</th>
		<th width="100">邮箱</th>
		<th width="250">邮箱修改记录</th>
		<th width="250">团号修改记录</th>	
		<th width="200">咨询信息</th>    
	</tr>
	<?php 
	$n=count($data['salestrack']);
	for($i=0;$i<$n;$i++){
		$data2=$salestrack->get_st($data['salestrack'][$i]['salestrack_id']);
		//print_r($data2);
		echo $data[$i]['login_id'];
		$salestrack->get_admin_name($data[$i]['login_id'],$admin_list);
	?>
	<tr>
		<td><?php echo $salestrack->get_admin_name($data2['main'][0]['login_id'],$admin_list);?></td>  
		<td>
		<a href="salestrack_edit.php?salestrack_id=<?php echo $data['salestrack'][$i]['salestrack_id'];?>" target="_blank">
		<?php echo $data2['main'][0]['add_date'];?></a>
		</td>
		<td><?php echo $data2['main'][0]['customer_name'];?></td>
		<td><?php echo $data2['main'][0]['customer_email'];?></td>
		<td>
		<?php 
		$n2=count($data2['email_history']);
		for($i2=0;$i2<$n2;$i2++){
		?>
			<span class="datetime"><?php echo $data2['email_history'][$i2]['add_date']?></span>&nbsp;<?php echo $data2['email_history'][$i2]['email'];?><br/><br/>
		<?php 
		}
		?>
		</td>
		<td>
		<?php 
		$n3=count($data2['code_history']);
		for($i3=0;$i3<$n3;$i3++){
		?>
			<span class="datetime"><?php echo $data2['code_history'][$i3]['add_date']?></span>&nbsp;<?php echo $data2['code_history'][$i3]['code'];?><br/><br/>
		<?php 
		}
		?>
		</td>
		<td><?php echo nl2br(tep_db_output($data2['main'][0]['customer_info']));?></td>
	</tr>
	<?php 
	}
	?>
	</table>
<?php
}else{
	echo '<span style="color:#FF0000;">please input orders id.......</span><br/>';
}
?>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>