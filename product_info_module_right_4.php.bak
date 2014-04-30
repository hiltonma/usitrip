<?php 
//接送时间和地点

$max_show_rows_num = 20;	//最大显示10条
$start_show_rows_num = 1;	//开始为第1条
$total_pages = 1; //初始总页数
?>

<?php 
////html页面显示部分
$total_dep_cnt_row = 0;
if($final_departure_array_result != ''){
?>
	<div class="timePlaceTop">
	  <span><?php echo db_to_html("时间")?></span><span><?php echo db_to_html("地点")?></span>
	</div>
	<ul class="timePlaceCon">
	  <?php
	  foreach($final_departure_array_result as $key => $val){
		$valarray = explode("##",$val);

		$displaydeparturestr = '';
		$departures_query_address = "select * from ".TABLE_PRODUCTS_DEPARTURE." where  products_id = ".(int)$HTTP_GET_VARS['products_id']." and departure_id = ".(int)$valarray[1]." ";
		$departures_row_address = tep_db_query($departures_query_address);
		$departures_address = tep_db_fetch_array($departures_row_address);
		

		$len=strlen($departures_address['departure_time']);
		if($len == 6){
			$depart_final = '0'.$departures_address['departure_time'];
		}else{
			$depart_final = $departures_address['departure_time'];
		}
		
		//howard added departure_tips
		$departures_tips = $departures_address['departure_tips'];
		$tips_msn ='';
		if(tep_not_null($departures_tips)){
			$tips_msn = '简介：'.$departures_tips;
		}
		//howard added departure_tips end
		$departure_time_li_style = '';
		if($start_show_rows_num > $max_show_rows_num){
			$departure_time_li_style = ' style="display:none" ';
		}
		
	  ?>
	  <li id="departure_time_li_<?= $start_show_rows_num?>" <?= $departure_time_li_style;?>>
		<span><?= db_to_html($depart_final)?></span>
		<div class="timePlaceConMid">
		  <h2><?php echo db_to_html($departures_address['departure_address'].', <span class="qubie">'.$departures_address['departure_full_address'] .'</span>');?></h2>
		  <p><?php echo db_to_html($tips_msn);?></p>
		</div>
		<?php
		if($departures_address['map_path']!=''){
		?>
		<a href="<?php echo $departures_address['map_path']?>" target="_blank" class="showMap"><?php echo db_to_html('查看地图');?></a>
		<?php
		}
		?>
	  </li>
	  <?php
	  $start_show_rows_num++;
	 }
	  ?>
	
	</ul>
	<?php
	$total_pages = ceil(sizeof($final_departure_array_result)/$max_show_rows_num); 
	if($total_pages>1){
	?>
	<script type="text/javascript">
	function dep_page_split(page_num, total_pages, max_rows){
		jQuery("li[id^=departure_time_li_]").fadeOut(0);
		var start_rows = ((page_num-1) * max_rows)+1;
		var end_rows = start_rows + max_rows-1;
		for(var i=start_rows; i<=end_rows; i++){
			jQuery("li[id=departure_time_li_"+i+"]").fadeIn(600);
		}
		
		//重新设置上一页和下一页的onclick事件
		if(page_num>1){
			jQuery("#dep_page_pre").unbind("click");
			jQuery("#dep_page_pre").removeAttr("onclick");
			jQuery("#dep_page_pre").bind("click", function(){ dep_page_split((page_num-1),total_pages,max_rows);});
			jQuery("#dep_page_pre").attr("style","display:");
			jQuery("#dep_page_first").attr("style","display:");
		}else{
			jQuery("#dep_page_pre").unbind("click");
			jQuery("#dep_page_pre").removeAttr("onclick");
			jQuery("#dep_page_pre").bind("click", function(){ dep_page_split(1,total_pages,max_rows);});
			jQuery("#dep_page_pre").attr("style","display:none");
			jQuery("#dep_page_first").attr("style","display:none");
		}
		if(page_num==total_pages){
			jQuery("#dep_page_next").unbind("click");
			jQuery("#dep_page_next").removeAttr("onclick");
			jQuery("#dep_page_next").bind("click", function(){ dep_page_split(total_pages,total_pages,max_rows);});
			jQuery("#dep_page_next").attr("style","display:none");
			jQuery("#dep_page_last").attr("style","display:none");
		}else{
			jQuery("#dep_page_next").unbind("click");
			jQuery("#dep_page_next").removeAttr("onclick");
			jQuery("#dep_page_next").bind("click", function(){ dep_page_split((page_num+1),total_pages,max_rows);});
			jQuery("#dep_page_next").attr("style","display:");
			jQuery("#dep_page_last").attr("style","display:");
		}
		//重新设置当前页
		var old_b = jQuery("b[id^=dep_page_]");
		for(var i=0; i<old_b.length; i++){
			var old_page_num = old_b[i].id.replace(/dep_page_/,'');
			jQuery("#dep_page_"+old_page_num).replaceWith('<a id="dep_page_'+old_page_num+'" href="javascript:void(0);" onclick="dep_page_split('+old_page_num+', '+total_pages+', '+max_rows+')">'+old_page_num+'</a>');
		}
		jQuery("#dep_page_"+page_num).replaceWith('<b id="dep_page_'+page_num+'">'+page_num+'</b>'); 
	}

	</script>
	<div class="page">
	  <a id="dep_page_first" href="javascript:void(0);" class="go first" onclick="dep_page_split(1, <?= $total_pages?>, <?= $max_show_rows_num;?>)" style="display:none"></a>
	  <a id="dep_page_pre"href="javascript:void(0);" class="go pre"  onclick="dep_page_split(1, <?= $total_pages?>, <?= $max_show_rows_num;?>)" style="display:none"><?php echo db_to_html('上一页');?></a>
	  <span>&nbsp;
	<?php
	for($i=1; $i<=$total_pages; $i++){
		if($i==1){ echo '|<b id="dep_page_'.$i.'">'.$i.'</b>';}
		else{ echo '|<a id="dep_page_'.$i.'" href="javascript:void(0);" onclick="dep_page_split('.$i.', '.$total_pages.', '.$max_show_rows_num.')">'.$i.'</a>';}
	}
	?>|
	  </span>
	  <a id="dep_page_next" href="javascript:void(0);" class="go next"  onclick="dep_page_split(2, <?= $total_pages?>, <?= $max_show_rows_num;?>)"><?php echo db_to_html('下一页');?></a>
	  <a id="dep_page_last" href="javascript:void(0);" class="go last"  onclick="dep_page_split(<?= $total_pages?>, <?= $total_pages?>, <?= $max_show_rows_num;?>)"></a>
	</div>
	<?php
	}
	?>

<?php
}
//接送时间和地点end
?>