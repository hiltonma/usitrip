<?php
/*
  $Id: product_reviews_write.php,v 1.1.1.1 2004/03/04 23:38:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */
require_once('includes/application_top.php');
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE);
//strar to check post from ajax
if (isset($_POST['aryFormData'])) {
    $aryFormData = $_POST['aryFormData'];

    foreach ($aryFormData as $key => $value) {
        foreach ($value as $key2 => $value2) {
            $value2 = iconv('utf-8', CHARSET . '//IGNORE', $value2);
            $HTTP_POST_VARS[$key] = stripslashes(str_replace('@@amp;', '&', $value2));
        }
    }

    if (isset($aryFormData['rating'])) {
        foreach ($aryFormData['rating'] as $rat_key => $rat_val) {

            if ($aryFormData['rating'][$rat_key] == "true") {
                $HTTP_POST_VARS['rating'] = (int) $rat_key + 1;
            }
        }
    }

    if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {

        $tmp_fold = $_SESSION['prod_temp'];
        $front_title = tep_db_prepare_input($HTTP_POST_VARS['front_title']);
        $front_desc = tep_db_prepare_input($HTTP_POST_VARS['front_desc']);
        $customers_name = $HTTP_POST_VARS['customers_name'];
        $customers_email = $HTTP_POST_VARS['customers_email'];
        $reviews_title = $HTTP_POST_VARS['review_title'];
        $rating = $HTTP_POST_VARS['rating'];
        $review = $HTTP_POST_VARS['review'];

        $error = false;


        if ($customers_name == '') {
            $error = true;
            $error_msg .= TEXT_ERROR_MSG_YOUR_NAME;
        }

        if ($customers_email == '') {
            $error = true;
            $error_msg .= TEXT_ERROR_MSG_YOUR_EMAIL;
        } else {
            /* if(is_CheckvalidEmail($customers_email) != true){
              $error = true;
              $error_msg .= TEXT_ERROR_MSG_VALID_EMAIL;
              } */
        }

        if ($reviews_title == '') {
            $error = true;
            $error_msg .= TEXT_ERROR_MSG_REVIEW_TITLE;
        }

        if (strlen($review) == 0) {
            $error = true;
            $error_msg .= TEXT_ERROR_MSG_REVIEW_TEXT;
        }

        if (($rating < 1) || ($rating > 5)) {
            $error = true;
            $error_msg .= TEXT_ERROR_MSG_REVIEW_RATING;
        }

        if ($error == false) {
            tep_db_query(html_to_db("insert into " . TABLE_REVIEWS . " (products_id, customers_id, customers_name, reviews_rating, date_added, reviews_status, customers_email) values ('" . (int) $HTTP_GET_VARS['products_id'] . "', '" . (int) $customer_id . "', '" . tep_db_input($customers_name) . "', '" . tep_db_input($rating) . "', now(), '0', '" . tep_db_input($customers_email) . "')"));
            $insert_id = tep_db_insert_id();

            tep_db_query(html_to_db("insert into " . TABLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text, reviews_title) values ('" . (int) $insert_id . "', '" . (int) $languages_id . "', '" . tep_db_input($review) . "',  '" . tep_db_input($reviews_title) . "')"));

            // write to products_index
            $index_type = 'reviews';
            auto_add_product_index((int) $HTTP_GET_VARS['products_id'], $index_type);
            // write to products_index end
#### Points/Rewards Module V2.1rc2a BOF ####*/
            if (isset($customer_id) && $customer_id != '') {
                if (USE_POINTS_SYSTEM == 'true' && (int)USE_POINTS_FOR_REVIEWS && tep_get_customers_reviews_total_today($customer_id) <= (int)EVERY_DAY_MAX_NUM_FOR_ADD_POINTS_FOR_REVIEWS ) {
                    $points_toadd = USE_POINTS_FOR_REVIEWS;
                    $comment = 'TEXT_DEFAULT_REVIEWS';
                    $points_type = 'RV';
                    tep_add_pending_points($customer_id, (int) $insert_id, $points_toadd, $comment, $points_type, '', (int) $HTTP_GET_VARS['products_id']);
                }
            }
#### Points/Rewards Module V2.1rc2a EOF ####*/



            if (isset($front_title)) {
                $file = DIR_WS_IMAGES . 'reviews/temp/' . $tmp_fold . '/';
                if (file_exists($file)) {
                    $newfile = DIR_WS_IMAGES . 'reviews/';
                    tep_full_copy($file, $newfile, $_GET['products_id'], $insert_id, $customers_name, $customers_email, $front_title, $front_desc);
                }
                if (isset($tmp_fold)) {
                    $rem_source = DIR_WS_IMAGES . 'reviews/temp/' . $tmp_fold;
                    tep_recursive_remove_directory($rem_source);
                }
            }
?>
            
<?php
            echo 'review_new_added|###|';
            echo '|###|';
?>	
            <table border="0" width="98%" align="center" id="success_review_fad_out_id"  class="automarginclass" cellspacing="2" cellpadding="2">                        
                <tr class="messageStackSuccess">                        
                    <td class="messageStackSuccess"><img src="image/icons/success.gif" border="0" alt="Success" title=" Success " width="10" height="10">&nbsp;<?php echo TEXT_REVIEW_ADDED_SUCCESS; ?></td>
                </tr>                        
            </table>                        
<?php
            echo '|###|success';
?>
<?php
        } else {
?>	
            <table width="100%"  border="0" cellspacing="5" cellpadding="5">                        
                <tr>                        
                    <td bgcolor="messageStackError" class="main"><?php echo $error_msg; ?></td>
                </tr>                        
            </table>                        
            
<?php
        }
    } else if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process_photos')) { //旧的分享图片上传start
	
        
		/*if(1==2){
			$tmp_fold = $_SESSION['prod_temp'];
			$front_title = tep_db_prepare_input($HTTP_POST_VARS['front_title']);
			$front_desc = tep_db_prepare_input($HTTP_POST_VARS['front_desc']);
			$customers_name = $HTTP_POST_VARS['customers_name'];
			$customers_email = $HTTP_POST_VARS['customers_email'];
	
			$error = false;
	
	
			if ($customers_name == '') {
				$error = true;
				$error_msg .= TEXT_ERROR_MSG_YOUR_NAME;
			}
	
			if ($customers_email == '') {
				$error = true;
				$error_msg .= TEXT_ERROR_MSG_YOUR_EMAIL;
			} else {
				if (is_CheckvalidEmail($customers_email) != true) {
					$error = true;
					$error_msg .= TEXT_ERROR_MSG_VALID_EMAIL;
				}
			}
	
			if ($error == false) {
				$return_files = array();
				$file = DIR_WS_IMAGES . 'reviews/temp/' . $tmp_fold . '/';
				if (file_exists($file)) {
					$newfile = DIR_WS_IMAGES . 'reviews/';
					$return_files = tep_full_copy($file, $newfile, $_GET['products_id'], '', $customers_name, $customers_email, $front_title, $front_desc);
				}
				$rem_source = DIR_WS_IMAGES . 'reviews/temp/' . $tmp_fold;
				tep_recursive_remove_directory($rem_source);
	
				// write to products_index
				$index_type = 'photos';
				auto_add_product_index((int)$_GET['products_id'], $index_type);
				// write to products_index end
	//reviews photographs upload
	?>
				
				<table border="0" width="98%" align="center" id="success_review_fad_out_id"  class="automarginclass" cellspacing="2" cellpadding="2">                        
					<tr class="messageStackSuccess">                        
						<td class="messageStackSuccess"><img src="image/icons/success.gif" border="0" alt="Success" title=" Success " width="10" height="10">&nbsp;<?php echo TEXT_PHOTO_ADD_SUCCESS; ?> <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=photos&' . tep_get_all_get_params(array('info', 'mnu', 'rn', 'action'))); ?>"><b><?php echo TEXT_CLICK_HERE; ?></b></a> <?php echo TEXT_PHOTO_ADD_SUCCESS_NEXT; ?>
						</td>                        
					</tr>                        
				</table> 
	<?php
	is_array($return_files) && count($return_files) && $return_files = $return_files[0];
	if($return_files!=''){
		$customers_name=($customers_name);
		$customers_email=($customers_email);
		$front_title = explode('/--title--/',$front_title);
		$front_title = ($front_title[0]);
		$front_desc = explode('/--desc--/',$front_desc);
		$front_desc = ($front_desc[0]);
		$image_url_links = DIR_WS_IMAGES . 'reviews/' .$return_files;
		$image = tep_image($image_url_links);
		$added_date = explode(" ",date('Y-m-d H:i:s',time()));						
		$date = strtotime($added_date[0]);
		$date_disp = date("F Y",$date);
		require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);
	?>	
	<script type="text/javascript">
	var reviews_photos_ul = jQuery('#reviews_photos_ul');
	reviews_photos_ul.show();
	jQuery('#no_reviews_photos_div').hide();
	var li = '<li><div class="pic"><a target="_blank" rel="lightbox[review_photos]" href="<?php echo $image_url_links?>" ><?php echo $image; ?></a></div><p class="name"><b><?php echo $front_title; ?></b></p><p class="edit"><?php echo $front_desc; ?></p><p class="info"><?php echo TEXT_PHOTO_BY.' '.$customers_name; ?>, <?php echo $date_disp; ?><font style="color:red;"><?php echo db_to_html('&nbsp;&nbsp;未审核');?></font></p></li>';
	reviews_photos_ul.prepend(li);
	</script>
	<?php
	}
			} else {
	?>	
				<table width="100%"  border="0" cellspacing="5" cellpadding="5">                        
					<tr>                        
						<td bgcolor="#FFE1E1" class="messageStackError"><?php echo $error_msg; ?></td>
					</tr>                        
				</table>                        
				
	<?php
			}
    
		}
	*/
	//旧的分享图片上传end
	}
} else {
	
	//图片分享 start
	if (isset($_GET['action']) && ($_GET['action'] == 'process_photos') && $_POST['ajax']=="true") {
		$dir = $_SERVER['DOCUMENT_ROOT'].'/tmp/';

		$year = date("Y");
		$month = date("m");
		$new_dir = $_SERVER['DOCUMENT_ROOT'].'/images/reviews/'.$year.'_'.$month;
		if(!file_exists($new_dir)){
			if(!mkdir($new_dir)){
				echo "mkdir error!";
				exit;
			}
		}
		foreach($_POST['image_name'] as $key => $val){ 
			if(file_exists($dir.$val)){
				rename($dir.$val, $new_dir.'/'.$val);
				$thumb_name = str_replace('detail_','thumb_',$val);
				rename($dir.$thumb_name, $new_dir.'/'.$thumb_name);
				
				$image_name_ = str_replace('detail_','',$val);
				
				$customers_email = tep_get_customers_email($_SESSION['customer_id']);
				$customers_name = tep_customers_name($_SESSION['customer_id']);
				if(!tep_not_null($_POST['image_title'][$key])){
					//$_POST['image_title'][$key] = $image_name_;
					$_POST['image_title'][$key] = general_to_ajax_string(db_to_html('未命名'));
				}
				if(!tep_not_null($_POST['image_desc'][$key])){
					//$_POST['image_desc'][$key] = $image_name_;
					$_POST['image_desc'][$key] = '';
				}
				
				$image_name_ = $year.'_'.$month.'/'.$image_name_;
				
				
				$insert_photo_sql_data_array = array(
							  'products_id' => $_GET['products_id'],
							  'customers_name' => tep_db_prepare_input($customers_name),
							  'customer_id' => intval($_SESSION['customer_id']),
							  'customers_email' => tep_db_prepare_input($customers_email),
							  'image_name' => tep_db_prepare_input($image_name_),							 
							  'image_title' => html_to_db(ajax_to_general_string(tep_db_prepare_input($_POST['image_title'][$key]))),
							  'image_desc' => html_to_db(ajax_to_general_string(tep_db_prepare_input($_POST['image_desc'][$key]))),
							  'image_status' => 0,
							   );
				tep_db_perform(TABLE_TRAVELER_PHOTOS, $insert_photo_sql_data_array);
				$insert_photo_id = tep_db_insert_id();
				#### Points/Rewards Module V2.1rc2a BOF ####*/
				if(isset($_SESSION['customer_id']) && $_SESSION['customer_id']!=''){
					if ((USE_POINTS_SYSTEM == 'true') && (tep_not_null(USE_POINTS_FOR_PHOTOS))) {
						$points_toadd = USE_POINTS_FOR_PHOTOS;
						$comment = 'TEXT_DEFAULT_REVIEWS_PHOTOS';
						$points_type = 'PH';
						tep_add_pending_points($_SESSION['customer_id'], (int)$insert_photo_id, $points_toadd, $comment, $points_type, '', (int)$products_id);
					}
				}
				#### Points/Rewards Module V2.1rc2a EOF ####*/
				
				// write to products_index
				$index_type = 'photos';
				auto_add_product_index((int)$_GET['products_id'], $index_type);
			}
		}
		
		
		$js_str = '[JS]';
		//$js_str .= 'alert("'.db_to_html("更新成功！").'");';
		$js_str .= 'jQuery("#uploaded_photos_box").val("");';
		$js_str .= "sendFormData('','".tep_href_link('review_photos.php', 'products_id=' . $_GET['products_id'])."&active_photo=1','review_result_photo','true');";
		$js_str .= 'jQuery("#review_result_photo").removeClass("photoUpload");';
		$js_str .= 'jQuery("#review_result_photo").addClass("photoList");';
		$js_str .= '[/JS]';
		echo $js_str;
		exit;
		
	}
	//图片分享 end

	echo ("the product_reviews_write has stop use!");
	/*


    $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int) $HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int) $languages_id . "'");
    if (!tep_db_num_rows($product_info_query)) {
        tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
    } else {
        $product_info = tep_db_fetch_array($product_info_query);
    }

    $customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int) $customer_id . "'");
    $customer = tep_db_fetch_array($customer_query);

    if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
        $rating = tep_db_prepare_input($HTTP_POST_VARS['rating']);
        $review = tep_db_prepare_input($HTTP_POST_VARS['review']);
        $reviews_title = tep_db_prepare_input($HTTP_POST_VARS['review_title']);
        $customers_name = tep_db_prepare_input($HTTP_POST_VARS['customers_name']);
        $customers_email = tep_db_prepare_input($HTTP_POST_VARS['customers_email']);
        $error = false;

        //if (strlen($review) < REVIEW_TEXT_MIN_LENGTH) {
        if (strlen($review) == 0) {
            $error = true;

            $messageStack->add('review', JS_REVIEW_TEXT);
        }

        if (($rating < 1) || ($rating > 5)) {
            $error = true;

            $messageStack->add('review', JS_REVIEW_RATING);
        }

        if ($error == false) {
            tep_db_query("insert into " . TABLE_REVIEWS . " (products_id, customers_id, customers_name, reviews_rating, date_added,customers_email,reviews_status) values ('" . (int) $HTTP_GET_VARS['products_id'] . "', '" . (int) $customer_id . "', '" . tep_db_input($customers_name) . "', '" . tep_db_input($rating) . "', now(),'" . tep_db_input($customers_email) . "','0')");
            $insert_id = tep_db_insert_id();

            tep_db_query("insert into " . TABLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text, reviews_title) values ('" . (int) $insert_id . "', '" . (int) $languages_id . "', '" . tep_db_input($review) . "',  '" . tep_db_input($reviews_title) . "')");

            tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath", 'NONSSL', false, true, "reviews"));
        }
    }
    $new_price = tep_get_products_special_price($product_info['products_id']);

    if ((int) $new_price) {
        $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    } else {
        $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
    }

    if (tep_not_null($product_info['products_model'])) {
        $products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
    } else {
        $products_name = $product_info['products_name'];
    }


    require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS_WRITE);

    $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));

    $content = CONTENT_PRODUCT_REVIEWS_WRITE;
    $javascript = $content . '.js';

    require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

    require(DIR_FS_INCLUDES . 'application_bottom.php');
*/
} //end of ajax if
?>