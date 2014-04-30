<?php
/*
  $Id: account_newsletters.php,v 1.1.1.1 2004/03/04 23:37:53 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_NEWSLETTERS);

  $newsletter_query = tep_db_query("select customers_newsletter,customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  $newsletter = tep_db_fetch_array($newsletter_query);

  //查询其他电子报的订阅情况
   $query = tep_db_query("select * from  newsletters_email   where newsletters_email_address = '" . $newsletter['customers_email_address'] . "'");
	$newsletter_eusitrip = '0' ;
   while($row = tep_db_fetch_array($query)){	   
		if($row['content_id'] == '1' && $row['agree_newsletter'] == '1'){
			$newsletter_eusitrip = '1';
		}
   }

  if (isset($HTTP_POST_VARS['action']) && ($HTTP_POST_VARS['action'] == 'process')) {
    $newsletter_general = isset($HTTP_POST_VARS['newsletter_general']) && is_numeric($HTTP_POST_VARS['newsletter_general'])?tep_db_prepare_input($HTTP_POST_VARS['newsletter_general']):'0';
	if($HTTP_POST_VARS['newsletter_eusitrip'] == '1') $agree_newsletter = '1' ;else $agree_newsletter = '0' ;
  	if ($newsletter_general != $newsletter['customers_newsletter']) {
      $newsletter_general = (($newsletter['customers_newsletter'] == '1') ? '0' : '1');
      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_newsletter = '" . (int)$newsletter_general . "' where customers_id = '" . (int)$customer_id . "'");
    }
	$query = tep_db_query("select *  from  newsletters_email   where newsletters_email_address = '" . $newsletter['customers_email_address'] . "' AND  content_id = '1' LIMIT 1");
	$newsletter_other_row = tep_db_fetch_array($query);
	if(empty($newsletter_other_row)){
		tep_db_query("INSERT INTO   newsletters_email   (newsletters_email_address,content_id,agree_newsletter) VALUES('" . $newsletter['customers_email_address'] . "','1','".$agree_newsletter."')");
	}else{
		tep_db_query("UPDATE   newsletters_email SET  agree_newsletter = '".$agree_newsletter."' WHERE  `newsletters_email_id` = '".$newsletter_other_row['newsletters_email_id']."' LIMIT 1 ");
	}
	
    $messageStack->add_session('account', db_to_html('恭喜你！走四方网信息订阅管理设置成功。'), 'success');
    //tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(db_to_html("订阅走四方资讯"), tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'));

  $content = CONTENT_ACCOUNT_NEWSLETTERS;
  $javascript = $content . '.js';
  
  $is_my_account = true;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
