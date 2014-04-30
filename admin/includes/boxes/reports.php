<?php
/*
  $Id: reports.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reports //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();
	$heading_reports_sales = array();
	$heading_reports_marketing = array();
	$heading_reports_product = array();
	$heading_reports_service = array();
	$heading_reports_payment = array();
	$heading_reports_accounting = array();
	$heading_reports_accountant = array();
	$heading_reports_csc = array();

  $heading[] = array('text'  => BOX_HEADING_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, 'selected_box=reports'));
	$heading_reports_sales[] = array('text'  => BOX_HEADING_SALES_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_SALES_REPORT, 'selected_box=reports_sales'));
	$heading_reports_marketing[] = array('text'  => BOX_HEADING_MARKETING_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_AD_RESULTS_DETAILS, 'selected_box=reports_marketing'));
	$heading_reports_product[] = array('text'  => BOX_HEADING_PRODUCT_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, 'selected_box=reports_product'));
	$heading_reports_service[] = array('text'  => BOX_HEADING_SERVICE_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_CUSTOMERS, 'selected_box=reports_service'));
	$heading_reports_payment[] = array('text'  => BOX_HEADING_PAYMENT_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_PAYMENT_ADJUSTED_ORDER_HISTORY, 'selected_box=reports_payment'));
	$heading_reports_accounting[] = array('text'  => BOX_HEADING_ACCOUNTING_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, 'selected_box=reports_accounting'));
	$heading_reports_accountant[] = array('text'  => BOX_HEADING_ACCOUNTANT_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_SALES_REPORT, 'selected_box=reports_accountant'));
	

  if ($selected_box == 'reports' || $menu_dhtml == true) {
      $contents[] = array('text'  =>
//Admin begin

                                   
								'<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED) . '" onclick="return false;" onmouseover="menuItemMouseover(event, \'reports_salesMenu\');">'.BOX_HEADING_SALES_REPORTS.'&nbsp;&nbsp;&raquo;&nbsp;</a>'.
								'<a href="' . tep_href_link(FILENAME_STATS_AD_RESULTS_DETAILS) . '" onclick="return false;" onmouseover="menuItemMouseover(event, \'reports_marketingMenu\');">'.BOX_HEADING_MARKETING_REPORTS.'&nbsp;&nbsp;&raquo;&nbsp;</a>'.
								'<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED) . '" onclick="return false;" onmouseover="menuItemMouseover(event, \'reports_productMenu\');">'.BOX_HEADING_PRODUCT_REPORTS.'&nbsp;&nbsp;&raquo;&nbsp;</a>'.
								'<a href="' . tep_href_link(FILENAME_STATS_CUSTOMERS) . '" onclick="return false;" onmouseover="menuItemMouseover(event, \'reports_serviceMenu\');">'.BOX_HEADING_SERVICE_REPORTS.'&nbsp;&nbsp;&raquo;&nbsp;</a>'.
								'<a href="' . tep_href_link(FILENAME_STATS_PAYMENT_ADJUSTED_ORDER_HISTORY) . '" onclick="return false;" onmouseover="menuItemMouseover(event, \'reports_paymentMenu\');">'.BOX_HEADING_PAYMENT_REPORTS.'&nbsp;&nbsp;&raquo;&nbsp;</a>'.
								'<a href="' . tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES) . '" onclick="return false;" onmouseover="menuItemMouseover(event, \'reports_accountingMenu\');">'.BOX_HEADING_ACCOUNTING_REPORTS.'&nbsp;&nbsp;&raquo;&nbsp;</a>'.
								'<a href="' . tep_href_link(FILENAME_STATS_SALES_REPORT) . '" onclick="return false;" onmouseover="menuItemMouseover(event, \'reports_accountantMenu\');">'.BOX_HEADING_ACCOUNTANT_REPORTS.'&nbsp;&nbsp;&raquo;&nbsp;</a>'
						
						);
//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
	foreach($heading_reports_sales as $item_menu){ 
		require(DIR_WS_BOXES. "reports_sales.php" ); 
	}
	foreach($heading_reports_marketing as $item_menu){ 
		require(DIR_WS_BOXES. "reports_marketing.php" ); 
	}
	foreach($heading_reports_product as $item_menu){ 
		require(DIR_WS_BOXES. "reports_product.php" ); 
	}
	foreach($heading_reports_service as $item_menu){ 
		require(DIR_WS_BOXES. "reports_service.php" ); 
	}
	foreach($heading_reports_payment as $item_menu){ 
		require(DIR_WS_BOXES. "reports_payment.php" ); 
	}
	foreach($heading_reports_accounting as $item_menu){ 
		require(DIR_WS_BOXES. "reports_accounting.php" ); 
	}
	foreach($heading_reports_accountant as $item_menu){ 
		require(DIR_WS_BOXES. "reports_accountant.php" ); 
	}
	foreach($heading_reports_csc as $item_menu){ 
		require(DIR_WS_BOXES. "reports_csc.php" ); 
	}
?>
<!--此两标签多余
            </td>
          </tr>
//-->
<!-- reports_eof //-->
