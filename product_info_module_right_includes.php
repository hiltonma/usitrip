<?php if($product_info['products_other_description'] != '') {
//·ÑÓÃ°üÀ¨
?>
<h3 class="bankuo"><?php echo TEXT_HEADING_PACKAGE_INCLUDES; ?></h3>
<div class="description">
<?php  echo stripslashes2(db_to_html($product_info['products_other_description'])); ?>
</div>
<?php } ?>
