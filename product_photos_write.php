<?php
/*
  $Id: product_reviews_write.php,v 1.1.1.1 2004/03/04 23:38:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require_once('includes/application_top.php');
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);
  if (!tep_session_is_registered('customer_id')) {
    //$navigation->set_snapshot();
   // tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL')); 
  }
  //strar to check post from ajax

	
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
  
    $products_id = $HTTP_GET_VARS['products_id'];
	$traveler_photo_id = $HTTP_GET_VARS['traveler_photo_id'];
	
    $error = false;
  
    if ($error == false) {

		$select_image = "select ri.customers_name,ri.image_desc,ri.image_name,ri.image_title,ri.added_date from ".TABLE_TRAVELER_PHOTOS." ri where ri.traveler_photo_id=".$traveler_photo_id."";
		$res_image = tep_db_query($select_image);
		$row_image = tep_db_fetch_array($res_image);
		$image_name = $row_image['image_name'];
		if($image_name!='')
		{
			if(file_exists(DIR_FS_IMAGES.'reviews/detail_'.$image_name)) 
			{
				$image = tep_image(DIR_WS_IMAGES.'reviews/detail_'.$image_name);  
			}
			else
			{
				$image = tep_image(DIR_WS_IMAGES.'reviews/'.$image_name);  
			}
		}
		?>
			<table width="99%" cellpadding="2" cellspacing="0">
			<?php /*
				<tr>
					<td valign="middle">
						<div class="pr_1_b_review" style="height:25px; padding-top:5px; padding-left:10px;"><h3><?php echo db_to_html(tep_get_products_name($products_id)); ?></h3></div>
					</td>
				</tr>
				<tr><td height="5"></td></tr>
				*/ ?>
				<tr>
					<td class="sp3_no_decoration" style="padding-left:40px;"><b><?php echo db_to_html($row_image['image_title']); ?></b></td>
				</tr>
				<tr>
					<td><div class="nr_img_2Copy_bottom_review"><div class="nr_img_1_review" align="center" ><?php echo $image; ?></div></div></td>
				</tr>
				<tr>
					<td style="padding-left:40px;"><?php echo db_to_html($row_image['image_desc']); ?></td>
				</tr>
				<?php
					$added_date = explode(" ",$row_image['added_date']);
					$date = strtotime($added_date[0]);
					$date_disp = date("F Y",$date);
				?>
				<tr>
					<td align="right" class="review_gray_front"><?php echo TEXT_PHOTO_BY.' '. db_to_html($row_image['customers_name']); ?>, <?php echo $date_disp; ?></td>
				</tr>
				<tr><td height="10"></td></tr>
				<tr>
					<td><hr color="#108bcd" /></td>
				</tr>
			</table>
		<?php

    }
}
  
  
?>
