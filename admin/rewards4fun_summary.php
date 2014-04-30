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
  $where_date = '';
  if(strlen($_GET['start_date'])==10 || strlen($_GET['end_date'])==10 ){
	  if(strlen($_GET['start_date'])==10){
		$where_date .= ' AND date_added >="'.$_GET['start_date'].' 00:00:00'.'" ';
		$where_date_customers .= ' AND customers_info_date_account_created >="'.$_GET['start_date'].' 00:00:00'.'" ';
	  }
	  if(strlen($_GET['end_date'])==10){
		$where_date .= ' AND date_added <="'.$_GET['end_date'].' 23:59:59'.'" ';
		$where_date_customers .= ' AND customers_info_date_account_created <="'.$_GET['end_date'].' 23:59:59'.'" ';
	  }
	  
  		$total_customers_with_points_str = "select count(customers_id) as total_customers_with_points from " . TABLE_CUSTOMERS . ", `customers_info`   where customers_shopping_points > 0 AND customers_info_id=customers_id ".$where_date_customers;
		$total_customers_with_points_query = tep_db_query($total_customers_with_points_str);
		//echo $total_customers_with_points_str;
		
  }else{
  		$total_customers_with_points_query = tep_db_query("select count(customers_id) as total_customers_with_points from " . TABLE_CUSTOMERS . " where customers_shopping_points > 0 ");
  }
    
  $row_total_customers_with_points = tep_db_fetch_array($total_customers_with_points_query);
  $total_customers_with_points = $row_total_customers_with_points['total_customers_with_points'];
  
  $total_points_earned_query = tep_db_query("select sum(points_pending) as total_points_earned from ".TABLE_CUSTOMERS_POINTS_PENDING." where points_status=2".$where_date);
  $row_total_points_earned = tep_db_fetch_array($total_points_earned_query);
  $total_points_earned = $row_total_points_earned['total_points_earned'];
  
  $total_points_redeemed_query = tep_db_query("select count(cp.orders_id) as total_sale_using_points,sum(value) as total_sale_value, sum(cp.points_pending) as total_points_redeemed from ".TABLE_CUSTOMERS_POINTS_PENDING." as cp, ".TABLE_ORDERS_TOTAL." as ot where cp.orders_id=ot.orders_id and ot.class='ot_total' and cp.points_status=4 and cp.points_type='SP'".$where_date);
  $row_total_points_redeemed = tep_db_fetch_array($total_points_redeemed_query);
  $total_points_redeemed = $row_total_points_redeemed['total_points_redeemed'];
  $total_sale_using_points = $row_total_points_redeemed['total_sale_using_points'];
  $total_sales_value_using_points = $row_total_points_redeemed['total_sale_value'];
  
  $total_actions_taken_query = tep_db_query("select unique_id from ".TABLE_CUSTOMERS_POINTS_PENDING." WHERE unique_id > 0 ".$where_date);
  $total_actions_taken = tep_db_num_rows($total_actions_taken_query);
  
  //结伴同游置顶积分数据分析
  $total_points_bbs_top_redeemed_query = tep_db_query("select sum(cp.points_pending) as total_points_redeemed from ".TABLE_CUSTOMERS_POINTS_PENDING." cp where cp.points_status=4 and cp.points_type='TP'".$where_date);
  $total_points_bbs_top_redeemed_row = tep_db_fetch_array($total_points_bbs_top_redeemed_query);
  $total_points_bbs_top_redeemed = $total_points_bbs_top_redeemed_row['total_points_redeemed'];
  
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

<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript">
<!--

//var Qdate_start = new ctlSpiffyCalendarBox("Qdate_start", "Search", "start_date","btnDate1","<?php echo ($start_date); ?>",scBTNMODE_CUSTOMBLUE);
//var Qdate_end = new ctlSpiffyCalendarBox("Qdate_end", "Search", "end_date","btnDate2","<?php echo ($end_date); ?>",scBTNMODE_CUSTOMBLUE);

//-->
</script>
<div id="spiffycalendar" class="text"></div>

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
            <td><table width="90%" border="0" cellpadding="4" cellspacing="2" class="dataTableContent">
              <center>
                <tr>
                  <td colspan="4" align="center" class="dataTableContent">
				<?php

			  	echo tep_draw_form('Search', 'rewards4fun_summary.php','','get');
				echo '<table border="0" cellspacing="0" cellpadding="0">';
				echo '<tr><td class="infoBoxContent">date:&nbsp;&nbsp;</td><td class="infoBoxContent">
				<table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td nowrap class="main">&nbsp;' . tep_draw_input_field('start_date', $start_date, ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"') . '<script language="javascript">//Qdate_start.writeControl(); Qdate_start.dateFormat="yyyy-MM-dd";</script></td>
                          <td class="main">&nbsp;至&nbsp;</td>
                          <td nowrap class="main">' . tep_draw_input_field('end_date', $end_date, ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"') . '<script language="javascript">//Qdate_end.writeControl(); Qdate_end.dateFormat="yyyy-MM-dd";</script></td>
                        </tr>
						<tr>
						<td>
						<input type="button" name="Submit" value="Submit" onClick="this.form.submit();">
						</td>
						<td>&nbsp;
						</td>
						</tr>
                      </table>
				</td></tr>';
			    echo '<tr><td>&nbsp;</td><td id="Statistical_Report">&nbsp;</td></tr>';
			    echo '</table>';
				echo '</form>';
				?>				  </td>
                  </tr>
                <tr>
                  <td width="45%" align="right" class="dataTableContent"><?php echo TOTAL_CUSTOMERS_WITH_POINTS; ?></td>
                  <td width="5%" class="dataTableContent"><?php echo $total_customers_with_points; ?></td>
                  <td width="45%" align="right" class="dataTableContent"><?php echo TOTAL_ACTIONS_TAKEN; ?></td>
                  <td width="5%" class="dataTableContent"><?php echo $total_actions_taken; ?></td>
                </tr>
                <tr>
                  <td width="45%" align="right" class="dataTableContent"><?php echo TOTAL_POINTS_EARNED; ?></td>
                  <td width="5%" class="dataTableContent">
				  <?php echo round($total_points_earned); ?>				  </td>
                  <td width="45%" align="right" class="dataTableContent"><?php echo TOTAL_POINTS_REDEEMED; ?></td>
                  <td width="5%" class="dataTableContent">
				  <?php
				  //兑换的积分总数
				  $total_points_redeemed_round = round($total_points_redeemed * (-1));
				  echo $total_points_redeemed_round;
				  ?>				  </td>
                </tr>
                <tr>
                  <td width="45%" align="right" class="dataTableContent"><?php echo TOTAL_SALES_USING_POINTS; ?></td>
                  <td width="5%" class="dataTableContent"><?php echo $total_sale_using_points;?></td>
				  <td width="45%" align="right" class="dataTableContent"><?php echo TOTAL_VALUE_OF_SALES_USING_POINTS; ?></td>
                  <td width="5%" class="dataTableContent"><?php echo $currencies->display_price($total_sales_value_using_points, ''); ?></td>
                </tr>
                <tr>
                  <td align="right" class="dataTableContent">兑换的积分总金额:</td>
                  <td class="dataTableContent"><?php echo $currencies->display_price(round($total_points_redeemed_round*REDEEM_POINT_VALUE*100)/100, '');?></td>
                  <td align="right" class="dataTableContent">订单积分兑换率:</td>
                  <td class="dataTableContent">
				  <?php
				  echo (round($total_points_redeemed_round/max($total_points_earned, 1) *10000)/100) .'%';
				  ?>				  </td>
                </tr>
                <tr>
                  <td align="right" class="dataTableContent">&nbsp;</td>
                  <td class="dataTableContent">&nbsp;</td>
                  <td align="right" class="dataTableContent">&nbsp;</td>
                  <td class="dataTableContent">&nbsp;</td>
                </tr>
                <tr>
                  <td align="right" class="dataTableContent">结伴同游同帖子置顶所用积分数:</td>
                  <td class="dataTableContent">
				  <?php
				  $total_points_bbs_top_redeemed_round = round($total_points_bbs_top_redeemed * (-1));
				  echo $total_points_bbs_top_redeemed_round;
				  ?>
				  </td>
                  <td align="right" class="dataTableContent">结伴同游同帖子置顶所用积分兑换率:</td>
                  <td class="dataTableContent">
				  <?php
				  echo (round($total_points_bbs_top_redeemed_round/max($total_points_earned, 1) *10000)/100) .'%';
				  ?></td>
                </tr>
                
                <tr>
                  <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
              </center>
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>
