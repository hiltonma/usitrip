<?php
//check if new banner is available
if((int)$current_category_id && $cPathOnly!="33"){	//夏威夷不需要传统广告
	//幻灯片广告
	$slide_advertising = false;
	if($slide_advertising == true && file_exists(DIR_FS_TEMPLATE_IMAGES."/catbanners/cat_".(int)$current_category_id.".txt") && ($cat_mnu_sel == 'tours' || $cat_mnu_sel == '')){ //banner display by file system
				$file_handle = @fopen(DIR_WS_TEMPLATE_IMAGES."/catbanners/cat_".(int)$current_category_id.".txt", "rb");
?>
				<div class="banner"><script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>banner.js"></script>
				<div id="MainCatProBanner">
				<div id="SlidePlayer">
					<ul class="Slides">
					<?php
					while (!feof($file_handle) ) {
					$line_of_text = fgets($file_handle);
					$cat_banner_info_splits = explode('|!!|', $line_of_text);
					//los-angeles-tours-top-banner.jpg|!!|Los Ageles|!!|los-angeles-tours/
						if($cat_banner_info_splits[0] != ''){ ?>
							<li><a target="<?php echo $cat_banner_info_splits[3]?>" href="<?php echo $cat_banner_info_splits[1]; ?>"><?php echo tep_image(DIR_WS_IMAGES . $cat_banner_info_splits[0], db_to_html($cat_banner_info_splits[2]));?></a></li>
						<?php	 
						}
					}
					?>
					</ul>
				</div>
				<script type="text/javascript">
				  TB.widget.SimpleSlide.decoration('SlidePlayer', {eventType:'mouse', effect:'scroll'});
				</script>
				</div></div>				
<?php					
				fclose($file_handle);
	}else{ //幻灯片广告 end

		//广告系统内的广告
		$banner_ctop_obj = get_banners("Catalog List Top 745px");
		if(tep_not_null($banner_ctop_obj)){
			echo '<div class="banner">';
			if(tep_not_null($banner_ctop_obj[0]['FinalCode'])){
				echo $banner_ctop_obj[0]['FinalCode'];
			}else{
				echo '<div style="float: left; width: 745px;"><a href="'.$banner_ctop_obj[0]['links'].'"><img border="0" alt="'.$banner_ctop_obj[0]['alt'].'" src="'.$banner_ctop_obj[0]['src'].'" /></a></div>';
			}
			echo '</div>';
		}else{
			//传统目录顶部广告页面
			if($category['categories_banner_image'] != ''){
				$show_top_banner_cat_pic = DIR_WS_IMAGES . $category['categories_banner_image'];
			}else{
				if($showdh == '2'){
				//west cost
				$show_top_banner_cat_pic = 'image/pic7.jpg';
				}else if($showdh == '3'){
				//east coast
				$show_top_banner_cat_pic = 'image/cat_top_eastcoast.jpg';
				}else if($showdh == '4'){
				//hawaii
				$show_top_banner_cat_pic = 'image/cat_top_hawaii.jpg';
				}else if($showdh == '5'){
				//folida
				$show_top_banner_cat_pic = 'image/cat_top_florida.jpg';
				}else if($showdh == '7'){
				//by city
				$show_top_banner_cat_pic = 'image/cat_top_city.jpg';
				}else{
				$show_top_banner_cat_pic = 'image/pic6.jpg';
				}
			}	
			
?>
		<div class="banner"><div title="<?php echo $category['categories_top_banner_image_alt_tag']; ?>" style="float: left; width: 650px; background: url(<?php echo $show_top_banner_cat_pic;?>) no-repeat left top;">
		<div class="r_img_tt">
		<div class="biaoti6" id="h_6_title"><h6><?php //echo db_to_html($category['categories_name']);?></h6></div>
		<div class="r_img_tt_1">
	<?php if($category['categories_seo_description'] != '')  {
		//echo tep_db_prepare_input(substr(db_to_html($category['categories_seo_description']),0,220)).'...';
	?>
	<?php } ?>
		</div>
		</div>
  		</div></div>
  
<?php 			} // end of new banner check
			//传统目录顶部广告页面 end
		}
	}  

?>