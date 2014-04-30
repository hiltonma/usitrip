<?php

/*

  $Id: orders.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $



  osCommerce, Open Source E-Commerce Solutions

  http://www.oscommerce.com



  Copyright (c) 2003 osCommerce



  Released under the GNU General Public License

*/



  require('includes/application_top.php');



  require(DIR_WS_CLASSES . 'currencies.php');

  $currencies = new currencies();



  $orders_statuses = array();

  $orders_status_array = array();

  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name, orders_status_name_1 from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "' order by orders_status_name");

  while ($orders_status = tep_db_fetch_array($orders_status_query)) {

    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],

                               'text' => $orders_status['orders_status_name']);

    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name_1'];

  }



  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');



  if (tep_not_null($action)) {

    switch ($action) {

//begin PayPal_Shopping_Cart_IPN  V2.8 DMG

        case 'accept_order':

            include(DIR_FS_CATALOG_MODULES.'payment/paypal/admin/AcceptOrder.inc.php');

            break;

//end PayPal_Shopping_Cart_IPN

      case 'update_order':

        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);

        $status = tep_db_prepare_input($HTTP_POST_VARS['status']);

        $comments = tep_db_prepare_input($HTTP_POST_VARS['comments']);



        $order_updated = false;

        $check_status_query = tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");

        $check_status = tep_db_fetch_array($check_status_query);

// BOF: WebMakers.com Added: Downloads Controller

// always update date and time on order_status

// original        if ( ($check_status['orders_status'] != $status) || tep_not_null($comments)) {

                   if ( ($check_status['orders_status'] != $status) || $comments != '' || ($status ==DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE) ) {

          tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where orders_id = '" . (int)$oID . "'");

        $check_status_query2 = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");

        $check_status2 = tep_db_fetch_array($check_status_query2);

      if ( $check_status2['orders_status']==DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE ) {

        tep_db_query("update " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " set download_maxdays = '" . tep_get_configuration_key_value('DOWNLOAD_MAX_DAYS') . "', download_count = '" . tep_get_configuration_key_value('DOWNLOAD_MAX_COUNT') . "' where orders_id = '" . (int)$oID . "'");

      }

// EOF: WebMakers.com Added: Downloads Controller



          $customer_notified = '0';

          if (isset($HTTP_POST_VARS['notify']) && ($HTTP_POST_VARS['notify'] == 'on')) {

            $notify_comments = '';

// BOF: WebMakers.com Added: Downloads Controller - Only tell of comments if there are comments

            if (isset($HTTP_POST_VARS['notify_comments']) && ($HTTP_POST_VARS['notify_comments'] == 'on')) {

              $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";

            }

// EOF: WebMakers.com Added: Downloads Controller

            $email = "尊敬的 ".$check_status['customers_name'] . "\n".  EMAIL_SEPARATOR . "\n"  . STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);



           //amit added to change email subject start

					

			if($HTTP_POST_VARS['email_subject'] != ''){

			$email_sent_subject =$HTTP_POST_VARS['email_subject'];

			}else{

			//$email_sent_subject = EMAIL_TEXT_SUBJECT;
			//$email_sent_subject = 'Order '.$oID.' Update';
			$email_sent_subject = 'Reservation Update - Reservation # '.$oID;

			}



		//code extra information on comformation tour by bhavik

			if($status == '100000')

			include("orders_confirm_email_info.php");



			 $email .= "\n\n" .str_replace('<a></a>', get_login_publicity_name() ,CONFORMATION_EMAIL_FOOTER);

            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], $email_sent_subject, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

		//amit added to change email subject end

            $customer_notified = '1';

          }



          tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "')");



          $order_updated = true;

        }



        if ($order_updated == true) {

         $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');

        } else {

          $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');

        }



        tep_redirect(tep_href_link(FILENAME_CUSTOMERS_VIEW_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));

        break;

		

	  case 'update_eticket':

	  

	  $oID = addslashes($HTTP_GET_VARS['oID']);

	  $tour_provider = addslashes($HTTP_POST_VARS['tourprovider']);

	  $tour_arrangement = addslashes($HTTP_POST_VARS['tour_arrangement']);

	  $depature_full_address = addslashes($HTTP_POST_VARS['depature_full_address']);

	  $emergency_contact_person = addslashes($HTTP_POST_VARS['emergency_contact_person']);

	  $emergency_contact_no = addslashes($HTTP_POST_VARS['emergency_contact_no']);

	  $special_note = addslashes($HTTP_POST_VARS['special_note']);

	 //amit added to update order products table start
	  $depature_full_address = ltrim($depature_full_address);
	   $depature_full_address = str_replace('  ',' ',$depature_full_address);
	   $depature_full_address = str_replace('&nbsp;',' ',$depature_full_address);

	  $fulladdress_array = explode(' ',$depature_full_address);
	  $products_departure_date = $fulladdress_array[0];
	
	  $departure_add_location = '';
		  if(!empty($fulladdress_array)){
		  foreach($fulladdress_array as $key => $val){	
			if($key > 0) $departure_add_location .= $val.' ';
		  }
		 }
	 	  
	  if(eregi('am ',$departure_add_location)){
	    $fulladdress_array_time = explode('am ',$departure_add_location);
	  	$products_departure_time = $fulladdress_array_time[0].'am ';
		$departure_add_location = $fulladdress_array_time[1];
	  }else if(eregi('pm ',$departure_add_location)){
	    $fulladdress_array_time = explode('pm ',$departure_add_location);
	 	$products_departure_time = $fulladdress_array_time[0].'pm ';
		$departure_add_location = $fulladdress_array_time[1];
	  } 
	 
	  $products_departure_location = $departure_add_location;
	  $sql_data_array_locations = array('products_departure_date' => tep_db_prepare_input($products_departure_date),

                                  		'products_departure_time' => tep_db_prepare_input($products_departure_time),
								  		
								  		'products_departure_location' => tep_db_prepare_input($products_departure_location)
								  		);
								  
			tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array_locations, 'update', "orders_id = '" . (int)$oID  . "' and products_id = '".$_GET['products_id']."' ");					  
								  
	  //amit added to update order products table end


	  $guest_name = '';

	  foreach ($_POST as $key=>$val)

	  {

	  	//echo "<br>$key=>$val";

		if(strstr($key,'guest'))

		{

			$guest_name .= $val."<::>";

		}

		if(strstr($key,'bodyweight'))

		{

			$body_weight .= $val."<::>";

		}

		

	  }

	  

	  $the_extra_query= tep_db_query("select * from ".TABLE_ORDERS_PRODUCTS_ETICKET." where orders_id = '" . (int)$oID . "'");

	  $the_extra= tep_db_fetch_array($the_extra_query);

	  $orders_id= $the_extra['orders_id'];
	  if(!tep_not_null(str_replace('<::>','',$guest_name))){ $guest_name="无客人姓名customers_view_orders.php [".(int)$login_id."]<::>";}

		if($orders_id == '')

		{

		$products_id = $_GET['products_id'];

		  tep_db_query("INSERT INTO `".TABLE_ORDERS_PRODUCTS_ETICKET."` ( `orders_id` , `products_id` , `guest_name` , `tour_provider` , `tour_arrangement`, `confirmation_date`,`special_note`,`emergency_contact_person`,`emergency_contact_no`,`depature_full_address`,`guest_body_weight` ) 

			VALUES ('$oID', '$products_id', '$guest_name', '$tour_provider', '$tour_arrangement', 'now()', '$special_note', '$emergency_contact_person', '$emergency_contact_no','$depature_full_address', '$body_weight'

			);");

		}else

		{

			tep_db_query("update ".TABLE_ORDERS_PRODUCTS_ETICKET." set guest_name='".$guest_name."' , tour_provider='".$tour_provider."' , tour_arrangement='".$tour_arrangement."', special_note='".$special_note."', emergency_contact_person='".$emergency_contact_person."', emergency_contact_no='".$emergency_contact_no."', depature_full_address='".$depature_full_address."', guest_body_weight='".$body_weight."'  where orders_id = ".$orders_id." and products_id = ".$_GET['products_id']." ");

		}



		$airline_name=addslashes($HTTP_POST_VARS['airline_name']);

		$flight_no=addslashes($HTTP_POST_VARS['flight_no']);

		$airline_name_departure=addslashes($HTTP_POST_VARS['airline_name_departure']);

		$flight_no_departure=addslashes($HTTP_POST_VARS['flight_no_departure']);

		$airport_name=addslashes($HTTP_POST_VARS['airport_name']);

		$airport_name_departure=addslashes($HTTP_POST_VARS['airport_name_departure']);		

		$arrival_date=addslashes($HTTP_POST_VARS['arrival_date']);

		$arrival_time=addslashes($HTTP_POST_VARS['arrival_time']);

		$departure_date=addslashes($HTTP_POST_VARS['departure_date']);

		$departure_time=addslashes($HTTP_POST_VARS['departure_time']);

		

	  $the_flight_query= tep_db_query("select * from  ".TABLE_ORDERS_PRODUCTS_FLIGHT." where orders_id = '" . (int)$oID . "'");

	  $the_flight= tep_db_fetch_array($the_flight_query);

	  $orders_id= $the_flight['orders_id'];

		if($orders_id == '')

		{

			$products_id = $_GET['products_id'];

		  tep_db_query("INSERT INTO `".TABLE_ORDERS_PRODUCTS_FLIGHT."` ( `orders_id` , `products_id` , `airline_name` , `flight_no` , `airline_name_departure`, `flight_no_departure`, `airport_name`, `airport_name_departure`, `arrival_date`,`arrival_time`,`departure_date`,`departure_time` ) 

			VALUES ('$oID', '$products_id', '$airline_name' , '$flight_no' , '$airline_name_departure', '$flight_no_departure', '$airport_name', '$airport_name_departure','$arrival_date','$arrival_time','$departure_date','$departure_time'

			);");

		}else

		{

			tep_db_query("update  ".TABLE_ORDERS_PRODUCTS_FLIGHT." set airline_name='$airline_name' , flight_no='$flight_no' , airline_name_departure='$airline_name_departure', flight_no_departure='$flight_no_departure', airport_name='$airport_name', airport_name_departure='$airport_name_departure', arrival_date='$arrival_date',arrival_time='$arrival_time',departure_date='$departure_date',departure_time='$departure_time' where orders_id = ".$orders_id." and products_id = ".$_GET['products_id']." ");

		}

	  

	  

		$customers_cellphone=addslashes($HTTP_POST_VARS['customers_cellphone']);

		$customers_id=addslashes($HTTP_POST_VARS['customers_id']);

	  

	  tep_db_query("update  ".TABLE_CUSTOMERS." set customers_cellphone='$customers_cellphone' where customers_id = ".$customers_id."  ");

	  

		tep_redirect(tep_href_link(FILENAME_CUSTOMERS_VIEW_ORDERS, tep_get_all_get_params(array('action','oID','products_id')).'action=eticket&products_id='.$products_id.'&oID='.$orders_id));

	   break;

	   

	   

      case 'deleteconfirm':

      

        $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);


        $restock1 = $HTTP_POST_VARS['restock'];

         if ($restock1== 'on'){

                $restock11 = 'true' ;

                }

        tep_remove_order($oID, $HTTP_POST_VARS['restock']);



        tep_redirect(tep_href_link(FILENAME_CUSTOMERS_VIEW_ORDERS, tep_get_all_get_params(array('oID', 'action'))));

        break;

    }

  }



  if (($action == 'edit') && isset($HTTP_GET_VARS['oID'])) {

    $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);



    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");

    $order_exists = true;

    if (!tep_db_num_rows($orders_query)) {

      $order_exists = false;

      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');

    }

  }

  

  if (($action == 'eticket') && isset($HTTP_GET_VARS['oID'])) {

    $oID = tep_db_prepare_input($HTTP_GET_VARS['oID']);



    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");

    $order_exists = true;

    if (!tep_db_num_rows($orders_query)) {

      $order_exists = false;

      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');

    }

  }

// BOF: WebMakers.com Added: Additional info for Orders

// Look up things in orders

$the_extra_query= tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");

$the_extra= tep_db_fetch_array($the_extra_query);

$the_customers_id= $the_extra['customers_id'];

// Look up things in customers

$the_extra_query= tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $the_customers_id . "'");

$the_extra= tep_db_fetch_array($the_extra_query);

$the_customers_fax= $the_extra['customers_fax'];

// EOF: WebMakers.com Added: Additional info for Orders



  include(DIR_WS_CLASSES . 'order.php');

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">





<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>



<div id="spiffycalendar" class="text"></div>





<script language="javascript" src="includes/menu.js"></script>

<script language="javascript" src="includes/general.js"></script>

<script language="javascript"><!--

function popupWindow(url) {

  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=650,height=500,screenX=150,screenY=150,top=150,left=150')

}

//--></script>

<?php if ((HTML_AREA_WYSIWYG_DISABLE == 'Enable') or (HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Enable')) { ?>

<script language="Javascript1.2"><!-- // load htmlarea

//MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.8 <head>

      _editor_url = "<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>htmlarea/";  // URL to htmlarea files

        var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);

         if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }

          if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }

           if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }

       <?php if (HTML_AREA_WYSIWYG_BASIC_PD == 'Basic'){ ?>  if (win_ie_ver >= 5.5) {

       document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_basic.js"');

       document.write(' language="Javascript1.2"></scr' + 'ipt>');

          } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }

       <?php } else{ ?> if (win_ie_ver >= 5.5) {

       document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_advanced.js"');

       document.write(' language="Javascript1.2"></scr' + 'ipt>');

          } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }

       <?php }?>

// --></script>

<?php }

function RTESafe($strText) {
	//returns safe code for preloading in the RTE
	$tmpString = trim($strText);
	//convert all types of single quotes
	$tmpString = str_replace(chr(145), chr(39), $tmpString);
	$tmpString = str_replace(chr(146), chr(39), $tmpString);
	//$tmpString = str_replace("'", "&#39;", $tmpString);
	//convert all types of double quotes
	$tmpString = str_replace(chr(147), chr(34), $tmpString);
	$tmpString = str_replace(chr(148), chr(34), $tmpString);
	//replace carriage returns & line feeds
	$tmpString = str_replace(chr(10), "", $tmpString);
	$tmpString = str_replace(chr(13), "\\n", $tmpString);
	return $tmpString;
}
?>

<script language="JavaScript">

	var orderstatussubjectarray = new Array(); 
	var orderstatuscommentarray = new Array(); 
	<?php
	  $orders_status_default_email_query = tep_db_query("select orders_status_id, orders_status_name, orders_status_default_subject, orders_status_default_comment  from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
	  
	  while($orders_status_default_email_row = mysql_fetch_array($orders_status_default_email_query)){
	 
	  if($orders_status_default_email_row['orders_status_default_comment'] !='' && $orders_status_default_email_row['orders_status_id'] != '100009' && $orders_status_default_email_row['orders_status_id'] !='100019' && $orders_status_default_email_row['orders_status_id'] !='100018' && $orders_status_default_email_row['orders_status_id'] !='100008'  && $orders_status_default_email_row['orders_status_id'] !='5' && $orders_status_default_email_row['orders_status_id'] !='99999' && $orders_status_default_email_row['orders_status_id'] !='100003' && $orders_status_default_email_row['orders_status_id'] !='1' && $orders_status_default_email_row['orders_status_id'] !='2'){
	  $orders_status_default_email_row['orders_status_default_comment'] = $orders_status_default_email_row['orders_status_default_comment'] . '\n\nRegards,\n\nCustomer Service Representative';
	  }
	  echo 'orderstatussubjectarray['.$orders_status_default_email_row['orders_status_id'].'] = "'.RTESafe(str_replace('OID', $HTTP_GET_VARS['oID'] ,$orders_status_default_email_row['orders_status_default_subject'])).'";';
	  echo 'orderstatuscommentarray['.$orders_status_default_email_row['orders_status_id'].'] = "'.RTESafe(str_replace('OID', $HTTP_GET_VARS['oID'] ,$orders_status_default_email_row['orders_status_default_comment'])).'";';
	
	  }	
	?>
	
	 function changesubjectemail_new(theform){
	 
		 if(document.status.status.value != ""){
		 	try{
		 	document.status.email_subject.value = orderstatussubjectarray[document.status.status.value];
		 	document. status.comments.value = orderstatuscommentarray[document.status.status.value];
			}catch(e){
			
			}
		 }
	  return true;
	  }
	


	  function changesubjectemail(theform){

	  	  if( document.status.status.value == "100001" ){

		  document.status.email_subject.value = "Receipt of Reservation - Reservation # <?php echo $HTTP_GET_VARS['oID'];?>";

		 } else if(document.status.status.value == "100002"){

		 document.status.email_subject.value = "Your E-Ticket has been issued. (Reservation # <?php echo $HTTP_GET_VARS['oID'];?>)";

		 		 document. status.comments.value = 'Your E-Ticket has been delivered to your email address. Please carefully review all the information on your E-Ticket and let us know should there be an error.We shall not be responsible for any consequences due to being notified of an error within 72 business hours before departure date by customer. \n\nYou may also access your E-Ticket by logging into your account: <?= HTTP_SERVER?>/login.php  "view" your previous orders and click "e-ticket" button. \n\nThank you very much for your purchase.';


		  }else if(document.status.status.value ==  "100000"){ 

		 document. status.email_subject.value = "Reservation Confirmation";	

		 document. status.comments.value = "Congratulations! Your reservation on usitrip has been confirmed. Please save this confirmation email and your reservation number for future reference. We will send you an E-Ticket two or three days before your departure date or even sooner. \n\nIf your reservation includes airport pick-up and/or drop-off and you have not yet provided your flight information to us, please go to <?= HTTP_SERVER?>/account.php to update your flight information as soon as you book flights. We will not be able to issue an E-Ticket to you without flight information.";	 	 

		  }else if(document.status.status.value ==  "100003"){ 

		 document. status.email_subject.value = "Reservation Update - Reservation # <?php echo $HTTP_GET_VARS['oID'];?>";		 

		 }else if(document.status.status.value ==  "100004"){ 

		 document. status.comments.value = "We regret that we will not be available to confirm booking of your interested tour this time. We apologize for any inconvenience this may have caused you.";

		 }else if(document.status.status.value ==  "100005"){ 

		 document. status.comments.value = "We have refunded $ to your credit card per your request for your reservation.\n\nThe transaction may be displayed temporarily as a pending transcation before it is settled. Please allow a couple of days for it to appear on your credit card statement. \n\nWe appreciate the opportunity that you provided for us to serve you and we hope that you will come back visit us in the future."; 

		 }else if(document.status.status.value ==  "100007"){ 

		 document. status.comments.value = "Adjusted Sales:    Adjusted Net:\n\nOriginal Sales:    Original Net:"; 

		   }else if(document.status.status.value ==  "6"){ 

		 document. status.comments.value = "We regret that we were not able to provide a tour for you this time but we definitely appreciate your interest.\n\nPlease come back see us soon.";	 

		  }else if(document.status.status.value ==  "100011"){ 

		 document. status.comments.value = "Our attempts to authorize $ on your provided credit card were declined for some reason.\n\nPlease check with your credit card issuer to make sure our next attempt upon receipt of your required documents will go through.\n\nOr if you would like to use another credit card, please feel free to make changes on the Acknowledgement of Card Billing form and send supporting documents to us.\n<?= HTTP_SERVER?>/acknowledgement_of_card_billing.php.\n\nThank you for your attention. We would appreciate if you could respond promptly. ";	 

		  }else if(document.status.status.value ==  "100012"){ 

		 document. status.comments.value = "Please provide flight information to us by logging into <?= HTTP_SERVER?>/account.php to update your flight information in your account as soon as you book flight. We will not be able to issue an E-Ticket to you without flight information. We would appreciate if you could send an email to service@usitrip.com to inform us of your completion of flight update in your account. Thank you in advance for your time.";	 

		  }else if(document.status.status.value ==  "100013"){ 

		 document. status.comments.value = "Congratulations! Your reservation on usitrip has been confirmed. Please save this confirmation email and your reservation number for future reference.  \n\nWe will issue an E-Ticket (voucher needed to join tour activity) to you as soon as we receive supporting documentation from you. \n\nTo review details of supporting documentation requirement and how to send documents to us, please go to <?= HTTP_SERVER?>/acknowledgement_of_card_billing.php\n\nThank you in advance for your time.";	 

		  }else if(document.status.status.value ==  "100014"){ 

		 document. status.comments.value = "Thank you for following up with our documentation requirement in a prompt manner. We confirm that we have received your documents and everything looks great. We will issue an E-Ticket to you shortly. ";	 

		  }else if(document.status.status.value ==  "100015"){ 

		 document. status.comments.value = "Thank you for following up with our documentation requirement in a prompt manner.  We confirm that we have received your documents; however, your copy of valid photo ID was missing.  Please send a copy to us at your earliest convenience.";	 

			}else if(document.status.status.value ==  "100016"){ 

			 document. status.comments.value = "Please notify us as soon as you send funds to us. We would like to further process your booking. \n\nThank you for your attention.";	 

			}else if(document.status.status.value == "100020"){

			 document. status.comments.value = "We have received your email regarding cancellation of your booking; however, according to our Cancellation and Refund Policy, we will not acknowledge voice mail or email cancellation.Cancellation must be made in writing by fax or by mail or by sending scanned/digital Cancellation Request Form with signature.\n\nTo download Cancellation Request Form, please go to <?= HTTP_SERVER?>/cancellation-and-refund-policy.php";	

			}else if(document.status.status.value == "100021"){

			 document. status.comments.value = "Thank you for your follow-up.  We confirm receipt of your flight information.  We will issue an E-Ticket to you shortly.";	

			}else if(document.status.status.value == "100022"){

			 document. status.comments.value = "We confirm receipt of your Cancellation Request Form.  According to our Cancellation and Refund Policy, <?= HTTP_SERVER?>/cancellation-and-refund-policy.php\n\nWe will charge  of the tour fees ($  ) as cancellation cost. Please allow 1-5 business days for us to process your request.\n\nWe regret that you cannot join a tour this time and hope you will come back visit us in the future.  We appreciate your business.";	

			}else if(document.status.status.value == "100023"){
			 document. status.comments.value = "We confirm receipt of your wire transfer. We have proceeded with further processing your booking. Thank you.";	
			}else if(document.status.status.value == "100024"){
			
			 document. status.comments.value = "You paypal payment did not go through. Please check with Paypal and re-send the payment to paypal@usitrip.com. Besides Paypal, we also accept wire transfer, credit card and money order as payment option. If you wish to use credit card (Visa or Mastercard), you may follow the below link, download and fill out the Acknowledgement of Card Billing form and send it to us with a copy of credit card holder\'s US Driver\'s License or US Photo ID or national passport with signature page. Thank you for your attention and time in advance. <?= HTTP_SERVER?>/acknowledgement_of_card_billing.php  ";	
			
			} 		 	  

		  return true;

	  }

</script>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->

<?php

  require(DIR_WS_INCLUDES . 'header.php');

?>

<!-- header_eof //-->



<!-- body //-->

<table border="0" width="100%" cellspacing="2" cellpadding="2">

  <tr>

    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">

<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- left_navigation_eof //-->

    </table></td>

<!-- body_text //-->

    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">


      <tr>

        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>

            <td colspan="3" class="pageHeading"><?php echo HEADING_TITLE; ?></td>

            
          </tr>

        </table></td>
      </tr>

      <tr>

        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">

          <tr>
          <tr>

            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">



              <tr class="dataTableHeadingRow">

                <td class="dataTableHeadingContent"><?php echo HEADING_ORDER_ID; ?></td>

                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>

				<td class="dataTableHeadingContent"><?php echo 'Ad Source';?></td>		

                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>

                <td class="dataTableHeadingContent" align="center"><?php 
				
				echo TABLE_HEADING_DATE_PURCHASED;
				?></td>

				<td class="dataTableHeadingContent" ><?php 
				echo 'Date of Departure';
				?></td>			

                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
              </tr>

<?php

    $sortorder = 'order by ';

	$addedtable ='';

	$addextracondition ='';

    if($_GET["sort"] == 'customer') {

      if($_GET["order"] == 'ascending') {

        $sortorder .= 'o.customers_name  asc, ';

      } else {

        $sortorder .= 'o.customers_name desc, ';

      }

    } elseif($_GET["sort"] == 'date') {

      if($_GET["order"] == 'ascending') {

        $sortorder .= 'o.date_purchased  asc, ';

      } else {

        $sortorder .= 'o.date_purchased desc, ';

      }

    } elseif($_GET["sort"] == 'departure_date') {

       if($_GET["order"] == 'ascending') {

        $sortorder .= 'opdate asc, ';

		 $addselect = " min(op.products_departure_date) AS opdate, ";

      } else {

        $sortorder .= 'opdate desc, ';

		 $addselect = " max(op.products_departure_date) AS opdate, ";

      }

	  $addedtable = ", " . TABLE_ORDERS_PRODUCTS . " as op ";

	  $addextracondition = " o.orders_id = op.orders_id  and ";

	 

	  $addgroupby = " group by  o.orders_id ";

    } 

	

	

    $sortorder .= 'o.orders_id DESC';

	

    

      $cID = tep_db_prepare_input($HTTP_GET_VARS['cID']);

      $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id,o.customers_advertiser,  o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s ".$addedtable." where ". $addextracondition." o.customers_id = '" . (int)$cID . "' and ot.orders_id = o.orders_id and o.orders_status!=6 and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by orders_id DESC";

     

	

    $orders_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $orders_query_raw, $orders_query_numrows);

    $orders_query = tep_db_query($orders_query_raw);

    while ($orders = tep_db_fetch_array($orders_query)) {

    if ((!isset($HTTP_GET_VARS['oID']) || (isset($HTTP_GET_VARS['oID']) && ($HTTP_GET_VARS['oID'] == $orders['orders_id']))) && !isset($oInfo)) {

        $oInfo = new objectInfo($orders);

      }

	

	
			
			echo '              <tr class="dataTableRow"  >' . "\n";
	
		
	

?>

                <td class="dataTableContent" ><a href="<?php echo tep_href_link('orders.php','oID='.$orders['orders_id'].'&action=edit'); ?>" target="_blank"><?php echo $orders['orders_id']; ?></a></td>

				<td class="dataTableContent"><?php 
				
					echo $orders['customers_name'];
				
				
				 ?></td>

                  <td class="dataTableContent" ><?php echo $orders['customers_advertiser']; //echo tep_get_ad_source($orders['customers_id']); ?></td>

				<td class="dataTableContent" align="right"><?php echo strip_tags($orders['order_total']); ?></td>

                <td class="dataTableContent" align="center"><?php echo tep_datetime_short($orders['date_purchased']); ?></td>

				 <td class="dataTableContent" ><?php echo tep_get_date_of_departure($orders['orders_id']); ?></td>

                <td class="dataTableContent" align="right"><?php echo $orders['orders_status_name']; ?></td>

              </tr>

<?php

    }

?>

              <tr>

                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">

                  <tr>

                    <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>

                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>

                </table></td>
              </tr>

            </table></td>

<?php

  $heading = array();

  $contents = array();



  switch ($action) {

    case 'delete':

      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDER . '</b>');



      $contents = array('form' => tep_draw_form('orders', FILENAME_CUSTOMERS_VIEW_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm'));

      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br><br>');

      $contents[] = array('text' => TEXT_INFO_DELETE_DATA . '&nbsp;' . $oInfo->customers_name . '<br>');

      $contents[] = array('text' => TEXT_INFO_DELETE_DATA_OID . '&nbsp;<b>' . $oInfo->orders_id . '</b><br>');

      $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);

      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CUSTOMERS_VIEW_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');

      break;





    default:

      if (isset($oInfo) && is_object($oInfo)) {

        $heading[] = array('text' => '<b>[' . $oInfo->orders_id . ']&nbsp;&nbsp;' . tep_datetime_short($oInfo->date_purchased) . '</b>');



        if (tep_not_null($oInfo->last_modified)) $contents[] = array('text' => TEXT_DATE_ORDER_LAST_MODIFIED . ' ' . tep_date_short($oInfo->last_modified));

     //$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS_VIEW_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CUSTOMERS_VIEW_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $oInfo->orders_id). '">' . tep_image_button('button_update.gif', IMAGE_UPDATE) . '</a>');

	
	  $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS_REPEAT_ORDERS, tep_get_all_get_params(array('oID', 'action', 'page_c', 'page')). 'page=' . $_GET['page_c'] ) . '">' . tep_image_button('button_back.gif', 'Back') . '</a>');
  
	
	

     $contents[] = array('text' => '<br>' . TEXT_DATE_ORDER_CREATED . ' ' . tep_date_short($oInfo->date_purchased));

        $contents[] = array('text' => '<br>' . TEXT_INFO_PAYMENT_METHOD . ' '  . $oInfo->payment_method);

//begin PayPal_Shopping_Cart_IPN V2.8 DMG

        if (strtolower($oInfo->payment_method) == 'paypal') {

        include_once(DIR_FS_CATALOG_MODULES . 'payment/paypal/functions/general.func.php');

        $contents[] = array('text' => TABLE_HEADING_PAYMENT_STATUS . ': ' .paypal_payment_status($oInfo->orders_id) );

    }

//end PayPal_shopping_Cart_IPN

      }

      break;

  }



  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {

    echo '            <td width="25%" valign="top">' . "\n";



    $box = new box;

    echo $box->infoBox($heading, $contents);



    echo '            </td>' . "\n";

  }

?>
          </tr>

        </table></td>
      </tr>

<?php

  

?>

    </table></td>

<!-- body_text_eof //-->

  </tr>

</table>

<!-- body_eof //-->



<!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

<br>

</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>



<script>

function LTrim(str){if(str==null){return null;}for(var i=0;str.charAt(i)==" ";i++);return str.substring(i,str.length);}

function RTrim(str){if(str==null){return null;}for(var i=str.length-1;str.charAt(i)==" ";i--);return str.substring(0,i+1);}

function Trim(str){return LTrim(RTrim(str));}

function validation_guest()

{

			var i = 0 ;

			for ( i= 0 ; i < window.document.etickets.elements.length ; i ++)

			{

				if(window.document.etickets.elements[i].name.substr(0,5) == "guest")

				{

					var ch = Trim(window.document.etickets.elements[i].value);

					if(ch == "")

					{

						alert("Please Enter guest name")

						window.document.etickets.elements[i].focus();

						return false;

					}

				}

			}

			return true;

			

			

}

</script>