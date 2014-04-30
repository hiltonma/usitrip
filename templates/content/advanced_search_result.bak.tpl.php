<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td  colspan="2" nowrap class="pageHeading">
	<?php echo TEXT_SEARCH_RESULT_BOX_HEADING; ?>
	</td>	
  </tr>
    <tr>
    <td width="190" valign="top"><?php require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/column_left.php'); ?></td>
	<td width="100%" valign="top">
	 <div id="down">	 
	 <!-- search result start -->
	 <table border="0" style="border:5px solid #172848;" width="100%" align="center" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
     <tr>
	 <td> <div class="cl_table">
	 
	  <?php
		 echo tep_draw_form('sort_order', '' ,"",'id=sort_order');
		 ?>		 
		<table width="577" cellspacing="0" class="clearit">
		<tr><td width="76" height="3"></td>
		</tr>
		<tr>
		<td width="5"></td>
		<td  nowrap><b><?php echo TEXT_OPTION_FILTER_BY;?></b></td>
		<td><?php
		
		
					
		$products_type_arrays = array(array('id' => '', 'text' => TEXT_OPTION_TOUR_TYPE));
		$products_type_array = tep_db_query("select products_type_id, products_type_name from " . TABLE_PRODUCTS_TYPES . " order by products_type_id");
		while ($products_type_info = tep_db_fetch_array($products_type_array)) {
			  $products_type_arrays[] = array('id' => $products_type_info['products_type_id'],
											  'text' => str_replace('Regular Tour',db_to_html('j¡õC'),db_to_html($products_type_info['products_type_name']))
											 );
		}	
		$products_type_arrays[] = array('id' => ' ', 'text' => TEXT_OPTION_ALL_TOUR_TYPES);		   
		
		echo tep_draw_pull_down_menu('tours_type', $products_type_arrays,'', ' class="sel_sort" onChange="sendFormData(\'sort_order\',\'product_listing_index_products_ajax.php?&cPath='.$cPath.'&addhash=true\',\'div_product_listing\',\'true\');"');?>
		
		</td>
		
		<td>
		<?php
		
		$products_durations_array = array(array('id' => '', 'text' => TEXT_DURATION_OPTION_DURATION));
		$products_durations_array[] = array('id' => '0-1', 'text' => TEXT_DURATION_OPTION_LESS_ONE);
		$products_durations_array[] = array('id' => '1-1', 'text' => TEXT_DURATION_OPTION_2);
		$products_durations_array[] = array('id' => '2-2', 'text' => TEXT_DURATION_OPTION_3);
		$products_durations_array[] = array('id' => '2-3', 'text' => TEXT_DURATION_OPTION_4);
		$products_durations_array[] = array('id' => '3-3', 'text' => TEXT_DURATION_OPTION_5);
		$products_durations_array[] = array('id' => '3-4', 'text' => TEXT_DURATION_OPTION_6);
		$products_durations_array[] = array('id' => '4-4', 'text' => TEXT_DURATION_OPTION_7);
		$products_durations_array[] = array('id' => '4-', 'text' => TEXT_DURATION_OPTION_8);
		$products_durations_array[] = array('id' => '5-', 'text' => TEXT_DURATION_OPTION_9);
		$products_durations_array[] = array('id' => 'vpackage', 'text' => TEXT_TAB_VACATION_PACKAGES);
		$products_durations_array[] = array('id' => ' ', 'text' => TEXT_DURATION_OPTION_ALL_DURATIONS);
		echo tep_draw_pull_down_menu('products_durations', $products_durations_array,'', ' class="sel_sort" onChange="sendFormData(\'sort_order\',\'product_listing_index_products_ajax.php?&cPath='.$cPath.'&addhash=true\',\'div_product_listing\',\'true\');"');
		?>
		
		</td>
		<td>
			<?php
			
			$departure_city_class_array = array(array('id' => '', 'text' => TEXT_DEPARTURE_OPTION_CITY));

			if(isset($HTTP_GET_VARS['categories_id']) && $HTTP_GET_VARS['categories_id'] != ''){
			$departure_city_class_query = tep_db_query("select  p.departure_city_id as city_id , ct.city from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, ".TABLE_TOUR_CITY." ct  where  p.departure_city_id = ct.city_id  and p.products_status = '1' and p.products_id = p2c.products_id  and p2c.categories_id = '" . (int)$HTTP_GET_VARS['categories_id'] . "' group by ct.city order by  ct.city");
			}else{
			$departure_city_class_query = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where departure_city_status = '1' order by city");
			}
		

			while ($departure_city_class = tep_db_fetch_array($departure_city_class_query)) 
			{
			  $departure_city_class_array[] = array('id' => $departure_city_class['city_id'],
										 'text' => db_to_html($departure_city_class['city']));
			}		
			$departure_city_class_array[] = array('id' => ' ', 'text' => TEXT_DEPARTURE_OPTION_ALL_DEPARTURE_CITY);  
			
			echo tep_draw_pull_down_menu('departure_city_id',$departure_city_class_array,'', ' class="sel_sort" onChange="sendFormData(\'sort_order\',\'product_listing_index_products_ajax.php?&cPath='.$cPath.'&addhash=true\',\'div_product_listing\',\'true\');"');
			?>
			
		</td>
		<td nowrap>	
			<?php 	
			$sortby = $HTTP_GET_VARS['sort'];
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
			
			  foreach($_GET as $key=>$val)
				{
				 if($key != 'sort' && $key != 'page' && $key != 'submit' && $key != 'search' && $key != 'departure_city_id' && $key != 'products_durations' && $key != 'tours_type') 
				 echo tep_draw_hidden_field($key,$val);
				}
			
			echo '<b>'.TEXT_OPTION_SORT_BY.' </b>'.tep_draw_pull_down_menu('sort', $quertstring, '', 'id=sort class="sel_sort" onChange="sendFormData(\'sort_order\',\'product_listing_index_products_ajax.php?&cPath='.$cPath.'&addhash=true\',\'div_product_listing\',\'true\');"');
			echo '<input type="hidden" name="selfpagename" value="adv_search">';
			echo '<input type="hidden" name="ajxsub_send_sort" value="true">';
			
			?>
		  
		  </td>
		  <td width="5"></td>
		</tr>
		</table>
		</form>
				
			</div>
	 </td>
	 </tr>
	 <tr>
	 <td>
	  <div style="background:#EBF8FB; height:100%; min-height:20px; padding-bottom:5px;" >
         <div class="tab1_1" ><?php echo TEXT_NOTES_CLICK_VIDEO;?></div>
		 </div>
	 </td>
	 </tr>
	 <tr>
        <td>
<?php
// create column list 
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
	  case 'PRODUCT_DESCRIPTION':
        $select_column_list .= 'pd.products_description, ';
        break;
    }
  }
 
  $select_str = "select distinct " . $select_column_list . " p.products_durations, p.products_durations_type, p.products_model, p.products_durations_description, p.departure_city_id, pd.products_small_description ,p.products_is_regular_tour, m.manufacturers_id, p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price ";

  if ( (DISPLAY_PRICE_WITH_TAX == 'true') && (tep_not_null($pfrom) || tep_not_null($pto)) ) {
    $select_str .= ", SUM(tr.tax_rate) as tax_rate ";
  }

  $from_str = "from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m using(manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_SPECIALS . " s on pd.products_id = s.products_id, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c";

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
  
  $where_str = " where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id ";

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
          $where_str .= "(pd.products_name like '%" . tep_db_input($keyword) . "%' or p.products_model like '%" . tep_db_input($keyword) . "%' or p.provider_tour_code like '%" . tep_db_input($keyword) . "%'  or m.manufacturers_name like '%" . tep_db_input($keyword) . "%'";
          if (isset($HTTP_GET_VARS['search_in_description']) && ($HTTP_GET_VARS['search_in_description'] == '1')) $where_str .= " or pd.products_description like '%" . tep_db_input($keyword) . "%'";
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
				$where_str .= " and (p.products_durations = '" . $products_durations1 . "' or p.products_durations > '" . $products_durations1 . "' and products_durations_type ='0')";
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
    $where_str .= " and p.departure_city_id = '" . $departure_city_id . "'";
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
    }
  }

  $listing_sql = $select_str . $from_str . $where_str . $order_str;
 

// Search enhancement mod start
                $search_enhancements_keywords = $_GET['keywords'];
                $search_enhancements_keywords = strip_tags($search_enhancements_keywords);
                $search_enhancements_keywords = addslashes($search_enhancements_keywords);                
          
               	$stdate = date("Y/m/d/ H:i:s");
               if ($search_enhancements_keywords != $last_search_insert) {

                        tep_db_query("insert into search_queries (search_text, search_date) values ('" .  $search_enhancements_keywords . "','".$stdate."')");

                        tep_session_register('last_search_insert');

                        $last_search_insert = $search_enhancements_keywords;

                }
// Search enhancement mod end

  //require(DIR_FS_MODULES . FILENAME_PRODUCT_LISTING);
  echo ' <div id="div_product_listing" class="main" style="width:99%;">';
 		
		include(DIR_FS_MODULES . 'product_listing_index_products.php'); 
  echo '</div>';					

?>
        </td>
      </tr>    
    </table>
	 <!-- Search sesult end -->
	 
	 </div>
	</td>
  </tr>
</table>
