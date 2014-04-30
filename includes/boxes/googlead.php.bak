<?php
/*
  $Id: googlead.php, v 1.1 2004/05/30 Tom O'Neill (zip1)

  osCommerce
  http://www.oscommerce.com/

  Copyright (c) 2000,2001 osCommerce

  Released under the GNU General Public License
 based on banner in a box by aubrey@mycon.co.za

  IMPORTANT NOTE:

  This script is not part of the official osC distribution
  but an add-on contributed to the osC community. Please
  read the README and  INSTALL documents that are provided
  with this file for further information and installation notes.

*/
?>
<!-- google-banner-ad-in-a-box //-->
<?php
  if (!(getenv('HTTPS')=='on')){
  if ($banner = tep_banner_exists('dynamic', 'googlebox')) {
?>
          <tr>
            <td>

<?php
    $bannerstring = tep_display_banner('static', $banner);

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => '<font color="' . $font_color . '">' . BOX_GOOGLE_AD_BANNER_HEADING . '</font>'
                                );
    new infoBoxHeading($info_box_contents, false, false);

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text'  => $bannerstring
                                );
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
  }
?>
<!-- google-banner-ad-in-a-box_eof //-->
