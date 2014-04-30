<?php
/*
  $Id: reports.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reports_service //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_SERVICE_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_CUSTOMERS, 'selected_box=reports_service'));

  if ($selected_box == 'reports_service' || $menu_dhtml == true) {
      $contents[] = array('text'  =>
//Admin begin
                                   tep_admin_files_boxes(FILENAME_STATS_CUSTOMERS, BOX_REPORTS_ORDERS_TOTAL) .
								   tep_admin_files_boxes(FILENAME_CUSTOMERS_REP_ORDERS, BOX_REPORTS_CUSTOMERS_REP_ORDERS).
								   tep_admin_files_boxes(FILENAME_CUSTOMERS_REPEAT_ORDERS, BOX_CUSTOMERS_REPEAT_ORDERSM).
								   tep_admin_files_boxes(FILENAME_STATS_ORDER_ANALYSIS, BOX_CUSTOMER_ORDER_ANALYSIS). 
								   tep_admin_files_boxes('stats_analysis_order.php', '订单统计报表'). 
								   tep_admin_files_boxes(FILENAME_CPC_REPORT, BOX_CPC_REPORT) .
									tep_admin_files_boxes(FILENAME_STATS_CSC, BOX_CSC_REPORT) .
									tep_admin_files_boxes('assessment_score.php', '工作人员评分统计') .
									tep_admin_files_boxes('assessment_score_report.php', '工作人员评分统计(报表)')
						);
//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- reports_service //-->
