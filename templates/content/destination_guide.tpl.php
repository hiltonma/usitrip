
  <!--<div class="Bread"><h1 class="product-title">目的地指南 |</h1><p>首页 > <span>目的地指南</span></p>
  </div>
  -->
<div id="mudidi">
  <div class="map-usa">
	<?php
	//地图
	if(preg_match('/^\<iframe/',$rows['map_image'])){
		$map_image_code = $rows['map_image'];
	}elseif(preg_match('/^http:\/\//',$rows['map_image'])){
		$map_image_code = '<iframe width="746" height="425" frameborder="0" scrolling="no" src="';
		$map_image_code .= $rows['map_image'];
		$map_image_code .= '" marginwidth="0" marginheight="0" ></iframe>';
	}elseif(tep_not_null($rows['map_image'])){
		$map_image_code = '<img src="images/destination_guide/'.$rows['map_image'].'" style="border:1px solid #52B9F4;" />';
	}
	echo $map_image_code;
	?>
  </div>
  <div class="mudidi-list-us">
    <div style="padding-left:10px;">
	<?php //列出子级目录start?>
	<?php
	
	$child_sql = tep_db_query('SELECT dg_categories_id, dg_categories_name FROM `destination_guide_categories`  WHERE parent_id ="'.(int)$rows['dg_categories_id'].'" AND dg_categories_state =1 ORDER BY sort_order ASC, dg_categories_id DESC ');
	$child_loop = 0;
	while($child_rows = tep_db_fetch_array($child_sql)){
		$child_loop++;
		$h2_style = '';
		if($child_loop>1){
			$h2_style = 'style="margin-top:5px;"';
		}
	?>
	  <h2 <?php echo $h2_style?>><?php echo db_to_html(tep_db_output($child_rows['dg_categories_name']));?></h2> 
      
		<ul style="width:100%">
			<li>
			<?php
			//再列子级目录start
			$child_1_sql = tep_db_query('SELECT dg_categories_id, dg_categories_name FROM `destination_guide_categories`  WHERE parent_id ="'.(int)$child_rows['dg_categories_id'].'" AND dg_categories_state =1 ORDER BY sort_order ASC, dg_categories_id DESC ');
			$str_length = 0;
			$row_length = 56;
			while($child_1_rows = tep_db_fetch_array($child_1_sql)){
				$str_length += strlen($child_1_rows['dg_categories_name']) + 1 ;
				if($str_length > $row_length){
					$str_length = strlen($child_1_rows['dg_categories_name']) + 1 ;
					echo '<br>';
				}
			?>
			<a href="<?php echo tep_href_link('destination_guide_details.php','dg_categories_id='.(int)$child_1_rows['dg_categories_id'])?>" class="lanzi4"><?php echo db_to_html(tep_db_output($child_1_rows['dg_categories_name']));?></a>
			<?php
			}
			//再列子级目录end
			?>
			</li>
		</ul>
    <?php
	}
	?> 
	<?php //列出子级目录end?>
	
	</div>
    
	<div style="padding-left:45px;">
     <h2><?php echo db_to_html('热门景点')?></h2>
      
	<ul>	
    <?php
	//列出热门景点24个 strart
	$hot_dg_categories_ids = explode(',',trim($rows['hot_dg_categories_ids']));
	if((int)count($hot_dg_categories_ids)){
		$d_ids = array();
		foreach((array)$hot_dg_categories_ids as $key => $val){
			if((int)$val){
				$d_ids[] = (int)$val;
			}
		}
		$d_ids_str = implode(',',$d_ids);
		if(tep_not_null($d_ids_str)){
			$hot_cate_sql = tep_db_query('SELECT dg_categories_id, dg_categories_name FROM `destination_guide_categories` WHERE dg_categories_id in('.$d_ids_str.') AND dg_categories_state ORDER BY sort_order ASC, dg_categories_id DESC Limit 24 ');
			$hot_loop = 0;
			while($hot_cate_rows = tep_db_fetch_array($hot_cate_sql)){
				if($hot_loop%12==0 && (int)$hot_loop){
					echo '</ul><ul style=" padding-left:80px;">';
				}
				$hot_loop++;
	?>   
		<li><a href="<?php echo tep_href_link('destination_guide_details.php','dg_categories_id='.(int)$hot_cate_rows['dg_categories_id'])?>" class="lanzi4"><?php echo db_to_html(tep_db_output($hot_cate_rows['dg_categories_name']));?></a></li>
	<?php
			}
		}
	}
	//列出热门景点24个 end
	
	?>	
	</ul>
	  
    </div>
  </div>
	
</div>