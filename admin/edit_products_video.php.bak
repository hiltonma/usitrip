<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");
require ('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('edit_products_video');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
require 'includes/classes/ProductList.class.php';
require 'includes/classes/ProductVideo.class.php';
require 'includes/classes/GetAgency.class.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>" />
<title>产品视频</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<link type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<link rel="stylesheet" type="text/css" href="includes/jquery-1.3.2/jquery_ui.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery.nyroModal-1.6.2.js" ></script>

<script type="text/javascript" src="includes/jquery-1.3.2/jquery-ui.js" ></script>
<script type="text/javascript">
function checkBoxCheck(doc){
	//if(checked ==true)
}
</script>
<style type="text/css">
#connter {
	width: 960px;
	margin: 0 auto;
}

table {
	border-collapse: collapse;
	border-spacing: 0;
}

table th,#TableList td {
	border: 1px solid #DCDCDC;
	background: #eee;
	line-height: 25px;
}

#TableList td {
	background: #fff;
	text-align: center;
}
.tr1{
	background: #DCDCDC ;
}
tr{ height:30px}
.contentTable,.contentTable td{ 
pading:5px; border:1px solid #DCDCDC; border-bottom-color:#666; border-right-color:#666;border-left-color:#666; width:300px; border-spacing:0;
}
.ui-helper-hidden-accessible{display:none}
</style>
</head>
<body>
<?php
$video=new ProductVideo;
$agency=new getAgency();
if($_POST){
	$video->changeOne($_POST['video_url'], $_POST['chkbox']);
}
$agency_list=$agency->get();
$data_list=$video->getList($_GET);
$agency_arr=$agency->createOneAgency($agency_list);
//print_r($data_list);
?>

<?php
require (DIR_WS_INCLUDES . 'header.php');
if ($messageStack->size > 0) {
	echo $messageStack->output();
}
?>
<h1>(产品中心)产品视频管理</h1>
<br /><br />
<fieldset>
<legend>搜索区域</legend>
<form action="" method="get">
	地接商编号：
	<select name="agency_id">
	<option value="">请选择供应商</option>
	<?php echo $agency->dreawAgencyOption($agency_list,$_GET['agency_id'])?>
	</select>
	产品ID：
	<input type="text" value="<?php echo tep_db_input($_GET['product_id'])?>" name="product_id" />
	视频地址关键字：
	<input type="text" value="<?php echo tep_db_input($_GET['pri_key'])?>" name="pri_key" />
	<input type="submit" value="搜索" />
	<input type="hidden" name="action" value="search" />
</form>
</fieldset>
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('edit_products_video');
$list = $listrs->showRemark();
?>
<br/>
	<br />
	<br />
	<form method="post" name="change" action="">
	视频地址：<input type="text" name="video_url" />&nbsp;&nbsp;<input type="submit" value="确定修改" />
	<table width="100%" border="1">
		<tr>
			<th><input type="checkbox" onClick="if(this.checked ==true){jQuery('input[name=\'chkbox[]\']').attr('checked',true);}else{jQuery('input[name=\'chkbox[]\']').attr('checked',false);}"/>产品ID</th>
			<th>产品名称</th>
			<th>供应商</th>
			<th>视频地址</th>
			<th>操作</th>
		</tr>
		
  <?php $i=0;foreach($data_list['info'] as $value){ $i++;?>
  <tr <?php if ($i%2==0) echo 'class="tr1"';?>>
			<td><input type="checkbox" name="chkbox[]" value="<?=$value['products_id']?>" /><?=$value['products_id']?></td>
			<td><?=$value['products_name']?></td>
			<td><?=$agency_arr[$value['agency_id']]?></td>
			<td><?=$value['products_video']?> </td>
			<td></td>
  </tr>
  <?php }?>


	</table>
	   </form>
	<table width="100%" border="1">
<td colspan="2" align="left"><?php echo $data_list['b']?></td><td colspan="5" align="right"><?php echo $data_list['a']?></td>
</table>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>