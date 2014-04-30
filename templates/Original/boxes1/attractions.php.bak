<?php

$h3class = 'style="	
			border-bottom:0px;
			color:#999;
			background:none;
			width:94%;
			margin-left:6px;
			padding-top:10px;
			display:inline;"';
$total_attractions = 0;
?>
<?php
	////
	//周边主要景点
	$parent_categories_query = tep_db_query("select categories_destinations from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
	$row_parent_category = tep_db_fetch_array($parent_categories_query);
	$categories_destinations = $row_parent_category['categories_destinations'];
	if(tep_not_null($categories_destinations)){
		$categories_destinations_array = explode(',',$categories_destinations);
		$total_attractions += count($categories_destinations_array); 
	}
			
	////
			
			if( (!isset($_GET['cat_mnu_sel'])) || (isset($_GET['cat_mnu_sel']) && ($_GET['cat_mnu_sel']=='tours' || $_GET['cat_mnu_sel']=='')) ){
				$sql_str = "select categories_top_attractions_tourtab as categories_top_attractions from ".TABLE_CATEGORIES." where categories_id = '".$current_category_id."'";
			}else{
				$sql_str = "select categories_top_attractions as categories_top_attractions from ".TABLE_CATEGORIES." where categories_id = '".$current_category_id."'";
			}
			//echo $sql_str;
			$top_10_attractions_query = tep_db_fetch_array(tep_db_query($sql_str));
			$top_10_attractions_query['categories_top_attractions'];
			if($top_10_attractions_query['categories_top_attractions'] != ''){
				$attractions_exist = 1;
				$top_10_attractions_array = explode(',',$top_10_attractions_query['categories_top_attractions']);
				
?>
				<h3 style="border-bottom: 1px solid #58BAF9"><?php echo HEADING_ATTRACTIONS; ?></h3>
				<h3 <?=$h3class?>><?php echo db_to_html(tep_get_category_name($current_category_id)).db_to_html('出发'); ?></h3>
				<div class="chufa-city1">
					<ul>
					<?php
						$top_10_attractions_array_count = count($top_10_attractions_array);
						$row_attractions_array = array();
						for($i=0; $i<min($top_10_attractions_array_count,30); $i++){
						
							$attractions_query = tep_db_query("select distinct(c.city_id), c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co  where c.city_id ='".$top_10_attractions_array[$i]."' and s.zone_country_id = co.countries_id and c.state_id = s.zone_id and c.city !='' and (c.is_attractions='1' or c.is_attractions='2') order by c.city");
							$row_attractions = tep_db_fetch_array($attractions_query);
							if((int)$row_attractions['city_id']){
								$row_attractions_array[] = array('city_id'=>$row_attractions['city_id'], 'city'=>$row_attractions['city']);
							}
						}
						
						$attraction_count = 0;
						$total_attractions += count($row_attractions_array);
						$row_attractions_array_count = count($row_attractions_array);
						for($j=0; $j<$row_attractions_array_count; $j++){	
							$attraction_count++;
							
							if(($attraction_count-1) >0 && ($attraction_count-1) %15==0){
								echo '</ul><ul>';
							}
							if($_GET['cat_mnu_sel'] == 'vcpackages'){
								$tabname = 'vp';
							}else{
								$tabname = '';
							}
							?>
							<li id="left_topatt_<?php echo $attraction_count; ?>">
							<a href="JavaScript:void(0);" onClick="attraction_selected('<?php echo $attraction_count; ?>', '<?php echo $total_attractions; ?>'); set_value_to_hidden_var('top_attractions','<?php echo $row_attractions_array[$j]['city_id']; ?>','<?php echo $cPath; ?>', '<?php echo $tabname; ?>');" class="lanzi4">
							<span id="left_topatt_city_<?php echo $row_attractions_array[$j]['city_id']; ?>"><?php echo db_to_html($row_attractions_array[$j]['city']); ?></span></a><?php if($j<($row_attractions_array_count-1)){ echo '|';}?>
							
							</li>
							<?php
						}
					?>
					</ul>

				</div>
				<?php
			}else{
				$attractions_exist = 0;
			}
		?>
	
	
<?php
if((int)count($categories_destinations_array)){

?>	
		<h3 <?=$h3class?>><?php echo db_to_html('周边主要景点'); ?></h3>
		<div class="chufa-city1">
		<ul>
		<?php
		$loop_num = 0;
		$categories_destinations_array_count = count($categories_destinations_array);
		foreach($categories_destinations_array as $key => $val){
			$attraction_count++;
			$loop_num++;
			if($val == $current_category_id){
				$li_class = 's';
			}else{
				$li_class = '';
			}
		?>
		<li id="left_topatt_<?php echo $attraction_count; ?>">
		<a href="JavaScript:void(0);" onClick="attraction_selected('<?php echo $attraction_count; ?>', '<?php echo $total_attractions; ?>'); set_value_to_hidden_var('top_attractions','<?php echo $val; ?>','<?php echo $cPath; ?>', '<?php echo $tabname; ?>');" class="lanzi4">
		<span id="left_topatt_city_<?php echo $val; ?>"><?php echo db_to_html(tep_get_city_name($val)); ?></span></a><?php if($loop_num<$categories_destinations_array_count){ echo '|';}?>
		</li>
		<?php
		}
		?>
		</ul>
		</div>
<?php
}
?>

<?php
//如果当前景点下面有子目录则把子目录列出到这里来 end
#像大峡谷这些都有子目录的
if(count($cPath_array)>1){
	$sub_categories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$current_category_id . "' AND categories_feature_status = '0' AND categories_status='1' Order By sort_order ASC, categories_id DESC ");
	$sub_categories = tep_db_fetch_array($sub_categories_query);
	if($sub_categories['categories_id']){
?>
	<h3><?php echo HEADING_ATTRACTIONS; ?></h3>
	<h3 <?=$h3class?>><?php echo db_to_html(db_to_html('出发到').tep_get_category_name($current_category_id)); ?></h3>
	<div class="chufa-city1">
		<ul>
<?php
		do{
			echo '<li><a href="'.tep_href_link(FILENAME_DEFAULT,'cPath='.$sub_categories['categories_id']).'">'.tep_get_category_name($sub_categories['categories_id']).'</a></li>';
		}while($sub_categories = tep_db_fetch_array($sub_categories_query));
?>
		</ul>
	</div>
<?php		
		$attractions_exist=1;
	}
}
//如果当前景点下面有子目录则把子目录列出到这里来 end
?>