<?php
/*
  $Id: product_reviews_write.php,v 1.1.1.1 2004/03/04 23:38:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require_once('includes/application_top.php');
require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ARTICLE_REVIEWS_WRITE);
  if (!tep_session_is_registered('customer_id')) {
    //$navigation->set_snapshot();
   // tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL')); 
  }
  //strar to check post from ajax
  if(isset($_POST['aryFormData']))
  {
	  $aryFormData = $_POST['aryFormData'];
	
		foreach ($aryFormData as $key => $value )
		{
		  foreach ($value as $key2 => $value2 )
		  {	  
			$HTTP_POST_VARS[$key] = stripslashes(str_replace('@@amp;','&',$value2));    	   
		  }
		}
	
	if(isset($HTTP_POST_VARS['rating']))
	{
	   foreach($aryFormData['rating'] as $rat_key => $rat_val){
			
			if($aryFormData['rating'][$rat_key] == "true"){
				//$HTTP_POST_VARS['rating'] = $key+1;
				$HTTP_POST_VARS['rating'] = (int)$rat_key+1;
			}
		}
	}
	
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
  
  
    $customers_name = $HTTP_POST_VARS['customers_name'];
	$customers_email = $HTTP_POST_VARS['customers_email'];
	$reviews_title = $HTTP_POST_VARS['review_title']; 
    $rating = $HTTP_POST_VARS['rating'];
    $review = $HTTP_POST_VARS['review'];
	$image_caption =tep_db_prepare_input($_POST['image_caption']);
	$front =tep_db_prepare_input($HTTP_POST_VARS['front']);
	
    $error = false;
  
 
 	if($customers_name == ''){
	  $error = true;
	  $error_msg .= ERROR_CUSTOMER_NAME;
	}
	
	if($customers_email == ''){
	  $error = true;
	  $error_msg .= ERROR_CUSTOMER_EMAIL;
	}else {		
		if(is_CheckvalidEmail($customers_email) != true){
		  $error = true;
		  $error_msg .= ERROR_CUSTOMER_VALID_EMAIL;
		}
	}
	
	if($reviews_title == ''){
	  $error = true;
	  $error_msg .= ERROR_REVIEW_TITLE;
	}
 
	if (strlen($review) == 0) {
      $error = true;
	  $error_msg .= ERROR_REVIEW_TEXT;
      //$messageStack->add('review', JS_REVIEW_TEXT);
    }

    if (($rating < 1) || ($rating > 5)) {
      $error = true;
	  $error_msg .=  ERROR_REVIEW_RATING;
      //$messageStack->add('review', JS_REVIEW_RATING);
    }
//echo $error;
//exit;
    if ($error == false) {
     // tep_db_query("insert into " . TABLE_REVIEWS . " (products_id, customers_id, customers_name, reviews_rating, date_added) values ('" . (int)$HTTP_GET_VARS['products_id'] . "', '" . (int)$customer_id . "', '" . tep_db_input($customer['customers_firstname']) . ' ' . tep_db_input($customer['customers_lastname']) . "', '" . tep_db_input($rating) . "', now())");
	  tep_db_query("insert into " . TABLE_ARTICLE_REVIEWS . " (articles_id, customers_id, customers_name, reviews_rating, date_added,customers_email) values ('" . (int)$HTTP_GET_VARS['articles_id'] . "', '" . (int)$customer_id . "', '" . tep_db_input($customers_name) . "', '" . tep_db_input($rating) . "', now(),'".tep_db_input($customers_email)."')");
      $insert_id = tep_db_insert_id();

      tep_db_query("insert into " . TABLE_ARTICLE_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text, reviews_title) values ('" . (int)$insert_id . "', '" . (int)$languages_id . "', '" . tep_db_input($review) . "',  '" . tep_db_input($reviews_title) . "')");



  	 // tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath",'NONSSL',false,true,"reviews"));
	?>
	
	<?php
	echo 'review_new_added|###|';
	$article_info['articles_id'] = (int)$HTTP_GET_VARS['articles_id'];
	$reviews['reviews_id'] = $insert_id;
	$reviews['customers_email'] = tep_db_prepare_input($customers_email);
	$reviews['reviews_title'] = tep_db_prepare_input($reviews_title);
	$reviews['reviews_rating'] = tep_db_prepare_input($rating);
	$reviews['customers_name'] = tep_db_prepare_input($customers_name);
	$reviews['reviews_text'] = tep_db_prepare_input($review);
	$reviews['date_added'] = date('Y-m-d H:i:s');
	if( STORE_OWNER_EMAIL_ADDRESS == $reviews['customers_email'] ){				
				?>
					<div class="pr_b_q1">
					   <div class="pr_b_q_1 sp10 sp6">
						<table width="683">
						<tr><td width="18"><img src="image/q.gif" /></td>
						<td >
						<b><?php echo tep_output_string_protected($reviews['reviews_title']); ?> </b>
						</td>
						<td><?php
						 if($reviews['reviews_rating']){
						   echo tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating']));
						  }
						?>
						</td>
						<td  align="right"><?php echo '<span class="sp1">'.$reviews['customers_name'].'</span>&nbsp;&nbsp;|&nbsp;&nbsp;'.sprintf(tep_date_long_review($reviews['date_added'])); ?></td> 
						</tr></table></div>
						<div class="pr_b_qq sp10 sp6">
							<?php //echo tep_break_string(tep_output_string_protected($reviews['reviews_text']), 240, '-<br>') . ((strlen($reviews['reviews_text']) >= 250) ? '..' : '') ; 
							echo tep_break_string(tep_output_string_protected(substr($reviews['reviews_text'],0,240)), 80, '-<br>') . ((strlen($reviews['reviews_text']) >= 240) ? '<span id="span_id_dot_'.$reviews['reviews_id'].'">..</span><span style="DISPLAY: none" id="span_id_dot_more_'.$reviews['reviews_id'].'">'. tep_break_string(tep_output_string_protected(substr($reviews['reviews_text'],240,strlen($reviews['reviews_text']))), 80, '-<br>').'</span>' : '') ; 
							?>
						</div>
					</div>
					<div class="pr_b_qing"><div class="pr_b_qimg"><img src="image/pr_s1.gif" /></div>					
					<?php					
					//echo ((strlen($reviews['reviews_text']) >= 240) ? '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $product_info['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '" class="pr_b_qimg_t sp3">Read full review</a> ' : '');
					if(strlen($reviews['reviews_text']) >= 240){
					?>
					 <a style="CURSOR: pointer" onclick="javascript:toggel_div('span_id_dot_<?php echo $reviews['reviews_id'];?>');toggel_div('span_id_dot_more_<?php echo $reviews['reviews_id'];?>');" class="pr_b_qimg_t sp3"><?php echo TEXT_FULL_REVIEW; ?></a>
					<?php }	
					 ?>
					</div>
					<?php }else{ ?>
					<div class="pr_b_q">
					    <div class="pr_b_q_1 sp10 sp6">
						<table width="683">
						<tr><td width="18"><img src="image/q.gif" /></td>
						<td >
						<b><?php echo tep_output_string_protected($reviews['reviews_title']); ?> </b>
						</td>
						<td><?php
						 if($reviews['reviews_rating']){
						   echo tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating']));
						  }
						?>
						</td>
						<td  align="right"><?php echo $reviews['customers_name'].'&nbsp;&nbsp;|&nbsp;&nbsp;'.sprintf(tep_date_long_review($reviews['date_added'])); ?></td> 
						</tr></table></div>
						<div class="pr_b_qq sp10 sp6">
							<?php //echo tep_break_string(tep_output_string_protected($reviews['reviews_text']), 240, '-<br>') . ((strlen($reviews['reviews_text']) >= 250) ? '..' : '') ; 
							echo tep_break_string(tep_output_string_protected(substr($reviews['reviews_text'],0,240)), 80, '-<br>') . ((strlen($reviews['reviews_text']) >= 240) ? '<span id="span_id_dot_'.$reviews['reviews_id'].'">..</span><span style="DISPLAY: none" id="span_id_dot_more_'.$reviews['reviews_id'].'">'. tep_break_string(tep_output_string_protected(substr($reviews['reviews_text'],240,strlen($reviews['reviews_text']))), 80, '-<br>').'</span>' : '') ; 
							?>
						</div>
					</div>
					<div class="pr_b_qing"><div class="pr_b_qimg"><img src="image/pr_s.gif" /></div>					
					<?php					
					//echo ((strlen($reviews['reviews_text']) >= 240) ? '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $product_info['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '" class="pr_b_qimg_t sp3">Read full review</a> ' : ''); 
					if(strlen($reviews['reviews_text']) >= 240){
					?>
					 <a style="CURSOR: pointer" onclick="javascript:toggel_div('span_id_dot_<?php echo $reviews['reviews_id'];?>');toggel_div('span_id_dot_more_<?php echo $reviews['reviews_id'];?>');" class="pr_b_qimg_t sp3"><?php echo TEXT_FULL_REVIEW; ?></a>
					<?php }	
					?>
					</div>
					
					<?php } 
					echo '|###|';
					?>	
			<table border="0" width="98%" align="center" id="success_review_fad_out_id"  class="automarginclass" cellspacing="2" cellpadding="2">
		  	<tr class="messageStackSuccess">
			<td class="messageStackSuccess"><img src="image/icons/success.gif" border="0" alt="Success" title=" Success " width="10" height="10">&nbsp;<?php echo TEXT_REVIEW_SUCCESS; ?></td>
		  	</tr>
			</table>
			 <?php			  
			  echo '|###|success';
			 	?>
	<?php
	  
    }else{
	//echo $error_msg;
	//echo $error_msg;
	?>	
	<table width="100%"  border="0" cellspacing="5" cellpadding="5">
	  <tr>
		<td bgcolor="#FFE1E1" class="main"><?php echo $error_msg; ?></td>
	  </tr>
	</table>

	<?php
	}
  }
  else if(isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process_friend')) 
  {
  	
  
  $article_info_query = tep_db_query("select pd.articles_name,p.articles_seo_url,t.topics_id from " . TABLE_ARTICLES . " p, " . TABLE_ARTICLES_DESCRIPTION . " pd, ".TABLE_ARTICLES_TO_TOPICS." t where p.articles_status = '1' and p.articles_id = '" . (int)$HTTP_GET_VARS['articles_id'] . "' and p.articles_id = pd.articles_id and p.articles_id=t.articles_id and pd.language_id = '" . (int)$languages_id . "'");
    if (tep_db_num_rows($article_info_query)) {
      $valid_article = "true";
      $article_info = tep_db_fetch_array($article_info_query);
    } else {
     $valid_article = "false";
 //    tep_redirect(tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $tell_articles_id));
    }
   $_POST['to_email_address'] = preg_replace( "/\n/", " ", $_POST['to_email_address'] );
    //$_POST['to_name'] = preg_replace( "/\n/", " ", $_POST['to_name'] );
    $_POST['to_email_address'] = preg_replace( "/\r/", " ", $_POST['to_email_address'] );
    //$_POST['to_name'] = preg_replace( "/\r/", " ", $_POST['to_name'] );
    $_POST['to_email_address'] = str_replace("Content-Type:","",$_POST['to_email_address']);
    //$_POST['to_name'] = str_replace("Content-Type:","",$_POST['to_name']);
    
    $to_email_address = tep_db_prepare_input($HTTP_POST_VARS['to_email_address']);
    //$to_name = tep_db_prepare_input($HTTP_POST_VARS['to_name']);
    $from_email_address = tep_db_prepare_input($HTTP_POST_VARS['from_email_address']);
    //$from_name = tep_db_prepare_input($HTTP_POST_VARS['from_name']);
    //$message = tep_db_prepare_input($HTTP_POST_VARS['message']);
    
	$to_name_explode = explode('@',$to_email_address);
	$to_name = $to_name_explode[0];
	
	$from_name_explode = explode('@',$from_email_address);
	$from_name = $from_name_explode[0];
	
	
	$ferror = false;
       /*if (empty($from_name)) {
          $error = "true";
          $messageStack->add('friend', ERROR_FROM_NAME);
		  $error = true;
	  	  $error_msg .= "* Please enter Your Name.<br/>";
		  $error_msg .= ERROR_FROM_NAME."<br/>";
        }*/

        if (!tep_validate_email($from_email_address)) {
          $ferror = true;
		  $ferror_msg .= ERROR_FROM_ADDRESS;
          //$messageStack->add('friend', ERROR_FROM_ADDRESS);
        }
    
        /*if (empty($to_name)) {
          $error = true;
		  $error_msg .= ERROR_TO_NAME."<br/>";
          //$messageStack->add('friend', ERROR_TO_NAME);
        }*/
    
        if (!tep_validate_email($to_email_address)) {
          $ferror = true;
		  $ferror_msg .= ERROR_TO_ADDRESS;
          //$messageStack->add('friend', ERROR_TO_ADDRESS);
        }

    if ($ferror == false) {
     
          $email_subject = sprintf(TEXT_EMAIL_SUBJECT, $from_name, STORE_NAME);
          $email_body_article = sprintf(TEXT_EMAIL_INTRO, $to_name, $from_name, $article_info['articles_name'], STORE_NAME) . "\n\n";
          if (tep_not_null($message)) {
            $email_body_article .= $message . "\n\n";
          }
		  
		  $current_topic_id = $article_info['topics_id'];
		  $topic_path = seo_get_topic_url($current_topic_id);
		  
     if (TELL_ARTICLE_EMAIL_USE_HTML == 'false') {
            
			$email_body_article .= TEXT_EMAIL_LINK_TEXT . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG .'articles/'.$topic_path. $article_info['articles_seo_url'] .'">' .  HTTP_SERVER  . DIR_WS_CATALOG .'articles/'.$topic_path. $article_info['articles_seo_url']. '</a>' . "\n\n";
			//$email_body_article .= TEXT_EMAIL_LINK_TEXT . '<a href="' . tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $articles_all['articles_id']) . '">' .  HTTP_SERVER  . DIR_WS_CATALOG .'articles/'.$topic_path. $article_info['articles_seo_url']. '</a>' . "\n\n";
      $email_body_article .= TEXT_EMAIL_SIGNATURE. STORE_NAME . "\n" . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG .'">' .  HTTP_SERVER  . DIR_WS_CATALOG . '</a>';
 
    }else{ 
            $email_body_article .= TEXT_EMAIL_LINK . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG .'articles/'.$topic_path. $article_info['articles_seo_url'].'">' .  HTTP_SERVER  . DIR_WS_CATALOG .'articles/'.$topic_path.  $article_info['articles_seo_url'].'</a>' . "\n\n";
      $email_body_article .= TEXT_EMAIL_SIGNATURE. STORE_NAME . "\n" . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG .'">' .  HTTP_SERVER  . DIR_WS_CATALOG . '</a>';
      
                }
                
         $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
  
 
          if (TELL_ARTICLE_EMAIL_USE_HTML == 'false') {
              $mimemessage->add_text($email_body_article);
           } else {
              $mimemessage->add_html($email_body_article);
           }                
  
            $mimemessage->build_message();
            
			/*echo $to_name;
			echo '<br />';
			echo $to_email_address;
			echo '<br />';
			echo $from_name;
			echo '<br />';
			echo $from_email_address;
			echo '<br />';
			echo $email_subject;
			echo '<br />';
			echo $email_body_article;
			exit;*/
			
			$mimemessage->send($to_name, $to_email_address, $from_name, $from_email_address, $email_subject);

  //$messageStack->add_session('header', sprintf(TEXT_EMAIL_SUCCESSFUL_SENT, $article_info['articles_name'], tep_output_string_protected($to_name)), 'success');  
    //tep_redirect(tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $tell_articles_id));
 

  	 // tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath",'NONSSL',false,true,"reviews"));
	 
	?>
	
			<table border="0" width="98%" align="center" id="success_review_fad_out_id"  class="automarginclass" cellspacing="2" cellpadding="2">
		  	<tr class="messageStackSuccess">
			<td class="messageStackSuccess"><img src="image/icons/success.gif" border="0" alt="Success" title=" Success " width="10" height="10">&nbsp;<?php echo sprintf(TEXT_EMAIL_SUCCESSFUL_SENT, $article_info['articles_name'], tep_output_string_protected($to_name)); ?></td>
		  	</tr>
			</table>
	<?php
	  
    }else{
	//echo $error_msg;
	//echo $error_msg;
	?>	
	<table width="100%"  border="0" cellspacing="5" cellpadding="5">
	  <tr>
		<td bgcolor="#FFE1E1" class="main"><?php echo $ferror_msg; ?></td>
	  </tr>
	</table>

	<?php
	}
  
  }
  
  
  }else{


  $article_info_query = tep_db_query("select p.articles_id, p.articles_image, pd.articles_name from " . TABLE_ARTICLES . " p, " . TABLE_ARTICLES_DESCRIPTION . " pd where p.articles_id = '" . (int)$HTTP_GET_VARS['articles_id'] . "' and p.articles_id = pd.articles_id and pd.language_id = '" . (int)$languages_id . "'");
  if (!tep_db_num_rows($article_info_query)) {
    tep_redirect(tep_href_link(FILENAME_ARTICLE_REVIEWS, tep_get_all_get_params(array('action'))));
  } else {
    $article_info = tep_db_fetch_array($article_info_query);
  }

  $customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
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
     // tep_db_query("insert into " . TABLE_REVIEWS . " (products_id, customers_id, customers_name, reviews_rating, date_added) values ('" . (int)$HTTP_GET_VARS['products_id'] . "', '" . (int)$customer_id . "', '" . tep_db_input($customer['customers_firstname']) . ' ' . tep_db_input($customer['customers_lastname']) . "', '" . tep_db_input($rating) . "', now())");
	  tep_db_query("insert into " . TABLE_ARTICLES_REVIEWS . " (articles_id, customers_id, customers_name, reviews_rating, date_added) values ('" . (int)$HTTP_GET_VARS['articles_id'] . "', '" . (int)$customer_id . "', '" . tep_db_input($customers_name) . "', '" . tep_db_input($rating) . "', now())");
      $insert_id = tep_db_insert_id();

      tep_db_query("insert into " . TABLE_ARTICLES_REVIEWS_DESCRIPTION . " (reviews_id, languages_id, reviews_text) values ('" . (int)$insert_id . "', '" . (int)$languages_id . "', '" . tep_db_input($review) . "')");

      //tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
	  //tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action','page'),'NONSSL',false,true,"reviews")));
	  tep_redirect(tep_href_link(FILENAME_ARTICLE_INFO, "articles_id=$articles_id&cPath=$cPath",'NONSSL',false,true,"reviews"));

	  
    }
  }

  /*if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
    $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
  }*/

  /*if (tep_not_null($product_info['products_model'])) {
    $products_name = $product_info['products_name'] . '<br><span class="smallText">[' . $product_info['products_model'] . ']</span>';
  } else {
    $products_name = $product_info['products_name'];
  }*/
  
  
   

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ARTICLE_REVIEWS, tep_get_all_get_params()));

  $content = CONTENT_ARTICLE_REVIEWS_WRITE;
  $javascript = $content . '.js';

  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
  
  } //end of ajax if
  

 
?>
