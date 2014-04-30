<?php
/*
  $Id: links.php,v 1.2 2004/03/12 19:28:57 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// define our link functions
  require(DIR_FS_FUNCTIONS . 'links.php');

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ABOUT_US);
  
  $travel_pay = true;	//这个变量不能少，否则在非登录的情况下将看不到支付方式
  require(DIR_FS_CLASSES . 'payment.php');
  $paymentModules = new payment;
  $selection = $paymentModules->selection();
  $_all_payments = array('USD'=>array(), 'CNY'=>array());
  $_all_payments_ids = array();
  for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
  	$_p_name = html_to_db($selection[$i]['module']);
  	$_p_name = preg_replace('/(（|\().+/','',$_p_name);
  	$_width = ($selection[$i]['id']=='cashdeposit' ? 'width:105px;' : '' );
  	$_all_payments[$selection[$i]['currency']][] = array('id'=> $selection[$i]['id'], 'name'=> $_p_name, 'width' => $_width);
  	$_all_payments_ids[] = $selection[$i]['id'];
  }
  
  
	//seo信息
	$the_title = db_to_html('订购协议-走四方网');
	$the_desc = db_to_html('　');
	$the_key_words = db_to_html('　');
	//seo信息 end

  $add_div_footpage_obj = true;
  $content = 'order_agreement';
	$breadcrumb->add(db_to_html('订购协议'), 'order_agreement.php');

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
  
?>
