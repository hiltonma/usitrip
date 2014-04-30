<?php
/*
  $Id: reports.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reports_payment //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_PAYMENT_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_PAYMENT_ADJUSTED_ORDER_HISTORY, 'selected_box=reports_payment'));

  if($login_id == 22 || $login_id == 26 || $login_id == 64 || $login_id == 112 || $login_id == 165 || $login_id == 155 || $login_id == 82 || $login_id == 28 || $login_groups_id == '1' || $login_groups_id == '21'){ //Weiyi, Joanna & Top Administrators
  if ($selected_box == 'reports_payment' || $menu_dhtml == true) {
      
   if($login_id == 28 || $login_id == 64 || $login_id == 112 || $login_id == 165 || $login_id == 155 || $login_id == 82){ // Joyce & Judy	
	 $contents[] = array('text'  =>
								   tep_admin_files_boxes(FILENAME_CANCELLED_ORDERS, BOX_REPORTS_CANCELLED_ORDERS).	
								   tep_admin_files_boxes(FILENAME_STATS_CHARGE_CAPTURED_REPORT, BOX_CHARGE_CAPTURED_REPORT)
						 );
   }else{
      $contents[] = array('text'  =>
								   tep_admin_files_boxes(FILENAME_STATS_PAYMENT_ADJUSTED_ORDER_HISTORY, BOX_REPORTS_PAYMENT_ADJUSTED_ORDER_HISTORY) .
								   tep_admin_files_boxes(FILENAME_CANCELLED_ORDERS, BOX_REPORTS_CANCELLED_ORDERS).	
								   tep_admin_files_boxes(FILENAME_CCEXPIRED_ORDERS_REPORT, BOX_REPORTS_CCEXPIRED_ORDERS_REPORT).
								   tep_admin_files_boxes(FILENAME_STATS_SETTLEMENT_REPORT, BOX_REPORTS_SETTLEMENT).				   
								   tep_admin_files_boxes(FILENAME_STATS_CHARGE_CAPTURED_REPORT, BOX_CHARGE_CAPTURED_REPORT).
								   tep_admin_files_boxes(FILENAME_STATS_UNCHARGED_REPORT, BOX_UNCHARGED_REPORT)
						);
   }
  }
  
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- reports_payment //-->
