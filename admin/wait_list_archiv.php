<?php
/*
  $Id: customers.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  $error = false;
  $processed = false;

  if (tep_not_null($action)) {
    switch ($action) {
      case 'update':
        $waitlist_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        $customers_firstname = tep_db_prepare_input($HTTP_POST_VARS['customers_firstname']);
		$cutomer_email = tep_db_prepare_input($HTTP_POST_VARS['cutomer_email']);
        $date = tep_db_prepare_input($HTTP_POST_VARS['cutomer_travel_date']);
		
		if (!tep_validate_email($cutomer_email)) {
          $error = true;
          $entry_email_address_check_error = true;
        } else {
          $entry_email_address_check_error = false;
        }
		if ($error == false) {
        $sql_data_array = array('customer_name' => $customers_firstname,
                                'cutomer_email' => $cutomer_email,
								'cutomer_travel_date' => $date );

       
        tep_db_perform(TABLE_CUSTOMER_WAITLIST, $sql_data_array, 'update', "waitlist_id = '" . (int)$waitlist_id . "'");

        
        tep_redirect(tep_href_link(FILENAME_WAITLIST_ARCHIV, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $waitlist_id));
		}else if ($error == true) {
          $cInfo = new objectInfo($HTTP_POST_VARS);
          $processed = true;
        }
       
        break;
      case 'deleteconfirm':
        $waitlist_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        tep_db_query("delete from " . TABLE_CUSTOMER_WAITLIST . " where waitlist_id = '" . (int)$waitlist_id . "'");
        tep_redirect(tep_href_link(FILENAME_WAITLIST_ARCHIV, tep_get_all_get_params(array('cID', 'action'))));
        break;
		
	  case 'send':
	  $waitlist_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
	  $toname = tep_db_prepare_input($HTTP_POST_VARS['customers_firstname']);
	  $to = tep_db_prepare_input($HTTP_POST_VARS['cutomer_email']);
	  $message = tep_db_prepare_input($HTTP_POST_VARS['message']);
		
		 $customers_query_raw = "select p.products_urlname,wa.cutomer_email,pd.products_name from
		" . TABLE_CUSTOMER_WAITLIST . " wa, 
		" . TABLE_PRODUCTS . " p,
		" . TABLE_PRODUCT_DESCRIPTION . " pd where wa.products_id = p.products_id AND pd.products_id = p.products_id 
		AND wa.waitlist_id = '".$waitlist_id."'";
		  $customers_query = tep_db_query($customers_query_raw);
		  while ($mail = tep_db_fetch_array($customers_query)) {
		  $email_body .= TEXT_BODY_HEADER. "\n\n<br/>";
		  $email_body .= TEXT_MAIL_HEADER;
		  $email_body .= sprintf(TEXT_MAIL_HEAD,$toname). "\n\n<br/>";
		  $email_body .= TEXT_MAIL_BODY1;   
		  $email_body .= sprintf(TEXT_MAIL_BODY,$mail['products_urlname'],$mail['products_name'],$mail['products_urlname']). "\n\n<br/>";   
		  if (tep_not_null($message)) {
		  	$email_body .= stripslashes($message) . "\n\n<br/>";
		  }
		  $email_body .= TEXT_THANKS . "\n\n<br/>";		  
		  	  	tep_mail($toname,$to, TEXT_MAIL_SUB, $email_body, TEXT_FROM_NAME, TEXT_FROM_EMAIL);
		  }
		  tep_redirect(tep_href_link(FILENAME_WAITLIST_ARCHIV, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $waitlist_id));
		break;
		case 'setflag':
		$waitlist_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
		$contected = tep_db_prepare_input($HTTP_GET_VARS['flag']);
		$sql_data_array = array('contacted' => $contected);
		tep_db_perform(TABLE_CUSTOMER_WAITLIST, $sql_data_array, 'update', "waitlist_id = '" . (int)$waitlist_id  . "'");
		tep_redirect(tep_href_link(FILENAME_WAITLIST_ARCHIV, tep_get_all_get_params(array('cID', 'action'))));
		break;
		
      default:
		$customers_query = tep_db_query("select wa.waitlist_id, wa.customer_name, wa.customer_request_date,wa.tour_code,wa.cutomer_travel_date,p.products_urlname,p.products_model,p.provider_tour_code,wa.cutomer_email,wa.contacted,wa.cutomer_phone from
		" . TABLE_CUSTOMER_WAITLIST . " wa, 
		" . TABLE_PRODUCTS . " p
		where
		wa.products_id = p.products_id and
		wa.waitlist_id = '" . (int)$HTTP_GET_VARS['cID'] . "'");
        $customers = tep_db_fetch_array($customers_query);
        $cInfo = new objectInfo($customers);
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<?php
  if ($action == 'edit' || $action == 'update') {
?>
<script type="text/javascript"><!--

function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var customers_firstname = document.customers.customers_firstname.value;
  var customers_lastname = document.customers.customers_lastname.value;
<?php if (ACCOUNT_COMPANY == 'true') echo 'var entry_company = document.customers.entry_company.value;' . "\n"; ?>
<?php if (ACCOUNT_DOB == 'true') echo 'var customers_dob = document.customers.customers_dob.value;' . "\n"; ?>
  var customers_email_address = document.customers.customers_email_address.value;
  var entry_street_address = document.customers.entry_street_address.value;
  var entry_postcode = document.customers.entry_postcode.value;
  var entry_city = document.customers.entry_city.value;
  var customers_telephone = document.customers.customers_telephone.value;

  if (customers_firstname == "" || customers_firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
    error = 1;
  }
  if (customers_email_address == "" || customers_email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
    error = 1;
  }
  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
<?php
  }
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
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
<?php
  if ($action == 'edit' || $action == 'update') {    
  $waitlist_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
  $customers_query = "select waitlist_id, customer_name,cutomer_email,cutomer_travel_date from " . TABLE_CUSTOMER_WAITLIST . " where waitlist_id = '".$waitlist_id."'";
  $customers_query = tep_db_query($customers_query);
  while ($customers = tep_db_fetch_array($customers_query)) {

?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('customers', FILENAME_WAITLIST_ARCHIV, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"'); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_firstname_error == true) {
      echo tep_draw_input_field('customers_firstname', $customers['customer_name'], 'maxlength="32"') . '&nbsp;' . ENTRY_FIRST_NAME_ERROR;
    } else {
      echo $customers['customer_name'] . tep_draw_hidden_field('customer_name');
    }
  } else {
    echo tep_draw_input_field('customers_firstname', $customers['customer_name'], 'maxlength="32"', true);
  }
?></td>
          </tr>
        
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_email_address_error == true) {
      echo tep_draw_input_field('cutomer_email', $customers['cutomer_email'], 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
    } elseif ($entry_email_address_check_error == true) {
      echo tep_draw_input_field('cutomer_email', $customers['cutomer_email'], 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
    } elseif ($entry_email_address_exists == true) {
      echo tep_draw_input_field('cutomer_email', $customers['cutomer_email'], 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR_EXISTS;
    } else {
      echo $customers['cutomer_email'] . tep_draw_hidden_field('cutomer_email');
    }
  } else {
    echo tep_draw_input_field('cutomer_email', $customers['cutomer_email'], 'maxlength="96"', true);
  }
?></td>
          </tr>
		  <tr>
            <td class="main"><?php echo TABLE_TRAVEL_DATE; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_firstname_error == true) {
      echo tep_draw_input_field('cutomer_travel_date', $customers['cutomer_travel_date'], 'maxlength="32"') . '&nbsp;' . ENTRY_FIRST_NAME_ERROR;
    } else {
      echo $customers['cutomer_travel_date'] . tep_draw_hidden_field('cutomer_travel_date');
    }
  } else {
    echo tep_draw_input_field('cutomer_travel_date', $customers['cutomer_travel_date'], 'maxlength="32"', true);
  }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_WAITLIST_ARCHIV, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr></form>
<?php
  } } else if ($action == 'sendmail') {
		  $waitlist_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
		  $mail = tep_db_prepare_input($HTTP_GET_VARS['customer']);
   $customers_query_raw = "select customer_name,cutomer_email from " . TABLE_CUSTOMER_WAITLIST . " where waitlist_id = '".$waitlist_id."'";
  $customers_query = tep_db_query($customers_query_raw);
  while ($customers = tep_db_fetch_array($customers_query)) {
		 ?>
		 <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('customers', FILENAME_WAITLIST_ARCHIV, tep_get_all_get_params(array('action')) . 'action=send', 'post', 'onSubmit="return check_form();"'); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_firstname_error == true) {
      echo tep_draw_input_field('customers_firstname', $customers['customer_name'], 'maxlength="32"') . '&nbsp;' . ENTRY_FIRST_NAME_ERROR;
    } else {
      echo $customers['customer_name'] . tep_draw_hidden_field('customer_name');
    }
  } else {
    echo tep_draw_input_field('customers_firstname', $customers['customer_name'], 'maxlength="32"', true);
  }
?></td>
          </tr>
        
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_email_address_error == true) {
      echo tep_draw_input_field('cutomer_email', $customers['cutomer_email'], 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
    } elseif ($entry_email_address_check_error == true) {
      echo tep_draw_input_field('cutomer_email', $customers['cutomer_email'], 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
    } elseif ($entry_email_address_exists == true) {
      echo tep_draw_input_field('cutomer_email', $customers['cutomer_email'], 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR_EXISTS;
    } else {
      echo $customers['cutomer_email'] . tep_draw_hidden_field('cutomer_email');
    }
  } else {
    echo tep_draw_input_field('cutomer_email', $customers['cutomer_email'], 'maxlength="96"', true);
  }
?></td>
          </tr>	
		  <tr>
		  <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
          <td><?php echo tep_draw_textarea_field('message', 'soft', '60', '15'); ?></td>
		  </tr>	  
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL). ' <a href="' . tep_href_link(FILENAME_WAITLIST_ARCHIV, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr></form>
		 <?php
		  }
		 }
	 else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
		 <tr>
                <td class="smallText" align="right">
<?php
    	/*echo tep_draw_form('goto', FILENAME_WAITLIST_ARCHIV, '', 'get');
		echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $waitlist_id, 'onChange="this.form.submit();"');
    echo '</form>';*/
?>
                </td>
              </tr>
          <tr><?php echo tep_draw_form('search', FILENAME_WAITLIST_ARCHIV, '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
           <?php /* <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>*/?>
          </form></tr> 
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
				<?php 
					  $HEADING_TOUR_CODE = HEADING_TOUR_CODE;
					  $HEADING_TOUR_CODE .= '<br/><a href="' . $_SERVER['PHP_SELF'] . '?sort=tour_code&order=ascending">';
					  $HEADING_TOUR_CODE .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>';
					  $HEADING_TOUR_CODE .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=tour_code&order=decending">';
					  $HEADING_TOUR_CODE .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>';
					  $HEADING_PROVIDER_CODE = HEADING_PROVIDER_CODE;
					  $HEADING_PROVIDER_CODE .= '<br/><a href="' . $_SERVER['PHP_SELF'] . '?sort=provider_tour_code&order=ascending">';
					  $HEADING_PROVIDER_CODE .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>';
					  $HEADING_PROVIDER_CODE .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=provider_tour_code&order=decending">';
					  $HEADING_PROVIDER_CODE .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>';
				?>            
                <td class="dataTableHeadingContent"><?php echo  HEADING_TOUR_NAME; ?></td>
				<td class="dataTableHeadingContent" ><?php echo $HEADING_TOUR_CODE; ?></td>
				<td class="dataTableHeadingContent" ><?php echo $HEADING_PROVIDER_CODE; ?></td>
				<td class="dataTableHeadingContent" ><?php echo HEADING_CONTECTED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
      $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
	  if (strpos($keywords, " ") === false){
	  	//echo 'false';
		$search_extra = "";
	  }else{
	  	//echo 'true';
		$keyword_split = explode(" ",$keywords);
      	$search_extra = " or wa.customer_name like '%" . $keyword_split[0] . "%' or p.products_model like '%" . $keyword_split[1] . "%' or p.provider_tour_code like '%" . $keyword_split[0] . "%'";
	  }
	  $search = " and (wa.customer_name like '%" . $keywords . "%' or p.products_model like '%" . $keywords . "%' or p.provider_tour_code like '%" . $keywords . "%'".$search_extra.")";
    }
    // BOM Mod:provide an order by option
    $sortorder = 'order by wa.cutomer_travel_date desc';
    switch ($_GET["sort"]) {
      case 'customer_name':
        if($_GET["order"]==ascending) {
          $sortorder = 'order by wa.customer_name  asc';
        } else {
          $sortorder = 'order by wa.customer_name  desc';
        }
        break;
		case 'customer_request_date':
        if($_GET["order"]==ascending) {
          $sortorder = 'order by wa.customer_request_date  asc';
        } else {
          $sortorder = 'order by wa.customer_request_date  desc';
        }
        break;
		case 'cutomer_travel_date':
        if($_GET["order"]==ascending) {
          $sortorder = 'order by wa.cutomer_travel_date  asc';
        } else {
          $sortorder = 'order by wa.cutomer_travel_date  desc';
        }
        break;
      default:
        if($_GET["order"]==ascending) {
          $sortorder = 'order by wa.cutomer_travel_date  asc';
        } else {
          $sortorder = 'order by wa.cutomer_travel_date  desc';
        }
        break;
    }


 $customers_query_raw = "select wa.waitlist_id, wa.customer_name, wa.customer_request_date,wa.cutomer_travel_date,p.products_urlname,
 wa.tour_code, count(wa.tour_code) as tot,p.provider_tour_code,wa.cutomer_email, pd.products_name, wa.contacted,wa.cutomer_phone from
" . TABLE_CUSTOMER_WAITLIST . " wa, 
" . TABLE_PRODUCTS . " p,
" . TABLE_PRODUCTS_DESCRIPTION . " pd
where
wa.products_id = p.products_id and wa.products_id = pd.products_id and wa.contacted ='1'
" . $search ." GROUP BY wa.tour_code ".$sortorder;
// EOM mod
    $customers_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $customers_query_raw, $customers_query_numrows);
    $customers_query = tep_db_query($customers_query_raw);
    while ($customers = tep_db_fetch_array($customers_query)) {
      if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $customers['waitlist_id']))) && !isset($cInfo)) {
	    
		$reviews_query = tep_db_query("select count(*) as number_of_reviews from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers['customers_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);

       /* $customer_info = @array_merge($info, $reviews);*/

        $cInfo_array = @array_merge($customers, $reviews);
		$cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($customers['waitlist_id'] == $cInfo->waitlist_id)) {
        echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_WAITLIST_ARCHIV, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->waitlist_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_WAITLIST_ARCHIV, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['waitlist_id']) . '\'">' . "\n";
      }
?>
                
				<td class="dataTableContent"><?php echo $customers['products_name']; ?></td>
				<td class="dataTableContent"><?php echo $customers['tour_code']; ?></td>
				<td class="dataTableContent"><?php echo $customers['provider_tour_code']; ?></td>
				<td class="dataTableContent" align="center"><?php echo '<a href="' . tep_href_link(FILENAME_WAITLIST, tep_get_all_get_params(array('cID', 'action')) . 'tcode='.$customers['tour_code']) . '">' .$customers['tot'] .'</a>'; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($customers['waitlist_id'] == $cInfo->waitlist_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_WAITLIST_ARCHIV, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['waitlist_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                    <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>                                  
				  </tr>
<?php
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_WAITLIST_ARCHIV) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'confirm':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</b>');

      $contents = array('form' => tep_draw_form('customers', FILENAME_WAITLIST_ARCHIV, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . (int)$HTTP_GET_VARS['cID'] . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_WAITLIST_ARCHIV, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . (int)$HTTP_GET_VARS['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
       $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_WAITLIST, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->waitlist_id . '&tcode='.$cInfo->tour_code) . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> ');
        $contents[] = array('text' => '<br>' . HEADING_TOUR_CODE . ' ' . $cInfo->tour_code);        
      }
      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '<td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
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
