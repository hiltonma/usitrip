<?php
/*
  $Id: product_reviews_write.php,v 1.1.1.1 2004/03/04 23:38:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    //$navigation->set_snapshot();
   // tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL')); 
  }

 //amitcommecnted for login eof

  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");

  if (!tep_db_num_rows($product_info_query)) {

   // tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));

  } else {

    $product_info = tep_db_fetch_array($product_info_query);

  }

   //amitcommecnted for login bof

  //$customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");

  //$customer = tep_db_fetch_array($customer_query);

  //amitcommecnted for login eof
 	$success = false;
	
	if(isset($HTTP_GET_VARS['products_id']) &&  $HTTP_GET_VARS['products_id'] != ''){
	$products_id =  $HTTP_GET_VARS['products_id'];
	}
	
	
		
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
  
  
  
 	 if(isset($HTTP_POST_VARS['products_id']) &&  $HTTP_POST_VARS['products_id'] != ''){
		$products_id =  $HTTP_POST_VARS['products_id'];
	 }
	 
	 if(isset($HTTP_POST_VARS['cPath']) &&  $HTTP_POST_VARS['cPath'] != ''){
		$cPath =  $HTTP_POST_VARS['cPath'];
	 }
	
	
  

   // tep_db_query("insert into " . TABLE_REVIEWS . " (products_id, customers_name, customers_email, reviews_rating, date_added) values ('" . (int)$HTTP_GET_VARS['products_id'] . "', '" . tep_db_input($customers_name)  . "','" . tep_db_input($customers_email)  . "', '" . tep_db_input($rating) . "', now())");

      //$insert_id = tep_db_insert_id();

  			$sql_data_array = array( 'products_id' => tep_db_prepare_input($products_id),
									'lead_fname' => tep_db_prepare_input($HTTP_POST_VARS['lead_fname']),
									'lead_lname' => tep_db_prepare_input($HTTP_POST_VARS['lead_lname']),
									'lead_email' => tep_db_prepare_input($HTTP_POST_VARS['lead_email']),
									'lead_dayphone'	=> tep_db_prepare_input($HTTP_POST_VARS['lead_dayphone']),
									'lead_eveningphone'	=> tep_db_prepare_input($HTTP_POST_VARS['lead_eveningphone']),
									'lead_besttimetocall' => tep_db_prepare_input($HTTP_POST_VARS['lead_besttimetocall']),
									'lead_comment' => tep_db_prepare_input($HTTP_POST_VARS['lead_comment']),									
                                    'date_added' => 'now()',
									'lead_guest_num' => tep_db_prepare_input($HTTP_POST_VARS['lead_guest_num']),
									'lead_have_visa' => tep_db_prepare_input($HTTP_POST_VARS['lead_have_visa']),
									'lead_departure_date' => tep_db_prepare_input($HTTP_POST_VARS['lead_departure_date']),
									'lead_tour_day_num' => tep_db_prepare_input($HTTP_POST_VARS['lead_tour_day_num'])
								  );


          $sql_data_array = html_to_db($sql_data_array);
		  tep_db_perform(TABLE_TOUR_LEADS_INFO, $sql_data_array);

	  
	  tep_redirect(tep_href_link(FILENAME_TOUR_LEAD_QUESTION, "products_id=$products_id&cPath=$cPath&send=true"));
	  exit;
	  
	
	//echo "question added successfully";
      //tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
	
	// send mail to merchant  --scs Bof
	
	/*
	$fromemail = 'service@samszone.com';
	$headers = "From: samszone <$fromemail>\r\n"; 
	$headers .= "MIME-Version: 1.0\r\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	$subject="Answer your customer question to make a sale";
	
	
	
	$findmerchatdata = tep_db_query("select si.stores_email_address,si.stores_name from " . TABLE_PRODUCTS_TO_STORES . " as pts, " . TABLE_STORES . " as si where pts.products_id = '".(int)$HTTP_GET_VARS['products_id'] ."' and pts.stores_id = si.stores_id");
	while ($merchant = tep_db_fetch_array($findmerchatdata)) {
		$merchant_email = $merchant["stores_email_address"];
		$storesname = $merchant["stores_name"];
		$message = 	"<table width='100%' border='0' cellpadding='0' cellspacing='0' >".
      			"<tr>".
		        "<td valign='top' colspan='2'>Hi, $storesname,</td>".
				"</tr>".
				"<tr>".
		       	"<td valign='top'></br>A customer has a question regarding your product,&nbsp;<b>".$product_info['products_name']."</b></td>".
				"</tr>".
				"<tr>".
		        "<td valign='top' >The question is: \"".$questions."\"</td>".
				"</tr>".
				"<tr>".
		       	"<td valign='top'>Please go to <a href=".tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath",'NONSSL',false,true,"qanda").">".tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath",'NONSSL',false,true,"qanda")."</a> to answer your customers question to make a sale.</td>".
				"</tr>".
				"<tr>".
		       	"<td valign='top'></br></br>Thanks</br><a href='http://www.samszone.com'>www.samszone.com</a></td>".
				"</tr>".	
				"</table>";
		mail($merchant_email,$subject,$message,$headers);
	}
	*/ 
   // send mail to merchant  --scs Bof
 	if(isset($_POST['redirectto']) && $_POST['redirectto'] == "question"){
	tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath&success=true",'NONSSL',true,true,"qanda"));
	exit;
	}
	
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




  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_TOUR_LEAD_QUESTION);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_TOUR_LEAD_QUESTION, tep_get_all_get_params()));
$validation_include_js = 'true';
  $content = CONTENT_TOUR_LEAD_QUESTION;
  $javascript = $content.'.js';
  
 
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
