<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
<div class="leftside"> 
    
	
	<div style="background: transparent url(image/renmen_banner.jpg) no-repeat scroll left top; float: left; width: 662px; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;" title="">
		<div class="r_img_tt">
		<div class="biaoti6"><h6><?php echo db_to_html('热门景点')?></h6></div>
		<div class="r_img_tt_1">
					  </div>
		</div>
	</div>
	
    <div class="left_bg"><h1><?php echo db_to_html('热门景点')?></h1>
      <div class="introduction"><h4><?php echo db_to_html('热门景点概况')?></h4> 
        <div class="jingpin_left2"><div class="middle_img4"><img src="image/pic_1.jpg" class="img_bian_b3" /></div>
<p><?php echo db_to_html('走四方网为您搜罗北美热门景点，从美国西海岸黄石国家公园，到东海岸自由女神像，由夏威夷欧胡岛，至加拿大千岛湖，我们一一为您描述……')?></p>
<div class="clear"></div>
</div></div>
     
      <div class="renmen_map"><h4><?php echo db_to_html('热门景点地图')?></h4>
      <div class="jingpin_left2"><div class="middle_map"><a href="<?php echo tep_href_link('hot-tours-map.php')?>"><img src="image/map_jingdian.gif" /></a></div>

<div class="clear"></div>
</div></div>
     
<?php //美国西海岸?>	   
     <div class="product_list3"><div class="middle_img4"><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=24'); ?>"><img src="image/city2.jpg" /></a></div>
       
	   <div class="product_content"><h5><?php echo db_to_html('美国西海岸景点')?></h5>
        
		<ul  class="remen_list">
<?php
$info1_sql = tep_db_query('SELECT information_id,info_title FROM `information` WHERE info_type ="美国西海岸景点介绍" AND visible=1 ');
$info1_rows = tep_db_fetch_array($info1_sql);
$j=0;
do{
?>	  
        
		
		<?php if($j%4==0 && $j>0){echo '</ul><ul  class="remen_list">';}?>
		<li><a title="<?php echo db_to_html(tep_db_output($info1_rows['info_title'])) ?>" href="<?php echo tep_href_link('hot-tours-content.php','information_id='.(int)$info1_rows['information_id'])?>" class="ff_a"><?php echo cutword(db_to_html(tep_db_output($info1_rows['info_title'])),18);?></a></li>
		
 <?php
$j++;
 }while($info1_rows = tep_db_fetch_array($info1_sql));
 ?>
		</ul>
 
 
 <div class="yi_chanpin">
 <?php 
 $end_prod_sql = tep_db_query('SELECT pd.products_name,pd.products_id, p.products_model, p.products_price, p.products_tax_class_id FROM `products` p,  `products_description` pd,`products_to_categories` ptc WHERE p.products_id = ptc.products_id AND p.products_id = pd.products_id AND ptc.categories_id="133" AND p.products_status="1" ORDER BY `products_id` DESC limit 1;');
 $end_prod_row = tep_db_fetch_array($end_prod_sql);
 if((int)$end_prod_row['products_id']){
 ?>
 <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $end_prod_row['products_id']);?>" class="cheng"><?php echo db_to_html($end_prod_row['products_name']).'&nbsp;'.$currencies->display_price($end_prod_row['products_price'],tep_get_tax_rate($end_prod_row['products_tax_class_id']))  ?> [<?php echo $end_prod_row['products_model']  ?>] </a>
 <?php
 }?>
 </div>
        <div class="clear"></div>
       </div>
	   
</div>
<?php //美国西海岸end?>

<?php //美国东海岸?>
<div class="product_list4"><div class="middle_img4"><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=25'); ?>"><img src="image/city2_dong.jpg" /></a></div>
      <div class="product_content">
        <h5><?php echo db_to_html('美国东海岸景点')?></h5>
        
        <ul class="remen_list">
<?php
$info2_sql = tep_db_query('SELECT information_id,info_title FROM `information` WHERE info_type ="美国东海岸景点介绍" AND visible=1 ');
$info2_rows = tep_db_fetch_array($info2_sql);
$j=0;
do{
?>	  
		<?php if($j%4==0 && $j>0){echo '</ul><ul  class="remen_list">';}?>
		<li><a title="<?php echo db_to_html(tep_db_output($info2_rows['info_title'])) ?>" href="<?php echo tep_href_link('hot-tours-content.php','information_id='.(int)$info2_rows['information_id'])?>" class="ff_a"><?php echo cutword(db_to_html(tep_db_output($info2_rows['info_title'])),18);?></a></li>
		
 <?php
$j++;
 }while($info2_rows = tep_db_fetch_array($info2_sql));
 ?>
		</ul>

 
<div class="yi_chanpin">
 <?php 
 $end_prod_sql = tep_db_query('SELECT pd.products_name,pd.products_id, p.products_model, p.products_price, p.products_tax_class_id FROM `products` p,  `products_description` pd,`products_to_categories` ptc WHERE p.products_id = ptc.products_id AND p.products_id = pd.products_id AND ptc.categories_id="52" AND p.products_status="1" ORDER BY `products_id` DESC limit 1;');
 $end_prod_row = tep_db_fetch_array($end_prod_sql);
 if((int)$end_prod_row['products_id']){
 ?>
 <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $end_prod_row['products_id']);?>" class="cheng"><?php echo db_to_html($end_prod_row['products_name']).'&nbsp;'.$currencies->display_price($end_prod_row['products_price'],tep_get_tax_rate($end_prod_row['products_tax_class_id']))  ?> [<?php echo $end_prod_row['products_model']  ?>] </a>

<?php
}
?>

</div>
<div class="clear"></div>
</div>
</div>
<?php //美国东海岸end?>

<?php //夏威夷?>	   
     <div class="product_list3"><div class="middle_img4"><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=33'); ?>"><img src="image/city2_xiaweiyi.jpg" /></a></div>
       
	   <div class="product_content"><h5><?php echo db_to_html('夏威夷景点')?></h5>
        
		<ul  class="remen_list">
<?php
$info3_sql = tep_db_query('SELECT information_id,info_title FROM `information` WHERE info_type ="夏威夷景点介绍" AND visible=1 ');
$info3_rows = tep_db_fetch_array($info3_sql);
$j=0;
do{
?>	  
        
		
		<?php if($j%4==0 && $j>0){echo '</ul><ul  class="remen_list">';}?>
		<li><a title="<?php echo db_to_html(tep_db_output($info3_rows['info_title'])) ?>" href="<?php echo tep_href_link('hot-tours-content.php','information_id='.(int)$info3_rows['information_id'])?>" class="ff_a"><?php echo cutword(db_to_html(tep_db_output($info3_rows['info_title'])),18);?></a></li>
		
 <?php
$j++;
 }while($info3_rows = tep_db_fetch_array($info3_sql));
 ?>
		</ul>
 
 
 <div class="yi_chanpin">
 <?php 
 $end_prod_sql = tep_db_query('SELECT pd.products_name,pd.products_id, p.products_model, p.products_price, p.products_tax_class_id FROM `products` p,  `products_description` pd,`products_to_categories` ptc WHERE p.products_id = ptc.products_id AND p.products_id = pd.products_id AND ptc.categories_id="86" AND p.products_status="1" ORDER BY `products_id` DESC limit 1;');
 $end_prod_row = tep_db_fetch_array($end_prod_sql);
 if((int)$end_prod_row['products_id']){
 ?>
 <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $end_prod_row['products_id']);?>" class="cheng"><?php echo db_to_html($end_prod_row['products_name']).'&nbsp;'.$currencies->display_price($end_prod_row['products_price'],tep_get_tax_rate($end_prod_row['products_tax_class_id']))  ?> [<?php echo $end_prod_row['products_model']  ?>] </a>

<?php
}
?>
 
 </div>
        <div class="clear"></div>
       </div>
	   
</div>
<?php //夏威夷end?>



<?php //加拿大?>
<div class="product_list4"><div class="middle_img4"><a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=54'); ?>"><img src="image/city2_jianada.jpg" /></a></div>
      <div class="product_content">
        <h5><?php echo db_to_html('加拿大景点')?></h5>
        
        <ul class="remen_list">
<?php
$info4_sql = tep_db_query('SELECT information_id,info_title FROM `information` WHERE info_type ="加拿大景点介绍" AND visible=1 ');
$info4_rows = tep_db_fetch_array($info4_sql);
$j=0;
do{
?>	  
		<?php if($j%4==0 && $j>0){echo '</ul><ul  class="remen_list">';}?>
		<li><a title="<?php echo db_to_html(tep_db_output($info4_rows['info_title'])) ?>" href="<?php echo tep_href_link('hot-tours-content.php','information_id='.(int)$info4_rows['information_id'])?>" class="ff_a"><?php echo cutword(db_to_html(tep_db_output($info4_rows['info_title'])),18);?></a></li>
		
 <?php
$j++;
 }while($info4_rows = tep_db_fetch_array($info4_sql));
 ?>
		</ul>

 
<div class="yi_chanpin">
 <?php 
 $end_prod_sql = tep_db_query('SELECT pd.products_name,pd.products_id, p.products_model, p.products_price, p.products_tax_class_id FROM `products` p,  `products_description` pd,`products_to_categories` ptc WHERE p.products_id = ptc.products_id AND p.products_id = pd.products_id AND ptc.categories_id="147" AND p.products_status="1" ORDER BY `products_id` DESC limit 1;');
 $end_prod_row = tep_db_fetch_array($end_prod_sql);
 if((int)$end_prod_row['products_id']){
 ?>
 <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $end_prod_row['products_id']);?>" class="cheng"><?php echo db_to_html($end_prod_row['products_name']).'&nbsp;'.$currencies->display_price($end_prod_row['products_price'],tep_get_tax_rate($end_prod_row['products_tax_class_id']))  ?> [<?php echo $end_prod_row['products_model']  ?>] </a>
 
 <?php
 }
 ?>

</div>
<div class="clear"></div>
</div>
</div>
<?php //加拿大end?>

</div>
        </div>	
	</td>
    <td valign="top">
<?php require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/column_right.php'); ?>	</td>
  </tr>
</table>