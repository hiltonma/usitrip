<?php
//用户的个人中心
//分两种情况，一种是该个人中心是该用户的$customer_id，第二种情况就是其它网友来浏览别人的用户中心$customers_id。
require_once('includes/application_top.php');

//$customers_id是本文的唯一的用户id
$customers_id = isset($_GET['customers_id']) ? (int)$_GET['customers_id'] : (int)$customer_id;
if(!(int)$customers_id){
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

//检查用户
$check_customers_sql = tep_db_query('SELECT customers_id, space_public FROM `customers` WHERE customers_id ="'.$customers_id.'" AND customers_state = "1" ');
$check_customers = tep_db_fetch_array($check_customers_sql);
if(!(int)$check_customers['customers_id']){
	header('Content-Type:text/html;charset='.CHARSET);
	echo db_to_html('该用户账号不存在，或已经被冻结！详情请拨4006001610');
	exit;
}
if(!(int)$check_customers['space_public'] && $check_customers['customers_id']!=(int)$customer_id){
	header('Content-Type:text/html;charset='.CHARSET);
	echo db_to_html('此用户个人中心尚未对外开放！');
	exit;
}

//取得用户已经完成的订单产品
$orders_sql = 'SELECT o.customers_id ,op.products_departure_date, op.products_id FROM `orders` o, `orders_products` op WHERE o.customers_id ="'.$customers_id.'" AND o.orders_status = "100006" AND op.orders_id = o.orders_id Group By op.products_id ';
$orders_query = tep_db_query($orders_sql);
$orders_products = array();
while($orders_rows = tep_db_fetch_array($orders_query)){
	$orders_products[$orders_rows['products_id']] = array('products_id'=>$orders_rows['products_id'], 'products_departure_date'=>$orders_rows['products_departure_date']);
}
//echo $orders_sql;
//取得用户已经付款的结伴同游订单产品
$orders1_sql = 'SELECT otc.customers_id ,op.products_departure_date, otc.products_id FROM `orders` o, `orders_travel_companion` otc, `orders_products` op WHERE otc.customers_id ="'.
$customers_id.'" AND o.orders_status = "100006" AND otc.orders_id = o.orders_id AND op.orders_id = o.orders_id AND op.products_id = otc.products_id Group By otc.products_id ';
$orders1_query = tep_db_query($orders1_sql);
while($orders1_rows = tep_db_fetch_array($orders1_query)){
	$orders_products[$orders1_rows['products_id']] = array('products_id'=>$orders1_rows['products_id'], 'products_departure_date'=>$orders1_rows['products_departure_date']);
}



//取得用户的伴友
$customers_travel_companion_per = array();
$customers_travel_companion_orders_sql = tep_db_query('SELECT orders_id FROM `orders_travel_companion` WHERE `customers_id` ="'.$customers_id.'" ');
$customers_travel_companion_orders_ids = '';
while($customers_travel_companion_orders = tep_db_fetch_array($customers_travel_companion_orders_sql)){
	$customers_travel_companion_orders_ids .= $customers_travel_companion_orders['orders_id'].',';
}
$customers_travel_companion_orders_ids = substr($customers_travel_companion_orders_ids,0,-1);
if(tep_not_null($customers_travel_companion_orders_ids)){
	$customers_travel_companion_sql = tep_db_query('SELECT otc.customers_id, count(otc.customers_id) as total FROM `orders_travel_companion` otc, `customers` c WHERE otc.orders_id in('.$customers_travel_companion_orders_ids.') and otc.customers_id>0 and otc.customers_id = c.customers_id and c.customers_state = "1" and otc.customers_id !="'.$customers_id.'" Group By otc.customers_id Order By total DESC ');
	while($customers_travel_companion = tep_db_fetch_array($customers_travel_companion_sql)){
		$customers_travel_companion_per[] = array('customers_id' => $customers_travel_companion['customers_id'], 'travel_companion_total' =>$customers_travel_companion['total']);
	}
}

//取得用户的相册
$where_books_exc = '';
$photo_books_sql = tep_db_query('SELECT * FROM `photo_books` WHERE customers_id = "'.$customers_id.'" '.$where_books_exc.' Order By photo_sum DESC, photo_books_id DESC');
$photo_books = tep_db_fetch_array($photo_books_sql);

//用户名称
$customers_name = tep_customers_name($customers_id);
if($customers_id==$customer_id){ $customers_name = "我"; }

$is_travel_companion_bbs = true;
$other_css_base_name = "new_travel_companion_index";
$javascript = 'new_travel_companion.js.php';
$content = 'individual_space';

$breadcrumb_title = $customers_name.'的个人中心';
$breadcrumb->add(db_to_html('结伴同游'), tep_href_link('new_travel_companion_index.php'));
$breadcrumb->add(db_to_html($breadcrumb_title), tep_href_link('individual_space.php'));


require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require_once(DIR_FS_INCLUDES . 'application_bottom.php');
?>