	<?php
	define(MAX_DISPLAY_CATEGORIES_PER_ROW_ROOT_LISTING,1);
    if (isset($cPath) && strpos('_', $cPath)) {
// check to see if there are deeper categories within the current category
      $category_links = array_reverse($cPath_array);
      for($i=0, $n=sizeof($category_links); $i<$n; $i++) {
        $categories_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.categories_status='1' and c.categories_feature_status='0' ");
        $categories = tep_db_fetch_array($categories_query);
        if ($categories['total'] < 1) {
          // do nothing, go through the loop
        } else {
          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_video, c.categories_image, cd.categories_first_sentence, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.categories_status='1' and c.categories_feature_status='0' order by sort_order, cd.categories_name");
          break; // we've found the deepest category the customer is in
        }
      }
    } else {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_video, c.categories_image, cd.categories_first_sentence, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.categories_status='1' and c.categories_feature_status='0' order by sort_order, cd.categories_name");
    }

	//echo "select c.categories_id, cd.categories_name, c.categories_image, cd.categories_first_sentence, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name limit 10,1000";
   // $number_of_categories = tep_db_num_rows($categories_query);

    $rows = 0;
	$divflag = 0;
    while ($categories = tep_db_fetch_array($categories_query)) {
      $rows++;
	  $divflag++;
      $cPath_new = tep_get_path($categories['categories_id']);
	  
	
    //  $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW_ROOT_LISTING) . '%';
	  $width ="100";
	  
	  //howard tmp added for yellowstone0904
	  $link_page = tep_href_link(FILENAME_DEFAULT, $cPath_new);
	  if($categories['categories_id']=='156'){
		$html_file = 'yellowstone_tw.html';
		if(CHARSET=='gb2312'){
			$html_file = 'yellowstone.html';
		}

		if(date('Y-m-d')>='2009-04-16' && date('Y-m-d')<='2009-06-16'){
			$link_page = HTTP_SERVER.'/landing-page/yellowstone0904/'.$html_file;
		}
	  }
	  //howard tmp added yellowstone0904 end
   	?>
							
						 <div class="xunhuan" >
					 	
						 <?php if($rows%2==0){$class_neirong='neirong_2';}else{$class_neirong='neirong2';}?>
						 <div class="<?php echo $class_neirong?>">
						    <div class="nr_left">
	                            <div class="nr_img_1">
								<?php									
								echo '<a href="' . $link_page . '">' . tep_image(DIR_WS_IMAGES . $categories['categories_image'], db_to_html($categories['categories_name']),160, 95) . '</a>';
								?></div><?php
							   if($categories['categories_video'] != '' && $forcevideooff=="true") { ?>
						  	   <div class="nr_img_2">
							   <div class="nr_img_2_wz"><a href="<?php echo $link_page; ?>">
							   <?php
							  // if($categories['categories_video'] != '') {
								echo '<img src="image/vido.gif" />';
								//}else{
								//echo '<img src="image/vido_no.gif" />';
								//}
								?></a></div></div>								
								<?php } ?>
	                        </div>
							
							<div class="nr_right">
							    <div class="nr_l_table">
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									 <tr><td height="20" colspan="2"><?php echo '<a href="' . $link_page . '" ><h5 class="sp4">' . db_to_html($categories['categories_name']) . '</h5></a>';?></td>

									 </tr> 
									 <tr><td  colspan="2">
									 <p class="nr_right_table_p">
									<?php echo db_to_html(tep_db_prepare_input($categories['categories_first_sentence'])); ?>
									 </p>
									  </td>
									 </tr>
									<?php
										 $product_query_select_new = "select distinct p.products_id, ta.operate_currency_code, pd.products_name,p.products_price,p.products_tax_class_id from ".TABLE_PRODUCTS." as p, ".TABLE_PRODUCTS_DESCRIPTION." as pd, ".TABLE_PRODUCTS_TO_CATEGORIES." as p2c, " . TABLE_TRAVEL_AGENCY . " ta where  p.agency_id = ta.agency_id and p.products_id = pd.products_id and p2c.products_id = p.products_id and p2c.products_id = pd.products_id  and p2c.categories_id ='".$categories['categories_id']."' and pd.language_id='".(int) $languages_id."' and p.products_status=1 and p.featured_products=1  order by p.products_id desc LIMIT 2 ";
										$new_products_query_arrive = tep_db_query($product_query_select_new);
										  
											echo '<tr ><td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">';		
										  while ($new_products_arrive = tep_db_fetch_array($new_products_query_arrive)) {
											//amit modified to make sure price on usd
											if($new_products_arrive['operate_currency_code'] != 'USD' && $new_products_arrive['operate_currency_code'] != ''){
												$new_products_arrive['products_price'] = tep_get_tour_price_in_usd($new_products_arrive['products_price'],$new_products_arrive['operate_currency_code']);
											}
											//amit modified to make sure price on usd
											
												$dis_products_name = db_to_html($new_products_arrive['products_name']);
											
											echo '<tr><td valign="top" height="17" width="97%">- <a class="sp3" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products_arrive['products_id']) . '">' . $dis_products_name . '</a></td><td width="2%" align="right" nowrap="nowrap"><span class="sp5">'. $currencies->display_price($new_products_arrive['products_price'], tep_get_tax_rate($new_products_arrive['products_tax_class_id'])).'</span></td></tr>';
										 }
											
										echo '</table></td></tr>';
									?>
									</table>
									</div>
	                            <div class="nr_img_3"><a href="<?php echo $link_page; ?>" class="a_img_1"><?php echo tep_template_image_button('button_view_detail.gif')?></a></div>
							</div>
							 
							 <div class="xiao_bt_bottom"></div>
						 </div>
			    </div>
			
							<?php
					  
    }

// needed for the new products module shown below
    $new_products_category_id = $current_category_id;
?>

 					<div >
					       <div class="ladt_tt"><a href="javascript:scroll(0,0);" target="_self" class="a_3">TOP</a></div>
						   
					 </div>
					 
					 
