<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
<div class="leftside">
	<div class="left_bg2">
		<h1><?php	echo db_to_html($info_row['info_title']);	?></h1>
		  <div class="text_gjz" style="margin-top:0px; border-top:0px;"><span><?php echo db_to_html('文章关键字：')?></span><?php	echo db_to_html($info_row['info_keyword']);	?></div>  
			<div>
			<?php
			echo db_to_html($info_row['description']);
			?>
			</div>
		
		<div class="top"><a href="javascript:scroll(0,0);" target="_self" ><?php echo db_to_html('回顶部')?></a></div>
		<div class="chanpin_xg"><h4><?php echo db_to_html('相关产品')?></h4>
		<ul>
		
		<?php
		//根据关键字或类别选择相关产品(随机显示)
		$in_id = '';
		$keyword_array = explode('|',$info_row['info_keyword']);
		//print_r($keyword_array);
		//根据关键字来搜索产品
		$where_str = '';
		$where_num = 0;
		for($i=0; $i<count($keyword_array); $i++){
			if(tep_not_null(trim($keyword_array[$i]))){
				//$where_str .= 'OR (pd.products_name Like Binary ("%'.trim($keyword_array[$i]).'%") OR pd.products_description Like Binary ("%'.trim($keyword_array[$i]).'%") )  ';
				$where_str .= "OR (pd.products_name Like Binary  '%".trim($keyword_array[$i])."%'  )  ";
				$where_num++;
			}
		}
		
		$where_str = preg_replace('/^OR/',' ',$where_str);
		//echo $where_str;
		
		if((int)$where_num){
			$sql = tep_db_query('SELECT p.products_id FROM `products` p, `products_description` pd WHERE p.products_status="1" AND p.products_id = pd.products_id AND '.$where_str.' GROUP BY pd.products_name ORDER BY rand() limit 10');
			//echo ('SELECT p.products_id FROM `products` p, `products_description` pd WHERE p.products_status="1" AND p.products_id = pd.products_id AND '.$where_str.' GROUP BY pd.products_name ORDER BY rand() limit 10');
			while($rows = tep_db_fetch_array($sql)){
				if((int)$rows['products_id']){
					$in_id.=$rows['products_id'].',';
					
				}
			}
		}
		
		
		//echo $in_id;
		$in_id_array = preg_replace('/,$/','', explode(',',$in_id));
		if((int)count($in_id_array)<6){

			switch($info_row['info_type']){
				case '美国西海岸景点介绍': $in_id .= '113,130,134,354,411,370,110,382,122,131'; break;
				case '美国东海岸景点介绍': $in_id .= '147,149,152,143,307,139,313,343,319,216'; break;
				case '夏威夷景点介绍': $in_id .= '161,162,163,164,156,157,159,160,369'; break;
				case '加拿大景点介绍': $in_id .= '150,153,348,316,320,146,148,318,349'; break;
			}
		}
		$in_id = preg_replace('/,$/','',$in_id);
		//echo $in_id;
		
		$prod_sql = tep_db_query('SELECT p.products_id, p.products_model,p.products_price,p.products_tax_class_id, pd.products_name FROM `products` p, `products_description` pd WHERE p.products_id in('.$in_id.') AND p.products_id = pd.products_id AND  p.products_status ="1" Group By p.products_id ORDER BY rand() limit 5');
		//echo ('SELECT p.products_id, p.products_model,p.products_price,p.products_tax_class_id, pd.products_name FROM `products` p, `products_description` pd WHERE p.products_id in('.$in_id.') AND p.products_id = pd.products_id AND  p.products_status ="1" Group By p.products_id ORDER BY rand() limit 5');
		while($prod_rows = tep_db_fetch_array($prod_sql)){
		?>
			<li><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $prod_rows['products_id']);?>" class="lanzi4" title="<?php echo db_to_html(tep_db_output($prod_rows['products_name']))?>"><?php echo cutword(db_to_html(tep_db_output($prod_rows['products_name'])),60)?></a> <span><?php echo $currencies->display_price($prod_rows['products_price'],tep_get_tax_rate($prod_rows['products_tax_class_id']))?></span></li>
		<?php
		}
		?>
		
		</ul>
		</div>
	</div>
</div>	
	</td>
    <td valign="top">
<?php require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/column_right.php'); ?>	</td>
  </tr>
</table>
