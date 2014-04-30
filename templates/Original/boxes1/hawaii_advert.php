<?php
if($cPathOnly=="33"){	//夏威夷 顶部广告 start
	//选两个夏威夷的团做为广告
	$hawaii_b_prod_ids = array();
	$hawaii_b_prod_ids[] = array('id'=>'370',
								'name'=>'三日西峡谷玻璃桥,拉斯维加斯两晚住大道最高地标',
								'text'=>'参观享有“世界娱乐之都”美誉的拉斯维加斯，拥有工业世界七大奇观之一美誉的胡佛大坝也不容错过');

	$hawaii_b_prod_ids[] = array('id'=>'1036',
								'name'=>'四日夏威夷檀香山经典之旅 B (品质团)',
								'text'=>'有三十年操作经验的资深夏威夷地接，为您提供极具品质的浪漫之旅，独家加长型礼宾车机场迎宾...');
	
	
?>
	<div class="hawaii_pro">
	<?php
	//大广告图
	$big_banner_hawaii = array('link'=> tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=2158'), 'img'=>'image/hawaii_banner_2.jpg');
	?>
	<a href="<?= $big_banner_hawaii['link']?>"><img width="580" height="220" src="<?= $big_banner_hawaii['img']?>" class="hawaii_banner"></a> 
	
		<div class="hawaii_two_pro" style="margin:0px;">
            <div class="two_pro_t_l"></div>
            <div class="two_pro_t_r"></div>
            <div class="two_pro_b_l"></div>
            <div class="two_pro_b_r"></div>
		<?php
			for($i=0; $i<count($hawaii_b_prod_ids); $i++){
		?>
		<p><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $hawaii_b_prod_ids[$i]['id']);?>"><?php echo db_to_html($hawaii_b_prod_ids[$i]['name'])?></a><br>
		<?php echo db_to_html($hawaii_b_prod_ids[$i]['text'])?>
		</p>
		<?php
			}
		?>
		
		</div>
	</div>
<?php	
}
//夏威夷 顶部广告end
?>