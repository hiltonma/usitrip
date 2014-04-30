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
  	$remark = new Remark('stats_sales_by_category');
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

<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>
<div id="spiffycalendar" class="text"></div>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('stats_sales_by_category');
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
			<table width="99%" cellpadding="2" cellspacing="0" border="0">
				<?php  echo tep_draw_form('search', FILENAME_STATS_SALES_BY_CATEGORY, '', 'get'); ?>
				<?php /*?><tr>
							<td class="smallText" width="10%" ><?php echo TABLE_HEADING_CATEGORY_SELECT; ?>:</td>
							<td class="smallText" align="left">
							<?php
							 echo tep_draw_hidden_field('action', 'view_report');
							 echo tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id); //onChange="this.form.submit();" ?>
							
							</td>
				</tr><?php */?>
				<tr>
				<?php
				if(!isset($HTTP_GET_VARS['action']) && !isset($HTTP_GET_VARS['order_date_purchased_from']) && !isset($HTTP_GET_VARS['order_date_purchased_to'])){		
	  $_GET['order_date_purchased_from'] = $HTTP_GET_VARS['order_date_purchased_from'] = date('m/01/Y');
	}
				?>
				
				<script type="text/javascript">
//var order_date_purchased_start = new ctlSpiffyCalendarBox("order_date_purchased_start", "search", "order_date_purchased_from","btnDate3","<?php echo tep_get_date_disp($_GET['order_date_purchased_from']); ?>",scBTNMODE_CUSTOMBLUE);
//var order_date_purchased_end = new ctlSpiffyCalendarBox("order_date_purchased_end", "search", "order_date_purchased_to","btnDate4","<?php echo tep_get_date_disp($_GET['order_date_purchased_to']); ?>",scBTNMODE_CUSTOMBLUE);
</script>
				<?php //echo tep_draw_form('search', FILENAME_TOUR_GROSS_PROFIT_REPORT, '', 'get'); ?>
					<td class="smallText" width="10%" >Order Date Start</td>
					<td class="smallText" >From:
					<?php echo tep_draw_input_field('order_date_purchased_from', tep_get_date_disp($_GET['order_date_purchased_from']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
					<script type="text/javascript">//order_date_purchased_start.writeControl(); order_date_purchased_start.dateFormat="MM/dd/yyyy";</script> To:
					<?php echo tep_draw_input_field('order_date_purchased_to', tep_get_date_disp($_GET['order_date_purchased_to']), ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1);GeCalendar.OutPutType=\'m/d/y\'; GeCalendar.SetDate(this);"');?>
						<script type="text/javascript">//order_date_purchased_end.writeControl(); order_date_purchased_end.dateFormat="MM/dd/yyyy";</script>
					</td>
				</tr>
				<tr>
					<td class="smallText" nowrap="nowrap" ><?php echo 'Filter By Tour/Package'; ?>:</td>
					<td class="smallText" align="left">
					<?php
					$tour_package_filetr_array = array(array('id' => '', 'text' => 'All'));
					$tour_package_filetr_array[] = array('id' => '0', 'text' => 'Tours');
					$tour_package_filetr_array[] = array('id' => '1', 'text' => 'Package');
					?>
					<?php echo tep_draw_pull_down_menu('tour_pack_filter', $tour_package_filetr_array, $_GET['tour_pack_filter'], 'style="width:200px; "'); //onChange="this.form.submit();" ?>
					
					</td>
				</tr>			
				<tr>
					<td></td><td>&nbsp;<?php echo tep_image_submit('button_search.gif', IMAGE_SEARCH).'&nbsp;'; ?></td>
				</tr>
				<?php echo '</form>'; ?>
			</table>			
			<table border="0" width="100%" cellspacing="0" cellpadding="3">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent" width="450">
					<?php echo TABLE_HEADING_CATEGORY; ?>
					<?php //echo '<a href="' . tep_href_link(FILENAME_STATS_SALES_BY_CATEGORY, tep_get_all_get_params(array('page','sort','order')).'sort=categories_name&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_SALES_BY_CATEGORY, tep_get_all_get_params(array('page','sort','order')).'sort=categories_name&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>				
              
				  <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PURCHASED; ?>&nbsp;
				<?php echo '<a href="' . tep_href_link(FILENAME_STATS_SALES_BY_CATEGORY, tep_get_all_get_params(array('page','sort','order')).'sort=quantitysum&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_SALES_BY_CATEGORY, tep_get_all_get_params(array('page','sort','order')).'sort=quantitysum&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?></td>
				<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_GROSS_PRICE; ?>
				<?php echo '<a href="' . tep_href_link(FILENAME_STATS_SALES_BY_CATEGORY, tep_get_all_get_params(array('page','sort','order')).'sort=gross_price&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_SALES_BY_CATEGORY, tep_get_all_get_params(array('page','sort','order')).'sort=gross_price&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?></td>
				<?php if($access_full_edit == 'true') { 	 ?>
				
				<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_GROSS; ?>
				<?php echo '<a href="' . tep_href_link(FILENAME_STATS_SALES_BY_CATEGORY, tep_get_all_get_params(array('page','sort','order')).'sort=gross&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_STATS_SALES_BY_CATEGORY, tep_get_all_get_params(array('page','sort','order')).'sort=gross&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?></td>
				<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_GROSS_PROFIT; ?></td>
				<td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_GROSS_PROFIT_PERCENTAGE; ?></td>
				<?php } ?>
              </tr>
<?php
require(DIR_WS_CLASSES.'split_page_results_outer.php');

 
 $sortorder = '';
	if($_GET["sort"] == 'categories_id') {
	   if($_GET["order"] == 'ascending') {
			$sortorder = ' p2c.categories_id asc ';
	  } else {
			$sortorder = ' p2c.categories_id desc ';
	  }
	}else if($_GET["sort"] == 'categories_name') {
	   if($_GET["order"] == 'categories_name') {
			$sortorder = ' cd.categories_name asc ';
	  } else {
			$sortorder = ' cd.categories_name desc ';
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
			$sortorder .= 'gross_cost asc ';
	  } else {
			$sortorder .= 'gross_cost desc ';
	  }
	}
	else if($_GET["sort"] == 'gross_price') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'gross_price asc ';
	  } else {
			$sortorder .= 'gross_price desc ';
	  }
	}
	else
	{
		//$sortorder .= 'quantitysum DESC';
		//$sortorder .= 'cd.categories_name';
		//$sortorder .= 'op.products_id';
		$sortorder .= 'quantitysum DESC';
	}
	
	$where_date .= " and (o.orders_status <> 6 and o.orders_status <> 100060 and o.orders_status <> 100055) ";	
	if(isset($_GET['categories_id']) && $_GET['categories_id'] != '0')
	  {
		$where_date .= " and p2c.categories_id='".$_GET['categories_id']."'";
	  }
	 if(isset($_GET['tour_pack_filter']) && $_GET['tour_pack_filter'] != '')
	  {
		$where_date .= " and p.products_vacation_package='".$_GET['tour_pack_filter']."'";
	  }
	  
	  if($_GET['order_date_purchased_from'] > date("Y-m-d")){
	  	$_GET['order_date_purchased_from'] = $HTTP_GET_VARS['order_date_purchased_from'] = date("Y-m-d");
	  }
	  if ((isset($HTTP_GET_VARS['order_date_purchased_from']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (isset($HTTP_GET_VARS['order_date_purchased_to']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) 
						  {
						  
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_from']). " 00:00:00";					 
						  $make_end_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_to']). " 23:59:59";
						  $where_date .= " and  o.date_purchased >= '" . $make_start_date . "' and o.date_purchased <= '" . $make_end_date . "' ";
						  
						  }
						  else if ((isset($HTTP_GET_VARS['order_date_purchased_from']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (!isset($HTTP_GET_VARS['order_date_purchased_to']) or !tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) {
							
						  $make_start_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_from']) . " 00:00:00";
						  
						  $where_date .= " and  o.date_purchased >= '" . $make_start_date . "' ";
						  
						  
						  }
						  else if ((!isset($HTTP_GET_VARS['order_date_purchased_from']) or !tep_not_null($HTTP_GET_VARS['order_date_purchased_from'])) && (isset($HTTP_GET_VARS['order_date_purchased_to']) && tep_not_null($HTTP_GET_VARS['order_date_purchased_to'])) ) {
							
						 $make_end_date = tep_get_date_db($HTTP_GET_VARS['order_date_purchased_to']) . " 23:59:59";
						  
						 $where_date .= " and  o.date_purchased <= '" . $make_end_date . "' ";
						 
						 }
						 
// $products_query_raw = "select count(DISTINCT(op.products_id)) as prod_count, p.products_id, p.agency_id, sum(op.products_quantity) as quantitysum,  sum(op.final_price_cost*op.products_quantity) as gross_cost, sum(op.final_price*op.products_quantity) as gross_price FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p WHERE o.orders_id = op.orders_id and op.products_id = p.products_id ".$where_date." group by op.products_id order by ".$sortorder."";//group by p.agency_id 
  
  $products_query_raw = "select count(DISTINCT(op.products_id)) as prod_count, p2c.categories_id, p.products_id, cd.categories_name,  op.products_name,sum(op.products_quantity) as quantitysum, sum(op.final_price_cost*op.products_quantity) as gross_cost, sum(op.final_price*op.products_quantity) as gross_price FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p, ".TABLE_PRODUCTS_TO_CATEGORIES." as p2c, ".TABLE_CATEGORIES_DESCRIPTION." as cd WHERE o.orders_id = op.orders_id and op.products_id = p.products_id and p2c.products_id = op.products_id and p2c.products_id = p.products_id and p2c.categories_id=cd.categories_id and p2c.categories_id in (select min(categories_id) from ".TABLE_PRODUCTS_TO_CATEGORIES." where products_id=op.products_id group by products_id order by products_id desc) and cd.language_id = '".$languages_id."' ".$where_date." group by p2c.categories_id order by ".$sortorder."";//group by p.agency_id 
 
  $products_query = tep_db_query($products_query_raw);
  $rows = 1;
  while($products = tep_db_fetch_array($products_query)){
  
    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
	$gross_profit = sprintf('%01.2f', ($products['gross_price'] - $products['gross_cost']));
	if($products['gross_price'] != 0){	
		$margin = tep_round((((($products['gross_price'])-($products['gross_cost']))/($products['gross_price']))*100), 0);
	}else{
		$margin = 0;
	}

  ?>
  <tr class="dataTableRow"  >
	  <td class="dataTableContent" nowrap="nowrap"><?php echo $rows; ?>.</td>
	  <td class="dataTableContent" width="450"><?php //echo tep_output_generated_category_path(tep_get_products_catagory_id($products['products_id']));
	  echo tep_output_generated_category_path($products['categories_id']);
	   //$products['categories_name'].'('.$products['prod_count'].')'.'<br>'. .'('.$products['prod_count'].')'?></td>
	  <td class="dataTableContent" align="right"><?php echo $products['quantitysum']; ?>&nbsp;</td>
	  <td class="dataTableContent" align="right">$<?php echo sprintf('%01.2f', $products['gross_price']); ?>&nbsp;</td>
	  <?php if($access_full_edit == 'true') { ?>	  
	  <td class="dataTableContent" align="right">$<?php echo sprintf('%01.2f', $products['gross_cost']); ?>&nbsp;</td>
	  <td class="dataTableContent" align="right">$<?php echo $gross_profit;?></td>
	  <td class="dataTableContent" align="right"><?php echo $margin . '%';?></td>
	  <?php } ?>
  </tr>
  <?php 
  $rows++;
}
?>
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