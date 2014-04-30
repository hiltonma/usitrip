<!-- content main body start -->
<div class="jb_grzx_yj_bt">
    <h3><?php echo db_to_html('大家常去的地方')?></h3>
</div>
<?php
    $ord_sql = tep_db_query('SELECT o.customers_id ,o.last_modified, op.* FROM `orders` o, `orders_products` op WHERE o.orders_status = "100006" AND op.orders_id = o.orders_id Group By op.products_id LIMIT 20 ');
    while($ord = tep_db_fetch_array($ord_sql)){
    	$ord_p_name = db_to_html(tep_get_products_name($ord['products_id']));
?>
     <div class="jb_gone_df2 line1">
        <div class="jb_gone_df_l2"><a class="col_2" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $ord['products_id']);?>" title="<?= $ord_p_name?>"><?php echo cutword($ord_p_name,66)?></a></div>
        <div class="jb_gone_df_r2">
            <p><?= substr($ord['last_modified'],0,19)?></p>
        </div>
    </div>
<?php
	}
?>
