<?php
/*
  $Id: reports.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reports_marketing //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_MARKETING_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_AD_RESULTS_DETAILS, 'selected_box=reports_marketing'));

  if ($selected_box == 'reports_marketing' || $menu_dhtml == true) {
      $contents[] = array('text'  =>
//Admin begin
                                   tep_admin_files_boxes(FILENAME_STATS_AD_RESULTS_DETAILS, BOX_REPORTS_AD_RESULTS) .
								   tep_admin_files_boxes(FILENAME_STATS_AD_RESULTS_MEDIUM, BOX_REPORTS_AD_RESULTS_MEDIUM)
						);
//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- reports_marketing //-->
