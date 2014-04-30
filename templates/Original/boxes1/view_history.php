<?php
//for hot-tours || panorama-tours.php
if(basename($PHP_SELF) == 'hot-tours.php' || basename($PHP_SELF) == 'panorama-tours.php' ||
  $content=='index_nested' || $content=='index_products'){

	if((int)count($_COOKIE['view_history'])){

		// 取得列的列表
		foreach ($_COOKIE['view_history'] as $key => $value) {
			$products_id[$key]  = $value['products_id'];
			$date_time[$key] = $value['date_time'];
		}
		// 将资料根据 date_time 降幂排列，根据 products_id 升幂排列
		// 把 $_COOKIE['view_history'] 作为最后一个参数，以通用键排序
		array_multisort($date_time, SORT_DESC, $products_id, SORT_ASC, $_COOKIE['view_history']);
?>
	<?php //您浏览过的团?>
	<div class="fenlei5">
		<div class="biaoti3x">
			<h3><?php echo db_to_html('您浏览过的团')?></h3>
		</div>
		<div class="content6">
			<ul class="list3">
			
			<?php
			$tmp_var = 0;
			foreach ($_COOKIE['view_history'] as $key => $value){
				$tmp_var++;
				if($tmp_var<7){
					$sql = tep_db_query("select p.products_id, pd.products_name FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$value['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
					$row = tep_db_fetch_array($sql);
					if((int)$row['products_id']){
						echo db_to_html('<li>- <a title="'.tep_db_output($row['products_name']).'" href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row['products_id']).'" class="text">'.cutword(tep_db_output($row['products_name']),34).'</a> </li>');
					}
				}
			}
			?>
			
			</ul>
			
			<br />

		<div class="clear"></div>
		</div>
	</div>
	<?php //您浏览过的团end?>
<?php
	}
}
?>