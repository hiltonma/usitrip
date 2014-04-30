<?php
/*谷歌Analytics和电子商务跟踪代码*/
// display only on prod site .
?>
<script type="text/javascript">
/* Google Analytics start */
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-19590146-1']);
  _gaq.push(['_setDomainName', '.usitrip.com']);
  _gaq.push(['_setAllowHash', false]);
  _gaq.push(['_addOrganic', 'baidu', 'wd']);
  _gaq.push(['_addOrganic', 'soso', 'w']);
  _gaq.push(['_addOrganic', 'youdao', 'q']);
  _gaq.push(['_addOrganic', 'sogou', 'query']);
  _gaq.push(['_addOrganic', 'vnet114', 'kw']);
  _gaq.push(['_trackPageview']);
/* Google Analytics end */
</script>

<?php

//交易成功跟踪代码
if(basename($PHP_SELF) == 'checkout_success.php' && (int)$HTTP_GET_VARS['order_id'] && (int)$customer_id){

	$orders_google_ecom_query = tep_db_query("select o.delivery_city, o.delivery_state, o.delivery_country from " . TABLE_ORDERS . " o  where o.customers_id = '" . (int)$customer_id . "' and o.orders_id ='" . (int)$HTTP_GET_VARS['order_id'] . "'");
	while ($orders_google_query = tep_db_fetch_array($orders_google_ecom_query)) {
		$orders_city = $orders_google_query['delivery_city'];
		$orders_state = $orders_google_query['delivery_state'];
		$orders_country = $orders_google_query['delivery_country'];
		$totalValue = tep_get_order_final_price_of_oid($HTTP_GET_VARS['order_id']);
?>
<script type="text/javascript">
  var _gaq = _gaq || [];
 // _gaq.push(['_setAccount', 'UA-19590146-1']);
 // _gaq.push(['_setDomainName', '.usitrip.com']);
 // _gaq.push(['_setAllowHash', false]);
  _gaq.push(['_addTrans',
  '<?= (int)$HTTP_GET_VARS['order_id'];?>',           // order ID - required
  '<?= tep_db_output($customers_advertiser);?>',  // affiliation or store name
  '<?= number_format($totalValue, 2, '.', '');?>',          // total - required
  '0.00',           // tax
  '0.00',              // shipping
  '<?= tep_db_output($orders_city);?>',       // city
  '<?= tep_db_output($orders_state);?>',     // state or province
  '<?= tep_db_output($orders_country);?>'             // country
]);
  
<?php 
		$orders_google_item_query = tep_db_query("select op.products_id, op.products_model, op.products_name, op.final_price,op.products_tax,op.products_quantity from " . TABLE_ORDERS_PRODUCTS . " op  where op.orders_id ='" . (int)$HTTP_GET_VARS['order_id'] . "' order by orders_products_id");
		while ($orders_google_item = tep_db_fetch_array($orders_google_item_query)) {
			$product_to_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $orders_google_item['products_id'] . "'");
			$product_to_categories = tep_db_fetch_array($product_to_categories_query);
			$categories_id = $product_to_categories['categories_id'];
			$order_get_cat_name =  tep_get_category_name($categories_id);
?>

  _gaq.push(['_addItem',
  '<?= (int)$HTTP_GET_VARS['order_id'];?>',           // order ID - required
  '<?= tep_db_output($orders_google_item['products_model']);?>',           // SKU/code
  '<?= tep_db_output($orders_google_item['products_name']);?>',        // product name
  '<?= tep_db_output($order_get_cat_name);?>',   // category or variation
  '<?= number_format($orders_google_item['final_price'], 2, '.', '');?>',          // unit price - required
  '<?= $orders_google_item['products_quantity'];?>'               // quantity - required
]);

<?php
		}
?>
  
  _gaq.push(['_trackTrans']);
</script>

<script type="text/javascript">
<!-- Yahoo! Hong Kong Limited
window.ysm_customData = new Object();
window.ysm_customData.conversion = "transId=,currency=,amount=";
var ysm_accountid  = "143BMLQ1NIS5B4RNPELQMC8VQKS";
document.write("<SCR" + "IPT language='JavaScript' type='text/javascript' " 
+ "SRC=//" + "srv3.wa.marketingsolutions.yahoo.com" + "/script/ScriptServlet" + "?aid=" + ysm_accountid 
+ "></SCR" + "IPT>");
// -->
</script>

<?php 
	}
}
//交易成功跟踪代码 end

//谷歌异步跟踪代码的公共部分 start
//同一网页面只需要出现一次
?>
<script type="text/javascript">
/* Google Public code start */
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
/* Google Public code end */
</script>

<?php
//谷歌异步跟踪代码的公共部分 end

// display only on prod site . end
?>
