<?php
/*
  $Id: product_question_answers_write.php,v 1.1.1.1 2004/03/04 23:38:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
  if (!tep_session_is_registered('customer_id')) {
    //$navigation->set_snapshot();
   // tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL')); 
  }



  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");

  if (!tep_db_num_rows($product_info_query)) {

   // tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));

  } else {

    $product_info = tep_db_fetch_array($product_info_query);

  }

 	$success = false;
	
	if(isset($HTTP_GET_VARS['cPath']) &&  $HTTP_GET_VARS['cPath'] != ''){
	$cPath =  $HTTP_GET_VARS['cPath'];
	}
	if(isset($HTTP_GET_VARS['mnu']) &&  $HTTP_GET_VARS['mnu'] != ''){
	$mnu =  $HTTP_GET_VARS['mnu'];
	}
	if(isset($HTTP_GET_VARS['products_id']) &&  $HTTP_GET_VARS['products_id'] != ''){
	$products_id =  $HTTP_GET_VARS['products_id'];
	}
	if(isset($HTTP_GET_VARS['que_id']) &&  $HTTP_GET_VARS['que_id'] != ''){
	$que_id =  $HTTP_GET_VARS['que_id'];
	}
	
	
		
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {

    $replay_name = tep_db_input($HTTP_POST_VARS['replay_name']);

	$replay_email = tep_db_input($HTTP_POST_VARS['replay_email']);
	
	$anwers = tep_db_input($HTTP_POST_VARS['anwers']);
	
	
	$replay_name = ucfirst(substr($replay_name, 0, 1)).substr($replay_name, 1, strlen($replay_name));
	
   

	
   
     // tep_db_query("insert into " . TABLE_REVIEWS . " (products_id, replay_name, replay_email, reviews_rating, date_added) values ('" . (int)$HTTP_GET_VARS['products_id'] . "', '" . tep_db_input($replay_name)  . "','" . tep_db_input($replay_email)  . "', '" . tep_db_input($rating) . "', now())");

      //$insert_id = tep_db_insert_id();


	   if($replay_name != '' && $anwers != '' ) {	
      tep_db_query("insert into " . TABLE_QUESTION_ANSWER . " (que_id,ans,date,replay_name,replay_email,languages_id) values ('" . (int)$que_id . "', '" .  tep_db_input($anwers) ."', now(),'" . tep_db_input($replay_name) . "','" . tep_db_input($replay_email) . "','" . (int)$languages_id . "')");
	
      //tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
		// send mali to customer
	// send mail to merchant  --scs Bof
	
	$findcustomerdata = tep_db_query("select * from " . TABLE_QUESTION . " where que_id = '".(int)$que_id ."' and  languages_id='" . (int)$languages_id . "'");
	if($customerdata = tep_db_fetch_array($findcustomerdata)) {
		$c_email = $customerdata["customers_email"];
		$c_name = ucfirst(substr($customerdata["customers_name"], 0, 1)).substr($customerdata["customers_name"], 1, strlen($customerdata["customers_name"]));
		$subject="您在usitrip.com提交的问题得到回复啦！";		
		$message = 	"<table width='100%' border='0' cellpadding='0' cellspacing='0' >".
      			"<tr>".
		        "<td valign='top' colspan='2'>您好，$c_name,</td>".
				"</tr>".
				"<tr>".
		       	"<td valign='top'></br>$replay_name 已经就您关於 <b>".$product_info['products_name']."</b> 所提出的问题做了回复，请您点击以下地址以便查看我们的回复：</td>".
				"</tr>".
				"<tr>".
		       	"<td valign='top'><a href=".tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath",'NONSSL',false,true,"qanda").">".tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath",'NONSSL',false,true,"qanda")."</a></td>".
				"</tr>".
				"<tr>".
		       	"<td valign='top'><br><br>谢谢<br><a href='".HTTP_SERVER."'>www.usitrip.com</a></td>".
				"</tr>".	
				"</table>";
// zhh fix and added
		$to_name = $c_name;
		$to_email_address = $c_email;
		$email_subject = $subject;
		$email_text = $message;
		
		$from_email_name = STORE_OWNER;
		$from_email_address = STORE_OWNER_EMAIL_ADDRESS;
		
		tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true');

	}
	
   // send mail to merchant  --scs Bof
   
    tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath",'NONSSL',false,true,"qanda"));
		exit;
 	
	}
		
		// send mail to cutomer
		
		
 

  }



  if ($new_price = tep_get_products_special_price($product_info['products_id'])) {

    $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';

  } else {

    $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));

  }



  if (tep_not_null($product_info['products_model'])) {

    $products_name = $product_info['products_name'] . '&nbsp;<span class="sp10 sp6">[' . $product_info['products_model'] . ']</span>';

  } else {

    $products_name = $product_info['products_name'];

  }



  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_QUESTION_ANSWER_WRITE);


  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_QUESTION_ANSWER_WRITE, tep_get_all_get_params()));

  $content = CONTENT_TOUR_QUESTION_ANSWER_WRITE;
  $javascript = $content.'.js';
  
 
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
