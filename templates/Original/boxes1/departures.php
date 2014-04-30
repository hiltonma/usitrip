<?php
if(tep_not_null($top_cate_ids)){
	$left_datanum=ceil(count($categories_more_city)/2);
?>
<dl class="route">
<dt><?php echo HEADING_DEPARTURE_CITIES; //按出发城市查看?></dt>
<dd>
<?php
if(!is_array($categories_more_city_link)){
	$categories_more_city_link=tep_href_link(FILENAME_DEFAULT,'cPath='.$cPathOnly.'&mnu='.$cat_mnu_sel);
	$categories_more_city_link = explode('?',$categories_more_city_link);
}
foreach($categories_more_city as $key=>$row){
	if($key==$left_datanum)echo '</dd><dd class="right">';
	$link_class = ($row['city_id'] == $fc)?"selected":"";
	$href = $categories_more_city_link[0].'fc-'.$row['city_id'].'/'.($categories_more_city_link[1]?'?'.$categories_more_city_link[1]:'');
	$name = db_to_html(preg_replace('/ .+/','',$row['city']));
	echo '<a href="'.$href.'" class="'.$link_class.'" val="'.$row['city_id'].'">'.$name.'</a>';
}
?></dd>
</dl>
<?php
}
?>

<?php if($current_category_id=="32"){?>
<div><a href="<?php echo tep_href_link(FILENAME_DEFAULT,'cPath=32&mnu=show');?>"><img src="image/las_vegas_show_banner.gif" /></a></div>		
<?php }?>