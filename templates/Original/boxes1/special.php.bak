<?php
//for hot-tours || panorama-tours.php
$new_sevsion = true;
if(basename($PHP_SELF) == 'hot-tours.php' || basename($PHP_SELF) == 'panorama-tours.php' || $new_sevsion ==true){
	if(tep_not_null($cPath)){
		$where_cpath = " and ptc.categories_id in (".str_replace('_',',',$cPath).") ";
		$c_title = '特价推荐'; //'本类特价';
	}else{
		$where_cpath = "";
		$c_title = '特价推荐';
	}
	$specials_sql = tep_db_query("select s.specials_new_products_price, p.products_id,pd.products_name, p.products_price,p.products_tax_class_id, p.products_image from ".TABLE_SPECIALS." as s, ".TABLE_PRODUCTS." as p, ".TABLE_PRODUCTS_DESCRIPTION." as pd, ".TABLE_PRODUCTS_TO_CATEGORIES." ptc where s.products_id = p.products_id AND p.products_id = pd.products_id and pd.language_id='".(int) $languages_id."' and p.products_status=1 and s.status=1 and ptc.products_id = s.products_id ".$where_cpath." Group BY p.products_id limit 20 ");
	$specials_rows = tep_db_fetch_array($specials_sql);
	if((int)$specials_rows['products_id']){
	?>
    <div class="special border_1 margin_b10">  
	<div class="title titleSmall">
       <h3><?php echo db_to_html($c_title)?></h3>
    </div>
    <div>
       <ul class="specials">
	<?php
	do{
		$specials_rows['productsImage'] = (stripos($specials_rows['products_image'],'http://') === false ? 'images/':'') . $specials_rows['products_image'];
	?>
    	<li><div class="pic"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials_rows['products_id']);?>" title="<?php echo db_to_html(tep_db_output($specials_rows['products_name']))?>"><img width="90" src="<?php echo get_thumbnails_fast($specials_rows['productsImage']);?>" alt="<?php echo db_to_html(tep_db_output($specials_rows['products_name']))?>" /></a></div>
        <div class="info">
          <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials_rows['products_id']);?>" title="<?php echo db_to_html(tep_db_output($specials_rows['products_name']))?>"><?php echo cutword(db_to_html(tep_db_output($specials_rows['products_name'])),36,'')?></a><del><?php echo str_replace('.00','',$currencies->display_price($specials_rows['products_price'], tep_get_tax_rate($specials_rows['products_tax_class_id'])))?></del>
          <span><?php echo str_replace('.00','',db_to_html('省'.$currencies->display_price(($specials_rows['products_price']-$specials_rows['specials_new_products_price']),tep_get_tax_rate($specials_rows['products_tax_class_id']))))?></span><b><?php echo str_replace('.00','',$currencies->display_price($specials_rows['specials_new_products_price'],tep_get_tax_rate($specials_rows['products_tax_class_id'])))?></b>
          </div>
        </li>
	<?php }while($specials_rows = tep_db_fetch_array($specials_sql));?>
	</ul>
    <div class="del_float"></div>
    </div>
    </div>

<?php 
	}
}?>