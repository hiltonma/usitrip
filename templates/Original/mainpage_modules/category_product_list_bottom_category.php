					 <?php
					define(MAX_DISPLAY_CATEGORIES_PER_ROW_ROOT_LISTING2,3);
					/*
					if (isset($cPath) && strpos('_', $cPath)) {
					// check to see if there are deeper categories within the current category
					  $category_feature_links = array_reverse($cPath_array);
					  for($i=0, $n=sizeof($category_feature_links); $i<$n; $i++) {
						$categories_feature_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_feature_links[$i] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and c.categories_status='1' and c.categories_feature_status='1'  order by sort_order, cd.categories_name ");
						$categories = tep_db_fetch_array($categories_feature_query);
						if ($categories['total'] < 1) {
						  // do nothing, go through the loop
						} else {
						  $categories_feature_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$category_feature_links[$i] . "' and c.categories_id = cd.categories_id and cd.categories_name !='' and cd.language_id = '" . (int)$languages_id . "' and c.categories_status='1' and c.categories_feature_status='1'  order by sort_order, cd.categories_name ");
						  break; // we've found the deepest category the customer is in
						}
					  }
					} else {
					  $categories_feature_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.categories_name !='' and cd.language_id = '" . (int)$languages_id . "' and c.categories_status='1' and c.categories_feature_status='1'  order by sort_order, cd.categories_name");
					}
					*/
				
					if((int)$cPathOnly){
						$categories_feature_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$cPathOnly . "' and c.categories_id = cd.categories_id and cd.categories_name !='' and cd.language_id = '" . (int)$languages_id . "' and c.categories_status='1' and c.categories_feature_status='1'  order by sort_order, cd.categories_name");
						$number_of_categories = tep_db_num_rows($categories_feature_query);
					}
				
				   
				   if($number_of_categories > 0) {

					?>
					  <div class="tab_xunhuan">
					     <div class="m_tours">
						 
						 <h5><?php echo db_to_html('¸ü¶à '.tep_get_categories_name((int)$cPathOnly));?></h5><hr size="1" noshade="noshade" />
						 
						 </div>
						 <div class="m_tours1">
						     <table width="100%" border="0" cellpadding="0" cellspacing="0">
							 <tr>
							   <td colspan="4" height="10"></td>
							   </tr>							   
							   <tr>
							   <td width="3%" ></td>
							   <td width="94%">
							   <table width="100%" border="0"  cellpadding="0" cellspacing="0">
							   <tr>
							   <?php
							   
							    $rows = 0;
								
								while ($categories_feature_result = tep_db_fetch_array($categories_feature_query)) {
								 $cate_image_names_done_ids .= "'" . $categories_feature_result['categories_image'] . "', ";
								  $rows++;
								  
								 
								  $cPath_new = tep_get_path($categories_feature_result['categories_id']);
								
								  $categories_name_array = explode(' ',$categories_feature_result['categories_name']);
								  $china_name = $categories_name_array[0];
								  $en_name = '';
								  for($iu=1; $iu<count($categories_name_array); $iu++){
								  	$en_name .= $categories_name_array[$iu].' ';
								  }
								  
								  
								  echo ' <td height="18"  ><a  href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new) . '" >- ' . db_to_html($categories_name_array[0]) . '</a><br>&nbsp;&nbsp;<span class="huise">'.$en_name.'</span></td>' . "\n";
								  
								  
								  if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW_ROOT_LISTING2) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW_ROOT_LISTING2)) && ($rows != $number_of_categories)) {
									echo '              </tr>' . "\n";
									echo '              <tr>' . "\n";
								  }
								  
								  
								}
							   ?>
							   </tr>
							   </table>
							</td>
							 <td width="3%" ></td>
							</tr>							
							  <tr>
							   <td colspan="4" height="10"></td>
							   </tr>
						   </table>
						 </div>
					  </div>
					  <?php } ?>
