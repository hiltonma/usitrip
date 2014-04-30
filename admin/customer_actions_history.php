<?php
/*
  $Id: affiliate_summary.php,v 1.1.1.1 2004/03/04 23:38:10 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $customer_id = $_GET['customers_id'];
  
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
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=450,height=120,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
	  <tr><td><?php echo tep_draw_separator('pixel_trans.gif', '1', '20'); ?></td></tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td>
			<?php if(isset($HTTP_GET_VARS['customers_id'])){ ?>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <?php /* ?><tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main"><b><?php echo HEADING_TITLE; ?></b></td>
            <td class="main" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'money.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr><?php */ ?>
      <tr>
        <td>
		
          <table border="0" width="100%" cellspacing="0" cellpadding="2">            
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
            </tr>

<?php
$get_customer_name = tep_db_query("select customers_lastname, customers_firstname from " . TABLE_CUSTOMERS . " where customers_id = '".$customer_id."'");
$customer_name_row = tep_db_fetch_array($get_customer_name);
$customers_name = ucfirst($customer_name_row['customers_firstname']).'&nbsp;'.ucfirst($customer_name_row['customers_lastname']);

 $has_point = tep_get_shopping_points($customer_id);
  if ($has_point > 0) {
?>
              <td class="main"><?php echo '<b>'.$customers_name.'</b>&nbsp;&nbsp;&nbsp;'.sprintf(MY_POINTS_CURRENT_BALANCE, number_format($has_point,POINTS_DECIMAL_PLACES),$currencies->format(tep_calc_shopping_pvalue($has_point))); ?></td>
<?php
  } else {
         echo'<td class="main"><b>' . TEXT_NO_POINTS . '</b></td>';
  }
?>
            </tr>
          </table>
<?php
    $pending_points_query_raw = "select unique_id, orders_id, points_pending, points_comment, date_added, points_status, points_type, feedback_other_site_url, admin_id from " . TABLE_CUSTOMERS_POINTS_PENDING . " where customer_id = '" . (int)$customer_id . "' order by unique_id desc";
    //$pending_points_split = new splitPageResults($pending_points_query, MAX_DISPLAY_POINTS_RECORD);
	$pending_points_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $pending_points_query_raw, $pending_points_query_numrows);
	$pending_points_query = tep_db_query($pending_points_query_raw);

    if (tep_db_num_rows($pending_points_query)) {
?>
          <table border="0" width="100%" cellspacing="1" cellpadding="2" class="productListing-heading">
            <tr class="dataTableHeadingRow">
              <td class="dataTableHeadingContent"width="13%"><?php echo HEADING_ORDER_DATE; ?></td>
              <td class="dataTableHeadingContent"width="25%"><?php echo HEADING_ORDERS_NUMBER; ?></td>
              <td class="dataTableHeadingContent" width="250"><?php echo HEADING_POINTS_COMMENT; ?></td>
              <td class="dataTableHeadingContent"><?php echo HEADING_POINTS_STATUS; ?></td>
              <td class="dataTableHeadingContent" align="right"><?php echo HEADING_POINTS_TOTAL; ?></td>
            </tr>
          </table>
          <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
            <tr class="infoBoxContents">
              <td><table border="0" width="100%" cellspacing="1" cellpadding="2">
                <tr>
<?php
    while ($pending_points = tep_db_fetch_array($pending_points_query)) {
	    $orders_status_query = tep_db_query("select o.orders_id, o.orders_status, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = '" . (int)$pending_points['orders_id'] . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "'");
	    $orders_status = tep_db_fetch_array($orders_status_query);
	    
	    if ($pending_points['points_status'] == '1') $points_status_name = TEXT_POINTS_PENDING;
	    if ($pending_points['points_status'] == '2') $points_status_name = TEXT_POINTS_CONFIRMED;
	    if ($pending_points['points_status'] == '3') $points_status_name = '<span class="pointWarning">' . TEXT_POINTS_CANCELLED . '</span>';
	    if ($pending_points['points_status'] == '4') $points_status_name = '<span class="pointWarning">' . TEXT_POINTS_REDEEMED . '</span>';
	    
	    if ($orders_status['orders_status'] == 2 && $pending_points['points_status'] == 1 || $orders_status['orders_status'] == 3 && $pending_points['points_status'] == 1) {
		    $points_status_name = TEXT_POINTS_PROCESSING;
	    }
	    
	    if (($pending_points['points_type'] == 'SP') && ($pending_points['points_comment'] == 'TEXT_DEFAULT_COMMENT')) {
		    $pending_points['points_comment'] = TEXT_DEFAULT_COMMENT;
	    }elseif(preg_match('/^TEXT_/',$pending_points['points_comment'])){
			$pending_points['points_comment'] = constant($pending_points['points_comment']);
		}
	    
/*		if ($pending_points['points_comment'] == 'TEXT_DEFAULT_REDEEMED') {
			$pending_points['points_comment'] = TEXT_DEFAULT_REDEEMED;
		}
		if ($pending_points['points_comment'] == 'TEXT_WELCOME_POINTS_COMMENT') {
			$pending_points['points_comment'] = TEXT_WELCOME_POINTS_COMMENT;
		}
		if ($pending_points['points_comment'] == 'TEXT_DEFAULT_REVIEWS_PHOTOS') {
			$pending_points['points_comment'] = TEXT_DEFAULT_REVIEWS_PHOTOS;
		}
		if ($pending_points['points_comment'] == 'TEXT_DEFAULT_FEEDBACK_APPROVAL') {
			$pending_points['points_comment'] = TEXT_DEFAULT_FEEDBACK_APPROVAL;
		}
		if ($pending_points['points_comment'] == 'TEXT_DEFAULT_ANSWER') {
			$pending_points['points_comment'] = TEXT_DEFAULT_ANSWER;
		}
*/		
		$referred_customers_name = '';
		if ($pending_points['points_type'] == 'RF') {
			$referred_name_query = tep_db_query("select customers_name from " . TABLE_ORDERS . " where orders_id = '" . (int)$pending_points['orders_id'] . "' limit 1");
			$referred_name = tep_db_fetch_array($referred_name_query);
			if ($pending_points['points_comment'] == 'TEXT_DEFAULT_REFERRAL') {
				$pending_points['points_comment'] = TEXT_DEFAULT_REFERRAL;
			}
			$referred_customers_name = $referred_name['customers_name'];
		}
	
	if (($pending_points['points_type'] == 'RV') && ($pending_points['points_comment'] == 'TEXT_DEFAULT_REVIEWS')) {
		$pending_points['points_comment'] = TEXT_DEFAULT_REVIEWS;
	}
	
	if (($pending_points['orders_id'] > '0') && (($pending_points['points_type'] == 'SP')||($pending_points['points_type'] == 'RD'))) {
?>
        <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_ORDERS . '?oID=' . $pending_points['orders_id'] . '&action=edit'); ?>'" title="<?php echo TEXT_ORDER_HISTORY .'&nbsp;' . $pending_points['orders_id']; ?>">
<?php
	}
	
	if ($pending_points['points_type'] == 'RV') {
?>
        <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $pending_points['orders_id'] . '&action=edit'); ?>'" title="<?php echo TEXT_REVIEW_HISTORY; ?>">
<?php
	}
	
	if ($pending_points['points_type'] == 'PH') {
?>
        <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_TRAVELER_PHOTOS, 'page=' . $_GET['page'] . '&tpID=' . $pending_points['orders_id'] . '&action=edit'); ?>'" title="<?php echo TEXT_REVIEW_HISTORY; ?>">
<?php
	}
	if ($pending_points['points_type'] == 'FA') {
?>
        <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="window.open('<?php echo $pending_points['feedback_other_site_url']; ?>');" title="">
<?php
	}
	if ($pending_points['points_type'] == 'QA') {
        /*$get_products_id = tep_db_query("select products_id from ".TABLE_QUESTION." where que_id='".$pending_points['orders_id']."'");
		$row_products_id = tep_db_fetch_array($get_products_id);*/
?>

		<tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_QUESTION_ANSWERS, 'page=' . $_GET['page'] . '&que_id=' . $pending_points['orders_id'] . '&action=view_question'); ?>'" title="<?php echo TEXT_REVIEW_HISTORY; ?>">
<?php
	}
	
	
	if (($pending_points['orders_id'] == 0) || ($pending_points['points_type'] == 'RF') || ($pending_points['points_type'] == 'RV') || ($pending_points['points_type'] == 'PH') || ($pending_points['points_type'] == 'QA')) {
		$orders_status['orders_status_name'] = '<span class="pointWarning">' . TEXT_STATUS_ADMINISTATION . '</span>';
		$pending_points['orders_id'] = '<span class="pointWarning">' . TEXT_ORDER_ADMINISTATION . '</span>';
	}
?>
                  <td class="main"width="13%"><?php echo tep_date_short($pending_points['date_added']); ?></td>
                  <td class="main"width="25%"><?php echo '#' . $pending_points['orders_id'] . '&nbsp;&nbsp;' . $orders_status['orders_status_name']; ?></td>                    
                  <td class="main" width="250">
				  	<?php 
						echo nl2br($pending_points['points_comment']) .'&nbsp;' . $referred_customers_name; 
						if($pending_points['admin_id']>0){
							echo '<br> - Updated by: '.tep_get_admin_customer_name($pending_points['admin_id']);
						}
					?>
				  </td>
                  <td class="main"><?php echo  $points_status_name; ?></td>                    
                  <td class="main" align="right"><?php echo number_format($pending_points['points_pending'],POINTS_DECIMAL_PLACES); ?></td>                    
                </tr>
<?php
	}
?>
              </table></td>
            </tr>
          </table>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <?php /*<tr>
            <td class="smallText" valign="top"><?php echo $pending_points_split->display_count(TEXT_DISPLAY_NUMBER_OF_RECORDS); ?></td>
            <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $pending_points_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>*/ ?>
		  
		  <tr>
                    <td class="smallText" valign="top"><?php echo $pending_points_split->display_count($pending_points_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                    <td class="smallText" align="right"><?php echo $pending_points_split->display_links($pending_points_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                  </tr>
        </table></td>
      </tr>
<?php
  }
?>
    
	  
    </table>
	<?php } ?>
			
			</td>
          </tr>
		  <tr>
		  	<td><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, 'cID='.$HTTP_GET_VARS['customers_id'].'&'.tep_get_all_get_params(array('customers_id','cID')), 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
		  </tr>
        </table></td>
      </tr>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>
