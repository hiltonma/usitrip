<?php
//取得今日特惠的产品2个
if(tep_not_null($cPath) && is_array($cPath_array)){
	$top_r_products = array();
	for($i=(count($cPath_array)-1); $i>=0; $i--){
		$p_id_sql = tep_db_query('SELECT p.products_id,p.products_image, 
								 pttrp.products_small_name, pttrp.products_small_description, pttrp.products_small_image, 
								 pd.products_name, pd.products_small_description as pd_description 
								 FROM `categories_to_today_recommended_products` pttrp, `products` p, `products_description` pd
								 WHERE pttrp.categories_id="'.$cPath_array[$i].'" AND p.products_id=pttrp.products_id AND pd.products_id=pttrp.products_id AND p.products_status = "1" AND pd.language_id ="1" Order By sort_order ASC Limit 2 ');
		while($p_id_rows = tep_db_fetch_array($p_id_sql)){
			$t_products_id = $p_id_rows['products_id'];
			$t_small_name = tep_not_null($p_id_rows['products_small_name']) ? $p_id_rows['products_small_name'] : $p_id_rows['products_name'];
			$t_description = tep_not_null($p_id_rows['products_small_description']) ? $p_id_rows['products_small_description'] : $p_id_rows['pd_description'];
			$t_image = tep_not_null($p_id_rows['products_small_image']) ? $p_id_rows['products_small_image'] : $p_id_rows['products_image'];
			$top_r_products[] = array('id'=>$t_products_id,'name'=>$t_small_name,'description'=>$t_description,'image'=>$t_image);
		}
	}
	//echo $cPath_array[$i].'<br>';
	//print_r($top_r_products);
	if((int)count($top_r_products)){
?>
<div class="nei-leftside post_today">
	<div class="nei-leftside-top"><b></b><span></span></div>
  <div class="leftside-box">
  <h3 style="border-bottom: 1px solid #58BAF9" ><?php echo db_to_html('今日特别推荐')?></h3>
  <div class="chufa-city">
		
			<?php
			for($i=0; $i<min(count($top_r_products), 2); $i++){
				if(preg_match('/^http[s]*:\/\//',$top_r_products[$i]['image'])){
					$image_src = $top_r_products[$i]['image'];
				}else{
					$image_src = DIR_WS_IMAGES . $top_r_products[$i]['image'];
				}
			?>
			
			<div class="top_prod_box" id="top_prod_<?=$top_r_products[$i]['id']?>">
				<div class="top_prod_image" id="top_prod_image_<?=$top_r_products[$i]['id']?>"><a href="<?php echo  tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $top_r_products[$i]['id']); ?>"><?php echo tep_image($image_src, db_to_html($top_r_products[$i]['name']), SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) ;?></a></div>
				<div class="top_prod_right" id="top_prod_right_<?=$top_r_products[$i]['id']?>">
					<div class="top_prod_name" id="top_prod_name_<?=$top_r_products[$i]['id']?>"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $top_r_products[$i]['id']); ?>"><?php echo cutword(db_to_html($top_r_products[$i]['name']),46) ;?></a></div>
					<div class="top_prod_description" id="top_prod_description_<?=$top_r_products[$i]['id']?>">
					<?php echo cutword(db_to_html(strip_tags($top_r_products[$i]['description'])),46) ;?>
					</div>
					<div class="top_prod_details_button" id="top_prod_details_button_<?=$top_r_products[$i]['id']?>">
					<a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $top_r_products[$i]['id']); ?>"><?php echo db_to_html('查看详情') ;?></a>
					</div>
				</div>
			</div>
			
			<?php }?>
	
	</div>
  </div>
  <div class="nei-leftside-bottom"><b></b><span></span></div>
<div class="clear"></div></div>

<?php
	}
}
?>