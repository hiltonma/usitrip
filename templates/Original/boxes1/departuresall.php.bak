<?php
//列出所有出发城市 start
$dep_city_ids = array();
$dep_city_ids[] = array('ids'=>'1,2,3,10,23,25,37,279,219,43,271,44,257', 'area'=>'美西','pathid'=>'24');
$dep_city_ids[] = array('ids'=>'66,67,65,71,93', 'area'=>'美东','pathid'=>'25');
$dep_city_ids[] = array('ids'=>'124,129,270', 'area'=>'加拿大','pathid'=>'54');
$dep_city_ids[] = array('ids'=>'331,319,325,339,340,311', 'area'=>'欧洲','pathid'=>'157');
?>
<div class="title titleSmall">
  <b></b><span></span>
  <h3><?php echo db_to_html('更多出发城市');?></h3>
</div>
<ul class="moreroute">
  <?php
  for($i=0; $i<count($dep_city_ids); $i++){
	  	if($cPathOnly == $dep_city_ids[$i]['pathid'])continue;
  ?>
  <li>
  <h3><?php echo db_to_html($dep_city_ids[$i]['area'])?></h3>
  <?php
      $city_sql = tep_db_query('select * from '.TABLE_TOUR_CITY.' where city_id in ('.$dep_city_ids[$i]['ids'].') AND is_attractions !="1" AND departure_city_status ="1" AND city_code!="" ORDER BY city_code ASC ');
if(!is_array($categories_more_city_link)){
	$categories_more_city_link=tep_href_link(FILENAME_DEFAULT,'cPath='.$cPathOnly.'&mnu='.$cat_mnu_sel);
	$categories_more_city_link = explode('?',$categories_more_city_link);
}
      while($row = tep_db_fetch_array($city_sql)){
          $href = $categories_more_city_link[0].'fc-'.$row['city_id'].'/'.($categories_more_city_link[1]?'?'.$categories_more_city_link[1]:'');
          $name = db_to_html(preg_replace('/ .+/','',tep_get_city_name($row['city_id'])));
          echo '<a href="'.$href.'">'.$name.'</a>';
      }
  ?></li>
  <?php
  }
  ?>
</ul>