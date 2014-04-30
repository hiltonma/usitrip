<a name="top"></a>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	 <tr>
    <td  colspan="2" nowrap="nowrap" class="pageHeading">
       <?php 
     $heading_text_box = $category['categories_name'];

               if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_heading_title'])) ) {
                 echo db_to_html($category['categories_heading_title']);
               } else {
                 echo HEADING_TITLE;
                 $heading_text_box = $category['categories_name'];
                 echo   db_to_html($heading_text_box);
               }
             ?>
   </td>	
  </tr>
  <tr>
    <td width="190" valign="top"><?php require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/column_left.php'); ?></td>
	<td width="100%" valign="top">
		 <div id="down1">	  
	   <div id="right">
	   
	    <?php if($current_category_id == '24'){ //west coast top Tours ?>	 		
		 	<div style="float: left; width: 591px;">
		  		<script type="text/javascript">
				var m_nPageInitTime = new Date();
				var MainTopRoll = new xwzRollingImageTrans("IMG_MAIN_TOP_ROLL_DETAIL", "IMGS_MAIN_TOP_ROLL_THUMBNAIL");
				MainTopRoll.addItem("<?php echo HTTP_SERVER?>","images/west_coast_top_banner_shower_01.jpg");
				MainTopRoll.addItem("<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=192');?>","images/west_coast_top_banner_shower_02.jpg");
				MainTopRoll.addItem("<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=191');?>","images/west_coast_top_banner_shower_03.jpg");
				MainTopRoll.addItem("<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=205');?>","images/west_coast_top_banner_shower_04.jpg");
				</script>
				  <table width="591" style="height:175px;" align="center" cellpadding="0" cellspacing="0">
					<tbody>
					  <tr>
						<td width="481" style="height:175px;" align="left" id="IDS_DIV_MAIN_TOP_ROLL_DETAIL"><img 
							  class="clssMainRoll" height="175" 
							  src="images/west_coast_top_banner_shower_01.jpg" 
							  width="476" border="0" name="IMG_MAIN_TOP_ROLL_DETAIL" alt="" /></td>       
						<td width="115"><table cellspacing="0" cellpadding="0" align="center">
							<tbody>
							  <tr>
								<td style="height:45px;" align="right" valign="top"><img style="DISPLAY: none" src="images/" height="5"  width="9" align="middle" border="0" 
									name="IMGS_MAIN_TOP_ROLL_THUMBNAIL" alt="" /><img 
									src="images/west_coast_top_banner_mini_01.jpg" width="110" height="40" 
									border="0" 
									style="CURSOR: pointer" onclick="MainTopRoll.alterImage(0);" alt="" /></td>
							  </tr>
							  <tr>
								<td style="height:45px;" align="right" valign="top"><img style="DISPLAY: none" 
									height="5" width="9" align="middle" border="0" src="images/" 
									name="IMGS_MAIN_TOP_ROLL_THUMBNAIL" alt="" /><img 
									src="images/west_coast_top_banner_mini_02.jpg" width="110" height="40" 
									border="0" 
									style="CURSOR: pointer" onclick="MainTopRoll.alterImage(1);" alt="" /></td>
							  </tr>
							  <tr>
								<td style="height:45px;" align="right" valign="top"><img style="DISPLAY: none" 
									height="5"  width="9" align="middle" border="0" src="images/"
									name="IMGS_MAIN_TOP_ROLL_THUMBNAIL" alt="" /><img 
									src="images/west_coast_top_banner_mini_03.jpg" width="110" height="40" 
									border="0" 
									style="CURSOR: pointer" onclick="MainTopRoll.alterImage(2);" alt="" /></td>
							  </tr>
							  <tr>
								<td align="right" style="height:40px;"><img style="DISPLAY: none" 
									height="5"  width="9" align="middle" border="0" src="images/"
									name="IMGS_MAIN_TOP_ROLL_THUMBNAIL" alt="" /><img 
									src="images/west_coast_top_banner_mini_04.jpg" width="110" height="40" 
									border="0" 
									style="CURSOR: pointer" onclick="MainTopRoll.alterImage(3);" alt="" /></td>
							  </tr>
							</tbody>
						</table></td>
					  </tr>
					</tbody>
				</table>
				<script type="text/javascript">
				MainTopRoll.Index =  parseInt('0');
				MainTopRoll.install();
				</script>
		 </div>
	    <?php }else{ //no new banner uploaded
	  
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
		  <div title="<?php echo $category['categories_top_banner_image_alt_tag']; ?>" style="float: left; width: 591px; background: url(<?php echo $show_top_banner_cat_pic;?>) no-repeat left top;"><div class="r_img_tt">		  
		    <div class="biaoti6"><h6><?php echo db_to_html($category['categories_name']);?></h6></div>
            <div class="r_img_tt_1">
			  <?php 					
			  if($category['categories_seo_description'] != '')  {
	 			 echo tep_db_prepare_input(substr(db_to_html($category['categories_seo_description']),0,200)).'...';
				?>
			<?php } ?>
			 </div>
		  </div></div>				
		  
		  <?php } // end of new banner check ?>
		  
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
				}else if($cat_mnu_sel == 'maps'){
					$sel_maps_style = 'class="s"';
				}else{
					$cat_mnu_sel = "tours";
					$sel_tours_style = 'class="s"';
				}
				?>
					<?php if($category['categories_video_description'] != ''){ ?>
					<li <?php echo $sel_intoroduction_style; ?> style="width:125px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=introduction');?>" id="h_book"><?php echo TEXT_TAB_INTRODUCTION; ?></a></li>
					<?php } ?>
					<li <?php echo $sel_tours_style; ?> style="width:80px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=tours');?>" id="h_study"><?php echo TEXT_TAB_TOURS; ?></a></li>
					<li <?php echo $sel_vcpackages_style; ?> style="width:155px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=vcpackages');?>" id="h_tech2"><?php echo TEXT_TAB_VACATION_PACKAGES; ?></a></li>
					<li <?php echo $sel_recommended_style; ?> style="width:135px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=recommended');?>" id="h_tech"><?php echo TEXT_TAB_RECOMMENDED; ?></a></li>
					<?php if($category['categories_map'] != ''){?>
					<li <?php echo $sel_maps_style; ?> style="width:70px;"><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath='.$cPath.'&mnu=maps');?>" id="h_tech1"><?php echo TEXT_TAB_MAP; ?></a></li>
					<?php } ?>
				
				</ul>
		  </div>
		  </div>
		  <div class="content2_prod">
		   		
		         <div id="c_study">
				  <?php if($cat_mnu_sel == 'tours'){ //start of selected tab tour ?>
				   	<div class="tab_biaoti"><div class="biaoti5"><h5><?php echo db_to_html($category['categories_name']);?></h5></div></div>
					  
						  <?php
											
											define(INCLUDE_MODULE_CATEGORIES_ROOT_LISTING,'category_root_listing.php');
											if (tep_not_null(INCLUDE_MODULE_CATEGORIES_ROOT_LISTING)) {
											include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/' . $template_name . INCLUDE_MODULE_CATEGORIES_ROOT_LISTING);
											
											}
											
							?>									 
					<?php } //end of selected tab tour ?> 
		      </div>
				
				<div id="c_book" style="overflow:hidden;<?php echo $ext_des_style;?>">
				<?php if($cat_mnu_sel == 'introduction'){ //start of introduction section tab ?>
				<div class="tab_biaoti"><div class="biaoti5"><h5><?php echo db_to_html($category['categories_name']);?></h5></div></div> 
					<div class="tab_video">
					<div class="video">
									<?php
									if(tep_not_null($category['categories_video'])){
											if(eregi("youtube.com",$category['categories_video'])){ //amit added to check show from youtube
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
																src="<?php echo urlencode($category['categories_video']);?>" autostart='1' designtimesp='5311' loop='0'>
																</EMBED>
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
																src="<?php echo DIR_WS_VIDEO.$category['categories_video'];?>" autostart='1' designtimesp='5311' loop='0'>
																</EMBED>
															</OBJECT>							
														<?php
														}
											}

											}//amit added to check show from youtube if end			
											
											}//end of check video available
											?>								
					</div>
					<div class="c_tt">
					<?php echo db_to_html(stripslashes($category['categories_video_description'])); ?>
					
					</div>
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
								 	<?php echo tep_image(DIR_WS_IMAGES . $category_intro['categories_introduction_image'],$category_intro['categories_introduction_image_alt']);?>
								 </div>
								 <?php } ?>
								 <div class="tab_xunhuan_tt">
										 <div class="c_tt2">
										 <?php echo db_to_html($category_intro['categories_introduction_image_descirption']);?>
										 </div>
								 </div>
								 <div class="ladt_tt">
								 <!--<a href="<?php //echo tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array( 'info', 'x', 'y')));?>#top" class="a_3">TOP</a>-->
								 <a href="javascript:scroll(0,0);" target="_self" class="a_3"><?php echo TEXT_DISPLAY_TOP;?></a>
								 </div>
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
				
				<div id="c_tech2" style="overflow:hidden;">
				<?php if($cat_mnu_sel == 'vcpackages'){ //start vackpackages ?>
				  <?php
									define(INCLUDE_MODULE_CATEGORIES_VACK_PACK_LISTING,'category_vackation_packages_listing.php');
									if (tep_not_null(INCLUDE_MODULE_CATEGORIES_VACK_PACK_LISTING)) {
									include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/' . $template_name . INCLUDE_MODULE_CATEGORIES_VACK_PACK_LISTING);
									
									}
									
					?>		
					<?php } //end of vacation package ?>				
				</div>
		      
				<div id="c_tech" style="overflow:hidden;">
				<?php if($cat_mnu_sel == 'recommended'){ //start of recommended tours ?>
				<?php
				include(DIR_FS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/' . $template_name .'categories_recommended_tours.php');
				?>
				<?php } //end of recommended tours ?>		
				</div>
				<div id="c_tech1" style="overflow:hidden;">
				<?php if($cat_mnu_sel == 'maps'){ //start of map ?>
							<?php if($category['categories_map'] != '')   {?>
								<DIV id=top1text  >								
									<?php echo $category['categories_map']; ?>																							
                                </DIV>
								<?php } ?>
				<?php } //end of map ?>					
				</div>
		  </div>
	   </div>
	
	</div>
	
	</td>
  </tr>
  <tr>
  <td colspan="2"><div class="down2">
	  <div class="down2_a">
	     <div class="down2_a_1">
		 <table   border="0" cellspacing="0" cellpadding="0">
			  <tr>
				 <?php
					for ($ib=0, $nb=sizeof($breadcrumb->_trail); $ib<$nb; $ib++) {
						echo  '<td>&nbsp;<a href="' . $breadcrumb->_trail[$ib]['link'] . '" >' . $breadcrumb->_trail[$ib]['title'] . '</a>&nbsp;</td>';
						if(($nb-1) != $ib){
							echo '<td width="13"><img src="image/xx.gif" alt="" /></td>';
						}
					}
				?>
			  </tr>
			</table>
		 </div>
		 <div class="down2_a_2">
		     <table width="746" border="0"  cellpadding="2" cellspacing="2" >			 
			 <tr>
			 <?php
			  $categories_navigate_query = tep_db_query("select c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id ='".$current_category_id."'  and c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."'");
 			  if ($categories_navigate = tep_db_fetch_array($categories_navigate_query))  {
			  $categories_navigate_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id ='".$categories_navigate['parent_id']."' and  c.categories_id !='".$current_category_id."' and  c.categories_id = cd.categories_id and c.categories_status='1' and cd.language_id='" . $languages_id ."' order by c.sort_order, cd.categories_name");
 			  $inav_row_c =0;
			  while ($categories_navigate = tep_db_fetch_array($categories_navigate_query))  {
			  echo "<td><a href='". tep_href_link(FILENAME_DEFAULT, 'cPath=' . $categories_navigate['categories_id'])."' >".db_to_html($categories_navigate['categories_name']).'</a></td>';
			  $inav_row_c++;
			  if($inav_row_c % 4 == 0){
				  if(mysql_num_rows($categories_navigate_query)!=$inav_row_c)
				  {
						echo '</tr>';
						echo '<tr>';
				  }
			  //echo '</tr><tr>';
			  }
			  }
			  }
			 ?>
			 </tr>
		    </table>
		 </div>
	  </div>
	</div>   </td>
  </tr>
</table>
