<?php
/*
  $Id: categories.php,v 1.2 2004/03/29 00:18:17 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require('includes/functions/categories_description.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();  
 
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
	
	case 'upload_product_video':
	
	if (isset($HTTP_GET_VARS['pID'])) $products_id = tep_db_prepare_input($HTTP_GET_VARS['pID']);
	
	if(($HTTP_POST_VARS['products_video_url'] != '') or (basename($_FILES['products_video']['name'] != ''))){
			$products_video = '';				  
			if($HTTP_POST_VARS['products_video_url'] != ''){
				$products_video_name = $HTTP_POST_VARS['products_video_url'];
				
			}else if(basename($_FILES['products_video']['name']) != '') {
				
				
				$products_video = new upload('products_video');
				$products_video->set_destination(DIR_FS_CATALOG_VIDEO);
				if ($products_video->parse() && $products_video->save()) {
				  $products_video_name = $products_video->filename;
				} else {
				  $products_video_name = (isset($HTTP_POST_VARS['products_previous_video']) ? $HTTP_POST_VARS['products_previous_video'] : '');
				}
				
				//add delete to previouse start
				//add delete to previos end
			}	
	}else{
	  $products_video_name = (isset($HTTP_POST_VARS['products_previous_video']) ? $HTTP_POST_VARS['products_previous_video'] : '');
				
	}
					
   /*if (($HTTP_POST_VARS['unlink_image'] == 'yes') or ($HTTP_POST_VARS['delete_image'] == 'yes')) {
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
        }*/
		
		if (($HTTP_POST_VARS['unlink_map'] == 'yes') or ($HTTP_POST_VARS['delete_map'] == 'yes')) {
        $products_map = '';
        $products_map_name = '';
        } elseif ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')) {
        $products_map = new upload('products_map');
        $products_map->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_map->parse() && $products_map->save()) {
          $products_map_name = $products_map->filename;
        } else {
          $products_map_name = (isset($HTTP_POST_VARS['products_previous_map']) ? $HTTP_POST_VARS['products_previous_map'] : '');
        }
        } else {
          if (isset($HTTP_POST_VARS['products_map']) && tep_not_null($HTTP_POST_VARS['products_map']) && ($HTTP_POST_VARS['products_map'] != 'none')) {
            $products_map_name = $HTTP_POST_VARS['products_map'];
          } else {
            $products_map_name = (isset($HTTP_POST_VARS['products_previous_map']) ? $HTTP_POST_VARS['products_previous_map'] : '');
          }
        }
   		if (($HTTP_POST_VARS['unlink_image_med'] == 'yes') or ($HTTP_POST_VARS['delete_image_med'] == 'yes')) {
        $products_image_med = '';
        $products_image_med_name = '';
		
		$products_image = '';
		$products_image_name = '';
        } elseif ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')) {
        $products_image_med = new upload('products_image_med');
        $products_image_med->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_med->parse() && $products_image_med->save()) {
          $products_image_med_name = $products_image_med->filename;
		  $uploadfile = DIR_FS_CATALOG_IMAGES.$products_image_med_name;
			$target = DIR_FS_CATALOG_IMAGES;
			$size = getimagesize($uploadfile);
			$width = $size[0];
			//exit;
			if($width>159)
			{
				$thumb_image = 1;
				$new_height = 103;
				$new_width = 159;
				imageCompression($uploadfile,159,$target  .'thumb_'. $products_image_med_name,$new_width,$new_height);
			}
			else
			{
				imageCompression($uploadfile,$width,$target  .'thumb_'. $products_image_med_name);
			}
			
			$products_image_name = 	'thumb_'. $products_image_med_name;
        } else {
          $products_image_med_name = (isset($HTTP_POST_VARS['products_previous_image_med']) ? $HTTP_POST_VARS['products_previous_image_med'] : '');		  
		  $products_image_name = (isset($HTTP_POST_VARS['products_previous_image']) ? $HTTP_POST_VARS['products_previous_image'] : '');
        }
        } else {
          if (isset($HTTP_POST_VARS['products_image_med']) && tep_not_null($HTTP_POST_VARS['products_image_med']) && ($HTTP_POST_VARS['products_image_med'] != 'none')) {
            $products_image_med_name = $HTTP_POST_VARS['products_image_med'];
			$products_image_name = $HTTP_POST_VARS['products_image_med'];
          } else {
            $products_image_med_name = (isset($HTTP_POST_VARS['products_previous_image_med']) ? $HTTP_POST_VARS['products_previous_image_med'] : '');
			$products_image_name = (isset($HTTP_POST_VARS['products_previous_image']) ? $HTTP_POST_VARS['products_previous_image'] : '');
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
		
		
		$HTTP_POST_VARS['products_image'] = $products_image_name;
		$HTTP_POST_VARS['products_map'] = $products_map_name;
		$HTTP_POST_VARS['products_image_med'] = $products_image_med_name;
		$HTTP_POST_VARS['products_image_lrg'] = $products_image_lrg_name;
		$HTTP_POST_VARS['products_video'] = $products_video_name;
		
	  if($HTTP_POST_VARS['products_video'] != '' && $HTTP_POST_VARS['products_previous_video'] != '' && ($HTTP_POST_VARS['products_video'] != $HTTP_POST_VARS['products_previous_video']) && substr($HTTP_POST_VARS['products_previous_video'],0,4) != 'http:' ){
		@unlink(DIR_FS_CATALOG_VIDEO . $HTTP_POST_VARS['products_previous_video']);
	  }
	  
		if ($HTTP_POST_VARS['delete_map'] == 'yes') {			
			@unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_map']);
		}
		/*if ($HTTP_POST_VARS['delete_image'] == 'yes') {			
			@unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image']);
		}*/
		if ($HTTP_POST_VARS['delete_image_med'] == 'yes') {
			@unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_med']);
			@unlink(DIR_FS_CATALOG_IMAGES . 'thumb_'.$HTTP_POST_VARS['products_previous_image_med']);
		}
		if ($HTTP_POST_VARS['delete_image_lrg'] == 'yes') {
			@unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_lrg']);
		}
	  
	     $sql_data_array = array(	   						
								  'products_video' => tep_db_prepare_input($HTTP_POST_VARS['products_video']),
								  'products_last_modified' => 'now()'
                                 );
								  
	    /*if (($HTTP_POST_VARS['unlink_image'] == 'yes') or ($HTTP_POST_VARS['delete_image'] == 'yes')) {
            $sql_data_array['products_image'] = '';
			
           } else {
         if (isset($HTTP_POST_VARS['products_image']) && tep_not_null($HTTP_POST_VARS['products_image']) && ($HTTP_POST_VARS['products_image'] != 'none')) {
            $sql_data_array['products_image'] = tep_db_prepare_input($HTTP_POST_VARS['products_image']);
          }
          }*/
		  
		  if (($HTTP_POST_VARS['unlink_map'] == 'yes') or ($HTTP_POST_VARS['delete_map'] == 'yes')) {
            $sql_data_array['products_map'] = '';
           } else {
         if (isset($HTTP_POST_VARS['products_map']) && tep_not_null($HTTP_POST_VARS['products_map']) && ($HTTP_POST_VARS['products_map'] != 'none')) {
            $sql_data_array['products_map'] = tep_db_prepare_input($HTTP_POST_VARS['products_map']);
          }
          }
		  
       if (($HTTP_POST_VARS['unlink_image_med'] == 'yes') or ($HTTP_POST_VARS['delete_image_med'] == 'yes')) {

            $sql_data_array['products_image_med'] = '';
			$sql_data_array['products_image'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_med']) && tep_not_null($HTTP_POST_VARS['products_image_med']) && ($HTTP_POST_VARS['products_image_med'] != 'none')) {
            $sql_data_array['products_image_med'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_med']);
			$sql_data_array['products_image'] = tep_db_prepare_input($HTTP_POST_VARS['products_image']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_lrg'] == 'yes') or ($HTTP_POST_VARS['delete_image_lrg'] == 'yes')) {
            $sql_data_array['products_image_lrg'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_lrg']) && tep_not_null($HTTP_POST_VARS['products_image_lrg']) && ($HTTP_POST_VARS['products_image_lrg'] != 'none')) {
            $sql_data_array['products_image_lrg'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_lrg']);
          }
          }
		  
		  
	tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
			
		///// PRODUCTS EXTRA IMAGES CODE
	
		 foreach($_POST['cat_intro_sort_order'] as $key=>$val){	
				if( $val != ''){					
					if(isset($_POST['db_categories_introduction_id'][$key]) && $_POST['db_categories_introduction_id'][$key] !=''){					
						if($_POST['remove_id_form_db'][$key] == 'on'){							
							
							//simple delete form db
							if($_POST['previouse_image_introfile'][$key] != ''){
										//unlink previouse image
								@unlink(DIR_FS_CATALOG_IMAGES . $_POST['previouse_image_introfile'][$key]);
							}
							tep_db_query("delete from " . TABLE_PRODUCTS_EXTRA_IMAGES . " where prod_extra_image_id = '" . (int)$_POST['db_categories_introduction_id'][$key] . "'");
						}else{
										//wirte code for upload new image	
									 if(basename($_FILES['image_introfile']['name'][$key]) != '' ){	
									 $insert_id = rand(1000,3000);							
											$tmp_categories_introduction_image_name = '';
											$uploadfile = DIR_FS_CATALOG_IMAGES.$insert_id.'_'.basename($_FILES['image_introfile']['name'][$key]);
											if (move_uploaded_file($_FILES['image_introfile']['tmp_name'][$key],$uploadfile)) {
												$tmp_categories_introduction_image_name = $insert_id.'_'.basename($_FILES['image_introfile']['name'][$key]);
											} 
										
										$categories_introduction_image_name = $tmp_categories_introduction_image_name;
										
										//delete previouse image
										if($_POST['previouse_image_introfile'][$key] != '' && basename($_FILES['image_introfile']['name'][$key]) != $_POST['previouse_image_introfile'][$key]){
										   @unlink(DIR_FS_CATALOG_IMAGES . $_POST['previouse_image_introfile'][$key]);
										}
									}else{
											//assine image name form privouse value
											
											$categories_introduction_image_name = $_POST['previouse_image_introfile'][$key];
									}
								if($categories_introduction_image_name != '' ){	
								$sql_data_array_intorupdate = array(
											 'products_id' => $products_id,
											 'product_image_name' => $categories_introduction_image_name,
											 'image_sort_order' => tep_db_prepare_input($_POST['cat_intro_sort_order'][$key])
											 );
				
							 tep_db_perform(TABLE_PRODUCTS_EXTRA_IMAGES, $sql_data_array_intorupdate,'update',"prod_extra_image_id = '" . (int)$_POST['db_categories_introduction_id'][$key] . "'");
							}	
								
						}
						
					}else{
							//add new seciton

										//wirte code for upload new image												
									if(basename($_FILES['image_introfile']['name'][$key]) != '' ){		
									 $insert_id = rand(1000,3000);								
											$tmp_categories_introduction_image_name = '';
											$uploadfile = DIR_FS_CATALOG_IMAGES.$insert_id.'_'.basename($_FILES['image_introfile']['name'][$key]);
											if (move_uploaded_file($_FILES['image_introfile']['tmp_name'][$key],$uploadfile)) {
												$tmp_categories_introduction_image_name = $insert_id.'_'.basename($_FILES['image_introfile']['name'][$key]);
											} 
										
										$categories_introduction_image_name = $tmp_categories_introduction_image_name;
										
										//delete previouse image
										if($_POST['previouse_image_introfile'][$key] != '' && basename($_FILES['image_introfile']['name'][$key]) != $_POST['previouse_image_introfile'][$key]){
										   @unlink(DIR_FS_CATALOG_IMAGES . $_POST['previouse_image_introfile'][$key]);
										}
									
				
							$sql_data_array_intoradd = array(
											 'products_id' => $products_id,
											 'product_image_name' => $categories_introduction_image_name,
											 'image_sort_order' => tep_db_prepare_input($_POST['cat_intro_sort_order'][$key])
											 );
											 
							tep_db_perform(TABLE_PRODUCTS_EXTRA_IMAGES, $sql_data_array_intoradd);
								}
				}
			}	  
		  
  		  //amit added for image and  description for introduction section END		  

		
	}
	
	///// PRODUCTS EXTRA IMAGES CODE		

	//$messageStack->add_session('Updated '.TEXT_HEADING_IMAGES_VIDEOS.' Section', 'success');
	 
	tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID'].(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '').'&action=new_product'));
	
	break;
	case 'setflagf':
	if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) 
	{
		if ( isset($HTTP_GET_VARS['pID']) ) 
		{
		           tep_db_query("update " . TABLE_PRODUCTS . " set featured_products = '" . $HTTP_GET_VARS['flag'] . "' where products_id = '" . $HTTP_GET_VARS['pID'] . "'");
		}
	}
	tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID'].(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')));
	 break;		
	  case 'setflagffc':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
           if (isset($HTTP_GET_VARS['cID'])) {
            tep_set_category_feature_status($HTTP_GET_VARS['cID'], $HTTP_GET_VARS['flag']);
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('categories');
            tep_reset_cache_block('also_purchased');
          }
        }
       
		tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&cID=' . $HTTP_GET_VARS['cID']));
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
       
		tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $HTTP_GET_VARS['cPath'] . '&pID=' . $HTTP_GET_VARS['pID']. '&cID=' . $HTTP_GET_VARS['cID'].(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')));
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
		
		if(!tep_not_null($urlname)){ 
			$urlname = seo_generate_urlname($HTTP_POST_VARS['categories_name']['1']);
        }else{ 
			$urlname = seo_generate_urlname($urlname);
		}			
		//amit added to check last charactor is endec with / start
			  if (substr($urlname, -1) != '/') {
            	$urlname = $urlname.'/';
        	  }
  		  //amit added to check last charactor is endec with / end
		  
		  //check for already exist urlname
		  	if ($action == 'update_category'){
				$where_url = " and categories_id != '" . (int)$categories_id . "'";
			}else{
				$where_url = "";
			}
			$check_url_query = tep_db_query("select categories_urlname from ".TABLE_CATEGORIES." where categories_urlname='".$urlname."' ".$where_url." ");
			if(tep_db_num_rows($check_url_query)>0){
				$messageStack->add_session(ERROR_URL_EXIST, 'error');
			}
		  //check for already exist urlname

          $sql_data_array = array('sort_order' => $sort_order,
								
                                  'categories_urlname' => $urlname,
								  
								  'categories_status' => tep_db_prepare_input($HTTP_POST_VARS['categories_status']),
								  
								  'categories_recommended_tours_ids' => tep_db_prepare_input($HTTP_POST_VARS['categories_recommended_tours_ids'])								  
								  
								  );
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
		  if(USE_MCACHE) MCache::update_categories(array('method'=>'update','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22
        }
		
		 if($HTTP_POST_VARS['categories_video'] != '' && $HTTP_POST_VARS['categories_previous_video'] != '' && ($HTTP_POST_VARS['categories_video'] != $HTTP_POST_VARS['categories_previous_video']) && substr($HTTP_POST_VARS['categories_previous_video'],0,4) != 'http:' ){
		  	@unlink(DIR_FS_CATALOG_VIDEO . $HTTP_POST_VARS['categories_previous_video']);
		  }		  
		  //amit added for image and  description for introduction section START		  
		  foreach($_POST['cat_intro_description_introfile'] as $key=>$val){				
				if( $val != ''){					
					if(isset($_POST['db_categories_introduction_id'][$key]) && $_POST['db_categories_introduction_id'][$key] !=''){					
						
						if($_POST['remove_id_form_db'][$key] == 'on'){							
							//simple delete form db
							if($_POST['previouse_image_introfile'][$key] != ''){
										//unlink previouse image
								@unlink(DIR_FS_CATALOG_IMAGES . $_POST['previouse_image_introfile'][$key]);
							}
							tep_db_query("delete from " . TABLE_CATEGORIES_DESCRIPTION_INTRODUCTION . " where categories_introduction_id = '" . (int)$_POST['db_categories_introduction_id'][$key] . "'");
							if(USE_MCACHE) MCache::update_categories(array('method'=>'delete','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22
						}else{
								if($_POST['cat_image_introfile'][$key] != '' ){
										//wirte code for upload new image												
										$categories_introduction_image_name = $_POST['cat_image_introfile'][$key];
										
										//delete previouse image
										if($_POST['previouse_image_introfile'][$key] != '' && $_POST['cat_image_introfile'][$key] != $_POST['previouse_image_introfile'][$key]){
										   @unlink(DIR_FS_CATALOG_IMAGES . $_POST['previouse_image_introfile'][$key]);
										}
								}else{
										//assine image name form privouse value
										
										$categories_introduction_image_name = $_POST['previouse_image_introfile'][$key];
								}
								
								$sql_data_array_intorupdate = array(
											 'categories_id' => $categories_id,
											 'categories_introduction_image' => $categories_introduction_image_name,
											 'categories_introduction_image_descirption' => tep_db_prepare_input($val),
											 'categories_introduction_image_alt' => tep_db_prepare_input($_POST['cat_intro_alt_introfile'][$key]),
											 'categories_introduction_sort_order' => tep_db_prepare_input($_POST['cat_intro_sort_order'][$key])
											 );
				
							 tep_db_perform(TABLE_CATEGORIES_DESCRIPTION_INTRODUCTION, $sql_data_array_intorupdate,'update',"categories_introduction_id = '" . (int)$_POST['db_categories_introduction_id'][$key] . "'");
							 if(USE_MCACHE) MCache::update_categories(array('method'=>'insert','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22
								
								
						}
						
						
					}else{
							//add new seciton
							
							if($_POST['cat_image_introfile'][$key] != '' ){
										//wirte code for upload new image									
								$categories_introduction_image_name = $_POST['cat_image_introfile'][$key];
								//delete previouse image
								if($_POST['previouse_image_introfile'][$key] != '' && $_POST['cat_image_introfile'][$key] != $_POST['previouse_image_introfile'][$key]){
								   @unlink(DIR_FS_CATALOG_IMAGES . $_POST['previouse_image_introfile'][$key]);
								}
							}else{
								$categories_introduction_image_name = '';
							}
				
							$sql_data_array_intoradd = array(
											 'categories_id' => $categories_id,
											 'categories_introduction_image' => $categories_introduction_image_name,
											 'categories_introduction_image_descirption' => tep_db_prepare_input($val),
											 'categories_introduction_image_alt' => tep_db_prepare_input($_POST['cat_intro_alt_introfile'][$key]),
											 'categories_introduction_sort_order' => tep_db_prepare_input($_POST['cat_intro_sort_order'][$key])
											 );
				
							tep_db_perform(TABLE_CATEGORIES_DESCRIPTION_INTRODUCTION, $sql_data_array_intoradd);
							if(USE_MCACHE) MCache::update_categories(array('method'=>'insert','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22				
				
					} //endo of check exiting
				
				}
			}	  
		  
  		  //amit added for image and  description for introduction section END		  

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
									  'categories_recommended_tours' => tep_db_prepare_input($HTTP_POST_VARS['categories_recommended_tours'][$language_id]),
									  'categories_map' => tep_db_prepare_input($HTTP_POST_VARS['categories_map'][$language_id]),
									  'categories_seo_description' => tep_db_prepare_input($HTTP_POST_VARS['categories_seo_description']),
									  'categories_logo_alt_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_logo_alt_tag']),
									  'categories_first_sentence' => tep_db_prepare_input($HTTP_POST_VARS['categories_first_sentence']),
                                      'categories_head_title_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_head_title_tag'][$language_id]),
                                      'categories_head_desc_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_head_desc_tag'][$language_id]),
                                      'categories_head_keywords_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_head_keywords_tag'][$language_id]),
									  'categories_video_description' => tep_db_prepare_input($HTTP_POST_VARS['categories_video_description'][$language_id]),
									  'categories_video' => tep_db_prepare_input($HTTP_POST_VARS['categories_video']),
									  'categories_top_banner_image_alt_tag' => tep_db_prepare_input($HTTP_POST_VARS['categories_top_banner_image_alt_tag'][$language_id])
									  );
        }
//End Categories Description 1.5

          if ($action == 'insert_category') {
            $insert_sql_data = array('categories_id' => $categories_id,
                                     'language_id' => $languages[$i]['id']);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
			if(USE_MCACHE) MCache::update_categories(array('method'=>'insert','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22
          } elseif ($action == 'update_category') {
            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
			if(USE_MCACHE) MCache::update_categories(array('method'=>'update','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22
          }
        }
//Added the following to replacce above code
          if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
            tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . $HTTP_POST_VARS['categories_image'] . "' where categories_id = '" .  tep_db_input($categories_id) . "'");
			if(USE_MCACHE) MCache::update_categories(array('method'=>'update','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22
            $categories_image = '';
      } else {
        if ($categories_image = new upload('categories_image', DIR_FS_CATALOG_IMAGES)) {
          tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
		  if(USE_MCACHE) MCache::update_categories(array('method'=>'update','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22
        }
       }
//End Categories Description 1.5

 		     tep_db_query("update " . TABLE_CATEGORIES . " set categories_map_image = '" .  $HTTP_POST_VARS['categories_map_image'] . "' where categories_id = '" . (int)$categories_id . "'");
			 if(USE_MCACHE) MCache::update_categories(array('method'=>'update','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22           
			
			
		  if(($HTTP_POST_VARS['categories_map_image'] != $HTTP_POST_VARS['categories_previous_map_image']) && $HTTP_POST_VARS['categories_previous_map_image'] != ''){
			  @unlink(DIR_FS_CATALOG_IMAGES.$HTTP_POST_VARS['categories_previous_map_image']);
		  }
		  
		  
		   tep_db_query("update " . TABLE_CATEGORIES . " set categories_banner_image = '" .  $HTTP_POST_VARS['categories_banner_image'] . "' where categories_id = '" . (int)$categories_id . "'");
		   if(USE_MCACHE) MCache::update_categories(array('method'=>'update','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22 
			
		  if(($HTTP_POST_VARS['categories_banner_image'] != $HTTP_POST_VARS['categories_previous_banner_image']) && $HTTP_POST_VARS['categories_previous_banner_image'] != ''){
			  @unlink(DIR_FS_CATALOG_IMAGES.$HTTP_POST_VARS['categories_previous_banner_image']);
		  }
			

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
			if(USE_MCACHE) MCache::update_categories(array('method'=>'update','categories_id'=>$categories_id)); //更新缓存-vincent-2011-4-22
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
// BOF: WebMakers.com Added: Copy Attributes Existing Product to another Existing Product
      case 'create_copy_product_attributes':
  // $products_id_to= $copy_to_products_id;
  // $products_id_from = $pID;
        tep_copy_products_attributes($pID,$copy_to_products_id);
        break;
// EOF: WebMakers.com Added: Copy Attributes Existing Product to another Existing Product
// WebMakers.com Added: Copy Attributes Existing Product to All Existing Products in a Category
      case 'create_copy_product_attributes_categories':
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
				@unlink(DIR_FS_CATALOG_IMAGES . 'thumb_'.$HTTP_POST_VARS['products_previous_image_med']);
            }
            if ($HTTP_POST_VARS['delete_image_lrg'] == 'yes') {
                @unlink(DIR_FS_CATALOG_IMAGES . $HTTP_POST_VARS['products_previous_image_lrg']);
            }
            
// EOF MaxiDVD: Modified For Ultimate Images Pack!


if (isset($HTTP_GET_VARS['pID'])) $products_id = tep_db_prepare_input($HTTP_GET_VARS['pID']);//this is to fetch peoduct id

	  
	/********************START  UPDATION OF PRODUCTS **********************/				  
					 if ($action == 'update_product') 
						  {
								//**********first fetch from the product table where it is first regular of irregular ***********//
							$product_regular_query = tep_db_query("select * from ".TABLE_PRODUCTS." where products_id = '" . (int)$products_id . "' ");
							$product_regular_result = tep_db_fetch_array($product_regular_query);	
							
							//**************************************** START CODING FOR UPDATING "products_is_regular_tour"   *************/
					  		//********** Code for inserrt update delete  product_start_date/products_available tables*****************//
								$newproductypecode = 1;
								if($HTTP_POST_VARS['products_type']==3 || $newproductypecode == 1)
								{
									
									
									tep_db_query("delete from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = '" . $product_regular_result['products_id'] . "'");
											
									tep_db_query("delete from " . TABLE_PRODUCTS_REG_IRREG_DESCRIPTION . " where products_id = '" . $product_regular_result['products_id'] . "'");
											
									for($no_main_sec=1;$no_main_sec<=$HTTP_POST_VARS['numberofsection'];$no_main_sec++)
									{
										
										
										if($HTTP_POST_VARS['products_is_regular_tour'.$no_main_sec]==0)
										{							
										
										   $sql_data_irregular_description = array('products_id' => $products_id,
																				'products_durations_description' => tep_db_prepare_input($HTTP_POST_VARS['products_durations_description'.$no_main_sec]),
																				'operate_start_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_start_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_year'.$no_main_sec]),
																				'operate_end_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_end_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_year'.$no_main_sec]));
														
											tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DESCRIPTION, $sql_data_irregular_description);
										
												
												for($no_dates=1;$no_dates<=$HTTP_POST_VARS['numberofdates'.$no_main_sec];$no_dates++)
												{									
													
														if($HTTP_POST_VARS['avaliable_day_date_'.$no_main_sec.'_'.$no_dates]!='')
															{
																$sql_data_irregular_date = array('products_id' => $products_id,
																					'available_date' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_date_'.$no_main_sec.'_'.$no_dates]),
																					'extra_charge' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_price_'.$no_main_sec.'_'.$no_dates]),																				
																					'spe_single' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_single_price_'.$no_main_sec.'_'.$no_dates]),
																					'spe_double' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_double_price_'.$no_main_sec.'_'.$no_dates]),
																					'spe_triple' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_triple_price_'.$no_main_sec.'_'.$no_dates]),
																					'spe_quadruple' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_quadruple_price_'.$no_main_sec.'_'.$no_dates]),
																					'spe_kids' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_kids_price_'.$no_main_sec.'_'.$no_dates]),																				
																					'prefix' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_prefix_'.$no_main_sec.'_'.$no_dates]),
																					'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_sort_order_'.$no_main_sec.'_'.$no_dates]),
																					'operate_start_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_start_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_year'.$no_main_sec]),
																					'operate_end_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_end_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_year'.$no_main_sec]));
																	
															tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DATE, $sql_data_irregular_date);
														}
													
												}
										}
										elseif($HTTP_POST_VARS['products_is_regular_tour'.$no_main_sec]==1)
										{										
											
											
											for($daysofweek=1;$daysofweek<8;$daysofweek++)
											{
												
												if(isset($HTTP_POST_VARS['weekday_option'.$no_main_sec."_".$daysofweek]))
												{
													$sql_data_irregular_weeday = array('products_id' => $products_id,
																						//products_durations_description' => tep_db_prepare_input($HTTP_POST_VARS['products_durations_description'.$no_main_sec]),
																						'products_start_day' => $daysofweek,
																						'extra_charge' => tep_db_prepare_input($HTTP_POST_VARS['weekday_price'.$no_main_sec.$daysofweek]),
																						'prefix' => tep_db_prepare_input($HTTP_POST_VARS['weekday_prefix'.$no_main_sec.$daysofweek]),
																						'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['weekday_sort_order'.$no_main_sec.$daysofweek]),
																						'operate_start_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_start_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_year'.$no_main_sec]),
																			'operate_end_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_end_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_year'.$no_main_sec]));
														
																tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DATE, $sql_data_irregular_weeday);
															} 
														}
										}
										
										
									}
									
								}
								else{
								tep_db_query("delete from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = '" . $product_regular_result['products_id'] . "'");//Amit added to delete from regular_irregular table
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
															if($HTTP_POST_VARS['avaliable_day_date_'.$total_no_dates]>=date("Y-m-d"))
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
															if($HTTP_POST_VARS['avaliable_day_date_'.$total_no_dates]>=date("Y-m-d"))
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
									if(tep_db_prepare_input($HTTP_POST_VARS['departure_address_'.$departure_places]) != '' && tep_db_prepare_input($HTTP_POST_VARS['depart_time_'.$departure_places]) != '' && tep_db_prepare_input($HTTP_POST_VARS['regioninsertid_'.$departure_places]) == $departure_places )
									{
										$sql_data_departure_places = array('products_id' => $products_id,
																	'departure_address' => tep_db_prepare_input($HTTP_POST_VARS['departure_address_'.$departure_places]),
																	'departure_full_address' => tep_db_prepare_input($HTTP_POST_VARS['departure_full_address_'.$departure_places]),
																	'departure_time' => tep_db_prepare_input($HTTP_POST_VARS['depart_time_'.$departure_places]),
																	'departure_region' => tep_db_prepare_input($HTTP_POST_VARS['depart_region_'.$departure_places]),
																	'map_path' => tep_db_prepare_input($HTTP_POST_VARS['departure_map_path_'.$departure_places]));
													
										tep_db_perform(TABLE_PRODUCTS_DEPARTURE, $sql_data_departure_places);
										
									}
								 }
							 }
							
							//***************************************** end code for updating "Departure" *******************//
							 
					  }		//end of the updation 		  
	/********************END  UPDATION OF PRODUCTS ************************/				  
	      $products_date_available = tep_db_prepare_input($HTTP_POST_VARS['products_date_available']);

          $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

			
		 $urlname = tep_db_prepare_input($HTTP_POST_VARS['products_urlname']);
         if(!tep_not_null($urlname)){
		  $urlname = seo_generate_urlname(tep_db_prepare_input($HTTP_POST_VARS['products_name'][1]));
		  }else {		  
		  $urlname = seo_generate_urlname($urlname);
		  }		
		  
		if($HTTP_POST_VARS['display_room_option'] == 0)
		  {
		  	$HTTP_POST_VARS['products_single'] = $HTTP_POST_VARS['products_single1'];
			$HTTP_POST_VARS['products_kids'] = $HTTP_POST_VARS['products_kids1'];
		  }
		  
		  if($HTTP_POST_VARS['products_video'] != '' && $HTTP_POST_VARS['products_previous_video'] != '' && ($HTTP_POST_VARS['products_video'] != $HTTP_POST_VARS['products_previous_video']) && substr($HTTP_POST_VARS['products_previous_video'],0,4) != 'http:' ){
		  	@unlink(DIR_FS_CATALOG_VIDEO . $HTTP_POST_VARS['products_previous_video']);
		  }
		  $stores_departure_end_city_ids = '';
		  $selected_departure_end_city_id = $HTTP_POST_VARS['departure_end_city_id'];
     	  if (!$selected_departure_end_city_id) $stores_departure_end_city_ids = 0;
          
		  if(is_array($selected_departure_end_city_id)){
			  foreach($selected_departure_end_city_id as $current_departure_end_city_id)
			  {
					$stores_departure_end_city_ids .= (int)$current_departure_end_city_id.",";
			  }
		  }
		  $stores_departure_end_city_ids = substr($stores_departure_end_city_ids, 0, -1);
		  
		  
		  $stores_departure_start_city_ids = '';
		  $selected_departure_start_city_id = $HTTP_POST_VARS['departure_city_id'];
     	  if (!$selected_departure_start_city_id) $stores_departure_start_city_ids = 0;
          
		  if(is_array($selected_departure_start_city_id)){
			  foreach($selected_departure_start_city_id as $current_departure_start_city_id)
			  {
					$stores_departure_start_city_ids .= (int)$current_departure_start_city_id.",";
			  }
		  }
		  $stores_departure_start_city_ids = substr($stores_departure_start_city_ids, 0, -1);
		  
		  
		  if(!isset($HTTP_POST_VARS['display_pickup_hotels'])){
		 	 $HTTP_POST_VARS['display_pickup_hotels'] = 0;
		  }
		  
		  
		   $sql_data_array = array(
		   						  'departure_city_id' => tep_db_prepare_input($stores_departure_start_city_ids),
								  'departure_end_city_id' => tep_db_prepare_input($stores_departure_end_city_ids),
								  'display_pickup_hotels' => tep_db_prepare_input($HTTP_POST_VARS['display_pickup_hotels']),
								  'products_model' => tep_db_prepare_input($HTTP_POST_VARS['products_model']),
								  'provider_tour_code' => tep_db_prepare_input($HTTP_POST_VARS['provider_tour_code']),
                                  'products_urlname' => $urlname,
								  'regions_id' => tep_db_prepare_input($HTTP_POST_VARS['regions_id']),
								  'products_price' => tep_db_prepare_input($HTTP_POST_VARS['products_price']),
                                  'products_date_available' => $products_date_available,
                                  'products_durations' => tep_db_prepare_input($HTTP_POST_VARS['products_durations']),
                                  'products_durations_type' => tep_db_prepare_input($HTTP_POST_VARS['products_durations_type']),
                              	  'display_room_option' => tep_db_prepare_input($HTTP_POST_VARS['display_room_option']),
								  'maximum_no_of_guest' => tep_db_prepare_input($HTTP_POST_VARS['maximum_no_of_guest']),
								  'products_single' => tep_db_prepare_input($HTTP_POST_VARS['products_single']),
								  'products_double' => tep_db_prepare_input($HTTP_POST_VARS['products_double']),
								  'products_triple' => tep_db_prepare_input($HTTP_POST_VARS['products_triple']),
								  'products_quadr' => tep_db_prepare_input($HTTP_POST_VARS['products_quadr']),
								  'products_kids' => tep_db_prepare_input($HTTP_POST_VARS['products_kids']),
                                  'products_status' => tep_db_prepare_input($HTTP_POST_VARS['products_status']),
								  'display_itinerary_notes' => tep_db_prepare_input($HTTP_POST_VARS['display_itinerary_notes']),
								  'display_hotel_upgrade_notes' => tep_db_prepare_input($HTTP_POST_VARS['display_hotel_upgrade_notes']),
								  'products_vacation_package' => tep_db_prepare_input($HTTP_POST_VARS['products_vacation_package']),
								  'products_type' => tep_db_prepare_input($HTTP_POST_VARS['products_type']),												
                                  'products_tax_class_id' => tep_db_prepare_input($HTTP_POST_VARS['products_tax_class_id']),
								  'agency_id' => tep_db_prepare_input($HTTP_POST_VARS['agency_id']),
								  'products_special_note' => tep_db_prepare_input($HTTP_POST_VARS['products_special_note']),
								  'products_video' => tep_db_prepare_input($HTTP_POST_VARS['products_video']),
                                  'manufacturers_id' => tep_db_prepare_input($HTTP_POST_VARS['manufacturers_id']));

// BOF MaxiDVD: Modified For Ultimate Images Pack!
       if (($HTTP_POST_VARS['unlink_image'] == 'yes') or ($HTTP_POST_VARS['delete_image'] == 'yes')) {
            $sql_data_array['products_image'] = '';
           } else {
         if (isset($HTTP_POST_VARS['products_image']) && tep_not_null($HTTP_POST_VARS['products_image']) && ($HTTP_POST_VARS['products_image'] != 'none')) {
            $sql_data_array['products_image'] = tep_db_prepare_input($HTTP_POST_VARS['products_image']);
          }
          }
		if (($HTTP_POST_VARS['unlink_map'] == 'yes') or ($HTTP_POST_VARS['delete_map'] == 'yes')) {
            $sql_data_array['products_map'] = '';
           } else {
         if (isset($HTTP_POST_VARS['products_map']) && tep_not_null($HTTP_POST_VARS['products_map']) && ($HTTP_POST_VARS['products_map'] != 'none')) {
            $sql_data_array['products_map'] = tep_db_prepare_input($HTTP_POST_VARS['products_map']);
          }
          }
       if (($HTTP_POST_VARS['unlink_image_med'] == 'yes') or ($HTTP_POST_VARS['delete_image_med'] == 'yes')) {

            $sql_data_array['products_image_med'] = '';
			$sql_data_array['products_image'] = '';
           } else {
          if (isset($HTTP_POST_VARS['products_image_med']) && tep_not_null($HTTP_POST_VARS['products_image_med']) && ($HTTP_POST_VARS['products_image_med'] != 'none')) {
            $sql_data_array['products_image_med'] = tep_db_prepare_input($HTTP_POST_VARS['products_image_med']);
			$sql_data_array['products_image'] = 'thumb_'.tep_db_prepare_input($HTTP_POST_VARS['products_image_med']);
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

            /*foreach($sql_data_array as $key=>$val)
			{
				echo "$key=>$val";
			}*/
			//exit;
			tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
            $products_id = tep_db_insert_id();

            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$current_category_id . "')");
			foreach($_POST['cat_intro_sort_order'] as $key=>$val){				
				if( $val != ''){					
					if(isset($_POST['db_categories_introduction_id'][$key]) && $_POST['db_categories_introduction_id'][$key] !=''){					
						
						if($_POST['remove_id_form_db'][$key] == 'on'){							
							//simple delete form db
							if($_POST['previouse_image_introfile'][$key] != ''){
										//unlink previouse image
								@unlink(DIR_FS_CATALOG_IMAGES . $_POST['previouse_image_introfile'][$key]);
							}
							tep_db_query("delete from " . TABLE_PRODUCTS_EXTRA_IMAGES . " where prod_extra_image_id = '" . (int)$_POST['db_categories_introduction_id'][$key] . "'");
						}else{
								if($_POST['cat_image_introfile'][$key] != '' ){
										//wirte code for upload new image												
										$categories_introduction_image_name = $_POST['cat_image_introfile'][$key];
										
										//delete previouse image
										if($_POST['previouse_image_introfile'][$key] != '' && $_POST['cat_image_introfile'][$key] != $_POST['previouse_image_introfile'][$key]){
										   @unlink(DIR_FS_CATALOG_IMAGES . $_POST['previouse_image_introfile'][$key]);
										}
								}else{
										//assine image name form privouse value
										
										$categories_introduction_image_name = $_POST['previouse_image_introfile'][$key];
								}
								if($categories_introduction_image_name!=''){
								$sql_data_array_intorupdate = array(
											 'products_id' => $products_id,
											 'product_image_name' => $categories_introduction_image_name,
											 'image_sort_order' => tep_db_prepare_input($_POST['cat_intro_sort_order'][$key])
											 );
				
							 tep_db_perform(TABLE_PRODUCTS_EXTRA_IMAGES, $sql_data_array_intorupdate,'update',"prod_extra_image_id = '" . (int)$_POST['db_categories_introduction_id'][$key] . "'");
							 }
						}
						
						
					}else{
							//add new seciton
							
							if($_POST['cat_image_introfile'][$key] != '' ){
										//wirte code for upload new image									
								$categories_introduction_image_name = $_POST['cat_image_introfile'][$key];
								//delete previouse image
								if($_POST['previouse_image_introfile'][$key] != '' && $_POST['cat_image_introfile'][$key] != $_POST['previouse_image_introfile'][$key]){
								   @unlink(DIR_FS_CATALOG_IMAGES . $_POST['previouse_image_introfile'][$key]);
								}
							}else{
								$categories_introduction_image_name = '';
							}
				if($categories_introduction_image_name!=''){
							$sql_data_array_intoradd = array(
											 'products_id' => $products_id,
											 'product_image_name' => $categories_introduction_image_name,
											 'image_sort_order' => tep_db_prepare_input($_POST['cat_intro_sort_order'][$key])
											 );
											 
							tep_db_perform(TABLE_PRODUCTS_EXTRA_IMAGES, $sql_data_array_intoradd);
				}
					} //endo of check exiting
				
				}
			}
          } 
		  elseif ($action == 'update_product') 
		  {
            $update_sql_data = array('products_last_modified' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $update_sql_data);

            tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
          }
			
			
//amit added to generate auto tour code start
	 $model_update_sql_data = array(			 								
									'products_model' => tep_get_auto_generate_tourcode(tep_db_prepare_input($HTTP_POST_VARS['products_model']),$HTTP_POST_VARS['agency_id'],$stores_departure_start_city_ids, (int)$products_id)
									);

	tep_db_perform(TABLE_PRODUCTS,  $model_update_sql_data , 'update', "products_id = '" . (int)$products_id . "'");
	//amit added to generate auto tour code end		
				
if($urlname!='')
	{
		$check_same_model =  tep_db_query("select products_urlname from ".TABLE_PRODUCTS."  where products_id not in (".(int)$products_id.") and products_urlname = '".$urlname."' ");
		if(tep_db_num_rows($check_same_model) > 0) {		
				$messageStack->add_session('Please update Product URLname. It is already used for other tour.', 'error');
				//$error='true';
		}		
	}		
				
					   
											
			//********** Code for inserrt update delete  product_start_date/products_available tables*****************//
								$newproductypecode = 1;
								if($HTTP_POST_VARS['products_type']==3 || $newproductypecode == 1)
								{
									
									tep_db_query("delete from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = '" . $product_regular_result['products_id'] . "'");									
									tep_db_query("delete from " . TABLE_PRODUCTS_REG_IRREG_DESCRIPTION . " where products_id = '" . $product_regular_result['products_id'] . "'");
									
									for($no_main_sec=1;$no_main_sec<=$HTTP_POST_VARS['numberofsection'];$no_main_sec++)
									{
										
										
										if($HTTP_POST_VARS['products_is_regular_tour'.$no_main_sec]==0)
										{							
										
										   $sql_data_irregular_description = array('products_id' => $products_id,
																				'products_durations_description' => tep_db_prepare_input($HTTP_POST_VARS['products_durations_description'.$no_main_sec]),
																				'operate_start_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_start_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_year'.$no_main_sec]),
																				'operate_end_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_end_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_year'.$no_main_sec]));
														
											tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DESCRIPTION, $sql_data_irregular_description);
										
												//echo $HTTP_POST_VARS['numberofdates'.$no_main_sec];			
											for($no_dates=1;$no_dates<=$HTTP_POST_VARS['numberofdates'.$no_main_sec];$no_dates++)
											{									
												//echo "<br>".$HTTP_POST_VARS['avaliable_day_date_'.$no_main_sec.'_'.$no_dates];
												if($HTTP_POST_VARS['avaliable_day_date_'.$no_main_sec.'_'.$no_dates]!='')
											{
												$sql_data_irregular_date = array('products_id' => $products_id,
																			//'products_durations_description' => tep_db_prepare_input($HTTP_POST_VARS['products_durations_description'.$no_main_sec]),
																			'available_date' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_date_'.$no_main_sec.'_'.$no_dates]),
																			'extra_charge' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_price_'.$no_main_sec.'_'.$no_dates]),
																			
																			'spe_single' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_single_price_'.$no_main_sec.'_'.$no_dates]),
																			'spe_double' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_double_price_'.$no_main_sec.'_'.$no_dates]),
																			'spe_triple' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_triple_price_'.$no_main_sec.'_'.$no_dates]),
																			'spe_quadruple' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_quadruple_price_'.$no_main_sec.'_'.$no_dates]),
																			'spe_kids' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_kids_price_'.$no_main_sec.'_'.$no_dates]),
																			
																			'prefix' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_prefix_'.$no_main_sec.'_'.$no_dates]),
																			'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['avaliable_day_sort_order_'.$no_main_sec.'_'.$no_dates]),
																			'operate_start_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_start_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_year'.$no_main_sec]),
																			'operate_end_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_end_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_year'.$no_main_sec]));
															
													tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DATE, $sql_data_irregular_date);
											}
												
											}
										}
										elseif($HTTP_POST_VARS['products_is_regular_tour'.$no_main_sec]==1)
										{										
											
											
											for($daysofweek=1;$daysofweek<8;$daysofweek++)
											{
												
												if(isset($HTTP_POST_VARS['weekday_option'.$no_main_sec."_".$daysofweek]))
												{
													$sql_data_irregular_weeday = array('products_id' => $products_id,
																						//products_durations_description' => tep_db_prepare_input($HTTP_POST_VARS['products_durations_description'.$no_main_sec]),
																						'products_start_day' => $daysofweek,
																						'extra_charge' => tep_db_prepare_input($HTTP_POST_VARS['weekday_price'.$no_main_sec.$daysofweek]),
																						'prefix' => tep_db_prepare_input($HTTP_POST_VARS['weekday_prefix'.$no_main_sec.$daysofweek]),
																						'sort_order' => tep_db_prepare_input($HTTP_POST_VARS['weekday_sort_order'.$no_main_sec.$daysofweek]),
																						'operate_start_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_start_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_start_date_year'.$no_main_sec]),
																			'operate_end_date' => tep_db_prepare_input($HTTP_POST_VARS['operate_end_date_month'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_day'.$no_main_sec]."-".$HTTP_POST_VARS['operate_end_date_year'.$no_main_sec]));
														
																tep_db_perform(TABLE_PRODUCTS_REG_IRREG_DATE, $sql_data_irregular_weeday);
															} 
														}
										}
										
										
									}
									
								}
								else{
								tep_db_query("delete from " . TABLE_PRODUCTS_REG_IRREG_DATE . " where products_id = '" . $product_regular_result['products_id'] . "'");//Amit added to delete from regular_irregular table
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
															if($HTTP_POST_VARS['avaliable_day_date_'.$total_no_dates]>=date("Y-m-d"))
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
															if($HTTP_POST_VARS['avaliable_day_date_'.$total_no_dates]>=date("Y-m-d"))
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
								} 
								} 
							//**************************************** end CODING FOR UPDATING "products_is_regular_tour"  *************/
					
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
			/* start  CODE FOR INSERT """""""""Departur""""""" OF THE TOUR */
			if(isset($HTTP_POST_VARS['numberofdaparture']) && ($HTTP_POST_VARS['numberofdaparture'] != '1'))
			{
				
				for($departure_places=1;$departure_places<$HTTP_POST_VARS['numberofdaparture'];$departure_places++)
				{
					if($action == 'insert_product')
					{
						if(tep_db_prepare_input($HTTP_POST_VARS['departure_address_'.$departure_places]) != '' && tep_db_prepare_input($HTTP_POST_VARS['depart_time_'.$departure_places]) != '' && tep_db_prepare_input($HTTP_POST_VARS['regioninsertid_'.$departure_places]) == $departure_places)
						{
						$sql_data_departure_places = array('products_id' => $products_id,
														'departure_address' => tep_db_prepare_input($HTTP_POST_VARS['departure_address_'.$departure_places]),
														'departure_time' => tep_db_prepare_input($HTTP_POST_VARS['depart_time_'.$departure_places]),
														'departure_full_address' => tep_db_prepare_input($HTTP_POST_VARS['departure_full_address_'.$departure_places]),
														'departure_region' => tep_db_prepare_input($HTTP_POST_VARS['depart_region_'.$departure_places]),
														'map_path' => tep_db_prepare_input($HTTP_POST_VARS['departure_map_path_'.$departure_places]));
										
							tep_db_perform(TABLE_PRODUCTS_DEPARTURE, $sql_data_departure_places);
							
							
							//this is to insert how many departure for that particular tour
							
						}
					}
				}
			}
			

          $addition = '';

          do {

              $products_urlname = $urlname . $addition;

              mysql_query("update ". TABLE_PRODUCTS . " set products_urlname = '{$products_urlname}' where products_id = '{$products_id}'");

              if($addition == '') $addition = '-2';

              elseif(preg_match('/^-([0-9]+)$/', $addition, $m)) $addition = '-' . ($m[1] + 1);

          } while (mysql_errno() != 0);

          // SEOurls end


         $languages = tep_get_languages();
         for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
		  
		 $language_id = $languages[$i]['id'];
		 
		 $eticket_itinerary_separated = @implode("!##!", $HTTP_POST_VARS['eticket_itinerary'][$language_id]);
		 $eticket_hotel_separated = @implode("!##!", $HTTP_POST_VARS['eticket_hotel'][$language_id]);
		 $eticket_notes_separated = @implode("!##!", $HTTP_POST_VARS['eticket_notes'][$language_id]);
	
		 $sql_data_array = array('products_name' => tep_db_prepare_input($HTTP_POST_VARS['products_name'][$language_id]),
                                    'products_description' => tep_db_prepare_input($HTTP_POST_VARS['products_description'][$language_id]),
									'products_other_description' => tep_db_prepare_input($HTTP_POST_VARS['products_other_description'][$language_id]),
									'products_package_excludes' => tep_db_prepare_input($HTTP_POST_VARS['products_package_excludes'][$language_id]),
									'products_package_special_notes' => tep_db_prepare_input($HTTP_POST_VARS['products_package_special_notes'][$language_id]),
									'eticket_itinerary' => tep_db_prepare_input($eticket_itinerary_separated),
									'eticket_hotel' => tep_db_prepare_input($eticket_hotel_separated),
									'eticket_notes' => tep_db_prepare_input($eticket_notes_separated),
									'products_pricing_special_notes' => tep_db_prepare_input($HTTP_POST_VARS['products_pricing_special_notes'][$language_id]),									
									'products_small_description' => tep_db_prepare_input($HTTP_POST_VARS['products_small_description'][$language_id]),									
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
		  

// BOF: WebMakers.com Added: Update Product Attributes and Sort Order
          // Update Product Attributes
          $rows = 0;		  
  
           if($HTTP_POST_VARS['agency_id']!=$HTTP_POST_VARS['prev_agency_id'] && $HTTP_POST_VARS['prev_agency_id']!='')
		   {			 
			 tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $HTTP_POST_VARS['products_id'] . "'");
		   }		   
		   $options_query = tep_db_query("select po.products_options_id, po.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " patp where po.language_id = '" . $languages_id . "' and po.products_options_id = patp.products_options_id and patp.agency_id= '".$HTTP_POST_VARS['agency_id']."' order by products_options_sort_order, products_options_name");
		  while ($options = tep_db_fetch_array($options_query)) {				
if($HTTP_POST_VARS['agency_id']!=''){
	  $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p, " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " pavtp where pov.products_options_values_id = p2p.products_options_values_id and pavtp.products_options_id = '" . $options['products_options_id'] . "' and p2p.products_options_id = pavtp.products_options_id and pavtp.agency_id='".$HTTP_POST_VARS['agency_id']."' and pavtp.products_options_values_id = p2p.products_options_values_id  and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
		 
} else {
	 	$values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
} 
while ($values = tep_db_fetch_array($values_query)) {
              $rows ++;
// original              $attributes_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
              $attributes_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix, single_values_price, double_values_price, triple_values_price, quadruple_values_price, kids_values_price,  products_options_sort_order from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
              if (tep_db_num_rows($attributes_query) > 0) {
                $attributes = tep_db_fetch_array($attributes_query);
                if ($HTTP_POST_VARS['option'][$rows]) {
                  if ( ($HTTP_POST_VARS['prefix'][$rows] <> $attributes['price_prefix']) || ($HTTP_POST_VARS['price'][$rows] <> $attributes['options_values_price']) || ($HTTP_POST_VARS['single_price'][$rows] <> $attributes['single_values_price']) || ($HTTP_POST_VARS['double_price'][$rows] <> $attributes['double_values_price']) || ($HTTP_POST_VARS['triple_price'][$rows] <> $attributes['triple_values_price']) || ($HTTP_POST_VARS['quadruple_price'][$rows] <> $attributes['quadruple_values_price']) || ($HTTP_POST_VARS['kids_price'][$rows] <> $attributes['kids_values_price']) || ($HTTP_POST_VARS['products_options_sort_order'][$rows] <> $attributes['products_options_sort_order']) ) {
                    tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_values_price = '" . $HTTP_POST_VARS['price'][$rows] . "', single_values_price='". $HTTP_POST_VARS['single_price'][$rows] ."', double_values_price='". $HTTP_POST_VARS['double_price'][$rows] ."', triple_values_price='". $HTTP_POST_VARS['triple_price'][$rows] ."', quadruple_values_price='". $HTTP_POST_VARS['quadruple_price'][$rows] ."', kids_values_price='". $HTTP_POST_VARS['kids_price'][$rows] ."', price_prefix = '" . $HTTP_POST_VARS['prefix'][$rows] . "', products_options_sort_order = '" . $HTTP_POST_VARS['products_options_sort_order'][$rows] . "'  where products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
                  
				  }
                } else {
                  tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . $attributes['products_attributes_id'] . "'");
                }
              } elseif ($HTTP_POST_VARS['option'][$rows]) {
               // tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $products_id . "', '" . $options['products_options_id'] . "', '" . $values['products_options_values_id'] . "', '" . $HTTP_POST_VARS['price'][$rows] . "', '" . $HTTP_POST_VARS['prefix'][$rows] . "', '". $HTTP_POST_VARS['single_price'][$rows] ."', '" . $HTTP_POST_VARS['double_price'][$rows] . "', '" . $HTTP_POST_VARS['triple_price'][$rows] . "', '" . $HTTP_POST_VARS['quadruple_price'][$rows] . "','" . $HTTP_POST_VARS['kids_price'][$rows] . "', '" . $HTTP_POST_VARS['products_options_sort_order'][$rows] . "')");
			   	$sql_data_array_insert = array(
									'products_id' => $products_id,
									'options_id' => $options['products_options_id'],
									'options_values_id' => $values['products_options_values_id'],
				 					'options_values_price' => $HTTP_POST_VARS['price'][$rows],	
									'price_prefix' => $HTTP_POST_VARS['prefix'][$rows],
									'single_values_price' => $HTTP_POST_VARS['single_price'][$rows],
									'double_values_price' => $HTTP_POST_VARS['double_price'][$rows],
									'triple_values_price' => $HTTP_POST_VARS['triple_price'][$rows],
									'quadruple_values_price' => $HTTP_POST_VARS['quadruple_price'][$rows],
									'kids_values_price' => $HTTP_POST_VARS['kids_price'][$rows],
									'products_options_sort_order' => $HTTP_POST_VARS['products_options_sort_order'][$rows]
									);
									
									tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array_insert);
              }
            }
          }
// EOF: WebMakers.com Added: Update Product Attributes and Sort Order
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
			
			$product_query = tep_db_query("select products_model,provider_tour_code, products_is_regular_tour, departure_city_id, products_urlname, products_image, products_image_med, products_image_lrg, products_price, products_date_added, products_last_modified,  products_durations, products_durations_type, products_durations_description, products_status, featured_products, products_tax_class_id, manufacturers_id, regions_id, agency_id, display_room_option, maximum_no_of_guest, products_single, products_double, products_triple, products_quadr, products_kids, products_ordered, products_quantity, products_weight, products_date_available, products_special_note, products_type, operate_start_date,operate_end_date, products_video, products_vacation_package, departure_end_city_id, display_pickup_hotels, display_itinerary_notes, display_hotel_upgrade_notes, products_margin, products_single_cost, products_double_cost,products_triple_cost, products_quadr_cost, products_kids_cost, products_map from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
            $product = tep_db_fetch_array($product_query);
            
			$insert_products_data_array = array('products_model' => tep_db_prepare_input($product['products_model']),
										'provider_tour_code' => tep_db_prepare_input($product['provider_tour_code']),
                                  		'products_is_regular_tour' => tep_db_prepare_input($product['products_is_regular_tour']),
								  		'departure_city_id' => tep_db_prepare_input($product['departure_city_id']),
										'products_urlname' => tep_db_prepare_input($product['products_urlname']),
										'products_image' => tep_db_prepare_input($product['products_image']),
										'products_image_med' => tep_db_prepare_input($product['products_image_med']),
										'products_image_lrg' => tep_db_prepare_input($product['products_image_lrg']),
										'products_price' => tep_db_prepare_input($product['products_price']),
										'products_date_added' => 'now()',
										'products_last_modified' => tep_db_prepare_input($product['products_last_modified']),
										'products_durations' => tep_db_prepare_input($product['products_durations']),
										'products_durations_type' => tep_db_prepare_input($product['products_durations_type']),
										'products_durations_description' => tep_db_prepare_input($product['products_durations_description']),
										'products_status' => tep_db_prepare_input($product['products_status']),
										'featured_products' => tep_db_prepare_input($product['featured_products']),
										'products_tax_class_id' => tep_db_prepare_input($product['products_tax_class_id']),
										'manufacturers_id' => tep_db_prepare_input($product['manufacturers_id']),
										'regions_id' => tep_db_prepare_input($product['regions_id']),
										'agency_id' => tep_db_prepare_input($product['agency_id']),
										'display_room_option' => tep_db_prepare_input($product['display_room_option']),
										'maximum_no_of_guest' => tep_db_prepare_input($product['maximum_no_of_guest']),
										'products_single' => tep_db_prepare_input($product['products_single']),
										'products_double' => tep_db_prepare_input($product['products_double']),
										'products_triple' => tep_db_prepare_input($product['products_triple']),
										'products_quadr' => tep_db_prepare_input($product['products_quadr']),
										'products_kids' => tep_db_prepare_input($product['products_kids']),
										'products_ordered' => tep_db_prepare_input($product['products_ordered']),
										'products_quantity' => tep_db_prepare_input($product['products_quantity']),
										'products_weight' => tep_db_prepare_input($product['products_weight']),
										'products_date_available' => tep_db_prepare_input($product['products_date_available']),
										'products_special_note' => tep_db_prepare_input($product['products_special_note']),
										'products_type' => tep_db_prepare_input($product['products_type']),
										'operate_start_date' => tep_db_prepare_input($product['operate_start_date']),
										'operate_end_date' => tep_db_prepare_input($product['operate_end_date']),
										'products_video' => tep_db_prepare_input($product['products_video']),
										'products_vacation_package' => tep_db_prepare_input($product['products_vacation_package']),
										'departure_end_city_id' => tep_db_prepare_input($product['departure_end_city_id']),
										'display_pickup_hotels' => tep_db_prepare_input($product['display_pickup_hotels']),
										'display_itinerary_notes' => tep_db_prepare_input($product['display_itinerary_notes']),
										'display_hotel_upgrade_notes' => tep_db_prepare_input($product['display_hotel_upgrade_notes']),
										'products_margin' => tep_db_prepare_input($product['products_margin']),
										'products_single_cost' => tep_db_prepare_input($product['products_single_cost']),
										'products_double_cost' => tep_db_prepare_input($product['products_double_cost']),
										'products_triple_cost' => tep_db_prepare_input($product['products_triple_cost']),
										'products_quadr_cost' => tep_db_prepare_input($product['products_quadr_cost']),
										'products_kids_cost' => tep_db_prepare_input($product['products_kids_cost']),
										'products_map' => tep_db_prepare_input($product['products_map'])
								  		);
			$query = tep_db_perform(TABLE_PRODUCTS, $insert_products_data_array);
			//tep_db_query("insert into " . TABLE_PRODUCTS . " (products_model, products_is_regular_tour, departure_city_id, products_urlname, products_image, products_image_med, c, products_price, products_date_added, products_last_modified,  products_durations, products_durations_type, products_durations_description, products_status, featured_products, products_tax_class_id, manufacturers_id, regions_id, agency_id, display_room_option, maximum_no_of_guest, products_single, products_double, products_triple, products_quadr, products_kids, products_ordered, products_quantity, products_weight, products_date_available, products_special_note, products_type, operate_start_date,operate_end_date, products_video, products_vacation_package, departure_end_city_id, display_pickup_hotels, display_itinerary_notes, display_hotel_upgrade_notes, products_margin, products_single_cost, products_double_cost,products_triple_cost, products_quadr_cost, products_kids_cost, products_map) values ('" . tep_db_input($product['products_model']) . "', '" . tep_db_input($product['products_is_regular_tour']) . "', '".tep_db_input($product['departure_city_id'])."', '".tep_db_input($product['products_urlname'])."', '" . tep_db_input($product['products_image']) . "', '" . tep_db_input($product['products_image_med']) . "', '" . tep_db_input($product['products_image_lrg']) . "', '" . tep_db_input($product['products_price']) . "',  now(), '" . tep_db_input($product['products_date_available']) . "', '" . tep_db_input($product['products_durations']) . "', '" . tep_db_input($product['products_durations_type']) . "', '" . tep_db_input($product['products_durations_description']) . "', '0', '" . (int)$product['products_tax_class_id'] . "', '" . (int)$product['manufacturers_id'] . "' ,'".(int)$product['products_type']."','".$product['operate_start_date']."','".$product['operate_end_date']."','".$product['display_itinerary_notes']."','".$product['display_hotel_upgrade_notes']."' )");
// BOF MaxiDVD: Modified For Ultimate Images Pack!
            $dup_products_id = tep_db_insert_id();
			
			
			//amit added to generate auto tour code start
			 $model_update_sql_data = array(			 								
											'products_model' => tep_get_auto_generate_tourcode(tep_db_prepare_input($product['products_model']),tep_db_prepare_input($product['agency_id']),tep_db_prepare_input($product['departure_city_id']), (int)$dup_products_id)
											);
		
			tep_db_perform(TABLE_PRODUCTS,  $model_update_sql_data , 'update', "products_id = '" . (int)$dup_products_id . "'");
			//amit added to generate auto tour code end	

            $description_query = tep_db_query("select language_id, products_name, products_description, products_small_description, products_pricing_special_notes,  products_other_description, products_package_excludes , products_package_special_notes, eticket_itinerary, eticket_hotel, eticket_notes, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
            while ($description = tep_db_fetch_array($description_query)) {

              $insert_pr_desc_array = array('products_id' => (int)$dup_products_id,
			  								'language_id' => (int)$description['language_id'],
											'products_name' => tep_db_input($description['products_name']),
											'products_description' => tep_db_input($description['products_description']),
											'products_small_description' => tep_db_input($description['products_small_description']),
											'products_pricing_special_notes' => tep_db_input($description['products_pricing_special_notes']),
											'products_other_description' => tep_db_input($description['products_other_description']),
											'products_package_excludes' => tep_db_input($description['products_package_excludes']),
											'products_package_special_notes' => tep_db_input($description['products_package_special_notes']),
											'eticket_itinerary' => tep_db_input($description['eticket_itinerary']),
											'eticket_hotel' => tep_db_input($description['eticket_hotel']),
											'eticket_notes' => tep_db_input($description['eticket_notes']),
											'products_head_title_tag' => tep_db_input($description['products_head_title_tag']),
											'products_head_desc_tag' => tep_db_input($description['products_head_desc_tag']),
											'products_head_keywords_tag' => tep_db_input($description['products_head_keywords_tag']),
											'products_url' => tep_db_input($description['products_url']),
											'products_viewed' => 0
								  		);
			$query = tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $insert_pr_desc_array);
			  //tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_pricing_special_notes, products_other_description, products_package_excludes , products_package_special_notes , eticket_itinerary, eticket_hotel, eticket_notes, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url, products_viewed) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '" . tep_db_input($description['products_description']) . "','" . tep_db_input($description['products_pricing_special_notes']) . "', '" . tep_db_input($description['products_other_description']) . "', '" . tep_db_input($description['products_package_excludes']) . "','" . tep_db_input($description['products_package_special_notes']) . "','" . tep_db_input($description['eticket_itinerary']) . "','" . tep_db_input($description['eticket_hotel']) . "', '" . tep_db_input($description['eticket_notes']) . "','" . tep_db_input($description['products_head_title_tag']) . "', '" . tep_db_input($description['products_head_desc_tag']) . "', '" . tep_db_input($description['products_head_keywords_tag']) . "', '" . tep_db_input($description['products_url']) . "', '0')");
            }

            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");
// BOF: WebMakers.com Added: Attributes Copy on non-linked
            $products_id_from=tep_db_input($products_id);
            $products_id_to= $dup_products_id;
            $products_id = $dup_products_id;
			tep_copy_reg_irreg_date($products_id_from,$products_id_to);
if ( $HTTP_POST_VARS['copy_attributes']=='copy_attributes_yes' and $HTTP_POST_VARS['copy_as'] == 'duplicate' ) {
// WebMakers.com Added: Copy attributes to duplicate product

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
					  
	if(($HTTP_POST_VARS['products_video_url'] != '') or (basename($_FILES['products_video']['name'] != ''))){
			$products_video = '';				  
			if($HTTP_POST_VARS['products_video_url'] != ''){
				$products_video_name = $HTTP_POST_VARS['products_video_url'];
				
			}else if(basename($_FILES['products_video']['name']) != '') {
				
				
				$products_video = new upload('products_video');
				$products_video->set_destination(DIR_FS_CATALOG_VIDEO);
				if ($products_video->parse() && $products_video->save()) {
				  $products_video_name = $products_video->filename;
				} else {
				  $products_video_name = (isset($HTTP_POST_VARS['products_previous_video']) ? $HTTP_POST_VARS['products_previous_video'] : '');
				}
				
				//add delete to previouse start
				//add delete to previos end
			}	
	}else{
	  $products_video_name = (isset($HTTP_POST_VARS['products_previous_video']) ? $HTTP_POST_VARS['products_previous_video'] : '');
				
	}
					
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
		
		if (($HTTP_POST_VARS['unlink_map'] == 'yes') or ($HTTP_POST_VARS['delete_map'] == 'yes')) {
        $products_map = '';
        $products_map_name = '';
        } elseif ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')) {
        $products_map = new upload('products_map');
        $products_map->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_map->parse() && $products_map->save()) {
          $products_map_name = $products_map->filename;
        } else {
          $products_map_name = (isset($HTTP_POST_VARS['products_previous_map']) ? $HTTP_POST_VARS['products_previous_map'] : '');
        }
        } else {
          if (isset($HTTP_POST_VARS['products_map']) && tep_not_null($HTTP_POST_VARS['products_map']) && ($HTTP_POST_VARS['products_map'] != 'none')) {
            $products_map_name = $HTTP_POST_VARS['products_map'];
          } else {
            $products_map_name = (isset($HTTP_POST_VARS['products_previous_map']) ? $HTTP_POST_VARS['products_previous_map'] : '');
          }
        }
  if (($HTTP_POST_VARS['unlink_image_med'] == 'yes') or ($HTTP_POST_VARS['delete_image_med'] == 'yes')) {
        $products_image_med = '';
        $products_image_med_name = '';
		
		$products_image = '';
        $products_image_name = '';
        } elseif ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')) {
        $products_image_med = new upload('products_image_med');
        $products_image_med->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($products_image_med->parse() && $products_image_med->save()) {
          $products_image_med_name = $products_image_med->filename;
		  //added for auto thumb start
		   $uploadfile = DIR_FS_CATALOG_IMAGES.$products_image_med_name;
			$target = DIR_FS_CATALOG_IMAGES;
			$size = getimagesize($uploadfile);
			$width = $size[0];
			//exit;
			if($width>159)
			{
				$thumb_image = 1;
				$new_height = 103;
				$new_width = 159;
				imageCompression($uploadfile,159,$target  .'thumb_'. $products_image_med_name,$new_width,$new_height);
			}
			else
			{
				imageCompression($uploadfile,$width,$target  .'thumb_'. $products_image_med_name);
			}		  
		  //added for auto thumb end
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
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript"  src="datetimepicker.js"></script>
<script type="text/javascript" src="includes/javascript/categories.js"></script>
<script type="text/javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=900,height=400,screenX=150,screenY=50,top=150,left=50')
}
//--></script>
<script type="text/javascript">
//** Ajax Tabs Content script v2.0- ?Dynamic Drive DHTML code library (http://www.dynamicdrive.com)
//** Updated Oct 21st, 07 to version 2.0. Contains numerous improvements
//** Updated Feb 18th, 08 to version 2.1: Adds a public "tabinstance.cycleit(dir)" method to cycle forward or backward between tabs dynamically. Only .js file changed from v2.0.
//** Updated April 8th, 08 to version 2.2:
//   -Adds support for expanding a tab using a URL parameter (ie: http://mysite.com/tabcontent.htm?tabinterfaceid=0) 
//   -Modified Ajax routine so testing the script out locally in IE7 now works 

var ddajaxtabssettings={}
ddajaxtabssettings.bustcachevar=1  //bust potential caching of external pages after initial request? (1=yes, 0=no)
ddajaxtabssettings.loadstatustext="<img src='images/loading.gif' /> Requesting content..." 


////NO NEED TO EDIT BELOW////////////////////////

function ddajaxtabs(tabinterfaceid, contentdivid){
	this.tabinterfaceid=tabinterfaceid //ID of Tab Menu main container
	this.tabs=document.getElementById(tabinterfaceid).getElementsByTagName("a") //Get all tab links within container
	this.enabletabpersistence=true
	this.hottabspositions=[] //Array to store position of tabs that have a "rel" attr defined, relative to all tab links, within container
	this.currentTabIndex=0 //Index of currently selected hot tab (tab with sub content) within hottabspositions[] array
	this.contentdivid=contentdivid
	this.defaultHTML=""
	this.defaultIframe='<iframe src="about:blank" marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0" class="tabcontentiframe" style="width:100%; height:auto; min-height: 100px"></iframe>'
	this.defaultIframe=this.defaultIframe.replace(/<iframe/i, '<iframe name="'+"_ddajaxtabsiframe-"+contentdivid+'" ')
this.revcontentids=[] //Array to store ids of arbitrary contents to expand/contact as well ("rev" attr values)
	this.selectedClassTarget="link" //keyword to indicate which target element to assign "selected" CSS class ("linkparent" or "link")
}

ddajaxtabs.connect=function(pageurl, tabinstance){
	var page_request = false
	var bustcacheparameter=""
	if (window.ActiveXObject){ //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
		try {
		page_request = new ActiveXObject("Msxml2.XMLHTTP")
		} 
		catch (e){
			try{
			page_request = new ActiveXObject("Microsoft.XMLHTTP")
			}
			catch (e){}
		}
	}
	else if (window.XMLHttpRequest) // if Mozilla, Safari etc
		page_request = new XMLHttpRequest()
	else
		return false
	var ajaxfriendlyurl=pageurl.replace(/^http:\/\/[^\/]+\//i, "http://"+window.location.hostname+"/") 
	page_request.onreadystatechange=function(){ddajaxtabs.loadpage(page_request, pageurl, tabinstance)}
	if (ddajaxtabssettings.bustcachevar) //if bust caching of external page
		bustcacheparameter=(ajaxfriendlyurl.indexOf("?")!=-1)? "&"+new Date().getTime() : "?"+new Date().getTime()
	page_request.open('GET', ajaxfriendlyurl+bustcacheparameter, true)
	page_request.send(null)
}

ddajaxtabs.loadpage=function(page_request, pageurl, tabinstance){
	var divId=tabinstance.contentdivid
	document.getElementById(divId).innerHTML=ddajaxtabssettings.loadstatustext //Display "fetching page message"
	if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("http")==-1)){
		document.getElementById(divId).innerHTML=page_request.responseText
		ddajaxtabs.ajaxpageloadaction(pageurl, tabinstance)
	}
}

ddajaxtabs.ajaxpageloadaction=function(pageurl, tabinstance){
	tabinstance.onajaxpageload(pageurl) //call user customized onajaxpageload() function when an ajax page is fetched/ loaded
}

ddajaxtabs.getCookie=function(Name){ 
	var re=new RegExp(Name+"=[^;]+", "i"); //construct RE to search for target name/value pair
	if (document.cookie.match(re)) //if cookie found
		return document.cookie.match(re)[0].split("=")[1] //return its value
	return ""
}

ddajaxtabs.setCookie=function(name, value){
	document.cookie = name+"="+value+";path=/" //cookie value is domain wide (path=/)
}

ddajaxtabs.prototype={

	expandit:function(tabid_or_position){ //PUBLIC function to select a tab either by its ID or position(int) within its peers
		this.cancelautorun() //stop auto cycling of tabs (if running)
		var tabref=""
		try{
			if (typeof tabid_or_position=="string" && document.getElementById(tabid_or_position).getAttribute("rel")) //if specified tab contains "rel" attr
				tabref=document.getElementById(tabid_or_position)
			else if (parseInt(tabid_or_position)!=NaN && this.tabs[tabid_or_position].getAttribute("rel")) //if specified tab contains "rel" attr
				tabref=this.tabs[tabid_or_position]
		}
		catch(err){alert("Invalid Tab ID or position entered!")}
		if (tabref!="") //if a valid tab is found based on function parameter
			this.expandtab(tabref) //expand this tab
	},

	cycleit:function(dir, autorun){ //PUBLIC function to move foward or backwards through each hot tab (tabinstance.cycleit('foward/back') )
		if (dir=="next"){
			var currentTabIndex=(this.currentTabIndex<this.hottabspositions.length-1)? this.currentTabIndex+1 : 0
		}
		else if (dir=="prev"){
			var currentTabIndex=(this.currentTabIndex>0)? this.currentTabIndex-1 : this.hottabspositions.length-1
		}
		if (typeof autorun=="undefined") //if cycleit() is being called by user, versus autorun() function
			this.cancelautorun() //stop auto cycling of tabs (if running)
		this.expandtab(this.tabs[this.hottabspositions[currentTabIndex]])
	},

	setpersist:function(bool){ //PUBLIC function to toggle persistence feature
			this.enabletabpersistence=bool
	},

	loadajaxpage:function(pageurl){ //PUBLIC function to fetch a page via Ajax and display it within the Tab Content instance's container
		ddajaxtabs.connect(pageurl, this)
	},

	loadiframepage:function(pageurl){ //PUBLIC function to fetch a page and load it into the IFRAME of the Tab Content instance's container
		this.iframedisplay(pageurl, this.contentdivid)
	},

	setselectedClassTarget:function(objstr){ //PUBLIC function to set which target element to assign "selected" CSS class ("linkparent" or "link")
		this.selectedClassTarget=objstr || "link"
	},

	getselectedClassTarget:function(tabref){ //Returns target element to assign "selected" CSS class to
		return (this.selectedClassTarget==("linkparent".toLowerCase()))? tabref.parentNode : tabref
	},

	urlparamselect:function(tabinterfaceid){
		var result=window.location.search.match(new RegExp(tabinterfaceid+"=(\\d+)", "i")) //check for "?tabinterfaceid=2" in URL
		return (result==null)? null : parseInt(RegExp.$1) //returns null or index, where index (int) is the selected tab's index
	},

	onajaxpageload:function(pageurl){ //PUBLIC Event handler that can invoke custom code whenever an Ajax page has been fetched and displayed
		//do nothing by default
	},

	expandtab:function(tabref){
		var relattrvalue=tabref.getAttribute("rel")
		//Get "rev" attr as a string of IDs in the format ",john,george,trey,etc," to easy searching through
		var associatedrevids=(tabref.getAttribute("rev"))? ","+tabref.getAttribute("rev").replace(/\s+/, "")+"," : ""
		if (relattrvalue=="#default")
			document.getElementById(this.contentdivid).innerHTML=this.defaultHTML
		else if (relattrvalue=="#iframe")
			this.iframedisplay(tabref.getAttribute("href"), this.contentdivid)
		else
			ddajaxtabs.connect(tabref.getAttribute("href"), this)
		this.expandrevcontent(associatedrevids)
		for (var i=0; i<this.tabs.length; i++){ //Loop through all tabs, and assign only the selected tab the CSS class "selected"
			this.getselectedClassTarget(this.tabs[i]).className=(this.tabs[i].getAttribute("href")==tabref.getAttribute("href"))? "selected" : ""
		}
		if (this.enabletabpersistence) //if persistence enabled, save selected tab position(int) relative to its peers
			ddajaxtabs.setCookie(this.tabinterfaceid, tabref.tabposition)
		this.setcurrenttabindex(tabref.tabposition) //remember position of selected tab within hottabspositions[] array
	},

	iframedisplay:function(pageurl, contentdivid){
		if (typeof window.frames["_ddajaxtabsiframe-"+contentdivid]!="undefined"){
			try{delete window.frames["_ddajaxtabsiframe-"+contentdivid]} //delete iframe within Tab content container if it exists (due to bug in Firefox)
			catch(err){}
		}
		document.getElementById(contentdivid).innerHTML=this.defaultIframe
		window.frames["_ddajaxtabsiframe-"+contentdivid].location.replace(pageurl) //load desired page into iframe
	},


	expandrevcontent:function(associatedrevids){
		var allrevids=this.revcontentids
		for (var i=0; i<allrevids.length; i++){ //Loop through rev attributes for all tabs in this tab interface
			//if any values stored within associatedrevids matches one within allrevids, expand that DIV, otherwise, contract it
			document.getElementById(allrevids[i]).style.display=(associatedrevids.indexOf(","+allrevids[i]+",")!=-1)? "block" : "none"
		}
	},

	setcurrenttabindex:function(tabposition){ //store current position of tab (within hottabspositions[] array)
		for (var i=0; i<this.hottabspositions.length; i++){
			if (tabposition==this.hottabspositions[i]){
				this.currentTabIndex=i
				break
			}
		}
	},

	autorun:function(){ //function to auto cycle through and select tabs based on a set interval
		this.cycleit('next', true)
	},

	cancelautorun:function(){
		if (typeof this.autoruntimer!="undefined")
			clearInterval(this.autoruntimer)
	},

	init:function(automodeperiod){
		var persistedtab=ddajaxtabs.getCookie(this.tabinterfaceid) //get position of persisted tab (applicable if persistence is enabled)
		var selectedtab=-1 //Currently selected tab index (-1 meaning none)
		var selectedtabfromurl=this.urlparamselect(this.tabinterfaceid) //returns null or index from: tabcontent.htm?tabinterfaceid=index
		this.automodeperiod=automodeperiod || 0
		this.defaultHTML=document.getElementById(this.contentdivid).innerHTML
		for (var i=0; i<this.tabs.length; i++){
			this.tabs[i].tabposition=i //remember position of tab relative to its peers
			if (this.tabs[i].getAttribute("rel")){
				var tabinstance=this
				this.hottabspositions[this.hottabspositions.length]=i //store position of "hot" tab ("rel" attr defined) relative to its peers
				this.tabs[i].onclick=function(){
					tabinstance.expandtab(this)
					tabinstance.cancelautorun() //stop auto cycling of tabs (if running)
					return false
				}
				if (this.tabs[i].getAttribute("rev")){ //if "rev" attr defined, store each value within "rev" as an array element
					this.revcontentids=this.revcontentids.concat(this.tabs[i].getAttribute("rev").split(/\s*,\s*/))
				}
				if (selectedtabfromurl==i || this.enabletabpersistence && selectedtab==-1 && parseInt(persistedtab)==i || !this.enabletabpersistence && selectedtab==-1 && this.getselectedClassTarget(this.tabs[i]).className=="selected"){
					selectedtab=i //Selected tab index, if found
				}
			}
		} //END for loop
		if (selectedtab!=-1) //if a valid default selected tab index is found
			this.expandtab(this.tabs[selectedtab]) //expand selected tab (either from URL parameter, persistent feature, or class="selected" class)
		else //if no valid default selected index found
			this.expandtab(this.tabs[this.hottabspositions[0]]) //Just select first tab that contains a "rel" attr
		if (parseInt(this.automodeperiod)>500 && this.hottabspositions.length>1){
			this.autoruntimer=setInterval(function(){tabinstance.autorun()}, this.automodeperiod)
		}
	} //END int() function

} //END Prototype assignment



</script>
<?php 
require('includes/javascript/categories.js.php'); ?>
<?php  //$request_type = SSL; 
if ((HTML_AREA_WYSIWYG_DISABLE == 'Enable') or (HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Enable')) { ?>
<script language="Javascript1.2"><!--

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

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

    </table></td> 
	     <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
	<?php   //----- new_category / edit_category (when ALLOW_CATEGORY_DESCRIPTIONS is 'true') -----
  if ($HTTP_GET_VARS['action'] == 'new_category_ACD' || $HTTP_GET_VARS['action'] == 'edit_category_ACD') {
    if ( ($HTTP_GET_VARS['cID']) && (!$HTTP_POST_VARS) ) {
      $categories_query = tep_db_query("select cd.categories_video, cd.categories_video_description, cd.categories_seo_description, cd.categories_logo_alt_tag, cd.categories_top_banner_image_alt_tag, cd.categories_first_sentence, c.categories_status, c.categories_id, c.categories_urlname, cd.categories_name, cd.categories_heading_title, cd.categories_description, cd.categories_recommended_tours, c.categories_recommended_tours_ids,cd.categories_map, cd.categories_head_title_tag, cd.categories_head_desc_tag, cd.categories_head_keywords_tag, c.categories_image, c.categories_map_image, c.categories_banner_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $HTTP_GET_VARS['cID'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");
      $category = tep_db_fetch_array($categories_query);

      $cInfo = new objectInfo($category);
    } elseif ($HTTP_POST_VARS) {
      $cInfo = new objectInfo($HTTP_POST_VARS);
      $categories_name = $HTTP_POST_VARS['categories_name'];
      $categories_heading_title = $HTTP_POST_VARS['categories_heading_title'];
      $categories_description = $HTTP_POST_VARS['categories_description'];
	  $categories_recommended_tours = $HTTP_POST_VARS['categories_recommended_tours'];	  
	  $categories_map = $HTTP_POST_VARS['categories_map'];		   
      $categories_head_title_tag = $HTTP_POST_VARS['categories_head_title_tag'];
      $categories_head_desc_tag = $HTTP_POST_VARS['categories_head_desc_tag'];
      $categories_head_keywords_tag = $HTTP_POST_VARS['categories_head_keywords_tag'];
      $categories_url = $HTTP_POST_VARS['categories_url'];
	  $categories_status = $HTTP_POST_VARS['categories_status'];
	  $categories_video = $HTTP_POST_VARS['categories_video'];
	  $categories_video_description = $HTTP_POST_VARS['categories_video_description'];
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
      <tr>
        <td><?php echo tep_draw_form('new_category', FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID'] . '&action=new_category_preview', 'post', 'enctype="multipart/form-data"'); ?><table border="0" cellspacing="0" cellpadding="2">
	   
	   <tr>
	   <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
       </tr>
	   <tr>
            <td class="main" ><?php echo TEXT_CATEGORY_STATUS; ?></td>
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
			<td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;'. tep_draw_input_field('categories_urlname', $cInfo->categories_urlname, 'size="50"'); ?></td>
          </tr>
<?php
    }
?>		  <tr>
            <td colspan="2" ><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
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
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

		     <tr>
            <td class="main"><?php echo TEXT_EDIT_CATEGORIES_INTRODUCTION_VIDEO; ?></td>
            <td class="main">
					<small>
					Direct URL
					</small>&nbsp;&nbsp;<?php echo tep_draw_input_field('categories_video_url', '', 'size="30"'); ?>
					<?php
					if(substr($cInfo->categories_video,0,5) == 'http:'){
					echo "<br>".$cInfo->categories_video;
					$directvurl="show";
					}
					?>						
					<br><small>or</small><br>
					<small>Upload Video</small>&nbsp;&nbsp;
					<?php echo tep_draw_file_field('categories_video').tep_draw_hidden_field('categories_previous_video', $cInfo->categories_video); ?>
					<br>
					<?php
					if($directvurl != "show" && $cInfo->categories_video != '' ){
					echo $cInfo->categories_video;
					}
					?>
			
			</td>
          </tr>		
<tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
           <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_INTRODUCTION_VIDEO_DISCRIPTION; ?></td>
           <td class="main" valign="top">
		   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
				<td><?php echo tep_draw_textarea_field('categories_video_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($categories_video_description[$languages[$i]['id']]) ? stripslashes($categories_video_description[$languages[$i]['id']]) : stripslashes(tep_get_categories_video_description($cInfo->categories_id, $languages[$i]['id'])))); ?>
				</td>
			  </tr>
			</table>
			</td>
			</tr>

<?php
    }
?>
  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  
		    <tr>
			 <td class="main" valign="top"><?php echo TEXT_EDIT_CATEGORIES_INTRODUCTION;?></td>
            <td class="main" valign="top">
								<div>
								<table width="100%"  border="1" cellspacing="3" cellpadding="3">
								  <tr class="dataTableHeadingRow">
									<td width="33%" class="dataTableHeadingContent">Image</td>
									<td width="30%" class="dataTableHeadingContent">Descirption</td>
									<td width="28%" class="dataTableHeadingContent">Alt Tag</td>
									<td width="3%" nowrap="nowrap" class="dataTableHeadingContent">Sort Order</td>
									<td width="3%" class="dataTableHeadingContent">Remove</td>
								  </tr>
								</table>
								</div>				
								<div id="myDiv">
								<?php
								
								$category_intro_query_sql  = "select *  from " . TABLE_CATEGORIES_DESCRIPTION_INTRODUCTION . " where categories_id = '" . $HTTP_GET_VARS['cID'] . "' order by categories_introduction_sort_order";
								$category_intro_query = tep_db_query($category_intro_query_sql);
								
								$tt_into_cnt_row = tep_db_num_rows($category_intro_query);
								//$category_intro = tep_db_fetch_array($category_intro_query);
								if($tt_into_cnt_row > 0){
									?>
									<input type="hidden" value="<?php echo $tt_into_cnt_row; ?>" id="theValue" />
									<?php
									$row = 1;
									while($category_intro = tep_db_fetch_array($category_intro_query)){
									?>
									<div id="my<?php echo $row;?>Div">
									<table width="100%" border="0" cellspacing="3" cellpadding="3">
									  <tr >
										<td  valign="top">
										<?php
										if($category_intro['categories_introduction_image']!= '') {
										?>
										<img src="<?php echo DIR_FS_CATALOG_IMAGES.$category_intro['categories_introduction_image'];?>" alt="<?php echo $category_intro['categories_introduction_image'];?>" width="150"><br/>
										<?php } ?>
										<input type='file' name='image_introfile[]'>
										<input type="hidden" name="previouse_image_introfile[]" value="<?php echo $category_intro['categories_introduction_image'];?>">
										</td>
										<td valign='top'><textarea name='cat_intro_description_introfile[]' rows="8" cols="28" ><?php echo stripslashes($category_intro['categories_introduction_image_descirption']);?></textarea></td>
										<td valign='top'><textarea name='cat_intro_alt_introfile[]' rows="8" cols="28" ><?php echo stripslashes($category_intro['categories_introduction_image_alt']);?></textarea></td>
										<td valign='top'><input type="text" name='cat_intro_sort_order[]' size="10" value="<?php echo $category_intro['categories_introduction_sort_order'];?>"></td>
										<td valign='top'><input type="hidden" name="db_categories_introduction_id[]" value="<?php echo $category_intro['categories_introduction_id'];?>"> <input type="hidden" id="remove_id_form_db_<?php echo $category_intro['categories_introduction_id'];?>" name="remove_id_form_db[]"  value="off"><input type="checkbox" name="delete_frm_db[]" onClick="document.getElementById('remove_id_form_db_<?php echo $category_intro['categories_introduction_id'];?>').value='on'"></td>
									  </tr>
									</table>
									</div>		
									<?php		
									$row++;
									}
									?>
									<?	
								}else{
								?>
								<input type="hidden" value="1" id="theValue" />
								<div id="my1Div">
								<table width="100%" border="0" cellspacing="3" cellpadding="3">
								  <tr>
									<td valign="top"><input type='file' name='image_introfile[]'>		
									</td>
									<td valign='top'><textarea name='cat_intro_description_introfile[]' rows="8" cols="28" ></textarea></td>
									<td valign='top'><textarea name='cat_intro_alt_introfile[]' rows="8" cols="28" ></textarea></td>
									<td valign='top'><input type="text" name='cat_intro_sort_order[]' size="10" value="1"></td>
									<td valign='top'><a href="javascript:;" onClick="removeEvent('my1Div')">Remove</a></td>
								  </tr>
								</table>
								</div>
								<?php
								}
								?>
								</div>
								<p><a href="javascript:;" onClick="addEvent();"><b><font color="#000099">Add More Introduction Section</font></b></a></p>
			
			</td>
          </tr>
		    <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
           <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_RECOMMENDED_TORUS;?></td>
           <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;
		   <?php echo tep_draw_textarea_field('categories_recommended_tours[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($categories_recommended_tours[$languages[$i]['id']]) ? stripslashes($categories_recommended_tours[$languages[$i]['id']]) : stripslashes(tep_get_categories_recommended_tours($cInfo->categories_id, $languages[$i]['id'])))); ?></td>
   </tr>
<?php
    }
?>
<tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		     <tr>
            <td class="main"><?php echo TEXT_EDIT_CATEGORIES_RECOMMENDED_TORUS.' IDs'; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('categories_recommended_tours_ids',$cInfo->categories_recommended_tours_ids, 'size="60"'); ?><?php echo '<a href="javascript:popupWindowAvailableTour(\'' . tep_href_link(FILENAME_AFFILIATE_VALIDPRODS_CATES) . '\')"><b>' . TEXT_AFFILIATE_VALIDPRODUCTS . '</b></a>'; ?>&nbsp;&nbsp;<?php echo TEXT_AFFILIATE_INDIVIDUAL_BANNER_VIEW;?></td>
          </tr>		 
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
 		 <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		     <tr>
            <td class="main"><?php echo TEXT_EDIT_CATEGORIES_MAP_IMAGE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('categories_map_image') . '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $cInfo->categories_map_image . tep_draw_hidden_field('categories_previous_map_image', $cInfo->categories_map_image); ?></td>
          </tr>		 
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
           <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_MAP;?></td>
           <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;
		   <?php echo tep_draw_textarea_field('categories_map[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($categories_map[$languages[$i]['id']]) ? stripslashes($categories_map[$languages[$i]['id']]) : tep_get_categories_map($cInfo->categories_id, $languages[$i]['id']))); ?></td>
            </tr>

<?php
    }
?>
 <tr>       <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
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
            <td class="main"><?php echo TEXT_EDIT_CATEGORIES_BANNER_IMAGE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('categories_banner_image') . '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $cInfo->categories_banner_image . tep_draw_hidden_field('categories_previous_banner_image', $cInfo->categories_banner_image); ?></td>
          </tr>		 
		   <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  <?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>             </tr>
			     <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_TOP_BANNER_IMAGE_ALT_TAG; ?></td>
	            <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;
			   <?php //echo tep_draw_textarea_field('categories_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($categories_head_keywords_tag[$languages[$i]['id']]) ? stripslashes($categories_head_keywords_tag[$languages[$i]['id']]) : stripslashes(tep_get_category_head_keywords_tag($cInfo->categories_id, $languages[$i]['id'])))); 
			   echo tep_draw_input_field('categories_top_banner_image_alt_tag[' . $languages[$i]['id'] . ']', $cInfo->categories_top_banner_image_alt_tag, 'size="30"')
			   ?></td>
			</tr>
<?php
  }
?>
		  	 
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
        <td class="main" align="right"><?php echo tep_draw_hidden_field('categories_date_added', (($cInfo->date_added) ? $cInfo->date_added : date('Y-m-d'))) . tep_draw_hidden_field('parent_id', $cInfo->parent_id) . tep_image_submit('button_update.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
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
		   editor_generate('categories_recommended_tours[<?php echo $languages[$i]['id']; ?>]',config);
		    editor_generate('categories_map[<?php echo $languages[$i]['id']; ?>]',config);
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
	  $categories_recommended_tours = $HTTP_POST_VARS['categories_recommended_tours'];
	  $categories_map = $HTTP_POST_VARS['categories_map'];
      $categories_head_title_tag = $HTTP_POST_VARS['categories_head_title_tag'];
      $categories_head_desc_tag = $HTTP_POST_VARS['categories_head_desc_tag'];
      $categories_head_keywords_tag = $HTTP_POST_VARS['categories_head_keywords_tag'];
	  $categories_video = $HTTP_POST_VARS['categories_video'];
      $categories_video_description = $HTTP_POST_VARS['categories_video_description'];
// copy image only if modified
        $categories_image = new upload('categories_image');
        $categories_image->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($categories_image->parse() && $categories_image->save()) {
          $categories_image_name = $categories_image->filename;
        } else {
        $categories_image_name = $HTTP_POST_VARS['categories_previous_image'];
      }	  
	  $categories_map_image = new upload('categories_map_image');
        $categories_map_image->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($categories_map_image->parse() && $categories_map_image->save()) {
          $categories_map_image_name = $categories_map_image->filename;		 
        } else {
        $categories_map_image_name = $HTTP_POST_VARS['categories_previous_map_image'];
      }	  
	   $categories_banner_image = new upload('categories_banner_image');
        $categories_banner_image->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($categories_banner_image->parse() && $categories_banner_image->save()) {
          $categories_banner_image_name = $categories_banner_image->filename;		 
        } else {
        $categories_banner_image_name = $HTTP_POST_VARS['categories_previous_banner_image'];
      }
	  if(($HTTP_POST_VARS['categories_video_url'] != '') or (basename($_FILES['categories_video']['name'] != ''))){
			$categories_video = '';				  
			if($HTTP_POST_VARS['categories_video_url'] != ''){
				$categories_video_name = $HTTP_POST_VARS['categories_video_url'];				
			}else if(basename($_FILES['categories_video']['name']) != '') {							
				$categories_video = new upload('categories_video');
				$categories_video->set_destination(DIR_FS_CATALOG_VIDEO);
				if ($categories_video->parse() && $categories_video->save()) {
				  $categories_video_name = $categories_video->filename;
				} else {
				  $categories_video_name = (isset($HTTP_POST_VARS['categories_previous_video']) ? $HTTP_POST_VARS['categories_previous_video'] : '');
				}
				
				//add delete to previouse start
				//add delete to previos end
			}
	
	}else{
	  $categories_video_name = (isset($HTTP_POST_VARS['categories_previous_video']) ? $HTTP_POST_VARS['categories_previous_video'] : '');
				
	}
	
    } else {
      $category_query = tep_db_query("select c.categories_id, cd.language_id, cd.categories_name, cd.categories_heading_title, cd.categories_description, cd.categories_recommended_tours, c.categories_recommended_tours_ids, cd.categories_map, cd.categories_head_title_tag, cd.categories_head_desc_tag, cd.categories_head_keywords_tag, c.categories_image, c.categories_map_image, c.categories_banner_image, c.sort_order, c.date_added, c.last_modified,cd.categories_video, cd.categories_video_description from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and c.categories_id = '" . $HTTP_GET_VARS['cID'] . "'");
      $category = tep_db_fetch_array($category_query);

      $cInfo = new objectInfo($category);
      $categories_image_name = $cInfo->categories_image;
	  $categories_map_image_name = $cInfo->categories_map_image;
	  $categories_banner_image_name = $cInfo->categories_banner_image;
	  $categories_video_name = $cInfo->categories_video;
    }

    $form_action = ($HTTP_GET_VARS['cID']) ? 'update_category' : 'insert_category';

    echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

    $languages = tep_get_languages();
    for ($i=0; $i<sizeof($languages); $i++) {
      if ($HTTP_GET_VARS['read'] == 'only') {
        $cInfo->categories_name = tep_get_category_name($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_heading_title = tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_description = tep_get_category_description($cInfo->categories_id, $languages[$i]['id']);
		 $cInfo->categories_recommended_tours = tep_get_categories_recommended_tours($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->category_template_id = tep_get_category_template_id($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_head_title_tag = tep_get_category_head_title_tag($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_head_desc_tag = tep_get_category_head_desc_tag($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_head_keywords_tag = tep_get_category_head_keywords_tag($cInfo->categories_id, $languages[$i]['id']);
      } else {
        $cInfo->categories_name = tep_db_prepare_input($categories_name[$languages[$i]['id']]);
        $cInfo->categories_heading_title = tep_db_prepare_input($categories_heading_title[$languages[$i]['id']]);
        $cInfo->categories_description = tep_db_prepare_input($categories_description[$languages[$i]['id']]);
		$cInfo->categories_recommended_tours = tep_db_prepare_input($categories_recommended_tours[$languages[$i]['id']]);		
		$cInfo->categories_map = tep_db_prepare_input($categories_map[$languages[$i]['id']]);		
        $cInfo->category_template_id = tep_db_prepare_input($category_template_id[$languages[$i]['id']]);
        $cInfo->categories_head_title_tag = tep_db_prepare_input($categories_head_title_tag[$languages[$i]['id']]);
        $cInfo->categories_head_desc_tag = tep_db_prepare_input($categories_head_desc_tag[$languages[$i]['id']]);
        $cInfo->categories_head_keywords_tag = tep_db_prepare_input($categories_head_keywords_tag[$languages[$i]['id']]);
		$cInfo->categories_video_description = tep_db_prepare_input($categories_video_description[$languages[$i]['id']]);
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
        <td class="main"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $categories_image_name, $cInfo->categories_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"'); //echo $cInfo->categories_description; ?></td>
      </tr>
	   <tr>
        <td class="main"><?php //echo  $cInfo->categories_recommended_tours; ?></td>
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
        <td align="right" class="smallText"><input type="text" name='tempvar_hiddenuse' style="border: 1px solid #FFFFFF"  value="">
<?php
/* Re-Post all POST'ed variables */
 //amit added for upload image for introduction section start	  
	  foreach($_POST['cat_intro_description_introfile'] as $key=>$val){
	
							if(basename($_FILES['image_introfile']['name'][$key]) != '' ){								
								$tmp_categories_introduction_image_name == '';
								$uploadfile = DIR_FS_CATALOG_IMAGES.'cat_intro_'.basename($_FILES['image_introfile']['name'][$key]);
								if (move_uploaded_file($_FILES['image_introfile']['tmp_name'][$key],$uploadfile)) {
									$tmp_categories_introduction_image_name = 'cat_intro_'.basename($_FILES['image_introfile']['name'][$key]);
								} 
								
								echo tep_draw_hidden_field('cat_image_introfile[' . $key . ']', stripslashes($tmp_categories_introduction_image_name));
								
								//echo tep_draw_hidden_field('cat_intro_sort_order[' . $key . ']', stripslashes($_POST['cat_intro_sort_order'][$key]));
								
							}
	
	  }		
	  
	  //amit added for upload image for introduction section end
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
	  
	 echo field_forwarder('cat_intro_description_introfile');
	 echo field_forwarder('cat_intro_sort_order');
	 echo field_forwarder('previouse_image_introfile');
	 echo field_forwarder('db_categories_introduction_id');
	 echo field_forwarder('remove_id_form_db');	 
      echo tep_draw_hidden_field('X_categories_image', stripslashes($categories_image_name));
      echo tep_draw_hidden_field('categories_image', stripslashes($categories_image_name));
	  echo tep_draw_hidden_field('X_categories_map_image', stripslashes($categories_map_image_name));
	  echo tep_draw_hidden_field('categories_map_image', stripslashes($categories_map_image_name));
	  echo tep_draw_hidden_field('X_categories_banner_image', stripslashes($categories_banner_image_name));
	  echo tep_draw_hidden_field('categories_banner_image', stripslashes($categories_banner_image_name));
	  echo tep_draw_hidden_field('categories_video', stripslashes($categories_video_name));
	  
      echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';
      if ($HTTP_GET_VARS['cID']) {
        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
      } else {
        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $HTTP_GET_VARS['cID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </form>
	  <script type="text/javascript">
	  document.<?php echo $form_action;?>.submit();  
	  </script>
	  </tr>
	  <?php
    }
  } elseif ($action == 'new_product') {

	tep_set_time_limit(0);
    $parameters = array('products_name' => '',
						'products_model' => '',
						'provider_tour_code' => '',		
						'products_urlname' => '',
                       'products_description' => '',
					    'products_pricing_special_notes' => '',
					   'products_other_description' => '',
					   'products_package_excludes' => '',
					   'products_package_special_notes' => '',
					   'eticket_itinerary' => '',
					   'eticket_hotel' => '',
					   'eticket_notes' => '',
                       'products_url' => '',
                       'products_id' => '',
                       'products_is_regular_tour' => '',
                       'products_image' => '',
					   'products_map' => '',
                       'products_image_med' => '',
                       'products_image_lrg' => '',
                       'products_price' => '',
                       'products_durations' => '',
                       'products_durations_type' => '',
                       'products_durations_description' => '',
                       'products_date_added' => '',
                       'products_last_modified' => '',
                       'products_date_available' => '',
					   'display_room_option' => '',
                       'products_status' => '',					   
					   'display_itinerary_notes' => '',
					   'display_hotel_upgrade_notes' => '',
					   'products_type' => '',
					   'operate_start_date' => '',
					   'operate_end_date' => '',
                       'products_tax_class_id' => '1',
					   'agency_id' => '',
					   'display_pickup_hotels' => '1',
                       'manufacturers_id' => '');

    $pInfo = new objectInfo($parameters);

    if (isset($HTTP_GET_VARS['pID']) && empty($HTTP_POST_VARS)) {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
      $product_query = tep_db_query("select p.products_video, p.operate_start_date, p.operate_end_date, p.products_type, p.maximum_no_of_guest,p.products_single,p.products_double,p.products_triple,p.products_quadr,p.products_kids,p.products_special_note,p.regions_id, p.agency_id, p.departure_city_id, p.departure_end_city_id, p.display_pickup_hotels, p.products_model, p.provider_tour_code, p.products_urlname, pd.products_name, pd.products_description, pd.products_other_description, pd.products_package_excludes , pd.products_package_special_notes, pd.eticket_itinerary, pd.eticket_hotel, pd.eticket_notes, pd.products_pricing_special_notes, pd.products_small_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_id, p.products_is_regular_tour, p.products_image, p.products_image_med, p.products_image_lrg, p.products_price, p.products_durations, p.products_durations_type, p.products_durations_description, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status,p.products_vacation_package, p.display_room_option, p.products_tax_class_id, p.manufacturers_id, p.display_itinerary_notes, p.display_hotel_upgrade_notes,p.products_map from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
// EOF MaxiDVD: Modified For Ultimate Images Pack!
      $product = tep_db_fetch_array($product_query);

      $pInfo->objectInfo($product);
    } elseif (tep_not_null($HTTP_POST_VARS)) {
      $pInfo->objectInfo($HTTP_POST_VARS);
      $products_name = $HTTP_POST_VARS['products_name'];
      $products_description = $HTTP_POST_VARS['products_description'];
	  $products_pricing_special_notes = $HTTP_POST_VARS['products_pricing_special_notes'];
	  $products_other_description = $HTTP_POST_VARS['products_other_description'];
	  $products_package_excludes = $HTTP_POST_VARS['products_package_excludes'];
	  $products_package_special_notes = $HTTP_POST_VARS['products_package_special_notes'];
	  $eticket_itinerary = $HTTP_POST_VARS['eticket_itinerary'];
	  $eticket_hotel = $HTTP_POST_VARS['eticket_hotel'];
	  $eticket_notes = $HTTP_POST_VARS['eticket_notes'];
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
	//$products_type_array = array(array('id' => '', 'text' => TEXT_NONE));

    $products_type_array = tep_db_query("select products_type_id, products_type_name from " . TABLE_PRODUCTS_TYPES . " order by products_type_id");
    while ($products_type_info = tep_db_fetch_array($products_type_array)) {
      $products_type_arrays[] = array('id' => $products_type_info['products_type_id'],
                                     'text' => $products_type_info['products_type_name']);
	}								 

    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while ($tax_class = tep_db_fetch_array($tax_class_query)) {
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);
    }
	
	$city_class_array = array(array('id' => '', 'text' => TEXT_NONE));
    $city_class_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co   where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' AND c.departure_city_status = '1' and (c.is_attractions='0' or c.is_attractions='2') order by c.city");
    while ($city_class = tep_db_fetch_array($city_class_query)) {
      $city_class_array[] = array('id' => $city_class['city_id'],
                                 'text' => $city_class['city'].', '.$city_class['zone_code'].', '.$city_class['countries_iso_code_3']);
    }
	
 //amit added to check operatedate start
  $months_operate_array = array(); 
  for ($i=1; $i<13; $i++) { 
    $months_operate_array[] = array('id' => sprintf("%02d", $i),
                            'text' => strftime('%b', mktime(0,0,0,$i,15))); 
  } 
  
  $days_operate_array = array(); 

			  for ($i=1; $i<32; $i++) { 
				$days_operate_array[] = array('id' => sprintf("%02d", $i), 
                            'text' => $i); 
              } 			  
  $operate_years_array = array(array('id' => '', 'text' => ''));
  $operate_years_array[] = array('id' => '2007', 'text' => '2007');
  $operate_years_array[] = array('id' => '2008', 'text' => '2008');
  $operate_years_array[] = array('id' => '2009', 'text' => '2009');
  $operate_years_array[] = array('id' => '2010', 'text' => '2010'); 		  
 //amit added to check operatedate end
    $languages = tep_get_languages();
    if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
    switch ($pInfo->products_status) {
      case '0': $in_status = false; $out_status = true; break;
      case '1':
      default: $in_status = true; $out_status = false;
    }
	if (!isset($pInfo->display_itinerary_notes)) $pInfo->display_itinerary_notes = '1';
      switch ($pInfo->display_itinerary_notes) {
      case '0': $display_itinerary_notes_in_status = false; $display_itinerary_notes_out_status = true; break;
      case '1':
      default: $display_itinerary_notes_in_status = true; $display_itinerary_notes_out_status = false;
    }
	
	if (!isset($pInfo->display_hotel_upgrade_notes)) $pInfo->display_hotel_upgrade_notes = '1';
      switch ($pInfo->display_hotel_upgrade_notes) {
      case '0': $display_hotel_upgrade_notes_in_status = false; $display_hotel_upgrade_notes_out_status = true; break;
      case '1':
      default: $display_hotel_upgrade_notes_in_status = true; $display_hotel_upgrade_notes_out_status = false;
    }
	if (!isset($pInfo->products_vacation_package)) $pInfo->products_vacation_package = '0';
    switch ($pInfo->products_vacation_package) {
      case '0': $in_status_products_vacation_package = false; $out_status_products_vacation_package = true; break;
      case '1':
      default: $in_status_products_vacation_package = true; $out_status_products_vacation_package = false;
    }
	
	$global_default_products_vacation_package = $pInfo->products_vacation_package;
	
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
<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>
<script type="text/javascript" src="includes/javascript/replacestring.js"></script>
<script type="text/javascript"><!--
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

var productmodeltest = '';

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
		var http1 = createRequestObject();
		
		function getInfoRegIrreg(url,products_id){
		
			try		{
				 if(products_id == '-1')
				 { 
					
					http.open('get', url);
					http.onreadystatechange = handleInfoRegIrreg;
					http.send(null);
					
				}	
			}catch(e){}	
		
		}
		function getInfo(url,products_id){
		try
		{
			 
		  if(products_id == '-1')
		  { 
		  	http.open('get', "input_1.php?agency_id="+document.getElementById("agency_id").options[document.getElementById("agency_id").selectedIndex].value);
			http.onreadystatechange = handleInfo1;
			http.send(null);
		  }
		  else if(products_id == '-2')
		  { 
		  	http.open('get', "input_1.php?agency_id="+document.getElementById("agency_id").options[document.getElementById("agency_id").selectedIndex].value+"&edit=true");
			http.onreadystatechange = handleInfo2;
			http.send(null);
		  }
		  else if(products_id != '-1' && products_id != '-2')
		  { 
			http.open('get', "input_1.php?products_model="+document.new_product.products_model.value+"&products_id="+products_id);
			http.onreadystatechange = handleInfo;
			http.send(null); 
		  }
		  
		}
		catch(e){}	
		}
		function handleInfo()
		{
			
			if(http.readyState == 4)
			{
			 var response = http.responseText;
			 productmodeltest = response;
			
			 
			}
		}
		function handleInfo1()
		{
			
			if(http.readyState == 4)
			{
			 var response = http.responseText;			
			 responses = response.split("<::::::::::>");
			 document.getElementById("div_id_departure").innerHTML = responses[0];
			 document.getElementById("div_id_region_select").innerHTML = responses[1];
			 document.new_product.numberofdaparture.value = responses[2];
			 
			}
		}
		function handleInfo2()
		{
			
			if(http.readyState == 4)
			{
			 var response = http.responseText;			
			 document.getElementById("div_id_region_select").innerHTML = response;
			 
			}
		}
		function handleInfoRegIrreg()
		{
			
			if(http.readyState == 4)
			{
			 var response = http.responseText;			
			document.getElementById("div_id_reg_irreg_input").innerHTML = response;
			change_title_single_adult();
			 
			}
		}
		
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
						
						if(window.document.forms[0].elements[i].name == "products_name[1]")
						{
							if(window.document.forms[0].elements[i].value == "")
							{
								alert("Please Enter the Products Name");
								window.document.forms[0].elements[i].focus();
								return false;
							}
						}
						
						if(window.document.forms[0].elements[i].name == "regions_id")
						{
							if(window.document.forms[0].elements[i].value == "0")
							{
								alert("Please Select the Region ");
								window.document.forms[0].elements[i].focus();
								return false;
							}
						}
						/*
						if(window.document.forms[0].elements[i].name == "products_model")
						{
							if(window.document.forms[0].elements[i].value == "")
							{
								alert("Please Enter the value for Products Model");
								window.document.forms[0].elements[i].focus();
								return false;
							}
						}
						*/
						
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
						}
						
						if(window.document.forms[0].elements[i].name == "departure_city_id[]")
						{
						if(window.document.forms[0].elements[i].options.length==0)
						{
							alert("Please Enter the value for Products Departure City");
							window.document.forms[0].departure_city_id_temp.focus();
							return false;
						}
							/*if(window.document.forms[0].elements[i].value == "0")
							{
								alert("Please Enter the value for Products Departure City");
								window.document.forms[0].elements[i].focus();
								return false;
							}*/
						}
						
						
												
													
					}
					
	
		
		for (var i=0;i<combo.options.length;i++)
		{
		combo.options[i].selected=true;		
		 if(i==0)
		 {
			document.new_product.selectedcityid.value = document.new_product.sel2.options[i].value;
		 }
		 else
		 {
			document.new_product.selectedcityid.value = document.new_product.selectedcityid.value+"::"+document.new_product.sel2.options[i].value;
		 }			
		}
		
		if(document.new_product.selectedcityid.value == "")
		{
	        alert("Please enter the Destination City");	
			document.new_product.sel1.focus();
			return false;
		}
		
		try{
		selectAllOptions('departure_end_city_id[]');
		selectAllOptions('departure_city_id[]');
		}catch(e){}
		
		
		return true;
}


function selectAllOptions(selStr)
{
  var selObj = document.getElementById(selStr);
  for (var i=0; i<selObj.options.length; i++) {
    selObj.options[i].selected = true;
  }
}

function generate()
{	
	<?php
	$subquery = 'select c.*, s.zone_code, co.countries_iso_code_3 from '.TABLE_TOUR_CITY.'  as c ,' . TABLE_ZONES . ' as s, '.TABLE_COUNTRIES.' as co where s.zone_country_id=co.countries_id and c.state_id = s.zone_id and c.city != ""  AND c.departure_city_status = "1" and (c.is_attractions="1" or c.is_attractions="2") order by c.city';	 
	$subresult = mysql_query($subquery);	
	?>
	var i; 
	var nrOpt = <?php echo mysql_num_rows($subresult); ?>; 
	
	var optiuni = Array(nrOpt); 
	
	<?php
	
	for ($i = 0; $i < mysql_num_rows($subresult); $i++)
	{
		
		$subrow = mysql_fetch_row($subresult); 
		echo 'optiuni['.$i.'] = new Array("'.$subrow[1].'","'.$subrow[0].'","'.$subrow[3].', '.$subrow[10].', '.$subrow[11].'");';
	}
	?>
		
	document.getElementById("sel1").options.length = 0;			
	var select = document.getElementById("regions_id").options[document.getElementById("regions_id").selectedIndex].value;			
	document.getElementById("sel1").options.length++;			
	document.getElementById("sel1").options[0] = new Option(" -- Select -- ", -1);			
		
	var opt = 1; 
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

<?php
if($pInfo->products_id == '') 	{
?>
function showtourdefault(){

	document.getElementById('id_products_pricing_special_notes[1]').innerHTML = '<?php echo htmlentities(TOURS_DEFAULT_PRICING_NOTES);?>';
	document.getElementById('id_products_other_description[1]').innerHTML  = '<?php echo htmlentities(TOURS_DEFAULT_PACKAGE_INCULDES);?>';
	document.getElementById('id_products_package_excludes[1]').innerHTML  = '<?php echo htmlentities(TOURS_DEFAULT_PACKAGE_EXCLUDES);?>';
	document.getElementById('id_products_package_special_notes[1]').innerHTML  = '<?php echo htmlentities(TOURS_DEFAULT_PACKAGE_SPECIAL_NOTES);?>';

}

function showvackationpackagedefault(){

	document.getElementById('id_products_pricing_special_notes[1]').innerHTML =  '<?php echo htmlentities(TOURS_VACATION_PACKAGE_DEFAULT_PRICING_NOTES);?>';
	document.getElementById('id_products_other_description[1]').innerHTML  = '<?php echo htmlentities(TOURS_VACATION_PACKAGE_DEFAULT_PACKAGE_INCULDES);?>';
	document.getElementById('id_products_package_excludes[1]').innerHTML  = '<?php echo htmlentities(TOURS_VACATION_PACKAGE_DEFAULT_PACKAGE_EXCLUDES);?>';
	document.getElementById('id_products_package_special_notes[1]').innerHTML  = '<?php echo htmlentities(TOURS_VACATION_PACKAGE_DEFAULT_PACKAGE_SPECIAL_NOTES);?>';

}

<?php }else{ 

 $product_query_for_default = tep_db_query("select products_pricing_special_notes, products_other_description, products_package_excludes, products_package_special_notes   from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$pInfo->products_id . "' and language_id = '1'");
 $product_for_default = tep_db_fetch_array($product_query_for_default);
?>

function showtourdefault(){
	
	<?php if($product_for_default['products_other_description'] == '') { ?>
	document.getElementById('id_products_other_description[1]').innerHTML  = '<?php echo htmlentities(TOURS_DEFAULT_PACKAGE_INCULDES);?>';
	<?php } ?>
	<?php if($product_for_default['products_package_excludes'] == '') { ?>	
	document.getElementById('id_products_package_excludes[1]').innerHTML  = '<?php echo htmlentities(TOURS_DEFAULT_PACKAGE_EXCLUDES);?>';
	<?php } ?>
	<?php if($product_for_default['products_package_special_notes'] == '') { ?>	
	document.getElementById('id_products_package_special_notes[1]').innerHTML  = '<?php echo htmlentities(TOURS_DEFAULT_PACKAGE_SPECIAL_NOTES);?>';
	<?php } ?>
}

function showvackationpackagedefault(){	
	<?php if($product_for_default['products_other_description'] == '') { ?>
	document.getElementById('id_products_other_description[1]').innerHTML  = '<?php echo htmlentities(TOURS_VACATION_PACKAGE_DEFAULT_PACKAGE_INCULDES);?>';
	<?php } ?>
	<?php if($product_for_default['products_package_excludes'] == '') { ?>
	document.getElementById('id_products_package_excludes[1]').innerHTML  = '<?php echo htmlentities(TOURS_VACATION_PACKAGE_DEFAULT_PACKAGE_EXCLUDES);?>';
	<?php } ?>
	<?php if($product_for_default['products_package_special_notes'] == '') { ?>
	document.getElementById('id_products_package_special_notes[1]').innerHTML  = '<?php echo htmlentities(TOURS_VACATION_PACKAGE_DEFAULT_PACKAGE_SPECIAL_NOTES);?>';
	<?php } ?>
}

<?php } ?>
//--></script>
<table>
<tr>
<td>


<?php 	
if(isset($HTTP_GET_VARS['pID']) && $HTTP_GET_VARS['pID'] != '') { // amit changed for new tour edit page
?>	

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr><td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText" align="right">
<?php
    echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get');
    /*if(isset($_GET['agency']) && $_GET['agency']!= '')
		{
			echo tep_draw_hidden_field('agency',$_GET['agency']);
		}*/
	echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
    echo '</form>';
?>
                </td>
              </tr>
			  <tr>
                <td class="smallText" align="right">
<?php
	$send_extra_search = '';
	if($HTTP_GET_VARS['searchkey'] != ''){
		$send_extra_search = "&searchkey=".$HTTP_GET_VARS['searchkey']."";
	}
	if($HTTP_GET_VARS['agency'] != ''){
		$send_extra_search .= "&agency=".$HTTP_GET_VARS['agency']."";
	}
	
	echo tep_draw_form('search', FILENAME_CATEGORIES, '', 'get');
    echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search',$HTTP_GET_VARS['searchkey']);
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
    $city_class_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co   where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.departure_city_status = '1' order by c.city");
    while ($city_class = tep_db_fetch_array($city_class_query)) {
      $city_class_array[] = array('id' => $city_class['city_id'],
                                 'text' => $city_class['city'].', '.$city_class['zone_code'].', '.$city_class['countries_iso_code_3']);
    }
	$agency_array_search = array(array('id' => '', 'text' => TEXT_NONE));
    $agency_query_search = tep_db_query("select agency_id, agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name");
	while ($agency_result_search = tep_db_fetch_array($agency_query_search)) {
      $agency_array_search[] = array('id' => $agency_result_search['agency_id'],
                                     'text' => $agency_result_search['agency_name']);
    }
	
    echo tep_draw_form('durationsearch', FILENAME_CATEGORIES, '', 'get');
	if(isset($_GET['cPath']) && $_GET['cPath']!= '')
	{
		echo tep_draw_hidden_field('cPath',$_GET['cPath']);
	}
    echo HEADING_TITLE_DURATION . ' ' . tep_draw_pull_down_menu('durations', $duration_tree_array, $_GET['durations'], 'onChange="this.form.submit();"');
    echo '<br>'.HEADING_TITLE_DEPARTURE . ' ' . tep_draw_pull_down_menu('departure', $city_class_array, $_GET['departure'], 'onChange="this.form.submit();"');
	echo '<br>'.TEXT_PRODUCTS_AGENCY . ' ' . tep_draw_pull_down_menu('agency', $agency_array_search, $_GET['agency'], 'onChange="this.form.submit();"');
	echo '</form>';
?>
                </td>
              </tr>
			
            </table></td>
          </tr>
        </table>
</td></tr>
 <tr>
	<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
  </tr>
 <tr>
	<td>
	 <table border="0" width="100%" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="pageHeadingSmall">
		<?php 
		$origina_prod_model = tep_get_products_model($HTTP_GET_VARS['pID']);
		$origina_provider_tourcode = tep_get_provider_tourcode($HTTP_GET_VARS['pID']);	
		echo sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id)).'<br>'.tep_get_products_name($HTTP_GET_VARS['pID']).'&nbsp;&nbsp;Tour Code:- ['.$origina_prod_model.']&nbsp;&nbsp;Provider Tour Code:- ['.$origina_provider_tourcode.']'; 
		?>  
		
		</td>
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
<ul id="product_ajax_edit_tabs" class="shadetabs">
<li><a href="categories_ajax_sections.php?section=tour_categorization&pID=<?php echo $HTTP_GET_VARS['pID'].$send_extra_search;?>&cPath=<?php echo $HTTP_GET_VARS['cPath'];?>" rel="countrycontainer">Categorization</a></li>
<li><a href="categories_ajax_sections.php?section=room_and_price&pID=<?php echo $HTTP_GET_VARS['pID'].$send_extra_search;?>&cPath=<?php echo $HTTP_GET_VARS['cPath'];?>" rel="countrycontainer">Room and Price</a></li>
<li><a href="categories_ajax_sections.php?section=tour_attribute&pID=<?php echo $HTTP_GET_VARS['pID'].$send_extra_search;?>&cPath=<?php echo $HTTP_GET_VARS['cPath'];?>" rel="countrycontainer">Attribute</a></li>
<li><a href="categories_ajax_sections.php?section=tour_operation&pID=<?php echo $HTTP_GET_VARS['pID'].$send_extra_search;?>&cPath=<?php echo $HTTP_GET_VARS['cPath'];?>" rel="countrycontainer">Operation</a></li>
<li><a href="categories_ajax_sections.php?section=tour_content&pID=<?php echo $HTTP_GET_VARS['pID'].$send_extra_search;?>&cPath=<?php echo $HTTP_GET_VARS['cPath'];?>" rel="countrycontainer">Content</a></li>
<li><a href="categories_ajax_sections.php?section=tour_eticket&pID=<?php echo $HTTP_GET_VARS['pID'].$send_extra_search;?>&cPath=<?php echo $HTTP_GET_VARS['cPath'];?>" rel="countrycontainer">E-ticket</a></li>
<li><a href="categories_ajax_sections.php?section=tour_image_video&cPath=<?php echo $HTTP_GET_VARS['cPath'].$send_extra_search;?>&pID=<?php echo $HTTP_GET_VARS['pID'];?>&cPath=<?php echo $HTTP_GET_VARS['cPath'];?>" rel="countrycontainer">Image/Video</a></li>
<li><a href="categories_ajax_sections.php?section=tour_meta_tag&pID=<?php echo $HTTP_GET_VARS['pID'].$send_extra_search;?>&cPath=<?php echo $HTTP_GET_VARS['cPath'];?>" rel="countrycontainer">Meta Tag</a></li>
</ul>

<div id="countrydivcontainer" style="border:1px solid gray; width:100%; margin-bottom: 1em; padding: 10px;"> 
	
</div>

<script type="text/javascript">
var proeditajaxtabs=new ddajaxtabs("product_ajax_edit_tabs", "countrydivcontainer");
proeditajaxtabs.setpersist(true);
proeditajaxtabs.setselectedClassTarget("link"); 
proeditajaxtabs.init();
<?php if($HTTP_GET_VARS['tabedit']=='eticket'){ ?>
proeditajaxtabs.expandit(5);
<?php } ?>
</script>
	</td>
  </tr>
</table>
	
<?php
}else { // only gose with default if new
	
							echo tep_draw_form('new_product', FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') . '&action=new_product_preview', 'post', 'enctype="multipart/form-data" onsubmit="return SelectAll(sel2)" '); ?>
							
							
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
								 
								 <tr><td class="main" colspan="2">
								 
									 <fieldset>
										<legend><?php echo TEXT_HEADING_TITLE_TOUR_CATEGORIZATION; ?></legend>				
										<table width="100%" border="0" cellspacing="0" cellpadding="2">
								  <tr>
									<td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
									<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE; ?></td>
								 </tr>
								 
								  <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
									<tr>
									<td class="main"><?php echo TEXT_PRODUCTS_VACATION_PACKAGE; ?></td>
									<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?>
									<?php
									if($pInfo->products_id == '')
									{
										$products_pricing_special_notes[1] = TOURS_DEFAULT_PRICING_NOTES;
										echo tep_draw_radio_field('products_vacation_package', '1', $in_status_products_vacation_package,'','onClick="showvackationpackagedefault();"') . '&nbsp;' . TEXT_PRODUCT_AVAILABLE_VACATION_PACKAGE . '&nbsp;' . tep_draw_radio_field('products_vacation_package', '0', $out_status_products_vacation_package,'','onClick="showtourdefault();"') ;
									}else{
										echo tep_draw_radio_field('products_vacation_package', '1', $in_status_products_vacation_package,'','onClick="showvackationpackagedefault();"') . '&nbsp;' . TEXT_PRODUCT_AVAILABLE_VACATION_PACKAGE . '&nbsp;' . tep_draw_radio_field('products_vacation_package', '0', $out_status_products_vacation_package,'','onClick="showtourdefault();"') ;
									}
									
									echo '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE_VACATION_PACKAGE; 
									
									?></td>
								 </tr>		 
								  <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
								   <tr>
									<td class="main"><?php echo HEADING_TITLE_PRODUCTS_TYPE; ?></td>
									<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_pull_down_menu('products_type', $products_type_arrays, $pInfo->products_type); //'onChange="show_go(this.value);"'?></td>
								 </tr>
								  <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>		
								  <tr>
									<td class="main"><?php echo TEXT_PRODUCTS_REGION; ?></td>
									<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('regions_id', tep_get_region_tree(), $pInfo->regions_id, 'id="regions_id" onChange="javascript: generate();"'); ?></td>
								  </tr>
								  <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
								  <tr>
									<td class="main"><?php echo TEXT_PRODUCTS_AGENCY; ?></td>
									<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') ; ?>
									<?php 
									if($pInfo->products_id == '')
									{
									echo tep_draw_pull_down_menu('agency_id', $agency_array, $pInfo->agency_id,'id="agency_id" onChange="getInfo(\'\',\'-1\'); change_tour_attri_list(\''.$pInfo->products_id.'\',this.value);"'); 
									}
									else
									{
									echo tep_draw_pull_down_menu('agency_id', $agency_array, $pInfo->agency_id,'id="agency_id" onChange="getInfo(\'\',\'-2\'); change_tour_attri_list(\''.$pInfo->products_id.'\',this.value);"'); 
									}
						
									echo tep_draw_hidden_field('products_id', $pInfo->products_id);
									echo tep_draw_hidden_field('prev_agency_id', $pInfo->agency_id);
									?>
						
									</td>
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
						
									<td class="main"><?php echo TEXT_PRODUCTS_URL_NAME; ?></td>
						
									<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_urlname', $pInfo->products_urlname, 'size="60"'); ?></td>
						
								  </tr>
								  
								  <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
								 <!--
								  <tr>
									<td class="main"><?php //echo TEXT_PRODUCTS_MODEL;    $products_id_model = $pInfo->products_id;?></td>
									<td class="main"><?php 
									//echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_model', $pInfo->products_model, 'onBlur="getInfo(\'\',\''.$products_id_model.'\')"'); 
									
									
									?></td>
								  </tr>
								  -->
								  
								  <tr>
									<td class="main"><?php echo TEXT_PRODUCTS_PROVIDER_MODEL;?>
									</td>
									<td class="main"><?php 
									echo  tep_draw_hidden_field('products_model', $pInfo->products_model); 
									echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('provider_tour_code', $pInfo->provider_tour_code); 
									
									
									?></td>
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
										
										</table>
						
									 </fieldset>		 
								 
								 </td></tr>
								 
								  <!-- start room and price display  -->
							<tr>
								<td class="main" colspan="2">
								<fieldset>
									<legend><?php echo TEXT_HEADING_TITLE_ROOM_AND_PRICE; ?></legend>
								
									<table width="100%" border="0" cellspacing="0" cellpadding="2">
									
										<!-- Start Display Room  -->
						<script><!--		
						updateGross();
						function toggleBox(szDivID,zzDivID) 
						{
						  if (document.layers) 
						  {   
							  document.layers[zzDivID].visibility = "hide";
							  document.layers[zzDivID].display = "none";
							  document.layers[szDivID].visibility = "show";
							  document.layers[szDivID].display = "inline";
							 
						  } else if (document.getElementById) 
						  { 
							var obj = document.getElementById(szDivID);
							var obj1 = document.getElementById(zzDivID);
							  obj1.style.visibility = "hidden";
							  obj1.style.display = "none";
							  obj.style.visibility = "visible";
							  obj.style.display = "inline";
							
						  } else if (document.all) 
						  { 
							  document.all[zzDivID].style.visibility = "hidden";
							  document.all[zzDivID].style.display = "none";
							  document.all[szDivID].style.visibility = "visible";
							  document.all[szDivID].style.display = "inline";
							
						  }
						 change_title_single_adult();
						}
								//--></script>
								  <tr>
								  
									<td class="main"><?php echo 'Display Room'; ?></td>
									<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('display_room_option', '1', $display_room_yes,'', 'onclick="toggleBox(\'optionyes\',\'optionno\');"') . '&nbsp; Yes &nbsp;' . tep_draw_radio_field('display_room_option', '0', $display_room_no,'', 'onclick="toggleBox(\'optionno\',\'optionyes\');"') . '&nbsp; No'; ?></td>
								</tr>
								 <?php 
								 if($display_room_yes)
								 {
								 ?>
								 <script type="text/javascript">
									option_value_yn = 0;			
								 </script>
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
								 <script type="text/javascript">
									option_value_yn = <?php echo $display_room_no;?>;			
								 </script>
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
											<td class="main" width="20%" align="left"><?php echo 'Maximum no of Guest '; ?></td>
											<!--<td class="main" width="80%" align="left"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('maximum_no_of_guest', $pInfo->maximum_no_of_guest, 'size="7"'); ?></td>-->
											<td class="main" idth="80%" align="left"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_pull_down_menu('maximum_no_of_guest', $maximum_no_of_guest_array,$selected_guest ); ?></td>
										  </tr>
										  
										  <tr>
											<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
										  </tr>
										  
										  <tr>
											<td class="main"><?php echo TEXT_HEADING_SINGLE_OCC_PRICE; ?></td>
											<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_single', $pInfo->products_single, 'size="7"'); ?></td>
										  </tr>
										  
										  <tr>
											<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
										  </tr>
										  
										  <tr>
											<td class="main"><?php echo TEXT_HEADING_DOUBLE_OCC_PRICE; ?></td>
											<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_double', $pInfo->products_double, 'size="7"'); ?></td>
										  </tr>
										  
										  <tr>
											<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
										  </tr>
										  
										  <tr>
											<td class="main"><?php echo TEXT_HEADING_TRIPLE_OCC_PRICE; ?></td>
											<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_triple', $pInfo->products_triple, 'size="7"'); ?></td>
										  </tr>
										  
										  <tr>
											<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
										  </tr>
										  
										  <tr>
											<td class="main" nowrap><?php echo TEXT_HEADING_QUADRUPLE_OCC_PRICE; ?></td>
											<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_quadr', $pInfo->products_quadr, 'size="7"'); ?></td>
										  </tr>
										  
										  <tr>
											<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
										  </tr>
										  
										  <tr>
											<td class="main"><?php echo TEXT_HEADING_KIDS_OCC_PRICE; ?></td>
											<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '15') . '&nbsp;' . tep_draw_input_field('products_kids', $pInfo->products_kids, 'size="7"'); ?></td>
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
											<td class="main" width="20%" align="left"><?php echo TEXT_HEADING_ADULT_PRICE; ?></td>
											<td class="main" width="80%" align="left"><?php echo tep_draw_separator('pixel_trans.gif', '40', '1') . '&nbsp;' . tep_draw_input_field('products_single1', $pInfo->products_single, 'size="7"'); ?></td>
										  </tr>
										  
										  
										  <tr>
											<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
										  </tr>
										  
										  <tr>
											<td class="main"><?php echo TEXT_HEADING_KIDS_PRICE; ?></td>
											<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '40', '1') . '&nbsp;' . tep_draw_input_field('products_kids1', $pInfo->products_kids, 'size="7"'); ?></td>
										  </tr>
										  
										  </table>
								  </div>	
									</td>	
								  </tr>
								  <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
								  
								  <?php
							for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
						?>
						<tr>
									<td class="main" valign="top" nowrap="nowrap"><?php echo TEXT_PRODUCTS_SPECIAL_RPICING_NOTE; ?></td>
									<td><table border="0" cellspacing="0" cellpadding="0">
									  <tr>
										<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
										<td class="main"><?php echo tep_draw_textarea_field('products_pricing_special_notes[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_pricing_special_notes[$languages[$i]['id']]) ? $products_pricing_special_notes[$languages[$i]['id']] : tep_get_products_pricing_special_notes($pInfo->products_id, $languages[$i]['id'])),'id=id_products_pricing_special_notes[' . $languages[$i]['id'] . ']'); ?></td>
						
									  </tr>
									</table></td>
								  </tr>
						
									<tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
						<?php } ?>
								  
						  
								  <tr>
									<td class="main" nowrap="nowrap"><?php echo TEXT_PRODUCTS_DISPLAY_HOTEL_UPGRADE_NOTES; ?></td>
									<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('display_hotel_upgrade_notes', '1', $display_hotel_upgrade_notes_in_status) . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_YES . '&nbsp;' . tep_draw_radio_field('display_hotel_upgrade_notes', '0', $display_hotel_upgrade_notes_out_status) . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_NO; ?></td>
								 </tr>
								 
								  <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
								  
						
						<!-- End Display Room  -->
						
									
									</table>
								</fieldset>	
								</td>
							</tr>
						<!-- start of tour opration -->
						
								<tr>
								<td class="main" colspan="2">
									<fieldset>
									<legend><?php echo TEXT_HEADING_TITLE_TOUR_OPERATION; ?></legend>
										
									<table width="100%" border="0" cellspacing="0" cellpadding="2">  
								<?php
						
								$no_of_sec=regu_irregular_section_numrow($pInfo->products_id);
								?>
								  <tr >
									<td class="main" colspan="2"><?php echo TEXT_PRODUCTS_TYPE; ?></td>
								</tr>	
								<tr id="on_change_show">
								  <td align="left" valign="top" class="main"># of regular/irregular sections</td>
									<td nowrap width="980"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15');?>
									  <div id="reg_irrg_id">
									  <input type="text" name="numberofsection" value="<?php echo $no_of_sec;?>">
									  <input type="button" name="go" value="Go" onClick="getInfoRegIrreg('createreg_illregulartour.php?product_id=<?=$pInfo->products_id;?>&numberofsection='+document.new_product.numberofsection.value,'-1');">
									  
									  </div>
									  <div id="div_id_reg_irreg_input">
										<?php
											$_GET['numberofsection']=$no_of_sec;
											$_GET['product_id']=$pInfo->products_id; 
											include("createreg_illregulartour_edit.php");
										?>
										  </div>
										
									  </td>
								  </tr>
								   <tr>
								   <td colspan="2">
								   <div id="country_state_start_city_id">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
									<td class="main" width="13%"><?php echo TEXT_HEADING_CITIES_BY_COUNTRY; ?></td>
									<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '21', '10'); ?>
									<?php
									echo tep_draw_pull_down_menu('countries_search_id', tep_get_countries('select country'), '', 'onChange="change_region_state_list(this.value,'.($pInfo->products_id>0?$pInfo->products_id:0).',document.new_product.regions_id.value);"');
									?>		
									</td>
								   </tr>
									<tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
									</tr>
								<tr>
									
									<td class="main" nowrap ><?php echo TEXT_PRODUCTS_DURATION_SELECT_CITY; ?></td>
									<td class="main">
										<?php
									if($pInfo->departure_city_id == ''){
									$pInfo->departure_city_id = 0;
									}
										$city_start_class_query_left = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id not in(".$pInfo->departure_city_id.")  and c.departure_city_status = '1' and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
										while ($city_start_class_left = tep_db_fetch_array($city_start_class_query_left)) {
										  $city_start_class_array_left[] = array('id' => $city_start_class_left['city_id'],
																	 'text' => $city_start_class_left['city'].', '.$city_start_class_left['zone_code'].', '. $city_start_class_left['countries_iso_code_3']);
										}
										
										$city_start_class_query_right = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id in(".$pInfo->departure_city_id.")  and c.departure_city_status = '1' and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
										while ($city_start_class_right = tep_db_fetch_array($city_start_class_query_right)) {
										  $city_start_class_array_right[] = array('id' => $city_start_class_right ['city_id'],
																	 'text' => $city_start_class_right ['city'].', '.$city_start_class_right['zone_code'].', '. $city_start_class_right['countries_iso_code_3']);
										}
									?>
									<table border="0">
									  <tr>
										<td><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . tep_draw_pull_down_menu('departure_city_id_temp', $city_start_class_array_left, '',' multiple="multiple" size="10"'); ?></td>
										<td><INPUT onClick="moveOptions(this.form.departure_city_id_temp, this.form.elements['departure_city_id[]']);" type=button value="-->"><BR><INPUT onClick="moveOptions(this.form.elements['departure_city_id[]'], this.form.departure_city_id_temp);" type=button value="<--"></td>
										<td><?php echo  tep_draw_pull_down_menu('departure_city_id[]', $city_start_class_array_right, '',' id="departure_city_id[]" multiple="multiple" size="10"'); ?></td>
									  </tr>
									</table>
										<?php //echo tep_draw_separator('pixel_trans.gif', '21', '15') . '&nbsp;' . tep_draw_pull_down_menu('departure_city_id', $city_class_array, $pInfo->departure_city_id); ?>
										</td>
									</tr>
									<tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
									</tr>
									
								   </tr>		  
								   <tr>
									<td class="main"><?php echo TEXT_PRODUCTS_DEPARTURE_END_CITY; ?></td>
									<td class="main">			
									<?php
									if($pInfo->departure_end_city_id == ''){
									$pInfo->departure_end_city_id = 0;
									}
										$city_end_class_query_left = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id not in(".$pInfo->departure_end_city_id.")  AND c.departure_city_status = '1' and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
										while ($city_class_left = tep_db_fetch_array($city_end_class_query_left)) {
										  $city_end_class_array_left[] = array('id' => $city_class_left['city_id'],
																	 'text' => $city_class_left['city'].', '.$city_class_left['zone_code'].', '. $city_class_left['countries_iso_code_3']);
										}
										
										$city_end_class_query_right = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s , " . TABLE_COUNTRIES . " as co  where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' and c.city_id in(".$pInfo->departure_end_city_id.") AND c.departure_city_status = '1' and (c.is_attractions='0' or c.is_attractions='2') order by c.city ");
										while ($city_class_right = tep_db_fetch_array($city_end_class_query_right)) {
										  $city_end_class_array_right[] = array('id' => $city_class_right ['city_id'],
																	 'text' => $city_class_right ['city'].', '.$city_class_right['zone_code'].', '. $city_class_right['countries_iso_code_3']);
										}
									?>
									<table border="0">
									  <tr>
										<td><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . tep_draw_pull_down_menu('departure_end_city_id_temp', $city_end_class_array_left, '',' multiple="multiple" size="10"'); ?></td>
										<td><INPUT onClick="moveOptions(this.form.departure_end_city_id_temp, this.form.elements['departure_end_city_id[]']);" type=button value="-->"><BR><INPUT onClick="moveOptions(this.form.elements['departure_end_city_id[]'], this.form.departure_end_city_id_temp);" type=button value="<--"></td>
										<td><?php echo  tep_draw_pull_down_menu('departure_end_city_id[]', $city_end_class_array_right, '',' id="departure_end_city_id[]" multiple="multiple" size="10"'); ?></td>
									  </tr>
									</table>
									</td>
								  </tr>
								  <?php $k =1;
										if($pInfo->products_id != "")
										{
												
												$products_departure_query = tep_db_query("select * from " . TABLE_PRODUCTS_DEPARTURE . " where products_id = ".$pInfo->products_id." order by departure_region");
												while ($products_departure_result = tep_db_fetch_array($products_departure_query)) 
												{
													$edit_extra_lines = "";
													if($region_name_display != $products_departure_result['departure_region'])
													{
													$selecthisid = $k;
													$region_name_display = $products_departure_result['departure_region'];
													$edit_extra_lines = '<tr><td colspan="6" class="main"><input type="checkbox" checked name="chkalldasda'.$selecthisid.'" onClick="chkallregion(this.form,this.checked,\\\'selece_'.$selecthisid.'\\\')" ><b>'.$region_name_display.'</b></td></tr>';
													}
						 $edit_departure_data .= '<table id="table_id_departure'.$k.'" cellpadding="2" cellspacing="2" width="100%"><tbody>'.$edit_extra_lines.'<tr class="'.(floor($k/2) == ($k/2) ? 'attributes-even' : 'attributes-odd').'"><td class="dataTableContent" valign="top" width="110" nowrap><input class="buttonstyle" checked  id="selece_'.$selecthisid.'" name="regioninsertid_'.$k.'" type="checkbox" value="'.$k.'">'.$k.'.<input name="depart_region_'.$k.'" value="'.addslashes($products_departure_result['departure_region']).'" size="12" type="hidden" ></td><td class="dataTableContent" valign="top" width="100" nowrap><input name="depart_time_'.$k.'" value="'.addslashes($products_departure_result['departure_time']).'" size="12" type="text"></td><td class="dataTableContent" align="center"  ><input size="20" name="departure_address_'.$k.'" value="'.addslashes($products_departure_result['departure_address']).'" type="text"></td><td class="dataTableContent" align="center" ><input size="30" name="departure_full_address_'.$k.'" value="'.addslashes($products_departure_result['departure_full_address']).'" type="text"></td><td class="dataTableContent" align="center" width="100"><input size="30" name="departure_map_path_'.$k.'" value="'.addslashes($products_departure_result['map_path']).'" type="text"></td><td align="center" width="70"></td></tr></tbody></table>';
									
								
													$k++;
												}
											  
										}
								?>
								  <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
										
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
													//$destination_query = tep_db_query("select * from ".TABLE_PRODUCTS_DESTINATION." where products_id = ".$pInfo->products_id." order by city_id");
													//while ($destination_result = tep_db_fetch_array($destination_query))
													//{
														$city_edit_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c ," . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co, ".TABLE_PRODUCTS_DESTINATION." as pde  where pde.products_id = ".$pInfo->products_id." and pde.city_id = c.city_id and s.zone_country_id = co.countries_id and c.state_id = s.zone_id and c.city !='' AND c.departure_city_status = '1' and (c.is_attractions='1' or c.is_attractions='2') order by c.city");
														while($city_edit_result = tep_db_fetch_array($city_edit_query)){
															$sel2values .= "<option value=".$city_edit_result['city_id'].">".$city_edit_result['city'].', '.$city_edit_result['zone_code'].', '.$city_edit_result['countries_iso_code_3']."</option>";
																					
																if($countingcity == 0){
																	$allready_product_city = $city_edit_result['city_id'];
																}else{
																	$allready_product_city .= ",".$city_edit_result['city_id'];
																}
																$countingcity++;														
														}
																								
													//}
													$tempsolution="";
												if($allready_product_city == '') $allready_product_city=0;
												
												$city_new_query = tep_db_query("select ttc.city_id, ttc.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as ttc, ".TABLE_REGIONS." as regi, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co where s.zone_country_id = co.countries_id and ttc.state_id = s.zone_id and  ttc.regions_id = regi.regions_id and regi.regions_id = ".$pInfo->regions_id." and ttc.city_id not in (".$allready_product_city.") and ttc.city!='' AND c.departure_city_status = '1'  and (ttc.is_attractions='1' or ttc.is_attractions='2')  order by ttc.city");
												 while ($city_new_result = tep_db_fetch_array($city_new_query)) 
												{
												 $tempsolution .=  "<option value=".$city_new_result['city_id'].">".$city_new_result['city'].', '.$city_new_result['zone_code'].', '.$city_new_result['countries_iso_code_3']."</option>";
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
													echo $sel2values;
												}
												?>
												</select>
												<input type="hidden" name="selectedcityid" value="">
											</td>
										</tr>
									</table>
									</td>
									</table>			
								   </div>
								   
								   </td>
								  </tr>
								  <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
								  <?php
												$arr_times_type = array();
												$arr_times_type[] = array('id' => 0,'text' => 'days');
												$arr_times_type[] = array('id' => 1,'text' => 'hours');
												$arr_times_type[] = array('id' => 2,'text' => 'minutes');
										?>				
								  <tr>
									<td class="main"><?php echo TEXT_PRODUCTS_DURATION; ?></td>
									<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_durations', $pInfo->products_durations, 'size="2" onChange="change_itenerary_hotel('.$pInfo->products_id.');"'); ?>
									<?php echo tep_draw_pull_down_menu('products_durations_type',$arr_times_type,$pInfo->products_durations_type,'onchange="change_itenerary_hotel('.$pInfo->products_id.');"');?>
									</td>
								  </tr>		  
								 <tr>
									 <td class="main" nowrap><?php echo TEXT_PRODUCTS_DISPLAY_PICKUP_HOTELS; ?></td>
									<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') ; ?>
									<?php echo tep_draw_checkbox_field('display_pickup_hotels', '1', $pInfo->display_pickup_hotels); ?></td>
								  </tr>
								 <tr>
									<td colspan="2" ><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
								  <tr>
									<td class="main" valign="top"><?php echo TEXT_PRODUCTS_DEPARTURE_PLACE; ?></td>
									<td class="main">
											  <table border="0" cellpadding="2" cellspacing="0" bgcolor="FFFFFF">
												  
												  <tr>
													<td colspan="6"><?php echo tep_black_line(); ?></td>
												  </tr>
												  <tr>
													<td colspan="6">
													 <table border="0" cellpadding="0" cellspacing="0" width="100%">
														  <tr class="dataTableHeadingRow">
															<td class="dataTableHeadingContent" width="110" align="left">Region</td>
															<td class="dataTableHeadingContent" width="100" align="left">Departure Time</td>
															<td class="dataTableHeadingContent" width="60" align="center">Location</td>
															<td class="dataTableHeadingContent" width="80" align="center">Address</td>
															<td class="dataTableHeadingContent" width="80" align="center">Map Path</td>
															<td class="dataTableHeadingContent" width="70" align="center"></td>
														  </tr>
														 </table>
													</td>
												  </tr>
												  <tr>
													<td colspan="6"><?php echo tep_black_line(); ?></td>
												  </tr>
												
												<tr class="dataTableRow">
												<td class=dataTableContent colspan="6">
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
													<td colspan="6"><?php echo tep_black_line(); ?></td>
												  </tr>
												  
												  <tr class="dataTableRow">
													<td class="dataTableContent" width="110" valign=top> <div id="div_id_region_select"></div>&nbsp;
															<?php 
															if($pInfo->products_id != "")
															{
															?>
															<script>getInfo('',-2);</script>
															<?php 
															} 					
															?>
													</td>
													<td class="dataTableContent" width="100" valign=top><input type="hidden" name="numberofdaparture" value="<?php echo $k; ?>">&nbsp;<?php echo tep_draw_input_field('depart_time', '', 'size="12"'); ?><br>(HH:MMam <br> e.g:- 9:00am)</td>
													<td class="dataTableContent" width="60" align="center"><?php echo tep_draw_input_field('departure_address', '', 'size="20"'); ?></td>
													<td class="dataTableContent" width="80" align="center"><?php echo tep_draw_input_field('departure_full_address', '', 'size="30"'); ?></td>
													<td class="dataTableContent" width="80" align="center"><?php echo tep_draw_input_field('departure_map_path', '', 'size="30"'); ?></td>
													<td class="dataTableContent" width="70" align="center"><?php echo tep_image_submit('button_insert.gif', '', 'onClick="return add_departure()"'); ?></td>
												   </tr>
												<tr>
												<td colspan="6"><?php echo tep_black_line(); ?></td>
											   </tr>
											 </table> 					  				
									</td>
								 </tr>
								 <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
								  </table>
									</fieldset>	
								</td>
							</tr>	
						<!-- End of Tour Operation -->
								  <!-- sart dispaly tour content -->
									<tr>
								<td class="main" colspan="2">
									<fieldset>
									<legend><?php echo TEXT_HEADING_TITLE_TOUR_CONTENT; ?></legend>		
									<table width="100%" border="0" cellspacing="0" cellpadding="2">
						  
						<?php
						
							for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
							
						?>
						
							  <SCRIPT language=javascript>
								<!--
								function textCounter<?php echo $languages[$i]['id'] ; ?>(field,cntfield,maxlimit)
								{
								if (field.value.length > maxlimit) 
								   field.value = field.value.substring(0, maxlimit);		
								else
								   cntfield.value = maxlimit - field.value.length;
								}		
								-->
								</SCRIPT>
								   <tr>
									<td class="main" valign="top" nowrap><?php echo TEXT_PRODUCTS_SMALL_DESCRIPTION; ?></td>
									<td><table border="0" cellspacing="0" cellpadding="0">
									  <tr>
										<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?></td>
										<td class="main">
										<?php echo tep_draw_textarea_field('products_small_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_small_description[$languages[$i]['id']]) ? $products_small_description[$languages[$i]['id']] : tep_get_products_small_description($pInfo->products_id, $languages[$i]['id']))); ?>
										</td>
										</tr>
									</table></td>
								  </tr>
								  
									<tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
						<?php
							}
						?> 		<tr>
									<td class="main"><?php echo TEXT_PRODUCTS_DISPLAY_ITINERARY_NOTES; ?></td>
									<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('display_itinerary_notes', '1', $display_itinerary_notes_in_status) . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_YES . '&nbsp;' . tep_draw_radio_field('display_itinerary_notes', '0', $display_itinerary_notes_out_status) . '&nbsp;' . TEXT_PRODUCTS_TEXT_RADIO_NO; ?></td>
								 </tr>		 
								  <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
						<?php
							for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
						?>
								  <tr>
									<td class="main" valign="top"><?php echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
									<td><table border="0" cellspacing="0" cellpadding="0">
									  <tr>
										<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
										<td class="main"><?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_description[$languages[$i]['id']]) ? $products_description[$languages[$i]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id']))); ?></td>
						
									  </tr>
									</table></td>
								  </tr>
								 <tr>
									<td class="main" valign="top"><?php echo TEXT_PRODUCTS_OTHER_DESCRIPTION; ?></td>
									<td><table border="0" cellspacing="0" cellpadding="0">
									  <tr>
										<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
										<td class="main"><?php echo tep_draw_textarea_field('products_other_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_other_description[$languages[$i]['id']]) ? $products_other_description[$languages[$i]['id']] : tep_get_products_other_description($pInfo->products_id, $languages[$i]['id'])),'id=id_products_other_description[' . $languages[$i]['id'] . ']'); ?></td>
						
									  </tr>
									</table></td>
								  </tr>
								  
								   <tr>
									<td class="main" valign="top" nowrap="nowrap"><?php echo TEXT_PRODUCTS_PACKAGE_EXCLUDES; ?></td>
									<td><table border="0" cellspacing="0" cellpadding="0">
									  <tr>
										<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
										<td class="main"><?php echo tep_draw_textarea_field('products_package_excludes[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_package_excludes[$languages[$i]['id']]) ? $products_package_excludes[$languages[$i]['id']] : tep_get_products_package_excludes($pInfo->products_id, $languages[$i]['id'])),'id=id_products_package_excludes[' . $languages[$i]['id'] . ']'); ?></td>
						
									  </tr>
									</table></td>
								  </tr>
								  
								   <tr>
									<td class="main" valign="top"><?php echo TEXT_PRODUCTS_PACKAGE_SPECIAL_NOTES; ?></td>
									<td><table border="0" cellspacing="0" cellpadding="0">
									  <tr>
										<td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
										<td class="main"><?php echo tep_draw_textarea_field('products_package_special_notes[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_package_special_notes[$languages[$i]['id']]) ? $products_package_special_notes[$languages[$i]['id']] : tep_get_products_package_special_notes($pInfo->products_id, $languages[$i]['id'])),'id=id_products_package_special_notes[' . $languages[$i]['id'] . ']'); ?></td>
						
									  </tr>
									</table></td>
								  </tr>		 
								<?php } ?>
									</table>
									</fieldset>	
								</td>
							</tr>
								  <!-- End dispaly tour content -->
						 <!--amit added for eticket hote and itenerary information start -->		  
								  <tr>
									<td colspan="2" class="main">
									<fieldset>
									<legend><?php echo TEXT_HEADING_ETICKET_INFORMATION; ?></legend>
									<table width="100%" border="0" cellspacing="0" cellpadding="2">  			
									<tr>			
								   <td  valign="top">
									<div id="eticket_div" >
										<table width = "100%"  >
												<tr>
												<td colspan=2 ></td>
												<td><table width="70%"   border="0" >
													<tr>
													<td width="10%"></td>
													
													<td class="main"  align="left"  width="30%">
													<b>	<?php echo TEXT_HEADING_ETICKET_ITINERARY; ?></b>
													</td>
													<td class="main" align="left" width="30%">
														<b><?php echo TEXT_HEADING_ETICKET_HOTEL; ?> </b>
													</td>
													<td class="main" align="left" width="30%" >
														<b><?php echo TEXT_HEADING_ETICKET_NOTES; ?> </b>
													</td>
												</tr>
												</table></td></tr>
												<?php
								 for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
								 ?>
								 <tr>
									<td colspan=2 class="main" >			
										<?php echo TEXT_HEADING_ETICKET. ' ' .  tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>
									</td>
									<td>
										<table width="60%"  border="0" >
											<tr>
												<td>
								 <?php 
								$count_itine_hotel = tep_get_products_eticket_itinerary($pInfo->products_id, $languages[$i]['id']);
								$count_itine_hotel = find_title($count_itine_hotel);
								?><table width="100%">
								<?php 
								for($j=1; $j<=$count_itine_hotel+1; $j++)
								{
									$content_prod=tep_get_products_eticket_itinerary($pInfo->products_id, $languages[$i]['id']);
									$splite_content =  explode("!##!", $content_prod);
								
								
									$content_prod_hotel=tep_get_products_eticket_hotel($pInfo->products_id, $languages[$i]['id']);
									$splite_content_hotel =  explode("!##!", $content_prod_hotel);
						
									$content_prod_notes=tep_get_products_eticket_notes($pInfo->products_id, $languages[$i]['id']);
									$splite_content_notes =  explode("!##!", $content_prod_notes);
							
						?>				
								  <tr>
								  <td class="main" align="center" width="10%">Day <?php echo $j ?></td> 
										<td class="main" align="center" width="30%"><?php echo tep_draw_textarea_field('eticket_itinerary[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '3', (isset($eticket_itinerary[$languages[$i]['id']]) ? $eticket_itinerary[$languages[$i]['id']] : $splite_content[$j-1]),'id=eticket_itinerary[' . $languages[$i]['id'] . ']'); ?>
										</td>
						<td class="main" width="30%" align="center"><?php echo tep_draw_textarea_field('eticket_hotel[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '3', (isset($eticket_hotel[$languages[$i]['id']]) ? $eticket_hotel[$languages[$i]['id']] : $splite_content_hotel[$j-1]),'id=eticket_hotel[' . $languages[$i]['id'] . ']'); ?></td>
						
									 <td class="main" width="30%" align="center">
										<?php echo tep_draw_textarea_field('eticket_notes[' . $languages[$i]['id'] . ']['.$j.']', 'soft', '30', '3', (isset($eticket_notes[$languages[$i]['id']]) ? $eticket_notes[$languages[$i]['id']] : $splite_content_notes[$j-1]),'id=eticket_notes[' . $languages[$i]['id'] . ']'); ?>
										</td>
						
								  </tr>
								  
								  
								<?php		
								}	
								?>
								</table>
									</td></tr></table></td></tr>
						
						<?php	
								}?>				
										</table></div>	
										</td></tr></table>
										
										 
										<table width="100%" border="0" cellspacing="0" cellpadding="2">
										  <tr>
											<td class="main" width="15%" nowrap><?php echo TEXT_HEADING_SPL_NOTES; ?></td>
											<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_textarea_field('products_special_note', 'soft', '70', '5',$pInfo->products_special_note); ?></td>
										  </tr>
										   <tr>
											<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
										  </tr>
										  </table>
										 </fieldset>
										 
									</td>
								  </tr>
								 
								  <!--amit added for eticket hote and itenerary information end -->
						
						<!-- // BOF: Eticket Information! -->
						
						<!-- Start image/video section -->
							<tr>
								<td class="main" colspan="2">
									<fieldset>
									<legend><?php echo TEXT_HEADING_IMAGES_VIDEOS; ?></legend>
								
									<table width="100%" border="0" cellspacing="0" cellpadding="2">
									
									<tr>
								   <td class="dataTableRow" valign="top"><span class="main"><?php echo TEXT_PRODUCTS_IMAGE_NOTE; ?></span></td>
								   <?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
								   <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image_med') . '<br>'; ?>
								   <?php } else { ?>
								   <td class="dataTableRow" valign="top"><span class="smallText"><?php echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . tep_draw_input_field('products_image_med', $pInfo->products_image_med,'') . tep_draw_hidden_field('products_previous_image_med', $pInfo->products_image_med) . '</td></tr></table>';
								   }
								   ?>
								   
								   <?php
								    if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image_med) != '')
								   echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image_med . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image_med, $pInfo->products_image_med, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('products_previous_image_med', $pInfo->products_image_med) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span></td>
								  </tr>
								  
								  <!--<tr>
								   <td class="dataTableRow" valign="top"><span class="main"><?php //echo TEXT_PRODUCTS_IMAGE_MEDIUM; ?></span></td>
								   <?php //if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
								   <td class="dataTableRow" valign="top"><span class="smallText"><?php //echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image') . '<br>'; ?>
								   <?php //} else { ?>
								   <td class="dataTableRow" valign="top"><span class="smallText"><?php //echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . tep_draw_input_field('products_image', $pInfo->products_image,'') . tep_draw_hidden_field('products_previous_image', $pInfo->products_image) . '</td></tr></table>';
								   //} if (($HTTP_GET_VARS['pID']) && ($pInfo->products_image) != '')
								   //echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_image . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_image, $pInfo->products_image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('products_previous_image', $pInfo->products_image) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span></td>
								  </tr>-->
								  
								  <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
								  <tr class="dataTableRow">
			 <td class="main" valign="top"><b>Extra Images:</b></td><td></td></tr>
								  <tr class="dataTableRow">
            <td class="main" valign="top" colspan="2">
								<div>
								<table width="70%"  border="1" cellspacing="3" cellpadding="3">
								  <tr class="dataTableHeadingRow">
									<td class="dataTableHeadingContent" width="250">Image</td>
									<td nowrap="nowrap" class="dataTableHeadingContent" width="150">Sort Order</td>
									<td class="dataTableHeadingContent" width="150">Remove</td>
								  </tr>
								</table>
								</div>				
								<div id="myDiv">
								<?php
								
								/*$category_intro_query_sql  = "select *  from " . TABLE_PRODUCTS_EXTRA_IMAGES . " where products_id = '" . $HTTP_GET_VARS['pID'] . "' order by image_sort_order";
								$category_intro_query = tep_db_query($category_intro_query_sql);
								
								$tt_into_cnt_row = tep_db_num_rows($category_intro_query);
								//$category_intro = tep_db_fetch_array($category_intro_query);
								if($tt_into_cnt_row > 0){
									?>
									<input type="hidden" value="<?php echo $tt_into_cnt_row; ?>" id="theValue" />
									<?php
									$row = 1;
									while($category_intro = tep_db_fetch_array($category_intro_query)){
									?>
									<div id="my<?php echo $row;?>Div">
									<table width="100%" border="0" cellspacing="3" cellpadding="3">
									  <tr >
										<td  valign="top">
										<?php
										if($category_intro['product_image_name']!= '') {
										?>
										<img src="<?php echo HTTP_CATALOG_SERVER.DIR_WS_CATALOG_IMAGES.$category_intro['product_image_name'];?>" alt="<?php echo $category_intro['product_image_name'];?>" width="100" height="100"><br/>
										<?php } ?>
										<input type='file' name='image_introfile[]'>
										<input type="hidden" name="previouse_image_introfile[]" value="<?php echo $category_intro['product_image_name'];?>">
										</td>
										<td valign='top'><input type="text" name='cat_intro_sort_order[]' size="10" value="<?php echo $category_intro['image_sort_order'];?>"></td>
										<td valign='top'><input type="hidden" name="db_categories_introduction_id[]" value="<?php echo $category_intro['prod_extra_image_id'];?>"> <input type="hidden" id="remove_id_form_db_<?php echo $category_intro['prod_extra_image_id'];?>" name="remove_id_form_db[]"  value="off"><input type="checkbox" name="delete_frm_db[]" onClick="document.getElementById('remove_id_form_db_<?php echo $category_intro['prod_extra_image_id'];?>').value='on'"></td>
									  </tr>
									</table>
									</div>		
									<?php		
									$row++;
									}
									?>
									<?	
								}else{*/
								?>
								<input type="hidden" value="1" id="theValue" />
								<div id="my1Div">
								<table width="70%" border="0" cellspacing="3" cellpadding="3">
								  <tr>
									<td valign="top" width="250"><input type='file' name='image_introfile[]'>		
									</td>
									<td valign='top' width="150"><input type="text" name='cat_intro_sort_order[]' size="10" value="1"></td>
									<td valign='top' width="150"><a href="javascript:;" onClick="removeEvent('my1Div')">Remove</a></td>
								  </tr>
								</table>
								</div>
								<?php
								//}
								?>
								</div>
								<p><a href="javascript:;" onClick="addEventExtra();"><b><font color="#000099">Add More Images</font></b></a></p>
			
			</td>
          </tr>
								  <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
								  <tr>
								  <td class="dataTableRow" valign="top"><span class="main"><?php echo TEXT_PRODUCTS_MAP_NOTE; ?></span></td>
								   <?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Disable') or (HTML_AREA_WYSIWYG_DISABLE == 'Disable')){ ?>
								   <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_map') . '<br>'; ?>
								   <?php } else { ?>
								   <td class="dataTableRow" valign="top"><span class="smallText"><?php echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td class="main">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp; </td><td class="dataTableRow">' . tep_draw_input_field('products_map', $pInfo->products_map,'') . tep_draw_hidden_field('products_previous_map', $pInfo->products_map) . '</td></tr></table>';
								   }
								   ?>
								   
								   <?php
								    if (($HTTP_GET_VARS['pID']) && ($pInfo->products_map) != '')
								   echo tep_draw_separator('pixel_trans.gif', '24', '17" align="left') . $pInfo->products_map . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->products_map, $pInfo->products_map, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . tep_draw_hidden_field('products_previous_map', $pInfo->products_map) . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_map" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_map" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span></td>
								  </tr>
								  <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
									<tr>
									<td  class="main"><?php echo TEXT_PRODUCTS_VIDEO; ?></td>
									<td class="main">
									<small>
									Direct URL
									</small>&nbsp;&nbsp;<?php echo tep_draw_input_field('products_video_url', '', 'size="30"'); ?>
									<?php
									if(substr($pInfo->products_video,0,5) == 'http:'){
									echo "<br>".$pInfo->products_video;
									$directvurl="show";
									}
									?>						
									<br><small>or</small><br>
									<small>Upload Video</small>&nbsp;&nbsp;
									<?php echo tep_draw_file_field('products_video').tep_draw_hidden_field('products_previous_video', $pInfo->products_video); ?>
									<br>
									<?php
									if($directvurl != "show" && $pInfo->products_video != '' ){
									echo $pInfo->products_video;
									}
									?>
									</td>
								  </tr>
									<tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
									
									</table>
									</fieldset>	
								</td>
							</tr>		 
								  
						<!-- End image/video section -->
								<tr>
								<td class="main" colspan="2">
									<fieldset>
									<legend><?php echo TEXT_PRODUCT_METTA_INFO; ?></legend>		
									<table width="100%" border="0" cellspacing="0" cellpadding="2">
						 <?php
							for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
						?>
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
								  
								  
								  <?php } ?>
									</table>
									</fieldset>	
								</td>
							</tr>
								 <!-- END display tour meta information -->		
								  <tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								  </tr>
								</table>
								</td>
							  </tr>
						
								  <?php
						// BOF: WebMakers.com Added: Draw Attribute Tables
						?>
							  <tr>
								<td><div id="tour_attribute_list"><table border="3" cellspacing="5" cellpadding="2" align="center" bgcolor="000000">
								
						<?php
							$rows = 0;
							
						   if($pInfo->agency_id!='')
							{	
							  $options_query = tep_db_query("select po.products_options_id, po.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_ATTRIBUTES_TOUR_PROVIDER . " patp where po.language_id = '" . $languages_id . "' and po.products_options_id = patp.products_options_id and patp.agency_id= '".$pInfo->agency_id."' order by products_options_sort_order, products_options_name");
							  
							}
							else
							{
							$options_query = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_sort_order, products_options_name");
							}	
							while ($options = tep_db_fetch_array($options_query)) {
							  
							if($pInfo->agency_id!='')
							{
							
							
							  $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p, " . TABLE_PRODUCTS_ATTRIBUTES_VALUES_TOUR_PROVIDER . " pavtp where pov.products_options_values_id = p2p.products_options_values_id and pavtp.products_options_id = '" . $options['products_options_id'] . "' and p2p.products_options_id = pavtp.products_options_id and pavtp.agency_id='".$pInfo->agency_id."' and pavtp.products_options_values_id = p2p.products_options_values_id  and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
								 
							 
							 } 
							 else
							 {
								$values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");
							 } 
							  $header = false;
							  while ($values = tep_db_fetch_array($values_query)) {
								$rows ++;
								if (!$header) {
								  $header = true;
						?>
								  <tr valign="top">
									<td><table border="2" cellpadding="2" cellspacing="2" bgcolor="FFFFFF">
									  <tr class="dataTableHeadingRow">
									  <td colspan="10" class="attributeBoxContent" align="center">Active Attributes</td>
									 </tr>
									  <tr class="dataTableHeadingRow">
										<td class="dataTableHeadingContent" width="250" align="left"><?php echo $options['products_options_name']; ?></td>
										<td class="dataTableHeadingContent" width="50" align="center"><?php echo 'Prefix'; ?></td>
										<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Price'; ?></td>
										<td class="dataTableHeadingContent" width="70" align="center"><?php echo '<div  id="label_single[]" >Single</div>'; ?></td>
										<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Double'; ?></td>
										<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Triple'; ?></td>
										<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Quadruple'; ?></td>
										<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Kids'; ?></td>
										<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Sort Order'; ?></td>
										<td class="dataTableHeadingContent" width="70" align="center"><?php echo 'Special'; ?></td>
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
								  $attributes_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix, single_values_price, double_values_price, triple_values_price, quadruple_values_price, kids_values_price,  products_options_sort_order from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $pInfo->products_id . "' and options_id = '" . $options['products_options_id'] . "' and options_values_id = '" . $values['products_options_values_id'] . "'");
								  if (tep_db_num_rows($attributes_query) > 0) {
									$attributes = tep_db_fetch_array($attributes_query);
								  }
								}
								
								
								if($attributes['single_values_price']>0 || $attributes['double_values_price']>0 || $attributes['triple_values_price']>0 || $attributes['quadruple_values_price']>0 || $attributes['kids_values_price']>0)
								{
									$check_spe_price_attri=true;
								}
								else
								{
									$check_spe_price_attri=false;
								}
								
								
						?>
									  <tr class="dataTableRow">
										<td class="dataTableContent"><?php echo tep_draw_checkbox_field('option[' . $rows . ']', $attributes['products_attributes_id'], $attributes['products_attributes_id']) . '&nbsp;' . $values['products_options_values_name']; ?>&nbsp;</td>
										<td class="dataTableContent" width="50" align="center"><?php echo tep_draw_input_field('prefix[' . $rows . ']', $attributes['price_prefix'], 'size="2"'); ?></td>
										<td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('price[' . $rows . ']', $attributes['options_values_price'], 'size="7" '.($check_spe_price_attri?'disabled="disabled"':'').''); ?></td>
										<td class="dataTableContent"  align="center" width="70"><?php echo ($check_spe_price_attri ?tep_draw_input_field('single_price[' . $rows . ']', $attributes['single_values_price'], 'size="7"'):'<div id="show_sub_divattri_s_'.$rows.'" style="display:none" >'.tep_draw_input_field('single_price[' . $rows . ']', $attributes['single_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_s_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'&nbsp;')?></div></td>
										<td class="dataTableContent"  align="center" width="70"><?php echo ($check_spe_price_attri ?tep_draw_input_field('double_price[' . $rows . ']', $attributes['double_values_price'], 'size="7"'):'<div id="show_sub_divattri_d_'.$rows.'" style="display:none" >'.tep_draw_input_field('double_price[' . $rows . ']', $attributes['double_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_d_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'&nbsp;')?></div></td>
										<td class="dataTableContent"  align="center" width="70"><?php echo ($check_spe_price_attri ?tep_draw_input_field('triple_price[' . $rows . ']', $attributes['triple_values_price'], 'size="7"'):'<div id="show_sub_divattri_t_'.$rows.'" style="display:none" >'.tep_draw_input_field('triple_price[' . $rows . ']', $attributes['triple_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_t_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'&nbsp;')?></div></td>
										<td class="dataTableContent"  align="center" width="70"><?php echo ($check_spe_price_attri ?tep_draw_input_field('quadruple_price[' . $rows . ']', $attributes['quadruple_values_price'], 'size="7"'):'<div id="show_sub_divattri_q_'.$rows.'" style="display:none" >'.tep_draw_input_field('quadruple_price[' . $rows . ']', $attributes['quadruple_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_q_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'&nbsp;')?></div></td>
										<td class="dataTableContent"  align="center" width="70"><?php echo ($check_spe_price_attri ?tep_draw_input_field('kids_price[' . $rows . ']', $attributes['kids_values_price'], 'size="7"'):'<div id="show_sub_divattri_k_'.$rows.'" style="display:none" >'.tep_draw_input_field('kids_price[' . $rows . ']', $attributes['kids_values_price'], 'size="7"').'</div><div id="hide_sub_divattri_k_'.$rows.'" style="display:block" >'.tep_draw_separator("pixel_trans.gif", "54", "15").'&nbsp;')?></div></td>
										<td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('products_options_sort_order[' . $rows . ']', $attributes['products_options_sort_order'], 'size="7"'); ?></td>
										<td class="dataTableContent" width="70" align="center">			
										<?php				
										echo  ($check_spe_price_attri?'<a href="javascript:void(0)" onclick="javascript:delete_spe_price_attri('.$rows.');">Delete spe. Price</a>':'<a href="javascript:void(0)" onclick="javascript:enter_spec_price_attri('.$rows.')">Sepecial Price</a>');
										?>				
										</td>
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
								</table></div></td>
							  </tr>
						<?php
						// EOF: WebMakers.com Added: Draw Attribute Tables
						?>
						
							  <tr>
								<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
							  </tr>
							  <tr>
								<td class="main" align="center" colspan="2"><?php echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . tep_image_submit('button_update.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
							  </tr>
							</table></form>
							</td>
						</tr>
						</table>
						<?php
						  if (HTML_AREA_WYSIWYG_DISABLE == 'Enable') { ?>
									<script language="JavaScript1.2" defer>
									 var config = new Object();  
									 config.width = "<?php echo HTML_AREA_WYSIWYG_WIDTH; ?>px";
									 config.height = "<?php echo HTML_AREA_WYSIWYG_HEIGHT; ?>px";
									 config.bodyStyle = 'background-color: <?php echo HTML_AREA_WYSIWYG_BG_COLOUR; ?>; font-family: "<?php echo HTML_AREA_WYSIWYG_FONT_TYPE; ?>"; color: <?php echo HTML_AREA_WYSIWYG_FONT_COLOUR; ?>; font-size: <?php echo HTML_AREA_WYSIWYG_FONT_SIZE; ?>pt;';
									 config.debug = <?php echo HTML_AREA_WYSIWYG_DEBUG; ?>;
								  <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>		
									   editor_generate('products_description[<?php echo $languages[$i]['id']; ?>]',config);			 
								  <?php } ?>
						<?php if ((HTML_AREA_WYSIWYG_DISABLE_JPSY == 'Enable') && (HTML_AREA_WYSIWYG_DISABLE == 'Enable')){ ?>
									 config.height = "25px";
									 config.bodyStyle = 'background-color: white; font-family: Arial; color: black; font-size: 12px;';
									 config.toolbar = [ ['InsertImageURL'] ];
									 config.OscImageRoot = '<?= trim(HTTP_SERVER . DIR_WS_CATALOG_IMAGES) ?>';
									 editor_generate('products_image',config);
									 editor_generate('products_image_med',config);
									 editor_generate('products_image_lrg',config);
									<?php } ?>
									</script>
						<?php } ?>

<?php
} //end of new tour add 

} elseif ($action == 'new_product_preview') {
    if (tep_not_null($HTTP_POST_VARS)) {
	  
      $pInfo = new objectInfo($HTTP_POST_VARS);
      $products_name = $HTTP_POST_VARS['products_name'];
      $products_description = $HTTP_POST_VARS['products_description'];
	  $products_other_description = $HTTP_POST_VARS['products_other_description'];
	  $products_package_excludes = $HTTP_POST_VARS['products_package_excludes'];
	  $products_package_special_notes = $HTTP_POST_VARS['products_package_special_notes'];
	   
	  $products_pricing_special_notes = $HTTP_POST_VARS['products_pricing_special_notes'];
      $products_head_title_tag = $HTTP_POST_VARS['products_head_title_tag'];
      $products_head_desc_tag = $HTTP_POST_VARS['products_head_desc_tag'];
      $products_head_keywords_tag = $HTTP_POST_VARS['products_head_keywords_tag'];
      $products_url = $HTTP_POST_VARS['products_url'];
    } else {
// BOF MaxiDVD: Modified For Ultimate Images Pack!
      $product_query = tep_db_query("select p.products_video, p.products_type, p.operate_start_date ,p.operate_end_date, pd.products_other_description, pd.products_package_excludes , pd.products_package_special_notes, pd.products_pricing_special_notes, p.featured_products, p.products_model, p.provider_tour_code, p.products_id, pd.language_id, pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_is_regular_tour, p.products_image, p.products_image_med, p.products_image_lrg, p.products_price, p.products_durations, p.products_durations_type, p.products_durations_description, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_vacation_package, p.manufacturers_id,p.products_map  from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "'");
// EOF MaxiDVD: Modified For Ultimate Images Pack!
      $product = tep_db_fetch_array($product_query);

      $pInfo = new objectInfo($product);
      $products_image_name = $pInfo->products_image;
      $products_image_med_name = $pInfo->products_image_med;
      $products_image_lrg_name = $pInfo->products_image_lrg;
	  $products_video_name = $pInfo->products_video;
	  $products_map_name = $pInfo->products_map;
    }

    $form_action = (isset($HTTP_GET_VARS['pID'])) ? 'update_product' : 'insert_product';

    echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');
$products_id = $HTTP_GET_VARS['pID'];
foreach($_POST['cat_intro_sort_order'] as $key=>$val){
	
							if(basename($_FILES['image_introfile']['name'][$key]) != '' ){	
							 $insert_id = rand(1000,3000);									
								$tmp_categories_introduction_image_name == '';
								$uploadfile = DIR_FS_CATALOG_IMAGES.$insert_id.'_'.basename($_FILES['image_introfile']['name'][$key]);
								if (move_uploaded_file($_FILES['image_introfile']['tmp_name'][$key],$uploadfile)) {
									$tmp_categories_introduction_image_name = $insert_id.'_'.basename($_FILES['image_introfile']['name'][$key]);
								} 
								
								echo tep_draw_hidden_field('cat_image_introfile[' . $key . ']', stripslashes($tmp_categories_introduction_image_name));
								echo tep_draw_hidden_field('cat_intro_sort_order[' . $key . ']', stripslashes($_POST['cat_intro_sort_order'][$key]));
								
							}
							
	  }
	  //echo field_forwarder('cat_intro_sort_order');
	 echo field_forwarder('previouse_image_introfile');
	 echo field_forwarder('db_categories_introduction_id');
	 echo field_forwarder('remove_id_form_db');	 

    $languages = tep_get_languages();
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) {
        $pInfo->products_name = tep_get_products_name($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_description = tep_get_products_description($pInfo->products_id, $languages[$i]['id']);
		$pInfo->products_other_description = tep_get_products_other_description($pInfo->products_id, $languages[$i]['id']);
		//$pInfo->$products_pricing_special_notes = tep_get_products_pricing_special_notes($pInfo->products_id, $languages[$i]['id']);		
        $pInfo->products_head_title_tag = tep_db_prepare_input($products_head_title_tag[$languages[$i]['id']]);
        $pInfo->products_head_desc_tag = tep_db_prepare_input($products_head_desc_tag[$languages[$i]['id']]);
        $pInfo->products_head_keywords_tag = tep_db_prepare_input($products_head_keywords_tag[$languages[$i]['id']]);
        $pInfo->products_url = tep_get_products_url($pInfo->products_id, $languages[$i]['id']);
      } else {
        $pInfo->products_name = tep_db_prepare_input($products_name[$languages[$i]['id']]);
        $pInfo->products_description = tep_db_prepare_input($products_description[$languages[$i]['id']]);
		$pInfo->products_other_description = tep_get_products_other_description($pInfo->products_id, $languages[$i]['id']);
		$pInfo->$products_pricing_special_notes = tep_get_products_pricing_special_notes($pInfo->products_id, $languages[$i]['id']);
		$pInfo->$products_package_excludes = tep_get_products_package_excludes($pInfo->products_id, $languages[$i]['id']);
		$pInfo->$products_package_special_notes = tep_get_products_package_special_notes($pInfo->products_id, $languages[$i]['id']);
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
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main">
<?php if ($products_image_med_name) { ?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_med_name, $products_image_med_name, MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"'); } elseif ($products_image_lrg_name) { ?>
<script type="text/javascript"><!--
      document.write('<?php echo '<a href="javascript:popupWindow(\\\'' . tep_href_link(FILENAME_POPUP_IMAGE, 'image=' . $products_image_lrg_name) . '\\\')">' . tep_image(DIR_WS_CATALOG_IMAGES . $products_image_name, $products_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . '</a>'; ?>');
//--></script>
<?php } elseif ($products_image_name) { 
?><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_name, $products_image_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"');}; ?>
        </td>
      </tr>
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

// BOF: WebMakers.com Added: Modified to include Attributes Code
/* Re-Post all POST'ed variables */
      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) {
			if (is_array($value)) {
			  while (list($k, $v) = each($value)) {
			  /// BOL: added for two dimention array
			   if (is_array($v)) {
					while (list($k1, $v1) = each($v)) {
						echo tep_draw_hidden_field($key.'[' . $k . '][' . $k1 . ']', htmlspecialchars(stripslashes($v1)));
					}
			  	} 
			  /// EOL: ended for two dimention array
				else
				{
			  		echo tep_draw_hidden_field($key . '[' . $k . ']', htmlspecialchars(stripslashes($v)));
			  	}
			  }
			} else {
			  echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
			}
      }

	  

      echo tep_draw_hidden_field('products_image', stripslashes($products_image_name));
// BOF MaxiDVD: Added For Ultimate Images Pack!
      echo tep_draw_hidden_field('products_image_med', stripslashes($products_image_med_name));
      echo tep_draw_hidden_field('products_image_lrg', stripslashes($products_image_lrg_name));
	  echo tep_draw_hidden_field('products_video', stripslashes($products_video_name));
	  echo tep_draw_hidden_field('products_map', stripslashes($products_map_name));
      
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
	</form>
	  <script type="text/javascript">
	  document.<?php echo $form_action;?>.submit();  
	  </script>
    </table>
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
    echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get');
	/*if(isset($_GET['agency']) && $_GET['agency']!= '')
		{
			echo tep_draw_hidden_field('agency',$_GET['agency']);
		}*/
    echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
    echo '</form>';
?>
                </td>
              </tr>
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

     $duration_tree_array = array(array('id' => '', 'text' => TEXT_NONE));
     $dura_query = tep_db_query("select distinct(products_durations) as prod_dura from " . TABLE_PRODUCTS . " group by products_durations");
      while($dura_result = tep_db_fetch_array($dura_query))
	  {
      $duration_tree_array[] = array('id' => $dura_result['prod_dura'],
                                 'text' => $dura_result['prod_dura']);
      }
	  
    $city_class_array = array(array('id' => '', 'text' => TEXT_NONE));
    $city_class_query = tep_db_query("select c.city_id, c.city, s.zone_code, co.countries_iso_code_3 from " . TABLE_TOUR_CITY . " as c, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co   where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.departure_city_status = '1' order by c.city");
    while ($city_class = tep_db_fetch_array($city_class_query)) {
      $city_class_array[] = array('id' => $city_class['city_id'],
                                 'text' => $city_class['city'].', '.$city_class['zone_code'].', '.$city_class['countries_iso_code_3']);
    }
	 $agency_array = array(array('id' => '', 'text' => TEXT_NONE));
    $agency_query = tep_db_query("select agency_id, agency_name from " . TABLE_TRAVEL_AGENCY . " order by agency_name");
	while ($agency_result = tep_db_fetch_array($agency_query)) {
      $agency_array[] = array('id' => $agency_result['agency_id'],
                                     'text' => $agency_result['agency_name']);
    }
	
    echo tep_draw_form('durationsearch', FILENAME_CATEGORIES, '', 'get');
	if(isset($_GET['cPath']) && $_GET['cPath']!= '')
	{
		echo tep_draw_hidden_field('cPath',$_GET['cPath']);
	}
    echo HEADING_TITLE_DURATION . ' ' . tep_draw_pull_down_menu('durations', $duration_tree_array, $_GET['durations'], 'onChange="this.form.submit();"');
    echo '<br>'.HEADING_TITLE_DEPARTURE . ' ' . tep_draw_pull_down_menu('departure', $city_class_array, $_GET['departure'], 'onChange="this.form.submit();"');
	echo '<br>'.TEXT_PRODUCTS_AGENCY . ' ' . tep_draw_pull_down_menu('agency', $agency_array, $_GET['agency'], 'onChange="this.form.submit();"');
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
				<td class="dataTableHeadingContent" nowrap><?php echo TABLE_HEADING_TOUR_CODE; ?><br><?php echo "<a  href=categories.php?sortorder=tour-code&".tep_get_all_get_params(array('selected_box','sortorder','page'))."><b>".Asc."</b></a>"; ?>&nbsp;&nbsp;<?php echo "<a  href=categories.php?sortorder=tour-code-desc&".tep_get_all_get_params(array('selected_box','sortorder','page'))."><b>".Desc."</b></a>"; ?></td>
				<td class="dataTableHeadingContent" nowrap><?php echo 'Provider Code'; ?><br><?php echo "<a  href=categories.php?sortorder=provider-code&".tep_get_all_get_params(array('selected_box','sortorder','page'))."><b>".Asc."</b></a>"; ?>&nbsp;&nbsp;<?php echo "<a  href=categories.php?sortorder=provider-code-desc&".tep_get_all_get_params(array('selected_box','sortorder','page'))."><b>".Desc."</b></a>"; ?></td>
				<td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?><br><?php echo "<a  href=categories.php?sortorder=products-name-code&".tep_get_all_get_params(array('selected_box','sortorder','page'))."><b>".Asc."</b></a>"; ?>&nbsp;&nbsp;<?php echo "<a  href=categories.php?sortorder=products-name-desc&".tep_get_all_get_params(array('selected_box','sortorder','page'))."><b>".Desc."</b></a>"; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
				<td class="dataTableHeadingContent" align="center">Feature</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $categories_count = 0;
    $rows = 0;
    if (isset($HTTP_GET_VARS['search'])) {
      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);

      $categories_query = tep_db_query("select c.categories_status,c.categories_feature_status, c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
    }elseif(($HTTP_GET_VARS['durations']=="") && ($HTTP_GET_VARS['departure']=="") && ($HTTP_GET_VARS['agency']==""))
	{
      $categories_query = tep_db_query("select c.categories_status,c.categories_feature_status, c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
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
				<td class="dataTableContent" width="5%"></td>
				<td class="dataTableContent" width="5%"></td>
				<td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . $categories['categories_name'] . '</b>'; ?></td>
                <td class="dataTableContent" align="center">
<?php 
	if ($categories['categories_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                 <td class="<?php echo $current_row; ?>" align="center">
				<?php 
					if ($categories['categories_feature_status'] == '1') {
						echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflagffc&flag=0&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
					  } else {
						echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflagffc&flag=1&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
					  }
				?>
				</td>
				<td class="dataTableContent" align="right">
				<?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=edit_category') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a>'; ?>&nbsp;
				<?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }


		switch (trim($HTTP_GET_VARS['sortorder'])) {              
			  case "tour-code":
              $sort_order = " p.products_model ";
              break;
              case "tour-code-desc":
              $sort_order = " p.products_model DESC";
			  break; 
			  case "provider-code":
              $sort_order = " p.provider_tour_code ";
              break;
              case "provider-code-desc":
              $sort_order = " p.provider_tour_code DESC";
			  break; 
			  case "products-name":
              $sort_order = " pd.products_name ";
              break;
              case "products-name-desc":
              $sort_order = " pd.products_name DESC";
			  break;          
              default:
              $sort_order = " pd.products_name ";
              break;          
          }	

    $products_count = 0;
    if (isset($HTTP_GET_VARS['search'])) {
 	   $searchqueyr1= "select p.products_id, p.featured_products, p.products_model, p.provider_tour_code,  pd.products_name, p.products_is_regular_tour, p.products_image,p.products_map, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status,  p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and (pd.products_name like '%" . tep_db_input($search) . "%'  or p.products_model like '%" . tep_db_input($search) . "%' or p.provider_tour_code like '%" . tep_db_input($search) . "%'  or p.provider_tour_code like '%" . tour_code_decode(tep_db_input($search)) . "%') order by $sort_order";
     $products_query = tep_db_query($searchqueyr1);  	
    }else if($HTTP_GET_VARS['durations']!='' || $HTTP_GET_VARS['departure']!='' || $HTTP_GET_VARS['agency']!=''){
		if($HTTP_GET_VARS['durations']!='')
		{
			$durations_where_condition = " and p.products_durations = '" . $HTTP_GET_VARS['durations'] . "'";
		}
		if($HTTP_GET_VARS['departure']!='')
		{
			$departure_where_condition = " and p.departure_city_id = '" . $HTTP_GET_VARS['departure'] . "'";
		}
		if($HTTP_GET_VARS['agency']!='')
		{
			$provider_where_condition = " and p.agency_id = '" . $HTTP_GET_VARS['agency'] . "'";
		}
		if($HTTP_GET_VARS['cPath'] != '')
		{
			$cpath_where_condition = " and p2c.categories_id = '" . (int)$current_category_id . "'";
		}
      $products_query = tep_db_query("select p.featured_products, p.products_model, p.provider_tour_code,  p.products_id, pd.products_name, p.products_is_regular_tour, p.products_image,p.products_map, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status,  p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id ".$durations_where_condition." ".$departure_where_condition." ".$provider_where_condition." ".$cpath_where_condition." order by $sort_order");
	} else {
      $products_query = tep_db_query("select p.featured_products, p.products_model, p.provider_tour_code, p.products_id, pd.products_name, p.products_is_regular_tour, p.products_image,p.products_map, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by $sort_order");
    }
	
	
	
    while ($products = tep_db_fetch_array($products_query)) {
      $products_count++;
      $rows++;

// Get categories_id for product if search
     if (isset($HTTP_GET_VARS['search']) && $HTTP_GET_VARS['search'] !='') $cPath = $products['categories_id'];
	  if(isset($HTTP_GET_VARS['durations']) && $HTTP_GET_VARS['durations'] !='') $cPath = $products['categories_id'];
	  
      if ( (!isset($HTTP_GET_VARS['pID']) && !isset($HTTP_GET_VARS['cID']) || (isset($HTTP_GET_VARS['pID']) && ($HTTP_GET_VARS['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
// find out the rating average from customer reviews
        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);
        $pInfo_array = array_merge($products, $reviews);
        $pInfo = new objectInfo($pInfo_array);
      }

      if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) && (!isset($HTTP_GET_VARS['search'])) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . (isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '') . (isset($HTTP_GET_VARS['search']) ? '&searchkey=' . $HTTP_GET_VARS['search'] . '' : '') . (isset($HTTP_GET_VARS['searchkey']) ? '&searchkey=' . $HTTP_GET_VARS['searchkey'] . '' : '') .'&action=new_product_preview&read=only') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'].(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '').(isset($HTTP_GET_VARS['search']) ? '&searchkey=' . $HTTP_GET_VARS['search'] . '' : '') . (isset($HTTP_GET_VARS['searchkey']) ? '&searchkey=' . $HTTP_GET_VARS['searchkey'] . '' : '')) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent" width="5%"><?php echo $products['products_id'];?></td>
				<td class="dataTableContent" width="5%" nowrap><?php echo $products['products_model'];?></td>
				<td class="dataTableContent" width="5%" nowrap><?php echo $products['provider_tour_code'];?></td>
				<td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $products['products_name']; ?></td>
                <td class="dataTableContent" align="center">
<?php
      if ($products['products_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath.(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . (isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
<td class="<?php echo $current_row; ?>" align="center">

<?php

      if ($products['featured_products'] == '1') {

        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflagf&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath .(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';

      } else {

        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflagf&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath.(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);

      }
?>
</td>
                <td class="dataTableContent" align="right">
				<?php echo '<a target="_blank" href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '') . '&action=new_product') . '">' . tep_image(DIR_WS_ICONS . 'edit.gif', ICON_EDIT) . '</a>'; ?>&nbsp;
				<?php if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) && (!isset($HTTP_GET_VARS['search']))) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']).(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '').(isset($HTTP_GET_VARS['searchkey']) ? '&searchkey=' . $HTTP_GET_VARS['searchkey'] . '' : '').(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '') .(isset($HTTP_GET_VARS['search']) ? '&searchkey=' . $HTTP_GET_VARS['search'] . '' : '') . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
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
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                   
				
				   <?php
					
					// fix here
					
					if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
					
					?>
					
										<td  class="smallText"></br><?php if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($HTTP_GET_VARS['search'])) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category_ACD') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>' ; 
																			//////scs added: export category //////////
																			echo "</br>";
																			
																			if( (isset($_GET['cPath'])) || (isset($_GET['cID'])))
																			{
																																							
																				$path = explode("_",$_GET['cPath']); 																				
																				if($path[2] != ""){
																				$path[1] = $path[2];																				
																				}
																				
																				if ($categories_count != 0 ){
																				echo '&nbsp;<a href="' . tep_href_link(FILENAME_EXPORT, 'download=stream&dltype=category&cPath='.$_GET['cPath'].'') . '">' . tep_image_button('button_export_category.gif', 'Export catergory') . '</a>';  
																				}
																				?>
																			<? }else{
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
				    <td align="right" class="smallText"><?php if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($HTTP_GET_VARS['search'])) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_product') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</td>
           			<?php
						} // category description is on
					?>

				  </tr>
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
        }
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','3'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . PRODUCT_NAMES_HELPER);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', 'Copy Attribtues') . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

            //$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>');
			 $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>');
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

            //$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id .(isset($HTTP_GET_VARS['agency']) ? '&agency=' . $HTTP_GET_VARS['agency'] . '' : '').(isset($HTTP_GET_VARS['searchkey']) ? '&searchkey=' . $HTTP_GET_VARS['searchkey'] . '' : ''). '&action=new_product') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a> <a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $pInfo->products_id ) . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>');
			
			$contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a> <a target="_blank" href="' . tep_catalog_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $pInfo->products_id ) . '">' . tep_image_button('button_preview.gif', IMAGE_PREVIEW) . '</a>');
			
			$contents[] = array('align' => 'center', 'text' => '<a href="javascript:popupImageWindow(\'' . FILENAME_RELATED_CATEGORIES . '?cat_id='.$current_category_id.'&products_id=' . $pInfo->products_id . '\')">' .tep_image_button('button_related_categories.gif', IMAGE_RELATED_CATS)  . '</a>');
            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->products_date_added));
            if (tep_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->products_last_modified));
            if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->products_date_available));
            $contents[] = array('text' => '<br>' . tep_info_image($pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>' . $pInfo->products_image);
            $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $currencies->format($pInfo->products_price) . '<br>' . TEXT_PRODUCTS_TYPE . ' ' . $pInfo->products_is_regular_tour);
//amit added to category list of products start
           $str_products_cat_name = '';
		   $product_ids_show_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$pInfo->products_id . "'");

            while ($product_ids_show_row = tep_db_fetch_array($product_ids_show_query)) {
	             $str_products_cat_name .= ' <a href="categories.php?cPath='.$product_ids_show_row['categories_id'].'&pID='.(int)$pInfo->products_id .'">'.tep_get_categories_name($product_ids_show_row['categories_id'], $languages_id). '</a><br>';
            }
			$contents[] = array('text' => '<br>' . TEXT_PRODUCTS_TO_CATEGORIES . '<br>' . $str_products_cat_name);
//amit added to show category list of product end           
		   
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

  </tr>
</table>
<script><!--
try{

if(getSelectedCheckboxValue(document.new_product.display_room_option) == 0 ){
change_title_single_adult();
}

}catch(e){

}
//--></script>
<?php
if ($action == 'new_product' && !isset($HTTP_GET_VARS[pID])) {
?>
<script>
	change_itenerary_hotel(<?php echo $pInfo->products_id?>);
</script>
<?php
}
if($pInfo->products_type==3)
{
	echo '<script>document.getElementById("on_change_show_pro_type").style.display="none";</script>';
}
?>	
<?php  require(DIR_WS_INCLUDES . 'footer.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<br>
</body>
</html>