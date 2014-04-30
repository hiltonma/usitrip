<table width="99%"  border="0" cellspacing="0" cellpadding="0">
				
<?php
if ($messageStack->size('account') > 0) {
?>
<tr>
<td class="main">
	<table border="0" width="100%" align="center" cellspacing="0" cellpadding="0">
	  <tr>
		<td><?php echo $messageStack->output('account'); ?></td>
	  </tr>
	  <tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	  </tr>
	  <tr>
		<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	  </tr>
	</table>
</td>
</tr>
<?php
}
?>

	  <tr>
		<td>
		
<div class="bg3_right">

<div class="geren_content_22"><p><h3><?php printf(WELCOME_TO_TOURS,db_to_html($first_or_last_name)) ?>
<span class="has_point_msn" style="margin-left:200px;">
<?php
$has_point = tep_get_shopping_points($customer_id);
if ($has_point > 0) {
	echo sprintf(MY_POINTS_CURRENT_BALANCE, number_format($has_point,POINTS_DECIMAL_PLACES),$currencies->format(tep_calc_shopping_pvalue($has_point)));
	echo db_to_html('<a href="'.tep_href_link('my_points.php','','SSL').'" class="sp1">[查看明细]</a>'); 
}
?>
</span>
</h3></p>


</div>


<div class="clear"></div>

<?php //控制面板?>
<div id="TabPanelAccount">
  <ul class="zhanghu_fuzhu_nav">
	<li class="lanzi4" tabindex="0"><?php echo db_to_html('您可能感兴趣的产品')?></li>
	<li class="lanzi4" tabindex="0"><?php echo db_to_html('最热销产品')?></li>
	<li class="lanzi4" tabindex="0"><?php echo db_to_html('特价产品')?></li>
	<li class="lanzi4" tabindex="0" style="border-right:0px;"><?php echo db_to_html('您浏览过的产品')?></li>
  </ul>
  
  <div>
	<div class="chanpin_xg2">
		<ul>
			<?php //您可能感兴趣的产品
			//暂时以我们推荐的产品为基础
			$products_sql = tep_db_query("select op.products_id, p.agency_id, p.products_price, p.products_tax_class_id, ta.agency_name, op.products_model, op.products_name, sum(op.products_quantity) as quantitysum, sum(op.final_price*op.products_quantity)as gross FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p, ".TABLE_TRAVEL_AGENCY." as ta WHERE o.orders_id = op.orders_id and op.products_id = p.products_id and p.agency_id = ta.agency_id  group by op.products_id order by quantitysum DESC, op.products_model limit 10,5 ");
			while($products_rows = tep_db_fetch_array($products_sql)){
			?>
			
			<li><span><?php echo $currencies->display_price($products_rows['products_price'],tep_get_tax_rate($products_rows['products_tax_class_id']))?></span><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_rows['products_id']);?>" class="lanzi4"><?php echo cutword(db_to_html(tep_db_output($products_rows['products_name'])),50) ?></a></li>
			
			<?php }?>

		</ul>
	</div>
   
	<div class="chanpin_xg2">
		<ul>
			<?php //最热销产品
			$products_sql = tep_db_query("select op.products_id, p.agency_id, p.products_price, p.products_tax_class_id, ta.agency_name, op.products_model, op.products_name, sum(op.products_quantity) as quantitysum, sum(op.final_price*op.products_quantity)as gross FROM " . TABLE_ORDERS . " as o, " . TABLE_ORDERS_PRODUCTS . " AS op, " . TABLE_PRODUCTS . " as p, ".TABLE_TRAVEL_AGENCY." as ta WHERE o.orders_id = op.orders_id and op.products_id = p.products_id and p.agency_id = ta.agency_id  group by op.products_id order by quantitysum DESC, op.products_model limit 5 ");
			while($products_rows = tep_db_fetch_array($products_sql)){
			?>
			
			<li><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_rows['products_id']);?>" class="lanzi4"><?php echo cutword(db_to_html(tep_db_output($products_rows['products_name'])),50) ?></a></li>
			
			<?php }?>

		</ul>
	</div>
	
	<div class="chanpin_xg2">
		<ul>
			<?php //特价产品
			$specials_sql = tep_db_query("select s.specials_new_products_price, p.products_id,pd.products_name, p.products_price,p.products_tax_class_id from `specials` as s, ".TABLE_PRODUCTS." as p, ".TABLE_PRODUCTS_DESCRIPTION." as pd where s.products_id = p.products_id AND p.products_id = pd.products_id and pd.language_id='".(int) $languages_id."' and p.products_status=1 limit 5 ");

			while($specials_rows = tep_db_fetch_array($specials_sql)){
			?>
			<li><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials_rows['products_id']);?>" title="<?php echo db_to_html(tep_db_output($specials_rows['products_name']))?>" class="text"><?php echo cutword(db_to_html(tep_db_output($specials_rows['products_name'])),70)?></a>  <span class="jiage" style="color:#BE391A"><?php echo $currencies->display_price($specials_rows['specials_new_products_price'],tep_get_tax_rate($specials_rows['products_tax_class_id']))?>&nbsp;</span>&nbsp;<span class="off_sale2" style="color:#999999"><?php echo $currencies->display_price($specials_rows['products_price'], tep_get_tax_rate($specials_rows['products_tax_class_id']))?>&nbsp;</span></li>
			<?php }?>
		</ul>
	</div>
	
	<div class="chanpin_xg2">
		<ul>
	<?php //您浏览过的产品
	if((int)count($_COOKIE['view_history'])){

		// 取得列的列表
		foreach ($_COOKIE['view_history'] as $key => $value) {
			$products_id[$key]  = $value['products_id'];
			$date_time[$key] = $value['date_time'];
		}
		// 将资料根据 date_time 降幂排列，根据 products_id 升幂排列
		// 把 $_COOKIE['view_history'] 作为最后一个参数，以通用键排序
		array_multisort($date_time, SORT_DESC, $products_id, SORT_ASC, $_COOKIE['view_history']);
		
		$tmp_var = 0;
		foreach ($_COOKIE['view_history'] as $key => $value){
			$tmp_var++;
			if($tmp_var<10){
				$sql = tep_db_query("select p.products_id, pd.products_name FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$value['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
				$row = tep_db_fetch_array($sql);
				if((int)$row['products_id']){
					echo db_to_html('<li><a title="'.tep_db_output($row['products_name']).'" href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row['products_id']).'" class="lanzi4">'.cutword(tep_db_output($row['products_name']),70).'</a> </li>');
				}
			}
		}
		
	}
	?>
		</ul>
	</div>
  </div>
</div>

<script type="text/javascript">
<!--
var TabbedPanelsAccount = new Spry.Widget.TabbedPanels("TabPanelAccount");
//-->
</script>
<?php //控制面板end?>


</div>												</td>
	  </tr>
</table>
