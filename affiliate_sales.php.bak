<?php
/*
  $Id: affiliate_sales.php,v 1.1.1.1 2004/03/04 23:37:54 ccwjr Exp $OSC-Affiliate
  Contribution based on:
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 - 2003 osCommerce
  Released under the GNU General Public License
*/

require('includes/application_top.php');
define('NAVBAR_TITLE_2', BOX_AFFILIATE_SALES);
define('NAVBAR_TITLE_2_1', BOX_AFFILIATE_PAYMENT);

  // 网站联盟开关
if (strtolower(AFFILIATE_SWITCH) === 'false') {
	echo '<div align="center">此功能暂不开放！回<a href="' . tep_href_link('index.php') . '">首页</a></div>';
	exit();
}
if (!tep_session_is_registered('affiliate_id')) {
	$navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}
checkAffiliateVerified();

require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_SALES);

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL'));

function get_other_rows($where, $affiliate_id) {
	// 统计非当前status状态的总记录
	$sql = "select count(*) as totalRows from " . TABLE_AFFILIATE_SALES . " afs, " . TABLE_ORDERS . " o where afs.affiliate_orders_id = o.orders_id and afs.affiliate_id =  '" . $affiliate_id . "' and afs.affiliate_isvalid = 1  " . $where;
	$affiliate_payment_pay = tep_db_query($sql);
	$affiliate_payment_pay = tep_db_fetch_array($affiliate_payment_pay);
	return $affiliate_payment_pay['totalRows'];
}

$status = isset($_GET['status']) ? strtolower($_GET['status']) : '';

$where_cearch = "";

if(tep_not_null($_GET['orders_date_start']) || tep_not_null($_GET['orders_date_end'])){
	if(tep_not_null($_GET['orders_date_start'])){
		$orders_date_start = tep_db_prepare_input(tep_db_input($_GET['orders_date_start']));
		$where_cearch.= ' and afs.affiliate_date >="'.date("Y-m-d H:i:s",strtotime($orders_date_start)).'" ';
	}
	if(tep_not_null($_GET['orders_date_end'])){
		$orders_date_end = tep_db_prepare_input(tep_db_input($_GET['orders_date_end']));
		$where_cearch.= ' and afs.affiliate_date <="'.date("Y-m-d 23:59:59",strtotime($orders_date_end)).'" ';
	}
}

$current_commission = array(); //记录一些统计数据
switch ($status) {
	case 'ticket'://已发电子参团凭证
		$current_commission['points'] = get_other_rows($where_cearch . ' and o.orders_status = \'100006\' ',$affiliate_id);
		$current_commission['other']  = get_other_rows($where_cearch . ' and o.orders_status<>\'100006\' and o.orders_status<>\'100002\' ',$affiliate_id);
		$where_cearch .= ' and o.orders_status=\'100002\' ';
		$title_text = "已发电子参团凭证";
		break;
	case 'other': //其他未出团获积分与未发电子票
		$current_commission['points'] = get_other_rows($where_cearch . ' and o.orders_status = \'100006\' ',$affiliate_id);
		$current_commission['ticket']  = get_other_rows($where_cearch . ' and o.orders_status=\'100002\' ',$affiliate_id);
		$where_cearch .= ' and o.orders_status<>\'100006\' and o.orders_status<>\'100002\' ';
		$title_text = "其它";
		break;
	case 'points':
	default:
		$current_commission['other']  = get_other_rows($where_cearch . ' and o.orders_status<>\'100006\' and o.orders_status<>\'100002\' ',$affiliate_id);
		$current_commission['ticket']  = get_other_rows($where_cearch . ' and o.orders_status=\'100002\' ',$affiliate_id);
		$title_text = "已出团";
		$where_cearch .= ' and o.orders_status = \'100006\' ';
}



//取得佣金总数
$sql = tep_db_query("select SUM(afs.affiliate_payment) as current_commission from " . TABLE_AFFILIATE_SALES . " afs
    	left join " . TABLE_ORDERS . " o on (afs.affiliate_orders_id = o.orders_id)
    	left join " . TABLE_ORDERS_STATUS . " os on (o.orders_status = os.orders_status_id and language_id = '" . $languages_id . "')
    	where afs.affiliate_id = '" . $affiliate_id . "'	and afs.affiliate_isvalid = 1 and os.orders_status_id >='".AFFILIATE_PAYMENT_ORDER_MIN_STATUS."' ".$where_cearch);
$row = tep_db_fetch_array($sql);
$current_commission['current'] = $row['current_commission'];




$affiliate_sales_raw = "
	select  afs.*, o.orders_status as orders_status_id, os.orders_status_name_1 as orders_status, o.customers_name, o.customers_id from " . TABLE_AFFILIATE_SALES . " afs 
    left join " . TABLE_ORDERS . " o on (afs.affiliate_orders_id = o.orders_id) 
    left join " . TABLE_ORDERS_STATUS . " os on (o.orders_status = os.orders_status_id and language_id = '" . $languages_id . "') 
    where afs.affiliate_id = '" . $affiliate_id . "'	and afs.affiliate_isvalid = 1 ".$where_cearch."
    order by affiliate_date DESC
";
//echo $affiliate_sales_raw;

$affiliate_sales_split = new splitPageResults($affiliate_sales_raw, MAX_DISPLAY_SEARCH_RESULTS);

  
$content = affiliate_sales; 

$is_my_account = true;
$AffiliateInfo = getAffiliateInfo($affiliate_id);
$Show_Calendar_JS = "true";

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_FS_INCLUDES . 'application_bottom.php'); 

?>