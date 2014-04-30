<?php
/*
  $Id: reports.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reports_product //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_PRODUCT_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, 'selected_box=reports_product'));

  if ($selected_box == 'reports_product' || $menu_dhtml == true) {
      $contents[] = array('text'  =>
//Admin begin
                                   tep_admin_files_boxes(FILENAME_STATS_PRODUCTS_VIEWED, BOX_REPORTS_PRODUCTS_VIEWED) .
                                   tep_admin_files_boxes(FILENAME_STATS_PRODUCTS_PURCHASED, BOX_REPORTS_PRODUCTS_PURCHASED) .
								   tep_admin_files_boxes(FILENAME_STATS_INVOICE_AMOUNT_MISMATCH, BOX_REPORTS_INVOICE_AMOUNT_MISMATCH).	
								   tep_admin_files_boxes(FILENAME_STATS_WITHOUT_INVOICE_AMOUNT, BOX_REPORTS_WITHOUT_INVOICE_AMOUNT).	
								   tep_admin_files_boxes(FILENAME_STATS_INVOICE_ORDER_MATCH, 'Invoice/Order Match').
								   tep_admin_files_boxes(FILENAME_TOUR_GROSS_PROFIT_REPORT, BOX_REPORT_GROSS_PROFIT).
					  			   tep_admin_files_boxes(FILENAME_TOUR_GROSS_PROFIT_MISMATCH, BOX_REPORT_GROSS_PROFIT_MISMATCH).
					    		   tep_admin_files_boxes(FILENAME_STATS_ORDER_ITEM_WITHOUT_AMOUNT, BOX_REPORT_ORDERS_TOURS_WITHOUT_COST).	
								   tep_admin_files_boxes(FILENAME_REPORT_TRAVELERS_BY_PROVIDER, BOX_REPORT_TRAVELERS_BY_PROVIDER)
						);
//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- reports_product //-->
