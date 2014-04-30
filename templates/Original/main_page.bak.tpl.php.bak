<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<?php
if ( file_exists(DIR_FS_INCLUDES . 'header_tags.php') ) {
  require(DIR_FS_INCLUDES . 'header_tags.php');
} else {
?>
  <title><?php echo TITLE ?></title>
<?php
}
?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_STYLE;?>" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />


<link rel="stylesheet" type="text/css" href="spiffyCal/spiffyCal_v2_1.css" />
<script type="text/javascript" src="spiffyCal/spiffyCal-v2-1-2008-04-21-min.js"></script>

<script type="text/javascript" src="menujs-2008-04-15-min.js"></script>
<script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>usitrip-tabs-2008-12-01-min.js"></script>

<script type="text/javascript">
//创建ajax对象
var ajax = false;
if(window.XMLHttpRequest) {
	 ajax = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
	try {
			ajax = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
	try {
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e) {}
	}
}
if (!ajax) {
	window.alert("<?php echo db_to_html('不能创建XMLHttpRequest对象实例.')?>");
}
</script>

<?php if($language=='schinese'){?>
<script type="text/javascript" src="big5_gb.js"></script>
<?php }else{?>
<script type="text/javascript" src="gb_big5.js"></script>
<?php }?>
<?php if (isset($javascript) && file_exists(DIR_FS_JAVASCRIPT . basename($javascript))) { require(DIR_FS_JAVASCRIPT . basename($javascript)); } ?>
<?php if (isset($javascript_external) && file_exists(DIR_FS_JAVASCRIPT . basename($javascript_external))) { ?>
<script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT.$javascript_external;?>"></script>
<?php } ?>
<?php if(basename($PHP_SELF) == 'index.php' || basename($PHP_SELF) == 'advanced_search_result.php' || $force_include_index_js == 'true' ){ ?>
<script src="<?php echo DIR_WS_JAVASCRIPT;?>index-2008-11-27-min.js" type="text/javascript"></script>
<?php } ?>

<?php
if ($validation_include_js=='true'){
?>
<script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>validation-2008-11-15-min.js"></script>
<?php
}
?>
</head>
<body onload="initTab('tab');  " style="margin-top:0;" >
<div id="last_ajax_hash" style="display:none; height:0px; width:0px;"></div>
<?php require(DIR_FS_INCLUDES . 'warnings.php'); ?>
<?php require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/header.php');
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
<?php
if (DOWN_FOR_MAINTENANCE == 'true') {
  $maintenance_on_at_time_raw = tep_db_query("select last_modified from " . TABLE_CONFIGURATION . " WHERE configuration_key = 'DOWN_FOR_MAINTENANCE'");
  $maintenance_on_at_time= tep_db_fetch_array($maintenance_on_at_time_raw);
  define('TEXT_DATE_TIME', $maintenance_on_at_time['last_modified']);
}
?>
<?php
if (DISPLAY_COLUMN_LEFT == 'yes')  {


if (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_COLUMN_LEFT_OFF =='false') {
?>
    <td width="<?php echo BOX_WIDTH_LEFT; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH_LEFT; ?>" cellspacing="0" cellpadding="<?php echo CELLPADDING_LEFT; ?>">

<?php require(DIR_FS_INCLUDES . 'column_left.php'); ?>

    </table></td>
<?php
}
}
?>

    <td width="100%" valign="top" >
<?php

if (isset($content_template) && file_exists(DIR_FS_CONTENT . basename($content_template))) {
    require(DIR_FS_CONTENT . basename($content_template));
  } else {
    require(DIR_FS_CONTENT . $content . '.tpl.php');
  }
  
?>
    </td>

<?php

if (DISPLAY_COLUMN_RIGHT == 'yes')  {
if (DOWN_FOR_MAINTENANCE =='false' || DOWN_FOR_MAINTENANCE_COLUMN_RIGHT_OFF =='false') {
?>
    <td width="<?php echo BOX_WIDTH_RIGHT; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH_RIGHT; ?>" cellspacing="0" cellpadding="<?php echo CELLPADDING_RIGHT; ?>">

<?php require(DIR_FS_INCLUDES . 'column_right.php'); ?>

    </table></td>
<?php
}
}
?>
  </tr>
</table>

<?php require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/footer.php'); 
?>
<script type="text/javascript" src="<?php echo DIR_WS_JAVASCRIPT;?>usitrip-tabs-bottom-2008-12-01-min.js"></script>
<?php
/*google track code*/
if(strtolower(CHARSET)=='gb2312'){
	$UA_code = 'UA-19590146-1';
}else{
	$UA_code = 'UA-1565452-1';
}

switch ($request_type) {
case ($request_type != 'SSL'):
echo '
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>';
break;
case ($request_type == 'SSL'):
echo '
<script src="https://ssl.google-analytics.com/urchin.js" type="text/javascript">
</script>';
break;
}

//if(!ereg('universal-studios',$_SERVER['REDIRECT_URL']) || strtolower(CHARSET)=='gb2312'){//繁体部分页面跟踪

echo '
<script type="text/javascript">
_uacct = "'.$UA_code.'";
urchinTracker();
</script>';

//}

?>

<?php /*
<!--google track code-->
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("<?php echo $UA_code ?>");
pageTracker._trackPageview();
</script>
<!--google track code end-->
*/?>
<?php

if(basename($PHP_SELF) == 'checkout_success.php' && isset($HTTP_GET_VARS['order_id']) && $HTTP_GET_VARS['order_id'] != '' && isset($customer_id)){

 $orders_google_ecom_query = tep_db_query("select  o.delivery_city, o.delivery_state, o.delivery_country from " . TABLE_ORDERS . " o  where o.customers_id = '" . (int)$customer_id . "' and o.orders_id ='" . $HTTP_GET_VARS['order_id'] . "'");
 while ($orders_google_query = tep_db_fetch_array($orders_google_ecom_query)) {
 $orders_city = $orders_google_query['delivery_city'];
 $orders_state = $orders_google_query['delivery_state'];
 $orders_country = $orders_google_query['delivery_country'];
 }
?>
 <form style="display:none;" name="utmform">
    <textarea id="utmtrans">UTM:T|<?=$HTTP_GET_VARS['order_id'];?>|<?=$customers_advertiser;?>|
    <?=number_format($totalValue, 2, '.', '');?>|0.00|0.00|<?=$orders_city;?>|<?=$orders_state;?>|<?=$orders_country;?>
	<?php	
	 $orders_google_item_query = tep_db_query("select op.products_id, op.products_model, op.products_name, op.final_price,op.products_tax,op.products_quantity from " . TABLE_ORDERS_PRODUCTS . " op  where op.orders_id ='" . $HTTP_GET_VARS['order_id'] . "' order by orders_products_id");
	 while ($orders_google_item = tep_db_fetch_array($orders_google_item_query)) {
	  
	    $product_to_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $orders_google_item['products_id'] . "'");
		$product_to_categories = tep_db_fetch_array($product_to_categories_query);
		$categories_id = $product_to_categories['categories_id'];
		$order_get_cat_name =  tep_get_category_name($categories_id);
	 ?>
	 UTM:I|<?=$HTTP_GET_VARS['order_id']?>|<?=$orders_google_item['products_model'];?>|<?=$orders_google_item['products_name'];?>|<?=$order_get_cat_name;?>|<?= number_format($orders_google_item['final_price'], 2, '.', '');?>|<?=$orders_google_item['products_quantity'];?>
	 <?php 	 }
	?>	
	</textarea>
    </form>
	 <script type="text/javascript">
    __utmSetTrans();
    </script>
<?php
}

?>
</body>
</html>