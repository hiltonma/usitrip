

			    <div class="product_detail_content">				
				<div class="product_tab_b_n"> 
				  <div class="tab1" id="tab1">
						<ul>
							
							<?php 
									if(isset($HTTP_GET_VARS['mnu']) && $HTTP_GET_VARS['mnu'] == 'qanda'){								
										$tourdetailsdispaly = '';
										$reviewdisplay = '';
										$pricesdisplay = '';
										$qandadisplay = 'class="s"';
										$videodisplay = '';
									}elseif(isset($HTTP_GET_VARS['mnu']) && $HTTP_GET_VARS['mnu'] == 'prices'){
										$qandadisplay = '';
										$pricesdisplay = 'class="s"';
										$reviewdisplay = '';
										$tourdetailsdispaly = '';
										$videodisplay = '';
									}elseif(isset($HTTP_GET_VARS['mnu']) && $HTTP_GET_VARS['mnu'] == 'reviews'){
										$qandadisplay = '';
										$pricesdisplay = '';
										$reviewdisplay = 'class="s"';
										$tourdetailsdispaly = '';
										$videodisplay = '';
									}elseif(isset($HTTP_GET_VARS['mnu']) && $HTTP_GET_VARS['mnu'] == 'photos'){
										$qandadisplay = '';
										$pricesdisplay = '';
										$reviewdisplay = '';
										$tourdetailsdispaly = '';
										$photodisplay = 'class="s"';
										$videodisplay = '';
									}elseif(isset($HTTP_GET_VARS['mnu']) && $HTTP_GET_VARS['mnu'] == 'video' && $product_info['products_video'] != '' ){
										$qandadisplay = '';
										$reviewdisplay = '';
										$tourdetailsdispaly = '';
										$photodisplay = '';
										$videodisplay ='class="s"';
									}else{	
										$mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "tours";					
										$qandadisplay = '';
										$pricesdisplay = '';
										$reviewdisplay = '';
										$tourdetailsdispaly = 'class="s"';
										$videodisplay = '';
									}
									
									
									//JS快速切换页面开关
									
									$display_fast = false;
									if(defined('USE_JS_SHOW_PRODUCT_DETAIL_CONTENT') && USE_JS_SHOW_PRODUCT_DETAIL_CONTENT=='true'){
										$display_fast = true;
									}
									
									$c_study_display="";
									$c_book_display="";
									$c_tech_display="";
									$c_photos_display="";
									$c_review_display="";
									
									if($display_fast == true){
										if($mnu != 'tours'){
											$c_study_display = ' style="display:none" ';
										}
										if($mnu != 'prices'){
											$c_book_display = ' style="display:none" ';
										}
										if($mnu != 'qanda'){
											$c_tech_display = ' style="display:none" ';
										}
										if($mnu != 'photos'){
											$c_photos_display= ' style="display:none" ';
										}
										if($mnu != 'reviews'){
											$c_review_display= ' style="display:none" ';
										}
									}
									?>
							
							<?php
							if($display_fast!=true){	//传统菜单
							?>
								<li <?php echo $tourdetailsdispaly;?>><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','mnu','page','rn')));?>" id="h_study"><?php echo TAB_TEXT_TOUR_DETAILS;?></a></li>
								<?php if($content!="product_info_vegas_show"){?>
								<li <?php echo $pricesdisplay;?>><a style="background:#FFF7D8; color:#F7860F;" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=prices&'.tep_get_all_get_params(array('info','mnu','page')));?>" id="h_book"><?php echo db_to_html('价格明细');//echo TAB_TEXT_REVIEW;?></a></li>
								<?php
								}
								?>
								<li <?php echo $qandadisplay;?>><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=qanda&'.tep_get_all_get_params(array('info','mnu','rn')));?>" id="h_tech"><?php echo TAB_TEXT_QUESTIONS_AND_ANSWERS;?></a></li>
								<?php if($content!="product_info_vegas_show"){?>
								<li <?php echo $photodisplay;?>><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=photos&'.tep_get_all_get_params(array('info','mnu','rn')));?>" id="h_photo"><?php echo TAB_TEXT_PHOTOS; ?></a></li>
								<?php
								}
								?>
								<li <?php echo $reviewdisplay;?>><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=reviews&'.tep_get_all_get_params(array('info','mnu','rn')));?>" id="h_review"><?php echo TAB_TEXT_REVIEW;?></a></li>
								<?php if($product_info['products_video'] != ''){?>
								<li <?php echo $videodisplay;?>><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=video&'.tep_get_all_get_params(array('info','mnu','rn')));?>" id="h_video"><?php echo TAB_TEXT_VIDEO; ?></a></li>
								
								<?php }?>
							
							<?php
							}//传统菜单end
							
							//JS快速切换菜单
							if($display_fast==true){	
							?>
								<li id="l_study" <?php echo $tourdetailsdispaly;?>><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('info','mnu','page','rn')));?>" onClick="shows_product_detail_content('c_study');stop_goto(this);" id="h_study"><?php echo TAB_TEXT_TOUR_DETAILS;?></a></li>
								<?php if($content!="product_info_vegas_show"){?>
								<li id="l_book" <?php echo $pricesdisplay;?>><a style="background:#FFF7D8; color:#F7860F;" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=prices&'.tep_get_all_get_params(array('info','mnu','page')));?>" onClick="shows_product_detail_content('c_book');stop_goto(this);" id="h_book"><?php echo db_to_html('价格明细');//echo TAB_TEXT_REVIEW;?></a></li>
								<?php
								}
								?>
								
								<li id="l_tech" <?php echo $qandadisplay;?>><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=qanda&'.tep_get_all_get_params(array('info','mnu','rn')));?>" onClick="shows_product_detail_content('c_tech');stop_goto(this);" id="h_tech"><?php echo TAB_TEXT_QUESTIONS_AND_ANSWERS;?></a></li>
								
								<?php if($content!="product_info_vegas_show"){?>
								<li id="l_photos" <?php echo $photodisplay;?>><a  href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=photos&'.tep_get_all_get_params(array('info','mnu','rn')));?>" onClick="shows_product_detail_content('c_photos');stop_goto(this);" id="h_photo"><?php echo TAB_TEXT_PHOTOS; ?></a></li>
								<?php
								}
								?>
								
								<li id="l_review" <?php echo $reviewdisplay;?>><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=reviews&'.tep_get_all_get_params(array('info','mnu','rn')));?>" onClick="shows_product_detail_content('c_review');stop_goto(this);" id="h_review"><?php echo TAB_TEXT_REVIEW;?></a></li>
								<?php if($product_info['products_video'] != ''){?>
								<li id="l_video" <?php echo $videodisplay;?>><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=video&'.tep_get_all_get_params(array('info','mnu','rn')));?>" onClick="shows_product_detail_content('c_video');stop_goto(this);" id="h_video"><?php echo TAB_TEXT_VIDEO; ?></a></li>
								
								<?php }?>
							
							<script type="text/javascript">
								function shows_product_detail_content(show_id){
									var l_study = document.getElementById('l_study');
									var l_book = document.getElementById('l_book');
									var l_tech = document.getElementById('l_tech');
									var l_photos = document.getElementById('l_photos');
									var l_review = document.getElementById('l_review');
									var l_video = document.getElementById('l_video');
									
									var c_study = document.getElementById('c_study');
									var c_book = document.getElementById('c_book');
									var c_tech = document.getElementById('c_tech');
									var c_photos = document.getElementById('c_photos');
									var c_review = document.getElementById('c_review');
									var c_video = document.getElementById('c_video');
																		
									l_study.className="";
									c_study.style.display="none";
									
									if(l_book!=null && c_book!=null){
										l_book.className="";
										c_book.style.display="none";
									}
									
									l_tech.className="";
									c_tech.style.display="none";
									
									if(l_photos!=null && c_photos!=null){
										l_photos.className="";
										c_photos.style.display="none";
									}
									
									l_review.className="";
									c_review.style.display="none";
									
									if(l_video!=null && c_video!=null){
										l_video.className="";
										c_video.style.display="none";
									}
									
									var show_obj = document.getElementById(show_id);
									show_obj.style.display="";
									
									document.getElementById(show_id.replace(/c\_/g,'l_')).className="s";
									
								}
								function stop_goto(obj){
									obj.href = 'JavaScript:void(0);';
								}
							</script>
							
							<?php
							}
							//JS快速切换菜单 end
							?>
							
							
						</ul>
				  </div>
				  </div>				
				</div>
				
				<div class="product_detail_content">
				<?php //行程内容页?>
				<div id="c_study" <?=$c_study_display?>>
				      <?php if($mnu == 'tours' || $display_fast==true){ //start of tour section tab ?>  
					  
						<?php include('product_info_module2_description.php');//行程内容?>	
										
				<?php } //end of tour section tab ?>
				
				
				 </div>   
				
				<?php //行程内容页end?>
				
		        <?php //价格明细?>
				<?php if($content!="product_info_vegas_show"){?>
				<div id="c_book" <?=$c_book_display?>>
				<?php
				if($mnu == 'prices' || $display_fast==true){ //start of prices section tab 
				?> 
						<div id="review_desc_body">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left:20px">
						  <tr>
							<td>
							<?php include('product_info_module_right_3.php');//价格?>
							</td>
						  </tr>
						  <tr>
							<td><?php include('product_info_module_right_includes.php');//费用包括?></td>
						  </tr>
						  <tr>
							<td><?php include('product_info_module_right_excludes.php');//费用不包括?></td>
						  </tr>
						</table>

						</div>	
				<?php } //end of prices section tab ?>   		
				</div>
				<?php
				}
				?>
				<?php //价格明细end?>
				
				<?php //问题咨询?>
				<div id="c_tech" <?=$c_tech_display?>>
				<?php
				if($mnu == 'qanda'  || $display_fast==true ){ //start of question and answers section tab
				?>   
				<?php
				//question_info.php
				include(FILENAME_QUESTION_INFO);
				?>
				<?php } //end of question and answers section tab ?>
				</div>		
                <?php //问题咨询end?>
               
			    <?php
				if($content!="product_info_vegas_show"){
				?>
				<div id="c_photos" <?=$c_photos_display?>>
				<!--photos-->
				<?php if($mnu == 'photos' || $display_fast==true ){ //start of question and answers section tab ?>   
					<?php
					include(FILENAME_REVIEWS_PHOTOS);
					?>
				<?php } //end of question and answers section tab ?>
				<!--photos end-->
				</div>	
               <?php }?>
			   
			   <?php //评价回访?>
			    <div id="c_review" <?=$c_review_display?>>
				<?php
				if($mnu == 'reviews' || $display_fast==true ){
					include('product_reviews_tabs_ajax.php');
				}
				?>
				</div>	
			   <?php //评价回访end?>
				
				
				<?php if($product_info['products_video'] != ''){?>
				<div id="c_video" style="width:615px;">
				<?php if($mnu == 'video' || $display_fast==true ){ //start of question and answers section tab ?>   
					<?php
					include('tours_video.php');
					?>
				<?php } //end of question and answers section tab ?>
				</div>	
				<?php }?>
				
				<div class="ladt_tt_t_n"><br />
                <br />
 <a href="javascript:scroll(0,0);" target="_self" class="a_3"><?php echo TEXT_DISPLAY_TOP; ?></a>
 <br /></div>
							
				</div>					 



