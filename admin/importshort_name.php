<?php
 require('includes/application_top.php');
 
 
  $product_query = tep_db_query("select products_id, language_id, products_name   from " . TABLE_PRODUCTS_DESCRIPTION . " ");
   while($product = tep_db_fetch_array($product_query)){
   
   
   echo $udate_short_name = "update " . TABLE_PRODUCTS_DESCRIPTION . " set  products_name_short ='".tep_db_input($product['products_name'])."'  where products_id = '" . $product['products_id'] . "' and language_id = '" . $product['language_id'] . "'";
	MCache::update_product($product['products_name']);//MCache update
   mysql_query($udate_short_name);
   }
?>