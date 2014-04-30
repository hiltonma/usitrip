<?php
require('includes/application_top.php');
header("Content-type: text/html; charset=gb2312");

$chanet_lists = $chanets->ouput_sales_lists();
if(tep_not_null($chanet_lists)){
	if($_GET['type']=="html"){	//html风格
		echo '
		<table cellspacing="5" border="1">
		<thead>
		<tr>
		<td>时间</td><td>SID</td><td>订单ID</td><td>产品型号</td><td>产品数量</td><td>订单总价</td><td>订单状态</td>
		</tr>
	  </thead>';
		for($i=0; $i<count($chanet_lists); $i++){
			echo '<tr><td>'.$chanet_lists[$i]['time']."</td><td>".$chanet_lists[$i]['SID']."</td><td>".$chanet_lists[$i]['order_id']."</td><td>".$chanet_lists[$i]['cate_name']."</td><td>".$chanet_lists[$i]['prod_num']."</td><td>".$chanet_lists[$i]['price']."</td><td>".$chanet_lists[$i]['status']."</td></tr>";
		}
		echo '</table>';
	}else{	//text风格
		for($i=0; $i<count($chanet_lists); $i++){
			echo $chanet_lists[$i]['time']."\t".$chanet_lists[$i]['SID']."\t".$chanet_lists[$i]['order_id']."\t".$chanet_lists[$i]['cate_name']."\t".$chanet_lists[$i]['prod_num']."\t".$chanet_lists[$i]['price']."\n";
		}
	}
}else{
	echo "no info!";
	exit;
}
?>