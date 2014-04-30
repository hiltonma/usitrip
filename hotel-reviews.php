<?php
/*
  $Id: links.php,v 1.2 2004/03/12 19:28:57 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');


  require(DIR_FS_LANGUAGES . $language . '/hotel-reviews.php');

	$error = false;
	if($_POST['action']=='reviews_process'){
		$customers_name = tep_db_prepare_input($_POST['customers_name']);
		$customers_email = tep_db_prepare_input($_POST['customers_email']);
		$c_customers_email = tep_db_prepare_input($_POST['c_customers_email']);
		$reviews_text = tep_db_prepare_input($_POST['reviews_text']);
		$reviews_rating = (int)$_POST['reviews_rating'];
		$hotel_id = (int)$_POST['hotel_id'];
		
		$reviews_status = 1;
		
		if (strlen($customers_email) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
		  $error = true;
		  $messageStack->add('reviews', ENTRY_EMAIL_ADDRESS_ERROR);
		} elseif (tep_validate_email($customers_email) == false) {
		  $error = true;
		  $messageStack->add('reviews', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
		}else{
			if($c_customers_email!=$customers_email){
				$error = true;
				$messageStack->add('reviews', ENTRY_CONFIRM_EMAIL_ADDRESS_CHECK_ERROR);
			}
		}
		
		if(strlen($reviews_text) <1 ){
			$error = true;
			$messageStack->add('reviews', ERROR_NEED_INPUT_REVIEWS);
		}
		
		if($error == false){
			$date_time = date('Y-m-d H:i:s');
			$sql_data_array = array('customers_name' => $customers_name,
									'customers_email' => $customers_email,
									'reviews_text' => $reviews_text,
									'reviews_rating' => $reviews_rating,
									'reviews_status' => $reviews_status,
									'hotel_id' => $hotel_id,
									'date_added' => $date_time,
									'last_modified' => $date_time
									);
			if(tep_session_is_registered('customer_id')){
				$sql_data_array['customers_id'] = (int)$customer_id;
			}
			$sql_data_array = html_to_db($sql_data_array); 
			tep_db_perform('hotel_reviews', $sql_data_array);
			
			//统计酒店平均分
			$rating_sql = tep_db_query('SELECT SUM(reviews_rating) as rating_total, count(*) as total FROM `hotel_reviews` WHERE hotel_id="'.(int)$hotel_id.'" ');
			$rating_row = tep_db_fetch_array($rating_sql);
			if(!(int)$rating_row['total']){ $rating_row['total'] = 1; }
			$average_val = $rating_row['rating_total']/$rating_row['total'];
			
			tep_db_query('UPDATE `hotel` SET `hotel_reviews_rating` = "'.ceil($average_val).'" WHERE hotel_id = "'.(int)$hotel_id.'" ');
						
			$messageStack->add_session('reviews', TEXT_REVIEW_ADDED_SUCCESS, 'success');
			
			tep_redirect(tep_href_link('hotel-reviews.php', 'hotel_id='.(int)$hotel_id));
		}
	}

  $content = 'hotel-reviews';
 //$javascript = $content . '.js.php';
  //关闭系统默认的导航栏
  $BreadOff = true;

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
