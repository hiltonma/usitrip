    <table border="0" width="100%" align="center" cellspacing="0" cellpadding="0<?php //echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php //echo HEADING_TITLE_2; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_browse.gif', HEADING_TITLE_2, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
	  <!--<tr>
	  	<td class="pageHeading" valign="top"><?php //echo $navigation_title.'4fun'; ?></td>
	  </tr>-->
<?php
}else{
$header_text = HEADING_TITLE_2;
}
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
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
 
  $select_str = "select distinct " . $select_column_list . " p.products_durations, p.products_model, p.products_durations_description, p.departure_city_id, pd.products_small_description ,p.products_is_regular_tour, m.manufacturers_id, p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price ";

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
          $where_str .= "(pd.products_name like '%" . tep_db_input($keyword) . "%' or p.products_model like '%" . tep_db_input($keyword) . "%' or m.manufacturers_name like '%" . tep_db_input($keyword) . "%'";
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
  if (tep_not_null($products_durations)) 
  {
 	$products_durations_ex = explode("-",$products_durations);
	$products_durations1 = $products_durations_ex[0];
	$products_durations2 = $products_durations_ex[1];
	
	if($products_durations2 != "")
	$where_str .= " and (p.products_durations = '" . $products_durations1 . "' or p.products_durations = '" . $products_durations2 . "')";
	else
	$where_str .= " and (p.products_durations = '" . $products_durations1 . "' or p.products_durations > '" . $products_durations2 . "')";
	
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
        $order_str .= "p.products_durations " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name";
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

  require(DIR_FS_MODULES . FILENAME_PRODUCT_LISTING);


?>
        </td>
      </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
			 <!-- amit added to invite friend links start  -->
		
	   <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
		 <tr>
        <td class="main">	
		<!--	<b>Interesting in this fun tour? 	Why don't you <?php echo '<a href="' . tep_href_link(FILENAME_REFER_A_FRIEND, 'products_id='.$product_info['products_id'], 'SSL'). '">'; ?><font color="#ff9900" size="2" face="Verdana, Arial, Helvetica, sans-serif" style="text-decoration:underline;"> refer your friend </font></a>and make <?php echo tep_get_affiliate_percent_display();?> commission?</b>
		-->
			<table width="100%" cellpadding="2" cellspacing="2">
			<tr>
			<td width="25"><?php echo tep_image(DIR_WS_IMAGES.'tell_friend_icon.jpg',''); ?></td>
			<td class="main">&nbsp;&nbsp; 
			<b><?php echo '   <a href="' . tep_href_link(FILENAME_REFER_A_FRIEND, '', 'SSL'). '">'; ?><font color="#ff9900" size="2" face="Verdana, Arial, Helvetica, sans-serif" style="text-decoration:underline;"><?php echo TEXT_TELL_YOUR_FRIEND; ?></font></a> <?php  echo TEXT_ABOUT. $extraaddonadd;?> <?php echo TEXT_AND_MAKE;?> <?php echo tep_get_affiliate_percent_display();?> <?php echo TEXT_COMMISSION;?></b>
			</td>
			</tr>			 
			</table>
		</td>
      </tr>
	   <tr>
      		<td><?php echo tep_draw_separator(); ?></td>
       </tr>
	  <!-- amit added to invite friend links end -->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('sort', 'page')), 'NONSSL', true, false) . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
      </tr>

	  
    </table>
	  <!-- amit added for why buy from us start -->
		<link rel="stylesheet" type="text/css" href="balloontip.css" />
		<script type="text/javascript" src="balloontip.js"></script>
	<table width="100%" cellpadding="0" cellspacing="0">
  	  <tr>
        <td  class="main"><FONT size="2"  color="#D24040"><b><?php echo TEXT_TOP_HEADING_BOOK;?></b></FONT>
		</td>
      </tr>
	</table>
<table class=unnamed2 cellSpacing=2 cellPadding=2 
                        width="100%" bgColor=#EFEFEF border=0>
  <tr style="font-size:12px;">
    <td width="24%" ><B><?php echo TAB_SPECIALLY_DESIGN_TOURS; ?></B></td>
    <td width="20%"><B><?php echo TAB_LOW_PRICE_GUANRANTEED; ?></B></td>
    <td width="36%"><B><?php echo TAB_EXPERIENCED_DRIVER; ?></B></td>
    <td width="20%"><B><?php echo TAB_PROFESSIONAL_TOUR_DUIDE; ?></B></td>
  </tr>
  <tr>
    <td height="1" bgcolor="#000000"></td>
    <td width="20%" height="1" bgcolor="#000000"></td>
    <td width="36%" height="1" bgcolor="#000000"></td>
    <td width="20%" height="1" bgcolor="#000000"></td>
  </tr>
  <tr>
    <td valign="top" style="font-size:12px;"><?php echo TEXT_PARA_SPECIALLY_DESIGN_TOURS; ?></td>
    <td valign="top" style="font-size:12px;"><?php echo TEXT_PARA_LOW_PRICE_GUANRANTEED; ?></td>
    <td valign="top" style="font-size:12px;"><?php echo TEXT_PARA_EXPERIENCED_DRIVER; ?></td>
    <td valign="top" style="font-size:12px;"><?php echo TEXT_PARA_PROFESSIONAL_TOUR_DUIDE; ?></td>
  </tr>
</table>

	   <!-- amit added for why buy from us end -->