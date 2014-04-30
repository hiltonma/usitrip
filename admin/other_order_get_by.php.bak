<?php
require ('includes/application_top.php');
require '../includes/classes/OtherOrders.class.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<link type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript"
	src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery.nyroModal-1.6.2.js" ></script>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>" />
<title>常见问答</title>
</head>
<?php 
require (DIR_WS_INCLUDES . 'header.php');
if ($messageStack->size > 0) {
	echo $messageStack->output();
}
$type=isset($_GET['type'])?$_GET['type']:1;
$other_order=new OtherOrders($type);
$action=isset($_GET['action'])?$_GET['action']:'';
switch($action){
	case 'add_show' :
		print <<<EOF
		<form method="post" action="?action=add" name="addOne">
		<table>
		<tr><td>工号:</td><td><input type="text" name="jobs_id" /></td></tr>
		<tr><td>类型</td><td><select name="type"><option value="1">7:30-17:30</option><option value="2">17:30-次日7:30</option></select></td></tr>
		<tr><td><input type="button" value="返回" onclick="location.href='other_order_get_by.php'" /></td><td><input type="submit" value="提交" /></td></tr>
		</table>
		</form>
EOF;
require (DIR_WS_INCLUDES . 'application_bottom.php');
		exit();
		break;
	case 'add' :
		$other_order->addOne($_POST['jobs_id'],$_POST['type']);
		break;
	case 'drop' :
		$other_order->dropOne($_GET['id']);
}


$arr_list=$other_order->getList();
?>
<h1>获取客户自主订单的工号管理</h1>
<br /><br />
<table width="713">
<tr><td><a href="other_order_get_by.php?type=1">7:30-17:30</a></td><td><a href="other_order_get_by.php?type=2">17:30-次日7:30</a></td><td><a href="?action=add_show">增加</a></td></tr>
</table>
<table>
<tr><td width="152">ID</td>
<td width="158">工号</td>
<td width="110">拿取</td>
<td width="173">操作</td>
</tr>
<?php $i=0;foreach($arr_list as $value){$i++;?>
<tr><td><?=$i;?></td><td><?=$value['jobs_id']?></td><td><?php if($value['is_get']==1)echo '<font color="red">是</font>'?></td><td><a href=?action=drop&&id=<?=$value['orders_jobs_id'] ?>>删除</a></td></tr></tr>
<?php }?>
</table>
<?php 
require (DIR_WS_INCLUDES . 'application_bottom.php');
?>