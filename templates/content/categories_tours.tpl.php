<table border="0" width="90%" cellspacing="0" cellpadding="2" align="center">

<?php $categories_tours_sql = "select pd.products_name, p.products_image, p.products_weight, p.products_durations, p.departure_city_id, pd.products_small_description , p.products_is_regular_tour, p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from products_description pd, products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id, products_to_categories p2c left join specials s on p.products_id = s.products_id where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '1' and p2c.categories_id = '".$cId."' order by p.products_durations , pd.products_name ";
$categories_tours_query = tep_db_query($categories_tours_sql);
while($categories_tours = tep_db_fetch_array($categories_tours_query)){
		?>
		<tr><td class='main'>
		<a href="<?=tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $categories_tours['products_id']);?>" class="textBlack"><?=$categories_tours['products_name'];?></a>
	</td></tr>
		<?php 	}
?>
</table>
