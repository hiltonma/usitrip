<?php
  require('includes/application_top.php');
  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_TOUR_LEAD_QUESTION);
  if (!tep_session_is_registered('customer_id')) {
    //$navigation->set_snapshot();
   // tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL')); 
  }

 //amitcommecnted for login eof

  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");

  if (!tep_db_num_rows($product_info_query)) {

   // tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));

  } else {

    $product_info = tep_db_fetch_array($product_info_query);

  }

 	$success = false;
	$error = false;


	if(isset($HTTP_GET_VARS['products_id']) &&  $HTTP_GET_VARS['products_id'] != ''){
	$products_id =  $HTTP_GET_VARS['products_id'];
	}
		
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process')) {
  
 	 if(isset($HTTP_POST_VARS['products_id']) &&  $HTTP_POST_VARS['products_id'] != ''){
		$products_id =  (int)$HTTP_POST_VARS['products_id'];
	 }
	 
	 if(isset($HTTP_POST_VARS['cPath']) &&  $HTTP_POST_VARS['cPath'] != ''){
		$cPath =  $HTTP_POST_VARS['cPath'];
	 }
	//vincent Urgent-增加注册用户注册验证码，QA全站提问验证码 BEGIN
	if(!tep_not_null($_SESSION['customer_id'])){
	 if(!tep_not_null($HTTP_POST_VARS['visual_verify_code']) || strtolower($HTTP_POST_VARS['visual_verify_code'])!=strtolower($_SESSION['captcha_key'])){
	 	$error = true;
		$messageStack->add('tour_question', db_to_html('验证码错误，请输入正确的验证码！'));
	 }
	}
	//vincent Urgent-增加注册用户注册验证码，QA全站提问验证码 END
	 if(!tep_not_null($HTTP_POST_VARS['customers_name'])){
	 	$error = true;
		
		$messageStack->add('tour_question', TEXT_YOUR_FNAME_ERROR);
	 }
	 if(!tep_not_null($HTTP_POST_VARS['customers_email'])){
	 	$error = true;
		
		$messageStack->add('tour_question', TEXT_YOUR_EMAIL_ERROR);
	 }
	 /*取消邮箱确认
	 if($HTTP_POST_VARS['c_customers_email'] != $HTTP_POST_VARS['customers_email']){
	 	$error = true;
		
		$messageStack->add('tour_question', TEXT_YOUR_EMAIL_CONFIRM_ERROR);
	 }*/
	 
	 if(!tep_not_null($HTTP_POST_VARS['question'])){
	 	$error = true;
		
		$messageStack->add('tour_question', TEXT_YOUR_COMMENT_ERROR);
	 }

	 $question_query = tep_db_query('SELECT question  FROM '.TABLE_QUESTION.' WHERE customers_email =\''.tep_db_input($customers_email).'\' AND products_id=\''.(int)$products_id.'\'');
	 $new_question = preg_replace("/(\s|\n)+/",'',html_to_db($HTTP_POST_VARS['question']));	 
	while($old_question = tep_db_fetch_array($question_query)) {
		$old_question = preg_replace("/(\s|\n)+/",'',$old_question['question']);	
		if($old_question == $new_question){
				$error = true;				
				$messageStack->add('tour_question', db_to_html("请不要重复提交问题!"));
				break;
		}
	}

	 if($error == false){

  			$sql_data_array = array('products_id' => (int)($products_id),
									'customers_name' => tep_db_prepare_input($HTTP_POST_VARS['customers_name']),
									'customers_email' => tep_db_prepare_input($HTTP_POST_VARS['customers_email']),
									'question' => tep_db_prepare_input($HTTP_POST_VARS['question']),									
                                    'date' => 'now()',
									'languages_id' => (int)$languages_id,
									'accept_newsletter' => (int)$HTTP_POST_VARS['accept_newsletter'],
									'customers_ip' => tep_get_ip_address()
								  );


          $sql_data_array = html_to_db($sql_data_array);
		  tep_db_perform('tour_question', $sql_data_array);

	  
		  tep_redirect(tep_href_link('tour_question.php', "products_id=$products_id&cPath=$cPath&send=true"));
		  exit;
	  }

 	if(isset($_POST['redirectto']) && $_POST['redirectto'] == "question"){
	tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, "products_id=$products_id&cPath=$cPath&success=true",'NONSSL',true,true,"qanda"));
	exit;
	}
	
  }



  if ($new_price = tep_get_products_special_price($product_info['products_id'])) {

    $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';

  } else {

    $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));

  }



  if (tep_not_null($product_info['products_model'])) {
  	 $products_name = $product_info['products_name'] . '&nbsp;<span class="sp10 sp6">[' . $product_info['products_model'] . ']</span>';
  } else {
    $products_name = $product_info['products_name'];
  }





  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_TOUR_LEAD_QUESTION, tep_get_all_get_params()));
  $validation_include_js = 'true';
  $content = 'tour_question';
  //$javascript = $content.'.js';
  
  //Html页面用到的变量和数组 start
  $form_head = tep_draw_form('product_queston_write', tep_href_link('tour_question.php', 'action=process'), 'post', 'id="frm_product_queston_write"');
  $form_bottom = '</form>';
  
  $shows = array();
  $shows['all_need_input'] = db_to_html('以下信息都必须填写');
  $shows['agree_new_info'] = db_to_html('接受走四方网的旅游资讯:');
  $shows['yes'] = db_to_html('是');
  $shows['no'] = db_to_html('否');
  $shows['link_back'] = '<a href="' . tep_href_link('all_question_answers.php') . '">' . db_to_html("返回问题咨询列表") . '&gt;&gt;</a>';
  $shows['link_history_go'] = '<a href="JavaScript:window.history.go(-1);">' . db_to_html("返回上一页") . '&gt;&gt;</a>';
  $shows['products_name'] = db_to_html($products_name);
  $shows['message'] = "";
  if ($messageStack->size('tour_question') > 0) {
  	$shows['message'] = '<tr><td>'.$messageStack->output('tour_question').'</td></tr><tr><td>'.tep_draw_separator('pixel_trans.gif', '100%', '10') .'</td></tr>';
  }
  
  $input_fields = array();
  $textarea_fields = array();
  $input_fields['customers_name'] = tep_draw_input_field('customers_name',db_to_html(ucfirst($customer_first_name)),'  class="required text username" id="customers_name" title="'.TEXT_YOUR_FNAME_ERROR.'" ');
  $input_fields['customers_email'] = tep_draw_input_field('customers_email',tep_get_customers_email($customer_id),'class="required validate-email text email" id="customers_email" title="'.TEXT_YOUR_EMAIL_ERROR.'" ');
  //$input_fields['c_customers_email'] = tep_draw_input_field('c_customers_email',tep_get_customers_email($customer_id),' class="required validate-email-confirm-que text email" title="'.TEXT_YOUR_EMAIL_CONFIRM_ERROR.'" id="c_customers_email"');
  $textarea_fields['question'] = tep_draw_textarea_field('question', 'soft', '', '','',' class="required textarea"  id="question" title="'.TEXT_YOUR_COMMENT_ERROR.'"');
  $submit_button = "";
  if($send != true){ 
  	$submit_button = '<a class="btn btnOrange"><button type="submit">'.db_to_html("提交问题").'</button></a>';	//tep_image_submit('button_submit_question.gif', IMAGE_BUTTON_CONTINUE);
  }
	$vvcUrl = preg_replace($p,$r,tep_href_link_noseo(FILENAME_CREATE_ACCOUNT));
	$RandomStr = md5(microtime());// md5 to generate the random string										
	$_SESSION['captcha_key'] = $ResultStr = substr($RandomStr,0,4);//trim 5 digit
	$RandomImg = '<img width="66" height="22" align="absmiddle" onclick="updateVVC()" id="vvc" alt="'.db_to_html('看不清?点击换一张图。').'" title="'.db_to_html('看不清?点击换一张图。').'" src="php_captcha.php?code='. base64_encode($_SESSION['captcha_key']).'"  /> <a href="javascript:;" onclick="updateVVC()">'.db_to_html('看不清?点击换一张图。').'</a> ';
	$RandomCodeText = db_to_html("验证码:");
	$msgSuccess = db_to_html('您的咨询已经成功提交，我们将尽快回复您。');
  $input_fields['visual_verify_code'] = tep_draw_input_field('visual_verify_code', '', 'title="'.db_to_html("请输入图片中显示的字符，不区分大小写。").'" class="required text" ', '', false);
  $useVisualVerifyCode = tep_not_null($_SESSION['customer_id'])? '0':'1';
  //Html页面用到的变量和数组 end
  
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_FS_INCLUDES . 'application_bottom.php');
?>
