<?php
/*
  $Id: affiliate.php,v 1.1.1.1 2004/03/04 23:42:24 ccwjr Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/
?>          
<!-- affiliate_system //-->
          <tr>
            <td>
<?php
  $info_box_contents = array();
    $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_AFFILIATE . '</font>');

  new infoBoxHeading($info_box_contents,'');

  if (tep_session_is_registered('affiliate_id')) {
    $info_box_contents = array();
    $info_box_contents[] = array('text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL') . '">' . BOX_AFFILIATE_SUMMARY . '</a><br>' .
                                           '<a href="' . tep_href_link(FILENAME_AFFILIATE_ACCOUNT, '', 'SSL'). '">' . BOX_AFFILIATE_ACCOUNT . '</a><br>' .
                                           '<a href="' . tep_href_link(FILENAME_REFER_A_FRIEND). '">' . BOX_AFFILIATE_REFER_FRIENDS . '</a><br>' .
                                           '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS). '">' . BOX_AFFILIATE_BANNERS . '</a><br>' .
                                           '<a href="' . tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL'). '">' . BOX_AFFILIATE_SALES . '</a><br>' .
                                           '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL'). '">' . BOX_AFFILIATE_PAYMENT . '</a><br>' .
//                                           '<a href="' . tep_href_link(FILENAME_AFFILIATE_CLICKS, '', 'SSL'). '">' . BOX_AFFILIATE_CLICKRATE . '</a><br>' .
                                           '<a href="' . tep_href_link(FILENAME_AFFILIATE_CONTACT). '">' . BOX_AFFILIATE_CONTACT . '</a><br>' .
                                           '<a href="' . tep_href_link(FILENAME_AFFILIATE_FAQ). '">' . BOX_AFFILIATE_FAQ . '</a>');
//                                             '<a href="' . tep_href_link(FILENAME_AFFILIATE_LOGOUT). '">' . BOX_AFFILIATE_LOGOUT . '</a>');
  } else {
    $info_box_contents = array();
    $info_box_contents[] = array('text' => '<a href="' . tep_href_link(FILENAME_AFFILIATE_INFO). '">' . BOX_AFFILIATE_INFO . '</a><br>' .
                                           '<a href="' . tep_href_link(FILENAME_AFFILIATE, '', 'SSL') . '">' . BOX_AFFILIATE_LOGIN . '</a>');
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
<!-- affiliate_system_eof //-->