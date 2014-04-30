<?php
/*
  $Id: storefeed.php,v 1.00 2004/09/07

  Store Data Feed admin box

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
  
  Contribution created by: Chemo
*/
?>
<!-- feed //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => 'Store Feeds',
                     'link'  => tep_href_link('froogle.php', 'selected_box=feeds'));

  if ($selected_box == 'feeds') {
    $contents[] = array('text'  => '<a href="' . tep_href_link('froogle.php', '', 'NONSSL') . '" class="menuBoxContentLink">Froogle</a><br>' //.
									//'<a href="' . tep_href_link('amazon.php', '', 'NONSSL') . '" class="menuBoxContentLink">Amazon</a><br>' .
									//'<a href="' . tep_href_link('yahoo.php', '', 'NONSSL') . '" class="menuBoxContentLink">Yahoo</a><br>' .
									//'<a href="' . tep_href_link('shoppingdotcom.php', '', 'NONSSL') . '" class="menuBoxContentLink">Shopping.com</a><br>' .
									//'<a href="' . tep_href_link('XMLaffiliates.php', '', 'NONSSL') . '" class="menuBoxContentLink">XML Affiliates</a><br>'
									);
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- feed_eof //-->