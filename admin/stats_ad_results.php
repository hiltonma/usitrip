<?php
/*
  $Id: stats_ad_results.php, v 2.3 2006/03/22
  
  Date range, sorting and number of sales added
  by mr_absinthe,  www.originalabsinthe.com

  osCommerce, Open Source E-Commerce Solutions

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
    if (isset($HTTP_GET_VARS['start_date'])) {
    $start_date = $HTTP_GET_VARS['start_date'];
  } else {
    $start_date = date('Y-m-01');
  }

  if (isset($HTTP_GET_VARS['end_date'])) {
    $end_date = $HTTP_GET_VARS['end_date'];
  } else {
    $end_date = date('Y-m-d');
  }
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
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script language="javascript"><!--

//var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "date_range", "start_date","btnDate","<?php echo $start_date; ?>",scBTNMODE_CUSTOMBLUE);
//var dateAvailable1 = new ctlSpiffyCalendarBox("dateAvailable1", "date_range", "end_date","btnDate1","<?php echo $end_date; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  if ($printable != 'on') {
  require(DIR_WS_INCLUDES . 'header.php');
  }; ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
  <?php 
   if ($printable != 'on') {;?>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
        </table>
		<?php }; ?>
		</td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>     
      <tr><td><table>
<tr><td></td><td class="main">
<?php
    echo tep_draw_form('date_range','stats_ad_results.php' , '', 'get');
    echo ENTRY_STARTDATE .  '&nbsp;'; //tep_draw_input_field('start_date', $start_date).
	?>
	<?php echo tep_draw_input_field('start_date', $start_date, ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
	<script language="javascript">//dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script>
	<?php
    echo '';
    echo ENTRY_TODATE .  '&nbsp;';//tep_draw_input_field('end_date', $end_date).
    ?>
	<?php echo tep_draw_input_field('end_date', $end_date, ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>
	<script language="javascript">//dateAvailable1.writeControl(); dateAvailable1.dateFormat="yyyy-MM-dd";</script>
	<?php
	echo ENTRY_PRINTABLE . tep_draw_checkbox_field('printable', $print). '&nbsp;';
    echo ENTRY_SORTVALUE . tep_draw_checkbox_field('total_value', $total_value). '&nbsp;&nbsp;';
    echo '<input type="submit" value="'. ENTRY_SUBMIT .'">';
    echo '</td></form>';

    $grand_total_value = 0;
    $total_number_sales = 0;
?>
</td></tr>
</table></td></tr>          
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
			<tr>
			<?php echo tep_draw_form('ad_results', FILENAME_STATS_AD_RESULTS, 'action=new_product_preview', 'post', 'enctype="multipart/form-data"'); ?>
          </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ADS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER_OF_SALES; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_AMOUNT; ?>&nbsp;</td>
              </tr>
<?php

 if ($total_value =='on') {
  $ad_query_raw = "select distinct orders.customers_advertiser, count(*) as count, sum(value) as total_value from " . TABLE_CUSTOMERS . ", " . TABLE_ORDERS . ", " . TABLE_ORDERS_TOTAL . " WHERE orders.customers_advertiser <> '' AND date_purchased BETWEEN '" . $start_date . "' AND '" . $end_date . " 23:59:59' AND customers.customers_id = orders.customers_id and orders.orders_id = orders_total.orders_id and class = 'ot_subtotal' group by orders.customers_advertiser ORDER BY total_value DESC";
  } else {
     $ad_query_raw = "select distinct orders.customers_advertiser, count(*) as count, sum(value) as total_value from " . TABLE_CUSTOMERS . ", " . TABLE_ORDERS . ", " . TABLE_ORDERS_TOTAL . " WHERE orders.customers_advertiser <> '' AND date_purchased BETWEEN '" . $start_date . "' AND '" . $end_date . " 23:59:59' AND customers.customers_id = orders.customers_id and orders.orders_id = orders_total.orders_id and class = 'ot_subtotal' group by orders.customers_advertiser ORDER BY orders.customers_advertiser";
   } 
	//echo $ad_query_raw;
  $products_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);

  $ad_query = tep_db_query($ad_query_raw);
  
  //echo tep_db_num_rows($ad_query) ;
  while ($ads = tep_db_fetch_array($ad_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
 
                <tr class="dataTableRow"  onmouseout="this.className='dataTableRow'">
                <td class="dataTableContent"><b><?php echo $rows; ?>.<b></td>
                <td class="dataTableContent"><b><?php echo $ads['customers_advertiser']; ?><b></td>
                <td class="dataTableContent"><b><?php echo $ads['count']; ?><b></td>
                <td class="dataTableContent" align="right"><b><?php echo $currencies->format($ads['total_value']); ?>&nbsp;</td>
                </tr>
				<!-- amit addded loop started  -->
				<?php 
				if ($total_value =='on') {
					 $sub_details_query_raw = "select  sum(value) as total_value, orders.orders_id from " . TABLE_CUSTOMERS . ", " . TABLE_ORDERS . ", " . TABLE_ORDERS_TOTAL . " WHERE orders.customers_advertiser <> '' AND date_purchased BETWEEN '" . $start_date . "' AND '" . $end_date . " 23:59:59' AND customers.customers_id = orders.customers_id and orders.orders_id = orders_total.orders_id and class = 'ot_subtotal' and date_purchased and orders.customers_advertiser ='".tep_db_input($ads['customers_advertiser']) ."' group by orders.orders_id ORDER BY total_value DESC";
				} else {
					 $sub_details_query_raw = "select  sum(value) as total_value, orders.orders_id from " . TABLE_CUSTOMERS . ", " . TABLE_ORDERS . ", " . TABLE_ORDERS_TOTAL . " WHERE orders.customers_advertiser <> '' AND date_purchased BETWEEN '" . $start_date . "' AND '" . $end_date . " 23:59:59' AND customers.customers_id = orders.customers_id and orders.orders_id = orders_total.orders_id and class = 'ot_subtotal' and date_purchased and orders.customers_advertiser ='".tep_db_input($ads['customers_advertiser']) ."' group by orders.orders_id";
				} 
					   
				$sub_details_query = tep_db_query($sub_details_query_raw);
 				while ($sub_details = tep_db_fetch_array($sub_details_query)){				
				?>				
							<tr>
							<td class="dataTableContent"></td>
							<td class="dataTableContent"></td>
							<td class="dataTableContent"><a target="_blank" href="orders.php?oID=<?=$sub_details['orders_id'];?>&action=edit"><?php echo  $sub_details['orders_id']; ?></a></td>
							<td class="dataTableContent" align="right"><?php echo $currencies->format($sub_details['total_value']); ?></td>
							</tr>
				<?php
				}
				?>			
				<!-- amit addded loop ended  -->
				<!--
				<tr>
                <td class="dataTableContent"></td>
                <td class="dataTableContent"></td>
                <td class="dataTableContent">Subtotal</td>
                <td class="dataTableContent" align="right"><b><?php // echo $currencies->format($ads['total_value']); ?>&nbsp;</td>
                </tr>
				-->
				
<?php
  $grand_total_value = $grand_total_value + $ads['total_value'];
  $total_number_sales = $total_number_sales + $ads['count'];
  }
?>

                <tr bgcolor="#F6F6F6">
                <td class="dataTableContent"><b><?php echo ENTRY_TOTAL; ?></b></td>
                <td class="dataTableContent"></td>
                <td class="dataTableContent"><b><?php echo $total_number_sales; ?></b></td>
                <td class="dataTableContent" align="right"><b><?php echo $currencies->format($grand_total_value); ?></b>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3">
			<!--- 
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?>&nbsp;</td>
              </tr>
            </table>
			 --->
			</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php
  if ($printable != 'on') {
   require(DIR_WS_INCLUDES . 'footer.php');
  }
?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>