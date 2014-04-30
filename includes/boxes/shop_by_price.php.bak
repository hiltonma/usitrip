<?php
/*
  $Id: shop_by_price.php,v 1.1.1.1 2004/03/04 23:42:27 ccwjr Exp $

  Contribution by Meltus
  http://www.highbarn-consulting.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- shop by price //-->
<?php
if (MODULE_SHOPBYPRICE_RANGES > 0) {
?>
          <tr>
            <td>
<?php
  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_SHOP_BY_PRICE);

  $info_box_contents = array();
  $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_SHOP_BY_PRICE . '</font>');
  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
	$sbp_array = unserialize(MODULE_SHOPBYPRICE_RANGE);

  $info_box_contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_SHOP_BY_PRICE, 'range=0', 'NONSSL') . '">' . TEXT_INFO_UNDER . $currencies->format($sbp_array[0]) . '</a><br>');
	for ($i=1, $ii=count($sbp_array); $i < $ii; $i++) {
    $info_box_contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_SHOP_BY_PRICE, 'range=' . $i, 'NONSSL') . '">' . TEXT_INFO_FROM . $currencies->format($sbp_array[$i-1]) . TEXT_INFO_TO . $currencies->format($sbp_array[$i]) . '</a><br>');
	}
  if (MODULE_SHOPBYPRICE_OVER == True) {
    $info_box_contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_SHOP_BY_PRICE, 'range=' . $i, 'NONSSL') . '">' . $currencies->format($sbp_array[$i-1]) . TEXT_INFO_ABOVE . '</a><br>');
  }
  new infoBox($info_box_contents);
   if (TEMPLATE_INCLUDE_FOOTER =='true'){
       $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left',
                                      'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                    );
   new infoboxFooter($info_box_contents);
 }
?>
            </td>
          </tr>
<?php
}
?>
<!-- shop_by_price //-->