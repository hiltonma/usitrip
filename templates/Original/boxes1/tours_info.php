<?php //美国旅游须知?>
<div class="fenlei3"><div class="biaoti"><h3><?php echo db_to_html('旅游须知')?></h3></div>
	<?php
	$info_type_sql = tep_db_query('SELECT * FROM `usa_tours_info_type` ');
	$info_type_rows = tep_db_fetch_array($info_type_sql);
	$limit = '3';
	if(CHARSET=='big5'){
		$limit = '6';
	}
	$start_num = 0;
	do{
		if($start_num>0 && CHARSET=='big5'){ $limit--;}
		$info_sql = tep_db_query('SELECT * FROM `usa_tours_info` uti, `usa_tours_info_to_type` utt WHERE  uti.usa_tours_info_id=utt.usa_tours_info_id AND utt.usa_tours_info_type_id="'.(int)$info_type_rows['usa_tours_info_type_id'].'" Group By utt.usa_tours_info_id limit '.$limit);
		$start_num++;
		
	?>
	<div class="content4"><h3><a href="<?php echo tep_href_link('usa-tours-info.php','usa_tours_info_type_id='.(int)$info_type_rows['usa_tours_info_type_id'])?>" class="lanzi3"><?php echo db_to_html($info_type_rows['usa_tours_info_type_name'])?></a></h3> <div class="more2">
				         <a href="<?php echo tep_href_link('usa-tours-info.php','usa_tours_info_type_id='.(int)$info_type_rows['usa_tours_info_type_id'])?>" class="ff_a">+ <?php echo db_to_html('更多')?></a>
			        </div>
	  <div class="fenlei_left2"><div class="middle_img2"><a href="<?php echo tep_href_link('usa-tours-info.php','usa_tours_info_type_id='.(int)$info_type_rows['usa_tours_info_type_id'])?>"><img src="image/<?php echo db_to_html($info_type_rows['usa_tours_info_type_image'])?>" width="247" height="52" border="0" /></a></div>
	    <div class="clear"></div>
	 </div>
	 <div class="fenlei_list5"> 
	 <ul>
	 	<?php while($info_rows = tep_db_fetch_array($info_sql)){?>
	 	<li>- <a href="<?php echo tep_href_link('usa-tours-info.php','usa_tours_info_id='.(int)$info_rows['usa_tours_info_id'])?>" class="ff_a"><?php echo db_to_html(tep_db_output($info_rows['usa_tours_info_title']))?></a></li>
		<?php }?>

	 </ul>
	 </div>
	 <div class="clear"></div>
    </div>
	<?php
	}while($info_type_rows = tep_db_fetch_array($info_type_sql));
	?>
    
    <div class="clear"></div> 
   </div>
<?php //美国旅游须知end?>
