<?php echo tep_get_design_body_header(HEADING_TITLE); ?>
					 <!-- content main body start -->
					 		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
							  <tr>
								<td height="15"></td>
							  </tr>
							  
							  
							 
							  <tr>
								<td class="main">
								
								  
								    <?php 
											
											$city_class_array = array(array('id' => '', 'text' => TEXT_NONE));
											//$city_class_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co   where c.state_id = s.zone_id and c.departure_city_status = '1' and s.zone_country_id = co.countries_id and c.city !='' order by c.city");
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
												echo '&#8226; <a href="'.tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'departure_city_id='.$city_class['city_id'].'&show_dropdown=true').'">'.db_to_html($city_class['city']).', '.$city_class['zone_code'].', '.$city_class['countries_iso_code_3'].'</a><br>';							 
											}		

									  ?>


								
								</td>
							  </tr>
							   <tr>
								<td height="15"></td>
							  </tr>
							  
							  
							
							
							</table><!-- content main body end -->
<?php echo tep_get_design_body_footer();?>
