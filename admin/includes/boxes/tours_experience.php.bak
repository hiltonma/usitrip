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
                     'link'  => tep_href_link(FILENAME_REWARDS4FUN_SUMMARY, 'selected_box=tours_experience'));

  if ($selected_box == 'tours_experience' || $menu_dhtml == true) {
    $contents[] = array('text'  =>
//Admin begin
						   tep_admin_files_boxes('tours_experience_categories.php', 'ToursExperienceCategories') . 															
						   tep_admin_files_boxes('tours_experience_admin.php', 'ToursExperienceAdmin').														
						   tep_admin_files_boxes('tours_experience_tags.php', 'ToursRxperienceTags')													
						   );

//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- customers_eof //-->
