<?php
exit;
  require('includes/application_top.php');

  mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
  mysql_select_db(DB_DATABASE);
  
  function tep_get_opstartdate($products_id){
   $last_category_query = tep_db_query("select operate_start_date from " . TABLE_PRODUCTS . " where products_id='".$products_id."'");
   $last_category = tep_db_fetch_array($last_category_query);  
   return $last_category['operate_start_date'];
  }
function tep_get_openddate($products_id){
   $last_category_query = tep_db_query("select operate_end_date from " . TABLE_PRODUCTS . " where products_id='".$products_id."'");
   $last_category = tep_db_fetch_array($last_category_query);   
   return $last_category['operate_end_date'];
  }
  
  function tep_get_product_duration_discription($products_id){
   $last_category_query = tep_db_query("select products_durations_description from " . TABLE_PRODUCTS . " where products_id='".$products_id."'");
   $last_category = tep_db_fetch_array($last_category_query);   
   return $last_category['products_durations_description'];
  }


/*
//amit added for old discription start
	$checl_irregulartour_category_query = tep_db_query("select *  from " . TABLE_PRODUCTS . " where  products_durations_description != ''");
   	
			while($last_numbrow_discription = tep_db_fetch_array($checl_irregulartour_category_query)) {
			   $sql_data_array_reg = array(	'products_id' => $last_numbrow_discription['products_id'],										
										'operate_start_date' => $last_numbrow_discription['operate_start_date'],
										'operate_end_date' => $last_numbrow_discription['operate_end_date'],
			  							'products_durations_description' => $last_numbrow_discription['products_durations_description']
			  						);							  
			
			 tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DESCRIPTION, $sql_data_array_reg);
			 
			}
//amit added for old discription end
echo "olddone";
exit;
*/

   $last_category_query = tep_db_query("select *  from " . TABLE_PRODUCTS_START_DATE . " order by products_start_day_id");
   while($last_category = tep_db_fetch_array($last_category_query)){

 		 		$opstart = tep_get_opstartdate($last_category['products_id']);
				
				$opend = tep_get_openddate($last_category['products_id']);
 				
				if($opstart == ''){
				$opstart='01-01';
				}
				if($opend == ''){
				$opend='12-31';
				}
			  $sql_data_array = array(
			  							'products_id' => $last_category['products_id'],
										'products_start_day' => $last_category['products_start_day'],
										'extra_charge' => $last_category['extra_charge'],
										'prefix' => $last_category['prefix'],
										'sort_order' => $last_category['sort_order'],
										'operate_start_date' => $opstart,
										'operate_end_date' => $opend,
			  							'available_date' => ''
			  						);													  
			
			  tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DATE, $sql_data_array);
	
		  }		
		  
		  
	$last_category_query = tep_db_query("select *  from " . TABLE_PRODUCTS_AVAILABLE . " order by products_available_id");
   while($last_category = tep_db_fetch_array($last_category_query)){
	
 				$opstart = tep_get_opstartdate($last_category['products_id']);
				
				$opend = tep_get_openddate($last_category['products_id']);
				
				if($opstart == ''){
				$opstart='01-01';
				}
				if($opend == ''){
				$opend='12-31';
				}
			  $sql_data_array = array(
			  							'products_id' => $last_category['products_id'],
										'products_start_day' => '0',
										'extra_charge' => $last_category['extra_charges'],
										'prefix' => $last_category['prefix'],
										'sort_order' => $last_category['sort_order'],
										'operate_start_date' => $opstart,
										'operate_end_date' => $opend,
			  							'available_date' => $last_category['available_date']
			  						);							  
		
			  tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DATE, $sql_data_array);
		
			
			$checl_irregulartour_category_query = tep_db_query("select *  from " . TABLE_PRODUCTS_REG_IRREG_DESCRIPTION . " where products_id='".$last_category['products_id']."' and operate_start_date='".$opstart."' and  operate_end_date='".$opend."'");
   			$last_numbrow_discription = tep_db_num_rows($last_category_query);
	
			if($last_numbrow_discription == 0) {
			$products_durations_description = tep_get_product_duration_discription($last_category['products_id']);
			   $sql_data_array_reg = array(	'products_id' => $last_category['products_id'],										
										'operate_start_date' => $opstart,
										'operate_end_date' => $opend,
			  							'products_durations_description' => tep_db_prepare_input($products_durations_description)
			  						);							  
			
			 tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DESCRIPTION, $sql_data_array_reg);
			 
			}
			 
		  }		
		  
		  
		 echo "done";   
		
						  
  ?>