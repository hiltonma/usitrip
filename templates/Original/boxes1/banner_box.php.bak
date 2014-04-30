<?php
//只在目录页显示 Box for Catalog
if (isset($banner_name) && $banner_name != '') {

} else {
	$banner_name ='Box for Catalog 265px';
}

if((int)$_GET['cPath'] || true == $search_banner){
	$banner_box = get_banners($banner_name);
	$banner_box_count = count($banner_box);
	if(tep_not_null($banner_box)){
		for($i=0; $i<$banner_box_count; $i++){
			?>
			<div class="productLeftAd margin_b10">
			<?php
			if(tep_not_null($banner_box[$i]['FinalCode'])){
				echo $banner_box[$i]['FinalCode'];
			}else{
				switch($banner_name){
					case 'group_buys_big':?>
					<img src="<?php echo $banner_box[$i]['src']?>" alt="<?php echo $banner_box[$i]['alt']?>" />
					<?php break;
					case 'group_buys_1':
					case 'group_buys_2':
					case 'group_buys_3':
					case 'group_buys_4':
						?><a title="<?php echo $banner_box[$i]['alt']?>" href="<?php echo $banner_box[$i]['directLinks']?>"><img src="<?php echo $banner_box[$i]['src']?>" alt="<?php echo $banner_box[$i]['alt']?>" /></a>
						<?php break;
					default:?>
					<a title="<?php echo $banner_box[$i]['alt']?>" href="<?php echo $banner_box[$i]['links']?>" target="_blank"><img src="<?php echo $banner_box[$i]['src']?>" alt="<?php echo $banner_box[$i]['alt']?>" /></a>
				<?php }?>
		<?php
			}
		?>
</div>
		<?php
		}
	}
}?>