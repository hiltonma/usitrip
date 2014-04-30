<?php require_once('travel_companion_tpl.php');

	$key = "product_html_vegas_show_".intval($products_id);
	$data = MCache::instance()->fetch($key  ,MCache::HOURS); //每小时更新一次
	if($data != '')
		 echo  db_to_html($data) ;
	else{
				ob_start();
?>
	<div class="widget">
	<div class="title titleSmall">
		<b></b><span></span>
		<h3>您可能感兴趣的拉斯维加斯秀</h3>
	</div>
	<ul class="history">
	<?php
	//show sql
	$show_sql = tep_db_query("select p.products_id, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_info_tpl='product_info_vegas_show' and p.products_id!='".(int)$_GET['products_id']."' and p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' Limit 8 ");
	while($show_rows = tep_db_fetch_array($show_sql)){
		$price_text = "";
		$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($show_rows['products_id']);
		if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
			$show_rows['products_price'] = tep_get_tour_price_in_usd($show_rows['products_price'],$tour_agency_opr_currency);
		}
		if (tep_get_products_special_price($show_rows['products_id'])) 
		{
			$price_text.= '<del>' .  $currencies->display_price($show_rows['products_price'], tep_get_tax_rate($show_rows['products_tax_class_id'])) . '</del> <b>' . $currencies->display_price(tep_get_products_special_price($show_rows['products_id']), tep_get_tax_rate($show_rows['products_tax_class_id'])) . '</b>';
		} 
		else 
		{
			$price_text.= '<b>'.$currencies->display_price($show_rows['products_price'], tep_get_tax_rate($show_rows['products_tax_class_id'])).'</b>';
		}
				
		echo '<li><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $show_rows['products_id']).'" title="'.tep_db_output($show_rows['products_name']).'">'.cutword(tep_db_output($show_rows['products_name']),48).'</a>'.$price_text.'</li>';
	}
	?>
	</ul>
	</div>
	<div class="widget">
	<div class="title titleSmall">
		<b></b><span></span>
		<h3>拉斯维加斯短期游</h3>
	</div>
	<ul class="history">
	<?php
	//short las
	$show_sql = tep_db_query("select p.products_id, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_name Like '%拉斯维加斯%' and p.products_id!='".(int)$_GET['products_id']."' and p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and (p.products_durations_type >0 || (p.products_durations_type='0' and p.products_durations='1') ) Limit 11 ");
	while($short_las_rows = tep_db_fetch_array($show_sql)){
		$price_text = "";
		$tour_agency_opr_currency = tep_get_tour_agency_operate_currency($short_las_rows['products_id']);
		if($tour_agency_opr_currency != 'USD' && $tour_agency_opr_currency != ''){
			$short_las_rows['products_price'] = tep_get_tour_price_in_usd($short_las_rows['products_price'],$tour_agency_opr_currency);
		}
		if (tep_get_products_special_price($short_las_rows['products_id'])) 
		{
			$price_text.= '<del>' .  $currencies->display_price($short_las_rows['products_price'], tep_get_tax_rate($short_las_rows['products_tax_class_id'])) . '</del> <b>' . $currencies->display_price(tep_get_products_special_price($short_las_rows['products_id']), tep_get_tax_rate($short_las_rows['products_tax_class_id'])) . '</b>';
		} 
		else 
		{
			$price_text.= '<b>'.$currencies->display_price($short_las_rows['products_price'], tep_get_tax_rate($short_las_rows['products_tax_class_id'])).'</b>';
		}
				
		echo '<li><a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $short_las_rows['products_id']).'" title="'.tep_db_output($short_las_rows['products_name']).'">'.cutword(tep_db_output($short_las_rows['products_name']),48).'</a>'.$price_text.'</li>';
	}
	?>
	</ul>
	
</div>
<?php  
	$data = ob_get_clean();
	MCache::instance()->add($key,$data);
	echo  db_to_html($data);
	}?>

 <div class="widget">
        <div class="title titleSmall">
            <b></b><span></span>
            <h3><?php echo db_to_html('我们的优势');?></h3>
        </div>
        <ul class="superior">
		    <li><?php echo db_to_html('<a href="'.tep_href_link('our-advantages.php').'">高品质旅游行程保证</a><p>实力品牌确保高品质旅游行程</p>');?></li>
		    <li><?php echo db_to_html('<a href="'.tep_href_link('our-advantages.php').'">BBB评级将走四方网评定为A-级</a><p>BBB评级是美国针对商业公司的非营利性评价机构</p>');?></li>
		    <li><?php echo db_to_html('<a href="'.tep_href_link('our-advantages.php').'">7/24小时安全便捷网站服务</a><p>网络直接预定，安全保密，方便快捷，全天候7/24小时为您服务</p>');?></li>
		    <li><?php echo db_to_html('<a href="'.tep_href_link('our-advantages.php').'">1000+精选行程适合各种需求</a><p>精选上千种旅游行程，提供更丰富的旅游资讯和行程选择');?></p></li>
		    <li><?php echo db_to_html('<a href="'.tep_href_link('our-advantages.php').'">高度关注客户感受</a><p>持续优化网站便捷性，不断丰富客户需求的高品质行程');?></p></li>
	    </ul>
    </div>
