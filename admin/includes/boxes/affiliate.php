<?php
/*
  $Id: affiliate.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- affiliates //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_AFFILIATE,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=affiliate'));

  if ($selected_box == 'affiliate' || $menu_dhtml == true) {
    
	$contents[] = array('text'  =>
									tep_admin_files_boxes(FILENAME_AFFILIATE_SUMMARY,BOX_AFFILIATE_SUMMARY) .
								   tep_admin_files_boxes(FILENAME_AFFILIATE,BOX_AFFILIATE) .
   								   tep_admin_files_boxes(FILENAME_AFFILIATE_PAYMENT,BOX_AFFILIATE_PAYMENT ) .
   								   tep_admin_files_boxes(FILENAME_AFFILIATE_SALES,BOX_AFFILIATE_SALES) .
								   tep_admin_files_boxes(FILENAME_AFFILIATE_CLICKS,BOX_AFFILIATE_CLICKS) .
								   tep_admin_files_boxes(FILENAME_AFFILIATE_BANNER_GROUPS,BOX_AFFILIATE_BANNERS) .
								   tep_admin_files_boxes(FILENAME_AFFILIATE_NEWS,BOX_AFFILIATE_NEWS) .
								   tep_admin_files_boxes(FILENAME_AFFILIATE_CONTACT,BOX_AFFILIATE_CONTACT) 				
									/*
								   '<a href="' . tep_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_SUMMARY . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_AFFILIATE, '', 'SSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_PAYMENT . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_SALES . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_AFFILIATE_CLICKS, '', 'SSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_CLICKS . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNER_MANAGER, '', 'SSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_BANNERS . '</a><br>' .
								   '<a href="' . tep_href_link(FILENAME_AFFILIATE_NEWS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_NEWS . '</a><br>' . 
                                   '<a href="' . tep_href_link(FILENAME_AFFILIATE_CONTACT, '', 'SSL') . '" class="menuBoxContentLink">' . BOX_AFFILIATE_CONTACT . '</a>'
									*/
									);
 
 }

  $box = new box;
  echo $box->menuBox($heading, $contents); 
?>
            </td>
          </tr>
<!-- affiliates_eof //-->
