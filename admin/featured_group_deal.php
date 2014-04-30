<?php
/*
  $Id: specials.php,v 1.1.1.1 2004/03/04 23:38:58 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('featured_group_deal');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
		tep_db_query("update " . TABLE_FEATURED_DEALS . " set status = '".$HTTP_GET_VARS['flag']."', date_status_change = now() where featured_deals_id = '" . (int)$HTTP_GET_VARS['id'] . "'");
        tep_redirect(tep_href_link(FILENAME_FEATURED_GROUP_DEAL, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'sID=' . $HTTP_GET_VARS['id'], 'NONSSL'));
      break;
	  case 'setflagf':
		if($HTTP_GET_VARS['id'] != "" && $HTTP_GET_VARS['flag'] != ""){
			tep_db_query("update " . TABLE_FEATURED_DEALS . " set featured_products = '" . $HTTP_GET_VARS['flag'] . "' where featured_deals_id = '" . $HTTP_GET_VARS['id'] . "'");
		}
        tep_redirect(tep_href_link(FILENAME_FEATURED_GROUP_DEAL, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'sID=' . $HTTP_GET_VARS['id'], 'NONSSL'));
      break;
      case 'insert':
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $products_price = tep_db_prepare_input($HTTP_POST_VARS['products_price']);
        $featured_deals_price = tep_db_prepare_input($HTTP_POST_VARS['featured_deals_price']);        
		

        if (substr($featured_deals_price, -1) == '%') {
          $new_special_insert_query = tep_db_query("select products_id, products_price, products_margin from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
          $new_special_insert = tep_db_fetch_array($new_special_insert_query);

          $products_price = $new_special_insert['products_price'];
          $featured_deals_price = ($products_price - round( ($featured_deals_price / 100) * ($products_price * ($new_special_insert['products_margin']/100)), 2));
        }

        $departure_restriction_date = tep_get_date_db($HTTP_POST_VARS['departure_restriction_date']);
		$active_date = tep_get_date_db($HTTP_POST_VARS['active_date']);
		$expires_date = tep_get_date_db($HTTP_POST_VARS['expires_date']);

        tep_db_query("insert into " . TABLE_FEATURED_DEALS . " (products_id, featured_deals_new_products_price, featured_deals_date_added, expires_date, status, active_date, departure_restriction_date) values ('" . (int)$products_id . "', '" . tep_db_input($featured_deals_price) . "', now(), '" . tep_db_input($expires_date) . "', '1', '" . tep_db_input($active_date) . "', '" . tep_db_input($departure_restriction_date) . "')");
		
		for($i=1;$i<=3;$i++){
			if($HTTP_POST_VARS['adj_price_'.$i] > 0){
				tep_db_query("insert into " . TABLE_FEATURED_DEALS_GROUP_DISCOUNTS . " (products_id, peple_no, discount_percent, adj_price, discount_amt) values ('" . (int)$products_id . "', '" . tep_db_input($HTTP_POST_VARS['people_no_'.$i]) . "', '" . tep_db_input($HTTP_POST_VARS['discount_percent_'.$i]) . "', '" . tep_db_input($HTTP_POST_VARS['adj_price_'.$i]) . "', '" . tep_db_input($HTTP_POST_VARS['discount_amt_'.$i]) . "')");
			}
		}

        tep_redirect(tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page=' . $HTTP_GET_VARS['page']));
      break;
      case 'update':
        $featured_deals_id = tep_db_prepare_input($HTTP_POST_VARS['featured_deals_id']);
        $products_price = tep_db_prepare_input($HTTP_POST_VARS['products_price']);
        $featured_deals_price = tep_db_prepare_input($HTTP_POST_VARS['featured_deals_price']);
		
		$get_update_tour_id_query = tep_db_query("select products_id, products_price, products_margin from " . TABLE_PRODUCTS . " where products_id = '" . (int)$HTTP_POST_VARS['products_id'] . "'");
        $update_tour = tep_db_fetch_array($get_update_tour_id_query);       

        if (substr($featured_deals_price, -1) == '%'){ 
			$featured_deals_price = ($products_price - round( ($featured_deals_price / 100) * ($products_price * ($update_tour['products_margin']/100)), 2));
		}

        $departure_restriction_date = tep_get_date_db($HTTP_POST_VARS['departure_restriction_date']);
		$active_date = tep_get_date_db($HTTP_POST_VARS['active_date']);
		$expires_date = tep_get_date_db($HTTP_POST_VARS['expires_date']);
		/*
		if (tep_not_null($dep_day) && tep_not_null($dep_month) && tep_not_null($dep_year)) {
          $departure_restriction_date = $dep_year;
          $departure_restriction_date .= (strlen($dep_month) == 1) ? '0' . $dep_month : $dep_month;
          $departure_restriction_date .= (strlen($dep_day) == 1) ? '0' . $dep_day : $dep_day;
        }
		*/

        tep_db_query("update " . TABLE_FEATURED_DEALS . " set featured_deals_new_products_price = '" . tep_db_input($featured_deals_price) . "', featured_deals_last_modified = now(), expires_date = '" . tep_db_input($expires_date) . "', active_date = '" . tep_db_input($active_date) . "', departure_restriction_date = '" . tep_db_input($departure_restriction_date) . "' where featured_deals_id = '" . (int)$featured_deals_id . "'");
		
		tep_db_query("delete from " . TABLE_FEATURED_DEALS_GROUP_DISCOUNTS . " where products_id = '".(int)$HTTP_POST_VARS['products_id']."'");
		for($i=1;$i<=3;$i++){
			if($HTTP_POST_VARS['adj_price_'.$i] > 0){
				tep_db_query("insert into " . TABLE_FEATURED_DEALS_GROUP_DISCOUNTS . " (products_id, peple_no, discount_percent, adj_price, discount_amt) values ('" . (int)$HTTP_POST_VARS['products_id'] . "', '" . tep_db_input($HTTP_POST_VARS['people_no_'.$i]) . "', '" . tep_db_input($HTTP_POST_VARS['discount_percent_'.$i]) . "', '" . tep_db_input($HTTP_POST_VARS['adj_price_'.$i]) . "', '" . tep_db_input($HTTP_POST_VARS['discount_amt_'.$i]) . "')");
			}
		}

        tep_redirect(tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $featured_deals_id));
      break;
      case 'deleteconfirm':
        $featured_deals_id = tep_db_prepare_input($HTTP_GET_VARS['sID']);

        $get_products_id = tep_db_fetch_array(tep_db_query("select products_id from " . TABLE_FEATURED_DEALS . " where featured_deals_id = '" . (int)$featured_deals_id . "'"));		
		tep_db_query("delete from " . TABLE_FEATURED_DEALS_GROUP_DISCOUNTS . " where products_id = '".(int)$get_products_id['products_id']."'");
		tep_db_query("delete from " . TABLE_FEATURED_DEALS . " where featured_deals_id = '" . (int)$featured_deals_id . "'");

        tep_redirect(tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page=' . $HTTP_GET_VARS['page']));
      break;
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
  if ( ($action == 'new') || ($action == 'edit') || ($action == 'search') ) {
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>
<?php /* ?>
<link rel="stylesheet" type="text/css" href="includes/javascript/calendar.css">
<script language="JavaScript" src="includes/javascript/calendarcode.js"></script>
<?php
*/
  }
?>
<div id="spiffycalendar" class="text"></div>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="popupcalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('featured_group_deal');
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
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ( ($action == 'new') || ($action == 'edit') || ($action == 'search') ) {
    $form_action = 'insert';
    if ( ($action == 'edit') && isset($HTTP_GET_VARS['sID']) ) {
      $form_action = 'update';
	  
	 

      $product_query = tep_db_query("select p.products_id, pd.products_name, p.products_model, p.products_price, p.products_margin, s.featured_deals_new_products_price, s.expires_date, s.departure_restriction_date, s.active_date from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_FEATURED_DEALS . " s where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = s.products_id and s.featured_deals_id = '" . (int)$HTTP_GET_VARS['sID'] . "'");
      $product = tep_db_fetch_array($product_query);
	  
	  
	    $tour_agency_opr_currency = tep_get_tour_agency_operate_currency($product['products_id']);		
		if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
		$display_tour_agency_opr_currency_note = '<span class="errorText">'.$tour_agency_opr_currency.':</span>';
		}else{
		$display_tour_agency_opr_currency_note = '';
		}


      $sInfo = new objectInfo($product);
    } else {
      $sInfo = new objectInfo(array());

// create an array of products on special, which will be excluded from the pull down menu of products
// (when creating a new product on special)
      $featured_deals_array = array();
      $featured_deals_query = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p, " . TABLE_FEATURED_DEALS . " s where s.products_id = p.products_id");
      while ($featured_deals = tep_db_fetch_array($featured_deals_query)) {
        $featured_deals_array[] = $featured_deals['products_id'];
      }
    }
		
		if($action == 'search' && $_POST['products_model']!=""){
			$qry_get_product_id = "select p.products_id from " . TABLE_PRODUCTS . " p WHERE p.products_model = '".$_POST['products_model']."'";
			$res_get_product_id = tep_db_query($qry_get_product_id);
			$row_get_product_id = tep_db_fetch_array($res_get_product_id);
			if($row_get_product_id['products_id'] != ""){
				$keep_selected_product_id = (int)$row_get_product_id['products_id'];
			}
		}
		
		if( ($action == 'new') || ($action == 'search') ) {
?>
			<tr>
				<td class="main" align="right" valign="top" width="100%">
				<form name="new_special_search" <?php echo 'action="' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, tep_get_all_get_params(array('action', 'info', 'sID')) . 'action=search', 'NONSSL') . '"'; ?> method="post"><?php if ($form_action == 'update') echo tep_draw_hidden_field('featured_deals_id', $HTTP_GET_VARS['sID']); ?>
					<table border="0" cellpadding="3" cellspacing="2" width="45%" class="main">
						<tr>
							<td>
								<?php echo TEXT_SEARCH_BY_TOUR_CODE;?>&nbsp;<?php echo tep_draw_input_field('products_model').'&nbsp;'.tep_image_submit('button_search.gif', IMAGE_INSERT); ?>
							</td>
						</tr>
					</table>
				</form>
				</td>
			</tr>
<?php }?>
		
      <tr>
        <td><br>
		<form name="new_special" id="new_special" <?php echo 'action="' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, tep_get_all_get_params(array('action', 'info', 'sID')) . 'action=' . $form_action, 'NONSSL') . '"'; ?> method="post"><?php if ($form_action == 'update') echo tep_draw_hidden_field('featured_deals_id', $HTTP_GET_VARS['sID']); ?>
		<table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_SPECIALS_PRODUCT; ?>&nbsp;</td>
            <td class="main"><?php echo (isset($sInfo->products_name)) ? $sInfo->products_name . ' ['.$sInfo->products_model.'] <small>(' .$display_tour_agency_opr_currency_note. $currencies->format($sInfo->products_price) . ')</small>'. tep_draw_hidden_field('products_id', $sInfo->products_id) : tep_draw_products_pull_down('products_id', 'style="font-size:10px; width:700px;" onchange="change_tour_discounts(this.value);"', $featured_deals_array, $keep_selected_product_id); echo tep_draw_hidden_field('products_price', (isset($sInfo->products_price) ? $sInfo->products_price : '')); ?>
			</td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_SPECIALS_SPECIAL_PRICE; ?>&nbsp;</td>
            <td class="main"><?php echo $display_tour_agency_opr_currency_note.tep_draw_input_field('featured_deals_price', (isset($sInfo->featured_deals_new_products_price) ? $sInfo->featured_deals_new_products_price : '')) . '&nbsp;&nbsp; (Original Tour Price: '. $currencies->format($sInfo->products_price).')'; ?></td>
          </tr>
		  <tr>
            <td class="main"><?php echo 'Tour Departure Restrictions:'; ?>&nbsp;</td>
            <td class="main">
			<?php echo tep_draw_input_field('departure_restriction_date', tep_get_date_disp($sInfo->departure_restriction_date), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
			<script type="text/javascript">
				//var Dept_rest_date = new ctlSpiffyCalendarBox("Dept_rest_date", "new_special", "departure_restriction_date","btnDate1","<?php echo tep_get_date_disp($sInfo->departure_restriction_date); ?>",scBTNMODE_CUSTOMBLUE);
				//Dept_rest_date.writeControl(); Dept_rest_date.dateFormat="MM/dd/yyyy";
			</script>			
			&nbsp;&nbsp;<b style="color:#FF0000;">Customer must choose departure after this date.</b>
			</td>
          </tr>
		  <tr><td height="10"></td></tr>
		  <tr>
		  	<td class="main" colspan="2">Discount Rate: </td>
		  </tr>
		  <tr><td height="10"></td></tr>
		  <?php
		  $percent = 30;
		  $tour_gp = $sInfo->products_price*($sInfo->products_margin/100);
		  echo '<tr><td class="main" id="show_gp">(GP = $'.$sInfo->products_price.'*'.$sInfo->products_margin.'% = $'.number_format($tour_gp, 2).')</td></tr><tr><td height="6"></td></tr>';
		  if($form_action == 'update'){
		  $get_discount_data = tep_db_query("select * from " . TABLE_FEATURED_DEALS_GROUP_DISCOUNTS . " where products_id = '".(int)$sInfo->products_id."' order by peple_no asc");
		  $i = 0;
		  while($discount_data = tep_db_fetch_array($get_discount_data)){
		  $i++;
		  ?>
		  <tr>
		  	<td class="main" colspan="2">
				<table cellpadding="0" cellspacing="0">
				<tr>
					<td class="main"><?php echo tep_draw_input_field('people_no_'.$i, $discount_data['peple_no'], 'size="3"'); ?></td>
					<td class="main">People Book</td>
					<td class="main">&nbsp;&nbsp;<?php echo tep_draw_input_field('tour_gp_'.$i, number_format($tour_gp, 2), 'size="10"'); ?>&nbsp;x&nbsp;</td>
					<td class="main"><?php echo tep_draw_input_field('discount_percent_'.$i, $discount_data['discount_percent'], 'size="4" onchange="change_percent('.$i.', this.value)"'); ?>%</td>
					<td class="main">&nbsp;=&nbsp;<?php echo tep_draw_input_field('discount_amt_'.$i, number_format($discount_data['discount_amt'], 2), 'size="10"'); ?></td>
					<td width="10">&nbsp;</td>
					<?php
					$adj_price = $sInfo->products_price - number_format($discount_data['discount_amt'], 2);
					?>
					<td class="main">Tour Price - Discount = <?php echo tep_draw_input_field('adj_price_'.$i, number_format($discount_data['adj_price'], 2), 'size="10"'); ?></td>
				</tr>
				</table>	
			</td>
		  </tr>
		  <?php
		  $percent = $percent+10;
		  }
		  
		  }else{
		  for($i=1;$i<=3;$i++){
		  ?>
		  <tr>
		  	<td class="main" colspan="2">
				<table cellpadding="0" cellspacing="0">
				<tr>
					<td class="main"><?php echo tep_draw_input_field('people_no_'.$i, $i*10, 'size="3"'); ?></td>
					<td class="main">People Book</td>
					<td class="main">&nbsp;&nbsp;<?php echo tep_draw_input_field('tour_gp_'.$i, number_format($tour_gp, 2), 'size="10"'); ?>&nbsp;x&nbsp;</td>
					<td class="main"><?php echo tep_draw_input_field('discount_percent_'.$i, $percent, 'size="4" onchange="change_percent('.$i.', this.value)"'); ?>%</td>
					<td class="main">&nbsp;=&nbsp;<?php echo tep_draw_input_field('discount_amt_'.$i, number_format($tour_gp*($percent/100), 2), 'size="10"'); ?></td>
					<td width="10">&nbsp;</td>
					<?php
					$adj_price = $sInfo->products_price - number_format($tour_gp*($percent/100), 2);
					?>
					<td class="main">Tour Price - Discount = <?php echo tep_draw_input_field('adj_price_'.$i, number_format($adj_price, 2), 'size="10"'); ?></td>
				</tr>
				</table>	
			</td>
		  </tr>
		  <?php
		  $percent = $percent+10;
		  }
		  }
		  ?>
		  <tr><td height="20"></td></tr>
          <tr>
            <td class="main"><?php echo 'Active Date: '; ?>&nbsp;</td>
            <td class="main">
			<?php echo tep_draw_input_field('active_date', ($sInfo->active_date > 0 ? tep_get_date_disp($sInfo->active_date) : ''), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'d/m/y\'; GeCalendar.SetDate(this);"');?>
			<script type="text/javascript">
				//var Dept_rest_date1 = new ctlSpiffyCalendarBox("Dept_rest_date1", "new_special", "active_date","btnDate2","<?php echo ($sInfo->active_date > 0 ? tep_get_date_disp($sInfo->active_date) : ''); ?>",scBTNMODE_CUSTOMBLUE);
				//Dept_rest_date1.writeControl(); Dept_rest_date1.dateFormat="MM/dd/yyyy";				
			</script>						
			</td>
          </tr>        
		  <tr>
            <td class="main"><?php echo TEXT_SPECIALS_EXPIRES_DATE; ?>&nbsp;</td>
            <td class="main">
			<?php echo tep_draw_input_field('expires_date', ($sInfo->expires_date > 0 ? tep_get_date_disp($sInfo->expires_date) : ''), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'d/m/y\'; GeCalendar.SetDate(this);"');?>
			<script type="text/javascript">
				//var Expires_date = new ctlSpiffyCalendarBox("Expires_date", "new_special", "expires_date","btnDate3","<?php echo ($sInfo->expires_date > 0 ? tep_get_date_disp($sInfo->expires_date) : ''); ?>",scBTNMODE_CUSTOMBLUE);
				//Expires_date.writeControl(); Expires_date.dateFormat="MM/dd/yyyy";
			</script>						
			</td>
          </tr>        
		  <tr>
			<td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
			  <tr>
				<td class="main"><br><?php echo TEXT_SPECIALS_PRICE_TIP; ?></td>
				<td class="main" align="right" valign="top"><br><?php echo (($form_action == 'insert') ? tep_image_submit('button_insert.gif', IMAGE_INSERT) : tep_image_submit('button_update.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page=' . $HTTP_GET_VARS['page'] . (isset($HTTP_GET_VARS['sID']) ? '&sID=' . $HTTP_GET_VARS['sID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
			  </tr>
			</table>
			</td>
		  </tr>
	  	</table>
	  <?php echo '</form>'; ?>
	  </td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRODUCTS_PRICE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
				<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_FEATURED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $featured_deals_query_raw = "select ta.operate_currency_code, p.products_id, p.products_model, pd.products_name, p.products_price, s.featured_deals_id, s.featured_deals_new_products_price, s.featured_deals_date_added, s.featured_deals_last_modified, s.expires_date, s.date_status_change, s.status, s.featured_products, p.products_margin from " . TABLE_PRODUCTS . " p, " . TABLE_FEATURED_DEALS . " s, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_TRAVEL_AGENCY . " ta where p.agency_id = ta.agency_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = s.products_id order by pd.products_name";
    $featured_deals_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $featured_deals_query_raw, $featured_deals_query_numrows);
    $featured_deals_query = tep_db_query($featured_deals_query_raw);
    while ($featured_deals = tep_db_fetch_array($featured_deals_query)) {
	
	if($featured_deals['operate_currency_code'] != 'USD' && $featured_deals['operate_currency_code'] != ''){
	 $featured_deals['products_price'] = tep_get_tour_price_in_usd($featured_deals['products_price'],$featured_deals['operate_currency_code']);
	 $featured_deals['featured_deals_new_products_price'] = tep_get_tour_price_in_usd($featured_deals['featured_deals_new_products_price'],$featured_deals['operate_currency_code']);
	}
	
      if ((!isset($HTTP_GET_VARS['sID']) || (isset($HTTP_GET_VARS['sID']) && ($HTTP_GET_VARS['sID'] == $featured_deals['featured_deals_id']))) && !isset($sInfo)) {
        $products_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int)$featured_deals['products_id'] . "'");
        $products = tep_db_fetch_array($products_query);
        $sInfo_array = array_merge($featured_deals, $products);
        $sInfo = new objectInfo($sInfo_array);
      }

      if (isset($sInfo) && is_object($sInfo) && ($featured_deals['featured_deals_id'] == $sInfo->featured_deals_id)) {
        echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
		// onclick="document.location.href=\'' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->featured_deals_id . '&action=edit') . '\'"
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">' . "\n";
		// onclick="document.location.href=\'' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $featured_deals['featured_deals_id']) . '\'"
      }
?>
                <td  class="dataTableContent">
				<?php echo '<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_deals['products_id']) . '">'.$featured_deals['products_name']. '</a><a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($featured_deals['products_id']) . '&pID=' . $featured_deals['products_id'].'&action=new_product&tabedit=room_and_price') . '"> ['.$featured_deals['products_model'].']</a>'; ?> </td>
                <td  class="dataTableContent" align="right">
                <?php 
				$total_featured_orders = tep_db_num_rows(tep_db_query("select products_room_info from ".TABLE_ORDERS_PRODUCTS." where is_diy_tours_book = '2' and group_buy_discount > 0 and products_id = '".$featured_deals['products_id']."' group by orders_id"));
				if($total_featured_orders>0){
					echo '<a href="javascript:void(0)" onclick="toggel_div(\'featured_orders_info_'.$featured_deals['featured_deals_id'].'\');">'.$total_featured_orders.'</a>';
				}else{
				echo $total_featured_orders;
				}
				?>
                </td>
                <td  class="dataTableContent" align="right"><span class="oldPrice"><?php echo $currencies->format($featured_deals['products_price']); ?></span> <span class="specialPrice"><?php echo $currencies->format($featured_deals['featured_deals_new_products_price']); ?></span></td>
                <td  class="dataTableContent" align="right">
<?php
      if ($featured_deals['status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page='.$_GET['page'].'&action=setflag&flag=0&id=' . $featured_deals['featured_deals_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page='.$_GET['page'].'&action=setflag&flag=1&id=' . $featured_deals['featured_deals_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
							<td class="<?php echo $current_row; ?>" align="center">
							<?php
								if($featured_deals['featured_products'] == '1'){
									echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page='.$_GET['page'].'&action=setflagf&flag=0&id=' . $featured_deals['featured_deals_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
								} else {
									echo '<a href="' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page='.$_GET['page'].'&action=setflagf&flag=1&id=' . $featured_deals['featured_deals_id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
								}?>
							</td>
                <td class="dataTableContent" align="right">
				<?php 
				if (isset($sInfo) && is_object($sInfo) && ($featured_deals['featured_deals_id'] == $sInfo->featured_deals_id)) {
					echo '<a href="javascript:void(0)" onclick="document.location.href=\'' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->featured_deals_id . '&action=edit') . '\'">'.tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '').'</a>	'; 
				} else { 
					echo '<a href="' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $featured_deals['featured_deals_id']) . '" onclick="document.location.href=\'' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $featured_deals['featured_deals_id']) . '\'">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; 
				} 
				?>&nbsp;
                </td>
      </tr>
      <?php
	  if($total_featured_orders>0){
	  ?>
      <tr>
      	<td colspan="6" class="dataTableContent">
        	<div id="featured_orders_info_<?php echo $featured_deals['featured_deals_id']; ?>" style="display:none">
            <table width="100%" cellpadding="2" style="border:1px solid #006666" cellspacing="2" bgcolor="#ddeeeb">
				<tr style="background-color:#006666; color:#FFFFFF;" class="dataTableContent">
                	<td><b>#Order ID</b></td>
                    <td><b>Purchase Amount</b></td>
                    <td><b>No. of People</b></td>
                </tr>
            <?php
				//echo 'here here here';
				$featured_orders_info_sql = tep_db_query("select products_room_info, total_room_adult_child_info, orders_id, final_price from ".TABLE_ORDERS_PRODUCTS." where is_diy_tours_book = '2' and group_buy_discount > 0 and products_id = '".$featured_deals['products_id']."' group by orders_id");		
				while($featured_orders_info = tep_db_fetch_array($featured_orders_info_sql)){
				?>
                <tr class="dataTableContent">
                	<td><?php echo '<a href="edit_orders.php?action=edit&oID='.$featured_orders_info['orders_id'].'" target="_blank"><strong>'.$featured_orders_info['orders_id'].'</strong></a> '?></td>
                    <td>$<?php echo $featured_orders_info['final_price']; ?></td>
                    <td>
                    <?php
					$featured_orders_info['total_room_adult_child_info'];
					$total_rooms = get_total_room_from_str($featured_orders_info['total_room_adult_child_info']);
					$total_guest = 0;
					if($total_rooms > 0){
						for($t=1;$t<=$total_rooms;$t++){
						 $chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($featured_orders_info['total_room_adult_child_info'],$t);
						 $total_guest = $total_guest + $chaild_adult_no_arr[0];
						 $total_guest = $total_guest + $chaild_adult_no_arr[1];
						}
					}else{
						$chaild_adult_no_arr = tep_get_room_adult_child_persion_on_room_str($featured_orders_info['total_room_adult_child_info'], 1);
						$total_guest = $chaild_adult_no_arr[0] + $chaild_adult_no_arr[1];
					}
					echo $total_guest;
					?>
                    </td>
                </tr>
                <?php		
				}
			?>
            </table>
            </div>
        </td>
      </tr>
      <?php
	  }	
	  ?>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $featured_deals_split->display_count($featured_deals_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></td>
                    <td class="smallText" align="right"><?php echo $featured_deals_split->display_links($featured_deals_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?></td>
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
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_SPECIALS . '</b>');

      $contents = array('form' => tep_draw_form('featured_deals', FILENAME_FEATURED_GROUP_DEAL, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->featured_deals_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $sInfo->products_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->featured_deals_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($sInfo)) {
        $heading[] = array('text' => '<b>' . $sInfo->products_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->featured_deals_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_FEATURED_GROUP_DEAL, 'page=' . $HTTP_GET_VARS['page'] . '&sID=' . $sInfo->featured_deals_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($sInfo->featured_deals_date_added));
        $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($sInfo->featured_deals_last_modified));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_info_image($sInfo->products_image, $sInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));
        $contents[] = array('text' => '<br>' . TEXT_INFO_ORIGINAL_PRICE . ' ' . $currencies->format($sInfo->products_price));
        $contents[] = array('text' => '' . TEXT_INFO_NEW_PRICE . ' ' . $currencies->format($sInfo->featured_deals_new_products_price));
        $contents[] = array('text' => '' . TEXT_INFO_PERCENTAGE . ' ' . number_format((100 * ($sInfo->products_price - $sInfo->featured_deals_new_products_price))/($sInfo->products_price * ($sInfo->products_margin/100))) . '% on GP');

        $contents[] = array('text' => '<br>' . TEXT_INFO_EXPIRES_DATE . ' <b>' . tep_date_short($sInfo->expires_date) . '</b>');
        $contents[] = array('text' => '' . TEXT_INFO_STATUS_CHANGE . ' ' . tep_date_short($sInfo->date_status_change));
      }
      break;
  }
  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
}
?>
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
</body>
</html>
<script type="text/javascript">
function change_tour_discounts(val){
	for(i=0;i<document.new_special.products_id.options.length;i++){
		if(document.new_special.products_id.options[i].value == val){
			var tour_data_id = document.new_special.products_id.options[i].id;			
			var tour_data = tour_data_id.split("##");
			var tour_gp = tour_data[1]*(tour_data[2]/100);
			document.getElementById('show_gp').innerHTML = '(GP = $'+tour_data[1]+'*'+tour_data[2]+'%&nbsp;=&nbsp;$'+tour_gp.toFixed(2)+')';
			document.new_special.tour_gp_1.value = tour_gp.toFixed(2);
			document.new_special.tour_gp_2.value = tour_gp.toFixed(2);
			document.new_special.tour_gp_3.value = tour_gp.toFixed(2);
			document.new_special.discount_amt_1.value = (tour_gp*(document.new_special.discount_percent_1.value/100)).toFixed(2);
			document.new_special.discount_amt_2.value = (tour_gp*(document.new_special.discount_percent_2.value/100)).toFixed(2);
			document.new_special.discount_amt_3.value = (tour_gp*(document.new_special.discount_percent_3.value/100)).toFixed(2);
			document.new_special.adj_price_1.value = (tour_data[1]-document.new_special.discount_amt_1.value).toFixed(2);
			document.new_special.adj_price_2.value = (tour_data[1]-document.new_special.discount_amt_2.value).toFixed(2);
			document.new_special.adj_price_3.value = (tour_data[1]-document.new_special.discount_amt_3.value).toFixed(2);
		}
	}
}
function change_percent(counter, val){
	document.new_special.elements['discount_amt_'+counter].value = (document.new_special.elements['tour_gp_'+counter].value*(val/100)).toFixed(2);
	document.new_special.elements['adj_price_'+counter].value = (document.new_special.products_price.value - document.new_special.elements['discount_amt_'+counter].value).toFixed(2);
}
</script>
<?php
if(isset($keep_selected_product_id) && tep_not_null($keep_selected_product_id)){
	?>
	<script type="text/javascript">
	change_tour_discounts(<?php echo $keep_selected_product_id; ?>);
	</script>				
	<?php
}

?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>