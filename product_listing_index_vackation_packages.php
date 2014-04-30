<?php
 require_once('includes/application_top.php');
//套餐的条件
//$pack_tj = ' products_vacation_package ="1" ';
$pack_tj = " products_durations >= ".TOURS_PACKAGE_MIN_DAY_NUM." and products_durations_type < 1 ";

if(!tep_not_null($cat_and_subcate_ids)){
	$cat_and_subcate_ids = tep_get_category_subcategories_ids($current_category_id);
}


?>
<?php
 if(isset($_POST['aryFormData']))
  {
    require_once("includes/application_top.php");
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
	if(isset($HTTP_POST_VARS['top_attractions1']) && $HTTP_POST_VARS['top_attractions1'] != ''){
	$top_attractions = $HTTP_GET_VARS['top_attractions1'] = $HTTP_POST_VARS['top_attractions1'];
	}else{
	$top_attractions = $HTTP_POST_VARS['top_attractions1'] = $HTTP_GET_VARS['top_attractions1'] ;
	}
	
	
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
	
	if($HTTP_POST_VARS['selfpagename'] == 'index_vackation_packages' ){
		$PHP_SELF = FILENAME_DEFAULT;
		//generate query section start
						//amit added for filter seach start
						$where_str_filter_vp ='';
					$where_str_filter_vp .= " and ".$pack_tj;
					$where_str_filter .= " and products_durations_type ='0' and p.products_durations > '3'";
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
							//$where_str_filter_vp .= " and p.departure_city_id = '" . $departure_city_id . "'";
							$where_str_filter_vp .= " and (p.departure_city_id REGEXP '[,]".$departure_city_id."' OR p.departure_city_id REGEXP '".$departure_city_id."[,]' OR p.departure_city_id ='".$departure_city_id."') ";
					  }	
					  if (tep_not_null($top_attractions)) {
							$where_str_filter_vp .= " and p.products_id in(select pd.products_id from ".TABLE_PRODUCTS_DESTINATION." pd where pd.city_id = '".$top_attractions."') ";
					  }	
					//amit added for filter search end

			if(strlen($cat_and_subcate_ids)<1){$cat_and_subcate_ids='0';}
			 $listing_sql = "select ta.operate_currency_code, p.products_image, pd.products_name, p.products_vacation_package, p.products_video, p.products_durations, p.products_durations_type, p.products_durations_description, p.departure_city_id, p.products_model, pd.products_small_description , p.products_is_regular_tour, p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, p.products_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c left join " . TABLE_SPECIALS . " s on p2c.products_id = s.products_id, " . TABLE_TRAVEL_AGENCY . " ta where  p.agency_id = ta.agency_id and p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id in (" . $cat_and_subcate_ids . ") ".$where_str_filter_vp." group by p.products_id";
			
			
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
					$listing_sql .= "p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
					break;
				  case 'PRODUCT_LIST_NAME':
					$listing_sql .= "pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
					break;
				  case 'PRODUCT_LIST_MANUFACTURER':
					$listing_sql .= "m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
					break;
				  case 'PRODUCT_LIST_QUANTITY':
					$listing_sql .= "p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
					break;
				  case 'PRODUCT_LIST_IMAGE':
					$listing_sql .= "pd.products_name";
					break;
				  case 'PRODUCT_LIST_WEIGHT':
					$listing_sql .= "p.products_durations_type " . ($sort_order == 'd' ? "" : "desc") . ", p.products_durations " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
					break;
				  	case 'PRODUCT_LIST_PRICE':
					$listing_sql .= "final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
					break;
				 	case 'PRODUCT_VIEWED':
					$listing_sql .= "pd.products_viewed " . ($sort_order == 'd' ? 'desc' : '');
					break;
	  			  	default:
					$listing_sql .= " p2c.products_sort_order " . ($sort_order == 'd' ? 'desc' : '') . ",  p.products_durations_type desc , p.products_durations asc ";
					break;
				  }
				  
		//generate query section end
	} 
	
	
	 
  
} //end of check if ajax post	

//echo $listing_sql;
include(DIR_FS_MODULES . 'product_listing_products_vacation_products.php'); 
?>

