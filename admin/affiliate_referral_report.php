<?php
/*
  $Id: affiliate_statistics.php,v 1.1.1.1 2004/03/04 23:38:09 ccwjr Exp $

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

  $affiliate_banner_history_raw = "select sum(affiliate_banners_shown) as count from " . TABLE_AFFILIATE_BANNERS_HISTORY .  " where affiliate_banners_affiliate_id  = '" .  $HTTP_GET_VARS['acID'] . "'";
  $affiliate_banner_history_query = tep_db_query($affiliate_banner_history_raw);
  $affiliate_banner_history = tep_db_fetch_array($affiliate_banner_history_query);
  $affiliate_impressions = $affiliate_banner_history['count'];
  if ($affiliate_impressions == 0) $affiliate_impressions = "n/a"; 
  
  $affiliate_query = tep_db_query("select * from " . TABLE_AFFILIATE . " where affiliate_id ='" . $HTTP_GET_VARS['acID'] . "'");
 
  $affiliate = tep_db_fetch_array($affiliate_query);
  $affiliate_percent = 0;
  $affiliate_percent = $affiliate['affiliate_commission_percent'];
  if ($affiliate_percent < AFFILIATE_PERCENT) $affiliate_percent = AFFILIATE_PERCENT;
  
  $affiliate_clickthroughs_raw = "select count(*) as count from " . TABLE_AFFILIATE_CLICKTHROUGHS . " where affiliate_id = '" . $HTTP_GET_VARS['acID'] . "'";
  $affiliate_clickthroughs_query = tep_db_query($affiliate_clickthroughs_raw);
  $affiliate_clickthroughs = tep_db_fetch_array($affiliate_clickthroughs_query);
  $affiliate_clickthroughs = $affiliate_clickthroughs['count'];

  $affiliate_sales_raw = "
  select count(*) as count, sum(affiliate_value) as total, sum(affiliate_payment) as payment from
    " . TABLE_AFFILIATE_SALES . " a, 
    " . TABLE_ORDERS . " o 
    where a.affiliate_id = '" . $HTTP_GET_VARS['acID'] . "' and
    o.orders_id = a.affiliate_orders_id and
    o.orders_status >= " . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . "
    ";  $affiliate_sales_query = tep_db_query($affiliate_sales_raw);
  $affiliate_sales = tep_db_fetch_array($affiliate_sales_query);

  $affiliate_transactions=$affiliate_sales['count'];
  if ($affiliate_clickthroughs > 0) {
	  $affiliate_conversions = tep_round(($affiliate_transactions / $affiliate_clickthroughs)*100,2) . "%";
  } else {
    $affiliate_conversions = "n/a";
  }

  if ($affiliate_sales['total'] > 0) {
    $affiliate_average = $affiliate_sales['total'] / $affiliate_sales['count'];
  } else {
    $affiliate_average = 0;
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript"><!--
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_STATISTICS, tep_get_all_get_params(array('action','page'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
     
          <tr>
            <td><table width="100%" border="0" cellpadding="4" cellspacing="2" class="dataTableContent">
              <center>
               <tr>
                  <td colspan="4">&nbsp;</td>
                </tr>
				<!-- amit added to show refereal emails-->		
						  <tr>
							<td class="main" colspan="4"><b>Referral report</b></td>
						  </tr>
						  <tr>
							<td class="main" colspan="4">
							<table width="100%" cellpadding="2" cellspacing="2">
						   <tr class="infoboxheading">
							   <td align=left width="14%"  class="infoboxheading"><B>Referral Date</B></td>
							   <td align=left width="14%"  class="infoboxheading"><B>Email Sent</B></td>
							   <td align=left width="14%"  class="infoboxheading"><B>Signed Up</B></td>
							   <td align=left width="14%"  class="infoboxheading"><B>Made a Purchase</B></td>			 
						   </tr>
							<?php
							
								$customer_referral_query_row = 	"select * from " . TABLE_REBATES_REFERRALS_INFO . " where customers_id=".$HTTP_GET_VARS['acID']."  order by referrals_date desc" ;
								
								$affiliate_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_AFFILIATE_REFERRALS_REPORT, $customer_referral_query_row, $affiliate_query_numrows);
    							$customer_referral_query = tep_db_query($customer_referral_query_row); 
								while ($customer_referral = tep_db_fetch_array($customer_referral_query)) {
								
								$referrals_email = $customer_referral['referrals_email'];
								
								
								$check_customer_query = tep_db_query("select customers_id, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($referrals_email) . "' ");
								if (tep_db_num_rows($check_customer_query) > 0 ) {
									$issignup = 'Y';
									if ($customer_check_done = tep_db_fetch_array($check_customer_query)) {
									$refferalid = $customer_check_done['customers_id'];				
									}
									$select_purchase_query = tep_db_query("select o.orders_id from " . TABLE_ORDERS ." as o, " . TABLE_AFFILIATE_SALES . " as s where s.affiliate_orders_id = o.orders_id  and o.customers_id='".(int) $refferalid."'");
									if (tep_db_num_rows($select_purchase_query) > 0 ) {
									$ispurchase = 'Y';	
									}		
								}else{
								$issignup = 'N';
								$ispurchase = 'N';
								}
								
								
							?>			
								<TR class="main">
									<TD class="smallText" ><?php echo tep_date_short($customer_referral['referrals_date']); ?></TD>
									<TD class="smallText"><?php echo $customer_referral['referrals_email']; ?></TD>
									<TD class="smallText"><?php echo $issignup; ?></TD>                          
									<TD class="smallText"><?php echo $ispurchase; ?></TD>                          
								</TR>
							<?php 
								}
							 ?>
							</table>
							</td>
							</tr>		
					<!-- amit added to show refereal emails--> 
					<!-- amit added to show pagging link start -->
					<tr>
						<td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
						  <tr>
							<td class="smallText" valign="top"><?php echo $affiliate_split->display_count($affiliate_query_numrows, MAX_DISPLAY_AFFILIATE_REFERRALS_REPORT, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_AFFILIATES); ?></td>
							<td class="smallText" align="right"><?php echo $affiliate_split->display_links($affiliate_query_numrows, MAX_DISPLAY_AFFILIATE_REFERRALS_REPORT, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
						  </tr>
		
						</table></td>
					  </tr>
					<!-- amit added to show pagging link end -->		  
				<tr>
                  <td colspan="4">&nbsp;</td>
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
