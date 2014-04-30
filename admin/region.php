<?php
/*
  $Id: regions.php,v 1.2 2004/03/29 00:18:17 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('region');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
//Added for Categories Description 1.5
  require('includes/functions/regions_description.php');
//End Categories Description 1.5

 require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
         
		  if (isset($HTTP_GET_VARS['cID'])) {
		   	 tep_db_query("update " . TABLE_REGIONS . " set regions_status = '".$HTTP_GET_VARS['flag']."', last_modified = now() where regions_id = '" . (int)$HTTP_GET_VARS['cID'] . "'");
			
		  }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('regions');
            tep_reset_cache_block('also_purchased');
          }
        }
       
		tep_redirect(tep_href_link(FILENAME_REGIONS,tep_get_all_get_params(array('action','flag','cID','cPath')). 'cPath=' . $HTTP_GET_VARS['cPath'] . '&cID=' . $HTTP_GET_VARS['cID']));
        break;

//Added for regions Description 1.5
      case 'new_category':
      case 'edit_category':
        if (ALLOW_CATEGORY_DESCRIPTIONS == 'true')
          $HTTP_GET_VARS['action']=$HTTP_GET_VARS['action'] . '_ACD';
        break;
//End regions Description 1.5

      case 'insert_category':
      case 'update_category':

//Added for regions Description 1.5

	
 if ( ($HTTP_POST_VARS['edit_x']) || ($HTTP_POST_VARS['edit_y']) ) {
          $HTTP_GET_VARS['action'] = 'edit_category_ACD';
        } else {
//End regions Description 1.5
$languages = tep_get_languages();
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
		  
		$language_id = $languages[$i]['id'];
		
		if (isset($HTTP_POST_VARS['regions_id'])){
			$add_exist_query = " and r.regions_id != '".tep_db_prepare_input($HTTP_POST_VARS['regions_id'])."'";
		} else {
			$add_exist_query = "";
		}
		$if_exist_query = tep_db_query("select r.countries_id from ".TABLE_REGIONS." r, ".TABLE_REGIONS_DESCRIPTION." rd where r.regions_id = rd.regions_id and rd.regions_name='".tep_db_prepare_input($HTTP_POST_VARS['regions_name'][$language_id])."'  and rd.language_id = '".$language_id."' ".$add_exist_query." ");
		
		/*echo tep_db_num_rows($if_exist_query);
		exit;*/
		if(tep_db_num_rows($if_exist_query)>0){
		$messageStack->add('Region \''.tep_db_prepare_input($HTTP_POST_VARS['regions_name'][$language_id]).'\' already exist to this country. Try with another Region.', 'error');
		$error='true';
		}
		
	}
	if($error!='true'){
        if (isset($HTTP_POST_VARS['regions_id'])) $regions_id = tep_db_prepare_input($HTTP_POST_VARS['regions_id']);

//Added for regions Description 1.5
        if ($regions_id == '') {
           $regions_id = tep_db_prepare_input($HTTP_GET_VARS['cID']);
         }
//End regions Description 1.5

        $sort_order = tep_db_prepare_input($HTTP_POST_VARS['sort_order']);
		$urlname = tep_db_prepare_input($HTTP_POST_VARS['regions_urlname']);
		
		if(!tep_not_null($urlname)) $urlname = seo_generate_urlname($HTTP_POST_VARS['regions_name']['1']);

          else $urlname = seo_generate_urlname($urlname);



          $sql_data_array = array('sort_order' => $sort_order,

                                  'regions_urlname' => $urlname,
								  
								  'countries_id' => (int)$HTTP_POST_VARS['countries_id'],
								  
								  'regions_status' => tep_db_prepare_input($HTTP_POST_VARS['regions_status']));


        //$sql_data_array = array('sort_order' => $sort_order);

        if ($action == 'insert_category') {
          $insert_sql_data = array('parent_id' => $current_category_id,
                                   'date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_REGIONS, $sql_data_array);

          $regions_id = tep_db_insert_id();
        } elseif ($action == 'update_category') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_REGIONS, $sql_data_array, 'update', "regions_id = '" . (int)$regions_id . "'");
        }

        
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $regions_name_array = $HTTP_POST_VARS['regions_name'];

          $language_id = $languages[$i]['id'];

          $sql_data_array = array('regions_name' => tep_db_prepare_input($regions_name_array[$language_id]));

//Added for Categories Description 1.5
            if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
              $sql_data_array = array('regions_name' => tep_db_prepare_input($HTTP_POST_VARS['regions_name'][$language_id]),
                                      'regions_heading_title' => tep_db_prepare_input($HTTP_POST_VARS['regions_heading_title'][$language_id]),
                                      'regions_description' => tep_db_prepare_input($HTTP_POST_VARS['regions_description'][$language_id]),
									  'regions_seo_description' => tep_db_prepare_input($HTTP_POST_VARS['regions_seo_description']),
									  'regions_logo_alt_tag' => tep_db_prepare_input($HTTP_POST_VARS['regions_logo_alt_tag']),
									  'regions_first_sentence' => tep_db_prepare_input($HTTP_POST_VARS['regions_first_sentence']),
                                      'regions_head_title_tag' => tep_db_prepare_input($HTTP_POST_VARS['regions_head_title_tag'][$language_id]),
                                      'regions_head_desc_tag' => tep_db_prepare_input($HTTP_POST_VARS['regions_head_desc_tag'][$language_id]),
                                      'regions_head_keywords_tag' => tep_db_prepare_input($HTTP_POST_VARS['regions_head_keywords_tag'][$language_id]));
        }
//End Categories Description 1.5

          if ($action == 'insert_category') {
            $insert_sql_data = array('regions_id' => $regions_id,
                                     'language_id' => $languages[$i]['id']);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_REGIONS_DESCRIPTION, $sql_data_array);
          } elseif ($action == 'update_category') {
            tep_db_perform(TABLE_REGIONS_DESCRIPTION, $sql_data_array, 'update', "regions_id = '" . (int)$regions_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          }
        }


//Code commented out for Categories Description 1.5
        if($_FILES['regions_image']['name']!=''){
			//delete existing image file first then upload new file
		  if($action == 'update_category'){
			$get_image_name_query = tep_db_query("select regions_image from ".TABLE_REGIONS." where regions_id = '".(int)$regions_id."'");
			$row_image_name = tep_db_fetch_array($get_image_name_query);
			if(file_exists(DIR_FS_CATALOG_IMAGES.$row_image_name['regions_image'])){
				@unlink(DIR_FS_CATALOG_IMAGES.$row_image_name['regions_image']);
			}
		  }
			$uploadfile = DIR_FS_CATALOG_IMAGES.$regions_id.'_'.basename($_FILES['regions_image']['name']);
			if (move_uploaded_file($_FILES['regions_image']['tmp_name'],$uploadfile)) {
			//if ($regions_image = new upload('regions_image', DIR_FS_CATALOG_IMAGES)) {
			  tep_db_query("update " . TABLE_REGIONS . " set regions_image = '" . $regions_id.'_'.basename($_FILES['regions_image']['name']) . "' where regions_id = '" . (int)$regions_id . "'");
			}
		  }
//Added the following to replacce above code
          if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
            //tep_db_query("update " . TABLE_REGIONS . " set regions_image = '" . $HTTP_POST_VARS['regions_image'] . "' where regions_id = '" .  tep_db_input($regions_id) . "'");
            //$regions_image = '';
      } else {
        if ($regions_image = new upload('regions_image', DIR_FS_CATALOG_IMAGES)) {
          tep_db_query("update " . TABLE_REGIONS . " set regions_image = '" . tep_db_input($regions_image->filename) . "' where regions_id = '" . (int)$regions_id . "'");
        }
       }
//End Categories Description 1.5

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('regions');
          tep_reset_cache_block('also_purchased');
        }

        tep_redirect(tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&cID=' . $regions_id));

//Added for Categories Description 1.5
        }
		
		}//end if
//End Categories Description 1.5

        break;
      case 'delete_category_confirm':
        if (isset($HTTP_POST_VARS['regions_id'])) {
          $regions_id = tep_db_prepare_input($HTTP_POST_VARS['regions_id']);

          $regions = tep_get_category_tree($regions_id, '', '0', '', true);
          $products = array();
          $products_delete = array();

          

          reset($products);
          while (list($key, $value) = each($products)) {
            $category_ids = '';

            for ($i=0, $n=sizeof($value['regions']); $i<$n; $i++) {
              $category_ids .= "'" . (int)$value['regions'][$i] . "', ";
            }
            $category_ids = substr($category_ids, 0, -2);

           
          }

// removing regions can be a lengthy process
          tep_set_time_limit(0);
          for ($i=0, $n=sizeof($regions); $i<$n; $i++) {
            tep_remove_region($regions[$i]['id']);
			$get_image_name_query = tep_db_query("select regions_image from ".TABLE_REGIONS." where regions_id = '".(int)$regions[$i]['id']."'");
			$row_image_name = tep_db_fetch_array($get_image_name_query);
			if(file_exists(DIR_FS_CATALOG_IMAGES.$row_image_name['regions_image'])){
				@unlink(DIR_FS_CATALOG_IMAGES.$row_image_name['regions_image']);
			}
          }

          reset($products_delete);
          while (list($key) = each($products_delete)) {
            tep_remove_product($key);
			$get_image_name_query = tep_db_query("select regions_image from ".TABLE_REGIONS." where regions_id = '".(int)$key."'");
			$row_image_name = tep_db_fetch_array($get_image_name_query);
			if(file_exists(DIR_FS_CATALOG_IMAGES.$row_image_name['regions_image'])){
				@unlink(DIR_FS_CATALOG_IMAGES.$row_image_name['regions_image']);
			}
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('regions');
          tep_reset_cache_block('also_purchased');
        }

        tep_redirect(tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath));
        break;
      case 'delete_product_confirm':
        if (isset($HTTP_POST_VARS['products_id']) && isset($HTTP_POST_VARS['product_regions']) && is_array($HTTP_POST_VARS['product_regions'])) {
          $product_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $product_regions = $HTTP_POST_VARS['product_regions'];

          if ($product_regions['total'] == '0') {
            tep_remove_product($product_id);
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('regions');
          tep_reset_cache_block('also_purchased');
        }

        tep_redirect(tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath));
        break;
      case 'move_category_confirm':
        if (isset($HTTP_POST_VARS['regions_id']) && ($HTTP_POST_VARS['regions_id'] != $HTTP_POST_VARS['move_to_category_id'])) {
          $regions_id = tep_db_prepare_input($HTTP_POST_VARS['regions_id']);
          $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

          $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));

          if (in_array($regions_id, $path)) {
            $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

            tep_redirect(tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&cID=' . $regions_id));
          } else {
            tep_db_query("update " . TABLE_REGIONS . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where regions_id = '" . (int)$regions_id . "'");

            if (USE_CACHE == 'true') {
              tep_reset_cache_block('regions');
              tep_reset_cache_block('also_purchased');
            }

            tep_redirect(tep_href_link(FILENAME_REGIONS, 'cPath=' . $new_parent_id . '&cID=' . $regions_id));
          }
        }

        break;
      case 'move_product_confirm':
        $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
        $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_category_id']);

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('regions');
          tep_reset_cache_block('also_purchased');
        }

        tep_redirect(tep_href_link(FILENAME_REGIONS, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
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
      case 'create_copy_product_attributes_regions':
  // $products_id_to= $regions_products_copying['products_id'];
  // $products_id_from = $make_copy_from_products_id;
  //  echo 'Copy from products_id# ' . $make_copy_from_products_id . ' Copy to all products in category: ' . $cID . '<br>';
       
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

					
          $products_date_available = tep_db_prepare_input($HTTP_POST_VARS['products_date_available']);

          $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

			
		 $urlname = tep_db_prepare_input($HTTP_POST_VARS['products_urlname']);
         if(!tep_not_null($urlname)) $urlname = seo_generate_urlname(tep_db_prepare_input($HTTP_POST_VARS['products_name'][1]));

          else $urlname = seo_generate_urlname($urlname);
		  
		  
		   $sql_data_array = array('products_is_regular_tour' => tep_db_prepare_input($HTTP_POST_VARS['products_is_regular_tour']),
		   						  'departure_city_id' => tep_db_prepare_input($HTTP_POST_VARS['departure_city_id']),
								  'products_model' => tep_db_prepare_input($HTTP_POST_VARS['products_model']),
                                  'products_urlname' => $urlname,
								  'region_id' => tep_db_prepare_input($HTTP_POST_VARS['region_id']),
								  'products_price' => tep_db_prepare_input($HTTP_POST_VARS['products_price']),
                                  'products_date_available' => $products_date_available,
                                  'products_durations' => tep_db_prepare_input($HTTP_POST_VARS['products_durations']),
                                  'products_status' => tep_db_prepare_input($HTTP_POST_VARS['products_status']),
                                  'products_tax_class_id' => tep_db_prepare_input($HTTP_POST_VARS['products_tax_class_id']),
								  'agency_id' => tep_db_prepare_input($HTTP_POST_VARS['agency_id']),
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
            
          } 
		  elseif ($action == 'update_product') 
		  {
            $update_sql_data = array('products_last_modified' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $update_sql_data);

            tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
            MCache::update_product($products_id);//MCache update
          }
			
					
		
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
                                    'products_url' => tep_db_prepare_input($HTTP_POST_VARS['products_url'][$language_id]),
		                    'products_head_title_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_title_tag'][$language_id]),
         'products_head_desc_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_desc_tag'][$language_id]),
         'products_head_keywords_tag' => tep_db_prepare_input($HTTP_POST_VARS['products_head_keywords_tag'][$language_id]));

            if ($action == 'insert_product') {
              $insert_sql_data = array('products_id' => $products_id,
                                       'language_id' => $language_id);

              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

              tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
              MCache::update_product($products_id);//MCache update
            } elseif ($action == 'update_product') {
              tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
               MCache::update_product($products_id);//MCache update
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
            tep_reset_cache_block('regions');
            tep_reset_cache_block('also_purchased');
          }

          tep_redirect(tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&pID=' . $products_id));
        }
        break;
      case 'copy_to_confirm':
        if (isset($HTTP_POST_VARS['products_id']) && isset($HTTP_POST_VARS['regions_id'])) {
          $products_id = tep_db_prepare_input($HTTP_POST_VARS['products_id']);
          $regions_id = tep_db_prepare_input($HTTP_POST_VARS['regions_id']);

          if ($HTTP_POST_VARS['copy_as'] == 'link') {
            if ($regions_id != $current_category_id) {
              //nothing
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
            	MCache::update_product($products_id);//MCache update
            }

           
// BOF: WebMakers.com Added: Attributes Copy on non-linked
            $products_id_from=tep_db_input($products_id);
            $products_id_to= $dup_products_id;
            $products_id = $dup_products_id;
            MCache::update_product($dup_products_id);//MCache update
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
            tep_reset_cache_block('regions');
            tep_reset_cache_block('also_purchased');
          }
        }

        tep_redirect(tep_href_link(FILENAME_REGIONS, 'cPath=' . $regions_id . '&pID=' . $products_id));
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
  
  
  //AMIT added for assign region to country
  
  
   function sbs_get_countries($countries_id = '', $with_iso_codes = false) {
    $countries_array = array();
    if ($countries_id) {
      if ($with_iso_codes) {
        $countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "' order by countries_name");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name'],
                                 'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
                                 'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
      } else {
        $countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "'");
        $countries_values = tep_db_fetch_array($countries);
        $countries_array = array('countries_name' => $countries_values['countries_name']);
      }
    } else {
      $countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
      while ($countries_values = tep_db_fetch_array($countries)) {
        $countries_array[] = array('countries_id' => $countries_values['countries_id'],
                                   'countries_name' => $countries_values['countries_name']);
      }
    }

    return $countries_array;
  }
  
  
  
   $countries_array = array(array('id' => '', 'text' => PLEASE_SELECT_COUNTRY));
   $countries = sbs_get_countries();
   $size = sizeof($countries);
   for ($i=0; $i<$size; $i++) {
     $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
   }

//AMIT added for assign region to country
?>


<?php
$go_back_to=$REQUEST_URI;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>

<script type="text/javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>

<?php if ((HTML_AREA_WYSIWYG_DISABLE == 'Enable') or (HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Enable')) { ?>
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
include(DIR_WS_INCLUDES . 'javascript/' . 'webmakers_added_js.php');

?>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('region');
$list = $listrs->showRemark();
?>
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
	//echo "select cd.regions_seo_description, cd.regions_logo_alt_tag, cd.regions_first_sentence, c.regions_status, c.regions_id, c.regions_urlname, cd.regions_name, cd.regions_heading_title, cd.regions_description, cd.regions_head_title_tag, cd.regions_head_desc_tag, cd.regions_head_keywords_tag, c.regions_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_REGIONS . " c, " . TABLE_REGIONS_DESCRIPTION . " cd where c.regions_id = '" . $HTTP_GET_VARS['cID'] . "' and c.regions_id = cd.regions_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.regions_name";
      $regions_query = tep_db_query("select cd.regions_seo_description, cd.regions_logo_alt_tag, cd.regions_first_sentence, c.regions_status, c.regions_id, c.regions_urlname, cd.regions_name, cd.regions_heading_title, cd.regions_description, cd.regions_head_title_tag, cd.regions_head_desc_tag, cd.regions_head_keywords_tag, c.regions_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.countries_id from " . TABLE_REGIONS . " c, " . TABLE_REGIONS_DESCRIPTION . " cd where c.regions_id = '" . $HTTP_GET_VARS['cID'] . "' and c.regions_id = cd.regions_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.regions_name");
      $category = tep_db_fetch_array($regions_query);

      $cInfo = new objectInfo($category);
    } elseif ($HTTP_POST_VARS) {
      $cInfo = new objectInfo($HTTP_POST_VARS);
      $regions_name = $HTTP_POST_VARS['regions_name'];
      $regions_heading_title = $HTTP_POST_VARS['regions_heading_title'];
      $regions_description = $HTTP_POST_VARS['regions_description'];
      $regions_head_title_tag = $HTTP_POST_VARS['regions_head_title_tag'];
      $regions_head_desc_tag = $HTTP_POST_VARS['regions_head_desc_tag'];
      $regions_head_keywords_tag = $HTTP_POST_VARS['regions_head_keywords_tag'];
      $regions_url = $HTTP_POST_VARS['regions_url'];
	  $regions_status = $HTTP_POST_VARS['regions_status'];
	  $countries_id = $HTTP_POST_VARS['countries_id'];
    } else {
      $cInfo = new objectInfo(array());
    }

    $languages = tep_get_languages();
	
	if (!isset($cInfo->regions_status)) $cInfo->regions_status = '1';
    switch ($cInfo->regions_status) {
      case '0': $cin_status = false; $cout_status = true; break;
      case '1':
      default: $cin_status = true; $cout_status = false;
    }

    $text_new_or_edit = ($HTTP_GET_VARS['action']=='new_category_ACD') ? TEXT_INFO_HEADING_NEW_REGION : TEXT_INFO_HEADING_EDIT_REGION;
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
      <tr><?php echo tep_draw_form('new_category', FILENAME_REGIONS, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID'] . '&action=new_category_preview', 'post', 'enctype="multipart/form-data"'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
	   
	   <tr>
	   <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
       </tr>
	   <tr>
            <td class="main"><?php echo TEXT_REGION_STATUS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('regions_status', '1', $cin_status) . '&nbsp;' . TEXT_REGION_AVAILABLE . '&nbsp;' . tep_draw_radio_field('regions_status', '0', $cout_status) . '&nbsp;' . TEXT_REGION_NOT_AVAILABLE; ?></td>
       </tr>
	   <tr>
	   <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
       </tr>
	   
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_EDIT_REGIONS_NAME; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('regions_name[' . $languages[$i]['id'] . ']', (($regions_name[$languages[$i]['id']]) ? stripslashes($regions_name[$languages[$i]['id']]) : tep_get_region_name($cInfo->regions_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  
		    <tr>
            <td class="main"><?php echo TEXT_REGION_COUNTRY; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('countries_id', $countries_array, $cInfo->countries_id, $parameters); ?></td>
       		</tr>
		  
		  
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  
		  
		  
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_EDIT_REGIONS_HEADING_TITLE; ?></td>
            <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('regions_heading_title[' . $languages[$i]['id'] . ']', (($regions_heading_title[$languages[$i]['id']]) ? stripslashes($regions_heading_title[$languages[$i]['id']]) : tep_get_region_heading_title($cInfo->regions_id, $languages[$i]['id']))); ?></td>
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

            <td class="main">Region URLname:</td>

            <!--<td class="main"><?php //echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('regions_urlname', $cInfo->regions_urlname, 'size="80"'); ?></td>-->
			<td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;'. tep_draw_input_field('regions_urlname', $cInfo->regions_urlname, 'size="50"'); ?></td>
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
           <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_REGIONS_DESCRIPTION; ?></td>
           <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;
		   <?php echo tep_draw_textarea_field('regions_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($regions_description[$languages[$i]['id']]) ? stripslashes($regions_description[$languages[$i]['id']]) : tep_get_region_description($cInfo->regions_id, $languages[$i]['id']))); ?></td>


            </tr>

<?php
    }
?>

          <tr>
            <td colspan="2" class="main"><hr></td> </td>
          </tr>


              <tr>
			    <td class="main" valign="top"><?php  echo TEXT_EDIT_REGIONS_SEO_DESCRIPTION; ?></td>
                <td class="main" valign="top">
				<?php echo tep_draw_separator('pixel_trans.gif', '32', '15') . '&nbsp;' . tep_draw_textarea_field('regions_seo_description', 'soft', '70', '10', $cInfo->regions_seo_description); ?></td>
             <tr>


		   <tr>
            <td colspan="2" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td> </td>
           </tr>


              <tr>
			    <td class="main" valign="top"><?php  echo TEXT_EDIT_REGIONS_LOGO_ALT_TAG; ?></td>
                <td class="main" valign="top">
				<?php echo tep_draw_separator('pixel_trans.gif', '32', '15') . '&nbsp;' .  tep_draw_input_field('regions_logo_alt_tag', $cInfo->regions_logo_alt_tag, 'size="30"') ; ?></td>
             <tr>
			 
		   <tr>
            <td colspan="2" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td> </td>
           </tr>


              <tr>
			    <td class="main" valign="top"><?php  echo TEXT_EDIT_REGIONS_FIRST_SENTENCE; ?></td>
                <td class="main" valign="top">
				<?php echo tep_draw_separator('pixel_trans.gif', '32', '15') . '&nbsp;' . tep_draw_textarea_field('regions_first_sentence', 'soft', '70', '10', $cInfo->regions_first_sentence); ?></td>
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
			  <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_REGIONS_TITLE_TAG; ?></td>
             <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;
			  <?php echo tep_draw_textarea_field('regions_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($regions_head_title_tag[$languages[$i]['id']]) ? stripslashes($regions_head_title_tag[$languages[$i]['id']]) : tep_get_region_head_title_tag($cInfo->regions_id, $languages[$i]['id']))); ?></td>
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
			    <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_REGIONS_DESC_TAG; ?></td>
                <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;
			    <?php echo tep_draw_textarea_field('regions_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($regions_head_desc_tag[$languages[$i]['id']]) ? stripslashes($regions_head_desc_tag[$languages[$i]['id']]) : tep_get_region_head_desc_tag($cInfo->regions_id, $languages[$i]['id']))); ?></td>
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
			     <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_REGIONS_KEYWORDS_TAG; ?></td>
	            <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;
			   <?php echo tep_draw_textarea_field('regions_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($regions_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($regions_head_keywords_tag[$languages[$i]['id']]) : tep_get_region_head_keywords_tag($cInfo->regions_id, $languages[$i]['id']))); ?></td>
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
            <td class="main"><?php echo TEXT_EDIT_REGIONS_IMAGE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('regions_image') . '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $cInfo->regions_image . tep_draw_hidden_field('regions_previous_image', $cInfo->regions_image); ?></td>
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
        <td class="main" align="right"><?php echo tep_draw_hidden_field('regions_date_added', (($cInfo->date_added) ? $cInfo->date_added : date('Y-m-d'))) . tep_draw_hidden_field('parent_id', $cInfo->parent_id) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
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
          editor_generate('regions_description[<?php echo $languages[$i]['id']; ?>]',config);
     <?php } } ?>
      </script>

<?php
  //----- new_category_preview (active when ALLOW_CATEGORY_DESCRIPTIONS is 'true') -----
  } elseif ($HTTP_GET_VARS['action'] == 'new_category_preview') {
    if ($HTTP_POST_VARS) {
      $cInfo = new objectInfo($HTTP_POST_VARS);
      $regions_name = $HTTP_POST_VARS['regions_name'];
      $regions_heading_title = $HTTP_POST_VARS['regions_heading_title'];
      $regions_description = $HTTP_POST_VARS['regions_description'];
      $regions_head_title_tag = $HTTP_POST_VARS['regions_head_title_tag'];
      $regions_head_desc_tag = $HTTP_POST_VARS['regions_head_desc_tag'];
      $regions_head_keywords_tag = $HTTP_POST_VARS['regions_head_keywords_tag'];

// copy image only if modified
        $regions_image = new upload('regions_image');
        $regions_image->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($regions_image->parse() && $regions_image->save()) {
          $regions_image_name = $regions_image->filename;
        } else {
        $regions_image_name = $HTTP_POST_VARS['regions_previous_image'];
      }
#     if ( ($regions_image != 'none') && ($regions_image != '') ) {
#       $image_location = DIR_FS_CATALOG_IMAGES . $regions_image_name;
#       if (file_exists($image_location)) @unlink($image_location);
#       copy($regions_image, $image_location);
#     } else {
#       $regions_image_name = $HTTP_POST_VARS['regions_previous_image'];
#     }
    } else {
      $category_query = tep_db_query("select c.regions_id, cd.language_id, cd.regions_name, cd.regions_heading_title, cd.regions_description, cd.regions_head_title_tag, cd.regions_head_desc_tag, cd.regions_head_keywords_tag, c.regions_image, c.sort_order, c.date_added, c.last_modified from " . TABLE_REGIONS . " c, " . TABLE_REGIONS_DESCRIPTION . " cd where c.regions_id = cd.regions_id and c.regions_id = '" . $HTTP_GET_VARS['cID'] . "'");
      $category = tep_db_fetch_array($category_query);

      $cInfo = new objectInfo($category);
      $regions_image_name = $cInfo->regions_image;
    }

    $form_action = ($HTTP_GET_VARS['cID']) ? 'update_category' : 'insert_category';

    echo tep_draw_form($form_action, FILENAME_REGIONS, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

    $languages = tep_get_languages();
    for ($i=0; $i<sizeof($languages); $i++) {
      if ($HTTP_GET_VARS['read'] == 'only') {
        $cInfo->regions_name = tep_get_region_name($cInfo->regions_id, $languages[$i]['id']);
        $cInfo->regions_heading_title = tep_get_region_heading_title($cInfo->regions_id, $languages[$i]['id']);
        $cInfo->regions_description = tep_get_region_description($cInfo->regions_id, $languages[$i]['id']);
        $cInfo->category_template_id = tep_get_category_template_id($cInfo->regions_id, $languages[$i]['id']);
        $cInfo->regions_head_title_tag = tep_get_region_head_title_tag($cInfo->regions_id, $languages[$i]['id']);
        $cInfo->regions_head_desc_tag = tep_get_region_head_desc_tag($cInfo->regions_id, $languages[$i]['id']);
        $cInfo->regions_head_keywords_tag = tep_get_region_head_keywords_tag($cInfo->regions_id, $languages[$i]['id']);
      } else {
        $cInfo->regions_name = tep_db_prepare_input($regions_name[$languages[$i]['id']]);
        $cInfo->regions_heading_title = tep_db_prepare_input($regions_heading_title[$languages[$i]['id']]);
        $cInfo->regions_description = tep_db_prepare_input($regions_description[$languages[$i]['id']]);
        $cInfo->category_template_id = tep_db_prepare_input($category_template_id[$languages[$i]['id']]);
        $cInfo->regions_head_title_tag = tep_db_prepare_input($regions_head_title_tag[$languages[$i]['id']]);
        $cInfo->regions_head_desc_tag = tep_db_prepare_input($regions_head_desc_tag[$languages[$i]['id']]);
        $cInfo->regions_head_keywords_tag = tep_db_prepare_input($regions_head_keywords_tag[$languages[$i]['id']]);

    }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $cInfo->regions_heading_title; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $regions_image_name, $cInfo->regions_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . $cInfo->regions_description; ?></td>
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
        $back_url = FILENAME_REGIONS;
        $back_url_params = 'cPath=' . $cPath . '&cID=' . $cInfo->regions_id;
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
          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        }
      }
      $languages = tep_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
        echo tep_draw_hidden_field('regions_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($regions_name[$languages[$i]['id']])));
        echo tep_draw_hidden_field('regions_heading_title[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($regions_heading_title[$languages[$i]['id']])));
        echo tep_draw_hidden_field('regions_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($regions_description[$languages[$i]['id']])));
        echo tep_draw_hidden_field('regions_head_title_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($regions_head_title_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('regions_head_desc_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($regions_head_desc_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('regions_head_keywords_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($regions_head_keywords_tag[$languages[$i]['id']])));

     }
      echo tep_draw_hidden_field('X_regions_image', stripslashes($regions_image_name));
      echo tep_draw_hidden_field('regions_image', stripslashes($regions_image_name));

      echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if ($HTTP_GET_VARS['cID']) {
        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
      } else {
        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </form></tr>
<?php
    }


  } elseif ($action == 'new_product') {
    
    
	
?>

<?php
  } elseif ($action == 'new_product_preview') {
   

    for ($i=0, $n=sizeof($languages); $i<$n; $i++) 
	{
      
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
<script type="text/javascript"><!--
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
        $back_url = FILENAME_REGIONS;
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
          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        } else {
          while (list($k, $v) = each($value)) {
            echo tep_draw_hidden_field($key . '[' . $k . ']', htmlspecialchars(stripslashes($v)));
          }
        }
      }
      $languages = tep_get_languages();
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
        echo tep_draw_hidden_field('products_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_name[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_description[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_head_title_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_title_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_desc_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_head_keywords_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('products_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_url[$languages[$i]['id']])));
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
            echo tep_draw_hidden_field($key . '[' . $k . ']', htmlspecialchars(stripslashes($v)));
          }
        } else {
          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
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
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </tr>
    </table></form>
<?php
    }
  } else {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
			
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText" align="right">
				Filter by:
			<?php 
			echo tep_draw_form('fildter_frm_by_country', FILENAME_REGIONS, '', 'get'); 
			echo tep_draw_pull_down_menu('countries_search_id', $countries_array, $selected_drop_down_country_id, 'onChange="this.form.submit();"');
			echo '</form>';
			?>
<?php
   // echo tep_draw_form('search', FILENAME_REGIONS, '', 'get');
   // echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');
  //  echo '</form>';
?>
                </td>
              </tr>
              <tr>
                <td class="smallText" align="right">
<?php
  //  echo tep_draw_form('goto', FILENAME_REGIONS, '', 'get');
  //  echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
 //   echo '</form>';
?>
                </td>
              </tr>
            </table></td>
          </tr>
		  <tr>
			<td  class="dataTableContent"><?php tep_get_atoz_filter_links(FILENAME_REGIONS); ?></td>
		  </tr>
		   <tr><td height="5"></td></tr>
        </table></td>
      </tr>
      
		  <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_COUNTRY_NAME; ?>
				<?php echo '<a href="' . tep_href_link(FILENAME_REGIONS, tep_get_all_get_params(array('sort','order')).'sort=country_name&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_REGIONS, tep_get_all_get_params(array('sort','order')).'sort=country_name&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
				<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_REGIONS_PRODUCTS; ?>
				<?php echo '<a href="' . tep_href_link(FILENAME_REGIONS, tep_get_all_get_params(array('sort','order')).'sort=name&order=ascending', 'NONSSL').'"><img src="images/arrow_up.gif" border="0"></a>&nbsp;<a href="' . tep_href_link(FILENAME_REGIONS, tep_get_all_get_params(array('sort','order')).'sort=name&order=decending', 'NONSSL').'"><img src="images/arrow_down.gif" border="0"></a>&nbsp;'; ?>
				</td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    if($_GET["sort"] == 'name') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'cd.regions_name asc ';
	  } else {
			$sortorder .= 'cd.regions_name desc ';
	  }
	}
	else if($_GET["sort"] == 'country_name') {
	   if($_GET["order"] == 'ascending') {
			$sortorder .= 'cnt.countries_name asc ';
	  } else {
			$sortorder .= 'cnt.countries_name desc ';
	  }
	}
	else
	{
		$sortorder .= 'c.sort_order, cd.regions_name ';
	}
	$regions_count = 0;
    $rows = 0;
    
	if(isset($_GET['filter_search']) && $_GET['filter_search']!=''){
		$filter_search_query = " and cd.regions_name like '".$_GET['filter_search']."%'";
	}
	if (isset($HTTP_GET_VARS['search'])) {
      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);

      $regions_query = tep_db_query("select c.regions_status, c.regions_id, cd.regions_name, c.regions_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.countries_id, cnt.countries_name from " . TABLE_REGIONS . " c, " . TABLE_REGIONS_DESCRIPTION . " cd, " . TABLE_COUNTRIES . " cnt where c.regions_id = cd.regions_id and cnt.countries_id = c.countries_id and cd.language_id = '" . (int)$languages_id . "' and cd.regions_name like '%" . tep_db_input($search) . "%' ".$filter_search_query." order by c.sort_order, cd.regions_name");
    }
	else if(isset($HTTP_GET_VARS['countries_search_id']) && $HTTP_GET_VARS['countries_search_id']!= ''){
  		$regions_query = tep_db_query("select c.regions_status, c.regions_id, cd.regions_name, c.regions_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.countries_id, cnt.countries_name from " . TABLE_REGIONS . " c, " . TABLE_REGIONS_DESCRIPTION . " cd, " . TABLE_COUNTRIES . " cnt where c.parent_id = '" . (int)$current_category_id . "' and c.regions_id = cd.regions_id and cnt.countries_id = c.countries_id and cd.language_id = '" . (int)$languages_id . "' and c.countries_id='".$HTTP_GET_VARS['countries_search_id']."' ".$filter_search_query." order by ".$sortorder."");
	} else {
	
      $regions_query = tep_db_query("select c.regions_status, c.regions_id, cd.regions_name, c.regions_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.countries_id, cnt.countries_name from " . TABLE_REGIONS . " c, " . TABLE_REGIONS_DESCRIPTION . " cd, " . TABLE_COUNTRIES . " cnt where c.parent_id = '" . (int)$current_category_id . "' and c.regions_id = cd.regions_id and cnt.countries_id = c.countries_id and cd.language_id = '" . (int)$languages_id . "' ".$filter_search_query." order by ".$sortorder."");
    }
    while ($regions = tep_db_fetch_array($regions_query)) {
      $regions_count++;
      $rows++;

// Get parent_id for subregions if search
      if (isset($HTTP_GET_VARS['search'])) $cPath= $regions['parent_id'];

      if ((!isset($HTTP_GET_VARS['cID']) && !isset($HTTP_GET_VARS['pID']) || (isset($HTTP_GET_VARS['cID']) && ($HTTP_GET_VARS['cID'] == $regions['regions_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => tep_childs_in_region_count($regions['regions_id']));
        $category_products = array('products_count' => tep_products_in_category_count($regions['regions_id']));

        $cInfo_array = array_merge($regions, $category_childs, $category_products);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($regions['regions_id'] == $cInfo->regions_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_REGIONS, tep_get_all_get_params(array('action','cID')).'action=edit_category_show&cID=' . $regions['regions_id']) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_REGIONS,  tep_get_all_get_params(array('action','cID','cPath')).'cPath=' . $cPath . '&cID=' . $regions['regions_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $regions['countries_name']; ?></td>
				<td class="dataTableContent"><?php echo $regions['regions_name']; //<a href="' . tep_href_link(FILENAME_REGIONS, tep_get_path($regions['regions_id'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;?></td>
                <td class="dataTableContent" align="center">
<?php 
	if ($regions['regions_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_REGIONS, tep_get_all_get_params(array('action','flag','cID','cPath')).'action=setflag&flag=0&cID=' . $regions['regions_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_REGIONS, tep_get_all_get_params(array('action','flag','cID','cPath')).'action=setflag&flag=1&cID=' . $regions['regions_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($regions['regions_id'] == $cInfo->regions_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_REGIONS, tep_get_all_get_params(array('cID','cPath')).'cPath=' . $cPath . '&cID=' . $regions['regions_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $products_count = 0;
	
	
    if (isset($HTTP_GET_VARS['search'])) {
      $products_query = tep_db_query("select p.products_model, p.products_id, pd.products_name, p.products_is_regular_tour, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'  and  p.products_id = '' and pd.products_name like '%" . tep_db_input($search) . "%' order by pd.products_name");
    } else {
      //echo "select p.products_model, p.products_id, pd.products_name, p.products_is_regular_tour, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and  p.products_id = '' order by pd.products_name";
	  
	  $products_query = tep_db_query("select p.products_model, p.products_id, pd.products_name, p.products_is_regular_tour, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and  p.products_id = '' order by pd.products_name");
    }
    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

// Get regions_id for product if search
      if (isset($HTTP_GET_VARS['search'])) $cPath = $products['regions_id'];

      if ( (!isset($HTTP_GET_VARS['pID']) && !isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['pID']) && ($HTTP_GET_VARS['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
// find out the rating average from customer reviews
        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);
        $pInfo_array = array_merge($products, $reviews);
        $pInfo = new objectInfo($pInfo_array);
      }

      if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $products['products_name']; ?></td>
                <td class="dataTableContent" align="center">
<?php
      if ($products['products_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_REGIONS, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_REGIONS, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
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
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_REGIONS . '&nbsp;' . $regions_count ; ?></td>
                    <td align="right" class="smallText"><?php if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_REGIONS, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($HTTP_GET_VARS['search'])) echo '<a href="' . tep_href_link(FILENAME_REGIONS,tep_get_all_get_params(array('action','cPath')). 'cPath=' . $cPath . '&action=new_category_show') . '">' . tep_image_button('button_new_region.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp; '; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($action) {
      case 'new_category_show':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_REGION . '</b>');

        $contents = array('form' => tep_draw_form('newcategory', FILENAME_REGIONS, tep_get_all_get_params(array('action','cPath')).'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_NEW_REGION_INTRO);
		
		if (!isset($cInfo->regions_status)) $cInfo->regions_status = '1';
    switch ($cInfo->regions_status) {
      case '0': $cin_status = false; $cout_status = true; break;
      case '1':
      default: $cin_status = true; $cout_status = false;
    }
	
		$contents[] = array('text' => '<br><b>' . TEXT_REGION_STATUS .'</b>'. tep_draw_radio_field('regions_status', '1', $cin_status) . '&nbsp;' . TEXT_REGION_AVAILABLE . '&nbsp;' . tep_draw_radio_field('regions_status', '0', $cout_status) . '&nbsp;' . TEXT_REGION_NOT_AVAILABLE);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('regions_name[' . $languages[$i]['id'] . ']');
        }

        $contents[] = array('text' => '<br><b>' . TEXT_REGIONS_NAME .'</b>'. $category_inputs_string);
		$contents[] = array('text' => '<br><b>' . TEXT_REGION_COUNTRY .'</b>' . tep_draw_pull_down_menu('countries_id', $countries_array, $row_get_country_id['countries_id'], $parameters));
        $contents[] = array('text' => '<br><b>' . TEXT_REGIONS_IMAGE . '</b><br>' . tep_draw_file_field('regions_image'));
        //$contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_REGIONS,tep_get_all_get_params(array('action','cPath')). 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'edit_category_show':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_REGION . '</b>');

        $contents = array('form' => tep_draw_form('regions', FILENAME_REGIONS, tep_get_all_get_params(array('action','cPath')).'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('regions_id', $cInfo->regions_id));
        $contents[] = array('text' => TEXT_EDIT_INTRO);
		
		
		if (!isset($cInfo->regions_status)) $cInfo->regions_status = '1';
    switch ($cInfo->regions_status) {
      case '0': $cin_status = false; $cout_status = true; break;
      case '1':
      default: $cin_status = true; $cout_status = false;
    }
	
		$contents[] = array('text' => '<br><b>' . TEXT_REGION_STATUS .'</b>'. tep_draw_radio_field('regions_status', '1', $cin_status) . '&nbsp;' . TEXT_REGION_AVAILABLE . '&nbsp;' . tep_draw_radio_field('regions_status', '0', $cout_status) . '&nbsp;' . TEXT_REGION_NOT_AVAILABLE);

        $category_inputs_string = '';
        $languages = tep_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('regions_name[' . $languages[$i]['id'] . ']', tep_get_region_name($cInfo->regions_id, $languages[$i]['id']));
        }

        $contents[] = array('text' => '<br><b>' . TEXT_EDIT_REGIONS_NAME.'</b>' . $category_inputs_string);
		 $get_country_id = tep_db_query("select c.countries_id from " . TABLE_REGIONS . " c where c.regions_id = '" . $cInfo->regions_id . "'");
		 $row_get_country_id = tep_db_fetch_array($get_country_id);
		$contents[] = array('text' => '<br><b>' . TEXT_REGION_COUNTRY .'</b>'. tep_draw_pull_down_menu('countries_id', $countries_array, $row_get_country_id['countries_id'], $parameters));
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->regions_image, $cInfo->regions_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->regions_image . '</b>');
        $contents[] = array('text' => '<br><b>' . TEXT_EDIT_REGIONS_IMAGE . '</b><br>' . tep_draw_file_field('regions_image'));
        //$contents[] = array('text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_REGIONS,tep_get_all_get_params(array('action','cPath','cID')). 'cPath=' . $cPath . '&cID=' . $cInfo->regions_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('regions', FILENAME_REGIONS, tep_get_all_get_params(array('action','cPath')).'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('regions_id', $cInfo->regions_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br><b>' . $cInfo->regions_name . '</b>');
        if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_REGIONS, tep_get_all_get_params(array('action','cPath','cID')).'cPath=' . $cPath . '&cID=' . $cInfo->regions_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('regions', FILENAME_REGIONS, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('regions_id', $cInfo->regions_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_REGIONS_INTRO, $cInfo->regions_name));
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->regions_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        //$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&cID=' . $cInfo->regions_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_REGIONS, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
        $contents[] = array('text' => '<br><b>' . $pInfo->products_name . '</b>');

        $product_regions_string = '';
        $product_regions = tep_generate_category_path($pInfo->products_id, 'product');
        for ($i = 0, $n = sizeof($product_regions); $i < $n; $i++) {
          $category_path = '';
          for ($j = 0, $k = sizeof($product_regions[$i]); $j < $k; $j++) {
            $category_path .= $product_regions[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
          $category_path = substr($category_path, 0, -16);
          $product_regions_string .= tep_draw_checkbox_field('product_regions[]', $product_regions[$i][sizeof($product_regions[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
        }
        $product_regions_string = substr($product_regions_string, 0, -4);

        $contents[] = array('text' => '<br>' . $product_regions_string);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_REGIONS, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_REGIONS . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        //$contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'copy_to':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

        $contents = array('form' => tep_draw_form('copy_to', FILENAME_REGIONS, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_REGIONS . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_REGIONS . '<br>' . tep_draw_pull_down_menu('regions_id', tep_get_category_tree(), $current_category_id));
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

        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
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
        $contents = array('form' => tep_draw_form('products', FILENAME_REGIONS, 'action=create_copy_product_attributes&cPath=' . $cPath . '&pID=' . $pInfo->products_id) . tep_draw_hidden_field('products_id', $pInfo->products_id) . tep_draw_hidden_field('products_name', $pInfo->products_name));
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
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', 'Copy Attribtues') . ' <a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
// WebMakers.com Added: Copy Attributes Existing Product to All Products in Category
      case 'copy_product_attributes_regions':
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
        $contents = array('form' => tep_draw_form('products', FILENAME_REGIONS, 'action=create_copy_product_attributes_regions&cPath=' . $cPath . '&cID=' . $cID . '&make_copy_from_products_id=' . $copy_from_products_id));
        $contents[] = array('text' => 'Copy Product Attributes from Product ID#&nbsp;' . tep_draw_input_field('make_copy_from_products_id', $make_copy_from_products_id, 'size="3"'));
        $contents[] = array('text' => '<br>Copying to all products in Category ID#&nbsp;' . $cID . '<br>Category Name: <b>' . tep_get_region_name($cID, $languages_id) . '</b>');
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
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', 'Copy Attribtues') . ' <a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&cID=' . $cID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->regions_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_REGIONS,tep_get_all_get_params(array('action','cPath','cID')). 'cPath=' . $cPath . '&cID=' . $cInfo->regions_id . '&action=edit_category_show') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_REGIONS,tep_get_all_get_params(array('action','cID','cPath')). 'cPath=' . $cPath . '&cID=' . $cInfo->regions_id . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');// <a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&cID=' . $cInfo->regions_id . '&action=move_category') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>'
            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));
            if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
            $contents[] = array('text' => '<br>' . tep_info_image($cInfo->regions_image, $cInfo->regions_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->regions_image);
            $contents[] = array('text' => '<br>' . TEXT_SUBREGIONS . ' ' . $cInfo->childs_count . '<br>');
            if ($cInfo->childs_count==0 and $cInfo->products_count >= 1) {
// WebMakers.com Added: Copy Attributes Existing Product to All Existing Products in Category
              //$contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
              if ($cID) {
               // $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&cID=' . $cID . '&action=copy_product_attributes_regions') . '">' . 'Copy Product Attributes to <br>ALL products in Category: ' . tep_get_region_name($cID, $languages_id) . '<br>' . tep_image_button('button_copy_to.gif', 'Copy Attributes') . '</a>');
              } else {
               // $contents[] = array('align' => 'center', 'text' => '<br>Select a Category to copy attributes to');
              }
            }
          } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
            $heading[] = array('text' => '<b>' . tep_get_products_name($pInfo->products_id, $languages_id) . '</b>');

            //$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>');
            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->products_date_added));
            if (tep_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->products_last_modified));
            if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->products_date_available));
            $contents[] = array('text' => '<br>' . tep_info_image($pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>' . $pInfo->products_image);
            $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $currencies->format($pInfo->products_price) . '<br>' . TEXT_PRODUCTS_TYPE . ' ' . $pInfo->products_is_regular_tour);
            $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
// WebMakers.com Added: Copy Attributes Existing Product to another Existing Product
            $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_REGIONS, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_product_attributes') . '">' . 'Products Attributes Copier:<br>' . tep_image_button('button_copy_to.gif', 'Copy Attributes') . '</a>');
            if ($pID) {
              $contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '10'));
            } else {
              $contents[] = array('align' => 'center', 'text' => '<br>Select a product to display attributes');
            }
          }
        } else { // create category/product info
          $heading[] = array('text' => '<b>' . EMPTY_REGION . '</b>');

          $contents[] = array('text' => TEXT_NO_CHILD_REGIONS_OR_PRODUCTS);
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
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
