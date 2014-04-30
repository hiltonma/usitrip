<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require('includes/application_top.php');
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

//写cookie保存已选中的行程值
if($_GET['action']=='write_cookie'){
	$_SESSION['hawaii_self'] = array();
	$_SESSION['hawaii_self']['free_combination_day'] = html_to_db(ajax_to_general_string($_POST['free_combination_day']));
	$_SESSION['hawaii_self']['adult_total'] = html_to_db(ajax_to_general_string($_POST['adult_total']));
	$_SESSION['hawaii_self']['child_total'] = html_to_db(ajax_to_general_string($_POST['child_total']));
	$_SESSION['hawaii_self']['tours_day'] = html_to_db(ajax_to_general_string($_POST['tours_day']));
	if(strlen($_POST['free_combination_day'])<2){
		$_SESSION['hawaii_self'] = array();
	}
	exit;
}

$error = false;
$error_msn = '';
if(!(int)check_date($_POST['date_free_start'])){
	$error=true;
	$error_msn .= '<div>入住日期无效！</div>';
}else{}

if(!(int)check_date($_POST['date_frees_end'])){
	$error=true;
	$error_msn .= '<div>离店时间无效！</div>';
}
if(!(int)$_POST['search_categories_id']){
	$error=true;
	$error_msn .= '<div>无有效的景点！</div>';
}
//计算入住天数
$stay_day_num = (strtotime($_POST['date_frees_end']) - strtotime($_POST['date_free_start']))/24/3600;
if($stay_day_num<1){
	$error=true;
	$error_msn .= '<div>入住天数不能小于1！</div>';
}

$can_min_date = date('Y-m-d',strtotime("+2 days"));
if($_POST['date_free_start'] < $can_min_date){
	$error=true;
	$error_msn .= '<div>入住日期需在'.chardate($can_min_date, "D").'之后！</div>';
}

$_POST['hotel_price_range'];
$_POST['ajax'];
$_POST['filtration'];

if($error==true){
	echo db_to_html($error_msn);
	exit;
}

//查询
//1、列出夏威夷的所有产品id
$search_categories_sub_ids = tep_get_category_subcategories_ids($_POST['search_categories_id']);
$ptc_sql = tep_db_query('SELECT distinct p.products_id FROM `products_to_categories` ptc , `products` p WHERE ptc.categories_id in('.$search_categories_sub_ids.') and ptc.products_id = p.products_id and p.products_durations <= '.((int)$stay_day_num+1)/*(int)$_POST['max_date']*/.' and p.products_durations_type < 1 ');
$products_ids = array();
while($ptc_rows = tep_db_fetch_array($ptc_sql)){
	$products_ids[] = $ptc_rows['products_id'];
}
$products_ids_str = implode(',',$products_ids);
if(!tep_not_null($products_ids_str)){
	echo db_to_html('无合适结果！0');
	exit;
}

//2、取得这堆产品里的有关的酒店的id
$options_ids_str = '2,18,19,48,52,59';	//酒店
$p_sql = tep_db_query('SELECT distinct options_values_id FROM `products_attributes` WHERE products_id in('.$products_ids_str.') and options_id in('.$options_ids_str.') ');
$options_values_id_for_hotel = '';
while($p_rows = tep_db_fetch_array($p_sql)){
	$options_values_id_for_hotel.= $p_rows['options_values_id'].',';
}
$options_values_id_for_hotel = substr($options_values_id_for_hotel,0,(strlen($options_values_id_for_hotel)-1));
//3、列出酒店资料 升级酒店、行程中提及到的酒店，接送地酒店不在此列之内
if(!tep_not_null($options_values_id_for_hotel)){
	echo db_to_html('无合适结果！1');
	exit;
}

$hotel_sql = tep_db_query('SELECT products_options_values_id, products_options_values_name FROM `products_options_values` WHERE `products_options_values_id` IN ( '.$options_values_id_for_hotel.')  and language_id ="'.(int)$languages_id .'"');
$hotels = array();
while($hotel_rows = tep_db_fetch_array($hotel_sql)){
	$price_range_where = '';
	if(tep_not_null($_POST['hotel_price_range'])){
		$tmp_array = explode(',',$_POST['hotel_price_range']);
		if(count($tmp_array)>1){
			$price_range_where .= ' and ( p.products_double >= '.((int)$tmp_array[0]/2).' and p.products_double < '.((int)$tmp_array[1]/2).')  ';
		}
	}
	
	$p_hotel_sql = tep_db_query('SELECT distinct p.products_id, p.products_image, p.products_single,(p.products_double * 2) as 2double ,(p.products_triple * 3) as 3triple, (p.products_quadr*4) as 4quadr, p.products_tax_class_id, pd.products_name FROM `products_description` pd, `products` p, `products_to_categories` ptc WHERE pd.products_id =p.products_id and ptc.products_id = p.products_id and ptc.categories_id ="182" and p.products_status="1" ' . ' and pd.products_name = "'.$hotel_rows['products_options_values_name'].'" '.$price_range_where.' Limit 1');
	$p_hotel_row = tep_db_fetch_array($p_hotel_sql);
	if((int)$p_hotel_row['products_id']){
		//先把满足条件的酒店找到：
		$hotels[$p_hotel_row['products_id']]=array('id'=> $p_hotel_row['products_id'],
						'name'=> $p_hotel_row['products_name'],
						'single'=> $p_hotel_row['products_single'],
						'double'=> $p_hotel_row['2double'],
						'triple'=> $p_hotel_row['3triple'],
						'quadr'=> $p_hotel_row['4quadr'],
						'kids'=> $p_hotel_row['products_kids'],
						'tax_class_id' => $p_hotel_row['products_tax_class_id'],
						'image' => DIR_WS_IMAGES.$p_hotel_row['products_image'],
						);		
	}
}
//print_r($hotels);

$hotels_count = count($hotels);
//html code start
if($hotels_count==0){
	echo db_to_html('没有合适的结果！2');
	exit;
}
if($hotels_count>0){
?>

		<table border="0" cellpadding="0" cellspacing="0" class="hotel_title_hawaii">
		  <tr class="hotel_title_hawaii_tt"><td width="35%" height="27" class="td_one_left_p"><?php echo db_to_html('酒店名')?></td>
		  <td width="6%"><?php echo db_to_html('单人')?></td>
		  <td width="9%"><?php echo db_to_html('双人房')?></td>
		  <td width="9%"><?php echo db_to_html('三人房')?></td>
		  <!--<td width="8%"><?php echo db_to_html('四人房')?></td>
		  <td width="7%"><?php echo db_to_html('儿童')?></td>-->
		  <td width="16%"><?php echo db_to_html('匹配行程')?></td>
		  <td width="10%"><?php echo db_to_html('状态')?></td>
		  </tr>
		  <?php
		  $i=0;
		  //foreack start
		  foreach((array) $hotels as $key => $value){
		  	$tax_rate_val = tep_get_tax_rate($value['tax_class_id']);
		  	//$tr_style =' style="background:#FEEBDA;"';
		  	$tr_style =' ';
			if($i%2==0){
				$tr_style ='';
			}
			
			//匹配行程
			$filtration_where = '';
			//echo $_POST['filtration'];
			if(tep_not_null($_POST['filtration'])){
				$filtration_ids = explode(',',$_POST['filtration']);
				$filtration_ids_str = '';
				for($fn=0; $fn<count($filtration_ids)-1; $fn++){
					$filtration_ids_str .= (int)$filtration_ids[$fn].',';
				}
				$filtration_ids_str = substr($filtration_ids_str,0,strlen($filtration_ids_str)-1 );
				if(tep_not_null($filtration_ids_str)){
					$filtration_where = ' and p.products_id not in ('.$filtration_ids_str.') ';
				}
			}
			
			$tours_sql = tep_db_query('SELECT pd.* ,p.*
FROM  `products_options_values_to_products_options` ovtp,
`products_options_values` ov,
`products_attributes` pa,
`products_description` pd,
`products` p,
`products_to_categories` ptc
WHERE ovtp.products_options_values_id = ov.products_options_values_id AND ovtp.products_options_id
IN ('.$options_ids_str.')  AND (ov.products_options_values_name ="'.$value['name'].'" || ov.products_options_values_name Like "%Hilton%") and pa.options_values_id = ovtp.products_options_values_id and pd.products_id = pa.products_id and p.products_id = pd.products_id and p.products_status ="1" and pd.language_id ="'.(int)$languages_id.'" and ptc.products_id = p.products_id and ptc.categories_id in ('.$search_categories_sub_ids.') and products_durations <= '.((int)$stay_day_num+1)/*(int)$_POST['max_date']*/.' and products_durations_type < 1 '.$filtration_where .' Group By p.products_id');
			$tours_sum = tep_db_num_rows($tours_sql);

		  ?>
		  <tr <?php echo $tr_style;?>><td width="35%" height="20" class="td_one_left_p"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $value['id']);?>" target="_blank" title="<?php echo db_to_html($value['name'])?>" ><?php echo cutword( db_to_html($value['name']),36,'')?></a> </td>
		  <td width="6%"><?php echo $currencies->display_price($value['single'], $tax_rate_val); ?></td>
		  <td width="9%"><?php echo $currencies->display_price($value['double'], $tax_rate_val); ?></td>
		  <td width="9%"><?php echo $currencies->display_price($value['triple'], $tax_rate_val); ?></td>
		  <!--<td width="8%"><?php echo $currencies->display_price($value['quadr'], $tax_rate_val); ?></td>
		  <td width="7%"><?php echo $currencies->display_price($value['kids'], $tax_rate_val); ?></td>-->
		  <td width="16%" class="pipei_hotel_hawaii"><a href="javascript:void(0)" onClick="ClearTours(<?php echo $i;?>)"><?php echo db_to_html($tours_sum.'个')?></a><img src="image/tours_jiantou_hawaii.gif" class="jiantou_tours"></td>
		  <td width="10%">
		  <input name="hotel_ids" type="radio" value="<?php echo $value['id']?>" id="hotel_ids_<?php echo $value['id']?>" onClick="ClearTours(<?php echo $i;?>)">
		  <input name="hotel_image_<?php echo $value['id']?>" id="hotel_image_<?php echo $value['id']?>" type="hidden" value="<?php echo $value['image']?>">
		  <input name="hotel_name_<?php echo $value['id']?>" id="hotel_name_<?php echo $value['id']?>" type="hidden" value="<?php echo db_to_html($value['name'])?>">
		  
		  </td></tr>
			<tr id="pipei_tours_tr_<?=$i?>" style="display:<?='none'?>">
			  <td height="20" colspan="8">
			<div class="pipei_tours_box">
			<table border="0" cellpadding="0" cellspacing="0">
			   <tr class="pipei_tours_title"><td width="67%" height="25" class="td_one_left_p"><?php echo db_to_html('匹配行程名')?></td>
			   <td width="10%"><?php echo db_to_html('匹配度')?></td>
			   <td width="14%"><?php echo db_to_html('价格')?></td>
			   <td width="9%"><?php echo db_to_html('状态')?></td>
			   </tr>
			   <tr><td  style="height:8px"></td></tr>
			   <?php
			   $ToursObj = array();
			   while($tours = tep_db_fetch_array($tours_sql)){
					//$stay_day_num = 行程天数-1 就完全匹配
					$Pepei = '<span class="sub_orangle_txt">部分匹配</span>';
					$PepeiNum = 0;
					if(($tours['products_durations']-1) == $stay_day_num && (int)$tours['products_durations_type']=="0" ){
						$Pepei = '<span class="highline-txt">完全匹配</span>';
						$PepeiNum = 1;
					}
					if (tep_get_products_special_price($tours['products_id'])){
						$price = '<span class="sp8">' .  $currencies->display_price($tours['products_price'], tep_get_tax_rate($tours['products_tax_class_id'])) . '</span>&nbsp;&nbsp;<span class="sp2">' . $currencies->display_price(tep_get_products_special_price($tours['products_id']), tep_get_tax_rate($tours['products_tax_class_id'])) . '</span>&nbsp;';
					} 
					else{
						$price = $currencies->display_price($tours['products_price'], tep_get_tax_rate($tours['products_tax_class_id'])) ;
					}
					
					$ToursObj[] = array('products_id'=> $tours['products_id'], 'products_name'=> $tours['products_name'], 'pepei_str'=> $Pepei, 'PepeiNum' => $PepeiNum, 'price'=> $price, 'durations'=> $tours['products_durations']);
			   }
			   
			   //按是否匹配来排序
				foreach ( $ToursObj as $key => $row) {
					$pep[$key] = $row['PepeiNum'];
				}
				
				@array_multisort($pep,SORT_DESC, $ToursObj);
			   
			   for($j=0; $j<count($ToursObj); $j++){
			   ?>
			   <tr ><td width="71%" height="22" class="td_one_left_p"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $ToursObj[$j]['products_id']);?>" target="_blank" title="<?php echo db_to_html(tep_db_output($ToursObj[$j]['products_name']))?>"><?php echo cutword( db_to_html(tep_db_output($ToursObj[$j]['products_name'])),66,'')?></a></td>
			   
			   <td width="9%">
			  	<?php echo db_to_html($ToursObj[$j]['pepei_str']);?>
			   </td>
			   <td width="11%"><?php echo db_to_html($ToursObj[$j]['price']);?></td>
			   <td width="9%">
			   
			   <input name="tours_ids" type="radio" value="<?php echo $ToursObj[$j]['products_id']?>" id="tours_ids_<?php echo $value['id']?>_<?php echo $ToursObj[$j]['products_id']?>" onClick="sel_hotel(<?php echo $value['id']?>);">
			   <input name="durations_<?php echo $value['id']?>_<?php echo $ToursObj[$j]['products_id']?>" type="hidden" id="durations_<?php echo $value['id']?>_<?php echo $ToursObj[$j]['products_id']?>" value="<?php echo $ToursObj[$j]['durations']?>">
			  <?php
			  //此值用于下一个日期段的开始日期，不可删除
			  $tmp_date_end = date("Y-m-d",strtotime($_POST['date_free_start'])+($ToursObj[$j]['durations']*24*3600));
			  ?>
			  <input name="tmp_end_date_<?php echo $value['id']?>_<?php echo $ToursObj[$j]['products_id']?>" type="hidden" value="<?php echo $tmp_date_end;?>" />

			   </td>
			   </tr>
			   <?php
			   }
			   ?>
			   
			</table>

		  </div>
			  </td>
			  </tr>
			<?php
				$i++;
			}
			//foreack end
			?>
			
		  </table>

<?php
}
//html code end
?>
