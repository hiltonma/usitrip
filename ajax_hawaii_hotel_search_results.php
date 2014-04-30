<?php
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

require('includes/application_top.php');
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

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
}elseif($stay_day_num>10){
	$error=true;
	$error_msn .= '<div>入住天数不能大于10天！</div>';
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
$ptc_sql = tep_db_query('SELECT distinct p.products_id FROM `products_to_categories` ptc , `products` p WHERE ptc.categories_id in('.$search_categories_sub_ids.') and ptc.products_id = p.products_id ');
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
		  <!--<td width="8%"><?php echo db_to_html('四人房')?></td>-->
		  <!--<td width="7%"><?php echo db_to_html('儿童')?></td>-->
		  <!--<td width="10%"><?php echo db_to_html('状态')?></td>-->
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

		  ?>
		  <tr <?php echo $tr_style;?>><td width="35%" height="20" class="td_one_left_p"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $value['id']);?>" target="_blank" title="<?php echo db_to_html($value['name'])?>" ><?php echo cutword( db_to_html($value['name']),36,'')?></a> </td>
		  <td width="6%"><?php echo $currencies->display_price($value['single'], $tax_rate_val); ?></td>
		  <td width="9%"><?php echo $currencies->display_price($value['double'], $tax_rate_val); ?></td>
		  <td width="9%"><?php echo $currencies->display_price($value['triple'], $tax_rate_val); ?></td>
		  <!--<td width="8%"><?php echo $currencies->display_price($value['quadr'], $tax_rate_val); ?></td>
		  <td width="7%"><?php echo $currencies->display_price($value['kids'], $tax_rate_val); ?></td>
		  <td width="10%">
		  	<input name="hotel_ids" type="radio" value="<?php echo $value['id']?>" />
			</td>
			-->
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
