<?php
$div_id = 'Category_body';
if($cPathOnly=="33"){	//夏威夷
	$div_id = 'hawaii_lei';
}
?>
<div id="<?php echo $div_id;?>">

<?php 
if(!tep_not_null($cat_and_subcate_ids)){
	$cat_and_subcate_ids = tep_get_category_subcategories_ids($current_category_id);
}
 
if(strlen($cat_and_subcate_ids)<1){$cat_and_subcate_ids='0';}
?>

<?php require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/column_left.php'); ?>

<?php
if($cPathOnly=="33"){	//夏威夷 顶部广告 start
	//选两个夏威夷的团做为广告
	$hawaii_b_prod_ids = array();
	$hawaii_b_prod_ids[] = array('id'=>'370',
								'name'=>'三日西峡谷玻璃桥,拉斯维加斯两晚住大道最高地标',
								'text'=>'参观享有“世界娱乐之都”美誉的拉斯维加斯，拥有工业世界七大奇观之一美誉的胡佛大坝也不容错过');
	$hawaii_b_prod_ids[] = array('id'=>'1036',
								'name'=>'四日夏威夷檀香山经典之旅 B (品质团)',
								'text'=>'有三十年操作经验的资深夏威夷地接，为您提供极具品质的浪漫之旅，独家加长型礼宾车机场迎宾...');
	
	
?>
	<div class="hawaii_pro">
	<?php
	//大广告图
	$big_banner_hawaii = array('link'=> tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=2158'), 'img'=>'image/hawaii_banner_2.jpg');
	?>
	<a href="<?= $big_banner_hawaii['link']?>"><img src="<?= $big_banner_hawaii['img']?>" class="hawaii_banner"></a> 
	
		<div class="hawaii_two_pro">
		<div class="two_pro_t_l"></div>
		<div class="two_pro_t_r"></div>
		<div class="two_pro_b_l"></div>
		<div class="two_pro_b_r"></div>
		<?php
			for($i=0; $i<count($hawaii_b_prod_ids); $i++){
		?>
		<p><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $hawaii_b_prod_ids[$i]['id']);?>"><?php echo db_to_html($hawaii_b_prod_ids[$i]['name'])?></a><br>
		<?php echo db_to_html($hawaii_b_prod_ids[$i]['text'])?>
		</p>
		<?php
			}
		?>
		
		</div>
	</div>
<?php	
}
//夏威夷 顶部广告end
?>

<div id="right" class="rightside">

  <div class="Bread">
  
	   <?php
				$title_h1='';
				$category_breadcrumb = '';
				for ($ib=0, $nb=sizeof($breadcrumb->_trail); $ib<$nb; $ib++) {
					$link_class = '';
					if(($nb-1) != $ib){
						$link_class = ' class="breadlink_gray"';
						$category_breadcrumb .= '&nbsp;<a href="' . $breadcrumb->_trail[$ib]['link'] . '" '.$link_class.'>' . preg_replace('/ .+/','',$breadcrumb->_trail[$ib]['title']) . '</a>&nbsp;';
						$category_breadcrumb .= '&gt;';
					}else{
						$category_breadcrumb .=  '&nbsp;<span>' . trim(preg_replace('/ .+/','',$breadcrumb->_trail[$ib]['title'])) . '</span>';
						//$title_h1 =  '<h1 class="product-title">' . preg_replace('/ .+/','',$breadcrumb->_trail[$ib]['title']) . '&nbsp;|</h1>';
					}
				}
				echo $title_h1.'<p>'.$category_breadcrumb.'</p>';
		?>
  </div>
	    
<?php
if((int)$current_category_id && $cPathOnly!="33"){	//夏威夷不需要传统广告

	//幻灯片广告
	if(file_exists(DIR_FS_TEMPLATE_IMAGES."/catbanners/cat_".(int)$current_category_id.".txt") && ($cat_mnu_sel == 'tours' || $cat_mnu_sel == '')){ //banner display by file system
				$file_handle = @fopen(DIR_WS_TEMPLATE_IMAGES."/catbanners/cat_".(int)$current_category_id.".txt", "rb");
?>
				<script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>banner.js"></script>
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
				</div>					
<?php					
				fclose($file_handle);
	}else{ //幻灯片广告 end
	
		//广告系统内的广告
		$banner_ctop_obj = get_banners("Catalog List Top 650px");
		if(tep_not_null($banner_ctop_obj)){
			if(tep_not_null($banner_ctop_obj[0]['FinalCode'])){
				echo $banner_ctop_obj[0]['FinalCode'];
			}else{
				echo '<div style="float: left; width: 650px;"><a href="'.$banner_ctop_obj[0]['links'].'"><img border="0" alt="'.$banner_ctop_obj[0]['alt'].'" src="'.$banner_ctop_obj[0]['src'].'" /></a></div>';
			}
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
				$show_top_banner_cat_pic = 'image/pic7.jpg';
				}
			}	
						
?>
  		<div title="<?php echo $category['categories_top_banner_image_alt_tag']; ?>" style="float: left; width: 650px; background: url(<?php echo $show_top_banner_cat_pic;?>) no-repeat left top;"><div class="r_img_tt">		  
    	<div class="biaoti6"><h6><?php //echo db_to_html($category['categories_name']);?></h6></div>
            <div class="r_img_tt_1">
              <?php 					
			  if($category['categories_seo_description'] != '')  {
	 			 //echo tep_db_prepare_input(substr(db_to_html($category['categories_seo_description']),0,200)).'...';
				?>
              <?php } ?>
            </div>
	    </div></div>				
		    
<?php 			} // end of new banner check
			//传统目录顶部广告页面 end
		}
	}  

?>

<?php
//今日特惠 start
include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/today_recommended_for_list.php');
//今日特惠 end
?>
 
<?php
if($cPathOnly=="33"){	//夏威夷酒店搜索
	include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/hawaii_hotel_search.php');	
}
?>
		
		<div class="tab_prod_b"> 
	      <div class="tab_prod" id="tab_prod">
	        <ul>
	          <?php
				/*
				if($mnu == 'vacationpackages'){
				$sel_tourdefault = '';
				$sel_vacationpackagesdefault = 'class="s"';
				}else{
				$sel_tourdefault = 'class="s"';
				$sel_vacationpackagesdefault = '';
				}
				*/
				if($cat_mnu_sel == 'introduction'){
					$sel_intoroduction_style = 'class="s"';
				}else if($cat_mnu_sel == 'tours'){
					$sel_tours_style = 'class="s"';
				}else if($cat_mnu_sel == 'vcpackages'){
					$sel_vcpackages_style = 'class="s"';
				}else if($cat_mnu_sel == 'recommended'){
					$sel_recommended_style = 'class="s"';
				}else if($cat_mnu_sel == 'special'){
					$sel_special_style = 'class="s"';
				}else if($cat_mnu_sel == 'maps'){
					$sel_maps_style = 'class="s"';
				}else{
					$cat_mnu_sel = "tours";
					$sel_tours_style = 'class="s"';
				}
				?>
	          <li id="l_study" <?php echo $sel_tours_style; ?> style="width:auto; margin-right:5px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=tours');?>" id="h_study"><?php echo TEXT_TAB_TOURS; ?></a></li>
			    <li id="l_tech2" <?php echo $sel_vcpackages_style; ?> style="width:auto; margin-right:5px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=vcpackages');?>" id="h_tech2"><?php echo TEXT_TAB_VACATION_PACKAGES; ?></a></li>
			    <?php /*隐藏畅销行程
					<li id="h_tech" <?php echo $sel_recommended_style; ?> style="width:auto; margin-right:5px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=recommended');?>" id="h_tech"><?php echo TEXT_TAB_RECOMMENDED; ?></a></li>
					*/?>
	          
			  <?php if($cPathOnly!="33"){	//夏威夷取消特价Tag?>
	          <li id="l_tech" <?php echo $sel_special_style; ?> style="width:auto; margin-right:5px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=special');?>" id="h_tech"><?php echo TEXT_TAB_SPECIAL; ?></a></li>
			  <?php }?>
			  
			    <?php if($category['categories_map'] != ''){?>
	          <li id="l_tech1"<?php echo $sel_maps_style; ?> style="width:auto; margin-right:5px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=maps');?>" id="h_tech1"><?php echo TEXT_TAB_MAP; ?></a></li>
			    <?php } ?>
				
	          <?php if($cPathOnly!="33"){	//夏威夷取消景点介绍Tag?>
			  <?php if($category['categories_video_description'] != ''){ ?>
	          <li id="l_book" <?php echo $sel_intoroduction_style; ?> style="width:auto; margin-right:5px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=introduction');?>" id="h_book"><?php echo TEXT_TAB_INTRODUCTION; ?></a></li>
			    <?php } ?>
				<?php } ?>
				
	          </ul>
	      </div>
	    </div>
	    <div class="content2_prod">
		      
		      <div id="c_study">
		        <?php if($cat_mnu_sel == 'tours'){ //start of selected tab tour ?>
		        <div class="tab_biaoti"><div class="biaoti5"><h5><?php echo db_to_html($category['categories_name']);?></h5></div></div>
			     <?php
					//美西关注度、客户咨询试验代码 
					//先手动设置几个产品等试验通过后再开发程序
					if($cPath=='24'){	//此条件在其子级下面也有显示
					?>
		        <div class="tourlist_pro"> 
		          <?php 
						echo get_cat_pord_index($cPath);
						echo get_cat_pord_question($cPath);
						
					?>
	             </div>
			     <?php
					  }
					//美西关注度、客户咨询试验代码 end
					?>
		        
		        <?php
											
											define(INCLUDE_MODULE_CATEGORIES_ROOT_LISTING,'category_root_listing.php');
											if (tep_not_null(INCLUDE_MODULE_CATEGORIES_ROOT_LISTING)) {
											include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/'. INCLUDE_MODULE_CATEGORIES_ROOT_LISTING);
											
											}
											
							?>									 
		        <?php } //end of selected tab tour ?> 
	        </div>
				  
			  <div id="c_book" style="display:hidden;<?php echo $ext_des_style;?>">
			    <?php if($cat_mnu_sel == 'introduction'){ //start of introduction section tab 景点介绍?>
				  <?php
					/*/如果是黄石的目录则直接到去到折扣团广告页面
					$html_file = 'yellowstone_tw.html';
					if(CHARSET=='gb2312'){
						$html_file = 'yellowstone.html';
					}

					if($current_category_id=='35' && date('Y-m-d')>='2009-04-16' &&  date('Y-m-d')>='2009-06-16'){
						echo '<script type="text/JavaScript"><!--'."\n";
						echo 'document.location="'.HTTP_SERVER.'/landing-page/yellowstone0904/'.$html_file.'"';
						echo "\n".'//--></script>';
						exit;
					}*/
					?>
				  
			    <div class="tab_biaoti"><div class="biaoti5"><h5><?php echo db_to_html($category['categories_name']);?></h5></div></div> 
				  <div class="tab_video">
				    <div class="video">
					      <?php
									if(tep_not_null($category['categories_video'])){
											if(eregi("youtube.com",$category['categories_video']) || eregi("youku.com",$category['categories_video'])){ //amit added to check show from youtube
											?>	<object width="300" height="244">
					        <param name="movie" value="<?php echo $category['categories_video'];?>"></param><param name="wmode" value="transparent"></param><embed src="<?php echo $category['categories_video'];?>" type="application/x-shockwave-flash" wmode="transparent" width="300" height="244"></embed>
					        </object>
					      <?php
											}else { //amit added to check show from youtube

											$fileext = substr(strrchr($category['categories_video'], '.'), 1);											
											if($fileext == "flv"){
											$loadflashplayer = "true";
											}
											if( (substr($category['categories_video'],0,5)) == 'http:'  ){
											//write code to show direct video url
														if($loadflashplayer == "true"){
														//load with flash player
															?>
					      <object type="application/x-shockwave-flash" width="300" height="244"
																	wmode="transparent" data="flvplayer.swf?file=<?php echo urlencode($category['categories_video']);?>">
					        <param name="movie" value="flvplayer.swf?file=<?php echo urlencode($category['categories_video']);?>" />
					        <param name="wmode" value="transparent" />
					        </object>
					      
					      <?php														
														}else{
														//load with media palyer
														?>
					      <OBJECT id='mediaPlayer'  width="300" height="244"
																classid='CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95'
																codebase='http://activex.microsoft.com/activex/controls/ mplayer/en/nsmp2inf.cab#Version=5,1,52,701'
																standby='Loading Microsoft Windows Media Player components?' type='application/x-oleobject'>
					        <param name='fileName' value="<?php echo urlencode($category['categories_video']);?>">
					        <param name='animationatStart' value='1'>
					        <param name='transparentatStart' value='1'>
					        <param name='autoStart' value='1'>
					        <param name='ShowControls' value='1'>
					        <param name='ShowDisplay' value='0'>
					        <param name='ShowStatusBar' value='1'>
					        <param name='loop' value='0'>
					        <EMBED type='application/x-mplayer2'
																pluginspage='http://microsoft.com/windows/mediaplayer/ en/download/'
																id='mediaPlayer' name='mediaPlayer' displaysize='4' autosize='0'
																bgcolor='darkblue' showcontrols='1' showtracker='1'
																showdisplay='0' showstatusbar='1' videoborder3d='0' width="300" height="244"
																src="<?php echo urlencode($category['categories_video']);?>" autostart='1' designtimesp='5311' loop='0'>					          </EMBED>
					        </OBJECT>
					      <?php
														}
											}else{
											//write code to show video from own server
														if($loadflashplayer == "true"){
														//load with flash player
															?>
					      
					      <object type="application/x-shockwave-flash" width="300" height="244"
																	wmode="transparent" data="flvplayer.swf?file=<?php echo DIR_WS_VIDEO.$category['categories_video'];?>">
					        <param name="movie" value="flvplayer.swf?file=<?php echo DIR_WS_VIDEO.$category['categories_video'];?>" />
					        <param name="wmode" value="transparent" />
					        </object>
					      
					      <?php																				
														}else{
														//load with media palyer
														?>
					      <OBJECT id='mediaPlayer' width="300" height="244"
																classid='CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95'
																codebase='http://activex.microsoft.com/activex/controls/ mplayer/en/nsmp2inf.cab#Version=5,1,52,701'
																standby='Loading Microsoft Windows Media Player components?' type='application/x-oleobject'>
					        <param name='fileName' value="<?php echo DIR_WS_VIDEO.$category['categories_video'];?>">
					        <param name='animationatStart' value='1'>
					        <param name='transparentatStart' value='1'>
					        <param name='autoStart' value='1'>
					        <param name='ShowControls' value='1'>
					        <param name='ShowDisplay' value='0'>
					        <param name='ShowStatusBar' value='1'>
					        <param name='loop' value='0'>
					        <EMBED type='application/x-mplayer2'
																pluginspage='http://microsoft.com/windows/mediaplayer/ en/download/'
																id='mediaPlayer' name='mediaPlayer' displaysize='4' autosize='0'
																bgcolor='darkblue' showcontrols='1' showtracker='1'
																showdisplay='0' showstatusbar='1' videoborder3d='0' width="300" height="244"
																src="<?php echo DIR_WS_VIDEO.$category['categories_video'];?>" autostart='1' designtimesp='5311' loop='0'>					          </EMBED>
					        </OBJECT>							
					      <?php
														}
											}

											}//amit added to check show from youtube if end			
											
											}//end of check video available
											?>								
			        </div>
				    <div class="c_tt">
				      <?php echo db_to_html(stripslashes($category['categories_video_description'])); ?>			        </div>
				  </div>
				     
					 
				   <?php
	
						$category_intro_query_sql  = "select *  from " . TABLE_CATEGORIES_DESCRIPTION_INTRODUCTION . " where categories_id = '".$current_category_id."' order by categories_introduction_sort_order";
						$category_intro_query = tep_db_query($category_intro_query_sql);
						
						$tt_into_cnt_row = tep_db_num_rows($category_intro_query);
						//$category_intro = tep_db_fetch_array($category_intro_query);
						if($tt_into_cnt_row > 0){
							while($category_intro = tep_db_fetch_array($category_intro_query)){
							if($category_intro['categories_introduction_image_alt'] == ''){
							$category_intro['categories_introduction_image_alt'] = TEXT_ALTER_TAG;
							}else{
							$category_intro['categories_introduction_image_alt'] = db_to_html($category_intro['categories_introduction_image_alt']);
							}
							?>
				  <div class="tab_xunhuan">
				    
				    <?php if($category_intro['categories_introduction_image'] != ''){?>
				    <div class="tab_xunhuan_img">								 
				      <?php echo tep_image(DIR_WS_IMAGES . $category_intro['categories_introduction_image'],$category_intro['categories_introduction_image_alt']);?>				      </div>
				      <?php } ?>
				    <div class="tab_xunhuan_tt">
				      <div class="c_tt2">
				        <?php echo db_to_html($category_intro['categories_introduction_image_descirption']);?>				        </div>
				      </div>
				      <div class="ladt_tt">
				        <!--<a href="<?php //echo tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array( 'info', 'x', 'y')));?>#top" class="a_3">TOP</a>-->
				        <a href="javascript:scroll(0,0);" target="_self" class="a_3"><?php echo TEXT_DISPLAY_TOP;?></a>			          </div>
					       </div>
							  
						  <?php		
							
							}
							
						 }
						?>
				  <div style="width:50px;">&nbsp;</div>
			 	  <?php
						}  //end of introduction section tab
					?>
		      </div>
				  
			  <div id="c_tech2" style="display:hidden;">
			    <?php if($cat_mnu_sel == 'vcpackages'){ //start vackpackages 旅游套餐 ?>
			    <?php
									define(INCLUDE_MODULE_CATEGORIES_VACK_PACK_LISTING,'category_vackation_packages_listing.php');
									if (tep_not_null(INCLUDE_MODULE_CATEGORIES_VACK_PACK_LISTING)) {
									include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/'. INCLUDE_MODULE_CATEGORIES_VACK_PACK_LISTING);
									
									}
									
					?>		
				  <?php } //end of vacation package ?>				
		      </div>
		        
			  <div id="c_tech" style="display:hidden;">
			    <?php if($cat_mnu_sel == 'recommended'){ //start of recommended tours 推荐?>
			    <?php
				include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/categories_recommended_tours.php');
				?>
			    <?php } //end of recommended tours ?>		
		      </div>
			  <div id="c_tech3" style="display:hidden;">
			    <?php if($cat_mnu_sel == 'special'){ //start of special tours 特价行程?>
			    <?php
				include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/categories_special_tours.php');
				?>
			    <?php } //end of special tours ?>		
		      </div>
			  <div id="c_tech1" style="display:hidden;">
			    <?php if($cat_mnu_sel == 'maps'){ //start of map ?>
				    <?php if($category['categories_map'] != '')   {?>
				    <DIV id=top1text  >								
				      <?php echo $category['categories_map']; ?>				      </DIV>
							  <?php } ?>
			    <?php } //end of map ?>					
		      </div>
	    </div>

<?php
//产品列表底部的目录列表
include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/category_product_list_bottom_category.php');
?>
	   
	   </div>
<?php
//根据不同情况使用js隐藏不同一标签菜单
if($cPath=="183"){	//美东新年倒数团目录

?>
<script type="text/javascript">
if(document.getElementById('h_study')!=null){
	document.getElementById('h_study').innerHTML ="<?php echo db_to_html('跨年度假行程');?>";
}
if(document.getElementById('h_book')!=null){
	document.getElementById('h_book').style.display="none";
}
if(document.getElementById('h_tech2')!=null){
	document.getElementById('h_tech2').style.display="none";
}
if(document.getElementById('h_tech1')!=null){
	document.getElementById('h_tech1').style.display="none";
}
if(document.getElementById('h_tech')!=null){
	document.getElementById('h_tech').style.display="none";
}

</script>
<?php }?>
<?php
//买二送一、买二送二的特价列表中去掉短期旅行和度假套餐的标签
if($cPath=="184" && $cat_mnu_sel=='vcpackages'){
?>
<script type="text/javascript">
if(document.getElementById('l_study')!=null){
	document.getElementById('l_study').style.display="none";
}
if(document.getElementById('l_tech')!=null){
	document.getElementById('l_tech').style.display="none";
}
</script>
<?php }?>

</div>
