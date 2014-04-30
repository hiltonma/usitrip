<?php 
require('includes/application_top.php');
require(DIR_FS_LANGUAGES . $language . '/vote_system.php');

if (!tep_session_is_registered('customer_id')) {
    /*
	$messageStack->add_session('login', NEXT_NEED_SIGN); 
	$navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	*/
}

$error_string ='';
if($_POST['ation_vote']){
	$submit_vote = submit_vote($error_string, $customer_id);
	/*	
	if($_GET['v_s_id']=='1' && !(int)$_POST['orders_id']){
		$js_code = '<script type="text/javascript">alert("'.db_to_html("此项调查您必须有适合条件的订单才能提交，谢谢你的支持！").'"); document.location="'.tep_href_link('vote_system_list.php', '').'"; </script>';
		echo $js_code;
		exit;
	}	
	*/
}

$content = 'vote_system';

require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');

?>