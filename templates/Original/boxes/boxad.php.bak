<?php
/*
  $Id: boxad.php, v 1.1 2002/03/21 by aubrey@mycon.co.za

  osCommerce
  http://www.oscommerce.com/

  Copyright (c) 2000,2001 osCommerce

  Released under the GNU General Public License


  IMPORTANT NOTE:

  This script is not part of the official osC distribution
  but an add-on contributed to the osC community. Please
  read the README and  INSTALL documents that are provided
  with this file for further information and installation notes.

*/
?>
<!-- banner-ad-in-a-box //-->
<?php
  if ($banner = tep_banner_exists('dynamic', BOX_AD_BANNER_TYPE)) {
?>
          <tr>
            <td>

<?php
    $bannerstring = tep_display_banner('static', $banner);

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<font color="' . $font_color . '">' . BOX_AD_BANNER_HEADING . '</font>'
                                );
    new infoBoxHeading($info_box_contents, false, false);

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text'  => $bannerstring
                                );
    new infoBox($info_box_contents);
?>
            </td>
          </tr>
<?php
  }
?>
<!-- banner-ad-in-a-box_eof //-->
