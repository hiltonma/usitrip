<?php
//我的结伴同游

require_once('includes/application_top.php');

define('NAVBAR_TITLE_2',db_to_html('我的结伴同游'));
require(DIR_FS_LANGUAGES . $language . '/bbs_travel_companion.php');

if (!tep_session_is_registered('customer_id')) {
	$navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}
$action = strtolower($_GET['action']);
switch($action){
	case 'my_sent':
		tep_redirect(tep_href_link('my_travel_companion.php') . '#my_sent');
		exit();
		break;
	case 'my_applied':
		tep_redirect(tep_href_link('my_travel_companion.php') . '#my_applied');
		exit;
		break;
}
//1.我的信息(5未读) start
$tab_title1 = '我收到的信息';
$my_sms_sql = tep_db_query('SELECT * FROM `site_inner_sms` WHERE owner_id ="'.$customer_id.'" Order By sis_id DESC');
$my_sms = tep_db_fetch_array($my_sms_sql);
//1.我的信息(5未读) end

//2.我发布的结伴帖部分 start
$tab_title2 = '我发起的结伴同游';
$my_sent_sql = tep_db_query('SELECT * FROM `travel_companion` WHERE customers_id="'.$customer_id.'" Order By t_companion_id DESC ');
$my_sent = tep_db_fetch_array($my_sent_sql);
//2.我发布的结伴帖部分 end

//3.我申请的结伴帖部分 start
$tab_title3 = '我申请的结伴同游';
$my_app_sql = tep_db_query('SELECT tca.*, tc.*, tc.customers_id as cus_id  FROM `travel_companion_application` tca , `travel_companion` tc WHERE tca.customers_id="'.$customer_id.'" and tc.t_companion_id = tca.t_companion_id Group By tca.tca_id Order By tca.tca_id DESC, tc.t_companion_id DESC');
$my_app = tep_db_fetch_array($my_app_sql);
//3.我申请的结伴帖部分 end


	$output_title .= db_to_html('-结伴同游-走四方网');
	$output_desc .= ','.cutword(db_to_html(tep_db_output($rows['t_companion_content'])),97,'...');
	//$output_key_words .= '-'.db_to_html('走四方网');
	
	//seo信息
	$the_title = $output_title;
	$the_desc = $output_desc;
	$the_key_words = $output_key_words;
	//seo信息 end

$other_css_base_name = "new_travel_companion_index";
$javascript = 'new_travel_companion.js.php';
$content = 'my_travel_companion';

$breadcrumb->add(db_to_html('我的走四方'),tep_href_link(FILENAME_ACCOUNT,'','SSL'));
$breadcrumb->add(db_to_html('结伴同游'), tep_href_link('new_travel_companion_index.php'));

//经过语言编码处理后的变量
$heading_h3 = db_to_html('我的结伴同游');
$tab_title1 = db_to_html($tab_title1);
$tab_title2 = db_to_html($tab_title2);
$tab_title3 = db_to_html($tab_title3);

$breadcrumb->add($heading_h3, tep_href_link('my_travel_companion.php'));
$is_my_account = true;
//$BreadOff = true;
//$is_travel_companion_bbs = true;

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
?>

<?php
require_once(DIR_FS_INCLUDES . 'application_bottom.php');
?>