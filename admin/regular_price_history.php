<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");
require 'includes/application_top.php';
$products_id = isset($_GET['products_id']) ? intval($_GET['products_id']) : 0;
if (!$products_id) {
	echo '<script type="text/javascript">alert("产品ID丢失！请联系技术人员处理！");</script>';
	exit;
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?>----常规产品价格维护历史</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<style type="text/css">
body,td{font-size:12px;}
.tbList { border:1px solid #CCCCCC; border-collapse:collapse;}
.tbList th{ background-color:#006699; color:#FFFFFF; font-weight:bolder; font-size:90%; border:1px solid #CCCCCC; padding:3px;}
.tbList td{ border:1px solid #CCCCCC; padding:3px; font-size:12px;}
.tbList td span.imp2{color:#FF0000; font-weight:bolder;}
.tbList td span.imp1{color:#FF0000; font-weight:normal;}
.tbList td span.imp0{color:#000000; font-weight:normal;}
.tbList tr.bc{ background-color:#EEEEEE}
.tbList .finish{color:#0000FF;}
.input{width:50px;}
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php 
require(DIR_WS_INCLUDES . 'header.php'); 
?>
<h1>常规产品快速更新历史</h1>
<fieldset>
	<legend>历史列表</legend>
	<table class="tbList" width="100%">
		<tr>
			<th colspan="4"></th>
			<th colspan="2">单人价格</th>
			<th colspan="2">单人配房</th>
			<th colspan="2">双人价格</th>
			<th colspan="2">三人价格</th>
			<th colspan="2">四人价格</th>
			<th colspan="2">小孩价格</th>
			<th colspan="8"></th>
		</tr>
		<tr>
			<th>操作日期</th>
			<th>供应商团号</th>
			<th>产品编号</th>
			<th>天数</th>
			<th>卖价</th>
			<th>底价</th>
			<th>卖价</th>
			<th>底价</th>
			<th>卖价</th>
			<th>底价</th>
			<th>卖价</th>
			<th>底价</th>
			<th>卖价</th>
			<th>底价</th>
			<th>卖价</th>
			<th>底价</th>
			<th>起价</th>
			<th>毛利率</th>
			<th>查看节日价格</th>
			<th>状态</th>
			<th>通知商务部</th>
			<th>备注</th>
			<th>操作人工号</th>
            <th>姓名</th>
		</tr>
		<?php
		$sql = "select p.products_id as pid,p.products_durations_type,p.products_durations,p.provider_tour_code,p.products_model,p.products_single,p.products_double,p.products_triple,p.products_quadr,prp.* from products as p left join products_regular_price as prp on prp.products_id=p.products_id where " . $where . " order by prp.last_modify desc";
		$sql = "select * from products_regular_price_history as prph left join products as p on p.products_id=prph.products_id where p.products_id='" . intval($products_id) . "' order by prph.modify_time desc";
		$keywords_query_numrows = 0;
		$pageMaxRowsNum = 40; //每页显示10条记录
		$_split = new splitPageResults($_GET['page'], $pageMaxRowsNum, $sql, $keywords_query_numrows);
		$result = tep_db_query($sql);
		while ($row = tep_db_fetch_array($result)) {
			$color = "";
			if ($row['status'] == 30) {
				$color = "#F0F000";
			}
		?>
		<tr id="tr_<?php echo $row['pid']?>">
			<td bgcolor="<?php echo $color?>"><?php echo $row['modify_time']?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['provider_tour_code']?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['products_model']?></td>
			<td bgcolor="<?php echo $color?>"><?php 
			//算天数
			switch ($row['products_durations_type ']) {
				case 0:
					echo $row['products_durations'] . '天';
					break;
				case 1:
					echo $row['products_durations'] . '小时';
					break;
				case 2:
					echo $row['products_durations'] . '分钟';
					break;
			}
			?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['single_ask']?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['single_upset']?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['single_room_ask']?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['single_room_upset']?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['double_ask']?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['double_upset']?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['triple_ask']?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['triple_upset']?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['four_ask']?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['four_upset']?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['child_ask']?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['child_upset']?></td>
			<td bgcolor="<?php echo $color?>"><?php 
			$qijia = $row['prices_from'];
			?></td>
			<td bgcolor="<?php echo $color?>"><?php echo $row['gross_profit']?></td>
			<td bgcolor="<?php echo $color?>"><a href="<?php echo tep_href_link('categories.php','pID=' . $row['pid'] .  '&action=new_product')?>" target="_blank" title="点击查看节日价格">查看节日价格</a></td>
			<td bgcolor="<?php echo $color?>"><?php
			switch($row['status']) {
				case '11':
					echo '已上传';
					break;
				case '21':
					echo '已上传';
					break;
				case '20':
					echo '<span style="color:#8F020E;">已复核</span>';
					break;
				case '31':
					echo '已上传';
					break;
				case '30':
					echo '<span style="color:#ff0000;">已开放</span>';
					break;
				default:
					echo '未知';
			}
			?></td>
			<td bgcolor="<?php echo $color?>">&nbsp;<?php 
			if ($row['send_message'] == 1) {
				echo '<span style="color:red">是</span>';
			} else {
				echo '否';
			}?></td>
			<td bgcolor="<?php echo $color?>"><?php echo nl2br($row['detail'])?></td>
			<td bgcolor="<?php echo $color?>"><?php echo tep_get_admin_customer_name($row['modify_user_id'])?></td>
            <td bgcolor="<?php echo $color?>"><?php echo tep_get_admin_customer_name($row['modify_user_id'],'name')?></td>
		</tr>
		<?php 
		}
		?>
		<tr>
			<td colspan="24" align="center"><?php echo $_split->display_links($_split->query_num_rows,$pageMaxRowsNum,6, $_GET['page'])?></td>
		</tr>
	</table>
</fieldset>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>


