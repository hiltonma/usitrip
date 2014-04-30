<?php
/*
  $Id: reports.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reports_accountant //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_ACCOUNTANT_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_SALES_REPORT, 'selected_box=reports_accountant'));

  if ($selected_box == 'reports_accountant' || $menu_dhtml == true) {
      $contents[] = array('text'  =>
//Admin begin
                                   tep_admin_files_boxes(FILENAME_STATS_SALES_REPORT, BOX_REPORTS_SALES_REPORT) .
								   tep_admin_files_boxes(FILENAME_STATS_SETTLEMENT_REPORT, BOX_REPORTS_SETTLEMENT).				   
								   tep_admin_files_boxes(FILENAME_STATS_UNCHARGED_REPORT, BOX_UNCHARGED_REPORT).					   
								   tep_admin_files_boxes(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW, BOX_REPORTS_PAID_PAYMENT_HISTORY).
								   tep_admin_files_boxes(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY, BOX_REPORTS_PAID_PAYMENT_HISTORY_OLD).
								   '<a href="' . tep_href_link(FILENAME_STATS_PAYABLE_REPORT, 'list_by=departure') . '" class="menuBoxContentLink">' . BOX_PAYABLE_REPORT_DEPARTURE . '</a><br>'.
								   tep_admin_files_boxes(FILENAME_STATS_PAYABLE_REPORT, BOX_PAYABLE_REPORT_RESERVATION).
								   tep_admin_files_boxes(FILENAME_STATS_FINANCIAL_PAYABLE_REPORT, BOX_FINANCIAL_PAYABLE_REPORT)
						);
//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- reports_accountant //-->
