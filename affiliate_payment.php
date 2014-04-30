<?php
/*
  $Id: affiliate_payment.php,v 1.1.1.1 2004/03/04 23:37:54 ccwjr Exp $
  OSC-Affiliate
  Contribution based on:
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2002 - 2003 osCommerce
  Released under the GNU General Public License
*/

require('includes/application_top.php');
define('NAVBAR_TITLE_2', BOX_AFFILIATE_PAYMENT);
define('NAVBAR_TITLE_2_1', BOX_AFFILIATE_SALES);

// 网站联盟开关
if (strtolower(AFFILIATE_SWITCH) === 'false') {
	echo '<div align="center">此功能暂不开放！回<a href="' . tep_href_link('index.php') . '">首页</a></div>';
	exit();
}
// 检测是否有填写收款信息 by lwkai added 2013-08-06
$sql = "select * from affiliate_affiliate where affiliate_id='" . intval($affiliate_id) . "'";
$result = tep_db_query($sql);
$rs = tep_db_fetch_array($result);
if (!tep_not_null($rs['affiliate_default_payment_method']) || (
		!tep_not_null($rs['affiliate_payment_paypal']) && 
		!tep_not_null($rs['affiliate_payment_bank_account_number']) && 
		!tep_not_null($rs['affiliate_payment_alipay']) 
)) {
	$messageStack->add_session('affiliate_details', db_to_html('进入结算中心之前必须填写收款信息！'));
	header('Location:' . tep_href_link('affiliate_details.php','action=edit','SSL'));
	exit;
}
// 检测是否有填写收款信息结束 by lwkai added 2013-08-06
if (!tep_session_is_registered('affiliate_id')) {
	$navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}
checkAffiliateVerified();


require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_PAYMENT);

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL'));

$where_cearch = " and p.is_delete = 0 "; // 只显示未删除的记录 by lwkai add 2012-12-18

$where_cearch2 = " and p.is_delete = 0 "; //用于统计与当前付款方式相反的记录结果 by lwkai add 2012-12-18

// 已付款 与 未付款 变量 ispay 已付款 by lwkai add 2012-12-18
$status = isset($_GET['status']) ? strtolower($_GET['status']) : '';
if ($status == 'ispay') {
	$where_cearch .= ' and p.affiliate_payment_status = 1 ';
	$where_cearch2 .= ' and p.affiliate_payment_status <> 1 ';
} else {
	$where_cearch .= ' and p.affiliate_payment_status <> 1 ';
	$where_cearch2 .= ' and p.affiliate_payment_status = 1 ';
}

if(tep_not_null($_GET['orders_date_start']) || tep_not_null($_GET['orders_date_end'])){
	if(tep_not_null($_GET['orders_date_start'])){
		$orders_date_start = tep_db_prepare_input(tep_db_input($_GET['orders_date_start']));
		$where_cearch.= ' and p.affiliate_payment_date >="'.date("Y-m-d H:i:s",strtotime($orders_date_start)).'" ';
		$where_cearch2 .= ' and p.affiliate_payment_date >= "' . date("Y-m-d H:i:s",strtotime($orders_date_start)).'" ';
	}
	if(tep_not_null($_GET['orders_date_end'])){
		$orders_date_end = tep_db_prepare_input(tep_db_input($_GET['orders_date_end']));
		$where_cearch.= ' and p.affiliate_payment_date <="'.date("Y-m-d 23:59:59",strtotime($orders_date_end)).'" ';
		$where_cearch2 .= ' and p.affiliate_payment_date <="'.date("Y-m-d 23:59:59",strtotime($orders_date_end)).'" ';
	}
		
	
}
	
//取得搜索范围内的佣金总数
$sql = tep_db_query("select SUM(p.affiliate_payment) as current_commission
		from " . TABLE_AFFILIATE_PAYMENT . " p, " . TABLE_AFFILIATE_PAYMENT_STATUS . " s
		where p.affiliate_payment_status = s.affiliate_payment_status_id
			and s.affiliate_language_id = '" . $languages_id . "'
			and p.affiliate_id =  '" . $affiliate_id . "' ".$where_cearch);
$row = tep_db_fetch_array($sql);
$current_commission = $row['current_commission'];

$affiliate_payment_raw = "select p.* , s.affiliate_payment_status_name from " . TABLE_AFFILIATE_PAYMENT . " p, " . TABLE_AFFILIATE_PAYMENT_STATUS . " s where p.affiliate_payment_status = s.affiliate_payment_status_id and s.affiliate_language_id = '" . $languages_id . "' and p.affiliate_id =  '" . $affiliate_id . "' ".$where_cearch." order by p.affiliate_payment_id DESC";

	$affiliate_payment_split = new splitPageResults($affiliate_payment_raw, MAX_DISPLAY_SEARCH_RESULTS);

	$affiliate_payment_values = tep_db_query("select sum(affiliate_payment) as total, count(*) as totalRows 
		from " . TABLE_AFFILIATE_PAYMENT . " p, " . TABLE_AFFILIATE_PAYMENT_STATUS . " s 
           where p.affiliate_payment_status = s.affiliate_payment_status_id and p.is_delete=0  
           and s.affiliate_language_id = '" . $languages_id . "' 
           and p.affiliate_id =  '" . $affiliate_id . "' ");
 
	$affiliate_payment = tep_db_fetch_array($affiliate_payment_values);

	// 统计非当前status状态的总记录
	$sql = "select count(*) as totalRows
		from " . TABLE_AFFILIATE_PAYMENT . " p, " . TABLE_AFFILIATE_PAYMENT_STATUS . " s
           where p.affiliate_payment_status = s.affiliate_payment_status_id 
           and s.affiliate_language_id = '" . $languages_id . "'
           and p.affiliate_id =  '" . $affiliate_id . "' " . $where_cearch2;
	
	//print_r($sql);
	$affiliate_payment_pay = tep_db_query($sql);
	
	$affiliate_payment_pay = tep_db_fetch_array($affiliate_payment_pay);
	
	
  $content = CONTENT_AFFILIATE_PAYMENT;
  
  $is_my_account = true;
  $Show_Calendar_JS = "true";

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
