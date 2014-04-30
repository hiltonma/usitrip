<?php
/*
  $Id: customers.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- customers //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_REWARDS4FUN,
                     'link'  => tep_href_link(FILENAME_REWARDS4FUN_SUMMARY, 'selected_box=rewards4fun'));

  if ($selected_box == 'rewards4fun' || $menu_dhtml == true) {
    $contents[] = array('text'  =>
//Admin begin
						   tep_admin_files_boxes(FILENAME_REWARDS4FUN_SUMMARY, BOX_REWARDS4FUN_SUMMARY) . 															
						   tep_admin_files_boxes(FILENAME_REVIEWS, BOX_CATALOG_REVIEWS) . 															
						   tep_admin_files_boxes(FILENAME_TRAVELER_PHOTOS, BOX_CATALOG_PHOTOS) .
						   //tep_admin_files_boxes(FILENAME_CUSTOMERS_POINTS, BOX_CUSTOMERS_POINTS) .// Points/Rewards Module V2.1rc2a
						   '<a href="' . tep_href_link(FILENAME_CUSTOMERS_POINTS, 'filter=2') . '" class="menuBoxContentLink">' . BOX_CUSTOMERS_POINTS . '</a><br>'.
						   //tep_admin_files_boxes(FILENAME_CUSTOMERS_POINTS_PENDING, BOX_CUSTOMERS_POINTS_PENDING) .// Points/Rewards Module V2.1rc2a
						   tep_admin_files_boxes(FILENAME_CUSTOMERS_POINTS_REFERRAL, BOX_CUSTOMERS_POINTS_REFERRAL) // Points/Rewards Module V2.1rc2a
								   
						   );

//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- customers_eof //-->
