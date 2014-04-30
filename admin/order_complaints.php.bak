<?php
require ('includes/application_top.php');
require 'includes/classes/OrderComplaints.class.php';
if (isset($_POST['ajax'])) {
	$complaints = new OrderComplaints();
	switch ($_POST['ajax']) { //ajax 上出一个类型的数据
		case 'drop_money':
			$complaints->dropOneMoney($_POST['id']);
			exit();
			break;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>" />
<title>投诉处理</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<link type="text/css"
	href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<link rel="stylesheet" type="text/css"
	href="includes/jquery-1.3.2/jquery_ui.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript"
	src="includes/jquery-1.3.2/jquery-1.9.1.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript"
	src="includes/jquery-1.3.2/jquery.nyroModal-1.6.2.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-ui.js"></script>

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

.tr1 {
	background: #DCDCDC;
}

.contentTable,.contentTable td {
	pading: 5px;
	border: 1px solid #DCDCDC;
	border-bottom-color: #666;
	border-right-color: #666;
	border-left-color: #666;
	width: 300px;
	border-spacing: 0;
}

.ui-helper-hidden-accessible {
	display: none
}
.STYLE1 {font-size: large}
.STYLE2 {font-size: medium}
</style>
</head>
<body>
<?php
require (DIR_WS_INCLUDES . 'header.php');
if ($messageStack->size > 0) {
	echo $messageStack->output();
}

$complaints = new OrderComplaints();
//处理后台修改提交开始{
if (isset($_POST['complaints_id'])) {
	$complaints->changeOneBack();
	tep_redirect(tep_href_link('order_complaints.php','action=change&&complaints_id='.$_POST['complaints_id']));
}
//处理后台修改提交结束}
//获取单个修改信息开始{
if (isset($_GET['action']) && $_GET['action'] = 'change') {
	$info = $complaints->getOne($_GET['complaints_id']);
	$agency_arr_tmp = $complaints->getAgency();//获得供应商列表
	?>
<script type="text/javascript">
function addOnePay(){//添加分类事件
	jQuery('#my_pay').append('<span><br /><select name="change_type[]"><option value="">请选择种类</option><?php echo $complaints->dreawOption($complaints->type_array,$type);?></select>：数量/金额<input type="text" name="change_money[]" />     <span onclick="dropOnePay(jQuery(this))" style="cursor:pointer;color:red">×</span></span>');
}
function dropOnePay(doc,id){//删除分类事件
	doc.parent().remove();
	if(id){
		jQuery.post('order_complaints.php',{ajax:'drop_money',id:id},function(data){});
	}
}
</script>
	<form method="post" name="change_complaints" action="">
		<input type="hidden" value="<?=$info['cps_list']['complaints_id']?>"
			name="complaints_id" />
		<table width="630" height="429" border="1" align="center">
			<tr>
				<td width="47">订单号：</td>
				<td width="387"><input type="text" name="orders_id"
					value="<?=$info['cps_list']['orders_id']?>" disabled="disabled" /></td>
			</tr>
			<tr>
				<td>相关供应商：</td>
				<td><select name="agency_id"><?php echo $complaints->dreawAgencyOption($agency_arr_tmp,$info['cps_list']['supplier_id']);?></select></td>
			</tr>
			<tr>
				<td>严重度：</td>
				<td><select name="severity"><?=$complaints->dreawOption($complaints->severity_array,$info['cps_list']['severity'])?></select></td>
			</tr>
			<tr>
				<td>投诉内容：</td>
				<td><textarea name="complaints_contents" cols="100"><?=$info['cps_list']['complaints_content']?></textarea></td>
			</tr>
			<tr>
				<td>进度：</td>
				<td><select name="step"><?=$complaints->dreawOption($complaints->step_array,$info['cps_list']['complaints_step'])?></select></td>
			</tr>
			<tr>
				<td>问题：</td>
				<td><select name="problem"><option value="">请选择问题</option><?php echo $complaints->dreawOption($complaints->problem_array,$info['cps_list']['problem']);?></select></td>
			</tr>
			<tr>
				<td>处理备注：</td>
				<td>
				<?php if($tmp_array=$complaints->getOrdersComplaintsRemark($info['cps_list']['complaints_id'])){
				$i=0;
				foreach($tmp_array as $value){
					echo ++$i,'.',$value['complaints_remark'].'由',$value['add_user'],'在',$value['add_time'],'<br />';
				}
			}?>
				<textarea name="complaints_remark" cols="100"></textarea></td>
			</tr>
			<tr>
				<td>添加时间：</td>
				<td><?=$info['cps_list']['add_time']?></td>
			</tr>
			<tr>
				<td>出行时间：</td>
				<td><span id="travel_time"><?=$info['travel_list']['time_show']?></span></td>
			</tr>
			<tr>
				<td>赔付：</td>
				<td id="my_pay">
	<?php
	if ($info['money_list'])
		foreach ($info['money_list'] as $value) {
			echo '<span><br />', $value['orders_complaints_type'], ':', $value['orders_complaints_money'], '   <span onclick="dropOnePay(jQuery(this),', $value['orders_complaints_money_id'], ')" style="cursor:pointer">×</span></span>';
		}
	?>
	<input type="button" value="增加" onclick="addOnePay()" />
				</td>
			</tr>
			<tr>
				<td><input type="button" value="返回"
					onclick="location.href='<?=$_SESSION['complaints_url']?>'" /></td>
				<td><input type="submit" value="submit" /></td>
			</tr>
	  </table>
	</form>
<?php
	exit();
	//获取单个投诉信息结束}
}
$info_list = $complaints->getList();//获得列表
$agency_arr_tmp = $complaints->getAgency();//获得供应商列表
$agency_arr = $complaints->createOneAgency($agency_arr_tmp);
$agency_number=$complaints->getAgencyComplaitsNumber();
//处理get信息
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$orders_id = isset($_GET['orders_id']) ? $_GET['orders_id'] : '';
$o_b_time = isset($_GET['o_b_time']) ? $_GET['o_b_time'] : '';
$o_e_time = isset($_GET['o_e_time']) ? $_GET['o_e_time'] : '';
$u_b_time = isset($_GET['u_b_time']) ? $_GET['u_b_time'] : '';
$u_e_time = isset($_GET['u_e_time']) ? $_GET['u_e_time'] : '';
$problem = isset($_GET['problem']) ? $_GET['problem'] : '';
$severity = isset($_GET['severity']) ? $_GET['severity'] : '';
$jobs_id = isset($_GET['jobs_id']) ? $_GET['jobs_id'] : '';
$key_world = isset($_GET['key_world']) ? $_GET['key_world'] : '';
$jobs_id = isset($_GET['jobs_id']) ? $_GET['jobs_id'] : '';
$step = isset($_GET['step']) ? $_GET['step'] : '';
$_SESSION['complaints_url'] = $_SERVER['REQUEST_URI'];
?>
<h1>(商务中心)投诉</h1>
	<br />
	<br />
	<table >
	<tr>
	<td><span class="STYLE2">被投诉的供应商：</span></td>
	<?php foreach($agency_number as $value){ ?>
	<td style=" width:100px"><span class="STYLE1"><a href="?agency_id=<?=$value['agency_id']?>" style="color:#FF0000;"><?php echo $value['agency_id'],'(  ',$value['complaints_number'],' )'?></a></span></td>
	<?php }?>
	</tr>
</table>
	<br />
	<br />
	<form action="" name="serach" method="get">
		<table width="100%" border="1">
			<tr>
				<td>订单号：</td>
				<td><input type="text" name="orders_id" value="<?=$orders_id?>" /></td>
				<td>供应商：</td>
				<td><select name="agency_id"><option value="">请选择供应商</option><?php echo $complaints->dreawAgencyOption($agency_arr_tmp,$_GET['agency_id']);?></select></td>
				<td>订购时间：</td>
				<td><input type="text" name="o_b_time" value="<?=$o_b_time?>"
					onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" /></td>
				<td>----</td>
				<td><input type="text" name="o_e_time" value="<?=$o_e_time?>"
					onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" /></td>
				<td>出团时间：</td>
				<td><input type="text" name="u_b_time" value="<?=$u_b_time?>"
					onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" /></td>
				<td>----</td>
				<td><input type="text" name="u_e_time" value="<?=$u_e_time?>"
					onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" /></td>
				<td>投诉问题：</td>
				<td><select name="problem"><option value="">请选择问题</option><?php echo $complaints->dreawOption($complaints->problem_array,$problem);?></select></td>
			</tr>
			<tr>
				<td>严重度：</td>
				<td><select name="severity"><option value="">请选择严重度</option><?php echo $complaints->dreawOption($complaints->severity_array,$severity);?></select></td>
				<td>处理工号：</td>
				<td><input type="text" name="jobs_id" value="<?=$jobs_id?>" /></td>
				<td>关键字：</td>
				<td><input type="text" name="key_world" value="<?=$key_world?>" /></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>赔付种类：</td>
				<td><select name="type"><option value="">请选择种类</option><?php echo $complaints->dreawOption($complaints->type_array,$type);?></select></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>进度：</td>
				<td><select name="step"><option value="">请选择进度</option><?php echo $complaints->dreawOption($complaints->severity_array,$step);?></select></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><input type="submit" value="搜索" /></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<br />
		<hr width=100% size=1 color=#000000 align=center noshade>
		
		
		<br />
	</form>
	<table width="100%" border="1">
		<tr>
			<th>订单号</th>
			<th>相关供应商</th>
			<th>严重度</th>
			<th>投诉问题</th>
			<th>投诉内容</th>
			<th>进度</th>
			<th>处理备注</th>
			<th>投诉添加时间</th>
			<th>投诉添加工号</th>
			<th>种类/金额</th>
			<th>操作</th>
		</tr>
  <?php foreach($info_list['info'] as $value){?>
  <tr>
			<td><a href="edit_orders.php?language=sc&oID=<?=$value['orders_id']?>&action=edit" target="_blank" ><?=$value['orders_id']?></a></td>
			<td><?=$value['supplier_id']?></td>
			<td><font color="<?=$complaints->serverity_color_array[$value['severity']]?>"><?=$value['severity']?></font></td>
			<td><?=$value['problem']?></td>
			<td><?=$value['complaints_content']?></td>
			<td><?=$value['complaints_step']?></td>
			<td><?php 
			$tmp_array=$complaints->getOrdersComplaintsRemark($value['complaints_id']);
			if($tmp_array){
			$arr_show=array_pop($tmp_array);
			$str_show='';
			if($tmp_array){
				$i=0;
				
				foreach($tmp_array as $val){
					$str_show.= ++$i.'.'.$val['complaints_remark'].'&nbsp;&nbsp;&nbsp;由'.$val['add_user'].'于'.$val['add_time']."添加\n\r";
				}
				
			}
			echo '<span title="',$str_show,'">',$arr_show['complaints_remark'].'&nbsp;&nbsp;&nbsp;由'.$arr_show['add_user'].'于'.$arr_show['add_time'].'添加</span>';
			}?></td>
			<td><?=$value['add_time']?></td>
			<td><?=$value['jobs_id']?></td>
			<td><?php
			if ($tmp_array = $complaints->getMoneyInfo($value['complaints_id'])) {
				foreach ($tmp_array as $val) {
					echo $val['orders_complaints_type'], ':', $val['orders_complaints_money'], ' </br>';
				}
			}
			?></td>
			<td><input type="button" value="编辑"
				onclick="location.href='?action=change&&complaints_id=<?=$value['complaints_id']?>'"></td>
	  </tr>
  <?php }?>
  <tr>
			<td colspan="5"><?php echo $info_list['a'];//分页的信息?></td>
			<td colspan="4"><?php echo $info_list['b'];//分页的信息?></td>
	  </tr>
	</table>

</body>
</html>
