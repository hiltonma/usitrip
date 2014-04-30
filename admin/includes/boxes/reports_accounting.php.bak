<?php
/*
  $Id: reports.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reports_accounting //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_ACCOUNTING_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_DETAILED_MONTHLY_SALES, 'selected_box=reports_accounting'));

  if($login_id == 22 || $login_id == 28 || $login_id == 24 || $login_id == 26 || $login_id == 64 || $login_id == 112 || $login_id == 165 || $login_id == 155 || $login_id == 82 || $login_groups_id == '1' || $login_groups_id == '21' || $login_groups_id == '11'){ //Weiyi, Joyce, Julia, Joanna & Top Administrators & Administrators & Accountant
  if ($selected_box == 'reports_accounting' || $menu_dhtml == true) {
      $contents[] = array('text'  =>
//Admin begin
                                   tep_admin_files_boxes(FILENAME_ACCOUNTS_PAYABLE_REPORTS, BOX_ACCOUNTS_PAYABLE_REPORTS).
								   tep_admin_files_boxes(FILENAME_STATS_DETAILED_MONTHLY_SALES, BOX_REPORTS_DETAILD_MONTHLY_SALES).	   
								   tep_admin_files_boxes(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY_NEW, BOX_REPORTS_PAID_PAYMENT_HISTORY).
								   tep_admin_files_boxes(FILENAME_STATS_PAID_PAYMENT_ORDER_HISTORY, BOX_REPORTS_PAID_PAYMENT_HISTORY_OLD)
						);
//Admin end
  }
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- reports_accounting //-->
