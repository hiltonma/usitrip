<?php 
if (DISPLAY_DHTML_MENU == 'CoolMenu') {
		require(DIR_FS_INCLUDES . 'coolmenu.php'); 
	}
	?>

<!-- body //-->


<script language="javascript"><!--

function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=780,height=550,screenX=100,screenY=100,top=75,left=75')
}
//--></script>
  <?php /*echo tep_get_design_body_header(HEADING_TITLE,1);*/ ?>
   <!-- body_text //-->
    <table border="0" width="99%" cellspacing="0" cellpadding="0">
     	  <?php
//amit added to affiliate page navigation start

require('includes/affiliate_page_navi.php');

//amit added to affiliate page navigation end
?>
<tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
	   <tr>
        <td class="main">
		<?php /* echo PAGE_HEADING_SUB_TITLE1;?> <a target="blank" href="http://www.tripadvisor.com"><u>TripAdvisor</u></a> <?php echo ADV_SITE_OR;?> <a href="http://www.mytravelguide.com" target="blank"><u>MyTravelGuide</u></a>, <?php echo PAGE_HEADING_SUB_TITLE2;?> <?php echo tep_get_affiliate_percent_display();?> <?php echo PAGE_HEADING_SUB_TITLE3; */?>		
		<?php echo TEXT_INFORMATION; ?>
		</td>
      </tr>  
	  <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr> 
      <tr>
        <td>
		<table border="0" width="95%"  cellspacing="0" cellpadding="2">
			<!-- build products link start  -->
          <tr>
            <td class="infoBoxHeading"><?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER . ' ' . db_to_html($affiliate_banners['affiliate_banners_title']); ?></td>
          </tr>
          <tr>
            <td class="smallText" ><?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_INFO . tep_draw_form('individual_banner', tep_href_link(FILENAME_AFFILIATE_BANNERS) ) . "\n<input type='text' size='5'  name='individual_banner_id' vlaue=''>&nbsp;&nbsp;" . tep_template_image_submit('button_affiliate_build_a_link.gif', IMAGE_BUTTON_BUILD_A_LINK); ?></form></td>
          </tr>
		  <tr>
      	 <td class="smallText" ><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_AFFILIATE_VALIDCATS) . '\')"><b>' . TEXT_AFFILIATE_VALIDPRODUCTS . '</b></a>'; ?>&nbsp;&nbsp;<?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_VIEW;?><br><?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_HELP;?></td>
     	</tr>
		 <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      	</tr>
		<!-- build products link end  -->
		<!-- build categories link start -->
          <tr>
            <td class="infoBoxHeading"><?php echo TEXT_AFFILIATE_CATEGORIES_INDIVIDUAL_BANNER . ' ' . db_to_html($affiliate_banners['affiliate_banners_title']); ?></td>
          </tr>
		  <tr>
			<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
		  </tr>
          <tr>
            <td class="smallText" ><?php echo TEXT_AFFILIATE_CATEGORIES_INDIVIDUAL_BANNER_INFO . tep_draw_form('individual_banner', tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD_CAT) ) . "\n" . tep_draw_input_field('individual_banner_id', '', 'size="5"') . "&nbsp;&nbsp;" . tep_image_submit('button_affiliate_build_a_link.gif', IMAGE_BUTTON_BUILD_A_LINK); ?></form></td>
          </tr>
		 <tr>
		   <td class="smallText"><?php echo '<a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_AFFILIATE_VALIDCATS) . '\')"><b>' . TEXT_AFFILIATE_VALIDPRODUCTS . '</b></a>'; ?>&nbsp;&nbsp;<?php echo TEXT_AFFILIATE_CATEGORIES_INDIVIDUAL_BANNER_VIEW;?><br><?php echo TEXT_AFFILIATE_CATEGORIES_INDIVIDUAL_BANNER_HELP;?></td>
		 </tr>
		 <tr>
			<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
		  </tr>
   		<!-- build categories link end -->
<?php
  if (tep_not_null($HTTP_POST_VARS['individual_banner_id']) || tep_not_null($HTTP_GET_VARS['individual_banner_id'])) {

    if (tep_not_null($HTTP_POST_VARS['individual_banner_id'])) $individual_banner_id = $HTTP_POST_VARS['individual_banner_id'];
    if ($HTTP_GET_VARS['individual_banner_id']) $individual_banner_id = $HTTP_GET_VARS['individual_banner_id'];
    $affiliate_pbanners_values = tep_db_query("select c.categories_image, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $individual_banner_id . "' and cd.categories_id = '" . $individual_banner_id . "' and cd.language_id = '" . $languages_id . "'");
    if ($affiliate_pbanners = tep_db_fetch_array($affiliate_pbanners_values)) {
      switch (AFFILIATE_KIND_OF_BANNERS) {
        case 1:
   			$link = '<a href="' . tep_href_link(FILENAME_DEFAULT , 'ref=' . $affiliate_id . '&cPath=' . $individual_banner_id . '&affiliate_banner_id=1').'" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $affiliate_pbanners['categories_image'] . '" border="0" alt="' . db_to_html($affiliate_pbanners['categories_name']) . '"></a>';
   			$link1 = '<a href="' . tep_href_link(FILENAME_DEFAULT , 'ref=' . $affiliate_id . '&cPath=' . $individual_banner_id . '&affiliate_banner_id=1').'" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $affiliate_pbanners['categories_image'] . '" border="0" alt="' . db_to_html($affiliate_pbanners['categories_name']) . '"></a>';
   			$link2 = '<a href="' . tep_href_link(FILENAME_DEFAULT , 'ref=' . $affiliate_id . '&cPath=' . $individual_banner_id . '&affiliate_banner_id=1').'" target="_blank">' . db_to_html($affiliate_pbanners['categories_name']) . '</a>'; 
   		break; 
  		case 2: 
   // Link to Products 
   			$link = '<a href="' . tep_href_link(FILENAME_DEFAULT , 'ref=' . $affiliate_id . '&cPath=' . $individual_banner_id . '&affiliate_banner_id=1').'" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_pbanner_id=' . $individual_banner_id . '" border="0" alt="' . db_to_html($affiliate_pbanners['categories_name']) . '"></a>';
   			$link1 = '<a href="' . tep_href_link(FILENAME_DEFAULT , 'ref=' . $affiliate_id . '&cPath=' . $individual_banner_id . '&affiliate_banner_id=1').'" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_pbanner_id=' . $individual_banner_id . '" border="0" alt="' . db_to_html($affiliate_pbanners['categories_name']) . '"></a>';
   			$link2 = '<a href="' . tep_href_link(FILENAME_DEFAULT , 'ref=' . $affiliate_id . '&cPath=' . $individual_banner_id . '&affiliate_banner_id=1').'" target="_blank">' . db_to_html($affiliate_pbanners['categories_name']) . '</a>'; 
   		break; 
     } 
} 
?>
      <tr>
        <td><table width="100%"  border="0" cellpadding="4" cellspacing="0" class="infoBoxContents">
          <tr>
            <td class="infoBoxHeading" ><?php echo TEXT_AFFILIATE_NAME; ?>&nbsp;<?php echo db_to_html($affiliate_pbanners['categories_name']); ?></td>
          </tr>
          <tr>
            <td class="smallText" ><?php echo $link; ?></td> 
          </tr> 
          <tr> 
            <td class="smallText" ><?php echo TEXT_AFFILIATE_INFO; ?></td> 
          </tr> 
          <tr> 
            <td class="smallText"> 
             <textarea cols="60" rows="4" class="boxText"><?php echo $link1; ?></textarea> 
            </td> 
          </tr> 
          <tr> 
            <td><td> 
          </tr> 
          <tr> 
            <td class="smallText" ><b><?php echo TEXT_AFFILIATE_TEXT_VERSION; ?></b> <?php echo $link2; ?></td> 
          </tr> 
          <tr> 
            <td class="smallText" ><?php echo TEXT_AFFILIATE_INFO; ?></td> 
          </tr> 
          <tr> 
            <td class="smallText"> 
             <textarea cols="60" rows="3" class="boxText"><?php echo $link2; ?></textarea> 
            </td> 
          </tr>
          </table>
		  
		  <?php
  if (tep_db_num_rows($affiliate_banners_values)) {

    while ($affiliate_banners = tep_db_fetch_array($affiliate_banners_values)) {
      if($affiliate_banners['affiliate_category_id']!=0)
	  {
	  	  $affiliate_products_query = tep_db_query("select pd.categories_name, p.categories_image from " . TABLE_CATEGORIES_DESCRIPTION . " as pd ," . TABLE_CATEGORIES . " as p where pd.categories_id = p.categories_id and pd.categories_id = '" . $affiliate_banners['affiliate_category_id'] . "' and pd.language_id = '" . $languages_id . "'");
		  $affiliate_products = tep_db_fetch_array($affiliate_products_query);
		  $products_name = $affiliate_products['categories_name'];
		  $products_images = $affiliate_products['categories_image'];
	  }
	  else
	  {
		  $affiliate_products_query = tep_db_query("select pd.products_name, p.products_image from " . TABLE_PRODUCTS_DESCRIPTION . " as pd ," . TABLE_PRODUCTS . " as p where pd.products_id = p.products_id and pd.products_id = '" . $affiliate_banners['affiliate_products_id'] . "' and pd.language_id = '" . $languages_id . "'");
		  $affiliate_products = tep_db_fetch_array($affiliate_products_query);
		  $products_name = $affiliate_products['products_name'];
		  $products_images = $affiliate_products['products_image'];
	  }
      
      $prod_id = $affiliate_banners['affiliate_products_id'];
      $ban_id = $affiliate_banners['affiliate_banners_id'];
      switch (AFFILIATE_KIND_OF_BANNERS) {
        case 1: // Link to Products
          if ($prod_id > 0) {
            $link = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO , 'ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id ). '" target="_blank"><img src="' . DIR_WS_IMAGES . $products_images . '" border="0" alt="' . $affiliate_products['products_name'] . '"></a>';
            $link2 = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO , 'ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id ). '" target="_blank">' . $products_name . '</a>'; 
   		  } else { // generic_link
            $link = '<a href="' .tep_href_link(FILENAME_DEFAULT , 'cPath='.$affiliate_banners['affiliate_category_id'].'&ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id ).'" target="_blank"><img src="' . DIR_WS_IMAGES . $products_images . '" border="0" alt="' . $affiliate_banners['affiliate_banners_title'] . '"></a>';
		    $link2 = '<a href="' . tep_href_link(FILENAME_DEFAULT , 'cPath='.$affiliate_banners['affiliate_category_id'].'&ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id ).'" target="_blank">'.$affiliate_banners['affiliate_banners_title'].'</a>';
      
          }		   
          break;
        case 2: // Link to Products
          if ($prod_id > 0) {
            $link = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO , 'ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id ). '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" border="0" alt="' . $affiliate_products['products_name'] . '"></a>';
			$link2 = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO , 'ref=' . $affiliate_id . '&products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id ). '" target="_blank">'.$products_name.'</a>';
          } else { // generic_link
            $link = '<a href="' . tep_href_link(FILENAME_DEFAULT , 'cPath='.$affiliate_banners['affiliate_category_id'].'&ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id ). '" target="_blank"><img src="' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . FILENAME_AFFILIATE_SHOW_BANNER . '?ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id . '" border="0" alt="' . $affiliate_banners['affiliate_banners_title'] . '"></a>';
			$link2 = '<a href="' . tep_href_link(FILENAME_DEFAULT , 'cPath='.$affiliate_banners['affiliate_category_id'].'&ref=' . $affiliate_id . '&affiliate_banner_id=' . $ban_id ). '" target="_blank">'.$affiliate_banners['affiliate_banners_title'].'</a>';
          }
          break; 
      }

?>
    <tr>
	    <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
		 <tr> 
            <td><td> 
          </tr> 
          <tr>
            <td class="infoBoxHeading"><?php echo TEXT_AFFILIATE_NAME; ?>&nbsp;<?php echo  $products_name; ?></td>
          </tr>
         <tr>
            <td class="smallText"><br><?php echo $link; ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo TEXT_AFFILIATE_INFO; ?></td>
          </tr>
          <tr>
            <td><textarea cols="60" rows="3" class="boxText"><?php echo $link; ?></textarea> 
			<?php //echo tep_draw_textarea_field('affiliate_banner', 'soft', '60', '6', $link); ?></td>
          </tr>
		   <tr> 
            <td><td> 
          </tr> 
		   <tr> 
            <td class="smallText"><b><?php echo TEXT_AFFILIATE_TEXT_VERSION; ?></b> <?php echo $link2; ?></td> 
          </tr> 
          <tr> 
            <td class="smallText"><?php echo TEXT_AFFILIATE_INFO; ?></td> 
          </tr> 
          <tr> 
            <td class="smallText"> 
             <textarea cols="60" rows="3" class="boxText"><?php echo $link2; ?></textarea> 
            </td> 
          </tr>
			 <tr> 
            <td><td> 
          </tr> 		
     </table>
  <?php
  }
      }
  ?>
<?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
<?php
}
?>
	 </td></tr>
	 </table>
	 </td>
      </tr>	
     </table>
<?php //echo tep_get_design_body_footer();?>
