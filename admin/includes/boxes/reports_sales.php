<?php
/*
  $Id: reports.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reports_sales //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_SALES_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_SALES_REPORT, 'selected_box=reports_sales'));

  if ($selected_box == 'reports_sales' || $menu_dhtml == true) {
      $contents[] = array('text'  =>
//Admin begin
                                   tep_admin_files_boxes('accounts_receivable.php', '到账及核对明细账').
                                   tep_admin_files_boxes('accounts_refund.php', '客户退款报表').
								   /*tep_admin_files_boxes(FILENAME_STATS_SALES_REPORT, BOX_REPORTS_SALES_REPORT) .*/
								   tep_admin_files_boxes(FILENAME_STATS_SALES_REPORT2, BOX_REPORTS_SALES_REPORT2) .
								   //tep_admin_files_boxes(FILENAME_STATS_SALES_REPORT2_GRAPH, BOX_REPORTS_SALES_CSV) .
								   tep_admin_files_boxes(FILENAME_STATS_MONTHLY_SALES, BOX_REPORTS_MONTHLY_SALES).
								   tep_admin_files_boxes(FILENAME_REPORT_DEPARTURE_CITIES, BOX_REPORT_DEPARTURE_CITIES).
								   tep_admin_files_boxes(FILENAME_STATS_SALES_BY_CATEGORY, BOX_REPORT_SALES_BY_CATEGORY).
								   tep_admin_files_boxes(FILENAME_STATS_SALES_BY_CATEGORY_TREE, BOX_REPORT_SALES_BY_CATEGORY_TREE).
								   tep_admin_files_boxes('travel_companion_reports.php', '结伴同游统计').
								   tep_admin_files_boxes(FILENAME_STATS_SALES_BY_DURATIONS, BOX_REPORT_SALES_BY_DURATTION).
								   tep_admin_files_boxes(FILENAME_STATS_PRODUCTS_VIEWED, BOX_REPORTS_PRODUCTS_VIEWED) .
								   tep_admin_files_boxes(FILENAME_STATS_PRODUCTS_PURCHASED, BOX_REPORTS_PRODUCTS_PURCHASED)
								   
						);
//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- reports_sales //-->
