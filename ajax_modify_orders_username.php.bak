<?php
/**
 * 用户中心订单详情提交留言保存
 * @package PHPDoc
 */
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require_once('includes/application_top.php');
header("Content-type: text/html; charset=".CHARSET);

$error = false;
//检测用户
if(!tep_session_is_registered('customer_id') || !(int)$customer_id){
	$rtn['error'] = iconv(CHARSET,'utf-8//IGNORE',db_to_html('您未登录或者登录超时，请重新登录账号！'));
	echo json_encode($rtn);
	exit;
}


$orders_id = (int)$_POST['orders_id'];
$products_id = (int)$_POST['products_id'];
$index = (int)$_POST['index'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastName'];
$gender = iconv('utf-8',CHARSET . '//IGNORE',$_POST['gender']);
if (CHARSET != 'gb2312'){
	$gender = iconv(CHARSET,'gb2312//IGNORE',$gender);
}
$num = (int)$_POST['num'];
$guestchildage = $_POST['guestchildage'];

if (!tep_not_null($firstname) || !tep_not_null($lastname)) {
	$rtn['error'] = iconv(CHARSET,'utf-8//IGNORE',db_to_html('姓名未填写完整！'));
	echo json_encode($rtn);
	exit;
}
if ($orders_id < 0 || $products_id < 0) {
	$rtn['error'] = iconv(CHARSET,'utf-8//IGNORE',db_to_html('您提交的数据非法！'));
	echo json_encode($rtn);
	exit;
}

if (tep_not_null($guestchildage) == true) {
	include_once DIR_FS_CLASSES . 'order.php'; 
	//DIR_WS_CATALOG'';
	$order = new order($orders_id);
	//根据出发日期与小孩生日进行换算,如果出团日期那天小孩子超过地接设定的岁数,则提示小孩子不能超过多少岁的提示.
	$age_diff_at_time_travel = (int)((strtotime(date("Y-m-d",strtotime($order->products[$index]['products_departure_date']))) - strtotime(trim($guestchildage)))/(86400*365)) ;
	if($age_diff_at_time_travel > $order->products[$index]['max_allow_child_age'] && $order->products[$index]['max_allow_child_age'] != ''){
		$rtn['error'] = iconv(CHARSET,'utf-8//IGNORE',db_to_html(sprintf("您所填写的儿童出生日期 %s 为无效的日期，儿童年龄应在 %s 岁以下。",$guestchildage,$order->products[$index]['max_allow_child_age'])));
		echo json_encode($rtn);
		exit;
	}else{
		//检查小孩年龄，如果其年龄小于min_watch_age规定的值就提示错误信息
		$age_sql = tep_db_query('SELECT min_watch_age FROM `products_show` WHERE products_id="' . $products_id . '" Limit 1');
		$age_row = tep_db_fetch_array($age_sql);
		if($age_diff_at_time_travel < $age_row['min_watch_age']){
			$rtn['error'] = iconv(CHARSET,'utf-8//IGNORE',db_to_html(sprintf("您所填写的儿童出生日期 %s 为无效的日期，儿童年龄应在 %s 岁以下。",$guestchildage,$age_row['min_watch_age'])));
			echo json_encode($rtn);
			exit;
		}
	}
}

$result = tep_db_query("select guest_name,guest_gender from orders_product_eticket where orders_id='" . $orders_id . "' and products_id='" . $products_id . "'");
$date = tep_db_fetch_array($result);

$guestSex = $date['guest_gender'];
$guestname = explode('<::>',$date['guest_name']);
$temp = $guestname[$num];
if (preg_match("/.+?\[([^\]]+)\].*?\|?\|?(.*)/i",$temp,$matchs)) {
	$rep_str = $matchs[1];
	$date_temp = $matchs[2];
}
if (tep_not_null($rep_str) == true) {
	$guestname[$num] = str_replace($rep_str,$firstname . ',' . $lastname,$guestname[$num]);
	if (tep_not_null($guestchildage) == true) {
		$guestname[$num] = str_replace($date_temp, date('m/d/Y',strtotime($guestchildage)), $guestname[$num]);
	}
} else {
	$guestname[$num] = $firstname . ' ' . $lastname . ' [' . $firstname . ' ' . $lastname . ']';
	if (tep_not_null($guestchildage) == true) {
		$guestname[$num] .= '||' . date('m/d/Y',strtotime($guestchildage));
	}
}

$guestSex = explode('<::>',$date['guest_gender']);
$guestSex[$num] = $gender;
$ist_stk = tep_db_fast_update("orders_product_eticket", "orders_id='" . $orders_id . "' and products_id='" . $products_id . "'", array('guest_name'=>join('<::>',$guestname),'guest_gender'=>join('<::>',$guestSex)), "guest_name,guest_gender");

$rtn['error'] = 'false';

$html = '游客' . ($num + 1) . '：' . $firstname . ',' . $lastname;
if (tep_not_null($guestchildage) == true) {
	$html .= '(' . $age_diff_at_time_travel . 'years)';
}
$html .= '<br/>';
if (tep_not_null($gender) == true) {
	$html .=  '性别：' . $gender;
}

$rtn['html'] = iconv(CHARSET,'utf-8//IGNORE',db_to_html($html));

echo json_encode($rtn);
?>