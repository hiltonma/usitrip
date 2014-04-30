<?php
/*
  $Id: categories.php,v 1.2 2004/03/29 00:18:17 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
//Added for Categories Description 1.5
  require('includes/functions/categories_description.php');
//End Categories Description 1.5

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
	
	case 'setflagf':
	if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) 
	{
		if ( isset($HTTP_GET_VARS['pID']) ) 
		{
		           tep_db_query("update " . TABLE_PRODUCTS . " set featured_products = '" . $HTTP_GET_VARS['flag'] . "' where products_id = '" . $HTTP_GET_VARS['pID'] . "'");
					MCache::update_product($HTTP_GET_VARS['pID']);//MCache update
		}
	}
	tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID']));
	 break;		
      case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          if (isset($HTTP_GET_VARS['pID'])) {
            tep_set_product_status($HTTP_GET_VARS['pID'], $HTTP_GET_VARS['flag']);
          }
		  if (isset($HTTP_GET_VARS['cID'])) {
            tep_set_category_status($HTTP_GET_VARS['cID'], $HTTP_GET_VARS['flag']);
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
        }
       
		tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID']. '&cID=' . $HTTP_GET_VARS['cID']));
        break;

//Added for Categories Description 1.5
      case 'new_category':
      case 'edit_category':
        if (ALLOW_CATEGORY_DESCRIPTIONS == 'true')
          $HTTP_GET_VARS['action']=$HTTP_GET_VARS['action'] . '_ACD';
        break;
//End Categories Description 1.5

      case 'insert_category':
      case 'update_category':

//Added for Categories Description 1.5
 if ( ($HTTP_POST_VARS['edit_x']) || ($HTTP_POST_VARS['edit_y']) ) {
          $HTTP_GET_VARS['action'] = 'edit_category_ACD';
        } else {
//End Categories Description 1.5

        if (isset($HTTP_POST_VARS['categories_id'])) $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

//Added for Categories Description 1.5
        if ($categories_id == '') {
           $categories_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
         }
//End Categories Description 1.5

        $sort_order = tep_db_prepare_input($HTTP_POST_VARS['sort_order']);
		$urlname = tep_db_prepare_input($HTTP_POST_VARS['categories_urlname']);
		
		if(!tep_not_null($urlname)) $urlname = seo_generate_urlname($HTTP_POST_VARS['categories_name']['1']);

          else $urlname = seo_generate_urlname($urlname);

			
		//amit added to check last charactor is endec with / start
			  if (substr($urlname, -1) != '/') {
            	$urlname = $urlname.'/';
        	  }

  		  //amit added to check last charactor is endec with / end


          $sql_data_array = array('sort_order' => $sort_order,

                                  'categories_urlname' => $urlname,
								  
								  'categories_status' => tep_db_prepare_input($HTTP_POST_VARS['categories_status']));


        //$sql_data_array = array('sort_order' => $sort_order);

        if ($action == 'insert_category') {
          $insert_sql_data = array('parent_id' => $current_category_id,
                                   'date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_CATEGORIES, $sql_data_array);		 
          $categories_id = tep_db_insert_id();
		  if(USE_MCACHE) MCache::update_categories(array('method'=>'insert','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22
        } elseif ($action == 'update_category') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");
		  if(USE_MCACHE)MCache::update_categories(array('method'=>'update','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22
        }

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $categories_name_array = $HTTP_POST_VARS['categories_name'];

          $language_id = $languages[$i]['id'];

          $sql_data_array = array('categories_name' => tep_db_prepare_input($categories_name_array[$language_id]));

//Added for Categories Description 1.5
            if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
              $sql_data_array = array('categories_name' => tep_db_prepare_input($HTTP_POST_VARS['categories_name'][$language_id]),
                                      'categories_heading_title' => tep_db_prepare_input($HTTP_POST_VARS['categories_heading_title'][$language_id]),
                                      'categories_description' => tep_db_prepare_input($HTTP_POST_VARS['categories_description'][$language_id]),
									  'categories_seo_description' => tep_db_prepare_input($HTTP_POST_VARS['categories_seo_description']),
									  'categories_logo_alt_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_logo_alt_tag']),
									  'categories_first_sentence' => tep_db_prepare_input($HTTP_POST_VARS['categories_first_sentence']),
                                      'categories_head_title_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_head_title_tag'][$language_id]),
                                      'categories_head_desc_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_head_desc_tag'][$language_id]),
                                      'categories_head_keywords_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_head_keywords_tag'][$language_id]));
        }
//End Categories Description 1.5

          if ($action == 'insert_category') {
            $insert_sql_data = array('categories_id' => $categories_id,
                                     'language_id' => $languages[$i]['id']);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
			if(USE_MCACHE)MCache::update_categories(array('method'=>'insert','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22
          } elseif ($action == 'update_category') {
            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
			if(USE_MCACHE)MCache::update_categories(array('method'=>'update','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22
          }
        }


//Code commented out for Categories Description 1.5
//        if ($categories_image = new upload('categories_image', DIR_FS_CATALOG_IMAGES)) {
//          tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . //tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
//        }
//Added the following to replacce above code
          if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
            tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . $HTTP_POST_VARS['categories_image'] . "' where categories_id = '" .  tep_db_input($categories_id) . "'");
			if(USE_MCACHE)MCache::update_categories(array('method'=>'update','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22
            $categories_image = '';
      } else {
        if ($categories_image = new upload('categories_image', DIR_FS_CATALOG_IMAGES)) {
          tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
		 if(USE_MCACHE) MCache::update_categories(array('method'=>'update','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22
        }
       }
//End Categories Description 1.5

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));

//Added for Categories Description 1.5
        }
//End Categories Description 1.5

        break;
      case 'delete_category_confirm':
        if (isset($HTTP_POST_VARS['categories_id'])) {
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

          $categories = tep_get_category_tree($categories_id, '', '0', '', true);
          $products = array();
          $products_delete = array();

          for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
            $product_ids_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$categories[$i]['id'] . "'");

            while ($product_ids = tep_db_fetch_array($product_ids_query)) {
              $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];
            }
          }

          reset($products);
          while (list($key, $value) = each($products)) {
            $category_ids = '';

            for ($i=0, $n=sizeof($value['categories']); $i<$n; $i++) {
              $category_ids .= "'" . (int)$value['categories'][$i] . "', ";
            }
            $category_ids = substr($category_ids, 0, -2);

            $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$key . "' and categories_id not in (" . $category_ids . ")");
            $check = tep_db_fetch_array($check_query);
            if ($check['total'] < '1') {
              $products_delete[$key] = $key;
            }
          }

// removing categories can be a lengthy process
          tep_set_time_limit(0);
          for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
            tep_remove_category($categories[$i]['id']);
          }

          reset($products_delete);
          while (list($key) = each($products_delete)) {
            tep_remove_product($key);
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'delete_product_confirm':
        if (isset($HTTP_POST_VARS['products_id']) && isset($HTTP_POST_VARS['product_categories']) && is_array($HTTP_POST_VARS['product_categories'])) {
          $product_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $product_categories = $HTTP_POST_VARS['product_categories'];

          for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
            tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
          }

          $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
          $product_categories = tep_db_fetch_array($product_categories_query);

          if ($product_categories['total'] == '0') {
            tep_remove_product($product_id);
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'move_category_confirm':
        if (isset($HTTP_POST_VARS['categories_id']) && ($HTTP_POST_VARS['categories_id'] != $HTTP_POST_VARS['move_to_category_id'])) {
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);
          $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

          $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));

          if (in_array($categories_id, $path)) {
            $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
          } else {
            tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");

			if(USE_MCACHE)MCache::update_categories(array('method'=>'update','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22

            if (USE_CACHE == 'true') {
              tep_reset_cache_block('categories');
              tep_reset_cache_block('also_purchased');
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
          }
        }

        break;
      case 'move_product_confirm':
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

        $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
        $duplicate_check = tep_db_fetch_array($duplicate_check_query);
        if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
        break;
///////////////////////////////////////////////////////////////////////////////////////
// BOF: WebMakers.com Added: Copy Attributes Existing Product to another Existing Product
      case 'create_copy_product_attributes':
  // $products_id_to= $copy_to_products_id;
  // $products_id_from = $pID;
        tep_copy_products_attributes($pID,$copy_to_products_id);
        break;
// EOF: WebMakers.com Added: Copy Attributes Existing Product to another Existing Product
///////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////
// WebMakers.com Added: Copy Attributes Existing Product to All Existing Products in a Category
      case 'create_copy_product_attributes_categories':
  // $products_id_to= $categories_products_copying['products_id'];
  // $products_id_from = $make_copy_from_products_id;
  //  echo 'Copy from products_id# ' . $make_copy_from_products_id . ' Copy to all products in category: ' . $cID . '<br>';
        $categories_products_copying_query= tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id='" . $cID . "'");
        while ( $categories_products_copying=tep_db_fetch_array($categories_products_copying_query) ) {
          // process all products in category
          tep_copy_products_attributes($make_copy_from_products_id,$categories_products_copying['products_id']);
        }
        break;
// EOF: WebMakers.com Added: Copy Attributes Existing Product to All Existing Products in a Category
///////////////////////////////////////////////////////////////////////////////////////
      case 'insert_product':
      case 'update_product':
        if (isset($HTTP_POST_VARS['edit_x']) || isset($HTTP_POST_VARS['edit_y'])) {
          $action = 'new_product';
        } else {

// BOF MaxiDVD: Modified For Ultimate Images Pack!
            if ($HTTP_POST_VARS['delete_image'] == 'yes') {
                @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image']);
            }
            if ($HTTP_POST_VARS['delete_image_med'] == 'yes') {
                @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_med']);
            }
            if ($HTTP_POST_VARS['delete_image_lrg'] == 'yes') {
                @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_lrg']);
            }
            
// EOF MaxiDVD: Modified For Ultimate Images Pack!


if (isset($HTTP_GET_VARS['pID'])) $products_id = tep_db_prepare_input($HTTP_GET_VARS['pID']);//this is to fetch peoduct id

						
					foreach($HTTP_POST_VARS as $key=>$val)
					  {
					   //echo "$key=>$val <br>";
					  }
					  
	/**********************************************************************/				  
	/********************START  UPDATION OF PRODUCTS **********************/				  
	/**********************************************************************/
					  
					 if ($action == 'update_product') 
						  {
								//**********first fetch from the product table where it is first regular of irregular ***********//
							$product_regular_query = tep_db_query("select * from ".TABLE_PRODUCTS." where products_id = '" . (int)$products_id . "' ");
							$product_regular_result = tep_db_fetch_array($product_regular_query);	
							
							//**************************************** START CODING FOR UPDATING "products_is_regular_tour"   *************/
					  		//********** Code for inserrt update delete  product_start_date/products_available tables*****************//
								
								if(isset($HTTP_POST_VARS['products_is_regular_tour']))
								{
									if($HTTP_POST_VARS['products_is_regular_tour'] == '1')//is regular
									{
										if($product_regular_result['products_is_regular_tour'] == $HTTP_POST_VARS['products_is_regular_tour'])//if both value is same
										{
												//update product_start_date table
												if(isset($HTTP_POST_VARS['regulartype']) && $HTTP_POST_VARS['regulartype']== 'daily')
												{
														tep_db_query("delete from " . TABLE_PRODUCTS_START_DATE . " where products_id = '" . $product_regular_result['products_id'] . "'");
														for($daysofweek=1;$daysofweek<8;$daysofweek++)
														{
															$sql_data_regular_daily = array('products_id' => $products_id,
																							'products_start_day' => $daysofweek,
																							'extra_charge' => tep_db_prepare_input($HTTP_POST_VARS['daily_price'.$daysofweek]),
																							'prefix' => tep_db_prepare_input($HTTP_POST_VARS['daily_prefix'.$daysofweek]),
																							'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['daily_sort_order'.$daysofweek]));
															
															tep_db_perform(TABLE_PRODUCTS_START_DATE, $sql_data_regular_daily);
														}
												}
												elseif(isset($HTTP_POST_VARS['regulartype']) && $HTTP_POST_VARS['regulartype']== 'weekday')
												{
														//if update type is weekday delete TABLE_PRODUCTS_START_DATE and again reinsert
														tep_db_query("delete from " . TABLE_PRODUCTS_START_DATE . " where products_id = '" . $product_regular_result['products_id'] . "'");
														for($daysofweek=1;$daysofweek<8;$daysofweek++)
														{
															if(isset($HTTP_POST_VARS['weekday_option'.$daysofweek]))
															{
																$sql_data_regular_weeday = array('products_id' => $products_id,
																						'products_start_day' => $daysofweek,
																						'extra_charge' => tep_db_prepare_input($HTTP_POST_VARS['weekday_price'.$daysofweek]),
																						'prefix' => tep_db_prepare_input($HTTP_POST_VARS['weekday_prefix'.$daysofweek]),
																						'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['weekday_sort_order'.$daysofweek]));
														
																tep_db_perform(TABLE_PRODUCTS_START_DATE, $sql_data_regular_weeday);
															}
														}
												}
												
										}
										elseif($product_regular_result['products_is_regular_tour'] != $HTTP_POST_VARS['products_is_regular_tour'])//if both value is not same
										{
										
												// delete products_available  table and insert into product_start_date  TABLE_PRODUCTS_START_DATE
												tep_db_query("delete from " . TABLE_PRODUCTS_AVAILABLE . " where products_id = '" . $product_regular_result['products_id'] . "'");
												if(isset($HTTP_POST_VARS['regulartype']) && $HTTP_POST_VARS['regulartype']== 'daily')
												{ //if update type is dialy
													for($daysofweek=1;$daysofweek<8;$daysofweek++)
														{
															$sql_data_regular_daily = array('products_id' => $products_id,
																							'products_start_day' => $daysofweek,
																							'extra_charge' => tep_db_prepare_input($HTTP_POST_VARS['daily_price'.$daysofweek]),
																							'prefix' => tep_db_prepare_input($HTTP_POST_VARS['daily_prefix'.$daysofweek]),
																							'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['daily_sort_order'.$daysofweek]));
															
															tep_db_perform(TABLE_PRODUCTS_START_DATE, $sql_data_regular_daily);
														}
												}
												elseif(isset($HTTP_POST_VARS['regulartype']) && $HTTP_POST_VARS['regulartype']== 'weekday')
												{//if update type is weekday
														for($daysofweek=1;$daysofweek<8;$daysofweek++)
														{
															if(isset($HTTP_POST_VARS['weekday_option'.$daysofweek]))
															{
																$sql_data_regular_weeday = array('products_id' => $products_id,
																						'products_start_day' => $daysofweek,
																						'extra_charge' => tep_db_prepare_input($HTTP_POST_VARS['weekday_price'.$daysofweek]),
																						'prefix' => tep_db_prepare_input($HTTP_POST_VARS['weekday_prefix'.$daysofweek]),
																						'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['weekday_sort_order'.$daysofweek]));
														
																tep_db_perform(TABLE_PRODUCTS_START_DATE, $sql_data_regular_weeday);
															}
														}
												}
												
										}
									}
									elseif($HTTP_POST_VARS['products_is_regular_tour'] == '0')// is irregular
									{
										if($product_regular_result['products_is_regular_tour'] == $HTTP_POST_VARS['products_is_regular_tour'])//if both value is same
										{
												//update products_available table and insert values if new 
											tep_db_query("delete from " . TABLE_PRODUCTS_AVAILABLE . " where products_id = '" . $product_regular_result['products_id'] . "'");
											if(isset($HTTP_POST_VARS['numberofdates']))
											{
												for($total_no_dates=1;$total_no_dates<$HTTP_POST_VARS['numberofdates'];$total_no_dates++)
												{
														if(tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_date_'.$total_no_dates]) != '')
														{
														$sql_data_irregular_day = array('products_id' => $products_id,
																							'available_date' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_date_'.$total_no_dates]),
																							'extra_charges' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_price_'.$total_no_dates]),
																							'prefix' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_prefix_'.$total_no_dates]),
																							'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_sort_order_'.$total_no_dates]));
															
														tep_db_perform(TABLE_PRODUCTS_AVAILABLE, $sql_data_irregular_day);
														}
												}
											}
											
										}
										elseif($product_regular_result['products_is_regular_tour'] != $HTTP_POST_VARS['products_is_regular_tour'])//if both value is not same
										{
												// delete product_start_date table and insert into products_available
											tep_db_query("delete from " . TABLE_PRODUCTS_START_DATE . " where products_id = '" . $product_regular_result['products_id'] . "'");
											if(isset($HTTP_POST_VARS['numberofdates']))
											{
												for($total_no_dates=1;$total_no_dates<$HTTP_POST_VARS['numberofdates'];$total_no_dates++)
												{
														if(tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_date_'.$total_no_dates]) != '')
														{
														$sql_data_irregular_day = array('products_id' => $products_id,
																							'available_date' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_date_'.$total_no_dates]),
																							'extra_charges' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_price_'.$total_no_dates]),
																							'prefix' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_prefix_'.$total_no_dates]),
																							'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_sort_order_'.$total_no_dates]));
															
														tep_db_perform(TABLE_PRODUCTS_AVAILABLE, $sql_data_irregular_day);
														}
												}
											}
												
										}
									}
								} 
								 
							//**************************************** end CODING FOR UPDATING "products_is_regular_tour"  *************/
					  	 
							//***************************************** start code for updating "Destination" *******************//
							if(isset($HTTP_POST_VARS['selectedcityid']) && ($HTTP_POST_VARS['selectedcityid'] != ''))
							{
								tep_db_query("delete from " . TABLE_PRODUCTS_DESTINATION . " where products_id = '" . $product_regular_result['products_id'] . "'");

									$selectedcityinsert = explode("::",$HTTP_POST_VARS['selectedcityid']);
									foreach($selectedcityinsert as $key => $val)
									{
									//echo "$key => $val <br>";
										$sql_data_duration_days = array('products_id' => $products_id,
																		'city_id' => $val);
										tep_db_perform(TABLE_PRODUCTS_DESTINATION, $sql_data_duration_days);
									}
							}
							//***************************************** end code for updating "Destination" *******************//
							
							
							//***************************************** start code for updating "Departure" *******************//
							if(isset($HTTP_POST_VARS['numberofdaparture']) && ($HTTP_POST_VARS['numberofdaparture'] != '1'))
							{
								tep_db_query("delete from " . TABLE_PRODUCTS_DEPARTURE . " where products_id = '" . $product_regular_result['products_id'] . "'");
								for($departure_places=1;$departure_places<$HTTP_POST_VARS['numberofdaparture'];$departure_places++)
								{
									if(tep_db_prepare_input($HTTP_POST_VARS['departure_address_'.$departure_places]) != '' && tep_db_prepare_input($HTTP_POST_VARS['depart_time_'.$departure_places]) != '')
									{
										$sql_data_departure_places = array('products_id' => $products_id,
																	'departure_address' => tep_db_prepare_input($HTTP_POST_VARS['departure_address_'.$departure_places]),
																	'departure_full_address' => tep_db_prepare_input($HTTP_POST_VARS['departure_full_address_'.$departure_places]),
																	'departure_time' => tep_db_prepare_input($HTTP_POST_VARS['depart_time_'.$departure_places]),
																	'map_path' => tep_db_prepare_input($HTTP_POST_VARS['departure_map_path_'.$departure_places]));
													
										tep_db_perform(TABLE_PRODUCTS_DEPARTURE, $sql_data_departure_places);
										
									}
								 }
							 }
							
							//***************************************** end code for updating "Departure" *******************//
							
							 
					  }		//end of the updation 
	/**********************************************************************/				  
	/********************END  UPDATION OF PRODUCTS ************************/				  
	/**********************************************************************/


          
          $products_date_available = tep_db_prepare_input($HTTP_POST_VARS['products_date_available']);

          $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

			
		 $urlname = tep_db_prepare_input($HTTP_POST_VARS['products_urlname']);
         if(!tep_not_null($urlname)) $urlname = seo_generate_urlname(tep_db_prepare_input($HTTP_POST_VARS['products_name'][1]));

          else $urlname = seo_generate_urlname($urlname);
		  
		if($HTTP_POST_VARS['display_room_option'] == 0)
		  {
		  	$HTTP_POST_VARS['products_single'] = $HTTP_POST_VARS['products_single1'];
			$HTTP_POST_VARS['products_kids'] = $HTTP_POST_VARS['products_kids1'];
		  }
		  
		   $sql_data_array = array('products_is_regular_tour' => tep_db_prepare_input($HTTP_POST_VARS['products_is_regular_tour']),
		   						  'departure_city_id' => tep_db_prepare_input($HTTP_POST_VARS['departure_city_id']),
								  'products_model' => tep_db_prepare_input($HTTP_POST_VARS['products_model']),
                                  'products_urlname' => $urlname,
								  'regions_id' => tep_db_prepare_input($HTTP_POST_VARS['regions_id']),
								  'products_price' => tep_db_prepare_input($HTTP_POST_VARS['products_price']),
                                  'products_date_available' => $products_date_available,
                                  'products_durations' => tep_db_prepare_input($HTTP_POST_VARS['products_durations']),
								  'display_room_option' => tep_db_prepare_input($HTTP_POST_VARS['display_room_option']),
								  'maximum_no_of_guest' => tep_db_prepare_input($HTTP_POST_VARS['maximum_no_of_guest']),
								  'products_single' => tep_db_prepare_input($HTTP_POST_VARS['products_single']),
								  'products_double' => tep_db_prepare_input($HTTP_POST_VARS['products_double']),
								  'products_triple' => tep_db_prepare_input($HTTP_POST_VARS['products_triple']),
								  'products_quadr' => tep_db_prepare_input($HTTP_POST_VARS['products_quadr']),
								  'products_kids' => tep_db_prepare_input($HTTP_POST_VARS['products_kids']),
                                  'products_status' => tep_db_prepare_input($HTTP_POST_VARS['products_status']),
                                  'products_tax_class_id' => tep_db_prepare_input($HTTP_POST_VARS['products_tax_class_id']),
								  'agency_id' => tep_db_prepare_input($HTTP_POST_VARS['agency_id']),
								  'products_special_note' => tep_db_prepare_input($HTTP_POST_VARS['products_special_note']),
                                  'manufacturers_id' => tep_db_prepare_input($HTTP_POST_VARS['manufacturers_id']));

// BOF MaxiDVD: Modified For Ultimate Images Pack!
       if (($HTTP_POST_VARS['unlink_image'] == 'yes') or ($HTTP_POST_VARS['delete_image'] == 'yes')) {
            $sql_data_array['products_image'] = '';
           } else {
         if (isset($HTTP_POST_VARS['products_image']) && tep_not_null($HTTP_POST_VARS['products_image']) && ($HTTP_POST_VARS['products_image'] != 'none')) {
            $sql_data_array['products_image'] = tep_db_prepare_input($HTTP_POST_VARS['products_image']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_med'] == 'yes') or ($HTTP_POST_VARS['delete_image_med'] == 'yes')) {

            $sql_data_array['products_image_med'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_med']) && tep_not_null($HTTP_POST_VARS['products_image_med']) && ($HTTP_POST_VARS['products_image_med'] != 'none')) {
            $sql_data_array['products_image_med'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_med']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_lrg'] == 'yes') or ($HTTP_POST_VARS['delete_image_lrg'] == 'yes')) {
            $sql_data_array['products_image_lrg'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_lrg']) && tep_not_null($HTTP_POST_VARS['products_image_lrg']) && ($HTTP_POST_VARS['products_image_lrg'] != 'none')) {
            $sql_data_array['products_image_lrg'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_lrg']);
          }
          }
      
// EOF MaxiDVD: Modified For Ultimate Images Pack!

          if ($action == 'insert_product') 
		  {
            $insert_sql_data = array('products_date_added' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
            $products_id = tep_db_insert_id();
			MCache::update_product($products_id);//MCache update

            $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");
	        $duplicate_check = tep_db_fetch_array($duplicate_check_query);
	        if ($duplicate_check['total'] < 1)tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$current_category_id . "')");
			
          } 
		  elseif ($action == 'update_product') 
		  {
            $update_sql_data = array('products_last_modified' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $update_sql_data);

            tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
			MCache::update_product($products_id);//MCache update
          }
			
			
					   
					  /*************************************************************************************/
					  /* start  CODE FOR INSERT ATTRIBUTES OF THE TOUR TYPE LIKE DAILY TOUR WEEKDATES TOUR*/
			
					  if(isset($HTTP_POST_VARS['products_is_regular_tour']) && ($HTTP_POST_VARS['products_is_regular_tour'] == '1'))
					  {
							if(isset($HTTP_POST_VARS['regulartype']) && $HTTP_POST_VARS['regulartype']== 'daily')
							{
								for($daysofweek=1;$daysofweek<8;$daysofweek++)
								{
									if($action == 'insert_product')
									{
										$sql_data_regular_daily = array('products_id' => $products_id,
																	'products_start_day' => $daysofweek,
																	'extra_charge' => tep_db_prepare_input($HTTP_POST_VARS['daily_price'.$daysofweek]),
																	'prefix' => tep_db_prepare_input($HTTP_POST_VARS['daily_prefix'.$daysofweek]),
																	'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['daily_sort_order'.$daysofweek]));
									
										tep_db_perform(TABLE_PRODUCTS_START_DATE, $sql_data_regular_daily);
									}
									
								}
							}
							elseif(isset($HTTP_POST_VARS['regulartype']) && $HTTP_POST_VARS['regulartype']== 'weekday')
							{
								for($daysofweek=1;$daysofweek<8;$daysofweek++)
								{
									if($action == 'insert_product')
									{
										if(isset($HTTP_POST_VARS['weekday_option'.$daysofweek]))
										{
											$sql_data_regular_weeday = array('products_id' => $products_id,
																	'products_start_day' => $daysofweek,
																	'extra_charge' => tep_db_prepare_input($HTTP_POST_VARS['weekday_price'.$daysofweek]),
																	'prefix' => tep_db_prepare_input($HTTP_POST_VARS['weekday_prefix'.$daysofweek]),
																	'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['weekday_sort_order'.$daysofweek]));
									
											tep_db_perform(TABLE_PRODUCTS_START_DATE, $sql_data_regular_weeday);
										}
									}
									
								}
							}
					  
					  }
					  elseif(isset($HTTP_POST_VARS['products_is_regular_tour']) && ($HTTP_POST_VARS['products_is_regular_tour'] == '0'))
					  {
					  	if(isset($HTTP_POST_VARS['numberofdates']))
						{
							for($total_no_dates=1;$total_no_dates<$HTTP_POST_VARS['numberofdates'];$total_no_dates++)
							{
									if($action == 'insert_product' && tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_date_'.$total_no_dates]) != '')
									{
										$sql_data_irregular_day = array('products_id' => $products_id,
																		'available_date' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_date_'.$total_no_dates]),
																		'extra_charges' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_price_'.$total_no_dates]),
																		'prefix' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_prefix_'.$total_no_dates]),
																		'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_sort_order_'.$total_no_dates]));
										
										tep_db_perform(TABLE_PRODUCTS_AVAILABLE, $sql_data_irregular_day);
										
									}
							}
						}
					  }
			
			/* end of  CODE FOR INSERT ATTRIBUTES OF THE TOUR TYPE LIKE DAILY TOUR WEEKDATES TOUR*/
			/*************************************************************************************/
						
			
			/*************************************************************************************/
			/* start  CODE FOR INSERT TABLE_PRODUCTS_DESTINATION OF THE TOUR */
			if(isset($HTTP_POST_VARS['selectedcityid']) && ($HTTP_POST_VARS['selectedcityid'] != ''))
			{
				if($action == 'insert_product')
				{
					$selectedcityinsert = explode("::",$HTTP_POST_VARS['selectedcityid']);
					foreach($selectedcityinsert as $key => $val)
					{
					//echo "$key => $val <br>";
						$sql_data_duration_days = array('products_id' => $products_id,
														'city_id' => $val);
						tep_db_perform(TABLE_PRODUCTS_DESTINATION, $sql_data_duration_days);
					}
				}
			}
			/* end of  CODE FOR INSERT TABLE_PRODUCTS_DESTINATION OF THE TOUR */
			/*************************************************************************************/			
						
						
			/*************************************************************************************/
			/* start  CODE FOR INSERT """""""""Departur""""""" OF THE TOUR */
			if(isset($HTTP_POST_VARS['numberofdaparture']) && ($HTTP_POST_VARS['numberofdaparture'] != '1'))
			{
				
				for($departure_places=1;$departure_places<$HTTP_POST_VARS['numberofdaparture'];$departure_places++)
				{
					if($action == 'insert_product')
					{
						if(tep_db_prepare_input($HTTP_POST_VARS['departure_address_'.$departure_places]) != '' && tep_db_prepare_input($HTTP_POST_VARS['depart_time_'.$departure_places]) != '')
						{
						$sql_data_departure_places = array('products_id' => $products_id,
														'departure_address' => tep_db_prepare_input($HTTP_POST_VARS['departure_address_'.$departure_places]),
														'departure_time' => tep_db_prepare_input($HTTP_POST_VARS['depart_time_'.$departure_places]),
														'map_path' => tep_db_prepare_input($HTTP_POST_VARS['departure_map_path_'.$departure_places]));
										
							tep_db_perform(TABLE_PRODUCTS_DEPARTURE, $sql_data_departure_places);
							
							
							//this is to insert how many departure for that particular tour
							
						}
					}
				}
			}
			/* end of  CODE FOR INSERT """""""""Departur""""""""" OF THE TOUR */
			/*************************************************************************************/			
						
		
		// SEOurls - insert products_urlname here

          // This code does not use tep_db_* functions as these halt on error

          $addition = '';

          do {

              $products_urlname = $urlname . $addition;

              mysql_query("update ". TABLE_PRODUCTS . " set products_urlname = '{$products_urlname}' where products_id = '{$products_id}'");
			  MCache::update_product($products_id);//MCache update

              if($addition == '') $addition = '-2';

              elseif(preg_match('/^-([0-9]+)$/', $addition, $m)) $addition = '-' . ($m[1] + 1);

          } while (mysql_errno() != 0);

          // SEOurls end


          $languages = tep_get_languages();
          for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
            $language_id = $languages[$i]['id'];

            $sql_data_array = array('products_name' => tep_db_prepare_input($HTTP_POST_VARS['products_name'][$language_id]),
                                    'products_description' => tep_db_prepare_input($HTTP_POST_VARS['products_description'][$language_id]),
									'products_small_description' => tep_db_prepare_input($HTTP_POST_VARS['products_small_description']),									
                                    'products_url' => tep_db_prepare_input($HTTP_POST_VARS['products_url'][$language_id]),
		                    'products_head_title_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_title_tag'][$language_id]),
         'products_head_desc_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_desc_tag'][$language_id]),
         'products_head_keywords_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_keywords_tag'][$language_id]));

            if ($action == 'insert_product') {
              $insert_sql_data = array('products_id' => $products_id,
                                       'language_id' => $language_id);

              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

              tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
            } elseif ($action == 'update_product') {
              tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
            }
          }
/////////////////////////////////////////////////////////////////////
// BOF: WebMakers.com Added: Update Product Attributes and Sort Order
// Update the changes to the attributes if any changes were made
          // Update Product Attributes
          $rows = 0;
          $options_query = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_sort_order, products_options_name");
          while ($options = tep_db_fetch_array($options_query)) {
            $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
            while ($values = tep_db_fetch_array($values_query)) {
              $rows ++;
// original              $attributes_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
              $attributes_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix, products_options_sort_order from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
              if (tep_db_num_rows($attributes_query) > 0) {
                $attributes = tep_db_fetch_array($attributes_query);
                if ($HTTP_POST_VARS['option'][$rows]) {
                  if ( ($HTTP_POST_VARS['prefix'][$rows] <> $attributes['price_prefix']) || ($HTTP_POST_VARS['price'][$rows] <> $attributes['options_values_price']) || ($HTTP_POST_VARS['products_options_sort_order'][$rows] <> $attributes['products_options_sort_order']) ) {
                    tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_values_price = '" . $HTTP_POST_VARS['price'][$rows] . "', price_prefix = '" . $HTTP_POST_VARS['prefix'][$rows] . "', products_options_sort_order = '" . $HTTP_POST_VARS['products_options_sort_order'][$rows] . "'  where products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
                  }
                } else {
                  tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
                }
              } elseif ($HTTP_POST_VARS['option'][$rows]) {
                tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $products_id . "', '" . $options['products_options_id'] . "', '" . $values['products_options_values_id'] . "', '" . $HTTP_POST_VARS['price'][$rows] . "', '" . $HTTP_POST_VARS['prefix'][$rows] . "', '" . $HTTP_POST_VARS['products_options_sort_order'][$rows] . "')");
              }
            }
          }
// EOF: WebMakers.com Added: Update Product Attributes and Sort Order
/////////////////////////////////////////////////////////////////////

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }

          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
        }
        break;
      case 'copy_to_confirm':
        if (isset($HTTP_POST_VARS['products_id']) && isset($HTTP_POST_VARS['categories_id'])) {
          $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $categories_id = tep_db_prepare_input($HTTP_POST_VARS['categories_id']);

          if ($HTTP_POST_VARS['copy_as'] == 'link') {
            if ($categories_id != $current_category_id) {
              $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
              $check = tep_db_fetch_array($check_query);
              if ($check['total'] < '1') {
                tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
              }
            } else {
              $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
            }
          } elseif ($HTTP_POST_VARS['copy_as'] == 'duplicate') {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
            $product_query = tep_db_query("select products_model, products_is_regular_tour, products_image, products_image_med, products_image_lrg, products_price, products_date_available, products_durations, products_tax_class_id, manufacturers_id from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
            $product = tep_db_fetch_array($product_query);
            tep_db_query("insert into " . TABLE_PRODUCTS . " (products_model, products_is_regular_tour, products_image, products_image_med, products_image_lrg, products_price, products_date_added, products_date_available, products_durations, products_status, products_tax_class_id, manufacturers_id) values ('" . tep_db_input($product['products_model']) . "', '" . tep_db_input($product['products_is_regular_tour']) . "', '" . tep_db_input($product['products_image']) . "', '" . tep_db_input($product['products_image_med']) . "', '" . tep_db_input($product['products_image_lrg']) . "', '" . tep_db_input($product['products_price']) . "',  now(), '" . tep_db_input($product['products_date_available']) . "', '" . tep_db_input($product['products_durations']) . "', '0', '" . (int)$product['products_tax_class_id'] . "', '" . (int)$product['manufacturers_id'] . "')");
// BOF MaxiDVD: Modified For Ultimate Images Pack!
            $dup_products_id = tep_db_insert_id();
			MCache::update_product($dup_products_id);//MCache update

            $description_query = tep_db_query("select language_id, products_name, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
            while ($description = tep_db_fetch_array($description_query)) {
              tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url, products_viewed) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_head_title_tag']) . "', '" . tep_db_input($description['products_head_desc_tag']) . "', '" . tep_db_input($description['products_head_keywords_tag']) . "', '" . tep_db_input($description['products_url']) . "', '0')");
            }

            $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$dup_products_id . "' and categories_id = '" . (int)$categories_id . "'");
	        $duplicate_check = tep_db_fetch_array($duplicate_check_query);
	        if ($duplicate_check['total'] < 1)tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");
// BOF: WebMakers.com Added: Attributes Copy on non-linked
            $products_id_from=tep_db_input($products_id);
            $products_id_to= $dup_products_id;
            $products_id = $dup_products_id;
if ( $HTTP_POST_VARS['copy_attributes']=='copy_attributes_yes' and $HTTP_POST_VARS['copy_as'] == 'duplicate' ) {
// WebMakers.com Added: Copy attributes to duplicate product
  // $products_id_to= $copy_to_products_id;
  // $products_id_from = $pID;
            $copy_attributes_delete_first='1';
            $copy_attributes_duplicates_skipped='1';
            $copy_attributes_duplicates_overwrite='0';

            if (DOWNLOAD_ENABLED == 'true') {
              $copy_attributes_include_downloads='1';
              $copy_attributes_include_filename='1';
            } else {
              $copy_attributes_include_downloads='0';
              $copy_attributes_include_filename='0';
            }
            tep_copy_products_attributes($products_id_from,$products_id_to);
// EOF: WebMakers.com Added: Attributes Copy on non-linked
}
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
        break;
// BOF MaxiDVD: Modified For Ultimate Images Pack!
      case 'new_product_preview':
// copy image only if modified
foreach($HTTP_POST_VARS as $key=>$val)
					  {
					  //echo "$key=>$val <br>";
					  }
					  //exit();

   if (($HTTP_POST_VARS['unlink_image'] == 'yes') or ($HTTP_POST_VARS['delete_image'] == 'yes')) {
        $products_image = '';
        $products_image_name = '';
        } elseif ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')) {
        $products_image = new upload('products_image');
        $products_image->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image->parse() && $products_image->save()) {
          $products_image_name = $products_image->filename;
        } else {
          $products_image_name = (isset($HTTP_POST_VARS['products_previous_image']) ? $HTTP_POST_VARS['products_previous_image'] : '');
        }
        } else {
          if (isset($HTTP_POST_VARS['products_image']) && tep_not_null($HTTP_POST_VARS['products_image']) && ($HTTP_POST_VARS['products_image'] != 'none')) {
            $products_image_name = $HTTP_POST_VARS['products_image'];
          } else {
            $products_image_name = (isset($HTTP_POST_VARS['products_previous_image']) ? $HTTP_POST_VARS['products_previous_image'] : '');
          }
        }
   if (($HTTP_POST_VARS['unlink_image_med'] == 'yes') or ($HTTP_POST_VARS['delete_image_med'] == 'yes')) {
        $products_image_med = '';
        $products_image_med_name = '';
        } elseif ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')) {
        $products_image_med = new upload('products_image_med');
        $products_image_med->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_med->parse() && $products_image_med->save()) {
          $products_image_med_name = $products_image_med->filename;
        } else {
          $products_image_med_name = (isset($HTTP_POST_VARS['products_previous_image_med']) ? $HTTP_POST_VARS['products_previous_image_med'] : '');
        }
        } else {
          if (isset($HTTP_POST_VARS['products_image_med']) && tep_not_null($HTTP_POST_VARS['products_image_med']) && ($HTTP_POST_VARS['products_image_med'] != 'none')) {
            $products_image_med_name = $HTTP_POST_VARS['products_image_med'];
          } else {
            $products_image_med_name = (isset($HTTP_POST_VARS['products_previous_image_med']) ? $HTTP_POST_VARS['products_previous_image_med'] : '');
          }
        }
   if (($HTTP_POST_VARS['unlink_image_lrg'] == 'yes') or ($HTTP_POST_VARS['delete_image_lrg'] == 'yes')) {
        $products_image_lrg = '';
        $products_image_lrg_name = '';
        } elseif ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')) {
        $products_image_lrg = new upload('products_image_lrg');
        $products_image_lrg->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_lrg->parse() && $products_image_lrg->save()) {
          $products_image_lrg_name = $products_image_lrg->filename;
        } else {
          $products_image_lrg_name = (isset($HTTP_POST_VARS['products_previous_image_lrg']) ? $HTTP_POST_VARS['products_previous_image_lrg'] : '');
        }
        } else {
          if (isset($HTTP_POST_VARS['products_image_lrg']) && tep_not_null($HTTP_POST_VARS['products_image_lrg']) && ($HTTP_POST_VARS['products_image_lrg'] != 'none')) {
            $products_image_lrg_name = $HTTP_POST_VARS['products_image_lrg'];
          } else {
            $products_image_lrg_name = (isset($HTTP_POST_VARS['products_previous_image_lrg']) ? $HTTP_POST_VARS['products_previous_image_lrg'] : '');
          }
        }
   
        break;
// EOF MaxiDVD: Modified For Ultimate Images Pack!
    }
  }

// check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
?>
<?php
// WebMakers.com Added: Display Order
  switch (true) {
    case (CATEGORIES_SORT_ORDER=="products_name"):
      $order_it_by = "pd.products_name";
      break;
    case (CATEGORIES_SORT_ORDER=="products_name-desc"):
      $order_it_by = "pd.products_name DESC";
      break;
    case (CATEGORIES_SORT_ORDER=="model"):
      $order_it_by = "p.products_model";
      break;
    case (CATEGORIES_SORT_ORDER=="model-desc"):
      $order_it_by = "p.products_model DESC";
      break;
    default:
      $order_it_by = "pd.products_name";
      break;
    }
?>

<?php
$go_back_to=$REQUEST_URI;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>

<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>

<?php $request_type = SSL; if ((HTML_AREA_WYSIWYG_DISABLE == 'Enable') or (HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Enable')) { ?>
<script language="Javascript1.2"><!-- // load htmlarea
//MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.8 <head>
      _editor_url = "<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>htmlarea/";  // URL to htmlarea files
        var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
         if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
          if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
           if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
       <?php if (HTML_AREA_WYSIWYG_BASIC_PD == 'Basic'){ ?>  if (win_ie_ver >= 5.5) {
       document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_basic.js"');
       document.write(' language="Javascript1.2"></scr' + 'ipt>');
          } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
       <?php } else{ ?> if (win_ie_ver >= 5.5) {
       document.write('<scr' + 'ipt src="' +_editor_url+ 'editor_advanced.js"');
       document.write(' language="Javascript1.2"></scr' + 'ipt>');
          } else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
       <?php }?>
// --></script>
<?php }?>
<?php
// WebMakers.com Added: Java Scripts - popup window
include(DIR_WS_INCLUDES . 'javascript/' . 'webmakers_added_js.php')
?>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
	<!-- body_text //-->
	     <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	<?php   //----- new_category / edit_category (when ALLOW_CATEGORY_DESCRIPTIONS is 'true') -----
  if ($HTTP_GET_VARS['action'] == 'new_category_ACD' || $HTTP_GET_VARS['action'] == 'edit_category_ACD') {
    if ( ($HTTP_GET_VARS['cID']) && (!$HTTP_POST_VARS) ) {
      $categories_query = tep_db_query("select cd.categories_seo_description, cd.categories_logo_alt_tag, cd.categories_first_sentence, c.categories_status, c.categories_id, c.categories_urlname, cd.categories_name, cd.categories_heading_title, cd.categories_description, cd.categories_head_title_tag, cd.categories_head_desc_tag, cd.categories_head_keywords_tag, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $HTTP_GET_VARS['cID'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");
      $category = tep_db_fetch_array($categories_query);

      $cInfo = new objectInfo($category);
    } elseif ($HTTP_POST_VARS) {
      $cInfo = new objectInfo($HTTP_POST_VARS);
      $categories_name = $HTTP_POST_VARS['categories_name'];
      $categories_heading_title = $HTTP_POST_VARS['categories_heading_title'];
      $categories_description = $HTTP_POST_VARS['categories_description'];
      $categories_head_title_tag = $HTTP_POST_VARS['categories_head_title_tag'];
      $categories_head_desc_tag = $HTTP_POST_VARS['categories_head_desc_tag'];
      $categories_head_keywords_tag = $HTTP_POST_VARS['categories_head_keywords_tag'];
      $categories_url = $HTTP_POST_VARS['categories_url'];
	  $categories_status = $HTTP_POST_VARS['categories_status'];
    } else {
      $cInfo = new objectInfo(array());
    }

    $languages = tep_get_languages();
	
	if (!isset($cInfo->categories_status)) $cInfo->categories_status = '1';
    switch ($cInfo->categories_status) {
      case '0': $cin_status = false; $cout_status = true; break;
      case '1':
      default: $cin_status = true; $cout_status = false;
    }

    $text_new_or_edit = ($HTTP_GET_VARS['action']=='new_category_ACD') ? TEXT_INFO_HEADING_NEW_CATEGORY : TEXT_INFO_HEADING_EDIT_CATEGORY;
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf($text_new_or_edit, tep_output_generated_category_path($current_category_id)); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
	  
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('new_category', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID'] . '&action=new_category_preview', 'post', 'enctype="multipart/form-data"'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
	   
	   <tr>
	   <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
       </tr>
	   <tr>
            <td class="main"><?php echo TEXT_CATEGORY_STATUS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('categories_status', '1', $cin_status) . '&nbsp;' . TEXT_CATEGORY_AVAILABLE . '&nbsp;' . tep_draw_radio_field('categories_status', '0', $cout_status) . '&nbsp;' . TEXT_CATEGORY_NOT_AVAILABLE; ?></td>
       </tr>
	   <tr>
	   <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
       </tr>
	   
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_NAME; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', (($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : tep_get_category_name($cInfo->categories_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_HEADING_TITLE; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_heading_title[' . $languages[$i]['id'] . ']', (($categories_heading_title[$languages[$i]['id']]) ? stripslashes($categories_heading_title[$languages[$i]['id']]) : tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>		  
		  <tr>

            <td class="main">Category URLname:</td>

            <!--<td class="main"><?php //echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('categories_urlname', $cInfo->categories_urlname, 'size="80"'); ?></td>-->
			<td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;'. tep_draw_input_field('categories_urlname', $cInfo->categories_urlname, 'size="50"'); ?></td>
          </tr>
<?php
    }
?>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
           <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_DESCRIPTION; ?></td>
           <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;
		   <?php echo tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : stripslashes(tep_get_category_description($cInfo->categories_id, $languages[$i]['id'])))); ?></td>


            </tr>

<?php
    }
?>

          <tr>
            <td colspan="2" class="main"><hr></td> </td>
          </tr>


              <tr>
			    <td class="main" valign="top"><?php  echo TEXT_EDIT_CATEGORIES_SEO_DESCRIPTION; ?></td>
                <td class="main" valign="top">
				<?php echo tep_draw_separator('pixel_trans.gif', '32', '15') . '&nbsp;' . tep_draw_textarea_field('categories_seo_description', 'soft', '70', '10', $cInfo->categories_seo_description); ?></td>
             <tr>


		   <tr>
            <td colspan="2" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td> </td>
           </tr>


              <tr>
			    <td class="main" valign="top"><?php  echo TEXT_EDIT_CATEGORIES_LOGO_ALT_TAG; ?></td>
                <td class="main" valign="top">
				<?php echo tep_draw_separator('pixel_trans.gif', '32', '15') . '&nbsp;' .  tep_draw_input_field('categories_logo_alt_tag', $cInfo->categories_logo_alt_tag, 'size="30"') ; ?></td>
             <tr>
			 
		   <tr>
            <td colspan="2" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td> </td>
           </tr>


              <tr>
			    <td class="main" valign="top"><?php  echo TEXT_EDIT_CATEGORIES_FIRST_SENTENCE; ?></td>
                <td class="main" valign="top">
				<?php echo tep_draw_separator('pixel_trans.gif', '32', '15') . '&nbsp;' . tep_draw_textarea_field('categories_first_sentence', 'soft', '70', '10', $cInfo->categories_first_sentence); ?></td>
             <tr>
		
           <tr>
             <td colspan="2" class="main"><hr></td> </td>
           </tr>
           <tr>
 		 <td class="main"></td><td><?php echo TEXT_PRODUCT_METTA_INFO; ?></td>
 		 </tr>

         <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
              <tr>
			  <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_TITLE_TAG; ?></td>
             <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;
			  <?php echo tep_draw_textarea_field('categories_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($categories_head_title_tag[$languages[$i]['id']]) ? stripslashes($categories_head_title_tag[$languages[$i]['id']]) : stripslashes(tep_get_category_head_title_tag($cInfo->categories_id, $languages[$i]['id'])))); ?></td>
			</tr>
<?php
  }
?>
          <tr>
            <td colspan="2" class="main"><hr></td> </td>
          </tr>
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>

              <tr>
			    <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_DESC_TAG; ?></td>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;
			    <?php echo tep_draw_textarea_field('categories_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($categories_head_desc_tag[$languages[$i]['id']]) ? stripslashes($categories_head_desc_tag[$languages[$i]['id']]) : stripslashes(tep_get_category_head_desc_tag($cInfo->categories_id, $languages[$i]['id'])))); ?></td>
             <tr>
<?php
  }
?>
          <tr>
            <td colspan="2" class="main"><hr></td> </td>
          </tr>
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>             </tr>
			     <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_KEYWORDS_TAG; ?></td>
	            <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;
			   <?php echo tep_draw_textarea_field('categories_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($categories_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($categories_head_keywords_tag[$languages[$i]['id']]) : stripslashes(tep_get_category_head_keywords_tag($cInfo->categories_id, $languages[$i]['id'])))); ?></td>
			</tr>

<?php
  }
?>
          <tr>
            <td colspan="2" class="main"><hr></td> </td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
          <tr>
            <td class="main"><?php echo TEXT_EDIT_CATEGORIES_IMAGE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('categories_image') . '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $cInfo->categories_image . tep_draw_hidden_field('categories_previous_image', $cInfo->categories_image); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_EDIT_SORT_ORDER; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo tep_draw_hidden_field('categories_date_added', (($cInfo->date_added) ? $cInfo->date_added : date('Y-m-d'))) . tep_draw_hidden_field('parent_id', $cInfo->parent_id) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </form></tr>
<?php
//MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 Products Description HTML - </form>
   if (HTML_AREA_WYSIWYG_DISABLE == 'Disable') {} else { ?>
            <script language="JavaScript1.2" defer>
             var config = new Object();  // create new config object
             config.width = "<?php echo HTML_AREA_WYSIWYG_WIDTH; ?>px";
             config.height = "<?php echo HTML_AREA_WYSIWYG_HEIGHT; ?>px";
             config.bodyStyle = 'background-color: <?php echo HTML_AREA_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo HTML_AREA_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo HTML_AREA_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo HTML_AREA_WYSIWYG_FONT_SIZE; ?>pt;';
             config.debug = <?php echo HTML_AREA_WYSIWYG_DEBUG; ?>;
          <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
          editor_generate('categories_description[<?php echo $languages[$i]['id']; ?>]',config);
     <?php } } ?>
      </script>

<?php
  //----- new_category_preview (active when ALLOW_CATEGORY_DESCRIPTIONS is 'true') -----
  } elseif ($HTTP_GET_VARS['action'] == 'new_category_preview') {
    if ($HTTP_POST_VARS) {
      $cInfo = new objectInfo($HTTP_POST_VARS);
      $categories_name = $HTTP_POST_VARS['categories_name'];
      $categories_heading_title = $HTTP_POST_VARS['categories_heading_title'];
      $categories_description = $HTTP_POST_VARS['categories_description'];
      $categories_head_title_tag = $HTTP_POST_VARS['categories_head_title_tag'];
      $categories_head_desc_tag = $HTTP_POST_VARS['categories_head_desc_tag'];
      $categories_head_keywords_tag = $HTTP_POST_VARS['categories_head_keywords_tag'];

// copy image only if modified
        $categories_image = new upload('categories_image');
        $categories_image->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($categories_image->parse() && $categories_image->save()) {
          $categories_image_name = $categories_image->filename;
        } else {
        $categories_image_name = $HTTP_POST_VARS['categories_previous_image'];
      }
#     if ( ($categories_image != 'none') && ($categories_image != '') ) {
#       $image_location = DIR_FS_CATALOG_IMAGES . $categories_image_name;
#       if (file_exists($image_location)) @unlink($image_location);
#       copy($categories_image, $image_location);
#     } else {
#       $categories_image_name = $HTTP_POST_VARS['categories_previous_image'];
#     }
    } else {
      $category_query = tep_db_query("select c.categories_id, cd.language_id, cd.categories_name, cd.categories_heading_title, cd.categories_description, cd.categories_head_title_tag, cd.categories_head_desc_tag, cd.categories_head_keywords_tag, c.categories_image, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and c.categories_id = '" . $HTTP_GET_VARS['cID'] . "'");
      $category = tep_db_fetch_array($category_query);

      $cInfo = new objectInfo($category);
      $categories_image_name = $cInfo->categories_image;
    }

    $form_action = ($HTTP_GET_VARS['cID']) ? 'update_category' : 'insert_category';

    echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

    $languages = tep_get_languages();
    for ($i=0; $i<sizeof($languages); $i++) {
      if ($HTTP_GET_VARS['read'] == 'only') {
        $cInfo->categories_name = tep_get_category_name($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_heading_title = tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_description = tep_get_category_description($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->category_template_id = tep_get_category_template_id($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_head_title_tag = tep_get_category_head_title_tag($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_head_desc_tag = tep_get_category_head_desc_tag($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_head_keywords_tag = tep_get_category_head_keywords_tag($cInfo->categories_id, $languages[$i]['id']);
      } else {
        $cInfo->categories_name = tep_db_prepare_input($categories_name[$languages[$i]['id']]);
        $cInfo->categories_heading_title = tep_db_prepare_input($categories_heading_title[$languages[$i]['id']]);
        $cInfo->categories_description = tep_db_prepare_input($categories_description[$languages[$i]['id']]);
        $cInfo->category_template_id = tep_db_prepare_input($category_template_id[$languages[$i]['id']]);
        $cInfo->categories_head_title_tag = tep_db_prepare_input($categories_head_title_tag[$languages[$i]['id']]);
        $cInfo->categories_head_desc_tag = tep_db_prepare_input($categories_head_desc_tag[$languages[$i]['id']]);
        $cInfo->categories_head_keywords_tag = tep_db_prepare_input($categories_head_keywords_tag[$languages[$i]['id']]);

    }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $cInfo->categories_heading_title; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $categories_image_name, $cInfo->categories_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . $cInfo->categories_description; ?></td>
      </tr>

<?php
    }
    if ($HTTP_GET_VARS['read'] == 'only') {
      if ($HTTP_GET_VARS['origin']) {
        $pos_params = strpos($HTTP_GET_VARS['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($HTTP_GET_VARS['origin'], 0, $pos_params);
          $back_url_params = substr($HTTP_GET_VARS['origin'], $pos_params + 1);
        } else {
          $back_url = $HTTP_GET_VARS['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = FILENAME_CATEGORIES;
        $back_url_params = 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="right" class="smallText">
<?php
/* Re-Post all POST'ed variables */
      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) {
        if (!is_array($HTTP_POST_VARS[$key])) {
          echo tep_draw_hidden_field($key, tep_htmlspecialchars(stripslashes($value)));
        }
      }
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
        echo tep_draw_hidden_field('categories_name[' . $languages[$i]['id'] . ']', tep_htmlspecialchars(stripslashes($categories_name[$languages[$i]['id']])));
        echo tep_draw_hidden_field('categories_heading_title[' . $languages[$i]['id'] . ']', tep_htmlspecialchars(stripslashes($categories_heading_title[$languages[$i]['id']])));
        echo tep_draw_hidden_field('categories_description[' . $languages[$i]['id'] . ']', tep_htmlspecialchars(stripslashes($categories_description[$languages[$i]['id']])));
        //echo tep_draw_hidden_field('products_small_description[' . $languages[$i]['id'] . ']', tep_htmlspecialchars(stripslashes($categories_description[$languages[$i]['id']])));
		echo tep_draw_hidden_field('categories_head_title_tag[' . $languages[$i]['id'] . ']', tep_htmlspecialchars(stripslashes($categories_head_title_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('categories_head_desc_tag[' . $languages[$i]['id'] . ']', tep_htmlspecialchars(stripslashes($categories_head_desc_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('categories_head_keywords_tag[' . $languages[$i]['id'] . ']', tep_htmlspecialchars(stripslashes($categories_head_keywords_tag[$languages[$i]['id']])));

     }
      echo tep_draw_hidden_field('X_categories_image', stripslashes($categories_image_name));
      echo tep_draw_hidden_field('categories_image', stripslashes($categories_image_name));

      echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if ($HTTP_GET_VARS['cID']) {
        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
      } else {
        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </form></tr>
<?php
    }


  } elseif ($action == 'new_product') {
    $parameters = array('products_name' => '',
						'products_model' => '',
						'products_urlname' => '',
                       'products_description' => '',
                       'products_url' => '',
                       'products_id' => '',
                       'products_is_regular_tour' => '',
                       'products_image' => '',
// BOF MaxiDVD: Modified For Ultimate Images Pack!
                       'products_image_med' => '',
                       'products_image_lrg' => '',
// EOF MaxiDVD: Modified For Ultimate Images Pack!
                       'products_price' => '',
                       'products_durations' => '',
                       'products_date_added' => '',
                       'products_last_modified' => '',
                       'products_date_available' => '',
					   'display_room_option' => '',
                       'products_status' => '',
                       'products_tax_class_id' => '',
					   'agency_id' => '',
                       'manufacturers_id' => '');

    $pInfo = new objectInfo($parameters);

    if (isset($HTTP_GET_VARS['pID']) && empty($HTTP_POST_VARS)) {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
      $product_query = tep_db_query("select p.maximum_no_of_guest,p.products_single,p.products_double,p.products_triple,p.products_quadr,p.products_kids,p.products_special_note,p.regions_id, p.agency_id, p.departure_city_id, p.products_model, p.products_urlname, pd.products_name, pd.products_description, pd.products_small_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_id, p.products_is_regular_tour, p.products_image, p.products_image_med, p.products_image_lrg, p.products_price, p.products_durations, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.display_room_option, p.products_tax_class_id, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
// EOF MaxiDVD: Modified For Ultimate Images Pack!
      $product = tep_db_fetch_array($product_query);

      $pInfo->objectInfo($product);
    } elseif (tep_not_null($HTTP_POST_VARS)) {
      $pInfo->objectInfo($HTTP_POST_VARS);
      $products_name = $HTTP_POST_VARS['products_name'];
      $products_description = $HTTP_POST_VARS['products_description'];
      $products_url = $HTTP_POST_VARS['products_url'];
    }

    $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                     'text' => $manufacturers['manufacturers_name']);
    }
	
	$agency_array = array(array('id' => '', 'text' => TEXT_NONE));
    $agency_query = tep_db_query("select agency_id, agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name");
	while ($agency_result = tep_db_fetch_array($agency_query)) {
      $agency_array[] = array('id' => $agency_result['agency_id'],
                                     'text' => $agency_result['agency_name']);
    }
	
	/* $region_array = array(array('id' => '', 'text' => TEXT_NONE));
    $region_query = tep_db_query("select regions_id, region_name from " . TABLE_REGION . " order by region_name");
    while ($regions = tep_db_fetch_array($region_query)) {
      $region_array[] = array('id' => $regions['regions_id'],
                                     'text' => $regions['region_name']);
    } */
	

    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
    }
	
	$city_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $city_class_query = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " order by city");
    while ($city_class = tep_db_fetch_array($city_class_query)) {
      $city_class_array[] = array('id' => $city_class['city_id'],
                                 'text' => $city_class['city']);
    }

    $languages = tep_get_languages();


    if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
    switch ($pInfo->products_status) {
      case '0': $in_status = false; $out_status = true; break;
      case '1':
      default: $in_status = true; $out_status = false;
    }

	if (!isset($pInfo->display_room_option)) $pInfo->display_room_option = '1';
    switch ($pInfo->display_room_option) {
      case '0': $display_room_yes = false; $display_room_no = true; break;
      case '1':
      default: $display_room_yes = true; $display_room_no = false;
    }
	
	// if ($pInfo->products_is_regular_tour!='') 
	 //{
    	switch ($pInfo->products_is_regular_tour) 
	{
      case '0': $is_regu = false; $not_regu = true; break;
      case '1':
      default: $is_regu = true; $not_regu = false;
    }
	//}
	
	
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<!--- this is scritp is added below is for the replace function in the java--->
<script language="JavaScript" src="includes/javascript/replacestring.js"></script>
<script language="javascript"><!--
  //var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<script language="javascript"><!--
var tax_rates = new Array();
<?php
    for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
      if ($tax_class_array[$i]['id'] > 0) {
        echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
      }
    }
?>

function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
  var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
  var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}

function updateGross() {
  var taxRate = getTaxRate();
  var grossValue = document.forms["new_product"].products_price.value;

  if (taxRate > 0) {
    grossValue = grossValue * ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
}

function updateNet() {
  var taxRate = getTaxRate();
  var netValue = document.forms["new_product"].products_price_gross.value;

  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price.value = doRound(netValue, 4);
}
//--></script>
<script>
var productmodeltest = '';
/* this is function for the checking same model in database*/
	function createRequestObject(){
		var request_;
		var browser = navigator.appName;
		if(browser == "Microsoft Internet Explorer"){
		 request_ = new ActiveXObject("Microsoft.XMLHTTP");
		}else{
		 request_ = new XMLHttpRequest();
		}
		return request_;
		}
		var http = createRequestObject();
		
		function getInfo(url,products_id){
		try
		{
			http.open('get', "input_1.php?products_model="+document.new_product.products_model.value+"&products_id="+products_id);
			http.onreadystatechange = handleInfo;
			http.send(null); 
		}
		catch(e)
		{
			//alert(e);
		}	
		}
		/******************************************************/
		function handleInfo()
		{
			
			if(http.readyState == 4)
			{
			 var response = http.responseText;
			 productmodeltest = response;
			 //document.new_product.tempdatastorage1234.value = '';
			 //document.new_product.tempdatastorage1234.value = response;
			 //alert(document.new_product.tempdatastorage.value);
			 
			}
		}
		/******************************************************/


/***************************this is function for validation  ***************************/

function SelectAll(combo)
{

						if(productmodeltest != 0)
						{
							alert(productmodeltest);
							document.new_product.products_model.focus();
							return false;
						}
						
						
	if(document.new_product.agency_id.value=="")
	{
		alert("Please Enter the value for Products Travel Agency");
		document.new_product.agency_id.focus();
		return false;
	}
					
					var i = 0 ;
					for ( i= 0 ; i < window.document.forms[0].elements.length ; i ++)
					{
						//alert(window.document.forms[0].elements[i].name + " " + window.document.forms[0].elements[i].name);
						//&& window.document.forms[0].elements[i].checked
						//alert(window.document.forms[0].elements[i].name);
						if(window.document.forms[0].elements[i].name == "products_name[1]")
						{
							if(window.document.forms[0].elements[i].value == "")
							{
								alert("Please Enter the Products Name");
								window.document.forms[0].elements[i].focus();
								return false;
							}
						}
						
						
						if(window.document.forms[0].elements[i].name == "products_model")
						{
							if(window.document.forms[0].elements[i].value == "")
							{
								alert("Please Enter the value for Products Model");
								window.document.forms[0].elements[i].focus();
								return false;
							}
						}
						
						if(window.document.forms[0].elements[i].name == "products_price")
						{
							if(window.document.forms[0].elements[i].value == "")
							{
								alert("Please Enter the value for Products Price");
								window.document.forms[0].elements[i].focus();
								return false;
							}
							if(isNaN(window.document.forms[0].elements[i].value))
							{
								alert("Please Enter the value for Products Price in numeric value");
								window.document.forms[0].elements[i].focus();
								return false;
							}
						}
						
						
		
						if(window.document.forms[0].elements[i].name == "products_description[1]")
						{
							if(window.document.forms[0].elements[i].value == "")
							{
								alert("Please Enter the value for Products Description");
								//window.document.forms[0].elements[i].focus();
								return false;
							}
						}
						
						if(window.document.forms[0].elements[i].name == "products_head_title_tag[1]")
						{
							if(window.document.forms[0].elements[i].value == "")
							{
								alert("Please Enter the value for Products Mata Tag Page Title");
								window.document.forms[0].elements[i].focus();
								return false;
							}
						}
						
						if(window.document.forms[0].elements[i].name == "products_head_desc_tag[1]")
						{
							if(window.document.forms[0].elements[i].value == "")
							{
								alert("Please Enter the value for Products Mata Tag Header Description");
								window.document.forms[0].elements[i].focus();
								return false;
							}
						}
						
						if(window.document.forms[0].elements[i].name == "products_head_keywords_tag[1]")
						{
							if(window.document.forms[0].elements[i].value == "")
							{
								alert("Please Enter the value for Products Mata Tag Keywords");
								window.document.forms[0].elements[i].focus();
								return false;
							}
						}
						
						
						if(window.document.forms[0].elements[i].name == "products_durations")
						{
							if(window.document.forms[0].elements[i].value == "")
							{
								alert("Please Enter the value for Products Duration");
								window.document.forms[0].elements[i].focus();
								return false;
							}
							if(isNaN(window.document.forms[0].elements[i].value))
							{
								alert("Please Enter the value for Products Duration in numeric value");
								window.document.forms[0].elements[i].focus();
								return false;
							}
						}
						
						if(window.document.forms[0].elements[i].name == "departure_city_id")
						{
							if(window.document.forms[0].elements[i].value == "")
							{
								alert("Please Enter the value for Products Departure City");
								window.document.forms[0].elements[i].focus();
								return false;
							}
						}
						
													
					}
					
	
		/***************************this is function for select all destination id ***************************/
		for (var i=0;i<combo.options.length;i++)
		{
		combo.options[i].selected=true;
		//alert(document.new_product.sel2.options[i].value);
		 if(i==0)
		 {
			document.new_product.selectedcityid.value = document.new_product.sel2.options[i].value;
		 }
		 else
		 {
			document.new_product.selectedcityid.value = document.new_product.selectedcityid.value+"::"+document.new_product.sel2.options[i].value;
		 }	
		//alert(document.new_product.selectedcityid.value);
		}
		
		if(document.new_product.selectedcityid.value == "")
		{
	        alert("Please enter the Destination City");	
			document.new_product.sel1.focus();
			return false;
		}
		
		
		
		return true;
}
/***************************this is function for validation  ***************************/

</script>
<script>
function generate()
{	
	<?
	//selectez toate subcategoriile din baza de date
	
	$subquery = 'select * from '.TABLE_TOUR_CITY.' order by city';	 
	$subresult = mysql_query($subquery);	
	?>
	var i; //un contor
	var nrOpt = <? echo mysql_num_rows($subresult); ?>; //nr total de optiuni din select
	
	var optiuni = Array(nrOpt); //matricea  :)
	
	<?
	
	for ($i = 0; $i < mysql_num_rows($subresult); $i++)
	{
		//umplu matricea cu ce am luat din baza de date
		$subrow = mysql_fetch_row($subresult); 
		echo 'optiuni['.$i.'] = new Array("'.$subrow[1].'","'.$subrow[0].'","'.$subrow[3].'");';
	}
	?>
		
	document.getElementById("sel1").options.length = 0;			//initializez lungimea selectului cu 0
	var select = document.getElementById("regions_id").options[document.getElementById("regions_id").selectedIndex].value;			//id-ul categoriei selectate
	document.getElementById("sel1").options.length++;			// :)
	document.getElementById("sel1").options[0] = new Option(" -- Select -- ", -1);			//ca sa apara ceva
		
	var opt = 1; //nr de optiuni din selectul final
	for (i = 0; i < nrOpt ; i++)
	{
		if (select == optiuni[i][0])
		{
			opt++;
			document.getElementById("sel1").options.length = opt;
			document.getElementById("sel1").options[opt-1] = new Option(optiuni[i][2],optiuni[i][1]);
		}
	}	
}

</script>
<table>
<tr>
<td>
    <?php echo tep_draw_form('new_product', FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') . '&action=new_product_preview', 'post', 'enctype="multipart/form-data" onsubmit="return SelectAll(sel2)" '); ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td>
		 <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id)); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table>
		</td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td>
		<table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE; ?></td>
         </tr>
		 
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_AGENCY; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('agency_id', $agency_array, $pInfo->agency_id); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_REGION; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('regions_id', tep_get_region_tree(), $pInfo->regions_id, 'id="regions_id" onChange="javascript: generate();"');
			//echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('regions_id', $region_array, $pInfo->regions_id); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? $products_name[$languages[$i]['id']] : tep_get_products_name($pInfo->products_id, $languages[$i]['id'])),'size="60"'); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>

            <td class="main">Product URLname:</td>

            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_urlname', $pInfo->products_urlname, 'size="60"'); ?></td>

          </tr>
		  
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MODEL;    $products_id_model = $pInfo->products_id;?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_model', $pInfo->products_model, 'onBlur="getInfo(\'\',\''.$products_id_model.'\')"'); ?></td>
          </tr>
		  
		  
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price', $pInfo->products_price, 'onKeyUp="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_gross', $pInfo->products_price, 'OnKeyUp="updateNet()"'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<script language="javascript"><!--
updateGross();
//--></script>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>

              </tr>
            </table></td>
          </tr>
<?php
    }
?>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_description[$languages[$i]['id']]) ? $products_description[$languages[$i]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id']))); ?></td>

              </tr>
            </table></td>
          </tr>
		  <SCRIPT language=javascript>

		<!--
		function textCounter(field,cntfield,maxlimit)
		{
		if (field.value.length > maxlimit) // if too long trim it!
		   field.value = field.value.substring(0, maxlimit);
		// otherwise, update 'characters left' counter
		else
		   cntfield.value = maxlimit - field.value.length;
		}
		
		-->
		</SCRIPT>
		  <tr>
            <td class="main" valign="top"><?php echo TEXT_PRODUCTS_SMALL_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="main">
				<?php //echo tep_draw_textarea_field('products_small_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_small_description[$languages[$i]['id']]) ? $products_small_description[$languages[$i]['id']] : tep_get_products_small_description($pInfo->products_id, $languages[$i]['id'])),'maxlength=390'); ?>
					<INPUT disabled 
                        onfocus=this.blur(); size=6 value=300 
                        name=desc_count><br>
						<?php echo tep_draw_textarea_field('products_small_description', 'soft', '70', '15', $pInfo->products_small_description,' onkeydown=textCounter(document.new_product.products_small_description,document.new_product.desc_count,300) onkeyup=textCounter(document.new_product.products_small_description,document.new_product.desc_count,300) '); ?></td>

              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2" class="main"><hr></td> </td>
          </tr>
          <tr>
		 <td class="main"></td><td><?php echo TEXT_PRODUCT_METTA_INFO; ?></td>
		 </tr>

          <tr>

         <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_PAGE_TITLE; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('products_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_title_tag[$languages[$i]['id']]) ? $products_head_title_tag[$languages[$i]['id']] : tep_get_products_head_title_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>

          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
           <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_HEADER_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_desc_tag[$languages[$i]['id']]) ? $products_head_desc_tag[$languages[$i]['id']] : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
		  <tr>	
		              <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
           <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_KEYWORDS; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? $products_head_keywords_tag[$languages[$i]['id']] : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
              </tr>

            </table></td>
          </tr>
          
		  <tr>
					<td colspan="2" class="main"><hr></td>
		  </tr>
<?php
    }
?>

          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_TYPE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_is_regular_tour', '1', $is_regu, '','') . '&nbsp;' . TEXT_PRODUCT_REGULAR_TOUR ; ?></td>
          </tr>
         <?php 
		 if($pInfo->products_is_regular_tour == "") $daily_check_true = 'CHECKED'; //IF INSERTING NEW PRODUCT
		
		if($pInfo->products_id && $pInfo->products_is_regular_tour != "" && $pInfo->products_is_regular_tour == '1')
		{
			$daily_check_true = '';
			$week_check_true = '';
			

				$startdate_count_query = tep_db_query("select count(*) as start_count from " . TABLE_PRODUCTS_START_DATE . " where products_id = ". $pInfo->products_id ." order by products_start_day");
				$startdate_count = tep_db_fetch_array($startdate_count_query); 
				//$startdate_count['start_count'];
				if($startdate_count['start_count'] == '7')
				{
					$daily_check_true = 'CHECKED';
   				    $i =1;
					$startdate_query = tep_db_query("select * from " . TABLE_PRODUCTS_START_DATE . " where products_id = ". $pInfo->products_id ." order by products_start_day");
					while ($startdate = tep_db_fetch_array($startdate_query)) 
					{
						$daily_start_date_id_result[$i] = $startdate['products_start_day_id'];
						$daily_price_result[$i] = $startdate['extra_charge'];
						$daily_prefix_result[$i] =  $startdate['prefix'];
						$daily_sort_order_result[$i] =  $startdate['sort_order'];
						$i++;
					}
				}
				else
				{
					$week_check_true = 'CHECKED';
					$i =1;
					$startdate_query = tep_db_query("select * from " . TABLE_PRODUCTS_START_DATE . " where products_id = ".$pInfo->products_id." order by products_start_day");
					while ($startdate = tep_db_fetch_array($startdate_query)) 
					{
						
						$checked[$startdate['products_start_day']] = "CHECKED";
						$weekday_start_date_id_result[$startdate['products_start_day']] = $startdate['products_start_day_id'];
						$weekday_price_result[$startdate['products_start_day']] = $startdate['extra_charge'];
						$weekday_prefix_result[$startdate['products_start_day']] =  $startdate['prefix'];
						$weekday_sort_order_result[$startdate['products_start_day']] =  $startdate['sort_order'];
						$i++;
					}
				}
		}
		 
		 ?>
		 <tr>
		 <td class="main"></td>
            <td class="main">
					  <table border="0" cellpadding="2" cellspacing="0" bgcolor="FFFFFF">
					  	<tr>
							<td colspan="4"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr class="dataTableHeadingRow">
						    <td colspan="4" class="attributeBoxContent" align="left">
							<input type=radio name=regulartype value=daily <?php echo $daily_check_true; ?>>
							Regular Daily</td>
						  </tr>
						  <tr>
							<td colspan="4"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="250" align="left">Week Days</td>
							<td class="dataTableHeadingContent" width="50" align="center">Prefix +/-</td>
							<td class="dataTableHeadingContent" width="70" align="center">Price</td>
							<td class="dataTableHeadingContent" width="70" align="center">Sort Order</td>
						  </tr>
						  <tr>
							<td colspan="4"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <?php
						  for($j=1;$j<8;$j++)
						  {
						  	if($j==1) $day = 'Sunday';
							if($j==2) $day = 'Monday';
							if($j==3) $day = 'Tuesday';
							if($j==4) $day = 'Wednesday';
							if($j==5) $day = 'Thursday';
							if($j==6) $day = 'Friday';
							if($j==7) $day = 'Saturday';
						  ?>
						  <script>  // this function si for the validation of the prefix price and sort_order
						  function daily_prefix_check(index)
						  {
						    if(document.all["daily_prefix"+index].value != '' && document.all["daily_prefix"+index].value != '+' && document.all["daily_prefix"+index].value != '-')
							{
								alert("Please enter the value +/- for the Prefix");
								document.all["daily_prefix"+index].value = '';
								document.all["daily_prefix"+index].focus();
								return false;
							} 
							return true;
						  }
						   
						   function daily_price_check(index)
						  {
						    if(document.all["daily_price"+index].value != '' && isNaN(document.all["daily_price"+index].value))
							{
								alert("Please enter the numeric value for the Price");
								document.all["daily_price"+index].value = '';
								document.all["daily_price"+index].focus();
								return false;
							} 
							return true;
						  }
						    
							function daily_sort_order_check(index)
						  {
						    if(document.all["daily_sort_order"+index].value != '' && isNaN(document.all["daily_sort_order"+index].value))
							{
								alert("Please enter the numeric value for the Sort Order");
								document.all["daily_sort_order"+index].value = '';
								document.all["daily_sort_order"+index].focus();
								return false;
							} 
							return true;
						  } 
						  
						  </script>
						  <tr class="dataTableRow">
							<td class="dataTableContent">&nbsp;<?php echo $day; ?><input type="hidden" name="daily_start_date_id_<?php echo $j;?>" value="<?php echo $daily_start_date_id_result[$j];?>"></td>
							<td class="dataTableContent" width="50" align="center"><?php echo tep_draw_input_field('daily_prefix'.$j, $daily_prefix_result[$j], 'size="2" onKeyUp="return daily_prefix_check('.$j.');"'); ?></td>
							<td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('daily_price'.$j, $daily_price_result[$j], 'size="7" onKeyUp="return daily_price_check('.$j.');"'); ?></td>
							<td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('daily_sort_order'.$j, $daily_sort_order_result[$j], 'size="7" onKeyUp="return daily_sort_order_check('.$j.');"'); ?></td>
                           </tr>
						<?php } ?>
						<tr>
							<td colspan="4"><?php echo tep_black_line(); ?></td>
						  </tr>
						 </table> 
					  <table border="0" cellpadding="2" cellspacing="0" bgcolor="FFFFFF">
					  <tr>
							<td colspan="4"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr class="dataTableHeadingRow">
						    <td colspan="4" class="attributeBoxContent" align="left">
							<input type=radio name=regulartype value=weekday <?php echo $week_check_true; ?>>
							Regular Week-days</td>
						  </tr>
						  <tr>
							<td colspan="4"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="250" align="left">Week Days</td>
							<td class="dataTableHeadingContent" width="50" align="center">Prefix +/-</td>
							<td class="dataTableHeadingContent" width="70" align="center">Price</td>
							<td class="dataTableHeadingContent" width="70" align="center">Sort Order</td>
						  </tr>
						  <tr>
							<td colspan="4"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <?php
						  for($j=1;$j<8;$j++)
						  {
						  	if($j==1) $day = 'Sunday';
							if($j==2) $day = 'Monday';
							if($j==3) $day = 'Tuesday';
							if($j==4) $day = 'Wednesday';
							if($j==5) $day = 'Thursday';
							if($j==6) $day = 'Friday';
							if($j==7) $day = 'Saturday';
						  ?>
						  <script>  // this function is for the validation of the prefix price and sort_order
						  function weekday_prefix_check(index)
						  {
						    if(document.all["weekday_prefix"+index].value != '' && document.all["weekday_prefix"+index].value != '+' && document.all["weekday_prefix"+index].value != '-')
							{
								alert("Please enter the value +/- for the prefix");
								document.all["weekday_prefix"+index].value = '';
								document.all["weekday_prefix"+index].focus();
								return false;
							} 
							return true;
						  }
						   
						   function weekday_price_check(index)
						  {
						    if(document.all["weekday_price"+index].value != '' && isNaN(document.all["weekday_price"+index].value))
							{
								alert("Please enter the numeric value for the Price");
								document.all["weekday_price"+index].value = '';
								document.all["weekday_price"+index].focus();
								return false;
							} 
							return true;
						  }
						    
							function weekday_sort_order_check(index)
						  {
						    if(document.all["weekday_sort_order"+index].value != '' && isNaN(document.all["weekday_sort_order"+index].value))
							{
								alert("Please enter the numeric value for the Sort Order");
								document.all["weekday_sort_order"+index].value = '';
								document.all["weekday_sort_order"+index].focus();
								return false;
							} 
							return true;
						  } 
						  
						  </script>
						   <tr class="dataTableRow">
							<td class="dataTableContent">&nbsp;<input type="hidden" name="weekday_start_date_id_<?php echo $j;?>" value="<?php echo $weekday_start_date_id_result[$j];?>">
							<?php echo tep_draw_checkbox_field('weekday_option'.$j, $checked[$j], $checked[$j]) . '&nbsp;' .$day; ?></td>
							<td class="dataTableContent" width="50" align="center"><?php echo tep_draw_input_field('weekday_prefix'.$j, $weekday_prefix_result[$j], 'size="2" onKeyUp="return weekday_prefix_check('.$j.');"'); ?></td>
							<td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('weekday_price'.$j, $weekday_price_result[$j], 'size="7" onKeyUp="return weekday_price_check('.$j.');"'); ?></td>
							<td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('weekday_sort_order'.$j, $weekday_sort_order_result[$j], 'size="7" onKeyUp="return weekday_sort_order_check('.$j.');"'); ?></td>
                           </tr>
						<?php } ?>
						<tr>
							<td colspan="4"><?php echo tep_black_line(); ?></td>
						  </tr>
						</table> 
		  	</td>
		 </tr>
		 
		 
		 <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <tr>
            <td class="main"></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_is_regular_tour', '0', $not_regu, '', '') . '&nbsp;' . TEXT_PRODUCT_NOT_REGULAR_TOUR; ?></td>
          </tr>
		  <?php $i =1;
		 		if($pInfo->products_is_regular_tour != "" && $pInfo->products_is_regular_tour == '0')
				{
						$available_query = tep_db_query("select * from " . TABLE_PRODUCTS_AVAILABLE . " where products_id = ".$pInfo->products_id." order by products_available_id");
						while ($available = tep_db_fetch_array($available_query)) 
						{
			$edit_irregular_data .= '<table id="table_id_irregular'.$i.'" cellpadding="2" cellspacing="2" width="100%"><tbody><tr class="'.(floor($i/2) == ($i/2) ? 'attributes-even' : 'attributes-odd').'"><td class="dataTableContent">'.$i.'. <input name="avaliable_day_date_'.$i.'" size="12" value="'.$available['available_date'].'" type="text"></td><td class="dataTableContent" align="center" width="50"><input name="avaliable_day_prefix_'.$i.'" size="2" value="'.$available['prefix'].'" type="text"></td><td class="dataTableContent" align="center" width="70"><input name="avaliable_day_price_'.$i.'" size="7" value="'.$available['extra_charges'].'" type="text"></td><td class="dataTableContent" align="center" width="70"><input name="avaliable_day_sort_order_'.$i.'" size="7" value="'.$available['sort_order'].'" type="text"></td><td align="center" width="70"><input src="includes/languages/english/images/buttons/button_delete.gif" name="delete_'.$i.'" onClick="return clearrow('.$i.')" type="image"></td></tr></tbody></table>';
							$i++;
						}
					  
				}
		?>
         <tr> 
		 <td class="main"></td>
            <td class="main">
					  <table border="0" cellpadding="2" cellspacing="0">
					      <tr>
							<td colspan="5"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr class="dataTableHeadingRow">
						    <td colspan="5" class="attributeBoxContent" align="left">
							Irregular Tour</td>
						  </tr>
						  <tr>
							<td colspan="5"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent" width="250" align="left">Dates</td>
							<td class="dataTableHeadingContent" width="50" align="center">Prefix +/-</td>
							<td class="dataTableHeadingContent" width="70" align="center">Price</td>
							<td class="dataTableHeadingContent" width="70" align="center">Sort Order</td>
							<td class="dataTableHeadingContent" width="70" align="center"></td>
						  </tr>
						  <tr>
							<td colspan="5"><?php echo tep_black_line(); ?></td>
						  </tr>
						  
						  <script>
						  function clearrow(dayid)
						  {	
															
								
								if(dayid%2 == 0)
								{
								class_tr_style = 'attributes-even' ;
								}else
								{
								class_tr_style = 'attributes-odd' ;
								}
								
								
								
								var originaldata = document.getElementById("div_id_avaliable_day_date").innerHTML;
								var tableirshow = document.getElementById("table_id_irregular"+dayid).innerHTML;
								//alert(tableidshow);
								
								var stringfinal = replaceAll(originaldata,tableirshow,"",true);
								document.getElementById("div_id_avaliable_day_date").innerHTML = stringfinal;
								
								
						  return false;
						  }
						  
							 function add()
							 {
							 	
								day_date = document.new_product.avaliable_day_date.value ;
								day_prefix = document.new_product.avaliable_day_prefix.value ;
								day_price = document.new_product.avaliable_day_price.value ;
								day_sort_order = document.new_product.avaliable_day_sort_order.value ;
								
								if(day_date == "")
								{
									alert("Please enter the date");
									document.new_product.avaliable_day_date.focus();
									return false;
								}
								if(day_prefix != "" )
								{
									if(day_prefix != '-' && day_prefix != '+')
									{
										alert("Please enter the prefix +/-");
										document.new_product.avaliable_day_prefix.focus();
										return false;
									}
								}
								
								if(day_price != "" )
								{
									 if(isNaN(day_price))
									{
										alert("Please enter the price in digit");
										document.new_product.avaliable_day_price.focus();
										return false;
									}
								}
								if(day_sort_order != "")
								{
									 if(isNaN(day_sort_order))
									{
										alert("Please enter the Sort Order in digit");
										document.new_product.avaliable_day_sort_order.focus();
										return false;
									}
								}
								
								noofdates =  document.new_product.numberofdates.value;
								
								if(noofdates%2 == 0)
								{
								class_tr_style = 'attributes-even' ;
								}else
								{
								class_tr_style = 'attributes-odd' ;
								}
																
								b = '<table id="table_id_irregular'+noofdates+'" width="100%" cellpadding="2" cellspacing="2"><tr class="'+class_tr_style+'"><td class=dataTableContent>'+noofdates+'. <input type="text" name="avaliable_day_date_'+noofdates+'" size="12" value="'+day_date+'"></td><td class="dataTableContent" width="50" align="center"><input type="text" name="avaliable_day_prefix_'+noofdates+'" size="2" value="'+day_prefix+'" ></td><td class="dataTableContent" width="70" align="center"><input type="text" name="avaliable_day_price_'+noofdates+'" size="7" value="'+day_price+'" ></td><td class="dataTableContent" width="70" align="center"><input type="text" name="avaliable_day_sort_order_'+noofdates+'" size="7" value="'+day_sort_order+'" ></td><td width="70" align="center"><input type="image" src="includes/languages/english/images/buttons/button_delete.gif" name="delete_'+noofdates+'" onClick="return clearrow('+noofdates+')"></td></tr></table>';
								document.getElementById("div_id_avaliable_day_date").innerHTML = document.getElementById("div_id_avaliable_day_date").innerHTML  + b ; 
								document.new_product.avaliable_day_date.value = "";
								document.new_product.avaliable_day_prefix.value = "";
								document.new_product.avaliable_day_price.value = "";
								document.new_product.avaliable_day_sort_order.value = "";
								document.new_product.avaliable_day_date.focus();
								document.new_product.numberofdates.value = (parseFloat(noofdates) + parseFloat(1));
								
								return false;
							 }
						</script>
						
						<script language="javascript"><!--
						  var dateavailable_date= new ctlSpiffyCalendarBox("dateavailable_date", "new_product", "avaliable_day_date","btnDate2","",scBTNMODE_CUSTOMBLUE);
						//--></script>
						  <tr class="dataTableRow">
							<td class=dataTableContent colspan="5">
							  <div id="div_id_avaliable_day_date"></div>	
						  	</td>	
						  </tr>
						  
						  
						<?php 
						if($pInfo->products_is_regular_tour != "" && $pInfo->products_is_regular_tour == '0')
						{
						?>
						<script>document.getElementById("div_id_avaliable_day_date").innerHTML = '<?php echo $edit_irregular_data; ?>';</script>
						<?php 
						} 
						?>
						 <tr>
							<td colspan="5"><?php echo tep_black_line(); ?></td>
						  </tr>		
						  
						  <tr class="dataTableRow">
							<td class="dataTableContent"><input type="hidden" name="numberofdates" value="<?php echo $i; ?>">&nbsp;<script language="javascript">dateavailable_date.writeControl(); dateavailable_date.dateFormat="yyyy-MM-dd";</script>(YYYY-MM-DD)</td>
							<td class="dataTableContent" width="50" align="center"><?php echo tep_draw_input_field('avaliable_day_prefix', '', 'size="2"'); ?></td>
							<td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('avaliable_day_price', '', 'size="7"'); ?></td>
							<td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('avaliable_day_sort_order', '', 'size="7"'); ?></td>
							<td class="dataTableContent" width="70" align="center"><?php echo tep_image_submit('button_insert.gif', '', 'onClick="return add()"'); ?></td>
                           </tr>
						   
						 <tr>
							<td  height="10" colspan="5">&nbsp;</td>
						  </tr>	 
						<tr>
							<td colspan="5"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr>
							<td  height="50" colspan="5">&nbsp;</td>
						  </tr>
						 </table> 
					  
		  	</td>
		 </tr>
		 
		 <tr>
			<td colspan="2" class="main"><hr></td>
	  	</tr>
		 
		 
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_DURATION; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_durations', $pInfo->products_durations, ''); ?></td>
          </tr>
			<script language="JavaScript" type="text/javascript"> //*THIS IS FUNCTION FROM LEFT COMBO TO RIGHT COMBO*//
			<!--
			
			var NS4 = (navigator.appName == "Netscape" && parseInt(navigator.appVersion) < 5);
			
			function addOption(theSel, theText, theValue)
			{
			  var newOpt = new Option(theText, theValue);
			  var selLength = theSel.length;
			  theSel.options[selLength] = newOpt;
			}
			
			function deleteOption(theSel, theIndex)
			{ 
			  var selLength = theSel.length;
			  if(selLength>0)
			  {
				theSel.options[theIndex] = null;
			  }
			}
			
			function moveOptions(theSelFrom, theSelTo)
			{
			  
			  var selLength = theSelFrom.length;
			  var selectedText = new Array();
			  var selectedValues = new Array();
			  var selectedCount = 0;
			  
			  var i;
			  
			  // Find the selected Options in reverse order
			  // and delete them from the 'from' Select.
			  for(i=selLength-1; i>=0; i--)
			  {
				if(theSelFrom.options[i].selected)
				{
				  selectedText[selectedCount] = theSelFrom.options[i].text;
				  selectedValues[selectedCount] = theSelFrom.options[i].value;
				  deleteOption(theSelFrom, i);
				  selectedCount++;
				}
			  }
			  
			  // Add the selected text/values in reverse order.
			  // This will add the Options to the 'to' Select
			  // in the same order as they were in the 'from' Select.
			  for(i=selectedCount-1; i>=0; i--)
			  {
				addOption(theSelTo, selectedText[i], selectedValues[i]);
			  }
			  
			  if(NS4) history.go(0);
			}
			
			//-->
			</script> 
		 <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_PRODUCTS_DESTINATION; ?></td>
            <td class="main">
			<table border="0">
				<tr>
					<td><?php echo tep_draw_separator('pixel_trans.gif', '20', '12'); 
					
					if($pInfo->products_id != "")
						{
						$sel2values = "";
						$countingcity = 0;
							$destination_query = tep_db_query("select * from ".TABLE_PRODUCTS_DESTINATION." where products_id = ".$pInfo->products_id." order by city_id");
							while ($destination_result = tep_db_fetch_array($destination_query))
							{
								//echo "select city_id, city from " . TABLE_TOUR_CITY . " where city_id=".$destination_result['city_id']."  order by city";
								$city_edit_query = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . " where city_id=".$destination_result['city_id']."  order by city");
								$city_edit_result = tep_db_fetch_array($city_edit_query); 
								$sel2values .= "<option value=".$city_edit_result['city_id'].">".$city_edit_result['city']."</option>";
															
								if((int)$city_edit_result['city_id'] != '')
								{								
									
									if($countingcity == 0)
									{
										$allready_product_city = $city_edit_result['city_id'];
									}
									else
									{
										$allready_product_city .= ",".$city_edit_result['city_id'];
									}
									$countingcity++;
								}
								
								//$allready_product_city = substr($allready_product_city, 0, -1);
								
							}
							$tempsolution="";
						if($allready_product_city == '') $allready_product_city=0;
						
						$city_new_query = tep_db_query("select ttc.city_id, ttc.city from " . TABLE_TOUR_CITY . " as ttc, ".TABLE_REGIONS." as regi where ttc.regions_id = regi.regions_id and regi.regions_id = ".$pInfo->regions_id." and ttc.city_id not in (".$allready_product_city.") order by ttc.city");
						 while ($city_new_result = tep_db_fetch_array($city_new_query)) 
						{
						 $tempsolution .=  "<option value=".$city_new_result['city_id'].">".$city_new_result['city']."</option>";
						} 
						
						}
						
					
					
					?>
					
						<select name="sel1" id="sel1" size="10" multiple="multiple">
						<?php 
						echo $tempsolution;						
						?>
						</select>
					</td>
					<td align="center" valign="middle">
						<input type="button" value="--&gt;"
						 onclick="moveOptions(this.form.sel1, this.form.sel2);" /><br />
						<input type="button" value="&lt;--"
						 onclick="moveOptions(this.form.sel2, this.form.sel1);" />
					</td>
					<td>
						<select name="sel2" size="10" multiple="multiple">
						<?php 
						if($pInfo->products_id != "")
						{
							echo $sel2values ;
						}
						?>
						</select>
						<input type="hidden" name="selectedcityid" value="">
					</td>
				</tr>
			</table>
			</td>
          </tr>
		 
		 
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		 
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_DURATION_SELECT_CITY; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('departure_city_id', $city_class_array, $pInfo->departure_city_id); ?></td>
          </tr>
		  
		  
		  <?php $k =1;
		 		if($pInfo->products_id != "")
				{
						
						$products_departure_query = tep_db_query("select * from " . TABLE_PRODUCTS_DEPARTURE . " where products_id = ".$pInfo->products_id." order by departure_id");
						while ($products_departure_result = tep_db_fetch_array($products_departure_query)) 
						{
			 $edit_departure_data .= '<table id="table_id_departure'.$k.'" cellpadding="2" cellspacing="2" width="100%"><tbody><tr class="'.(floor($k/2) == ($k/2) ? 'attributes-even' : 'attributes-odd').'"><td class="dataTableContent" valign="top" width="150" nowrap>'.$k.'. <input name="depart_time_'.$k.'" value="'.addslashes($products_departure_result['departure_time']).'" size="12" type="text"></td><td class="dataTableContent" align="center"  ><input size="20" name="departure_address_'.$k.'" value="'.addslashes($products_departure_result['departure_address']).'" type="text"></td><td class="dataTableContent" align="center" ><input size="30" name="departure_full_address_'.$k.'" value="'.addslashes($products_departure_result['departure_full_address']).'" type="text"></td><td class="dataTableContent" align="center" width="100"><input size="30" name="departure_map_path_'.$k.'" value="'.addslashes($products_departure_result['map_path']).'" type="text"></td><td align="center" width="70"><input src="includes/languages/english/images/buttons/button_delete.gif" name="delete_depart_'.$k.'" onclick="return cleardeparturerow('.$k.')" type="image"></td></tr></tbody></table>';
			
							$k++;
						}
					  
				}
		?>
		 <tr>
            <td colspan="2" ><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_PRODUCTS_DEPARTURE_PLACE; ?></td>
            <td class="main">
					  <table border="0" cellpadding="2" cellspacing="0" bgcolor="FFFFFF">
						  
						  <tr>
							<td colspan="5"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <tr class="dataTableHeadingRow">
							<td class="dataTableHeadingContent"  align="left">Departure Time</td>
							<td class="dataTableHeadingContent"  align="center">Location</td>
							<td class="dataTableHeadingContent"  align="center">Address</td>
							<td class="dataTableHeadingContent"  align="center">Map Path</td>
							<td class="dataTableHeadingContent"  align="center"></td>
						  </tr>
						  <tr>
							<td colspan="5"><?php echo tep_black_line(); ?></td>
						  </tr>
						  <script>
						  function cleardeparturerow(departid)
						  {	
								
								var originaldatadepart = document.getElementById("div_id_departure").innerHTML;
								var tableidshow = document.getElementById("table_id_departure"+departid).innerHTML;
								//alert(tableidshow);
								
								var stringfinal = replaceAll(originaldatadepart,tableidshow,"",true);
								document.getElementById("div_id_departure").innerHTML = stringfinal;
								
								
						  return false;
						  }
							 function add_departure()
							 {
							 
								depart_time = document.new_product.depart_time.value ;
								depart_add = document.new_product.departure_address.value ;
								depart_full_add = document.new_product.departure_full_address.value ;
								depart_map_path = document.new_product.departure_map_path.value;
								
								if(depart_time == "")
								{
									alert("Please enter the time");
									document.new_product.depart_time.focus();
									return false;
								}
								if(depart_add == "")
								{
									alert("Please enter the address");
									document.new_product.departure_address.focus();
									return false;
								}
								if(depart_full_add == "")
								{
									alert("Please enter the full address");
									document.new_product.departure_full_address.focus();
									return false;
								}
								
								noofdaparture =  document.new_product.numberofdaparture.value;
								//alert(day_date);
								
								if(noofdaparture%2 == 0)
								{
								class_tr_style_depart = 'attributes-even' ;
								}else
								{
								class_tr_style_depart = 'attributes-odd' ;
								}
								
								c = "<table id='table_id_departure"+noofdaparture+"' width='100%' cellpadding=2 cellspacing=2><tr class='"+class_tr_style_depart+"'><td width=150 class=dataTableContent valign=top>"+noofdaparture+". <input type=text name=depart_time_"+noofdaparture+" value='"+depart_time+"' size=12></td><td class=dataTableContent width=60 align=center><input type=text size=20 name=departure_address_"+noofdaparture+" value=\""+depart_add+"\"></td><td class=dataTableContent width=60 align=center><input type=text size=30 name=departure_full_address_"+noofdaparture+" value=\""+depart_full_add+"\"></td><td class=dataTableContent width=70 align=center><input type=text size=30 name=departure_map_path_"+noofdaparture+" value=\""+depart_map_path+"\"></td><td width=70 align=center><input type=image src='includes/languages/english/images/buttons/button_delete.gif' name='delete_depart_"+noofdaparture+"' onClick='return cleardeparturerow("+noofdaparture+")'></td></tr></table>";

								document.getElementById("div_id_departure").innerHTML = document.getElementById("div_id_departure").innerHTML  + c ; 
								document.new_product.depart_time.value = "";
								document.new_product.departure_address.value = "";
								document.new_product.departure_full_address.value = "";
								document.new_product.departure_map_path.value = "";
								document.new_product.depart_time.focus;
								document.new_product.numberofdaparture.value = (parseFloat(noofdaparture) + parseFloat(1));
								return false;
								}
						</script>
						<tr class="dataTableRow">
						<td class=dataTableContent colspan="5">
						  <div id="div_id_departure"></div>	
						  </td>					  
						</tr>
						
						<?php 
						if($pInfo->products_id != "")
						{
						?>
						<script>document.getElementById("div_id_departure").innerHTML = '<?php echo $edit_departure_data; ?>';</script>
						<?php 
						} 					
						?>
					
						 <tr>
							<td colspan="5"><?php echo tep_black_line(); ?></td>
						  </tr>
						  
						  <tr class="dataTableRow">
							<td class="dataTableContent" width="170" valign=top><input type="hidden" name="numberofdaparture" value="<?php echo $k; ?>">&nbsp;<?php echo tep_draw_input_field('depart_time', '', 'size="12"'); ?><br>(HH:MMam <br> e.g:- 9:00am)</td>
							<td class="dataTableContent" width="60" align="center"><?php echo tep_draw_input_field('departure_address', '', 'size="20"'); ?></td>
							<td class="dataTableContent" width="80" align="center"><?php echo tep_draw_input_field('departure_full_address', '', 'size="30"'); ?></td>
							<td class="dataTableContent" width="80" align="center"><?php echo tep_draw_input_field('departure_map_path', '', 'size="30"'); ?></td>
							<td class="dataTableContent" width="70" align="center"><?php echo tep_image_submit('button_insert.gif', '', 'onClick="return add_departure()"'); ?></td>
                           </tr>
						<tr>
						<td colspan="5"><?php echo tep_black_line(); ?></td>
					   </tr>
					 </table> 
					  				
		  	</td>
		 </tr>
		  
		  <tr>
			<td colspan="2" class="main"><hr></td>
		  </tr>
		  
		 
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<!-- // BOF: MaxiDVD Added for Ulimited Images Pack! -->
          <tr>
           <td class="dataTableRow" valign="top"><span class="main"><?php echo TEXT_PRODUCTS_IMAGE_NOTE; ?></span></td>
           <?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image_med') . '<br>'; ?>
           <?php } else { ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . tep_draw_input_field('products_image_med', $pInfo->products_image_med,'') . tep_draw_hidden_field('products_previous_image_med', $pInfo->products_image_med) . '</td></tr></table>';
           } if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_med) != '')
           echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image_med . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_med, $pInfo->products_image_med, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('products_previous_image_med', $pInfo->products_image_med) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span></td>
          </tr>
          <tr>
           <td class="dataTableRow" valign="top"><span class="main"><?php echo TEXT_PRODUCTS_IMAGE_MEDIUM; ?></span></td>
           <?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image') . '<br>'; ?>
           <?php } else { ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . tep_draw_input_field('products_image', $pInfo->products_image,'') . tep_draw_hidden_field('products_previous_image', $pInfo->products_image) . '</td></tr></table>';
           } if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image) != '')
           echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image, $pInfo->products_image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('products_previous_image', $pInfo->products_image) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span></td>
          </tr>
          <tr>
           <td class="dataTableRow" valign="top"><span class="main"><?php echo TEXT_PRODUCTS_IMAGE_LARGE; ?></span></td>
           <?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image_lrg') . '<br>'; ?>
           <?php } else { ?>
           <td class="dataTableRow" valign="top"><span class="smallText"><?php echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . tep_draw_input_field('products_image_lrg', $pInfo->products_image_lrg,'') . tep_draw_hidden_field('products_previous_image_lrg', $pInfo->products_image_lrg) . '</td></tr></table>';
           } if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_lrg) != '')
           echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image_lrg . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_lrg, $pInfo->products_image_lrg, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('products_previous_image_lrg', $pInfo->products_image_lrg) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_lrg" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_lrg" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span></td>
          </tr>

          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_URL . '<br><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (isset($products_url[$languages[$i]['id']]) ? $products_url[$languages[$i]['id']] : tep_get_products_url($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

		<script>
function toggleBox(szDivID,zzDivID) 
{
  if (document.layers) 
  { // NN4+
      document.layers[zzDivID].visibility = "hide";
      document.layers[zzDivID].display = "none";
      document.layers[szDivID].visibility = "show";
      document.layers[szDivID].display = "inline";
     
  } else if (document.getElementById) 
  { // gecko(NN6) + IE 5+
    var obj = document.getElementById(szDivID);
	var obj1 = document.getElementById(zzDivID);
      obj1.style.visibility = "hidden";
      obj1.style.display = "none";
      obj.style.visibility = "visible";
      obj.style.display = "inline";
    
  } else if (document.all) 
  { // IE 4
    
      document.all[zzDivID].style.visibility = "hidden";
      document.all[zzDivID].style.display = "none";
      document.all[szDivID].style.visibility = "visible";
      document.all[szDivID].style.display = "inline";
    
  }
}
		  </script>
		  <tr>
            <td class="main"><?php echo '是否显示房间：'; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('display_room_option', '1', $display_room_yes,'', 'onclick="toggleBox(\'optionyes\',\'optionno\');"') . '&nbsp; Yes &nbsp;' . tep_draw_radio_field('display_room_option', '0', $display_room_no,'', 'onclick="toggleBox(\'optionno\',\'optionyes\');"') . '&nbsp; No'; ?></td>
         </tr>
		 <?php 
		 if($display_room_yes)
		 {
		 ?>
		 <style>
				.display_room_option_yes { visibility: visible; display: inline; } 
		</style>
		<style>
				.display_room_option_no { visibility: hidden; display: none; } 
		</style>
		 <?php
		 }elseif($display_room_no)
		 {
		 ?>
		 <style>
				.display_room_option_yes { visibility: hidden; display: none; } 
		</style>
		<style>
				.display_room_option_no { visibility: visible; display: inline; } 
		</style>
		<?php 
		}?>
		
		<?php
		
   		$maximum_no_of_guest_array[] = array('id' => '1', 'text' => '1');
		$maximum_no_of_guest_array[] = array('id' => '2', 'text' => '2');
		$maximum_no_of_guest_array[] = array('id' => '3', 'text' => '3');
		$maximum_no_of_guest_array[] = array('id' => '4', 'text' => '4');
		if($pInfo->maximum_no_of_guest!='')
		$selected_guest = $pInfo->maximum_no_of_guest;
		else 
		$selected_guest = '4';
		?>
		  <tr class="dataTableRow">
			<td class=dataTableContent colspan="2">
			  <div id="optionyes" class="display_room_option_yes">
			  	 <table width="100%">  
				   <tr>	
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  <tr>
					<td class="main" width="20%" align="left"><?php echo '每房间最多可住几人： '; ?></td>
					<!--<td class="main" width="80%" align="left"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('maximum_no_of_guest', $pInfo->maximum_no_of_guest, 'size="7"'); ?></td>-->
            		<td class="main" idth="80%" align="left"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('maximum_no_of_guest', $maximum_no_of_guest_array,$selected_guest ); ?></td>
				  </tr>
				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  <tr>
					<td class="main"><?php echo 'Single Occupancy Price:'; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_single', $pInfo->products_single, 'size="7"'); ?></td>
				  </tr>
				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  <tr>
					<td class="main"><?php echo 'Double Occupancy Price:'; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_double', $pInfo->products_double, 'size="7"'); ?></td>
				  </tr>
				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  <tr>
					<td class="main"><?php echo 'Triple Occupancy Price:'; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_triple', $pInfo->products_triple, 'size="7"'); ?></td>
				  </tr>
				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  <tr>
					<td class="main"><?php echo 'Quadruple Occupancy Price:'; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_quadr', $pInfo->products_quadr, 'size="7"'); ?></td>
				  </tr>
				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  <tr>
					<td class="main"><?php echo 'Kids Price:'; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_kids', $pInfo->products_kids, 'size="7"'); ?></td>
				  </tr>
				  </table>
		  </div>	
			</td>	
		  </tr>
		  <tr class="dataTableRow">
			<td class=dataTableContent colspan="2">
			  <div id="optionno" class="display_room_option_no">
			  	 <table width="100%">  
				   <tr>	
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  
				  <tr>
					<td class="main" width="20%" align="left"><?php echo 'Adult Price:'; ?></td>
					<td class="main" width="80%" align="left"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_single1', $pInfo->products_single, 'size="7"'); ?></td>
				  </tr>
				  
				  
				  <tr>
					<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
				  </tr>
				  
				  <tr>
					<td class="main"><?php echo 'Kids Price:'; ?></td>
					<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_kids1', $pInfo->products_kids, 'size="7"'); ?></td>
				  </tr>
				  
				  </table>
		  </div>	
			</td>	
		  </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		   <tr>
            <td class="main"><?php echo 'Special Note'; ?></td>
			<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_textarea_field('products_special_note', 'soft', '70', '5',$pInfo->products_special_note); ?></td>
          </tr>
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table>
		</td>
      </tr>

		  <?php
/////////////////////////////////////////////////////////////////////
// BOF: WebMakers.com Added: Draw Attribute Tables
?>
      <tr>
        <td><table border="3" cellspacing="5" cellpadding="2" align="center" bgcolor="000000">
<?php
    $rows = 0;
    $options_query = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_sort_order, products_options_name");
    while ($options = tep_db_fetch_array($options_query)) {
      $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
      $header = false;
      while ($values = tep_db_fetch_array($values_query)) {
        $rows ++;
        if (!$header) {
          $header = true;
?>
          <tr valign="top">
			<td><table border="2" cellpadding="2" cellspacing="2" bgcolor="FFFFFF">
              <tr class="dataTableHeadingRow">
              <td colspan="4" class="attributeBoxContent" align="center">Active Attributes</td>
             </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="250" align="left"><?php echo $options['products_options_name']; ?></td>
                <td class="dataTableHeadingContent" width="50" align="center"><?php echo 'Prefix'; ?></td>
                <td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Price'; ?></td>
                <td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Sort Order'; ?></td>
              </tr>
<?php
        }
        $attributes = array();
        if (sizeof($HTTP_POST_VARS) > 0) {
          if ($HTTP_POST_VARS['option'][$rows]) {
            $attributes = array(
                                'products_attributes_id' => $HTTP_POST_VARS['option'][$rows],
                                'options_values_price' => $HTTP_POST_VARS['price'][$rows],
                                'price_prefix' => $HTTP_POST_VARS['prefix'][$rows],
                                'products_options_sort_order' => $HTTP_POST_VARS['products_options_sort_order'][$rows],
                                    );
          }
        } else {
          $attributes_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix, products_options_sort_order from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $pInfo->products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
          if (tep_db_num_rows($attributes_query) > 0) {
            $attributes = tep_db_fetch_array($attributes_query);
          }
        }
?>
              <tr class="dataTableRow">
                <td class="dataTableContent"><?php echo tep_draw_checkbox_field('option[' . $rows . ']', $attributes['products_attributes_id'], $attributes['products_attributes_id']) . '&nbsp;' . $values['products_options_values_name']; ?>&nbsp;</td>
                <td class="dataTableContent" width="50" align="center"><?php echo tep_draw_input_field('prefix[' . $rows . ']', $attributes['price_prefix'], 'size="2"'); ?></td>
                <td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('price[' . $rows . ']', $attributes['options_values_price'], 'size="7"'); ?></td>
                <td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('products_options_sort_order[' . $rows . ']', $attributes['products_options_sort_order'], 'size="7"'); ?></td>
                             </tr>
<?php
      }
      if ($header) {
?>
            </table></td>

<?php
      }
    }
?>
          </tr>
        </table></td>
      </tr>
<?php
// EOF: WebMakers.com Added: Draw Attribute Tables
/////////////////////////////////////////////////////////////////////
?>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr>
    </table></form>
	</td>
</tr>
</table>
<!-- // BOF: MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 Products Description HTML -->
<?php
  if (HTML_AREA_WYSIWYG_DISABLE == 'Enable') { ?>
            <script language="JavaScript1.2" defer>
             var config = new Object();  // create new config object
             config.width = "<?php echo HTML_AREA_WYSIWYG_WIDTH; ?>px";
             config.height = "<?php echo HTML_AREA_WYSIWYG_HEIGHT; ?>px";
             config.bodyStyle = 'background-color: <?php echo HTML_AREA_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo HTML_AREA_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo HTML_AREA_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo HTML_AREA_WYSIWYG_FONT_SIZE; ?>pt;';
             config.debug = <?php echo HTML_AREA_WYSIWYG_DEBUG; ?>;
          <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
             editor_generate('products_description[<?php echo $languages[$i]['id']; ?>]',config);
             editor_generate('products_small_description',config);
          <?php } ?>
<?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Enable') && (HTML_AREA_WYSIWYG_DISABLE == 'Enable')){ ?>
             config.height = "25px";
             config.bodyStyle = 'background-color: white; font-family: Arial; color: black; font-size:12px;';
             config.toolbar = [ ['InsertImageURL'] ];
             config.OscImageRoot = '<?= trim(HTTP_SERVER . DIR_WS_CATALOG_IMAGES) ?>';
             editor_generate('products_image',config);
             editor_generate('products_image_med',config);
             editor_generate('products_image_lrg',config);
            <?php } ?>
            </script>
<?php } ?>
<!-- // EOF: MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 Products Description HTML -->

<?php
  } elseif ($action == 'new_product_preview') {
    if (tep_not_null($HTTP_POST_VARS)) {
	
	foreach($HTTP_POST_VARS as $key=>$val)
					  {
					  //echo "$key=>$val <br>";
					  }
					  //exit();
					  
      $pInfo = new objectInfo($HTTP_POST_VARS);
      $products_name = $HTTP_POST_VARS['products_name'];
      $products_description = $HTTP_POST_VARS['products_description'];
      $products_head_title_tag = $HTTP_POST_VARS['products_head_title_tag'];
      $products_head_desc_tag = $HTTP_POST_VARS['products_head_desc_tag'];
      $products_head_keywords_tag = $HTTP_POST_VARS['products_head_keywords_tag'];
      $products_url = $HTTP_POST_VARS['products_url'];
    } else {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
      $product_query = tep_db_query("select p.featured_products, p.products_model, p.products_id, pd.language_id, pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_is_regular_tour, p.products_image, p.products_image_med, p.products_image_lrg, p.products_price, p.products_durations, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.manufacturers_id  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "'");
// EOF MaxiDVD: Modified For Ultimate Images Pack!
      $product = tep_db_fetch_array($product_query);

      $pInfo = new objectInfo($product);
      $products_image_name = $pInfo->products_image;
      $products_image_med_name = $pInfo->products_image_med;
      $products_image_lrg_name = $pInfo->products_image_lrg;
    }

    $form_action = (isset($HTTP_GET_VARS['pID'])) ? 'update_product' : 'insert_product';

    echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

    $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) {
        $pInfo->products_name = tep_get_products_name($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_description = tep_get_products_description($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
        $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
        $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);
        $pInfo->products_url = tep_get_products_url($pInfo->products_id, $languages[$i]['id']);
      } else {
        $pInfo->products_name = tep_db_prepare_input($products_name[$languages[$i]['id']]);
        $pInfo->products_description = tep_db_prepare_input($products_description[$languages[$i]['id']]);
        $pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
        $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
        $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);
        $pInfo->products_url = tep_db_prepare_input($products_url[$languages[$i]['id']]);
      }
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $pInfo->products_name; ?></td>
            <td class="pageHeading" align="right"><?php echo $currencies->format($pInfo->products_price); ?></td>
          </tr>
        </table></td>
      </tr>
<!-- // BOF MaxiDVD: Modified For Ultimate Images Pack! // -->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main">
<?php if ($products_image_med_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_med_name, $products_image_med_name, MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"'); } elseif ($products_image_lrg_name) { ?>
<script language="javascript"><!--
      document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_lrg_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_name, $products_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . '</a>'; ?>');
//--></script>
<?php } elseif ($products_image_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_name, $products_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"');}; ?>
<?php echo stripcslashes($pInfo->products_description) . '<br><br><center>'; ?>

        </td>
      </tr>
<!-- // EOF MaxiDVD: Modified For Ultimate Images Pack! // -->

<?php
      if ($pInfo->products_url) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->products_url); ?></td>
      </tr>
<?php
      }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
      if ($pInfo->products_date_available > date('Y-m-d')) {
?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->products_date_available)); ?></td>
      </tr>
<?php
      } else {
?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->products_date_added)); ?></td>
      </tr>
<?php
      }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
    }

    if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) {
      if (isset($HTTP_GET_VARS['origin'])) {
        $pos_params = strpos($HTTP_GET_VARS['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($HTTP_GET_VARS['origin'], 0, $pos_params);
          $back_url_params = substr($HTTP_GET_VARS['origin'], $pos_params + 1);
        } else {
          $back_url = $HTTP_GET_VARS['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = FILENAME_CATEGORIES;
        $back_url_params = 'cPath=' . $cPath . '&pID=' . $pInfo->products_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="right" class="smallText">
<?php
/////////////////////////////////////////////////////////////////////
// BOF: WebMakers.com Added: Preview Back
/* Re-Post all POST'ed variables */
      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) {
        if (!is_array($HTTP_POST_VARS[$key])) {
          echo tep_draw_hidden_field($key, tep_htmlspecialchars(stripslashes($value)));
        } else {
          while (list($k, $v) = each($value)) {
            echo tep_draw_hidden_field($key . '[' . $k . ']', tep_htmlspecialchars(stripslashes($v)));
          }
        }
      }
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        echo tep_draw_hidden_field('products_name[' . $languages[$i]['id'] . ']', tep_htmlspecialchars(stripslashes($products_name[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_description[' . $languages[$i]['id'] . ']', tep_htmlspecialchars(stripslashes($products_description[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_head_title_tag[' . $languages[$i]['id'] . ']', tep_htmlspecialchars(stripslashes($products_head_title_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', tep_htmlspecialchars(stripslashes($products_head_desc_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', tep_htmlspecialchars(stripslashes($products_head_keywords_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_url[' . $languages[$i]['id'] . ']', tep_htmlspecialchars(stripslashes($products_url[$languages[$i]['id']])));
      }
// EOF: WebMakers.com Added: Preview Back
/////////////////////////////////////////////////////////////////////
?>

<?php
/////////////////////////////////////////////////////////////////////
// BOF: WebMakers.com Added: Modified to include Attributes Code
/* Re-Post all POST'ed variables */
      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) {
        if (is_array($value)) {
          while (list($k, $v) = each($value)) {
            echo tep_draw_hidden_field($key . '[' . $k . ']', tep_htmlspecialchars(stripslashes($v)));
          }
        } else {
          echo tep_draw_hidden_field($key, tep_htmlspecialchars(stripslashes($value)));
        }
      }
// EOF: WebMakers.com Added: Modified to include Attributes Code
/////////////////////////////////////////////////////////////////////
      echo tep_draw_hidden_field('products_image', stripslashes($products_image_name));
// BOF MaxiDVD: Added For Ultimate Images Pack!
      echo tep_draw_hidden_field('products_image_med', stripslashes($products_image_med_name));
      echo tep_draw_hidden_field('products_image_lrg', stripslashes($products_image_lrg_name));
      
// EOF MaxiDVD: Added For Ultimate Images Pack!
      echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if (isset($HTTP_GET_VARS['pID'])) {
        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
      } else {
        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </tr>
    </table></form>
<?php
    }
  } else {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText" align="right">
<?php
    echo tep_draw_form('search', FILENAME_CATEGORIES, '', 'get');
    echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');
    echo '</form>';
?>
                </td>
              </tr>
              <tr>
                <td class="smallText" align="right">
<?php
    echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get');
    echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
    echo '</form>';
?>
                </td>
              </tr>
			  <tr>
                <td class="smallText" align="right">
<?php

     $duration_tree_array = array(array('id' => '', 'text' => TEXT_NONE));
     $dura_query = tep_db_query("select distinct(products_durations) as prod_dura from " . TABLE_PRODUCTS . " group by products_durations");
      while($dura_result = tep_db_fetch_array($dura_query))
	  {
      $duration_tree_array[] = array('id' => $dura_result['prod_dura'],
                                 'text' => $dura_result['prod_dura']);
      }
	  
    $city_class_array = array(array('id' => '', 'text' => TEXT_NONE));
    $city_class_query = tep_db_query("select city_id, city from " . TABLE_TOUR_CITY . "   where departure_city_status = '1' order by city");
    while ($city_class = tep_db_fetch_array($city_class_query)) {
      $city_class_array[] = array('id' => $city_class['city_id'],
                                 'text' => $city_class['city']);
    }
    echo tep_draw_form('durationsearch', FILENAME_CATEGORIES, '', 'get');
	if(isset($_GET['cPath']) && $_GET['cPath']!= '')
	{
		echo tep_draw_hidden_field('cPath',$_GET['cPath']);
	}
    echo HEADING_TITLE_DURATION . ' ' . tep_draw_pull_down_menu('durations', $duration_tree_array, $_GET['durations'], 'onChange="this.form.submit();"');
    echo '<br>'.HEADING_TITLE_DEPARTURE . ' ' . tep_draw_pull_down_menu('departure', $city_class_array, $_GET['departure'], 'onChange="this.form.submit();"');
	echo '</form>';
?>
                </td>
              </tr>
			
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_ID; ?></td>
				.<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
				<td class="dataTableHeadingContent" align="center">Feature</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $categories_count = 0;
    $rows = 0;
    if (isset($HTTP_GET_VARS['search'])) {
      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);

      $categories_query = tep_db_query("select c.categories_status, c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
    }elseif(($HTTP_GET_VARS['durations']=="") && ($HTTP_GET_VARS['departure']==""))
	{
      $categories_query = tep_db_query("select c.categories_status, c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
    }else
	{
      $categories_query = tep_db_query("select * from " . TABLE_CATEGORIES . " where categories_id = ''");
	}
    while ($categories = tep_db_fetch_array($categories_query)) {
      $categories_count++;
      $rows++;

// Get parent_id for subcategories if search
      if (isset($HTTP_GET_VARS['search'])) $cPath= $categories['parent_id'];

      if ((!isset($HTTP_GET_VARS['cID']) && !isset($HTTP_GET_VARS['pID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge($categories, $category_childs, $category_products);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent" width="5%"><?php echo $categories['categories_id']; ?></td>
				<td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . $categories['categories_name'] . '</b>'; ?></td>
                <td class="dataTableContent" align="center">
<?php 
	if ($categories['categories_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="<?php echo $current_row; ?>" align="center">&nbsp;</td>
				<td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $products_count = 0;
    if (isset($HTTP_GET_VARS['search'])) {
      $products_query = tep_db_query("select p.featured_products, p.products_model, p.products_id, pd.products_name, p.products_is_regular_tour, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status,  p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and pd.products_name like '%" . tep_db_input($search) . "%' order by pd.products_name");
    }else if($HTTP_GET_VARS['durations']!='' || $HTTP_GET_VARS['departure']!=''){
		if($HTTP_GET_VARS['durations']!='')
		{
			$durations_where_condition = " and p.products_durations = '" . $HTTP_GET_VARS['durations'] . "'";
		}
		if($HTTP_GET_VARS['departure']!='')
		{
			$departure_where_condition = " and p.departure_city_id = '" . $HTTP_GET_VARS['departure'] . "'";
		}
		if($HTTP_GET_VARS['cPath'] != '')
		{
			$cpath_where_condition = " and p2c.categories_id = '" . (int)$current_category_id . "'";
		}
      $products_query = tep_db_query("select p.featured_products, p.products_model, p.products_id, pd.products_name, p.products_is_regular_tour, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status,  p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id ".$durations_where_condition." ".$departure_where_condition." ".$cpath_where_condition." order by pd.products_name");
	} else {
      $products_query = tep_db_query("select p.featured_products, p.products_model, p.products_id, pd.products_name, p.products_is_regular_tour, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by pd.products_name");
    }
    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

// Get categories_id for product if search
      if (isset($HTTP_GET_VARS['search'])) $cPath = $products['categories_id'];
	  if(isset($HTTP_GET_VARS['durations'])) $cPath = $products['categories_id'];
	  
      if ( (!isset($HTTP_GET_VARS['pID']) && !isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['pID']) && ($HTTP_GET_VARS['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
// find out the rating average from customer reviews
        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);
        $pInfo_array = array_merge($products, $reviews);
        $pInfo = new objectInfo($pInfo_array);
      }

      if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent" width="5%"><?php echo $products['products_id'];?></td>
				<td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $products['products_name']; ?></td>
                <td class="dataTableContent" align="center">
<?php
      if ($products['products_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
<td class="<?php echo $current_row; ?>" align="center">

<?php

      if ($products['featured_products'] == '1') {

        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflagf&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';

      } else {

        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflagf&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);

      }

?>


</td>
                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $cPath_back = '';
    if (sizeof($cPath_array) > 0) {
      for ($i=0, $n=sizeof($cPath_array)-1; $i<$n; $i++) {
        if (empty($cPath_back)) {
          $cPath_back .= $cPath_array[$i];
        } else {
          $cPath_back .= '_' . $cPath_array[$i];
        }
      }
    }

    $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                   
				   <!-- amit added here start 18/02/2006-->
				   <?php
					
					// fix here
					
					if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
					
					?>
					
										<td  class="smallText"></br><?php if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($HTTP_GET_VARS['search'])) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category_ACD') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>' ; 
																			//////scs added: export category //////////
																			echo "</br>";
																			
																			if( (isset($_GET['cPath'])) || (isset($_GET['cID'])))
																			{
																				/*
																				if(isset($_GET['cID']) && $_GET['cPath'] != '') {
																				$_GET['cPath'] = $_GET['cPath']."_".$_GET['cID'];
																				}
																			
																				if ( isset($_GET['cID']) &&  !isset($_GET['cPath']) ){
																				$_GET['cPath'] = $_GET['cID'];
																				}
																				*/
																				
																				$path = explode("_",$_GET['cPath']); 
																				
																				if($path[2] != ""){
																				$path[1] = $path[2];
																				//$path[0] = $path[1]
																				}
																				
																			if ($categories_count != 0 ){
																				echo '&nbsp;<a href="' . tep_href_link(FILENAME_EXPORT, 'download=stream&dltype=category&cPath='.$_GET['cPath'].'') . '">' . tep_image_button('button_export_category.gif', 'Export catergory') . '</a>';  
																				}
																				?>
																			<? }
																			else
																			{
																				echo '&nbsp;<a href="' . tep_href_link(FILENAME_EXPORT, 'download=stream&dltype=category') . '">' . tep_image_button('button_export_category.gif', 'Export catergory') . '</a>';  ?>
																			<? }
																			///////export category end /////////
																			//////////scs added:  export  Product///////////
																			if(isset($_GET['cPath']) && $_GET['cPath'] != '')
																			{
																				if( $products_count > 0 || $categories_count > 0) {
																				echo '&nbsp;<a href="' . tep_href_link(FILENAME_EXPORT, 'download=stream&dltype=products&cPath='.$_GET['cPath'].'') . '">' . tep_image_button('button_export_product.gif', 'Export Product') . '</a>'; 
																				}
																			 }
																			else
																			{
																				echo '&nbsp;<a href="' . tep_href_link(FILENAME_EXPORT, 'download=stream&dltype=products') . '">' . tep_image_button('button_export_product.gif', 'Export Product') . '</a>';  
																			 }
																			
																			//////////scs added:   exaport product	 
																			
																			
										
										
					
					
					
					} else {
					
					?></td>

				   <!-- amit added here end 18/02/2006-->
				   
				    <td align="right" class="smallText"><?php if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($HTTP_GET_VARS['search'])) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</td>
                  <!-- amit added code -->
				  
				  <?php

						} // category description is on

					?>

                   <!-- amit added code -->
				  
				  
				  </tr>
				  
				  <!--  ////////scs for upload cvs file BOF/////////// -->
			  <tr>
			  <td  align="right" colspan="3" class="smallText" ></br>
			   <FORM ENCTYPE="multipart/form-data" ACTION="exportcsv.php?split=0" METHOD=POST>
              <p><p>
              <div align = "left">
                <p><b>Import category:</b></p>
                
                  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000000">
            		<INPUT TYPE="hidden" name="importdata"	value="uploadcategory">
                  <input name="usrfl" type="file" size="35"><input type="submit" name="buttoninsert" value="Import"><br>
                </p> 
              </div>

            </form>
			</td>
			  </tr>
			   <!--  ////////scs for upload cvs file added EOF/////////// -->


					    <!--  ////////scs for upload cvs file BOF/////////// -->
			  <tr>
			  <td  align="right" colspan="3" class="smallText" ></br>
			   <FORM ENCTYPE="multipart/form-data" ACTION="exportcsv.php?split=0" METHOD=POST>
              <p><p>
              <div align = "left">
                <p><b>Import Products:</b></p>
                
                  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000000">
            		<INPUT TYPE="hidden" name="importdata"	value="uploadproductcat">
                  <input name="usrfl" type="file" size="35"><input type="submit" name="buttoninsert" value="Import"><br>
                </p> 
              </div>

            </form>
			</td>
			  </tr>
			   <!--  ////////scs for upload cvs file added EOF/////////// -->
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($action) {
      case 'new_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('newcategory', FILENAME_CATEGORIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
        }

        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
        $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'edit_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_EDIT_INTRO);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', tep_get_category_name($cInfo->categories_id, $languages[$i]['id']));
        }

        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->categories_image . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image'));
        $contents[] = array('text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
        if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
        $contents[] = array('text' => '<br><b>' . $pInfo->products_name . '</b>');

        $product_categories_string = '';
        $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
        for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
          $category_path = '';
          for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
            $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
          $category_path = substr($category_path, 0, -16);
          $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
        }
        $product_categories_string = substr($product_categories_string, 0, -4);

        $contents[] = array('text' => '<br>' . $product_categories_string);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'copy_to':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

        $contents = array('form' => tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES . '<br>' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
// BOF: WebMakers.com Added: Attributes Copy
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
        // only ask about attributes if they exist
        if (tep_has_product_attributes($pInfo->products_id)) {
          $contents[] = array('text' => '<br>' . TEXT_COPY_ATTRIBUTES_ONLY);
          $contents[] = array('text' => '<br>' . TEXT_COPY_ATTRIBUTES . '<br>' . tep_draw_radio_field('copy_attributes', 'copy_attributes_yes', true) . ' ' . TEXT_COPY_ATTRIBUTES_YES . '<br>' . tep_draw_radio_field('copy_attributes', 'copy_attributes_no') . ' ' . TEXT_COPY_ATTRIBUTES_NO);
          $contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '10'));
          $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
        }
// EOF: WebMakers.com Added: Attributes Copy

        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;

/////////////////////////////////////////////////////////////////////
// WebMakers.com Added: Copy Attributes Existing Product to another Existing Product
      case 'copy_product_attributes':
        $copy_attributes_delete_first='1';
        $copy_attributes_duplicates_skipped='1';
        $copy_attributes_duplicates_overwrite='0';

        if (DOWNLOAD_ENABLED == 'true') {
          $copy_attributes_include_downloads='1';
          $copy_attributes_include_filename='1';
        } else {
          $copy_attributes_include_downloads='0';
          $copy_attributes_include_filename='0';
        }

        $heading[] = array('text' => '<b>' . 'Copy Attributes to another product' . '</b>');
        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=create_copy_product_attributes&cPath=' . $cPath . '&pID=' . $pInfo->products_id) . tep_draw_hidden_field('products_id', $pInfo->products_id) . tep_draw_hidden_field('products_name', $pInfo->products_name));
        $contents[] = array('text' => '<br>Copying Attributes from #' . $pInfo->products_id . '<br><b>' . $pInfo->products_name . '</b>');
        $contents[] = array('text' => 'Copying Attributes to #&nbsp;' . tep_draw_input_field('copy_to_products_id', $copy_to_products_id, 'size="3"'));
        $contents[] = array('text' => '<br>Delete ALL Attributes and Downloads before copying&nbsp;' . tep_draw_checkbox_field('copy_attributes_delete_first',$copy_attributes_delete_first, 'size="2"'));
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
        $contents[] = array('text' => '<br>' . 'Otherwise ...');
        $contents[] = array('text' => 'Duplicate Attributes should be skipped&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_skipped',$copy_attributes_duplicates_skipped, 'size="2"'));
        $contents[] = array('text' => '&nbsp;&nbsp;&nbsp;Duplicate Attributes should be overwritten&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_overwrite',$copy_attributes_duplicates_overwrite, 'size="2"'));
        if (DOWNLOAD_ENABLED == 'true') {
          $contents[] = array('text' => '<br>Copy Attributes with Downloads&nbsp;' . tep_draw_checkbox_field('copy_attributes_include_downloads',$copy_attributes_include_downloads, 'size="2"'));
          // Not used at this time - download name copies if download attribute is copied
          // $contents[] = array('text' => '&nbsp;&nbsp;&nbsp;Include Download Filenames&nbsp;' . tep_draw_checkbox_field('copy_attributes_include_filename',$copy_attributes_include_filename, 'size="2"'));
        }
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . PRODUCT_NAMES_HELPER);
        if ($pID) {
          $contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER);
        } else {
          $contents[] = array('align' => 'center', 'text' => '<br>Select a product for display');
        }
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', 'Copy Attribtues') . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
// WebMakers.com Added: Copy Attributes Existing Product to All Products in Category
      case 'copy_product_attributes_categories':
        $copy_attributes_delete_first='1';
        $copy_attributes_duplicates_skipped='1';
        $copy_attributes_duplicates_overwrite='0';

        if (DOWNLOAD_ENABLED == 'true') {
          $copy_attributes_include_downloads='1';
          $copy_attributes_include_filename='1';
        } else {
          $copy_attributes_include_downloads='0';
          $copy_attributes_include_filename='0';
        }

        $heading[] = array('text' => '<b>' . 'Copy Product Attributes to Category ...' . '</b>');
        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=create_copy_product_attributes_categories&cPath=' . $cPath . '&cID=' . $cID . '&make_copy_from_products_id=' . $copy_from_products_id));
        $contents[] = array('text' => 'Copy Product Attributes from Product ID#&nbsp;' . tep_draw_input_field('make_copy_from_products_id', $make_copy_from_products_id, 'size="3"'));
        $contents[] = array('text' => '<br>Copying to all products in Category ID#&nbsp;' . $cID . '<br>Category Name: <b>' . tep_get_category_name($cID, $languages_id) . '</b>');
        $contents[] = array('text' => '<br>Delete ALL Attributes and Downloads before copying&nbsp;' . tep_draw_checkbox_field('copy_attributes_delete_first',$copy_attributes_delete_first, 'size="2"'));
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
        $contents[] = array('text' => '<br>' . 'Otherwise ...');
        $contents[] = array('text' => 'Duplicate Attributes should be skipped&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_skipped',$copy_attributes_duplicates_skipped, 'size="2"'));
        $contents[] = array('text' => '&nbsp;&nbsp;&nbsp;Duplicate Attributes should be overwritten&nbsp;' . tep_draw_checkbox_field('copy_attributes_duplicates_overwrite',$copy_attributes_duplicates_overwrite, 'size="2"'));
        if (DOWNLOAD_ENABLED == 'true') {
          $contents[] = array('text' => '<br>Copy Attributes with Downloads&nbsp;' . tep_draw_checkbox_field('copy_attributes_include_downloads',$copy_attributes_include_downloads, 'size="2"'));
          // Not used at this time - download name copies if download attribute is copied
          // $contents[] = array('text' => '&nbsp;&nbsp;&nbsp;Include Download Filenames&nbsp;' . tep_draw_checkbox_field('copy_attributes_include_filename',$copy_attributes_include_filename, 'size="2"'));
        }
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . PRODUCT_NAMES_HELPER);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', 'Copy Attribtues') . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>');
            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));
            if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
            $contents[] = array('text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->categories_image);
            $contents[] = array('text' => '<br>' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
            if ($cInfo->childs_count==0 and $cInfo->products_count >= 1) {
// WebMakers.com Added: Copy Attributes Existing Product to All Existing Products in Category
              $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
              if ($cID) {
                $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID . '&action=copy_product_attributes_categories') . '">' . 'Copy Product Attributes to <br>ALL products in Category: ' . tep_get_category_name($cID, $languages_id) . '<br>' . tep_image_button('button_copy_to.gif', 'Copy Attributes') . '</a>');
              } else {
                $contents[] = array('align' => 'center', 'text' => '<br>Select a Category to copy attributes to');
              }
            }
          } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
            $heading[] = array('text' => '<b>' . tep_get_products_name($pInfo->products_id, $languages_id) . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>');
            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->products_date_added));
            if (tep_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->products_last_modified));
            if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->products_date_available));
            $contents[] = array('text' => '<br>' . tep_info_image($pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>' . $pInfo->products_image);
            $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $currencies->format($pInfo->products_price) . '<br>' . TEXT_PRODUCTS_TYPE . ' ' . $pInfo->products_is_regular_tour);
            $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
// WebMakers.com Added: Copy Attributes Existing Product to another Existing Product
            $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_product_attributes') . '">' . 'Products Attributes Copier:<br>' . tep_image_button('button_copy_to.gif', 'Copy Attributes') . '</a>');
            if ($pID) {
              $contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '10'));
            } else {
              $contents[] = array('align' => 'center', 'text' => '<br>Select a product to display attributes');
            }
          }
        } else { // create category/product info
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS);
        }
        break;
    }

    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
      echo '            <td width="25%" valign="top">' . "\n";

      $box = new box;
      echo $box->infoBox($heading, $contents);

      echo '            </td>' . "\n";
    }
?>
          </tr>
        </table></td>
      </tr>
    </table>
<?php
  }
?>
    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php  require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
