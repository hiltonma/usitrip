<?php
/*
  $Id: customers.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('wait_list');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
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
		$status = tep_db_prepare_input($HTTP_POST_VARS['status']);
		if (!tep_validate_email($cutomer_email)) {
          $error = true;
          $entry_email_address_check_error = true;
        } else {
          $entry_email_address_check_error = false;
        }
		if ($error == false) {
        $sql_data_array = array('customer_name' => $customers_firstname,
                                'cutomer_email' => $cutomer_email,
								'contact_status' => $status,
								'cutomer_travel_date' => $date );

       
        tep_db_perform(TABLE_CUSTOMER_WAITLIST, $sql_data_array, 'update', "waitlist_id = '" . (int)$waitlist_id . "'");

        
        tep_redirect(tep_href_link(FILENAME_WAITLIST, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $waitlist_id));
		}else if ($error == true) {
          $cInfo = new objectInfo($HTTP_POST_VARS);
          $processed = true;
        }
       
        break;
      case 'deleteconfirm':
        $waitlist_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
        tep_db_query("delete from " . TABLE_CUSTOMER_WAITLIST . " where waitlist_id = '" . (int)$waitlist_id . "'");
        tep_redirect(tep_href_link(FILENAME_WAITLIST, tep_get_all_get_params(array('cID', 'action'))));
        break;
		
	  case 'send':	  
	  $waitlist_id = tep_db_prepare_input($HTTP_POST_VARS['cID']);	  
	  $to = tep_db_prepare_input($HTTP_POST_VARS['cutomer_email']);
	  $message = tep_db_prepare_input($HTTP_POST_VARS['message']);
	  $toname = tep_db_prepare_input($HTTP_POST_VARS['customers_firstname']);
	  $status = tep_db_prepare_input($HTTP_POST_VARS['status']);
		
		 $customers_query_raw = "select p.products_urlname, wa.cutomer_email, wa.customer_name,  pd.products_name from
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
		  $email_body .= sprintf(TEXT_MAIL_BODY,$mail['products_urlname'],$mail['products_name'],$message,$mail['products_urlname']). "\n\n<br/>";   
		  
		  $email_body .= TEXT_THANKS . "\n\n<br/>";				   
		  	  	tep_mail($mail['customer_name'].' ',$to, TEXT_MAIL_SUB, $email_body, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
				tep_db_query("update " . TABLE_CUSTOMER_WAITLIST . " set reply_date = now(),contact_status = '". $status ."', contacted = '1' where waitlist_id = '" . $waitlist_id . "'");
		  }		
		 
		tep_redirect(tep_href_link(FILENAME_WAITLIST, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $waitlist_id));
		break;
		case 'setflag':
		$waitlist_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
		$contected = tep_db_prepare_input($HTTP_GET_VARS['flag']);
		$sql_data_array = array('contacted' => $contected);
		tep_db_perform(TABLE_CUSTOMER_WAITLIST, $sql_data_array, 'update', "waitlist_id = '" . (int)$waitlist_id  . "'");
		tep_redirect(tep_href_link(FILENAME_WAITLIST, tep_get_all_get_params(array('cID', 'action'))));
		break;
		
      default:
		$customers_query = tep_db_query("select wa.waitlist_id, wa.customer_name, wa.customer_request_date,wa.cutomer_travel_date,p.products_urlname,p.products_model,p.provider_tour_code,wa.cutomer_email,wa.contacted,wa.cutomer_phone from
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
<script type="text/javascript"><!--

function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var customers_firstname = document.customers.customers_firstname.value;
  var customers_email_address = document.customers.cutomer_email.value;


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
function Sendmail()
{
	document.customers.action = "wait_list.php?action=send";	
}
//--></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('wait_list');
$list = $listrs->showRemark();
?>
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
      <tr><?php echo tep_draw_form('customers', FILENAME_WAITLIST, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"'); ?>
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
        <td align="right" class="main"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_WAITLIST, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr></form>
<?php
  } } else if ($action == 'sendmail') {
		  $waitlist_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
		  $mail = tep_db_prepare_input($HTTP_GET_VARS['customer']);
   $customers_query_raw = "select customer_name,cutomer_email,cutomer_travel_date from " . TABLE_CUSTOMER_WAITLIST . " where waitlist_id = '".$waitlist_id."'";
  $customers_query = tep_db_query($customers_query_raw);
  while ($customers = tep_db_fetch_array($customers_query)) {
		echo tep_draw_hidden_field('cID',$waitlist_id);
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
      <tr><?php echo tep_draw_form('customers', FILENAME_WAITLIST, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"'); echo tep_draw_hidden_field('cID',$HTTP_GET_VARS['cID']);  ?>
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
      echo tep_draw_input_field('cutomer_travel_date', date('Y-m-d',strtotime($customers['cutomer_travel_date'])), 'maxlength="32"') . '&nbsp;' . ENTRY_FIRST_NAME_ERROR;
    } else {
      echo $customers['cutomer_travel_date'] . tep_draw_hidden_field('cutomer_travel_date');
    }
  } else {
    echo tep_draw_input_field('cutomer_travel_date', date('Y-m-d',strtotime($customers['cutomer_travel_date'])), 'maxlength="32"', true);
  }
?><span>(yyyy-mm-dd)</span></td>
          </tr>
		  
		  <tr>
		  <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
          <td><?php echo tep_draw_textarea_field('message', 'soft', '60', '15',TEXT_MESSAGE_BODY); ?></td>
		  </tr>
		  <tr>
		  <td valign="top" class="main"><?php echo TEXT_STATUS; ?></td>
		  <?php
		  	$status_array =array();
			$status_array[] = array('id' => 'Provider Team Follow Up', 'text' => 'Provider Team Follow Up');
			$status_array[] = array('id' => 'Provider Team Closed', 'text' => 'Provider Team Closed');
			$status_array[] = array('id' => 'Customer Service Follow Up', 'text' => 'Customer Service Follow Up');
			$status_array[] = array('id' => 'Customer Service Closed', 'text' => 'Customer Service Closed');
		  ?>
          <td><?php echo tep_draw_pull_down_menu('status',$status_array,''); ?></td>
		  </tr>	  
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE).' '.tep_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL, 'onClick="Sendmail()"'). '<a href="' . tep_href_link(FILENAME_WAITLIST, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr></form>
		 <?php
		  }
		 }
	 else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
		 <tr>
                <td class="smallText" align="right" colspan="5">
<?php
    	echo tep_draw_form('goto', FILENAME_WAITLIST, '', 'get');
		echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $waitlist_id, 'onChange="this.form.submit();"');
    echo '</form>';
?>
                </td>
              </tr>
          <tr><?php echo tep_draw_form('search', FILENAME_WAITLIST, '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
			<td class="smallText" align="left"><?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH); ?></td>
          </form></tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
			<?php
			  $HEADING_FIRSTNAME = TABLE_HEADING_FIRSTNAME;
			  $HEADING_FIRSTNAME .= '<br/><a href="' . $_SERVER['PHP_SELF'] . '?sort=customer_name&order=ascending">';
			  $HEADING_FIRSTNAME .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>';
			  $HEADING_FIRSTNAME .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=customer_name&order=decending">';
			  $HEADING_FIRSTNAME .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>';
			  $HEADING_TRAVEL_DATE = TABLE_TRAVEL_DATE;
			  $HEADING_TRAVEL_DATE .= '<br/><a href="' . $_SERVER['PHP_SELF'] . '?sort=cutomer_travel_date&order=ascending">';
			  $HEADING_TRAVEL_DATE .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>';
			  $HEADING_TRAVEL_DATE .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=cutomer_travel_date&order=decending">';
			  $HEADING_TRAVEL_DATE .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>';
			  $HEADING_REQUEST_DATE = TABLE_REQUEST_DATE;
			  $HEADING_REQUEST_DATE .= '<br/><a href="' . $_SERVER['PHP_SELF'] . '?sort=customer_request_date&order=ascending">';
			  $HEADING_REQUEST_DATE .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_up.gif" border="0"></a>';
			  $HEADING_REQUEST_DATE .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=customer_request_date&order=decending">';
			  $HEADING_REQUEST_DATE .= '&nbsp;<img src="'.DIR_WS_IMAGES.'arrow_down.gif" border="0"></a>';
			?>
                <td class="dataTableHeadingContent" nowrap><?php echo $HEADING_FIRSTNAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo  HEADING_TOUR_NAME; ?></td>
				<td class="dataTableHeadingContent" ><?php echo HEADING_TOUR_CODE; ?></td>
				<td class="dataTableHeadingContent" ><?php echo HEADING_PROVIDER_CODE; ?></td>
				<td class="dataTableHeadingContent"  nowrap><?php echo $HEADING_REQUEST_DATE; ?></td>
				<td class="dataTableHeadingContent" ><?php echo $HEADING_TRAVEL_DATE; ?></td>
				<td class="dataTableHeadingContent" ><?php echo TEXT_REPLY_DATE; ?></td>
				<td class="dataTableHeadingContent" ><?php echo 'Status'; ?></td>
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
	if($HTTP_GET_VARS['cPath'] != '' && $HTTP_GET_VARS['cPath']!=0)
	{
			$cpath_where_condition = " and p2c.categories_id = '" . (int)$HTTP_GET_VARS['cPath'] . "'";
	}
	
	if($HTTP_GET_VARS['tcode'] != '' && isset($HTTP_GET_VARS['tcode']))
	{
			 $search = " and p.products_model = '" . $HTTP_GET_VARS['tcode'] . "'";
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
if($HTTP_GET_VARS['tcode'] != '' && isset($HTTP_GET_VARS['tcode']))
	{
			 $search = " and p.products_model = '" . $HTTP_GET_VARS['tcode'] . "'";
			 $waitlist_query_raw = "select wa.waitlist_id, wa.customer_name, wa.customer_request_date, wa.cutomer_travel_date, wa.cutomer_email,wa.contact_status,  p.products_model, p.provider_tour_code, wa.products_id, wa.contacted, wa.phone_call_time, wa.cutomer_phone, wa.reply_date from
" . TABLE_CUSTOMER_WAITLIST . " wa, 
" . TABLE_PRODUCTS . " p
where
wa.products_id = p.products_id and wa.contacted = '1'
" . $search .  $sortorder;
	}
else {
		$waitlist_query_raw = "select wa.waitlist_id, wa.customer_name, wa.customer_request_date, wa.cutomer_travel_date, p.products_model, p.provider_tour_code,wa.products_id, wa.cutomer_email,wa.contact_status, wa.contacted,wa.phone_call_time, wa.cutomer_phone, wa.reply_date from
		" . TABLE_CUSTOMER_WAITLIST . " wa, 
		" . TABLE_PRODUCTS . " p
		where
		wa.products_id = p.products_id and wa.contacted = '0' 
		" . $search . " ". $sortorder;
}
// EOM mod
    $waitlist_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $waitlist_query_raw, $waitlist_query_numrows);	
    $customers_query = tep_db_query($waitlist_query_raw);
    while ($customers = tep_db_fetch_array($customers_query)) {
      if ((!isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $customers['waitlist_id']))) && !isset($cInfo)) {
	    
		$reviews_query = tep_db_query("select count(*) as number_of_reviews from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers['customers_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);

       /* $customer_info = @array_merge($info, $reviews);*/

        $cInfo_array = @array_merge($customers, $reviews);
		$cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($customers['waitlist_id'] == $cInfo->waitlist_id)) {
        echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_WAITLIST, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->waitlist_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_WAITLIST, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['waitlist_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $customers['customer_name']; ?></td>
				<td class="dataTableContent"><a href="<?php echo tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $customers['products_id']); ?>" target="_blank"><?php echo tep_get_products_name($customers['products_id']); ?></a></td>
				<td class="dataTableContent"><?php echo $customers['products_model']; ?></td>
				<td class="dataTableContent"><?php echo $customers['provider_tour_code']; ?></td>
                <td class="dataTableContent" ><?php echo date('m/d/Y',strtotime($customers['customer_request_date'])); ?></td>
				<td class="dataTableContent" ><?php echo date('m/d/Y',strtotime($customers['cutomer_travel_date'])); ?></td>
				<td class="dataTableContent" ><?php if($customers['reply_date'] != 0) echo date('m/d/Y',strtotime($customers['reply_date'])); ?></td>
				<td class="dataTableContent" align="center">
				<?php echo $customers['contact_status'];
				/*if ($customers['contacted'] == '1') {
				echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_WAITLIST, 'action=setflag&flag=0&cID=' . $customers['waitlist_id'] . '&page='.$page) . '">' . '</a>'.tep_image(DIR_WS_IMAGES .'icon_status_yellow.gif', '', 10, 10);
      } else {
        echo '<a href="' . tep_href_link(FILENAME_WAITLIST, 'action=setflag&flag=1&cID=' . $customers['waitlist_id'] . '&page='.$page) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
			  }*/
				?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($customers['waitlist_id'] == $cInfo->waitlist_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_WAITLIST, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['waitlist_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $waitlist_split->display_count($waitlist_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                    <td class="smallText" align="right"><?php echo $waitlist_split->display_links($waitlist_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>                  
                <td class="smallText" align="right" ><?php echo '<a href="' . tep_href_link(FILENAME_WAITLIST_ARCHIV,'') . '">' . tep_image_button('button_wait_archiv.gif', IMAGE_INSERT) . '</a>'; ?></td>
				  </tr>
<?php
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_WAITLIST) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
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

      $contents = array('form' => tep_draw_form('customers', FILENAME_WAITLIST, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . (int)$HTTP_GET_VARS['cID'] . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_WAITLIST, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . (int)$HTTP_GET_VARS['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<!--<a href="' . tep_href_link(FILENAME_WAITLIST, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->waitlist_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>--> <a href="' . tep_href_link(FILENAME_WAITLIST, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->waitlist_id . '&action=confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_WAITLIST, 'action=sendmail&customer=' . $cInfo->cutomer_email.'&cID='. $cInfo->waitlist_id) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a>');
        $contents[] = array('text' => '<br>' . TABLE_REQUEST_DATE . ' ' . tep_date_short($cInfo->customer_request_date));
        $contents[] = array('text' => '<br>' . TABLE_TRAVEL_DATE . ' ' . tep_date_short($cInfo->cutomer_travel_date)); 
		$contents[] = array('text' => '<br>' . TEXT_EMAIL . ' ' . $cInfo->cutomer_email);
        $contents[] = array('text' => '<br>' . TEXT_PHONE . ' ' . $cInfo->cutomer_phone); 
		$contents[] = array('text' => '<br>' . TEXT_PHONE_CALL_TIME . ' ' . $cInfo->phone_call_time);        
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