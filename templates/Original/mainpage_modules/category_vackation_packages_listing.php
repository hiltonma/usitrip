<?php
require_once('includes/application_top.php');
//套餐的条件
//$pack_tj = ' products_vacation_package ="1" ';
$pack_tj = " products_durations >= ".TOURS_PACKAGE_MIN_DAY_NUM." and products_durations_type < 1 ";
if(!tep_not_null($cat_and_subcate_ids)){
	$cat_and_subcate_ids = tep_get_category_subcategories_ids($current_category_id);
}

?>
	  <div id="cl_table_sticky" class="cl_table" >
		 <?php
		 echo tep_draw_form('sort_order_vacktion_package', '' ,"",'id=sort_order_vacktion_package');
		 ?>		 
		<table width="640" cellspacing="0" class="clearit">
		<tr>
		<td width="5"></td>
		<?php /* ?>
		<td  nowrap><b><?php echo TEXT_OPTION_FILTER_BY;?></b></td>
		<?php */ ?>
		<td style="width:112px;">
			<?php
			
			$departure_city_class_array1 = array(array('id' => '', 'text' => TEXT_DEPARTURE_OPTION_CITY));
			$drop_dwn_vackation_package_depature_city_ids = '454545,';
			if($category_depth == 'nested'){
			/*	//$departure_city_vackation_package_class_query = tep_db_query("select  p.departure_city_id as city_id , ct.city from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, ".TABLE_TOUR_CITY." ct  where  p.departure_city_id = ct.city_id  and p.products_status = '1' and p.products_id = p2c.products_id  and p2c.categories_id in (" . $cat_and_subcate_ids . ")  group by ct.city order by  ct.city");
				$get_va_pa_depature_city_ids_sql = "select  p.departure_city_id  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_status = '1' and ".$pack_tj." and p.products_id = p2c.products_id  and p2c.categories_id in (" . $cat_and_subcate_ids . ")  group by p.departure_city_id";
				$get_va_pa_depature_city_ids_sql = tep_db_query($get_va_pa_depature_city_ids_sql);
				while($get_va_pa_depature_city_ids_row = tep_db_fetch_array($get_va_pa_depature_city_ids_sql)){		 
				$drop_dwn_vackation_package_depature_city_ids .= "".$get_va_pa_depature_city_ids_row['departure_city_id'].",";
				}
			*/
			}else{
				//$departure_city_vackation_package_class_query = tep_db_query("select  p.departure_city_id as city_id , ct.city from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, ".TABLE_TOUR_CITY." ct  where  p.departure_city_id = ct.city_id  and p.products_status = '1' and p.products_id = p2c.products_id  and p2c.categories_id = '" . (int)$current_category_id . "' group by ct.city order by  ct.city");
				//echo $departure_city_class_sql_pr;
			}
			
			$get_va_pa_depature_city_ids_sql = "select  p.departure_city_id  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c  where p.products_status = '1' and ".$pack_tj." and p.products_id = p2c.products_id  and p2c.categories_id in (" . $cat_and_subcate_ids . ")  group by p.departure_city_id";
			$get_va_pa_depature_city_ids_sql = tep_db_query($get_va_pa_depature_city_ids_sql);
			while($get_va_pa_depature_city_ids_row = tep_db_fetch_array($get_va_pa_depature_city_ids_sql)){		 
				$drop_dwn_vackation_package_depature_city_ids .= "".$get_va_pa_depature_city_ids_row['departure_city_id'].",";
			}			
			 $drop_dwn_vackation_package_depature_city_ids = substr($drop_dwn_vackation_package_depature_city_ids, 0, -1);
			 $departure_city_vackation_package_sql_pr = "select  ct.city_id as city_id , ct.city from ".TABLE_TOUR_CITY." ct  where  ct.city_id in (" . $drop_dwn_vackation_package_depature_city_ids . ") AND `is_attractions` !='1' group by  ct.city_id order by  ct.city";
			 $departure_city_vackation_package_class_query = tep_db_query($departure_city_vackation_package_sql_pr);
			
			while($departure_city_class1 = tep_db_fetch_array($departure_city_vackation_package_class_query)) 
			{
			  $departure_city_class_array1[] = array('id' => $departure_city_class1['city_id'],
										 'text' => db_to_html($departure_city_class1['city']));
			}		
			$departure_city_class_array1[] = array('id' => ' ', 'text' => TEXT_DEPARTURE_OPTION_ALL_DEPARTURE_CITY);  
			
			echo tep_draw_pull_down_menu('departure_city_id1',$departure_city_class_array1,'', ' class="sel_sort" onChange="sendFormData(\'sort_order_vacktion_package\',\'product_listing_index_vackation_packages.php?&cPath='.$cPath.'&addhash=true\',\'div_product_vackation_packages\',\'true\');"');
			?>
			
		</td>
<?php
$onne_or_show = 'none';
$where_products_type ='';
if($current_category_id=='142'){
	$onne_or_show = 'none';
	$where_products_type = ' WHERE products_type_id in(1,2,3) ';
}
?>
<td style="display:<?=$onne_or_show?>">
<?php
		$products_type_arrays = array(array('id' => '', 'text' => TEXT_OPTION_TOUR_TYPE));
		
		$products_type_array = tep_db_query("select products_type_id, products_type_name from " . TABLE_PRODUCTS_TYPES . $where_products_type." order by products_type_id");
		
		while ($products_type_info = tep_db_fetch_array($products_type_array)) {
			  $products_type_arrays[] = array('id' => $products_type_info['products_type_id'],
											  'text' => str_replace('Regular Tour',db_to_html('定期游'),db_to_html($products_type_info['products_type_name']))
											 );
		}	
		$products_type_arrays[] = array('id' => ' ', 'text' => TEXT_OPTION_ALL_TOUR_TYPES);   		   
		
		echo tep_draw_pull_down_menu('tours_type1', $products_type_arrays,'', ' class="sel_sort" onChange="sendFormData(\'sort_order_vacktion_package\',\'product_listing_index_vackation_packages.php?&cPath='.$cPath.'&addhash=true\',\'div_product_vackation_packages\',\'true\');"');?>
		
		</td>
		
		
		
		<td>	
			<?php 	
			$sortby = $HTTP_GET_VARS['sort1'];
			$colnum = 1;
			$colnum1 = 3;
			$colnum2 = 6;
			$quertstring1 = $colnum . 'a';
			$quertstring2 = $colnum . 'd';
			$quertstring3 = $colnum1 . 'a';
			$quertstring4 = $colnum1 . 'd';
			$quertstring5 = $colnum2 . 'a';
			$quertstring6 = $colnum2 . 'd';
			$quertstring7 = '7d';
			
			$quertstring = array(array('id' => '0', 'text' => TEXT_SORT_OPTION_1));
			$quertstring[] = array('id' => $quertstring3, 'text' => TEXT_SORT_OPTION_2.' (+)');
			$quertstring[] = array('id' => $quertstring4, 'text' => TEXT_SORT_OPTION_2.' (-)');
			$quertstring[] = array('id' => $quertstring5, 'text' => TEXT_SORT_OPTION_3.' (+)');
			$quertstring[] = array('id' => $quertstring6, 'text' => TEXT_SORT_OPTION_3.' (-)');
			$quertstring[] = array('id' => $quertstring1, 'text' => TEXT_SORT_OPTION_4.' (a-z)');
			$quertstring[] = array('id' => $quertstring2, 'text' => TEXT_SORT_OPTION_4.' (z-a)');
			$quertstring[] = array('id' => $quertstring7, 'text' => TEXT_DROPDOWN_POULARITY);
			
			//echo '<b>'.TEXT_OPTION_SORT_BY.' </b>'.tep_draw_pull_down_menu('sort1', $quertstring, '', 'id=sort class="sel_sort" onChange="sendFormData(\'sort_order_vacktion_package\',\'product_listing_index_vackation_packages.php?&cPath='.$cPath.'&addhash=true\',\'div_product_vackation_packages\',\'true\');"');
			//从这里去..的菜单 start
			if( (!isset($_GET['cat_mnu_sel'])) || (isset($_GET['cat_mnu_sel']) && ($_GET['cat_mnu_sel']=='tours' || $_GET['cat_mnu_sel']=='')) ){
				//$sql_str = "select categories_top_attractions_tourtab as categories_top_attractions from ".TABLE_CATEGORIES." where categories_id = '".$current_category_id."'";
				$top_10_attractions_query = MCache::fetch_categories($current_category_id,'categories_top_attractions_tourtab');
				$top_10_attractions_query['categories_top_attractions'] = $top_10_attractions_query['categories_top_attractions_tourtab'];
			}else{				
				//$sql_str = "select categories_top_attractions as categories_top_attractions from ".TABLE_CATEGORIES." where categories_id = '".$current_category_id."'";
				$top_10_attractions_query = MCache::fetch_categories($current_category_id,'categories_top_attractions');
				$top_10_attractions_query['categories_top_attractions'] = $top_10_attractions_query['categories_top_attractions'];
			}
			
			//$top_10_attractions_query = tep_db_fetch_array(tep_db_query($sql_str));
			if($top_10_attractions_query['categories_top_attractions'] != ''){
				$top_10_attractions_array = explode(',',$top_10_attractions_query['categories_top_attractions']);
				$top_10_attractions_array_count = count($top_10_attractions_array);
				$row_attractions_array = array();
				$options = array();
				$options[] = array('id' => '', 'text' => TEXT_OPTION_FROM_TO);
				for($i=0; $i<min($top_10_attractions_array_count,30); $i++){
					$attractions_query = tep_db_query("select distinct(c.city_id), c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co  where c.city_id ='".$top_10_attractions_array[$i]."' and s.zone_country_id = co.countries_id and c.state_id = s.zone_id and c.city !='' and (c.is_attractions='1' or c.is_attractions='2') order by c.city");
					$row_attractions = tep_db_fetch_array($attractions_query);
					if((int)$row_attractions['city_id']){
						$options[] = array('id'=>$row_attractions['city_id'], 'text'=>db_to_html($row_attractions['city']));
					}
				}
				echo tep_draw_pull_down_menu('top_attractions1',$options, '', ' class="sel_sort sel_sort22" onChange="sendFormData(\'sort_order_vacktion_package\',\'product_listing_index_vackation_packages.php?&cPath='.$cPath.'&addhash=true\',\'div_product_vackation_packages\',\'true\');"');
			}else{
				echo '&nbsp;';
			}
			
			//从这里去..的菜单 end
			//echo tep_draw_hidden_field('top_attractions1');
			?>
			</td>
			<td nowrap align="center" width="100">
			<?php  	
			echo tep_draw_hidden_field('sort1', '', 'id="sort" class="sel_sort" onchange="sendFormData(\'sort_order_vacktion_package\',\'product_listing_index_vackation_packages.php?cPath='.$cPath.'&addhash=true\',\'div_product_vackation_packages\',\'true\');"');
			echo '<div class="sort_label_asc" id="sort_label_asc" onClick="javascript: set_value_to_hidden_var(\'sort\', \''.$quertstring3.'\', \''.$cPath.'\',\'vp\');"></div>
				  <div class="sort_label_desc" id="sort_label_desc" onClick="javascript: set_value_to_hidden_var(\'sort\', \''.$quertstring4.'\', \''.$cPath.'\',\'vp\');"></div>';		
			
			
			?>
		  
		  </td>
		  <td align="right">
		  <?php
		  $tours_or_vp = '套餐';
		  echo TEXT_TRAVEL_OPTIONS.'&nbsp;&nbsp;<label id="pdall" class="duration_label_sel" onClick="javascript: set_value_to_hidden_var(\'products_durations\', \' \', \''.$cPath.'\', \'vp\');">'.TEXT_DURATION_LINK_ALL.db_to_html($tours_or_vp).'</label> | <label id="pd4" class="duration_label" onClick="javascript: set_value_to_hidden_var(\'products_durations\', \'4-4\', \''.$cPath.'\', \'vp\');">' . TEXT_DURATION_LINK_3 . '</label>'. ' | <label id="pd56" class="duration_label" onClick="set_value_to_hidden_var(\'products_durations\', \'5-6\', \''.$cPath.'\',\'vp\');">' . TEXT_DURATION_LINK_4 . '</label> | <label id="pd7" class="duration_label" onClick="set_value_to_hidden_var(\'products_durations\', \'7-\', \''.$cPath.'\', \'vp\');">' . TEXT_DURATION_LINK_5 . '</label>'; ?>
		<?php
		
		$products_durations_array = array(array('id' => '', 'text' => TEXT_DURATION_OPTION_DURATION));
		/*
		$products_durations_array[] = array('id' => '0-1', 'text' => TEXT_DURATION_OPTION_LESS_ONE);
		$products_durations_array[] = array('id' => '1-1', 'text' => TEXT_DURATION_OPTION_2);
		$products_durations_array[] = array('id' => '2-3', 'text' => TEXT_DURATION_OPTION_3);
		*/
		$products_durations_array[] = array('id' => '4-5', 'text' => TEXT_DURATION_OPTION_4);
		$products_durations_array[] = array('id' => '6-', 'text' => TEXT_DURATION_OPTION_5);
		/*$products_durations_array[] = array('id' => '3-4', 'text' => TEXT_DURATION_OPTION_6);
		$products_durations_array[] = array('id' => '4-4', 'text' => TEXT_DURATION_OPTION_7);
		$products_durations_array[] = array('id' => '4-', 'text' => TEXT_DURATION_OPTION_8);
		$products_durations_array[] = array('id' => '5-', 'text' => TEXT_DURATION_OPTION_9);
		//$products_durations_array[] = array('id' => 'vpackage', 'text' => 'Vacation Package');*/
		$products_durations_array[] = array('id' => ' ', 'text' => TEXT_DURATION_OPTION_ALL_DURATIONS);
		//echo tep_draw_pull_down_menu('products_durations1', $products_durations_array,'', ' class="sel_sort" onChange="sendFormData(\'sort_order_vacktion_package\',\'product_listing_index_vackation_packages.php?&cPath='.$cPath.'&addhash=true\',\'div_product_vackation_packages\',\'true\');"');
		echo tep_draw_hidden_field('products_durations1', '', ' class="sel_sort" onchange="sendFormData(\'sort_order_vacktion_package\',\'product_listing_index_vackation_packages.php?cPath='.$cPath.'&addhash=true\',\'div_product_vackation_packages\',\'true\');"');
		echo '<input type="hidden" name="selfpagename" value="index_vackation_packages">';
		echo '<input type="hidden" name="ajxsub_send_vackation_packages" value="true">';
		?>
		
		</td>
		</tr>
		</table>
		</form>
   		</div>
   
		<div style="background:#EBF8FB; height:20px;  min-height:20px; padding-bottom:5px; display:none;" >
         <div class="tab1_1" ><?php echo TEXT_NOTES_CLICK_VIDEO;?>
		 </div>
				</div>
				 
				 <div id="div_product_vackation_packages" class="main" style="width:99%;">
				   <?php 								
				$where_str_filter_vp = '';
				//commmon code start	
						if(isset($HTTP_POST_VARS['sort1']) && $HTTP_POST_VARS['sort1'] != ''){
						$sort =  $HTTP_GET_VARS['sort1'] = $HTTP_POST_VARS['sort1'];
						}else{
						$sort  = $HTTP_POST_VARS['sort1'] =  $HTTP_GET_VARS['sort1'];
						}
						
						if(isset($HTTP_POST_VARS['tours_type1']) && $HTTP_POST_VARS['tours_type1'] != ''){
						$tours_type = $HTTP_GET_VARS['tours_type1'] = $HTTP_POST_VARS['tours_type1'];
						}else{
						$tours_type = $HTTP_POST_VARS['tours_type1'] = $HTTP_GET_VARS['tours_type1'];
						}
						
						if(isset($HTTP_POST_VARS['products_durations1']) && $HTTP_POST_VARS['products_durations1'] != ''){
						$products_durations = $HTTP_GET_VARS['products_durations1'] = $HTTP_POST_VARS['products_durations1'];
						}else{
						$products_durations  = $HTTP_POST_VARS['products_durations1'] = $HTTP_GET_VARS['products_durations1'];
						}
						
						if(isset($HTTP_POST_VARS['departure_city_id1']) && $HTTP_POST_VARS['departure_city_id1'] != ''){
						$departure_city_id = $HTTP_GET_VARS['departure_city_id1'] = $HTTP_POST_VARS['departure_city_id1'];
						}else{
						$departure_city_id = $HTTP_POST_VARS['departure_city_id1'] = $HTTP_GET_VARS['departure_city_id1'] ;
						}
						 define('PRODUCT_DESCRIPTION','3');
						$define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
										   'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
										   'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
										   'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
										   'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
										   'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
										   'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
										   'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW,
										   'PRODUCT_DESCRIPTION' => PRODUCT_DESCRIPTION);
					
					  asort($define_list);
					
					  $column_list = array();
					  reset($define_list);
					  while (list($key, $value) = each($define_list)) {
						if ($value > 0) $column_list[] = $key;
						//echo $value."==>".$key."<br>";
					  }
					
						$select_column_list = '';
					
						for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
						  switch ($column_list[$i]) {
							case 'PRODUCT_LIST_MODEL':
							  $select_column_list .= 'p.products_model, ';
							  break;
							case 'PRODUCT_LIST_NAME':
							  $select_column_list .= 'pd.products_name, ';
							  break;
							case 'PRODUCT_LIST_MANUFACTURER':
							  $select_column_list .= 'm.manufacturers_name, ';
							  break;
							case 'PRODUCT_LIST_QUANTITY':
							  $select_column_list .= 'p.products_quantity, ';
							  break;
							case 'PRODUCT_LIST_IMAGE':
							  $select_column_list .= 'p.products_image, ';
							  break;
							case 'PRODUCT_LIST_WEIGHT':
							  $select_column_list .= 'p.products_weight, ';
							  break;
						  }
						}
			//common code end
	
	//amit added for filter seach start
					$where_str_filter_vp = '';
					
					$where_str_filter_vp .= " and ".$pack_tj;
					
					 if (tep_not_null($tours_type)) {
						$where_str_filter_vp .= " and p.products_type = '" . $tours_type . "'";
					  }
				
					if (tep_not_null($products_durations)) 
					  {
					  
						if($products_durations == '0-1'){
								$where_str_filter_vp .= " and products_durations_type ='1' ";
						}else{
							
							$products_durations_ex = explode("-",$products_durations);
							$products_durations1 = $products_durations_ex[0];
							$products_durations2 = $products_durations_ex[1];
							
							if($products_durations2 != ""){
							$where_str_filter_vp .= " and (p.products_durations >= '" . $products_durations1 . "' and p.products_durations <= '" . $products_durations2 . "' and products_durations_type ='0')";
							}else{
							$where_str_filter_vp .= " and (p.products_durations >= '" . $products_durations1 . "' and products_durations_type ='0')";
							}
						}
						
					  }
					  
					   if (tep_not_null($departure_city_id)) {
							$where_str_filter_vp .= " and p.departure_city_id = '" . $departure_city_id . "'";
					  }	
					//amit added for filter search end
					$listing_sql='';
					
					if($category_depth == 'nested'){
							  $listing_sql = "select ta.operate_currency_code, p.products_image, pd.products_name, p.products_vacation_package, p.products_video, p.products_durations, p.products_durations_type, p.products_durations_description, p.departure_city_id, p.products_model, pd.products_small_description , p.products_is_regular_tour, p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, p.products_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p2c.products_id = s.products_id, " . TABLE_TRAVEL_AGENCY . " ta  where  p.agency_id = ta.agency_id and p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id in (" . $cat_and_subcate_ids . ") ".$where_str_filter_vp." group by p.products_id";
					}else{
							 $listing_sql = "select ta.operate_currency_code, p.products_image, pd.products_name, p.products_vacation_package, p.products_video, p.products_durations, p.products_durations_type, p.products_durations_description, p.departure_city_id, p.products_model, pd.products_small_description , p.products_is_regular_tour, p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, p.products_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p2c.products_id = s.products_id, " . TABLE_TRAVEL_AGENCY . " ta  where  p.agency_id = ta.agency_id and p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id in (" . $cat_and_subcate_ids . ") ".$where_str_filter_vp." group by p.products_id";
							 
					}
		
				//过滤产品
				if(!preg_match('/group by/i',$listing_sql )){
					$listing_sql .= ' Group By p.products_id ';
				}
			
				if ( (!isset($HTTP_GET_VARS['sort1'])) || (!ereg('[1-8][ad]', $HTTP_GET_VARS['sort1'])) || (substr($HTTP_GET_VARS['sort1'], 0, 1) > sizeof($column_list)) ) {
				  //amit commented $sort_column = CATEGORIES_SORT_ORDER;
				  //$sort_column = 'PRODUCT_LIST_WEIGHT';
				  //$sort_order = 'a';
				//以产品排序为最优先级
				  $sort_column = 'SORT_ORDER';
				  $sort_order = 'd';
				} else {
				   $sort_col = substr($HTTP_GET_VARS['sort1'], 0 , 1);
				   $sort_column = $column_list[$sort_col-1];
				   $sort_order = substr($HTTP_GET_VARS['sort1'], 1);
				}
				$listing_sql .= ' order by ';
				switch ($sort_column) {
				  case 'PRODUCT_LIST_MODEL':
					$listing_sql .= " p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
					break;
				  case 'PRODUCT_LIST_NAME':
					$listing_sql .= " pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
					break;
				  case 'PRODUCT_LIST_MANUFACTURER':
					$listing_sql .= " m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
					break;
				  case 'PRODUCT_LIST_QUANTITY':
					$listing_sql .= " p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
					break;
				  case 'PRODUCT_LIST_IMAGE':
					$listing_sql .= " pd.products_name";
					break;
				  case 'PRODUCT_LIST_WEIGHT':
					$listing_sql .= " p.products_durations_type " . ($sort_order == 'd' ? "" : "desc") . ", p.products_durations " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
					break;
				  case 'PRODUCT_LIST_PRICE':
					$listing_sql .= " final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
					break;
	  			  default:
					$listing_sql .= " p2c.products_sort_order " . ($sort_order == 'd' ? 'desc' : '') . ",  p.products_durations_type desc , p.products_durations asc ";
					break;
				  }
				  
		//generate query section end

						
				include(DIR_FS_MODULES . 'product_listing_products_vacation_products.php'); 						
			?>
	</div>	
