<?php
/*
  $Id: stats_products_purchased.php,v 1.1.1.1 2004/03/04 23:38:59 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('tour_gross_profit_report');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
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
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>

<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<div id="spiffycalendar" class="text"></div>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('tour_gross_profit_report');
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
			
								<table width="99%"  cellpadding="2" cellspacing="0" border="0">
									
									<tr>
										<td colspan="2">
											<?php 
												echo tep_draw_form('tourcode_search', FILENAME_TOUR_GROSS_PROFIT_REPORT, '', 'get');
											?>
											<table align="left" cellpadding="2" cellspacing="2" border="0">
												
												<tr>
												<td class="smallText" width="10%" ><?php echo HEADING_TITLE_PROVIDER; ?></td>
												<td class="smallText" align="left"><?php // echo tep_draw_form('search', FILENAME_TOUR_GROSS_PROFIT_REPORT, '', 'get'); ?>
												<?php
												 echo tep_draw_hidden_field('action', 'view_report');
												// echo tep_draw_hidden_field('month', $_GET['month']);
												 
												 $provider_array = array(array('id' => '', 'text' => TEXT_NONE));
												 $provider_query = tep_db_query("select agency_id,agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name");
												  while($provider_result = tep_db_fetch_array($provider_query))
												  {
												  $provider_array[] = array('id' => $provider_result['agency_id'],
																			 'text' => $provider_result['agency_name']);
												  }
												?>
												<?php echo tep_draw_pull_down_menu('provider', $provider_array, $_GET['provider'], 'style="width:200px; " '); //onChange="this.form.submit();" ?>
												<?php
														//echo '</form>';
												?>
												</td>
											</tr>
									
												<!--
												<tr>
													<td class="smallText" width="31%" valign="top">Tour Code:</td>
													<td class="smallText" align="left">
														<?php 
															//echo tep_draw_input_field('tourcode',$_GET['tourcode']);
														?>		
													</td>
												</tr>
												-->
												<tr>
									<td class="smallText" nowrap="nowrap">Order Date Start From:</td>
								<script type="text/javascript"><!--
//var order_date_purchased_start = new ctlSpiffyCalendarBox("order_date_purchased_start", "tourcode_search", "order_date_purchased_from","btnDate3","<?php echo tep_get_date_disp($_GET['order_date_purchased_from']); ?>",scBTNMODE_CUSTOMBLUE);
//var order_date_purchased_end = new ctlSpiffyCalendarBox("order_date_purchased_end", "tourcode_search", "order_date_purchased_to","btnDate4","<?php //echo tep_get_date_disp($_GET['order_date_purchased_to']); ?>",scBTNMODE_CUSTOMBLUE);
//-->
</script>
										<td class="smallText" >
										<?php echo tep_draw_input_field('order_date_purchased_from', tep_get_date_disp($_GET['order_date_purchased_from']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
										<script type="text/javascript">//order_date_purchased_start.writeControl(); order_date_purchased_start.dateFormat="MM/dd/yyyy";</script> 
											<script type="text/javascript">//To: order_date_purchased_end.writeControl(); order_date_purchased_end.dateFormat="MM/dd/yyyy";</script>
										</td>
									</tr>
									<tr>
									<td></td>
									<td>
														&nbsp;<?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH).'&nbsp;'; ?>									
													</td>
									</tr>				
									<tr>
											</table>
											<?php echo '</form>'; ?>											
										</td>
										
									</tr>
									
									
								</table>
								
							</td>
						</tr>
						
						</table>
			<table border="0" width="100%" cellspacing="0" cellpadding="3">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?><br />&nbsp;</td>
                <td class="dataTableHeadingContent">
					<?php echo TABLE_HEADING_PRODUCTS; ?>
					<br /><?php echo '<a href="' . tep_href_link(FILENAME_TOUR_GROSS_PROFIT_REPORT, tep_get_all_get_params(array('page','sort','order')).'sort=products_name&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_TOUR_GROSS_PROFIT_REPORT, tep_get_all_get_params(array('page','sort','order')).'sort=products_name&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
				<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PROV_TOURCODE; //echo TABLE_HEADING_MODEL; ?>
				 <br />
				 <?php  echo '<a href="' . tep_href_link(FILENAME_TOUR_GROSS_PROFIT_REPORT, tep_get_all_get_params(array('page','sort','order')).'sort=provider_tour_code&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_TOUR_GROSS_PROFIT_REPORT, tep_get_all_get_params(array('page','sort','order')).'sort=provider_tour_code&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				 <?php // echo '<a href="' . tep_href_link(FILENAME_TOUR_GROSS_PROFIT_REPORT, tep_get_all_get_params(array('page','sort','order')).'sort=products_model&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_TOUR_GROSS_PROFIT_REPORT, tep_get_all_get_params(array('page','sort','order')).'sort=products_model&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				 </td>
				<td class="dataTableHeadingContent" width="230"><?php echo TABLE_HEADING_AGENCY_NAME; ?><br /><?php echo '<a href="' . tep_href_link(FILENAME_TOUR_GROSS_PROFIT_REPORT, tep_get_all_get_params(array('page','sort','order')).'sort=agency_name&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_TOUR_GROSS_PROFIT_REPORT, tep_get_all_get_params(array('page','sort','order')).'sort=agency_name&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PURCHASED; ?>&nbsp;
				<br /><?php echo '<a href="' . tep_href_link(FILENAME_TOUR_GROSS_PROFIT_REPORT, tep_get_all_get_params(array('page','sort','order')).'sort=quantitysum&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_TOUR_GROSS_PROFIT_REPORT, tep_get_all_get_params(array('page','sort','order')).'sort=quantitysum&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?></td>
				<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_GROSS; ?>&nbsp;&nbsp;&nbsp;&nbsp;
				<br /><?php echo '<a href="' . tep_href_link(FILENAME_TOUR_GROSS_PROFIT_REPORT, tep_get_all_get_params(array('page','sort','order')).'sort=gross&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_TOUR_GROSS_PROFIT_REPORT, tep_get_all_get_params(array('page','sort','order')).'sort=gross&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?></td>
				<?php
				if($access_full_edit == 'true' || $allow_cost_gp_on_other_reports == true){
				?>
				<td class="dataTableHeadingContent" align="right">Gross Profit</td>
				<td class="dataTableHeadingContent" align="right">Gross Profit<br>(%)</td>
				<?php
				}
				?>
              </tr>
<?php
require(DIR_WS_CLASSES.'split_page_results_outer.php');

  if (isset($HTTP_GET_VARS['page']) && ($HTTP_GET_VARS['page'] > 1)) $rows = $HTTP_GET_VARS['page'] * MAX_DISPLAY_SEARCH_RESULTS_ADMIN - MAX_DISPLAY_SEARCH_RESULTS_ADMIN;
 // $products_query_raw = "select p.products_id, p.products_ordered, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . $languages_id. "' and p.products_ordered > 0 group by pd.products_id order by p.products_ordered DESC, pd.products_name";
 
 	if($_GET["sort"] == 'products_name') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'op.products_name asc ';
	  } else {
			$sortorder .= 'op.products_name desc ';
	  }
	}
	else if($_GET["sort"] == 'products_model') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'op.products_model asc ';
	  } else {
			$sortorder .= 'op.products_model desc ';
	  }
	}
	else if($_GET["sort"] == 'provider_tour_code') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'p.provider_tour_code asc ';
	  } else {
			$sortorder .= 'p.provider_tour_code desc ';
	  }
	}
	else if($_GET["sort"] == 'quantitysum') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'quantitysum asc ';
	  } else {
			$sortorder .= 'quantitysum desc ';
	  }
	}
	else if($_GET["sort"] == 'gross') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'gross asc ';
	  } else {
			$sortorder .= 'gross desc ';
	  }
	}
	else if($_GET["sort"] == 'agency_name') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'agency_name asc ';
	  } else {
			$sortorder .= 'agency_name desc ';
	  }
	}
	else
	{
		$sortorder .= 'quantitysum DESC, op.products_model';
	}
	
	
	if(isset($_GET['provider']) && $_GET['provider'] != '')
	  {
		$where .= " and p.agency_id='".$_GET['provider']."'";
	  }
						  
	if(isset($_GET['tourcode']) && $_GET['tourcode'] != '')
	  {
		$where .= " and op.products_model like '%".$_GET['tourcode']."%'";
	  }
						  
	if ((isset($HTTP_GET_VARS['order_date_purchased_from']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (isset($HTTP_GET_VARS['order_date_purchased_to']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) 
						  {
						  
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_from']). " 00:00:00";					 
						  $make_end_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_to']). " 00:00:00";
						  $where .= " and  o.date_purchased >= '" . $make_start_date . "' and o.date_purchased <= '" . $make_end_date . "' ";
						  
						  }
						  else if ((isset($HTTP_GET_VARS['order_date_purchased_from']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (!isset($HTTP_GET_VARS['order_date_purchased_to']) or !tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) {
							
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_from']) . " 00:00:00";
						  
						  $where .= " and  o.date_purchased >= '" . $make_start_date . "' ";
						  
						  
						  }
						  else if ((!isset($HTTP_GET_VARS['order_date_purchased_from']) or !tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (isset($HTTP_GET_VARS['order_date_purchased_to']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) {
							
						 $make_end_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_to']) . " 00:00:00";
						  
						 $where .= " and  o.date_purchased <= '" . $make_end_date . "' ";
						 
						 }
						 
						 
  $products_query_raw = "select o.orders_status, op.products_id, p.agency_id, p.provider_tour_code, ta.agency_name, op.products_model, op.products_name, sum(op.products_quantity) as quantitysum, sum(op.final_price*op.products_quantity)as gross, sum(op.final_price) as final_price_sum, sum(op.final_price_cost) as final_price_cost_sum FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p, ".TABLE_TRAVEL_AGENCY." as ta WHERE o.orders_id = op.orders_id and op.products_id = p.products_id and p.agency_id = ta.agency_id ".$where." and o.orders_status<>6 and o.orders_status<>100005 group by op.products_id order by ".$sortorder."";
 
  $products_split = new splitPageResults1($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $products_query_raw, $products_query_numrows);

  $rows = 0;
  $products_query = tep_db_query($products_query_raw);
  while ($products = tep_db_fetch_array($products_query)) {
    $rows++;
		$gross_profit = sprintf('%01.2f', ($products['final_price_sum'] - $products['final_price_cost_sum']));
		if($products['final_price_sum'] != 0){
		  $margin = tep_round((((($products['final_price_sum'])-($products['final_price_cost_sum']))/($products['final_price_sum']))*100), 0);
		}else{
		  $margin = 0;
		}
    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
              <tr class="dataTableRow"  >
              <td class="dataTableContent"><?php if(isset($_GET['page']) && $_GET['page']>1) { echo $rows+(($HTTP_GET_VARS['page']-1)*MAX_DISPLAY_SEARCH_RESULTS_ADMIN); }else{ echo $rows; } ?>.</td>
              <td class="dataTableContent"><?php echo '<a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products['products_id']) . '">' . $products['products_name'].'</a>';	?>			  </td>
              <td class="dataTableContent">
			  <?php 
			  //echo '<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($products['products_id']) . '&pID=' . $products['products_id'].'&action=new_product') . '">' .$products['products_model']. '</a>';
			  
			  echo '<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . tep_get_products_catagory_id($products['products_id']) . '&pID=' . $products['products_id'].'&action=new_product') . '">' .$products['provider_tour_code']. '</a>';
				?>&nbsp;</td>
				<td class="dataTableContent">
			  		<?php 
						echo $products['agency_name'];
						//$agency_name_query = ""
					?>&nbsp;
				</td>
			  <td class="dataTableContent" align="center"><?php echo $products['quantitysum']; ?>&nbsp;</td>
              <td class="dataTableContent" align="right">$<?php echo sprintf('%01.2f', $products['gross']); ?>&nbsp;</td>
			  <?php
				if($access_full_edit == 'true' || $allow_cost_gp_on_other_reports == true){
			  ?>
			  <td class="dataTableContent" align="right">$<?= $gross_profit;?></td>
			  <td class="dataTableContent" align="right"><?php echo $margin . '%';?></td>
			  <?php
			    }
			  ?>
              </tr>
<?php
  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo  $products_split->display_count1($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $products_split->display_links1($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'],tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?>&nbsp;</td>
              </tr>
            </table></td>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>