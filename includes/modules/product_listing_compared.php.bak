<link rel="stylesheet" type="text/css" href="spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="spiffyCal/spiffyCal-v2-1-2008-04-21-min.js"></script>
<script language="javascript"><!--
  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "advanced_search", "products_date_available","btnDate","<?php echo $HTTP_GET_VARS['products_date_available']; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<div id="spiffycalendar" class="text"></div>
<body onLoad="initDynamicOptionLists();">
 <?php echo tep_draw_form('advanced_search', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false), 'get', 'onSubmit="return check_form(this);"') . tep_hide_session_id(); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="2" align="center">
<SCRIPT>

var regionState = new DynamicOptionList();
regionState.addDependentFields("departure_city_id","attraction");
regionState.forValue("").addOptions("无指定景点");
<?php 
	$subquery = 'select * from '.TABLE_TOUR_CITY.' ';	 
	$subresult = mysql_query($subquery);	
	while($subrow = mysql_fetch_array($subresult))
	{
		$cityarrayval = '';
		$row = 1;
		$cityarrayval = '"无指定景点"';
		$select_query = mysql_query("select  distinct(c.city_id) as cities_id,c.city from tour_city as c , products as p , products_destination as pd where p.departure_city_id = '".$subrow['city_id']."' and p.products_id = pd.products_id and pd.city_id=c.city_id ");
		while($select_result = mysql_fetch_array($select_query))
		{
			if($row == 0)
			{
			$cityarrayval = '"'.$select_result['city'].'"';
			}
			else
			{
			$cityarrayval .= ',"'.$select_result['city'].'"';
			}
			$row++;
			
			if($attraction == $select_result['cities_id'])
			{
				$selectedcity = $select_result['city'];
			}
		}
		
		
		?> 
		regionState.forValue("<?php echo $subrow['city_id'] ?>").addOptions(<?php echo $cityarrayval ?>);
		regionState.forValue("<?php echo $subrow['city_id'] ?>").setDefaultOptions("<?php echo $selectedcity ?>");
		<?php
	}
?>

</SCRIPT>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
<?php
  if ($messageStack->size('search') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('search'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>
      <tr>
        <td class="bline"><table border="0" width="100%" cellspacing="0" cellpadding="2">
             
			 	<?php 
				$city_class_array = array(array('id' => '', 'text' => TEXT_NONE));
				$city_class_query = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . "  where departure_city_status = '1' order by city");
				while ($city_class = tep_db_fetch_array($city_class_query)) 
				{
				  $city_class_array[] = array('id' => $city_class['city_id'],
											 'text' => $city_class['city']);
				}
			  
			  ?>
			  
              <tr>
                <td width="23%" rowspan="7" class="fieldKey" valign="top">
						<table border="0" width="100%" cellspacing="10" cellpadding="2">
						  	<tr>
								<td class="fieldKey"><img src="images/TourSearchTips.gif" width="247" height="34" /></td>
							</tr>
							<tr>
								<td width="10%" class="smallText" align="justify"><img src="images/Required.gif" width="260" height="54" /></td>
							</tr>
						</table>
				</td>
                <td width="28%" valign="bottom" class="searchlables"> <?php echo DEPARTURE_CITY; ?></td>
                <td width="24%" valign="top" class="searchlables"><?php echo START_DATE . tep_draw_checkbox_field('start_date_ignore', '1','checked').IGNORE ;?></td>
                <td width="25%" valign="bottom" class="searchlables"><?php echo DURATION; ?></td>
              </tr>
              <tr>
                <td width="28%" class="searchlables"><?php echo tep_draw_pull_down_menu('departure_city_id', $city_class_array, '', 'id=departure_city_id'); ?></td>
                <td class="searchlables"><script language="javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script></td>
                <td class="searchlables"><?php
				   	if($HTTP_GET_VARS['products_durations'] != '')
				    $javavar ="document.advanced_search.products_durations.value='".$HTTP_GET_VARS['products_durations']."';"
					
					?>
                  <select name="products_durations" id="products_durations">
				    <option value="" selected>无指定景点</option>
                    <option value="1-1">1 day</option>
                    <option value="2-2">2 days</option>
                    <option value="2-3">2 to 3 days</option>
                    <option value="3-3">3 days</option>
                    <option value="3-4">3 to 4 days</option>
                    <option value="4-4">4 days</option>
                    <option value="4-">4 days or more</option>
                    <option value="5-">5 days or more</option>
                  </select>
				  <script language=javascript>
					<?php echo $javavar; ?>
					</script>
                </td>
              </tr>
			  
              <tr>
                <td class="searchlables">Attraction:</td>
                <td class="searchlables">&nbsp;</td>
                <td class="searchlables">&nbsp;</td>
              </tr>
              <tr>
                <td class="searchlables"><select name="attraction"  id="attraction" style="width:auto;">
					 <SCRIPT>regionState.printOptions("attraction")</SCRIPT>
					</select></td>
                <td class="searchlables">&nbsp;</td>
                <td class="searchlables">&nbsp;</td>
              </tr>
              
			  <tr>
                <td class="searchlables"><?php echo OPTIONAL_KEYWORD;?></td>
                <td class="searchlables"></td>
                <td class="searchlables">&nbsp;</td>
              </tr>
              <tr>
                <td class="searchlables" colspan="2"><?php echo tep_draw_input_field('keywords',$HTTP_GET_VARS['keywords']); ?>
				<input type="submit" name="search" value="Search"/></td>
                <td class="searchlables">&nbsp;</td>
              </tr>
			    
			  <tr>
                <td colspan="4"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
			  
            </table></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
    </table></form>
	</body>

	<?php
/*
  $Id: product_listing.php,v 1.1.1.1 2004/03/04 23:41:11 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/


  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<style type="text/css">
<!--
.STYLE2 {
font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #990000;
	font-weight: bold;
	font-size:12px;
}
-->
</style>
<table border="0" width="100%" cellspacing="0" cellpadding="2" align="center">
  <tr>
  <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td><span class="STYLE2">welcome to</span> <span class="TitleTextYellow"><?php 
				$cityquery = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id = '".$departure_city_id."'  order by city");
				$cityclass = tep_db_fetch_array($cityquery);
				echo $cityclass['city']; 
	?></span>
	<br>
	<span class="smallText">please read terms and conditions before booking online.</span>
	</td>
	<td align="right"><?php 
	
				//echo $lc_text = tep_create_sort_heading($HTTP_GET_VARS['sort'], 1, TABLE_HEADING_PRODUCTS);
				//echo $lc_text = tep_create_sort_heading($HTTP_GET_VARS['sort'], 3, TABLE_HEADING_PRICE); 
		 $sortby = $HTTP_GET_VARS['sort'];
		$colnum = 1;
		$colnum1 = 3;
		$quertstring1 = $colnum . 'a';
		$quertstring2 = $colnum . 'd';
		$quertstring3 = $colnum1 . 'a';
		$quertstring4 = $colnum1 . 'd';
		
		$quertstring = array(array('id' => '0', 'text' => '--Select--'));
		$quertstring[] = array('id' => $quertstring1, 'text' => 'Product Name Acending');
		$quertstring[] = array('id' => $quertstring2, 'text' => 'Product Name Decending');
		$quertstring[] = array('id' => $quertstring3, 'text' => 'Price Acending');
		$quertstring[] = array('id' => $quertstring4, 'text' => 'Price Decending');
		
		echo tep_draw_form('sort_order', tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('page', 'info', 'sort')) . 'page=1'));
		echo '<span class="smallText">Sort By:</span>'.tep_draw_pull_down_menu('sort', $quertstring, '', 'id=sort onchange="form.submit()"');
		echo '</form>'; 
		?>
		
		</td>
  </tr>
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>

  <tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>

</table>
<?php
  }


  if ($listing_split->number_of_rows > 0) {
  
  echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
					  <tr>
						<td height="20" bgcolor="#002450"><table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
							  <td align="left" class="TitleTextWhite">&nbsp;&nbsp;旅行线路:</td>
							  <td width="10">&nbsp;</td>
							</tr>
						</table></td>
					  </tr>
					  
					<tr>
					
        			';
  
    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
      $rows++;

      if (($rows/2) == floor($rows/2)) 
	  {
		echo '<td align="center" bgcolor="#ECF9FF">';
      } else 
	  {
        echo '<td align="center" bgcolor="#c6edff">';        
      }

			echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=add_product'));
			echo '	<table width="100%" border="0" cellspacing="4" cellpadding="0" class="textBlack2">
			<tr>
              <td colspan="2" align="left">&nbsp;<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']).'" class="textBlack"><span class="STYLE4">' . $listing['products_name'] . '</span></a></td>
              <td align="left">&nbsp;</td>
			  <td align="left"  width="5%">&nbsp;</td>
            </tr>';
			
			echo '<tr>
              <td width="170" align="left">
			  &nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>&nbsp;';
            echo '</td>
              <td align="left"  width="40%">
			  
			  <table width="100%" border="0" cellspacing="4" cellpadding="0" class="text">
                <tr>
					<td width="30%"></td>
                  <td  width="70%" align="left"><span class="TitleTextBlack">Price:</span> <span class="STYLE2">';
			if (tep_get_products_special_price($listing['products_id'])) 
			{
              echo '&nbsp;<s>' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price(tep_get_products_special_price($listing['products_id']), tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>&nbsp;';
            } 
			else 
			{
              echo  '&nbsp;' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '&nbsp;';
            }
			echo '</span> </td>
                </tr>';
				
				
				$avaliabledate = '';
				$from2 = '';
				$from1 = '';
				if($listing['products_is_regular_tour'] == 0)
			    {	
					$available_query = tep_db_query("select * from ".TABLE_PRODUCTS_AVAILABLE." where products_id = ".$listing['products_id']."  order by available_date");
					while($available_result = tep_db_fetch_array($available_query))
					{
						//echo '<br>'.$available_result['available_date'];
						$y = substr($available_result['available_date'], 0, 4);
						$m = substr($available_result['available_date'], 5, 2);
						$d = substr($available_result['available_date'], 8, 2);
						
						$extracharges = '';
						$prifix = '';
						if($available_result['extra_charges']!='0.00' && $available_result['extra_charges']!='')
						{
							$extracharges = '$'.$available_result['extra_charges'].')';
							$prifix = '('.$available_result['prefix'];
						}
						
						$from2 =  mktime (date ("H"), date ("i"), date ("s"), date($m), date ($d), date($y));
						$from1 = date ("Y-m-d (D)", $from2);
						$formval = date ("Y-m-d", $from2);
						$avaliabledate .= '<option value="'.$formval.'::'.$prifix.'##'.$extracharges.'">'.$from1.' '.$prifix.$extracharges.'</option>';
					}
				
				}elseif($listing['products_is_regular_tour'] == 1)
				{
					$daycount = 1;
			  		$day1 = '';
			  		$operator_query = tep_db_query("select * from ".TABLE_PRODUCTS_START_DATE." where products_id = ".$listing['products_id']."  order by products_start_day");
					  while($operator_result = tep_db_fetch_array($operator_query))
					  {
						
						if($operator_result['products_start_day'] == 1)
						{
							$day1 .= 'Sun/';
						}
						if($operator_result['products_start_day'] == 2)
						{
							$day1 .= 'Mon/';
						}
						if($operator_result['products_start_day'] == 3)
						{
							$day1 .= 'Tue/';
						}
						if($operator_result['products_start_day'] == 4)
						{
							$day1 .= 'Wed/';
						}
						if($operator_result['products_start_day'] == 5)
						{
							$day1 .= 'Thu/';
						}
						if($operator_result['products_start_day'] == 6)
						{
							$day1 .= 'Fri/';
						}
						if($operator_result['products_start_day'] == 7)
						{
							$day1 .= 'Sat/';
						}
						$daycount++;
					  }
					  
					  
						$extracharges = '';
						$prifix = '';
						if($operator_result['extra_charge']!='0.00' && $operator_result['extra_charge']!='')
						{
							$extracharges = '$'.$operator_result['extra_charge'].')';
							$prifix = '('.$operator_result['prefix'];
						}
							
							if($daycount == 8)
							{
								
								for($adate=7;$adate<=26; $adate++)
								{
									$from2 =  mktime (date ("H"), date ("i"), date ("s"), date("m"), date ("d") + $adate, date("Y"));
									$from1 = date ("Y-m-d (D)", $from2);
									$formval = date ("Y-m-d", $from2);
									//$avaliabledate .= '<option value="'.$from1.'">'.$from1.'</option>';
									$avaliabledate .= '<option value="'.$formval.'::'.$prifix.'##'.$extracharges.'">'.$from1.' '.$prifix.$extracharges.'</option>';

								}
								
								
							}
							else
							{
								$twnetycount = 0;
								for($adate=7;$adate<=200; $adate++)
								{
									$from2 =  mktime (date ("H"), date ("i"), date ("s"), date("m"), date ("d") + $adate, date("Y"));
									$from1 = date ("Y-m-d (D)", $from2);
									$check = date ("D", $from2);
									$formval = date ("Y-m-d", $from2);
									if(strstr($day1, $check))
									{
									//$avaliabledate .= '<option value="'.$from1.'">'.$from1.'</option>';
									$avaliabledate .= '<option value="'.$formval.'::'.$prifix.'##'.$extracharges.'">'.$from1.' '.$prifix.$extracharges.'</option>';

									$twnetycount++;
									if($twnetycount==20)
									break;
									}
								}
							}
				}
				
				
				
					echo '<tr>
						  <td align="right">Date:</td>
						  <td align="left"><select name="availabletourdate" style="width:auto;">
							  '.$avaliabledate.'
							</select></td>
						</tr>
						';
				
				    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $listing['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
					$products_attributes = tep_db_fetch_array($products_attributes_query);
					if ($products_attributes['total'] > 0) {
				
					  $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . $listing['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_sort_order");
					  while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
						$products_options_array = array();
						$products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $listing['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order");
						while ($products_options = tep_db_fetch_array($products_options_query)) {
						  $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
						  if ($products_options['options_values_price'] != '0') {
							$products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
						  }
						}
				
						if (isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
						  $selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
						} else {
						  $selected_attribute = false;
						}
				?>
							<tr>
							  <td class="main" align="right"><?php echo $products_options_name['products_options_name'] . ':'; ?></td>
							  <td class="main" align="left"><?php echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute); ?></td>
							</tr>
				<?php
					  }
					}
	
	
                $depart_option ='';
				$departure_query = tep_db_query("select * from ".TABLE_PRODUCTS_DEPARTURE." where products_id = ".$listing['products_id']." ");
				   while($departure_result = tep_db_fetch_array($departure_query))
				   {
				   	$depart_option .= '<option value="'.$departure_result['departure_time'].'::::'.$departure_result['departure_address'].'">'.$departure_result['departure_time'].' &nbsp; '.$departure_result['departure_address'].'</option>'; 
				   }  
				   
				   if($depart_option != '')
				   {
					echo'<tr>
					  <td align="right">Departure:</td>
					  <td align="left">	<select name="departurelocation" style="width:auto;">'.$depart_option.'</select></td>
					</tr>';
				  }				
				  //<img src="images/addToCart.gif" width="108" height="21" border="0" />
               echo '<tr>
			   <td></td>
                  <td align="left">'.tep_draw_hidden_field('products_id', $listing['products_id']) . tep_template_image_submit('addToCart.gif', IMAGE_BUTTON_IN_CART).'</td>
                </tr>
                
                
              </table></td>
              <td align="left" width="30%">';
			
			  if($listing['products_is_regular_tour'] == 1)
			  {	
			  	echo '<span class="TitleTextBlack">Operate:</span>';
				  
				  if($daycount == 8)
				  {
				  	echo 'daily';
				  }
				  else
				  {
				  	echo $day1;
				  }
			 }	  
			 //'.substr($listing['products_description'],0,200).'
			  echo '<br><span class="TitleTextBlack">Features: </span><div align="justify">'.$listing['products_small_description'].'</div>
				<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $listing['products_id']).'" class="textBlack"><span class="textBlue">Details</span></a></td>
      </tr></table></form></td></tr>';			
			/* echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>&nbsp;';
           echo $listing['products_description'];
            echo '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a>&nbsp;';
           
            
            
            echo  '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id']) . '">' . tep_template_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;';
           
	   */
	   


	   
	   
    }
	
	echo ' </td>
		  </tr>
		</table>';
		
  } else {
   /*
    $list_box_contents = array();

    $list_box_contents[0] = array('params' => 'class="productListing-odd"');
    $list_box_contents[0][] = array('params' => 'class="productListing-data"',
                                   'text' => TEXT_NO_PRODUCTS);

    new productListingBox($list_box_contents);
	*/
	?>
	<table border="0" width="98%" cellspacing="0" cellpadding="2" class="automarginclass" align="center">
	  <tr>
		<td class="main">
		<?php //echo TEXT_NO_PRODUCTS;?>
		<?php include(dirname(__FILE__).'/search_null.php');?>
		</td>
	  </tr>
	</table>
	<?php
  }

  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<table border="0" align="center" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
  <tr>
    <td class="smallText"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>

<?php
  }
?>
