<?php
  header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
  header( "Cache-Control: no-cache, must-revalidate" );
  header( "Pragma: no-cache" );

  require_once("includes/application_top.php");
  if(!tep_not_null($cat_and_subcate_ids)){
	$cat_and_subcate_ids = tep_get_category_subcategories_ids($current_category_id);
}


 if(isset($_POST['aryFormData']))
  {
	require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
	
	require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);
  
  	 $aryFormData = $_POST['aryFormData'];
	
		foreach ($aryFormData as $key => $value )
		{
		  foreach ($value as $key2 => $value2 )
		  {	  
		  	
			$HTTP_POST_VARS[$key] = stripslashes(str_replace('@@amp;','&',$value2));    	   
		  }
		}
		
		
	//commmon code start	
	$sort = $HTTP_GET_VARS['sort'] = $HTTP_POST_VARS['sort'];
	$tours_type = $HTTP_GET_VARS['tours_type'] = $HTTP_POST_VARS['tours_type'];
	$products_durations = $HTTP_GET_VARS['products_durations'] = $HTTP_POST_VARS['products_durations'];
	$departure_city_id = $HTTP_GET_VARS['departure_city_id'] = $HTTP_POST_VARS['departure_city_id'];
	$top_attractions = $HTTP_GET_VARS['top_attractions'] = $HTTP_POST_VARS['top_attractions'];
	
	 define('PRODUCT_DESCRIPTION','3');
	  define('PRODUCT_VIEWED','7');	
   $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                       'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                       'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                       'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                       'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                       'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                       'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                       'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW,
					   'PRODUCT_DESCRIPTION' => PRODUCT_DESCRIPTION,
					   'PRODUCT_VIEWED' => PRODUCT_VIEWED
					   );

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
		  case 'PRODUCT_VIEWED':
          $select_column_list .= 'pd.products_viewed, ';
          break; 
      }
    }
	//common code end
	
	if($HTTP_POST_VARS['selfpagename'] == 'index_nested' ){
		$PHP_SELF = FILENAME_DEFAULT;
		//generate query section start
						//amit added for filter seach start
					$where_str_filter ='';
					$where_str_filter .= " and (products_durations_type in ('1','2') or (products_durations_type ='0' and p.products_durations >= '0' and p.products_durations <= '3'))";
					 if (tep_not_null($tours_type)) {
						$where_str_filter .= " and p.products_type = '" . $tours_type . "'";
					  }
				
					if (tep_not_null($products_durations)) 
					  {
					  
						if($products_durations == 'vpackage'){
							$where_str_filter .= " and p.products_vacation_package = '1' ";
						}else{
							if($products_durations == '0-1'){
								$where_str_filter .= " and products_durations_type ='1' ";
							}else{
								$products_durations_ex = explode("-",$products_durations);
								$products_durations1 = $products_durations_ex[0];
								$products_durations2 = $products_durations_ex[1];
								
								if($products_durations2 != ""){
								$where_str_filter .= " and (p.products_durations >= '" . $products_durations1 . "' and p.products_durations <= '" . $products_durations2 . "' and products_durations_type ='0')";
								}else{
								$where_str_filter .= " and (p.products_durations >= '" . $products_durations1 . "' and products_durations_type ='0')";
								}
						  }						
						
						}
						
					  }
					  
					   if (tep_not_null($departure_city_id)) {
							//$where_str_filter .= " and p.departure_city_id = '" . $departure_city_id . "'";
							//$where_str_filter .= " and (p.departure_city_id REGEXP '[,]".$departure_city_id."' OR p.departure_city_id REGEXP '".$departure_city_id."[,]' OR p.departure_city_id ='".$departure_city_id."') ";
							$where_str_filter.= " and FIND_IN_SET('".(int)$departure_city_id."',p.departure_city_id) ";
					  }	
					   if (tep_not_null($top_attractions)) {
							$where_str_filter .= " and p.products_id in(select pd.products_id from ".TABLE_PRODUCTS_DESTINATION." pd where pd.city_id = '".$top_attractions."') ";
					  }	
					//amit added for filter search end
			
				  //短期旅行的条件
				  $where_str_filter .= " and ( (p.products_durations < ".TOURS_PACKAGE_MIN_DAY_NUM." and p.products_durations_type < 1 ) || (p.products_durations_type >= 1 ) ) ";
				  
				  $listing_sql = "select " . $select_column_list . " ta.operate_currency_code, p.products_vacation_package, p.products_video, p.products_durations, p.products_durations_type, p.products_durations_description, p.departure_city_id, p.products_model,p.is_visa_passport, pd.products_small_description , p.products_is_regular_tour, p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id,  p.products_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p2c.products_id = s.products_id, " . TABLE_TRAVEL_AGENCY . " ta where p.agency_id = ta.agency_id and p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and ( p2c.categories_id in (".$cat_and_subcate_ids.") ) ".$where_str_filter." ";
			
			//过滤产品
			$listing_sql .= ' group by p.products_id ';
			
			 if ( (!isset($HTTP_GET_VARS['sort'])) || (!ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {
				  //amit commented $sort_column = CATEGORIES_SORT_ORDER;
				  //$sort_column = 'PRODUCT_LIST_WEIGHT';
				  //$sort_order = 'a';
				//以产品排序为最优先级
				  $sort_column = 'SORT_ORDER';
				  $sort_order = 'd';
				} else {
				   $sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
				   $sort_column = $column_list[$sort_col-1];
				   $sort_order = substr($HTTP_GET_VARS['sort'], 1);
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
					 case 'PRODUCT_VIEWED':
					$listing_sql .= " pd.products_viewed " . ($sort_order == 'd' ? 'desc' : '');
					break;
	  			  default:
					$listing_sql .= " p2c.products_sort_order " . ($sort_order == 'd' ? 'desc' : '') . ",  p.products_durations_type desc , p.products_durations asc ";
					break;
				  }
				  
		//generate query section end
	
	
	}if($HTTP_POST_VARS['selfpagename'] == 'adv_search' ){
	
		$PHP_SELF = FILENAME_ADVANCED_SEARCH_RESULT;
		
		//generate query section start
							if(isset($HTTP_POST_VARS['category']) && $HTTP_POST_VARS['category'] != ''){
									$HTTP_POST_VARS['categories_urlname'] = $HTTP_POST_VARS['category'];
									
									$selected_country_category_urlname = $HTTP_POST_VARS['country'];
									$selected_region_category_urlname = $HTTP_POST_VARS['region'];
									$selected_category_category_urlname = $HTTP_POST_VARS['category'];									
									$current_cat_id_array = tep_get_categori_id_from_url($HTTP_POST_VARS['region']);			
									
									$actual_current_cat_id_array = tep_get_categori_id_from_url($HTTP_POST_VARS['category']);			
									$HTTP_GET_VARS['categories_id'] = $actual_current_cat_id_array['categories_id'];	
									$HTTP_GET_VARS['inc_subcat'] = '1';
									
								}else if(isset($HTTP_POST_VARS['region']) && $HTTP_POST_VARS['region'] != ''){
									$HTTP_POST_VARS['categories_urlname'] = $HTTP_POST_VARS['region'];
									$selected_country_category_urlname = $HTTP_POST_VARS['country'];
									$selected_region_category_urlname = $HTTP_POST_VARS['region'];				
									$current_cat_id_array = tep_get_categori_id_from_url($HTTP_POST_VARS['region']);					
									$HTTP_GET_VARS['categories_id'] = $current_cat_id_array['categories_id'];
									$HTTP_GET_VARS['inc_subcat'] = '1';
									
								}else if(isset($HTTP_POST_VARS['country']) && $HTTP_POST_VARS['country'] != ''){
									$HTTP_POST_VARS['categories_urlname'] = $HTTP_POST_VARS['country'];
									$selected_country_category_urlname = $HTTP_POST_VARS['country'];
									
									$actual_current_cat_id_array = tep_get_categori_id_from_url($HTTP_POST_VARS['country']);			
									$HTTP_GET_VARS['categories_id'] = $actual_current_cat_id_array['categories_id'];
									$HTTP_GET_VARS['inc_subcat'] = '1';
								}
									
		
		
				if($HTTP_GET_VARS['regions_id'] != ''){
				$regions_id = $HTTP_POST_VARS['regions_id'] = $_GET['regions_id'] = $HTTP_GET_VARS['regions_id'] ;
				}else{
				$regions_id = $_GET['regions_id'] = $HTTP_GET_VARS['regions_id'] = $HTTP_POST_VARS['regions_id'];
				}
				if($HTTP_GET_VARS['attraction'] != ''){
				$attraction = $HTTP_POST_VARS['attraction'] = $_GET['attraction'] = $HTTP_GET_VARS['attraction'] ;
				}else{
				$attraction = $_GET['attraction'] = $HTTP_GET_VARS['attraction'] = $HTTP_POST_VARS['attraction'];
				}
				if($HTTP_GET_VARS['keywords'] != ''){
				$keywords = $HTTP_POST_VARS['keywords'] = $_GET['keywords'] = $HTTP_GET_VARS['keywords'] ;
				}else{
				$keywords = $_GET['keywords'] = $HTTP_GET_VARS['keywords'] = $HTTP_POST_VARS['keywords'];
				}
				//howard added 目的地
				if($HTTP_GET_VARS['destination'] !=''){
					$destination = $HTTP_POST_VARS['destination'] = $_GET['destination'] = $HTTP_GET_VARS['destination'] ;
				}else{
					$destination = $_GET['destination'] = $HTTP_GET_VARS['destination'] = $HTTP_POST_VARS['destination'] ;
				}
				//howard added 目的地 end
				if($HTTP_GET_VARS['start_date_ignore'] != ''){
				$start_date_ignore = $HTTP_POST_VARS['start_date_ignore'] = $_GET['start_date_ignore'] = $HTTP_GET_VARS['start_date_ignore'];				
				}else{
				$start_date_ignore = $_GET['start_date_ignore'] = $HTTP_GET_VARS['start_date_ignore'] = $HTTP_POST_VARS['start_date_ignore'];
				}
				
					$dfrom = '';
					$dto = '';
					$pfrom = '';
					$pto = '';
					$keywords = '';
					$destination = '';
					$products_durations = '';
					$departure_city_id = '';
					$products_date_available = '';
				
					if (isset($HTTP_GET_VARS['dfrom'])) {
					  $dfrom = (($HTTP_GET_VARS['dfrom'] == DOB_FORMAT_STRING) ? '' : $HTTP_GET_VARS['dfrom']);
					}
				
					if (isset($HTTP_GET_VARS['dto'])) {
					  $dto = (($HTTP_GET_VARS['dto'] == DOB_FORMAT_STRING) ? '' : $HTTP_GET_VARS['dto']);
					}
				
					if (isset($HTTP_GET_VARS['pfrom'])) {
					  $pfrom = $HTTP_GET_VARS['pfrom'];
					}
				
					if (isset($HTTP_GET_VARS['pto'])) {
					  $pto = $HTTP_GET_VARS['pto'];
					}
					
					$navigation_title = '';
				
					if (isset($HTTP_GET_VARS['departure_city_id'])) {
						  $departure_city_id = $HTTP_GET_VARS['departure_city_id'];
						  $departcity = 'select * from '.TABLE_TOUR_CITY.' where city_id ="'.$departure_city_id.'" ';	 
						  $departcityresult = tep_db_query($departcity);
						  $departcityrow = tep_db_fetch_array($departcityresult);	 
						  $navigation_title .= $departcityrow['city']." ";
						}
						
					if (isset($HTTP_GET_VARS['regions_id'])) {
						  $regions_id = $HTTP_GET_VARS['regions_id'];
						  $departcity = "select rd.regions_name, r.regions_id, r.regions_image from " . TABLE_REGIONS . " r, " . TABLE_REGIONS_DESCRIPTION . " rd where r.regions_id = rd.regions_id and r.regions_id =".$regions_id." ";	 
						  $departcityresult = tep_db_query($departcity);
						  $departcityrow = tep_db_fetch_array($departcityresult);	 
						  $navigation_title .= $departcityrow['regions_id']." ";
						}
					
					
					
					if (isset($HTTP_GET_VARS['attraction'])) {
					  $attraction = $HTTP_GET_VARS['attraction'];
					  $attraction = str_replace('+',' ',$attraction);
					  if($attraction != db_to_html("无指定景点"))
					  {
						  $navigation_title .= "To ".$attraction." ";
						  $subcity = 'select * from '.TABLE_TOUR_CITY.' where city ="'.tep_db_input(tep_db_prepare_input($attraction)).'" ';	 
						  $subcityresult = tep_db_query($subcity);
						  $subcityrow = tep_db_fetch_array($subcityresult);	 
						  $attraction = $subcityrow['city_id']; 
					  }
					  else
					  {
						$attraction = '';
					  }
					}
					
					
					if (isset($HTTP_GET_VARS['keywords'])) {
					  $keywords = $HTTP_GET_VARS['keywords'];
					  $navigation_title .= $keywords." ";
					}
					 
					//vlaidatition for the product duration departure city and starting date
					if (isset($HTTP_GET_VARS['products_durations']) && $HTTP_GET_VARS['products_durations']!='') {
					  $products_durations = $HTTP_GET_VARS['products_durations'];
					  
					  if($products_durations == "1-1")
					  $navigation_title .= TEXT_DURATION_OPTION_2;
					  elseif($products_durations == "2-2")
					  $navigation_title .= TEXT_DURATION_OPTION_3;
					  elseif($products_durations == "2-3")
					  $navigation_title .= TEXT_DURATION_OPTION_4;
					  elseif($products_durations == "3-3")
					  $navigation_title .= TEXT_DURATION_OPTION_5;
					  elseif($products_durations == "3-4")
					  $navigation_title .= '3 to 4 days';
					  elseif($products_durations == "4-4")
					  $navigation_title .= '4 day';
					  elseif($products_durations == "4-")
					  $navigation_title .= '4 days or more';
					  elseif($products_durations == "5-")
					  $navigation_title .= '5 days or more';
					
					}
					
					if(!isset($HTTP_GET_VARS['start_date_ignore']))
					{
						if (isset($HTTP_GET_VARS['products_date_available'])) 
						{
						  $products_date_available = $HTTP_GET_VARS['products_date_available'];
						}
					}
					
					
					$navigation_title .= " - Tours";
		 

			  if(tep_not_null($destination)){
			  	$keywords .= $destination;
				$destination =  iconv('utf-8',CHARSET.'//IGNORE',$destination);
			  }
			  
			if (tep_not_null($keywords)) {
				//howard added change code
				$keywords = iconv('utf-8',CHARSET.'//IGNORE',$keywords);
				$keywords = split('[[:space:]]+', $keywords);
    			$keywords = array_unique($keywords);
    			$keywords = implode(' ',$keywords);

				//echo $keywords;
				
				if (!tep_parse_search_string($keywords, $search_keywords)) {
					$error = true;
					
					$messageStack->add_session('search', ERROR_INVALID_KEYWORDS);
				}
			}
		 
		  
		 $select_str = "select distinct " . $select_column_list . " ta.operate_currency_code, p.products_durations, p.products_durations_type, p.products_model,p.is_visa_passport, p.products_durations_description, p.departure_city_id, pd.products_small_description ,p.products_is_regular_tour, m.manufacturers_id, p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price ";
		
		  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
			$select_str .= ", SUM(tr.tax_rate) as tax_rate ";
		  }
		
		  $from_str = "from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_SPECIALS . " s on pd.products_id = s.products_id, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_TRAVEL_AGENCY . " ta";
		
		  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
			if (!tep_session_is_registered('customer_country_id')) {
			  $customer_country_id = STORE_COUNTRY;
			  $customer_zone_id = STORE_ZONE;
			}
			$from_str .= " left join " . TABLE_TAX_RATES . " tr on p.products_tax_class_id = tr.tax_class_id left join " . TABLE_ZONES_TO_GEO_ZONES . " gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = '0' or gz.zone_country_id = '" . (int)$customer_country_id . "') and (gz.zone_id is null or gz.zone_id = '0' or gz.zone_id = '" . (int)$customer_zone_id . "')";
		  }
		  
		  //left join if product state date
		  if(tep_not_null($products_date_available))
		  {
			$from_str .= " left join " . TABLE_PRODUCTS_START_DATE . " psday on p.products_id = psday.products_id left join " . TABLE_PRODUCTS_AVAILABLE . " padate on p.products_id = padate.products_id";
		  }
		  if(tep_not_null($attraction))
		  {
			$from_str .= " left join " . TABLE_PRODUCTS_DESTINATION . " pdes on p.products_id = pdes.products_id";
		  }
		  
		  $where_str = " where p.agency_id = ta.agency_id and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id ";
		
		  if (isset($HTTP_GET_VARS['categories_id']) && tep_not_null($HTTP_GET_VARS['categories_id'])) {
			if (isset($HTTP_GET_VARS['inc_subcat']) && ($HTTP_GET_VARS['inc_subcat'] == '1')) {
			  $subcategories_array = array();
			  tep_get_subcategories($subcategories_array, $HTTP_GET_VARS['categories_id']);
		
			  $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and (p2c.categories_id = '" . (int)$HTTP_GET_VARS['categories_id'] . "'";
		
			  for ($i=0, $n=sizeof($subcategories_array); $i<$n; $i++ ) {
				$where_str .= " or p2c.categories_id = '" . (int)$subcategories_array[$i] . "'";
			  }
		
			  $where_str .= ")";
			} else {
			  $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$HTTP_GET_VARS['categories_id'] . "'";
			}
		  }
		
		  if (isset($HTTP_GET_VARS['manufacturers_id']) && tep_not_null($HTTP_GET_VARS['manufacturers_id'])) {
			$where_str .= " and m.manufacturers_id = '" . (int)$HTTP_GET_VARS['manufacturers_id'] . "'";
		  }
		
		  if (isset($search_keywords) && (sizeof($search_keywords) > 0)) {
			$where_str .= " and (";
			for ($i=0, $n=sizeof($search_keywords); $i<$n; $i++ ) {
			  switch ($search_keywords[$i]) {
				case '(':
				case ')':
				case 'and':
				case 'or':
				  $where_str .= " " . $search_keywords[$i] . " ";
				  break;
				default:
				  $keyword = tep_db_prepare_input($search_keywords[$i]);
				  $keyword_analysis = html_to_db($keyword);
				  //echo db_to_html($keyword_analysis);
				  if(strtolower(CHARSET)=='big5'){
					$keyword_analysis = preg_replace('/洛杉襁/','洛杉矶',$keyword_analysis);
					$keyword_analysis = preg_replace('/峡威夷/','夏威夷',$keyword_analysis);
		  			$keyword_analysis = preg_replace('/酣城/','费城',$keyword_analysis);
		  			$keyword_analysis = preg_replace('/赫氏朱古寸城/','赫氏朱古力城',$keyword_analysis);
					$keyword_analysis = preg_replace('/魁北吹/','魁北克',$keyword_analysis);
					$keyword_analysis = preg_replace('/布.{2}佩斯/','布达佩斯',$keyword_analysis);
					$keyword_analysis = preg_replace('/^.{2}多.{4}/','维多利亚',$keyword_analysis);
					
				  }

				  $where_str .= "(pd.products_name LIKE  '%" . tep_db_input($keyword_analysis) . "%' or p.products_model LIKE '%" . tep_db_input($keyword_analysis) . "%' or p.provider_tour_code LIKE '%" . tep_db_input($keyword_analysis) . "%' or m.manufacturers_name LIKE '%" . tep_db_input($keyword_analysis) . "%'";
				  if (isset($HTTP_GET_VARS['search_in_description']) && ($HTTP_GET_VARS['search_in_description'] == '1')) $where_str .= " or pd.products_description LIKE '%" . tep_db_input($keyword_analysis) . "%'";
				  $where_str .= ')';
				  break;
			  }
			}
			$where_str .= " )";
		  }
		
		 /*  if (tep_not_null($dfrom)) {
			$where_str .= " and p.products_date_added >= '" . tep_date_raw($dfrom) . "'";
		  }
		
		  if (tep_not_null($dto)) {
			$where_str .= " and p.products_date_added <= '" . tep_date_raw($dto) . "'";
		  } */
		
			//coding for products duration departure_city_id
			
		  //howard added price
		  if(isset($HTTP_GET_VARS['price_range']) && (int)$HTTP_GET_VARS['price_range'] ){
			$price_range = (int)$HTTP_GET_VARS['price_range'];
			switch($price_range){
				case '1': $where_str .= " and p.products_price <=100 "; break;
				case '2': $where_str .= " and p.products_price >100 and p.products_price <=300 "; break;
				case '3': $where_str .= " and p.products_price >300 and p.products_price <=500 "; break;
				case '4': $where_str .= " and p.products_price >500 and p.products_price <=700 "; break;
				case '5': $where_str .= " and p.products_price >700 "; break;
			}
		  }
			
		  if (tep_not_null($tours_type)) {
			$where_str .= " and p.products_type = '" . $tours_type . "'";
		  }
			
		  if (tep_not_null($products_durations)) 
		  {
		  
			if($products_durations == 'vpackage'){
				$where_str .= " and p.products_vacation_package = '1' ";
			}else{
			
				if($products_durations == '0-1'){
					$where_str .= " and products_durations_type ='1' ";
				}else{
					$products_durations_ex = explode("-",$products_durations);
					$products_durations1 = $products_durations_ex[0];
					$products_durations2 = $products_durations_ex[1];
					
					if($products_durations2 != "")
					$where_str .= " and (p.products_durations >= '" . $products_durations1 . "' and p.products_durations <= '" . $products_durations2 . "' and products_durations_type ='0')";
					else
					//$where_str .= " and (p.products_durations = '" . $products_durations1 . "' or p.products_durations > '" . $products_durations1 . "' and products_durations_type ='0')";
					$where_str .= " and (p.products_durations >= '" . $products_durations1 . "' and products_durations_type ='0')";
					}
				}
		  }
		
		  /**************code for comparing regions_id city_id,Attraction and available date*****************/
		  if (tep_not_null($regions_id)) 
		  {
			$where_str .= " and p.regions_id = '" . $regions_id . "'";
		  }
		  if (tep_not_null($departure_city_id)) 
		  {
			//$where_str .= " and p.departure_city_id = '" . $departure_city_id . "'";
			//$where_str .= "and ( p.departure_city_id REGEXP '[,]".$departure_city_id."' OR p.departure_city_id REGEXP '".$departure_city_id."[,]' OR p.departure_city_id ='".$departure_city_id."' ) ";
			$where_str.= " and FIND_IN_SET('".$departure_city_id."',p.departure_city_id) ";

			
		  }	
		   if (tep_not_null($attraction)) 
		  {
			$where_str .= " and pdes.city_id = '" . $attraction . "'";
		  }
		  if(tep_not_null($products_date_available))
		  {
			$weeknumber = "";
			$tempdate = $products_date_available;
				$m = substr($tempdate,5,2);
				$d = substr($tempdate,8,2);
				$y = substr($tempdate,0,4);
			
			$renewaldate  = mktime (date ("H"), date ("i"), date ("s"), date($m), date ($d), date($y));
			$renewaldate1 = date ("D", $renewaldate);
			
			if($renewaldate1 == 'Sun')
			$weeknumber = '1';
			elseif($renewaldate1 == 'Mon')
			$weeknumber = '2';
			elseif($renewaldate1 == 'Tue')
			$weeknumber = '3';
			elseif($renewaldate1 == 'Wed')
			$weeknumber = '4';
			elseif($renewaldate1 == 'Thu')
			$weeknumber = '5';
			elseif($renewaldate1 == 'Fri')
			$weeknumber = '6';
			elseif($renewaldate1 == 'Sat')
			$weeknumber = '7'; 
			
		   $where_str .= " and ( psday.products_start_day = ".$weeknumber." or padate.available_date = '".$products_date_available."' ) ";
		   
		
		  }
		  //vincent add filter hotel
		  if( $where_str!='') $where_str .= " AND ";
		   $where_str .=" p.is_hotel='0'";
		
			//coding for products duration departure_city_id
		
		  /* if (tep_not_null($pfrom)) {
			if ($currencies->is_set($currency)) {
			  $rate = $currencies->get_value($currency);
		
			  $pfrom = $pfrom / $rate;
			}
		  }
		
		  if (tep_not_null($pto)) {
			if (isset($rate)) {
			  $pto = $pto / $rate;
			}
		  } */
		
		  if (DISPLAY_PRICE_WITH_TAX == 'true') {
			if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= " . (double)$pfrom . ")";
			if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= " . (double)$pto . ")";
		  } else {
			if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) >= " . (double)$pfrom . ")";
			if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) <= " . (double)$pto . ")";
		  }
		
		  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
			$where_str .= " group by p.products_id, tr.tax_priority";
		  }
		
		  if ( (!isset($HTTP_GET_VARS['sort'])) || (!ereg('[1-8][ad]', $HTTP_GET_VARS['sort'])) || (substr($HTTP_GET_VARS['sort'], 0, 1) > sizeof($column_list)) ) {
			for ($i=0, $n=sizeof($column_list); $i<$n; $i++) {
			  if ($column_list[$i] == 'PRODUCT_LIST_NAME') {
				$HTTP_GET_VARS['sort'] = $i+1 . 'a';
				$order_str = ' order by p.products_durations,pd.products_name';
				break;
			  }
			}
		  } else {
			$sort_col = substr($HTTP_GET_VARS['sort'], 0 , 1);
			$sort_order = substr($HTTP_GET_VARS['sort'], 1);
			$order_str = ' order by ';
			switch ($column_list[$sort_col-1]) {
			  case 'PRODUCT_LIST_MODEL':
				$order_str .= "p.products_model " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
				break;
			  case 'PRODUCT_LIST_NAME':
				$order_str .= "pd.products_name " . ($sort_order == 'd' ? "desc" : "");
				break;
			  case 'PRODUCT_LIST_MANUFACTURER':
				$order_str .= "m.manufacturers_name " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
				break;
			  case 'PRODUCT_LIST_QUANTITY':
				$order_str .= "p.products_quantity " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
				break;
			  case 'PRODUCT_LIST_IMAGE':
				$order_str .= "p.products_durations,pd.products_name";
				break;
			  case 'PRODUCT_LIST_WEIGHT':
				$order_str .= "p.products_durations_type " . ($sort_order == 'd' ? "" : "desc") . ", p.products_durations " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
				break;
			  case 'PRODUCT_LIST_PRICE':
				$order_str .= "final_price " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
				break;
			 case 'PRODUCT_VIEWED':
				$order_str .= "pd.products_viewed " . ($sort_order == 'd' ? 'desc' : '');
				break;
			}
		  }
		
		 $listing_sql = $select_str . $from_str . $where_str . $order_str;

		
		
		//generate query section end
		
	}else{
	
	$PHP_SELF = FILENAME_DEFAULT;
	
	}
	
	//$HTTP_POST_VARS[''];
	 
  
}
 //end of check if ajax post
 $ajax = true;	
include(DIR_FS_MODULES . 'product_listing_index_products.php'); 
//echo '<br><br>'.$listing_sql;
?>

