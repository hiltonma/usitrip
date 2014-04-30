<?php
/*
  FAQ system for OSC 2.2 MS2 v2.1  22.02.2005
  Originally Created by: http://adgrafics.com admin@adgrafics.net
  Updated by: http://www.webandpepper.ch osc@webandpepper.ch v2.0 (03.03.2004)
  Last Modified: http://shopandgo.caesium55.com timmhaas@web.de v2.1 (22.02.2005)
  Released under the GNU General Public License
  osCommerce, Open Source E-Commerce Solutions
  Copyright (c) 2004 osCommerce
	
	Chain Reaction Works, Inc
	Copyright &copy; 2006 Chain Reaction Works, Inc.
	
	Last Modified by $Author$
	Last Modifed on : $Date$
	Latest Revision : $Revision: 311 $
*/
?>
<!-- faq //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_FAQ,
                     'link'  => tep_href_link(FILENAME_FAQ_MANAGER, 'selected_box=faq'));

  if ($selected_box == 'faq' || $menu_dhtml == true) {
    $contents[] = array('text'  => //tep_admin_files_boxes(FILENAME_FAQ_MANAGER, BOX_FAQ_MANAGER) .
                                   tep_admin_files_boxes(FILENAME_FAQ_CATEGORIES, BOX_FAQ_CATEGORIES)
                                  // tep_admin_files_boxes(FILENAME_FAQ_VIEW, BOX_FAQ_VIEW) .
                                  // tep_admin_files_boxes(FILENAME_FAQ_VIEW_ALL,BOX_FAQ_VIEW_ALL)
								   );

}

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- faq-eof //-->
