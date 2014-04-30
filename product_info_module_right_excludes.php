<?php 
if($product_info['products_package_excludes'] != '') {
//费用不包括
?>
	<h3 class="bubaokuo"><?php echo TEXT_HEADING_PACKAGE_EXCLUDES;  ?></h3>
	<div class="description">
	<?php  echo stripslashes2(db_to_html($product_info['products_package_excludes'])); ?>
	</div>
<?php } ?>												
