<div id="all">
    <!--<div style="width: 100%; height:70px; background-color:#172848;	text-align: center;" ><img src="<?php echo HTTP_SERVER?>/image/topbanner.gif" /></div>-->
	<div id="contain">
	    <div class="content1">
		  <div class="content1_text">
		  		<div class="font_1" style="width:490px;" id="topmenufirstsentence" >
				<a href="javascript:void(0);" rel="first_sentence_tour" style="font-family:Arial, Tahoma, Verdana; font-size:12px; color:#FFFFFF; text-decoration:none"><?php echo stripslashes(mb_substr($first_sentence,0,43,CHARSET)); ?></a>...<?php echo tep_draw_separator('pixel_trans.gif', '20', '1'); ?>
				</div>
				<div id="first_sentence_tour" class="dropmenufirstsentencediv" style="width: 700px;"><?php echo $first_sentence; ?></div>				
				
				
				<div class="content1_img" align="right" style="white-space:nowrap;"><img src="image/phone.gif" alt="" /></div>
				<div class="font_2" align="right" style="white-space:nowrap;">1-626-898-7800 <span style="color: #0066CC">|</span> 0086-4006-333-926</div>
		  </div>
		</div>
	</div>
    <div id="content">
	  <div id="head">
	     <div id="caput">		       
			   <div id="caput_text">
			      <div class="caput_text_1">
				   <div class="text_1" style="white-space:nowrap">
				   <?php
				   if (!tep_session_is_registered('customer_id')) {
			 		echo '<div class="font_3">'.TEXT_HEADER_WELCOME_TO.'&nbsp;&nbsp;<span class="sp2"><a class="ff_a" href="' . tep_href_link(FILENAME_LOGIN) . '">'.TEXT_HEADER_JOIN_TODAY.'</a></span></div>';
					echo '<div class="font_3">'.TEXT_HEADER_ALREADY_A_MEMBER.'&nbsp;&nbsp;<span class="sp2"><a class="ff_a" href="' . tep_href_link(FILENAME_LOGIN) . '">'.TEXT_TOP_LINK_REGISTER_LOGIN.'</a></span></div>';
				   }else {
			  		echo '<div class="font_3"><a class="ff_a" href="' . tep_href_link(FILENAME_ACCOUNT,"", "SSL") . '">' . HEADER_TITLE_MY_ACCOUNT . ' </a></div>';
			 		echo '<div class="font_3"><a class="ff_a" href="' . tep_href_link(FILENAME_LOGOFF) . '">' . HEADER_TITLE_LOGOFF . ' </a></div>'; 
				   }
				   ?>				 
				   </div>
			     </div>
				   <div class="caput_text_2">
				   <div class="text_1"><div class="content1_img"><a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, 'action=checkout', 'SSL'); ?>" class="ff_a"><img src="image/ok.gif" border="0" alt="" /></a></div><div class="font_4"><a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING, 'action=checkout', 'SSL'); ?>" class="ff_a"><?php echo HEADER_TITLE_CHECKOUT; ?></a></div></div>
				   <div class="text_1"><div class="content1_img"><a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" class="ff_a"><img src="image/basket.gif" border="0" alt="" /></a></div><div class="font_4"><a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" class="ff_a"><?php echo HEADER_TITLE_CART_CONTENTS; ?></a></div></div>
				  </div>			  
		      </div>
			  <div id="logo"><a  href="<?=tep_href_link(FILENAME_DEFAULT);?>"><img border="0" src="image/logo.gif" alt="" /></a></div>
		 </div>
		 <div id="daohang">
		 <div id="daohang1">	
							
							 <table style="height:22px;" border="0" align="center" cellpadding="0" cellspacing="0" id="nav">
							  <tr>
								<td width="54" class="unsel"  id="dh1"><div  align="center" ><a  href="<?=tep_href_link(FILENAME_DEFAULT);?>" ><?php echo TEXT_MENU_HOME_LINK;?></a></div></td>
								<td width="90" class="unsel"  id="dh2"><div class="chromestyle" id="topmenu1" align="center" ><a rel="westcoast" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=24');?>" ><?php echo TEXT_MENU_WEST_COAST_TOURS;?></a></div></td>
								<td width="90" class="unsel" id="dh3"><div class="chromestyle" id="topmenu2" align="center" ><a rel="eastcoast" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=25');?>" ><?php echo TEXT_MENU_EAST_COAST_TOURS;?></a></div></td>
								<td width="90" class="unsel" id="dh4"><div class="chromestyle" id="topmenu3" align="center" ><a rel="Hawaii" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=33');?>" ><?php echo TEXT_MENU_HAWAII_TOURS;?></a></div></td>
								<td width="95" class="unsel"  id="dh5"><div class="chromestyle" id="topmenu4" align="center" ><a rel="Florida" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=104');?>" ><?php echo TEXT_MENU_FLORIDA_TOURS;?></a></div></td>
								<td width="85" nowrap="nowrap" class="unsel"  id="dh6"><div class="chromestyle" id="topmenu6" align="center" ><a rel="TourPackages" href="javascript:void(0);"  ><?php echo TEXT_MENU_VACATION_PACKAGES;?></a></div></td>
							  	<td class="unsel" id="dh7"><div class="chromestyle" id="topmenu5" align="center" ><a rel="ByCity" href="<?=tep_href_link(FILENAME_TOUR_DEPARTURE_CITIES);?>" ><?php echo TEXT_MENU_BY_DEPARTURE_CITY;?></a>
							  	</div></td>
		                        
								<td width="200" align="right" nowrap="nowrap">
								<?php echo LANGUAGE_BUTTON ?>								</td></tr>
		   </table>
								  <div id="westcoast" class="dropmenudiv" style="width:215px;"> 
									<?php 									$westcoast_query = tep_db_query("SELECT c . categories_id , c.categories_urlname, cd.categories_id, cd.categories_name FROM categories c, categories_description cd WHERE c.`parent_id` = 24 AND c.`categories_id` = cd.`categories_id` AND c.categories_id IN(29, 30, 31,32,35, 37, 38,39,40, 41,44, 45,46,47,48,49,107,108,111,112,119,120,132,133) AND c.categories_status='1' ");
									$westcoast_rows = tep_db_num_rows($westcoast_query);
									if($westcoast_rows>0){?>
										<?php 									while($westcoast = tep_db_fetch_array($westcoast_query)){?>
									<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=' . $westcoast['categories_id']);?>"><?=db_to_html($westcoast['categories_name']);?></a> 
									<?php }?>
									<a   href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=24');?>"><?php echo MENU_MORE_WEST_COAST_TOURS;?></a> 
									<?php }?>
		   </div>
																		  
								  <div id="eastcoast" class="dropmenudiv" style="width:190px;" > 
									<?php 									$eastcoast_query = tep_db_query("SELECT c . categories_id , c.categories_urlname, cd.categories_id, cd.categories_name FROM categories c, categories_description cd WHERE c.`parent_id` = 25 AND c.`categories_id` = cd.`categories_id` AND c.categories_id IN(52, 54, 55, 56, 57, 58, 59,60, 61,62,63) AND c.categories_status='1' ");
									$eastcoast_rows = tep_db_num_rows($eastcoast_query);
									if($eastcoast_rows>0){?>
										<?php 									while($eastcoast = tep_db_fetch_array($eastcoast_query)){?>
									<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=' . $eastcoast['categories_id']);?>"><?=db_to_html($eastcoast['categories_name']);?></a> 
									<?php }?>
									<a  href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=25');?>"><?php echo MENU_MORE_EAST_COAST;?></a> 
									<?php }?>
		   </div>
	  
								  <div id="Hawaii" class="dropmenudiv" style="width:190px;"> 
									<?php 									$Hawaii_query = tep_db_query("SELECT c . categories_id , c.categories_urlname, cd.categories_id, cd.categories_name FROM categories c, categories_description cd WHERE c.`parent_id` = 33 AND c.`categories_id` = cd.`categories_id` AND c.categories_status='1' limit 12");
									$Hawaii_rows = tep_db_num_rows($Hawaii_query);
									if($Hawaii_rows>0){?>
										<?php 									while($Hawaii = tep_db_fetch_array($Hawaii_query)){?>
									<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=' . $Hawaii['categories_id']);?>"><?=db_to_html($Hawaii['categories_name']);?></a> 
									<?php }?>
									<a href="<?=tep_href_link(FILENAME_DEFAULT,	'cPath=33');?>"><?=db_to_html($westcoast['categories_name']);?><b><?php echo MENU_MORE_HAWAII_TOURS;?></b></a> 

									<?php }?>
		   </div>
							
								  <div id="Florida" class="dropmenudiv" style="width:190px;"> 
											<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=104');?>"><?php echo MENU_FLORIDA_TOURS_PACKAGES;?></a>
		   </div>
								   
							   <div id="ByCity" class="dropmenudiv" style="width:190px;"> 								
							   			   <?php 
											
												$city_class_array = array(array('id' => '', 'text' => TEXT_NONE));
											$city_class_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co   where c.state_id = s.zone_id and c.departure_city_status = '1' and s.zone_country_id = co.countries_id and c.city !='' AND c.city_id !='8'
											 AND c.city_id !='53'
											  AND c.city_id !='68'
											   AND c.city_id !='93'
											    AND c.city_id !='129'
												 AND c.city_id !='136'
												  AND c.city_id !='138'
												   AND c.city_id !='203'
											 order by c.city");
											while ($city_class = tep_db_fetch_array($city_class_query))
											{
												/*
											  	$city_class_array[] = array('id' => $city_class['city_id'],
																		 'text' => $city_class['city']);
												*/
												echo '<a href="'.tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'departure_city_id='.$city_class['city_id'].'&show_dropdown=true').'">'.db_to_html($city_class['city']).', '.$city_class['zone_code'].', '.$city_class['countries_iso_code_3'].'</a> ';
											}
											echo '<a href="'.tep_href_link(FILENAME_TOUR_DEPARTURE_CITIES).'">'.TEXT_HEADING_MORE_DEPARTURE_CITIES.'</a> ';
						 
											
											/*			
										<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=105');?>"><?php echo MENU_HONOLUU_TOURS;?></a> 
										<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=100');?>"><?php echo MENU_LAS_VEGAS_TOURS;?></a> 
										<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=97');?>"><?php echo MENU_LOSANGELES_TOURS;?></a> 
										<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=70');?>"><?php echo MENU_NEW_YORK_TOURS;?></a> 
										<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=126');?>"><?php echo MENU_ORLANDO_TOURS;?></a>										
										<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=128');?>"><?php echo MENU_PHILADEPHIA_TOURS;?></a> 										
										<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=98');?>"><?php echo MENU_SALT_LAKE_CITY_TOURS;?></a> 
										<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=125');?>"><?php echo MENU_SAN_FRANCISCO_TOURS;?></a> 								
										*/
										?>
		   </div> 	
							 <div id="TourPackages" class="dropmenudiv" style="width:190px;">
										<?php 										$westcoast_query = tep_db_query("SELECT c . categories_id , c.categories_urlname, cd.categories_id, cd.categories_name FROM categories c, categories_description cd WHERE c.parent_id = 0 and c.categories_urlname like '%packages%' AND c.categories_id = cd.categories_id");
										$westcoast_rows = tep_db_num_rows($westcoast_query);
										while($westcoast = tep_db_fetch_array($westcoast_query)){?>
										<a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=' . 	$westcoast['categories_id']);?>"><?=db_to_html($westcoast['categories_name']);?></a> 
										<?php }?>
		   </div>
								
							<script type="text/javascript">							
							cssdropdown.startchrome("topmenu1");
							cssdropdown.startchrome("topmenu2");
							cssdropdown.startchrome("topmenu3");
							cssdropdown.startchrome("topmenu4");
							cssdropdown.startchrome("topmenu5");								
							cssdropdown.startchrome("topmenu6");			
							cssdropdown.startchrome("topmenufirstsentence");						
							</script>
					<?php
					//if(isset($current_category_id) && $current_category_id != '' ) {
					
					$tempexlodeArray = @explode('_',$cPath);
					$showdh = 1;
					if($current_category_id == '24' || $tempexlodeArray[1] == '24'){
					$showdh = 2;
					}else if($current_category_id == '25' || $tempexlodeArray[1] == '25'){
					$showdh = 3;
					}else if($current_category_id == '33' || $tempexlodeArray[1] == '33'){
					$showdh = 4;
					}else if($current_category_id == '104' || $tempexlodeArray[1] == '104'){
					$showdh = 5;
					}else if($current_category_id == '51' || $tempexlodeArray[1] == '71' || $current_category_id == '77' || $current_category_id == '104'){
					$showdh = 6;
					}else if($current_category_id == '96' || $tempexlodeArray[1] == '96'){
					$showdh = 7;
					}
					?>
					<script type="text/javascript">
						showdh(<?php echo $showdh; ?>);
					</script>
					<?php
					//}					
					
					?>
					

		   </div>	 
		 
		 </div>
		
	  </div>
	  <div id="spiffycalendar"  style="z-index:2;margin-left:21px;"></div>
	 <?php 
														if($cat_mnu_sel == 'maps'){
														 ?>
	   													<div id="bubble_tooltip" style="z-index:50;">
															<div class="bubble_top"><span></span></div>
															<div class="bubble_middle"><span id="bubble_tooltip_content"></span></div>
															<div class="bubble_bottom"></div>
														</div>
														<?php } ?>
														
	  <div id="middle">
