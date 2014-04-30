<?php
set_time_limit(0);
require_once('includes/application_top.php');
 
/*
$the_category_query = tep_db_query("select c.categories_id, cd.categories_head_desc_tag ,cd.categories_head_title_tag, cd.categories_head_keywords_tag, cd.language_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id");  
while($the_category = tep_db_fetch_array($the_category_query))
{
	$categories_all_title_tag_array = explode("#!##!#",$the_category['categories_head_title_tag']);
	$categories_all_desc_tag_array = explode("#!##!#",$the_category['categories_head_desc_tag']);
	$categories_all_keywords_tag_array = explode("#!##!#",$the_category['categories_head_keywords_tag']);
	
	
	 $categories_head_title_tag = $the_category['categories_head_title_tag']."#!##!#".$categories_all_title_tag_array[0];
	 $categories_head_desc_tag = $the_category['categories_head_desc_tag']."#!##!#".$categories_all_desc_tag_array[0];
	 $categories_head_keywords_tag = $the_category['categories_head_keywords_tag']."#!##!#".$categories_all_keywords_tag_array[0];


	  $sql_data_array = array(
	  'categories_head_title_tag' => tep_db_prepare_input($categories_head_title_tag),
	  'categories_head_desc_tag' => tep_db_prepare_input($categories_head_desc_tag),
	  'categories_head_keywords_tag' => tep_db_prepare_input($categories_head_keywords_tag)
	  );	
	  tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$the_category['categories_id'] . "' and language_id = '" . (int)$the_category['language_id'] . "'");
       
	   echo "<br>categories_id = '" . (int)$the_category['categories_id'] . "' and language_id = '" . (int)$the_category['language_id'] . "'<br>";
}
*/

//amti added for encrypt previouse orders credit card


   	  $orders_query_raw = "select orders_id, cc_number from " . TABLE_ORDERS . " where length(cc_number) between 1 and 20 order by orders_id limit 500 ";
      $orders_query = tep_db_query($orders_query_raw);

      while ($order_record = tep_db_fetch_array($orders_query)) {
        print '<br>' . $order_record['orders_id'] . '&nbsp;-&nbsp;';
       
   		echo "original CC:<br>". $order_record['cc_number']."<br>";       
        // check for encryption status and if credit card number exist encrypt
        if ((tep_not_null($order_record['cc_number'])) && (strlen($order_record['cc_number']) < 20) ) {
          $cc_new_value = scs_cc_encrypt($order_record['cc_number']);         
          
		  echo "encrypted cc: update " . TABLE_ORDERS . " set cc_number = '".trim($cc_new_value)."' where orders_id = '" . (int)$order_record['orders_id'] . "'";
		  
		  tep_db_query("update " . TABLE_ORDERS . " set cc_number = '".trim($cc_new_value)."' where orders_id = '" . (int)$order_record['orders_id'] . "'");
         		  
		  echo "<br>decrypted CC:<br>". scs_cc_decrypt($cc_new_value)."<br>";       
			
        }
      }
	  
	
	  //recover all css
	  /*
	  $orders_query_raw = "select orders_id, cc_number from " . TABLE_ORDERS . " where length(cc_number) between 20 and 255 order by orders_id limit 500 ";
      $orders_query = tep_db_query($orders_query_raw);

      while ($order_record = tep_db_fetch_array($orders_query)) {
        print '<br>' . $order_record['orders_id'] . '&nbsp;-&nbsp;';
       
   		echo "original CC:<br>". $order_record['cc_number']."<br>";       
        // check for encryption status and if credit card number exist encrypt
        if ((tep_not_null($order_record['cc_number'])) && (strlen($order_record['cc_number']) > 20) ) {
          $cc_new_value = scs_cc_decrypt($order_record['cc_number']);         
          
		  echo "encrypted cc: update " . TABLE_ORDERS . " set cc_number = '".trim($cc_new_value)."' where orders_id = '" . (int)$order_record['orders_id'] . "'";
		  
		  tep_db_query("update " . TABLE_ORDERS . " set cc_number = '".trim($cc_new_value)."' where orders_id = '" . (int)$order_record['orders_id'] . "'");
      
			
        }
      }
	  */

//amit added for encrypt previouse orders credit card

 require(DIR_WS_INCLUDES . 'application_bottom.php');

?>