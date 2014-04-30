<div class="attention">
<?php
//特别事项 start
if($product_info['products_package_special_notes'] != '') {
	$p_array = array('<li>','</li>','<LI>','</LI>','<Li>','</Li>');
	$r_array = array('<p>&nbsp;&nbsp;','</p>','<p>&nbsp;&nbsp;','</p>','<p>&nbsp;&nbsp;','</p>');
	echo '<h2>'.db_to_html('特别事项').'</h2>';
	echo str_replace($p_array,$r_array,stripslashes2(db_to_html($product_info['products_package_special_notes'])));
}
//特别事项 end
?>
</div>
