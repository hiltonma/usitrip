<?php
function isdate($str, $format = "Y-m-d") {
	$strArr = explode("-", $str);
	if (empty($strArr)) {
		return false;
	}
	foreach ( $strArr as $val ) {
		if (strlen($val) < 2) {
			$val = "0" . $val;
		}
		$newArr[] = $val;
	}
	$str = implode("-", $newArr);
	$unixTime = strtotime($str);
	$checkDate = date($format, $unixTime);
	if ($checkDate == $str)
		return true;
	else
		return false;
}
function isdate2($str, $format = "Y-m-d H:i:s") {
	$strArr = explode(" ", $str);
	if (empty($strArr)) {
		return false;
	}
	
	if (! isdate($strArr[0]))
		return false;
	$strArr2 = explode(":", $strArr[1]);
	if (empty($strArr2)) {
		return false;
	}
	if (count($strArr2) == 3)
		return true;
	else
		false;
}

require_once ('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('question_answers');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}

if ($_GET['que_id'] != '') {
	$que_id = $_GET['que_id'];
}

if (isset($_GET['dealscat']) && $_GET['dealscat'] != '') {
	$dealscat = $_GET['dealscat'];
}

// Howard updated 2010-10-27
// amit added to delete answer start
if ($_GET['action'] == 'view_question' && isset($_GET['deleteansid']) && $_GET['deleteansid'] != '') {
	tep_db_query("delete from " . TABLE_QUESTION_ANSWER . " where ans_id = '" . tep_db_input($_GET['deleteansid']) . "'");
	// 更新首页的咨询xml文档
	update_xml_for_index_page_customer_questions();
	
	if ($_GET['ajax'] == "true" && ( int ) $_GET['que_id']) {
		header("HTTP/1.0 200 OK");
		header("Status: 200 OK");
		echo '[JS] show_this_ans(' . ( int ) $_GET['que_id'] . ', 1);[/JS]';
		exit();
	}
}
// amit added to delete answer end

if ($_GET['action']) {
	
	switch ($_GET['action']) {
		case 'top' :  //置顶
			$str_sql = 'update tour_question set is_top=((is_top-1)*-1) where que_id=' . ( int ) $_POST['que_id'];
			//echo $str_sql;
			tep_db_query($str_sql);
			break;
		case 'copy' : //拷贝
		$time_mark=strtotime($_POST['show_time']);
		$i=0;
		$tmp=explode(',',$_POST['copy_to']);
		foreach($tmp as $value){
		if($value){
		
		$question_time=date('Y-m-d H:i:s',$time_mark-$i*86400*3);
		$anser_time=date('Y-m-d H:i:s',strtotime($question_time)-86400);
		$str_sql = ' INSERT INTO `tour_question`( `products_id`, `question`, `date`, `first_answer_date`, `customers_name`, `customers_email`, `sent_mail_count`, `replay`, `replay_has_checked`, `que_replied`, `languages_id`, `que_sort_order`, `set_hit`, `accept_newsletter`, `customers_ip`)
SELECT ' . ( int ) $value. ', `question`, "' . $question_time . '", `first_answer_date`, `customers_name`, `customers_email`, `sent_mail_count`, `replay`, `replay_has_checked`, `que_replied`, `languages_id`, `que_sort_order`, `set_hit`, `accept_newsletter`, `customers_ip`
FROM tour_question 
WHERE `que_id` =' . ( int ) $_POST['que_id'];
			tep_db_query($str_sql);
			$insert_id = tep_db_insert_id();
			$str_sql = 'insert into tour_question_answer(`que_id`, `ans`, `date`, `replay_email`, `replay_name`, `languages_id`, `modified_by`, `has_checked`, `ans_orig`) SELECT ' . $insert_id . ', `ans`, "'.$anser_time.'", `replay_email`, `replay_name`, `languages_id`, `modified_by`, `has_checked`, `ans_orig` FROM `tour_question_answer` WHERE que_id=' . ( int ) $_POST['que_id'];
			tep_db_query($str_sql);
		$i++;
		}
		}
			
			break;
		
		// 设置已通过审核
		case 'set_has_checked' :
			if (( int ) $_GET['ans_id'] && ( int ) $_GET['que_id']) {
			
			//发送邮件的地方按需求添加 WTJ add with 20121219{
					$que_id = ( int ) $_GET['que_id'];
					$fromemail = STORE_OWNER_EMAIL_ADDRESS;
					$sownername = STORE_OWNER . " ";
					$tablink = '-question-answer';
					
					$subject = "您在走四方旅游网的咨询，已得到专业旅游顾问的最新解答，快去了解下！ ";
					$main_text = "由于我们之前对您的咨询回复得尚有不足，为保证您的需求/疑问得到完美解答，走四方旅游网的专业旅游顾问已刚刚为您做了更为详细的补充回复！";
					
					$findcustomerdata = tep_db_query("select * from " . TABLE_QUESTION . " where que_id = '" . ( int ) $que_id. "'");
					if ($customerdata = tep_db_fetch_array($findcustomerdata)) {
						$sent_mail_count = $customerdata["sent_mail_count"];
						if (( int ) $sent_mail_count < 1) { // 如果是第一次发邮件，主题如下：
							$subject = "您在走四方旅游网的咨询，已经得到了走四方旅游网专业客服的回答！ ";
							$main_text = "您的需求/疑问我们已及时受理！<br>同时走四方旅游网的专业旅游顾问已为您进行了详细作答！";
						}
						$c_email = $customerdata["customers_email"];
						$products_id = $customerdata["products_id"];
						$cust_que = $customerdata["question"];
						$cPath = tep_get_products_catagory_id($products_id);
						$c_name = ucfirst(substr($customerdata["customers_name"], 0, 1)) . substr($customerdata["customers_name"], 1, strlen($customerdata["customers_name"]));
						/*
						 * $message = 	"<table width='100%' border='0'
						 * cellpadding='0' cellspacing='0' >". "<tr>". "<td
						 * valign='top' colspan='2'>Hi, $c_name,</td>". "</tr>".
						 * "<tr>". "<td valign='top'><br>Please click <a
						 * href=".HTTP_CATALOG_SERVER.'/'.seo_get_products_path($products_id,
						 * true,
						 * $tablink).">".HTTP_CATALOG_SERVER.'/'.seo_get_products_path($products_id,
						 * true, $tablink)."</a> to review the answer.</td>".
						 * "</tr>". "<tr>". "<td valign='top'><br>Thank you for
						 * your inquiry. ( ".$cust_que." )</td>". "</tr>".
						 * "<tr>". "<td
						 * valign='top'>".TEXT_DEFULT_ANSWER_REPLY_FOOT."</td>".
						 * "</tr>". "</table>";
						 */
						
						// zhh fix and added
						
						$to_name = $c_name . " ";
						$to_email_address = $c_email;
						$email_subject = $subject . " ";
						
						// $email_text = $message;
						
						$from_email_name = $sownername;
						$from_email_address = $fromemail;
						
						if (( int ) $products_id) {
							$link_href = HTTP_CATALOG_SERVER . '/' . seo_get_products_path($products_id, true, $tablink);
						} else {
							$link_href = HTTP_CATALOG_SERVER . '/all_question_answers.php';
						}
						$product_link_page = '<a href="' . $link_href . '">' . $link_href . '</a>';
						
						// howard added new eamil tpl
						$patterns = array();
						$patterns[0] = '{CustomerName}';
						$patterns[1] = '{images}';
						$patterns[2] = '{HTTP_SERVER}';
						$patterns[3] = '{HTTPS_SERVER}';
						$patterns[4] = '{ProductInfoPage}';
						$patterns[5] = '{EMAIL}';
						$patterns[6] = '{CONFORMATION_EMAIL_FOOTER}';
						$patterns[7] = '{LINK_HREF}';
						$patterns[8] = '{main_text}';
						
						$replacements = array();
						$replacements[0] = $to_name;
						$replacements[1] = HTTP_SERVER . '/email_tpl/images';
						$replacements[2] = HTTP_SERVER;
						$replacements[3] = HTTPS_SERVER;
						$replacements[4] = $product_link_page;
						$replacements[5] = $to_email_address;
						$replacements[6] = db_to_html(nl2br(CONFORMATION_EMAIL_FOOTER));
						$replacements[7] = $link_href;
						$replacements[8] = $main_text;
						
						$email_tpl = file_get_contents(DIR_FS_CATALOG . 'email_tpl/header.tpl.html');
						$email_tpl .= file_get_contents(DIR_FS_CATALOG . 'email_tpl/question_and_answer.tpl.html');
						$email_tpl .= file_get_contents(DIR_FS_CATALOG . 'email_tpl/footer.tpl.html');
						
						$email_text = str_replace($patterns, $replacements, db_to_html($email_tpl));
						$email_text = preg_replace('/[[:space:]]+/', ' ', $email_text);
						
						// howard added new eamil tpl end
						$email_charset = CHARSET;
						if (tep_not_null($to_email_address)) {
							tep_mail(iconv(CHARSET, $email_charset . '//IGNORE', $to_name), $to_email_address, iconv(CHARSET, $email_charset . '//IGNORE', $email_subject), iconv(CHARSET, $email_charset . '//IGNORE', $email_text), $from_email_name, $from_email_address, 'true');
							tep_db_query('update tour_question set sent_mail_count = (sent_mail_count+1) where que_id="' . ( int ) $_GET['que_id'] . '"');
						}
					}
				//发送邮件的地方按需求添加 WTJ add with 20121219}
				
				
				
				tep_db_query("update " . TABLE_QUESTION_ANSWER . " set has_checked='1' where ans_id='" . ( int ) $_GET['ans_id'] . "' and que_id='" . ( int ) $_GET['que_id'] . "' ");
				tep_db_query("update " . TABLE_QUESTION . " set replay_has_checked='1' where que_id='" . ( int ) $_GET['que_id'] . "' ");
				
				$js_str = ' show_this_ans(' . ( int ) $_GET['que_id'] . ', 1);';
				$js_string = '[JS]' . preg_replace('/[[:space:]]+/', ' ', $js_str) . '[/JS]';
				echo $js_string;
				exit();
			}
			break;
		case 'move_to_leads' :
			$que_id = ( int ) $_GET['que_id'];
			if (( int ) $que_id) {
				$q_sql = tep_db_query('SELECT que_id, products_id, question, date, customers_name, customers_email, replay,
					que_replied, languages_id, que_sort_order FROM `tour_question` WHERE que_id="' . ( int ) $que_id . '" limit 1');
				$q_row = tep_db_fetch_array($q_sql);
				if (tep_not_null($q_row['question'])) {
					$sql_data_array = array(
							'products_id' => ( int ) $q_row['products_id'],
							'lead_fname' => $q_row['customers_name'],
							'lead_email' => $q_row['customers_email'],
							'lead_comment' => $q_row['question'],
							// 'date_added' => 'now()',
							'date_added' => $q_row['date'],
							'que_id' => $q_row['que_id'] 
					);
					
					tep_db_perform(TABLE_TOUR_LEADS_INFO, $sql_data_array);
					$lead_id = tep_db_insert_id();
					if (( int ) $lead_id) {
						tep_db_query('DELETE FROM `tour_question` WHERE que_id="' . ( int ) $que_id . '" limit 1');
						
						$messageStack->add_session('更新成功！您目前已经转到Leads页面。', 'success');
						// 更新首页的咨询xml文档
						update_xml_for_index_page_customer_questions();
						
						tep_redirect(tep_href_link('tour_lead_question.php', 'lead_id=' . $lead_id));
						exit();
					}
				}
			}
			
			break;
		
		case 'update_ans_confirm' :
			
			if (isset($_GET['que_id']) && isset($_GET['ans_id'])) {
				
				if ($_POST['need_send_email_to_service_admin_id'] > 0) {
					$old_ans_sql = tep_db_query('SELECT ans,ans_orig, modified_by FROM ' . TABLE_QUESTION_ANSWER . ' WHERE que_id="' . ( int ) $_GET['que_id'] . '" and ans_id="' . ( int ) $_GET['ans_id'] . '" ');
					$old_ans = tep_db_fetch_array($old_ans_sql);
					$old_ans_contents = nl2br(tep_db_output($old_ans['ans']));
					$orig_ans_contents = nl2br(tep_db_output($old_ans['ans_orig']));
					$old_modified_by = ( int ) $old_ans['modified_by'];
				}
				
				$new_ans_contents = nl2br(tep_db_output(tep_db_input(tep_db_prepare_input($_POST['update_anwers']))));
				
				$sql_data_array = array(
						'ans' => tep_db_prepare_input($_POST['update_anwers']),
						'modified_by' => $login_id,
						'date' => date("Y-m-d H:i:s"),
						'has_checked' => ( int ) $_POST['has_checked'] 
				);
				if (isset($_POST['answer_date']) && isdate2($_POST['answer_date'])) { // 修改回答的时间
					$sql_data_array['date'] = $_POST['answer_date'];
				}
				if (isset($_POST['add_date']) && isdate2($_POST['add_date']) && isset($_POST['answer_date']) && isdate2($_POST['answer_date'])) { // 修改问题的时间，最后回答的时间
					$sql_question_array = array(
							'date' => $_POST['add_date'],
							'first_answer_date' => $_POST['answer_date'] 
					);
					tep_db_perform(TABLE_QUESTION, $sql_question_array, 'update', "que_id = '" . tep_db_prepare_input($_GET['que_id']) . "'");
				}
				tep_db_perform(TABLE_QUESTION_ANSWER, $sql_data_array, 'update', "ans_id='" . tep_db_prepare_input($_GET['ans_id']) . "' and que_id = '" . tep_db_prepare_input($_GET['que_id']) . "'");
				
				tep_add_qa_history(2, $login_id, $_GET['que_id'], $_GET['ans_id']); // 记录该操作
				                                                                 // 2 修改回复
				$email_charset = CHARSET;
				// 发最新更新的通知给上一个客服
				if ($_POST['need_send_email_to_service_admin_id'] > 0 && ( int ) $old_modified_by) {
					$question_sql = tep_db_query('SELECT question, customers_email FROM ' . TABLE_QUESTION . ' WHERE que_id ="' . ( int ) $_GET['que_id'] . '" ');
					$question_row = tep_db_fetch_array($question_sql);
					
					$email_text = '<div><b>客户的问题：</b></div><div>' . nl2br(tep_db_output($question_row['question'])) . '</div><hr>';
					$email_text .= '<div><b>最新更新的回复：</b></div><div>' . $new_ans_contents . '</div><hr>';
					if ($old_ans_contents != $orig_ans_contents)
						$email_text .= '<div><b>修改前的回复：</b></div><div>' . $old_ans_contents . '</div><hr>';
					$email_text .= '<div><b>您的原始回复：</b></div><div>' . $orig_ans_contents . '</div><hr>';
					$source_url = tep_href_link('question_answers.php', 'action=view_question&que_id=' . ( int ) $_GET['que_id']);
					$email_text .= '<div>参考网址：<a href="' . $source_url . '" target="_blank">' . $source_url . '</a></div><hr>';
					
					$customers_email = $question_row['customers_email'];
					$email_text .= '<div>客人邮箱：' . $customers_email . '</div><hr>';
					
					$email_subject = "您的Q&A回复已经被更新-走四方网客服系统 ";
					$to_name = tep_get_admin_customer_name($old_modified_by) . " ";
					$to_email_address = tep_get_admin_customer_email($old_modified_by);
					$from_email_name = tep_get_admin_customer_name($login_id) . " ";
					$from_email_address = tep_get_admin_customer_email($login_id);
					if (tep_not_null($to_email_address)) {
						tep_mail(iconv(CHARSET, $email_charset . '//IGNORE', $to_name), strtolower($to_email_address), iconv(CHARSET, $email_charset . '//IGNORE', $email_subject), iconv(CHARSET, $email_charset . '//IGNORE', $email_text), $from_email_name, $from_email_address, 'true');
					}
				}
				// 同时发通知客户邮件
				if (0&&$_POST['need_send_email_to_customers'] == "1") {//发送邮件的地方按需求停掉 WTJ add with 20121219
					$fromemail = STORE_OWNER_EMAIL_ADDRESS;
					$sownername = STORE_OWNER . " ";
					$tablink = '-question-answer';
					
					$subject = "您在走四方旅游网的咨询，已得到专业旅游顾问的最新解答，快去了解下！ ";
					$main_text = "由于我们之前对您的咨询回复得尚有不足，为保证您的需求/疑问得到完美解答，走四方旅游网的专业旅游顾问已刚刚为您做了更为详细的补充回复！";
					
					$findcustomerdata = tep_db_query("select * from " . TABLE_QUESTION . " where que_id = '" . ( int ) $que_id . "'");
					if ($customerdata = tep_db_fetch_array($findcustomerdata)) {
						$sent_mail_count = $customerdata["sent_mail_count"];
						if (( int ) $sent_mail_count < 1) { // 如果是第一次发邮件，主题如下：
							$subject = "您在走四方旅游网的咨询，已经得到了走四方旅游网专业客服的回答！ ";
							$main_text = "您的需求/疑问我们已及时受理！<br>同时走四方旅游网的专业旅游顾问已为您进行了详细作答！";
						}
						$c_email = $customerdata["customers_email"];
						$products_id = $customerdata["products_id"];
						$cust_que = $customerdata["question"];
						$cPath = tep_get_products_catagory_id($products_id);
						$c_name = ucfirst(substr($customerdata["customers_name"], 0, 1)) . substr($customerdata["customers_name"], 1, strlen($customerdata["customers_name"]));
						/*
						 * $message = 	"<table width='100%' border='0'
						 * cellpadding='0' cellspacing='0' >". "<tr>". "<td
						 * valign='top' colspan='2'>Hi, $c_name,</td>". "</tr>".
						 * "<tr>". "<td valign='top'><br>Please click <a
						 * href=".HTTP_CATALOG_SERVER.'/'.seo_get_products_path($products_id,
						 * true,
						 * $tablink).">".HTTP_CATALOG_SERVER.'/'.seo_get_products_path($products_id,
						 * true, $tablink)."</a> to review the answer.</td>".
						 * "</tr>". "<tr>". "<td valign='top'><br>Thank you for
						 * your inquiry. ( ".$cust_que." )</td>". "</tr>".
						 * "<tr>". "<td
						 * valign='top'>".TEXT_DEFULT_ANSWER_REPLY_FOOT."</td>".
						 * "</tr>". "</table>";
						 */
						
						// zhh fix and added
						
						$to_name = $c_name . " ";
						$to_email_address = $c_email;
						$email_subject = $subject . " ";
						
						// $email_text = $message;
						
						$from_email_name = $sownername;
						$from_email_address = $fromemail;
						
						if (( int ) $products_id) {
							$link_href = HTTP_CATALOG_SERVER . '/' . seo_get_products_path($products_id, true, $tablink);
						} else {
							$link_href = HTTP_CATALOG_SERVER . '/all_question_answers.php';
						}
						$product_link_page = '<a href="' . $link_href . '">' . $link_href . '</a>';
						
						// howard added new eamil tpl
						$patterns = array();
						$patterns[0] = '{CustomerName}';
						$patterns[1] = '{images}';
						$patterns[2] = '{HTTP_SERVER}';
						$patterns[3] = '{HTTPS_SERVER}';
						$patterns[4] = '{ProductInfoPage}';
						$patterns[5] = '{EMAIL}';
						$patterns[6] = '{CONFORMATION_EMAIL_FOOTER}';
						$patterns[7] = '{LINK_HREF}';
						$patterns[8] = '{main_text}';
						
						$replacements = array();
						$replacements[0] = $to_name;
						$replacements[1] = HTTP_SERVER . '/email_tpl/images';
						$replacements[2] = HTTP_SERVER;
						$replacements[3] = HTTPS_SERVER;
						$replacements[4] = $product_link_page;
						$replacements[5] = $to_email_address;
						$replacements[6] = db_to_html(nl2br(CONFORMATION_EMAIL_FOOTER));
						$replacements[7] = $link_href;
						$replacements[8] = $main_text;
						
						$email_tpl = file_get_contents(DIR_FS_CATALOG . 'email_tpl/header.tpl.html');
						$email_tpl .= file_get_contents(DIR_FS_CATALOG . 'email_tpl/question_and_answer.tpl.html');
						$email_tpl .= file_get_contents(DIR_FS_CATALOG . 'email_tpl/footer.tpl.html');
						
						$email_text = str_replace($patterns, $replacements, db_to_html($email_tpl));
						$email_text = preg_replace('/[[:space:]]+/', ' ', $email_text);
						
						// howard added new eamil tpl end
						$email_charset = CHARSET;
						if (tep_not_null($to_email_address)) {
							tep_mail(iconv(CHARSET, $email_charset . '//IGNORE', $to_name), $to_email_address, iconv(CHARSET, $email_charset . '//IGNORE', $email_subject), iconv(CHARSET, $email_charset . '//IGNORE', $email_text), $from_email_name, $from_email_address, 'true');
							tep_db_query('update tour_question set sent_mail_count = (sent_mail_count+1) where que_id="' . ( int ) $_GET['que_id'] . '"');
						}
					}
				}
			}
			
			// 更新首页的咨询xml文档
			update_xml_for_index_page_customer_questions();
			
			$que_id_str = 'action=view_question&que_id=' . $_GET['que_id'];
			tep_redirect(tep_href_link(FILENAME_QUESTION_ANSWERS, $que_id_str . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '')));
			
			break;
		
		case 'move_banner_confirm' :
			
			$question_query_raw = "select * from " . TABLE_QUESTION . "  where que_id =" . ( int ) $_POST['que_id'] . "";
			$question_query = tep_db_query($question_query_raw);
			$question = tep_db_fetch_array($question_query);
			
			$question_query_answer_raw = tep_db_query("select ans from " . TABLE_QUESTION_ANSWER . " where que_id = '" . ( int ) $question['que_id'] . "' order by date asc");
			
			if (tep_db_num_rows($question_query_answer_raw) > 0) {
				$final_answer_str = '';
				while ( $question_ans = tep_db_fetch_array($question_query_answer_raw) ) {
					$final_answer_str .= $question_ans['ans'] . '<br><br>';
				}
			}
			
			// add question to faq
			$data[visible] = '1';
			$data[v_order] = '';
			$data[question] = tep_db_input($question['question']);
			$data[answer] = tep_db_input($final_answer_str);
			$data[faq_language] = $language;
			$data['faq_category'] = $_POST['faq_category'];
			
			$query = "INSERT INTO " . TABLE_FAQ . " VALUES(null, '$data[visible]', '$data[v_order]', '$data[question]', '$data[answer]', NOW(''),'$data[faq_language]')";
			tep_db_query($query);
			
			// update category info
			$fID = tep_db_insert_id();
			tep_db_query("insert into " . TABLE_FAQ_TO_CATEGORIES . " (faq_id, categories_id) values ('" . ( int ) $fID . "', '" . ( int ) $data['faq_category'] . "')");
			// add question to faq end
			
			if ($fID > 0) {
				$messageStack->add_session(SUCCESS_MOVE_FAQ_SYSTEM, 'success');
			}
			
			$que_id_str = 'que_id=' . $_POST['que_id'];
			// check if need to delete question on tour detail page
			if ($_POST['delete_que_tour_page'] == '1') {
				$que_id = tep_db_prepare_input($_POST['que_id']);
				tep_db_query("delete from " . TABLE_QUESTION . " where que_id = '" . tep_db_input($que_id) . "'");
				tep_db_query("delete from " . TABLE_QUESTION_ANSWER . " where que_id = '" . tep_db_input($que_id) . "'");
				$messageStack->add_session(SUCCESS_DELETED_FROM_TOUR_PAGE, 'success');
				$que_id_str = '';
			}
			
			// 更新首页的咨询xml文档
			update_xml_for_index_page_customer_questions();
			
			tep_redirect(tep_href_link(FILENAME_QUESTION_ANSWERS, $que_id_str . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '')));
			
			break;
		
		case 'setflag' : // set the status of a news item.
			
			if (($_GET['flag'] == '0') || ($_GET['flag'] == '1')) {
				
				if ($_GET['que_id']) {
					tep_db_query("update " . TABLE_QUESTION . " set deals_status = '" . $_GET['flag'] . "' where que_id = '" . $_GET['que_id'] . "'");
				}
			}
			
			// tep_redirect(tep_href_link(FILENAME_QUESTION_ANSWERS,'s_id='.$_GET['s_id']));
			
			if (isset($_GET['dealscat']) && $_GET['dealscat'] != '') {
				tep_redirect(tep_href_link(FILENAME_QUESTION_ANSWERS, 's_id=' . $_GET['s_id'] . '&dealscat=' . $dealscat));
			} else {
				tep_redirect(tep_href_link(FILENAME_QUESTION_ANSWERS, 's_id=' . $_GET['s_id']));
			}
			
			break;
		
		case 'delete_question_confirm' : // user has confirmed deletion of news
		                                 // article.
			
			if (( int ) $_POST['que_id']) {
				$que_id = ( int ) ($_POST['que_id']);
				tep_db_query("delete from " . TABLE_QUESTION . " where que_id = '" . ( int ) ($que_id) . "'");
				tep_db_query("delete from " . TABLE_QUESTION_ANSWER . " where que_id = '" . ( int ) ($que_id) . "'");
			}
			
			if (tep_not_null($_GET['que_ids']) && $_GET['ajax'] == 'true') {
				$que_ids = preg_replace('/,$/', '', $_GET['que_ids']);
				tep_db_query("delete from " . TABLE_QUESTION . " where que_id in(" . tep_db_input($que_ids) . ") ");
				tep_db_query("delete from " . TABLE_QUESTION_ANSWER . " where que_id in(" . tep_db_input($que_ids) . ") ");
			}
			
			// 更新首页的咨询xml文档
			update_xml_for_index_page_customer_questions();
			
			// tep_redirect(tep_href_link(FILENAME_QUESTION_ANSWERS));
			$redirect_url = tep_href_link(FILENAME_QUESTION_ANSWERS, 'couponcat=' . $couponcat . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : ''));
			if ($_GET['ajax'] == 'true') {
				header("HTTP/1.0 200 OK");
				header("Status: 200 OK");
				header("Content-type: text/html; charset=gb2312");
				$js_str = '[JS]alert("删除成功！"); location="' . $redirect_url . '"[/JS]';
				echo $js_str;
				exit();
			} else {
				tep_redirect($redirect_url);
			}
			break;
		
		case 'insert_stores_deals' : // insert a new news article.
			
			/*
			 * //if ($_POST['que_id']) { $sql_data_array = array( 'stores_id' =>
			 * tep_db_prepare_input($_POST['stores_id']), 'categories_id' =>
			 * tep_db_prepare_input($_POST['categories_id']), 'question' =>
			 * tep_db_prepare_input($_POST['question']), 'deals_url' =>
			 * tep_db_prepare_input($_POST['deals_url']), 'deals_description' =>
			 * tep_db_prepare_input($_POST['deals_description']),
			 * 'deals_date_added' => 'now()', //uses the inbuilt mysql function
			 * 'now' 'deals_status' => '1', ); tep_db_perform(TABLE_QUESTION,
			 * $sql_data_array); $que_id = tep_db_insert_id(); //not actually
			 * used ATM -- just there in case //}
			 * tep_redirect(tep_href_link(FILENAME_QUESTION_ANSWERS));
			 */
			
			break;
		
		case 'update_que_ans' : // user wants to modify a news article.
			
			if ($_GET['que_id']) {
				
				$sql_data_array = array(
						'question' => tep_db_prepare_input($_POST['question']),
						'que_sort_order' => tep_db_input($_POST['que_sort_order']),
						'set_hit' => tep_db_input($_POST['set_hit']) 
				);
				
				tep_db_perform(TABLE_QUESTION, $sql_data_array, 'update', "que_id = '" . tep_db_prepare_input($_GET['que_id']) . "'");
				// 更新Tag内容 start
				tep_db_query('DELETE FROM ' . TABLE_QUESTION_TO_TAB . ' WHERE `que_id` = "' . tep_db_prepare_input($_GET['que_id']) . '" ');
				if (count($_POST['class_box'])) {
					foreach ( ( array ) $_POST['class_box'] as $key => $val ) {
						if (( int ) $val) {
							tep_db_query('INSERT INTO ' . TABLE_QUESTION_TO_TAB . ' ( `tour_question_tab_id`  , `que_id` ) VALUES ("' . $val . '", "' . tep_db_prepare_input($_GET['que_id']) . '");');
						}
					}
				}
				// 更新Tag内容 end
				
				// 更新首页的咨询xml文档
				update_xml_for_index_page_customer_questions();
			}
			
			// tep_redirect(tep_href_link(FILENAME_QUESTION_ANSWERS));
			
			$add_extra_action == '';
			if ($_POST['editfromque'] == 'view') {
				$add_extra_action = '&action=view_question';
			}
			
			tep_redirect(tep_href_link(FILENAME_QUESTION_ANSWERS, "que_id=" . tep_db_prepare_input($_GET['que_id']) . $add_extra_action . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '')));
			break;
		
		case 'process' :
			// send ans from admin bof
			
			if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
				
				$que_id = $_GET['que_id'];
				
				$replay_name = tep_db_prepare_input($_POST['replay_name']);
				
				$replay_email = tep_db_prepare_input($_POST['replay_email']);
				
				$anwers = tep_db_prepare_input($_POST['anwers']);
				
// 				$replay_name = ucfirst(substr($replay_name, 0, 1)) . substr($replay_name, 1, strlen($replay_name));
				$replay_name=ucfirst($replay_name);
				// tep_db_query("insert into " . TABLE_REVIEWS . " (products_id,
				// replay_name, replay_email, reviews_rating, date_added) values
				// ('" . (int)$_GET['products_id'] . "', '" .
				// tep_db_input($replay_name) . "','" .
				// tep_db_input($replay_email) . "', '" . tep_db_input($rating)
				// . "', now())");
				
				// $insert_id = tep_db_insert_id();
				
				if ($replay_name != '' && $anwers != '') {
					
					$check_first_date_update_query = tep_db_query("select first_answer_date from " . TABLE_QUESTION . " where que_id = '" . ( int ) $que_id . "'");
					if ($check_first_date_update = tep_db_fetch_array($check_first_date_update_query)) {
						if (( int ) tep_get_question_anwers_count(( int ) $que_id) == 0 || $check_first_date_update['first_answer_date'] == '0000-00-00 00:00:00') {
							tep_db_query("update " . TABLE_QUESTION . " SET first_answer_date=now() WHERE que_id=" . ( int ) $que_id);
						}
					}
					
					// tep_db_query("insert into " . TABLE_QUESTION_ANSWER . "
					// (que_id,ans,date,replay_name,replay_email, modified_by)
					// values ('" . (int)$que_id . "', '" .
					// tep_db_input($anwers) ."', now(),'" .
					// tep_db_input($replay_name) . "','" .
					// tep_db_input($replay_email) . "', '".$login_id."')");
					tep_db_query("insert into " . TABLE_QUESTION_ANSWER . " (que_id,ans,date,replay_name,replay_email, modified_by,ans_orig) values ('" . ( int ) $que_id . "', '" . tep_db_input($anwers) . "', now(),'" . tep_db_input($replay_name) . "','" . tep_db_input($replay_email) . "', '" . $login_id . "', '" . tep_db_input($anwers) . "')");
					// QA save first draft - vincent
					tep_db_query('OPTIMIZE TABLE ' . TABLE_QUESTION . ', ' . TABLE_QUESTION_ANSWER);
					// save operate history - vincent
					tep_add_qa_history(1, $login_id, $que_id, tep_db_insert_id()); // 记录该操作
					                                                            // 1添加回复
					                                                            
					// 更新首页的咨询xml文档
					update_xml_for_index_page_customer_questions();
					// tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS,
					// tep_get_all_get_params(array('action'))));
					// send mali to customer
					// send mail to merchant --scs Bof
					
					// $fromemail = 'service@samszone.com';
					// $fromemail = STORE_OWNER_EMAIL_ADDRESS;
					// $sownername = STORE_OWNER;
					$fromemail = $replay_email;
					$sownername = $replay_name;
					$tablink = '-question-answer';
					$subject = "走四方网提醒您：您的咨询已得到回复！ ";
					
					$findcustomerdata = tep_db_query("select * from " . TABLE_QUESTION . " where que_id = '" . ( int ) $que_id . "'");
					if ($customerdata = tep_db_fetch_array($findcustomerdata)) {
						$c_email = $customerdata["customers_email"];
						$products_id = $customerdata["products_id"];
						$cust_que = $customerdata["question"];
						$cPath = tep_get_products_catagory_id($products_id);
						$c_name = ucfirst(substr($customerdata["customers_name"], 0, 1)) . substr($customerdata["customers_name"], 1, strlen($customerdata["customers_name"]));
						$message = "<table width='100%' border='0' cellpadding='0' cellspacing='0' >" . "<tr>" . "<td valign='top' colspan='2'>Hi, $c_name,</td>" . "</tr>" . "<tr>" . "<td valign='top'><br>Please click <a href=" . HTTP_CATALOG_SERVER . '/' . seo_get_products_path($products_id, true, $tablink) . ">" . HTTP_CATALOG_SERVER . '/' . seo_get_products_path($products_id, true, $tablink) . "</a> to review the answer.</td>" . "</tr>" . "<tr>" . "<td valign='top'><br>Thank you for your inquiry. ( " . $cust_que . " )</td>" . "</tr>" . "<tr>" . "<td valign='top'>" . TEXT_DEFULT_ANSWER_REPLY_FOOT . "</td>" . "</tr>" . "</table>";
						
						// zhh fix and added
						
						$to_name = $c_name . " ";
						$to_email_address = $c_email;
						$email_subject = $subject . " ";
						
						// $email_text = $message;
						
						$from_email_name = $sownername . " ";
						$from_email_address = $fromemail;
						
						if (( int ) $products_id) {
							$product_link_page = '<a href="' . HTTP_CATALOG_SERVER . '/' . seo_get_products_path($products_id, true, $tablink) . '">' . HTTP_CATALOG_SERVER . '/' . seo_get_products_path($products_id, true, $tablink) . '</a>';
						} else {
							$product_link_page = '<a href="' . HTTP_CATALOG_SERVER . '/all_question_answers.php">' . HTTP_CATALOG_SERVER . '/all_question_answers.php</a>';
						}
						
						// howard added new eamil tpl
						$patterns = array();
						$patterns[0] = '{CustomerName}';
						$patterns[1] = '{images}';
						$patterns[2] = '{HTTP_SERVER}';
						$patterns[3] = '{HTTPS_SERVER}';
						$patterns[4] = '{ProductInfoPage}';
						$patterns[5] = '{EMAIL}';
						$patterns[6] = '{CONFORMATION_EMAIL_FOOTER}';
						
						$replacements = array();
						$replacements[0] = $to_name;
						$replacements[1] = HTTP_SERVER . '/email_tpl/images';
						$replacements[2] = HTTP_SERVER;
						$replacements[3] = HTTPS_SERVER;
						$replacements[4] = $product_link_page;
						$replacements[5] = $to_email_address;
						$replacements[6] = db_to_html(nl2br(CONFORMATION_EMAIL_FOOTER));
						
						$email_tpl = file_get_contents(DIR_FS_CATALOG . 'email_tpl/header.tpl.html');
						$email_tpl .= file_get_contents(DIR_FS_CATALOG . 'email_tpl/question_and_answer.tpl.html');
						$email_tpl .= file_get_contents(DIR_FS_CATALOG . 'email_tpl/footer.tpl.html');
						
						$email_text = str_replace($patterns, $replacements, db_to_html($email_tpl));
						
						// howard added new eamil tpl end
						$email_charset = CHARSET;
						// 在生产站下才发邮件给客人(走四方网第一次回复不发邮件给客人，等主管审核才能发！)
						if (0 && defined('IS_LIVE_SITES') && IS_LIVE_SITES === true) {
							tep_mail(iconv(CHARSET, $email_charset . '//IGNORE', $to_name), $to_email_address, iconv(CHARSET, $email_charset . '//IGNORE', $email_subject), iconv(CHARSET, $email_charset . '//IGNORE', $email_text), $from_email_name, $from_email_address, 'true');
						}
					}
					
					// send mail to merchant --scs Bof
					
					tep_redirect(tep_href_link(FILENAME_QUESTION_ANSWERS, "que_id=$que_id&action=view_question" . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : ''), 'NONSSL', false, true, "qanda"));
					exit();
				}
				
				// send mail to cutomer
			}
			
			// send ans from admin eof
			break;
		
		case 'send_to_merchant' :
			
			$customers_name = tep_db_prepare_input($_POST['customers_name']);
			
			$customers_email = tep_db_prepare_input($_POST['customers_email']);
			
			$message = tep_db_prepare_input($_POST['questions']);
			
			$products_id = tep_db_prepare_input($_POST['products_id']);
			$subject = tep_db_prepare_input($_POST['subject']);
			
			// $tablink = '-question-answer';
			
			$fromemail = trim($customers_email);
			
			foreach ( $_POST['merchantemail'] as $value ) {
				
				$merchant_email = trim($value);
				
				if ($merchant_email != '') {
					
					// zhh fix and added
					
					$to_name = ''; // $customers_name;
					$to_email_address = $merchant_email;
					$email_subject = $subject;
					$email_text = $message;
					
					$from_email_name = $customers_name;
					$from_email_address = $fromemail;
					
					tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true');
				}
			}
			
			// $subject="Answer your customer question to make a sale";
			// $subject="Message from samszone.com";
			
			/*
			 * $findmerchatdata = tep_db_query("select
			 * si.stores_email_address,si.stores_name from " .
			 * TABLE_PRODUCTS_TO_STORES . " as pts, " . TABLE_STORES . " as si
			 * where pts.products_id = '".(int)$products_id."' and pts.stores_id
			 * = si.stores_id"); while ($merchant =
			 * tep_db_fetch_array($findmerchatdata)) { $merchant_email =
			 * $merchant["stores_email_address"]; $storesname =
			 * $merchant["stores_name"]; if($merchant_email != '' ){ $message =
			 * 	"<table width='100%' border='0' cellpadding='0' cellspacing='0'
			 * >". "<tr>". "<td valign='top' colspan='2'>Hi, $storesname,</td>".
			 * "</tr>". "<tr>". "<td valign='top'></br>A customer has a question
			 * regarding your
			 * product,&nbsp;<b>".tep_get_products_name($products_id)."</b></td>".
			 * "</tr>". "<tr>". "<td valign='top' >".$questions."</td>".
			 * "</tr>". "<tr>". "<td valign='top'>Please click <a
			 * href=http://www.samszone.com/".seo_get_products_path($products_id,
			 * true,
			 * $tablink).">http://www.samszone.com/".seo_get_products_path($products_id,
			 * true, $tablink)."</a> to review the answer.</td>". "</tr>".
			 * "<tr>". "<td valign='top'></br></br>Thanks</br><a
			 * href='http://www.samszone.com'>www.samszone.com</a></td>".
			 * "</tr>". "</table>";
			 * mail($merchant_email,$subject,$message,$headers); } }
			 */
			
			tep_redirect(tep_href_link(FILENAME_QUESTION_ANSWERS, "que_id=" . $_GET['que_id'] . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '')));
			exit();
			
			break;
	}
}

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<script language="Javascript1.2"><!-- // load htmlarea

// MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 Products Description HTML - Head

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



<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript"
	src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>



<?php
$p = array(
		'/&amp;/',
		'/&quot;/' 
);
$r = array(
		'&',
		'"' 
);
?>

<script language="javascript"><!--
//检查删除按钮是否可用
jQuery(function(){
	jQuery("#TableListQuestionAnswers").click(function(){
		if(jQuery("input[name='que_ids[]']:checked").length > 0){
			jQuery("#DeleteButton").attr('disabled',false);
		}else{
			jQuery("#DeleteButton").attr('disabled',true);
		}
	}
	);
});

//全选
function SelectAll(){
	if(jQuery("input[name='que_ids[]']:checked").length==0){
		jQuery("input[name='que_ids[]']").attr('checked', true);
	}else{
		jQuery("input[name='que_ids[]']").attr('checked', false);
	}
}

//选择前10条
function SelectTop10(){
	jQuery("input[name='que_ids[]']:lt(10)").attr('checked', true);
}

//批量删除
function DeleteList(){
	if(confirm('您真的要删除这些咨询？一旦删除将不可再恢复请谨慎操作！')==true){
		Obj = jQuery("input[name='que_ids[]']:checked");
		id_str = '';
		for(var i=0; i< Obj.length; i++){
			id_str += jQuery(Obj[i]).val()+',';
		}
		if(id_str.length>1){
			var url = "<?php echo preg_replace($p,$r,tep_href_link_noseo('question_answers.php','ajax=true&action=delete_question_confirm')) ?>";
			url += '&que_ids='+id_str;
			ajax_get_submit(url,'','','');
		}
	}
}

//快速删除回答
function fast_del_ans(ans_id, que_id){
	if(ans_id>0 && que_id>0){
		var url = "<?php echo preg_replace($p,$r,tep_href_link_noseo('question_answers.php','ajax=true&action=view_question')) ?>";
		url+= '&deleteansid='+ans_id;
		url+= '&que_id='+que_id;
		var success_msm ="";
		var success_go_to ="";
		var replace_id ="";
		ajax_get_submit(url,success_msm,success_go_to,replace_id);
	}
}
//设置为已通过审核
function set_has_checked(ans_id, que_id){
	if(ans_id>0 && que_id>0){
		var url = "<?php echo preg_replace($p,$r,tep_href_link_noseo('question_answers.php','ajax=true&action=set_has_checked')) ?>";
		url+= '&ans_id='+ans_id;
		url+= '&que_id='+que_id;
		var success_msm ="";
		var success_go_to ="";
		var replace_id ="";
		ajax_get_submit(url,success_msm,success_go_to,replace_id);
	}
}
//显示某个问题的所有答案
function show_this_ans(que_id, Refresh){
	var ans_tr = document.getElementById('ans_tr_'+que_id);
	var ans_div = document.getElementById('ans_div_'+que_id);
	if(ans_tr==null || ans_div==null){ return false;}
	if(ans_tr.style.display!="none" && Refresh!=1 ){
		ans_tr.style.display="none";
	}else{
		ans_tr.style.display="";
		if(ans_div.innerHTML.length<15 || Refresh==1){	//需要查询数据库
			var url = "<?php echo preg_replace($p,$r,tep_href_link_noseo('question_answers_ajax.php','ajax=true')) ?>";
			url+= '&que_id='+que_id;
			var success_msm ="";
			var success_go_to ="";
			var replace_id ="";
			ajax_get_submit(url,success_msm,success_go_to,replace_id);
		}
	}
}

function validEmail(strEmail)
{	
	
	
    if (strEmail.search(/[A-Za-z0-9\._]+\@[A-Za-z0-9\-]+\.[A-Za-z0-9\.]/gi) != -1)
		return true;
    else
        return false; 
}	

function checkForm() {

if(document.product_question_answer_write.replay_name.value == ""){
alert("Please enter the value for your name");
document.product_question_answer_write.replay_name.focus();
return false;
}

if(document.product_question_answer_write.replay_email.value == ""){
alert("Please enter the value for your email");
document.product_question_answer_write.replay_email.focus();
return false;
}
if (!validEmail(document.product_question_answer_write.replay_email.value))
{
		alert("Please enter a Valid login id Address.");
		document.product_question_answer_write.replay_email.focus();
		return false;
}
if(document.product_question_answer_write.anwers.value == ""){
alert("Please write your answers");
document.product_question_answer_write.anwers.focus();
return false;
}
	
return  true;
 }


function checkForm1() {

if(document.product_queston_write.customers_name.value == ""){
alert("Please enter the value for customer name");
document.product_queston_write.customers_name.focus();
return false;
}

if(document.product_queston_write.customers_email.value == ""){
alert("Please enter the value for customer email");
document.product_queston_write.customers_email.focus();
return false;
}
if (!validEmail(document.product_queston_write.customers_email.value))
{
		alert("Please enter a Valid login id Address.");
		document.product_queston_write.customers_email.focus();
		return false;
}
if(document.product_queston_write.questions.value == ""){
alert("Please write your question");
document.product_queston_write.questions.focus();
return false;
}
	
return  true;
 }

//--></script>

<script type="text/javascript">

function submit_check(){
	var class_box = document.getElementById("class_box");
	for(i=0; i<class_box.length; i++){
		class_box.options[i].selected = true;
	}
	
	document.getElementById("edit_quesion").submit();
}

function add_to_class(){
	var all_class_box = document.getElementById("all_class_box");
	var class_box = document.getElementById("class_box");
	var s = class_box.length;
	var ready_add_value = all_class_box.value;
	var ready_add_text = all_class_box.options[all_class_box.selectedIndex].text;
	var add_action = true;
	for(i=0; i<class_box.length; i++){
		class_box.options[i].selected = true;
		if(ready_add_value == class_box.options[i].value){
			add_action = false; 
		}
	}
	if(add_action==true && ready_add_value>0){
		class_box.options[s] = new Option(ready_add_text, ready_add_value);
		class_box.options[s].selected = true;
	}
}

function move_form_categories(){
	var class_box = document.getElementById("class_box");
	for(i=0; i<class_box.length; i++){
		if( class_box.options[i].selected ){
			class_box.remove(i);
			break;
		}
	}

}

</script>


<link rel="stylesheet" type="text/css"
	href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">

<script language="JavaScript"
	src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript">
<!--

//var Qdate_start = new ctlSpiffyCalendarBox("Qdate_start", "goto", "start_date","btnDate1","<?php echo ($start_date); ?>",scBTNMODE_CUSTOMBLUE);
//var Qdate_end = new ctlSpiffyCalendarBox("Qdate_end", "goto", "end_date","btnDate2","<?php echo ($end_date); ?>",scBTNMODE_CUSTOMBLUE);
//var Adate_start = new ctlSpiffyCalendarBox("Adate_start", "goto", "ans_start_date","btnDate3","<?php echo ($ans_start_date); ?>",scBTNMODE_CUSTOMBLUE);
//var Adate_end = new ctlSpiffyCalendarBox("Adate_end", "goto", "ans_end_date","btnDate4","<?php echo ($ans_end_date); ?>",scBTNMODE_CUSTOMBLUE);

//-->
</script>
<div id="spiffycalendar" class="text"></div>


</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0"
	leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">

	<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->



	<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('question_answers');
$list = $listrs->showRemark();
?>
	<table border="0" width="100%" cellspacing="2" cellpadding="2">

		<tr>


			<td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0"
					width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1"
					class="columnLeft">

					<!-- left_navigation //-->

<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>

<!-- left_navigation_eof //-->

				</table></td>

			<!-- body_text //-->

			<td width="100%" valign="top"><table border="0" width="100%"
					cellspacing="0" cellpadding="2">

<?php

if ($_GET['action'] == 'edit_question') { // insert or edit a news item
	
	if (isset($_GET['que_id'])) { // editing exsiting news item
		
		$que_ans_query = tep_db_query("select *  from " . TABLE_QUESTION . " where que_id = '" . $_GET['que_id'] . "'");
		
		$que_ans = tep_db_fetch_array($que_ans_query);
	}
	
	?>

      <tr>

						<td><table border="0" width="100%" cellspacing="0" cellpadding="0">

								<tr>

									<td class="pageHeading">Quesions Answers</td>

									<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>

								</tr>

							</table></td>

					</tr>

					<tr>

						<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

					</tr>

					<tr><?php echo tep_draw_form('edit_quesion', FILENAME_QUESTION_ANSWERS,   isset($_GET['que_id']) ? 'que_id='.$_GET['que_id'].'&action=update_que_ans'.(isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '').(isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') : 'action=insert_stores_deals'.(isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '').(isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : ''), 'post', ' id="edit_quesion" onSubmit="submit_check(); return false;" enctype="multipart/form-data"'); ?>

        <td><table border="0" cellspacing="0" cellpadding="2">


								<tr>

									<td class="main" valign="top"><?php echo '<b>Question:</b>' ?></td>

									<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_textarea_field('question', 'soft', '70', '15', tep_db_prepare_input($que_ans['question'])); ?></td>

								</tr>


								<tr>

									<td class="main" valign="top"><?php echo '<b>Tab tag:</b>' ?></td>

									<td class="main">
			<?php
	$all_tab = array(
			array(
					'id' => 0,
					'text' => 'Not Tab' 
			) 
	);
	$tab_sql = tep_db_query('SELECT * FROM `tour_question_tab` WHERE parent_id ="0" Order By sort_order ASC ');
	while ( $tab_rows = tep_db_fetch_array($tab_sql) ) {
		$all_tab[] = array(
				'id' => $tab_rows['question_tab_id'],
				'text' => $tab_rows['question_tab_name'] 
		);
	}
	
	echo '<div style="float:left;">' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;</div>';
	echo '<div>';
	
	$top_class_tree = $all_tab;
	$all_class_box = '<select name="all_class_box"  id="all_class_box" style="color:#999999" size="10" ondblclick="add_to_class()" >';
	foreach ( ( array ) $top_class_tree as $key ) {
		if (( int ) $key['id']) {
			$top_calss_style = '';
			if (! preg_match('/^&nbsp;/', $key['text'])) {
				// $top_calss_style = ' style="background-color: #E8E8E8;
			// font-weight: bold; padding-top:3px; padding-bottom:3px;" ';
			}
			$all_class_box .= '<option value="' . $key['id'] . '" ' . $top_calss_style . '>' . $key['text'] . '</option>';
		}
	}
	$all_class_box .= '</select>';
	
	$class_sql = tep_db_query('SELECT qt.question_tab_id,qt.question_tab_name FROM `tour_question` q, `tour_question_to_tab` qtt, `tour_question_tab` qt WHERE q.que_id = "' . ( int ) $que_id . '" AND qtt.que_id = q.que_id and qt.question_tab_id = qtt.tour_question_tab_id Group By qtt.tour_question_tab_id  ');
	
	$tmp_str = '';
	while ( $class_rows = tep_db_fetch_array($class_sql) ) {
		$tmp_str .= '<option value="' . $class_rows['question_tab_id'] . '" selected="selected" >' . $class_rows['question_tab_name'] . '</option>';
	}
	$class_selected_box = '<select name="class_box[]" size="10" multiple="multiple" id="class_box">' . $tmp_str . '</select>';
	$html_str = '
  			<table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top" class="infoBoxContent">已选Tab</td>
                <td valign="top" class="infoBoxContent">&nbsp;</td>
                <td valign="top" class="infoBoxContent">所有Tabs</td>
              </tr>
              <tr>
                <td valign="top" class="infoBoxContent">' . $class_selected_box . '</td>
                <td valign="top" class="infoBoxContent">
				<input name="Submit" type="button" title="添加到Tab" onclick="add_to_class()" value=" &lt;&lt; " /><br /><br />
<input type="button" title="撤消Tab" name="Submit" onclick="move_form_categories()" value=" &gt;&gt; " /></td>
                <td valign="top" class="infoBoxContent">' . $all_class_box . '</td>
              </tr>
            </table>
  ';
	echo $html_str;
	echo '</div>';
	
	?>	

			</td>

								</tr>

								<tr>

									<td class="main"><b>Sort Order:</b>&nbsp;</td>

									<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' .tep_draw_input_field('que_sort_order',$que_ans['que_sort_order']); ?></td>

								</tr>
								<tr>

									<td class="main"><b>设置为常见问题:</b>&nbsp;</td>

									<td class="main">
			  <?php
	$set_hit1_checked = false;
	$set_hit0_checked = true;
	if ($que_ans['set_hit'] == "1") {
		$set_hit1_checked = true;
		$set_hit0_checked = false;
	}
	echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;<label>' . tep_draw_radio_field('set_hit', "1", $set_hit1_checked) . ' 是 </label>' . '&nbsp;<label>' . tep_draw_radio_field('set_hit', "0", $set_hit0_checked) . ' 否 </label>';
	?>
			  </td>

								</tr>

							</table></td>

					</tr>

					<tr>

						<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

					</tr>

					<tr>

						<td class="main" align="right">

           <?php
	
	if ($_GET['editfromque'] == 'view') {
		?>
			<input type="hidden" name="editfromque" value="view">
			<?php
		isset($_GET['que_id']) ? $cancel_button = '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, tep_get_all_get_params(array(
				'cPath',
				'replay',
				'action',
				'que_id',
				'editfromque' 
		)) . 'action=view_question&que_id=' . $_GET['que_id']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' : $cancel_button = '';
	} else {
		isset($_GET['que_id']) ? $cancel_button = '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, tep_get_all_get_params(array(
				'cPath',
				'replay',
				'action',
				'que_id' 
		)) . 'que_id=' . $_GET['que_id']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' : $cancel_button = '';
	}
	echo tep_image_submit('button_insert.gif', IMAGE_INSERT) . $cancel_button;
	
	?>
        </td>

						</form>
					</tr>

					<!--  check for answer of the question BOF--->
									  <?php
	$question_query_answer_raw = tep_db_query("select * from " . TABLE_QUESTION_ANSWER . " where que_id = '" . ( int ) $_GET['que_id'] . "' order by date desc");
	while ( $question_ans = tep_db_fetch_array($question_query_answer_raw) ) {
		
		?>
									<tr>

						<td width="100%" valign="top">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="3%">&nbsp;</td>
									<td width="97%">

										<table border="0" width="100%" cellspacing="0" cellpadding="2">

											<tr bgcolor="#DCF4FE">

												<td colspan="2" class="main">
												<?php
		if ($question_ans['has_checked'] == "1") {
			echo '<b style="color:#090">[Has Checked]</b><br />';
		}
		?>
												Re by:&nbsp;
													<?php
		
		$question_ans['replay_name'] = ucfirst(substr($question_ans['replay_name'], 0, 1)) . substr($question_ans['replay_name'], 1, strlen($question_ans['replay_name']));
		
		echo "<b>" . $question_ans['replay_name'] . "</b><br />";
		echo "at:&nbsp;" . sprintf(tep_date_long($question_ans['date']));
		
		?>
												 </td>

											</tr>
											<tr bgcolor="#DCF4FE">

												<td width="95%" valign="top" class="main">
														<?php
		$pet = '/(http:\/\/)*((www|cn)+\.usitrip\.com[\w\/\?\&\.\=%\-]*)/';
		$ans = tep_break_string(tep_output_string_protected($question_ans['ans']), 80, '-<br>');
		$ans = preg_replace($pet, '<a target="_blank" href="http://$2">$1$2</a>', $ans);
		echo nl2br(db_to_html($ans));
		?></td>
												<td width="5%" align="right" valign="bottom" class="main"><?php
		if ($can_check_question_answers === true || $login_groups_id == 1) { // Service
		                                                                 // Team
		                                                                 // (senior)
			echo '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, "que_id=" . $_GET['que_id'] . "&action=update_ans_of_que&updateansid=" . $question_ans['ans_id'] . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . "") . '">' . tep_image_button('button_edit.gif', 'Edit') . '</a>';
			echo '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, "que_id=" . $_GET['que_id'] . "&action=view_question&deleteansid=" . $question_ans['ans_id'] . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . "") . '">' . tep_image_button('button_delete.gif', 'Delete') . '</a>';
		}
		
		?></td>
											</tr>

										</table>
									</td>
								</tr>
							</table>

						</td>

					</tr>  
										
									<?
	}
	?>
														 
									  <!--  check for answer of the question EOF--->

<?php
} else if ($_GET['action'] == 'update_ans_of_que') {
	?>
<tr>

						<td><table border="0" width="100%" cellspacing="0" cellpadding="0">

								<tr>

									<td class="pageHeading">Quesions Answers</td>

									<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>

								</tr>

							</table></td>

					</tr>

					<tr>

						<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

					</tr>

					<tr>
						<td>
	  <?php
	echo tep_draw_form('update_answer_form', FILENAME_QUESTION_ANSWERS, 'action=update_ans_confirm&ans_id=' . ( int ) $_GET['updateansid'] . '&que_id=' . ( int ) $_GET['que_id'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : ''), 'post', '');
	?>
	  	<table width="100%" border="0" cellspacing="2" cellpadding="2">
								<tr>
									<td class="main" width="10%"><strong>Question</strong></td>
									<td class="main"><?php
	$show_question_query_raw = "select question,date,first_answer_date from " . TABLE_QUESTION . " where que_id = '" . ( int ) $_GET['que_id'] . "'";
	$show_question_query = tep_db_query($show_question_query_raw);
	
	while ( $show_question = tep_db_fetch_array($show_question_query) ) {
		echo tep_db_output($show_question['question']);
		
		?></td>
								</tr>
			  <?php if($can_update_question_date){//判断是否可以修改时间?>
			  <tr>
									<td>add date</td>
									<td><input type="text" name="add_date"
										value="<?php echo $show_question['date'];?>" /> 输入格式2012-11-11
										11:11:11</td>
								</tr>
								<tr>
									<td>Answered date</td>
									<td><input type="text" name="answer_date"
										value="<?php echo $show_question['first_answer_date']?>" />输入格式2012-11-11
										11:11:11</td>
								</tr>
			  <?php }?>
			  <tr>
			  <?php } ?>
				<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								</tr>
								<tr>
									<td class="main"><strong>Answers</strong></td>
									<td class="main">
				<?php
	$update_question_query_answer_raw = tep_db_query("select ans, modified_by, has_checked from " . TABLE_QUESTION_ANSWER . " where que_id = '" . ( int ) $_GET['que_id'] . "' and ans_id = '" . tep_db_input($_GET['updateansid']) . "'");
	$update_answer_question = tep_db_fetch_array($update_question_query_answer_raw);
	?>
				   <?php echo tep_draw_textarea_field('update_anwers', 'soft', 100, 10, tep_db_prepare_input($update_answer_question['ans'])); ?>
				   <br /> <label><?php echo tep_draw_checkbox_field('need_send_email_to_service_admin_id',$update_answer_question['modified_by'],true).db_to_html(" 给上个回答的客服发邮件");?></label>&nbsp;&nbsp;
										<label><?php echo tep_draw_checkbox_field('need_send_email_to_customers',"1",false).db_to_html(" 同时发送邮件给客户");?></label>
				   &nbsp;&nbsp;
				   <?php
	if ($can_check_question_answers === true || $login_groups_id == 1) { // Service
	                                                                 // Team
	                                                                 // (senior)
		$has_checked_action = false;
		if ($update_answer_question['has_checked'] == "1") {
			$has_checked_action = true;
		}
		?>
				   <label><?php echo tep_draw_checkbox_field('has_checked',"1",$has_checked_action).db_to_html(" 已通过审核");?></label>
				   <?php
	}
	?>
				</td>
								</tr>
								<tr>
									<td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
								</tr>
								<tr>
									<td class="main"></td>
									<td class="main">
				<?php
	
echo tep_image_submit('button_update.gif', 'Update');
	
	?>
				<?php echo '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'action=view_question&'.tep_get_all_get_params(array('cPath','updateansid','replay','action'))) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
								</tr>
							</table>
							</form>
						</td>
					</tr>
	  
<?php
} else if ($_GET['action'] == 'view_question') {
	
	?>
		<tr>

						<td><table border="0" width="100%" cellspacing="0" cellpadding="0">

								<tr>

									<td class="pageHeading">Quesions Answers</td>

									<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>

								</tr>

							</table></td>

					</tr>



					<tr>

						<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

					</tr>
					<tr>
					
					
					<tr>

						<td class="main"><b><?php
	$show_question_query_raw = "select question, products_id,customers_name  from " . TABLE_QUESTION . " where que_id = '" . ( int ) $_GET['que_id'] . "'";
	$show_question_query = tep_db_query($show_question_query_raw);
	
	if ($show_question = tep_db_fetch_array($show_question_query)) {
		
		if (isset($_GET['replay']) && $_GET['replay'] == 'ans') {
			echo 'Q. ' . tep_db_output($show_question['question']) . '<br>';
		}
		$tablink = '-question-answer';
		echo "<a target='_blank' href=" . HTTP_CATALOG_SERVER . "/" . seo_get_products_path($show_question['products_id'], true, $tablink) . ">" . tep_get_products_name($show_question['products_id']) . ' [' . tep_get_products_model($show_question['products_id']) . '] [' . tep_get_provider_tourcode($show_question['products_id']) . ']</a>';
	}
	
	?></b></td>

					</tr>

					<tr>

						<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>

					</tr>

					<td>
						<!--  view body text start  -->
		
		
			<?php
	
	if (isset($_GET['replay']) && $_GET['replay'] == 'ans') {
		?>
			
			
			<?php
		echo tep_draw_form('product_question_answer_write', FILENAME_QUESTION_ANSWERS, 'action=process&que_id=' . $que_id . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : ''), 'post', 'onSubmit="return checkForm();"');
		?>
			<table border="0" width="100%" cellspacing="2" cellpadding="2"
							class="unnamed1">
						
                          <?php
		if ($success == true) {
			?>
							<tr>

								<td colspan="3" class="main"><b>Your replay send successfully.</b></td>

							</tr>
							<?php
		} else {
			
			?>
						 
						  <tr>

								<td width="23%" class="main"><b>Your Name:</b>&nbsp;<font
									color="#FF0000">(required)</font></td>

								<td colspan="2" class="main"><?php echo tep_draw_input_field('replay_name',STORE_OWNER); ?></td>

							</tr>

							<tr>

								<td class="main"><b>Your E-mail:</b>&nbsp;<font color="#FF0000">(required)</font></td>

								<td colspan="2" class="main"><?php echo tep_draw_input_field('replay_email',STORE_OWNER_EMAIL_ADDRESS); ?></td>

							</tr>


							<tr>

								<td class="main"><b>Your Answers:</b>&nbsp;<font color="#FF0000">(required)</font></td>

								<td width="63%" class="main"><?php
			$default_comment_added = '<span style="color:red">(请直接填写您回答的内容)</span><br>尊敬的 ' . ucfirst($show_question['customers_name']) . ': ' . " \n 您好！ \n";
			$default_comment_added2 = TEXT_DEFULT_ANSWER_REPLY_CONTENT;
			echo nl2br($default_comment_added);
			echo tep_draw_textarea_field('anwers', 'soft', 45, 10, '');
			echo nl2br($default_comment_added2);
			?></td>

								<td width="14%" class="main">&nbsp;</td>
							</tr>

                        
					
                          <?php // BOF: WebMakers.com Added: Split to two rows ?>

                          <tr>

								<td class="main">&nbsp;</td>

								<td class="main"><input type="submit" name="submit"
									value="submit"></td>
								<td class="main">&nbsp;</td>
							</tr>
							<tr>

								<td colspan="3">
									<table border="0" width="100%" cellspacing="0" cellpadding="2">

										<tr>

											<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

											<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, tep_get_all_get_params(array('cPath','replay','action','dealscat'))) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>


											<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

										</tr>

									</table>

								</td>

							</tr>
						  
							<?php
		}
		?>
                          <?php // BOF: WebMakers.com Added: Split to two rows ?>

                        </table>
						</form>
			
			<?php }else{ ?>
			<table border="0" width="100%" cellspacing="0" cellpadding="2"
							class="unnamed1">
				<?php
		
		$question_query_raw = "select * from " . TABLE_QUESTION . " where que_id = '" . ( int ) $_GET['que_id'] . "'";
		$question_query = tep_db_query($question_query_raw);
		
		while ( $question = tep_db_fetch_array($question_query) ) {
			
			?>
				
									<tr>

								<td width="100%" valign="top">

									<table border="0" width="100%" cellspacing="0" cellpadding="2">
										<tr bgcolor="#99D2ED">
											<td colspan="2" class="main">by:&nbsp;
													<?php
			
			$question['customers_name'] = ucfirst(substr($question['customers_name'], 0, 1)) . substr($question['customers_name'], 1, strlen($question['customers_name']));
			echo "<b>" . $question['customers_name'] . "</b></br>";
			echo "at:&nbsp;" . sprintf(tep_date_long($question['date']));
			
			?>
												 </td>

										</tr>
										<tr bgcolor="#99D2ED">

											<td width="95%" valign="top" class="main"><?php echo tep_db_output($question['question']); ?></td>
											<td width="5%" align="right" valign="bottom" class="main">
												   <?php
			if ($can_check_question_answers === true || $login_groups_id == 1) { // Service
			                                                                 // Team
			                                                                 // (senior)
				echo '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, "que_id=" . $question['que_id'] . "&editfromque=view&action=edit_question" . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . "") . '">' . tep_image_button('button_edit.gif', 'Edit') . '</a>';
			}
			?>
												   <?php echo '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, tep_get_all_get_params().'replay=ans','NONSSL',true,true,"qanda") . '">' . tep_image_button('button_reply.gif', 'Reply') . '</a>'; ?></td>
										</tr>
										<!--
										  <tr> 
				
											<td colspan="2"><hr size="1"></td>
				
										  </tr>
										  -->
									</table>

								</td>

							</tr>



							<!--  check for answer of the question BOF--->
									  <?php
			$question_query_answer_raw = tep_db_query("select * from " . TABLE_QUESTION_ANSWER . " where que_id = '" . ( int ) $question['que_id'] . "' order by date desc");
			while ( $question_ans = tep_db_fetch_array($question_query_answer_raw) ) {
				
				?>
									<tr>

								<td width="100%" valign="top"><table width="100%" border="0"
										cellspacing="0" cellpadding="0">
										<tr>
											<td width="3%">&nbsp;</td>
											<td width="97%"><table border="0" width="100%"
													cellspacing="0" cellpadding="2">



													<tr bgcolor="#DCF4FE">

														<td colspan="2" class="main">
												  <?php
				if ($question_ans['has_checked'] == "1") {
					echo '<b style="color:#090">[Has Checked]</b><br />';
				}
				?>
												  Re by:&nbsp;
													<?php
				
				$question_ans['replay_name'] = ucfirst(substr($question_ans['replay_name'], 0, 1)) . substr($question_ans['replay_name'], 1, strlen($question_ans['replay_name']));
				
				echo "<b>" . $question_ans['replay_name'] . "</b></br>";
				echo "at:&nbsp;" . sprintf(tep_date_long($question_ans['date']));
				
				?>
												 </td>

													</tr>
													<tr bgcolor="#DCF4FE">

														<td width="95%" valign="top" class="main">
													<?php
				$pet = '/(http:\/\/)*((www|cn)+\.usitrip\.com[\w\/\?\&\.\=%\-]*)/';
				$ans = (tep_db_output($question_ans['ans']));
				$ans = preg_replace($pet, '<a target="_blank" href="http://$2">$1$2</a>', $ans);
				echo nl2br(db_to_html($ans));
				?></td>
														<td width="5%" align="right" valign="bottom" class="main">
												  <?php
				if ($can_check_question_answers === true || $login_groups_id == 1) { // Service
				                                                                 // Team
				                                                                 // (senior)
					echo '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, "que_id=" . $question['que_id'] . "&action=update_ans_of_que&updateansid=" . $question_ans['ans_id'] . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . "") . '">' . tep_image_button('button_edit.gif', 'Update') . '</a>';
					echo '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, "que_id=" . $question['que_id'] . "&action=view_question&deleteansid=" . $question_ans['ans_id'] . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . "") . '">' . tep_image_button('button_delete.gif', 'Delete') . '</a>';
				}
				
				?></td>
													</tr>
													<!--
										  <tr> 
				
											<td colspan="2"><hr size="1"></td>
				
										  </tr>
										  -->
												</table></td>
										</tr>
									</table></td>

							</tr>  
										
									<?
			}
			?>
														 
									  <!--  check for answer of the question EOF--->
									  
											 
											  
											  
						<?php
		} // end of while loop
		?>
						
						
			</table>
						<table border="0" width="100%" cellspacing="0" cellpadding="2">

							<tr>

								<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

								<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, tep_get_all_get_params(array('dealscat','replay','action','deleteansid'))) . '">' . tep_image_button('button_back.gif', 'Back') . '</a>'; ?></td>

								<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

							</tr>

						</table>
						
				<?php }?>		

		<!-- view body text end -->


					</td>

					</tr>

<?php

} else if ($_GET['action'] == 'send_merchant_question') {
	
	?>

 	<tr>
						<td>

							<table border="0" width="100%" cellspacing="0" cellpadding="0">

								<tr>
									<td colspan="2" class="pageHeading">Send message to merchant</td>
								</tr>

								<tr>
									<td colspan="2" class="main"></br>Merchant information</td>
								</tr>
								<tr class="dataTableHeadingRow">
									<td class="dataTableHeadingContent">Stores Name</td>
									<td class="dataTableHeadingContent">Contact email</td>
								</tr>
			<?php
	// find data BOF
	$question_query_raw = "select * from " . TABLE_QUESTION . "  where que_id =" . $_GET['que_id'] . "";
	$question_query = tep_db_query($question_query_raw);
	$question = tep_db_fetch_array($question_query);
	
	$products_id = ( int ) $question["products_id"];
	
	// find stores contact owner eof
	$findmerchatdata = tep_db_query("select si.stores_email_address,si.stores_name,si.stores_id from " . TABLE_PRODUCTS_TO_STORES . " as pts, " . TABLE_STORES . " as si where pts.products_id = '" . ( int ) $products_id . "' and pts.stores_id = si.stores_id");
	$storecount = tep_db_num_rows($findmerchatdata);
	
	if (( int ) $storecount == 0) {
		echo "<tr class='dataTableRow'><td class='dataTableContent' colspan='2'>No store offer this products</td></tr>";
	} else {
		
		while ( $merchant = tep_db_fetch_array($findmerchatdata) ) {
			$merchant_email = $merchant["stores_email_address"];
			$storesname = $merchant["stores_name"];
			$stores_id = $merchant["stores_id"];
			?>
				<tr class="dataTableRow">
									<td width="24%" class="dataTableContent"><?php echo $storesname;?></td>
									<td width="76%" class="dataTableContent"><?php if($merchant_email == ''){echo 'Contact email not available';}else{echo $merchant_email;}?></td>
								</tr>
				<?
		} // end of while loop
		?>
				<!-- send mail to merchant form bof-->
				
				<?php
		echo tep_draw_form('product_queston_write', FILENAME_QUESTION_ANSWERS, 'action=send_to_merchant&que_id=' . $que_id . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : ''), 'post', 'onSubmit="return checkForm1();"');
		?>
				<table border="0" width="100%" cellspacing="0" cellpadding="0">


									<tr>

										<td></br>
											<table width="100%" border="0" cellspacing="0"
												cellpadding="2">


												<tr>

													<td align="center" valign="top"><table border="0"
															width="100%" cellspacing="0" cellpadding="2">

															<tr>

																<td></br>
																	<table border="0" width="100%" cellspacing="2"
																		cellpadding="2" class="unnamed1">

																		<tr>

																			<td width="25%" class="main"><b>Your Name:</b>&nbsp;<font
																				color="#FF0000">(required)</font></td>

																			<td width="75%" class="main"><?php echo tep_draw_input_field('customers_name',STORE_OWNER); ?></td>

																		</tr>

																		<tr>

																			<td width="25%" class="main"><b>Subject:</b>&nbsp;</td>

																			<td width="75%" class="main"><?php echo tep_draw_input_field('subject','Answer your customer question to make a sale'); ?></td>

																		</tr>

																		<tr>

																			<td class="main"><b>Your E-mail:</b>&nbsp;<font
																				color="#FF0000">(required)</font></td>

																			<td class="main"><?php echo tep_draw_input_field('customers_email',STORE_OWNER_EMAIL_ADDRESS); ?></td>

																		</tr>
												  
												  <?php
		// find data BOF
		$question_query_raw = "select * from " . TABLE_QUESTION . "  where que_id =" . $_GET['que_id'] . "";
		$question_query = tep_db_query($question_query_raw);
		$question = tep_db_fetch_array($question_query);
		$questionsasked = tep_db_output($question['question']);
		$products_id = ( int ) $question["products_id"];
		$tablink = '-question-answer';
		// find stores contact owner eof
		$findmerchatdata = tep_db_query("select si.stores_email_address,si.stores_name,si.stores_id from " . TABLE_PRODUCTS_TO_STORES . " as pts, " . TABLE_STORES . " as si where pts.products_id = '" . ( int ) $products_id . "' and pts.stores_id = si.stores_id");
		$storecount = tep_db_num_rows($findmerchatdata);
		
		if (( int ) $storecount == 0) {
			echo "<tr class='dataTableRow'><td class='dataTableContent' colspan='2'>No store offer this products</td></tr>";
		} else {
			
			while ( $merchant = tep_db_fetch_array($findmerchatdata) ) {
				$merchant_email = $merchant["stores_email_address"];
				$storesname = $merchant["stores_name"];
				$stores_id = $merchant["stores_id"];
				?>
														<tr>

																			<td class="main"><b><?PHP echo $storesname;?> E-mail:</b>&nbsp;</td>

																			<td class="main"><input type="text"
																				name="merchantemail[]"
																				value="<?php echo $merchant_email;?>"> &nbsp;<?php if((int)$storecount > 1) { echo "Leave merchant email blank if don't want to send mail.";} ?>
															
															</td>

																		</tr>
														<?
			} // end of while loop
		}
		?>
													
						
												 
												  <tr>

																			<td class="main"><b>Your Message:</b>&nbsp;<font
																				color="#FF0000">(required)</font></td>

																			<td class="main">   
												
													
													<?php
		if ($storecount == 1) {
			$addheadin = $storesname . ',';
		} else {
			$addheadin = '';
		}
		$message = "<table width='100%' border='0' cellpadding='0' cellspacing='0' >" . "<tr >" . "<td valign='top' class='main' colspan='2'>Hi, " . $addheadin . "</td>" . "</tr>" . "<tr>" . "<td valign='top' class='main'></br>A customer has a question regarding your product,&nbsp;<b>" . tep_get_products_name($products_id) . "</b></td>" . "</tr>" . "<tr>" . "<td valign='top' class='main' >The question is: \"" . $questionsasked . "\"</td>" . "</tr>" . "<tr>" . "<td valign='top' class='main'>Please click <a href=http://www.samszone.com/" . seo_get_products_path($products_id, true, $tablink) . ">http://www.samszone.com/" . seo_get_products_path($products_id, true, $tablink) . "</a> to review the answer.</td>" . "</tr>" . "<tr>" . "<td valign='top' class='main'></br></br>Thanks</br><a href='http://www.samszone.com'>www.samszone.com</a></td>" . "</tr>" . "</table>";
		
		// echo tep_draw_textarea_field('questions','soft', 60, 15, $message);		?>
													<textarea name="questions" rows="10" cols="55"><?php echo $message; ?> </textarea>
																			</td>

																		</tr>
																		<SCRIPT language=JavaScript1.2 defer>

             var config = new Object();  // create new config object

             config.width = "505px";

             config.height = "240px";

             config.bodyStyle = 'background-color: White; font-family: "Verdana"; color: Black; font-size: 7pt;';

             config.debug = 0;

          
           editor_generate('questions',config);

     		 </SCRIPT>



																		<tr>
																			<td>&nbsp;</td>
																			<td><input type="hidden" name="products_id"
																				value="<?php echo $products_id;?>"> <input
																				type="submit" name="submit" value="Send"></td>

																		</tr>
						
												  <?php // BOF: WebMakers.com Added: Split to two rows ?>
						
												</table></td>
														
														</table></td>
											
											</table></td>

									</tr>

								</table>
								</form>



								<!-- send mail to merchant form eof -->
				
				
				<?php
	
} // end of if loop
	        
	// find stores contact owner eof
	        // find data EOF
	?>
			
			<tr>
									<td colspan="2"><table border="0" width="100%" cellspacing="0"
											cellpadding="2">

											<tr>

												<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

												<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, tep_get_all_get_params(array('cPath','replay','action'))) . '">' . tep_image_button('button_back.gif', 'Back') . '</a>'; ?></td>

												<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

											</tr>

										</table></td>

								</tr>


							</table>
						</td>
					</tr>	

<?php
} else {
	
	?>

      <tr>

						<td>

							<table border="0" width="100%" cellspacing="0" cellpadding="0">

								<tr>
									<td colspan="2" class="main" nowrap align="right"><?php
	$all_products_statuses = array(
			array(
					'id' => '',
					'text' => 'All Tours' 
			) 
	);
	$all_products_query = tep_db_query("select p.products_id, pd.products_name, p.products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . ( int ) $languages_id . "' and pd.products_name!='' order by products_name");
	while ( $all_products = tep_db_fetch_array($all_products_query) ) {
		
		$all_products_statuses[] = array(
				'id' => $all_products['products_id'],
				'text' => $all_products['products_name'] 
		);
	}
	
	$all_tab = array(
			array(
					'id' => 0,
					'text' => 'All Tab' 
			) 
	);
	
	$tab_sql = tep_db_query('SELECT * FROM `tour_question_tab` WHERE parent_id ="0" Order By sort_order ASC ');
	while ( $tab_rows = tep_db_fetch_array($tab_sql) ) {
		$all_tab[] = array(
				'id' => $tab_rows['question_tab_id'],
				'text' => $tab_rows['question_tab_name'] 
		);
	}
	?>
				<?php
	
	echo tep_draw_form('goto', FILENAME_QUESTION_ANSWERS, '', 'get');
	echo '<table border="0" cellspacing="0" cellpadding="0">';
	echo '<tr><td class="infoBoxContent">Go to:&nbsp;&nbsp;</td><td class="infoBoxContent">' . tep_draw_pull_down_menu('pID', $all_products_statuses, $_GET['pID'], 'onChange="this.form.submit();"') . '</td></tr>';
	echo '<tr><td class="infoBoxContent">Tab tag:&nbsp;&nbsp;</td><td class="infoBoxContent">' . tep_draw_pull_down_menu('question_tab_id', $all_tab, $_GET['question_tab_id'], 'onChange="this.form.submit();"') . '</td></tr>';
	
	echo '<tr><td class="infoBoxContent">Questions date:&nbsp;&nbsp;</td><td class="infoBoxContent">
				<table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td nowrap class="main">&nbsp;
						  ' . tep_draw_input_field('start_date', $start_date, ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"') . '
						  <script language="javascript">//Qdate_start.writeControl(); Qdate_start.dateFormat="yyyy-MM-dd";</script></td>
                          <td class="main">&nbsp;至&nbsp;</td>
                          <td nowrap class="main">' . tep_draw_input_field('end_date', $end_date, ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"') . '<script language="javascript">//Qdate_end.writeControl(); Qdate_end.dateFormat="yyyy-MM-dd";</script></td>
                        </tr>
						
                      </table>
				</td></tr>
				<tr><td class="infoBoxContent">Answered date:&nbsp;&nbsp;</td><td class="infoBoxContent">
				<table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td nowrap class="main">&nbsp;
						  ' . tep_draw_input_field('ans_start_date', $ans_start_date, ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"') . '
						  <script language="javascript">//Adate_start.writeControl(); Adate_start.dateFormat="yyyy-MM-dd";</script></td>
                          <td class="main">&nbsp;至&nbsp;</td>
                          <td nowrap class="main"> ' . tep_draw_input_field('ans_end_date', $ans_end_date, ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"') . '<script language="javascript">//Adate_end.writeControl(); Adate_end.dateFormat="yyyy-MM-dd";</script></td>
                        </tr>
						
                      </table>
				</td></tr>
				<tr>
						<td class="infoBoxContent">
						Modified By:
						</td>
						<td class="infoBoxContent">
						' . tep_draw_input_field('adminuser', $_GET['adminuser'], '', '') . '
						</td>
						</tr>
				<tr>
						<td class="infoBoxContent">
						&nbsp;
						</td>
						<td class="infoBoxContent"><input type="button" name="Submit" value="Submit" onClick="this.form.submit();">
						<input type="button"  value="Clear Repeated Question" onclick="javascript:if(confirm(\'' . db_to_html("你确定要要清除重复内容而且没有被回复的问题吗?该操作无法恢复,请慎重选择") . '\'))location=\'' . tep_href_link('question_clear.php') . '\';">
						</td>
						</tr>
				';
	echo '<tr><td>&nbsp;</td><td id="Statistical_Report">&nbsp;</td></tr>';
	echo '</table>';
	echo '</form>';
	?>
			 </td>
								</tr>
								<tr>

									<td class="pageHeading">Questions Answers</td>

									<td align="right"><?php // echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?>
			    <?php
	
	$categories_array = array(
			array(
					'id' => '',
					'text' => TEXT_NONE 
			) 
	);
	$categories_query = tep_db_query("select cd.categories_id, cd.categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " as cd , " . TABLE_CATEGORIES . " as c where cd.categories_id = c.categories_id   AND c.parent_id = '0' order by categories_name");
	
	while ( $categories = tep_db_fetch_array($categories_query) ) {
		
		$categories_array[] = array(
				'id' => $categories['categories_id'],
				
				'text' => $categories['categories_name'] 
		);
	}
	
	/*
	 * if (isset($_GET['dealscat']) && $_GET['dealscat'] != '' ){
	 * $current_category_id = $_GET['dealscat']; } echo tep_draw_form('goto',
	 * FILENAME_QUESTION_ANSWERS, '', 'get'); echo HEADING_TITLE_GOTO . ' ' .
	 * tep_draw_pull_down_menu('dealscat', $categories_array,
	 * $current_category_id, 'onChange="this.form.submit();"'); echo '</form>';
	 */
	?>
			  
			  </td>

								</tr>

							</table>

						</td>

					</tr>

					<tr>

						<td><table border="0" width="100%" cellspacing="0" cellpadding="0">

								<tr>

									<td valign="top"><table border="0" width="100%" cellspacing="0"
											cellpadding="2">

											<tr>

												<td id="TableListQuestionAnswers" valign="top">
													<div>
														<button type="button" onClick="SelectAll()">全选</button>
														<button type="button" onClick="SelectTop10()">选择前10条</button>
			<?php
	if ($can_check_question_answers === true || $login_groups_id == 1) { // Service
	                                                                 // Team
	                                                                 // (senior)
		?>
				<button id="DeleteButton" type="button" onClick="DeleteList()"
															disabled>删除</button>
			<?php
	} else {
		?>
			<button id="DeleteButton" type="button"
															onClick="alert('您所在的组没有删除的权限！请向您的上级寻求帮助！')" disabled>删除</button>
			<?php
	}
	?>
			</div>
													<table border="0" width="100%" cellspacing="0"
														cellpadding="2">

														<tr class="dataTableHeadingRow">

															<td class="dataTableHeadingContent">&nbsp;</td>
															<td class="dataTableHeadingContent">Questions</td>
															<td class="dataTableHeadingContent">top</td>
															<td class="dataTableHeadingContent" nowrap><?php echo TEXT_HEADING_TOUR_CODE; ?><br><?php echo "<a  href=question_answers.php?sortorder=tourcode&".tep_get_all_get_params(array('que_id','cPath','selected_box','sortorder','page'))."><b>".Asc."</b></a>"; ?>&nbsp;&nbsp;<?php echo "<a  href=question_answers.php?sortorder=tourcode-desc&".tep_get_all_get_params(array('que_id','cPath','selected_box','sortorder','page'))."><b>".Desc."</b></a>"; ?></td>

															<td class="dataTableHeadingContent" nowrap><?php echo 'Provider Code'; ?><br><?php echo "<a  href=question_answers.php?sortorder=providercode&".tep_get_all_get_params(array('que_id','cPath','selected_box','sortorder','page'))."><b>".Asc."</b></a>"; ?>&nbsp;&nbsp;<?php echo "<a  href=question_answers.php?sortorder=providercode-desc&".tep_get_all_get_params(array('que_id','cPath','selected_box','sortorder','page'))."><b>".Desc."</b></a>"; ?></td>

															<td class="dataTableHeadingContent" width="10%"
																align="center" nowrap>Sort Order<br><?php echo "<a  href=question_answers.php?sortorder=sort-order&".tep_get_all_get_params(array('que_id','cPath','selected_box','sortorder','page'))."><b>".Asc."</b></a>"; ?>&nbsp;&nbsp;<?php echo "<a  href=question_answers.php?sortorder=sort-order-desc&".tep_get_all_get_params(array('que_id','cPath','selected_box','sortorder','page'))."><b>".Desc."</b></a>"; ?></td>


															<td class="dataTableHeadingContent">Customers email</td>

															<td class="dataTableHeadingContent" width="10%"
																align="center">Date<br><?php echo "<a  href=question_answers.php?sortorder=date&".tep_get_all_get_params(array('que_id','cPath','selected_box','sortorder','page'))."><b>".Asc."</b></a>"; ?>&nbsp;&nbsp;<?php echo "<a  href=question_answers.php?sortorder=date-desc&".tep_get_all_get_params(array('que_id','cPath','selected_box','sortorder','page'))."><b>".Desc."</b></a>"; ?></td>

															<td class="dataTableHeadingContent" align="left"><?php echo TXT_MODIFIED_BY;?><br></td>

															<td class="dataTableHeadingContent" width="5%"
																align="center">Answered<br><?php echo "<a  href=question_answers.php?sortorder=ans&".tep_get_all_get_params(array('que_id','cPath','selected_box','sortorder','page'))."><b>".Asc."</b></a>"; ?>&nbsp;&nbsp;<?php echo "<a  href=question_answers.php?sortorder=ans-desc&".tep_get_all_get_params(array('que_id','cPath','selected_box','sortorder','page'))."><b>".Desc."</b></a>"; ?></td>

															<!--<td class="dataTableHeadingContent" align="center"><?php //echo INDEX_FEATURE_STATUS; ?></td>-->

															<td class="dataTableHeadingContent" width="5%"
																align="right">Action</td>

														</tr>
<?php
	// check for question answerd or not BOF
	$question_query_check_raw = "select que_id, que_replied from " . TABLE_QUESTION . " where que_replied < 1 ";
	
	$question_query_check = tep_db_query($question_query_check_raw);
	while ( $question_query = tep_db_fetch_array($question_query_check) ) {
		$temp_que_id = ( int ) $question_query['que_id'];
		// $que_replied = $question_query['que_replied'];
		if ((( int ) tep_get_question_anwers_count($temp_que_id) > 0) && (( int ) $question_query['que_replied'] == 0)) {
			tep_db_query("update " . TABLE_QUESTION . " set que_replied = '1' where que_id = '" . $temp_que_id . "'");
			$num_unanswered_questions = $num_unanswered_questions - 1;
			// 更新首页的咨询xml文档
			update_xml_for_index_page_customer_questions();
		}
	}
	
	// check for question answerd or not EOF
	switch (trim($_GET['sortorder'])) {
		
		case "ans" :
			// $order = " q.que_replied ";
			$order = " q.first_answer_date ASC";
			break;
		case "ans-desc" :
			// $order = " q.que_replied DESC";
			$order = " q.first_answer_date DESC";
			
			break;
		case " q.date" :
			$order = " q.date ";
			break;
		case "date-desc" :
			$order = " q.date DESC";
			break;
		case "tourcode" :
			$order = " p.products_model ";
			break;
		case "tourcode-desc" :
			$order = " p.products_model DESC";
			break;
		case "providercode" :
			$order = " p.provider_tour_code";
			break;
		case "providercode-desc" :
			$order = " p.provider_tour_code DESC";
			break;
		case "sort-order" :
			$order = "q.que_sort_order";
			break;
		case "sort-order-desc" :
			$order = "q.que_sort_order DESC";
			break;
		default :
			if ($num_unanswered_questions > 0) {
				$order = " q.first_answer_date ASC";
			} else {
				$order = " q.que_id DESC ";
			}
			// 如果有24小时未回复的帖子，则把未回复的放到前面来。
			$delay_sql = tep_db_query('SELECT que_id FROM `tour_question` WHERE first_answer_date="0000-00-00 00:00:00" AND date <= DATE_SUB(NOW(), INTERVAL 1 DAY )  limit 1');
			$delay_row = tep_db_fetch_array($delay_sql);
			if (( int ) $delay_row['que_id']) {
				$order = " q.first_answer_date ASC";
			}
			
			break;
	}
	
	// echo $order;
	
	$table_exc = TABLE_QUESTION . " as q left join " . TABLE_PRODUCTS . ' as p on q.products_id=p.products_id ';
	$where_exc = ' where 1 ';
	$off_tong_ji = false;
	$group_by = ' group by q.que_id ';
	if (tep_not_null($_GET['start_date'])) {
		$where_exc .= ' AND q.date >="' . $_GET['start_date'] . ' 00:00:00" ';
	}
	if (tep_not_null($_GET['end_date'])) {
		$where_exc .= ' AND q.date <="' . $_GET['end_date'] . ' 23:59:59" ';
	}
	if (( int ) $_GET['question_tab_id']) {
		$off_tong_ji = true;
		$table_exc .= ', ' . TABLE_QUESTION_TO_TAB . ' as qtt ';
		$where_exc .= ' AND qtt.que_id=q.que_id AND qtt.tour_question_tab_id = "' . ( int ) $_GET['question_tab_id'] . '" ';
	}
	if (( int ) $_GET['pID']) {
		$off_tong_ji = true;
		$where_exc .= ' AND q.products_id="' . ( int ) $_GET['pID'] . '" ';
	}
	if (tep_not_null($_GET['adminuser']) || tep_not_null($_GET['ans_start_date']) || tep_not_null($_GET['ans_end_date'])) {
		$table_exc .= ', ' . TABLE_QUESTION_ANSWER . ' as qa left join ' . TABLE_ADMIN . ' a on qa.modified_by = a.admin_id ';
		$where_exc .= ' AND qa.que_id=q.que_id ';
	}
	if (tep_not_null($_GET['adminuser'])) {
		$_GET['adminuser'] = addslashes($_GET['adminuser']);
		$where_exc .= " AND (a.admin_firstname like '%{$_GET['adminuser']}%' or a.admin_lastname like '%{$_GET['adminuser']}%') ";
	}
	if (tep_not_null($_GET['ans_start_date']) || tep_not_null($_GET['ans_end_date'])) {
		$off_tong_ji = true;
		if (tep_not_null($_GET['ans_start_date'])) {
			$where_exc .= ' AND qa.date >="' . $_GET['ans_start_date'] . ' 00:00:00" ';
		}
		if (tep_not_null($_GET['ans_end_date'])) {
			$where_exc .= ' AND qa.date <="' . $_GET['ans_end_date'] . ' 23:59:59" ';
		}
	}
	$rows = 0;
	$clunms = " q.que_id,q.is_top, q.replay_has_checked, q.question, q.customers_email, q.date, q.first_answer_date, q.products_id, q.que_sort_order, p.products_model, p.provider_tour_code ";
	// 问题列表
	$question_query_raw = "select {$clunms}  from {$table_exc} {$where_exc} $group_by order by $order ";
	// echo $question_query_raw;//exit;
	// $question_split = new splitPageResults($question_query_raw,
	// MAX_DISPLAY_NEW_REVIEWS);
	$question_answers_query_numrows = 0;
	define('MAX_DISPLAY_NEW_QUEASION', '50');
	$question_split = new splitPageResults($_GET['page'], MAX_DISPLAY_NEW_QUEASION, $question_query_raw, $question_answers_query_numrows);
	
	// if ($question_split->number_of_rows > 0) {
	
	// $question_query = tep_db_query($question_split->sql_query);
	
	$question_query = tep_db_query($question_query_raw);
	while ( $question = tep_db_fetch_array($question_query) ) {
		if (! tep_not_null($ans_start_date) && ! tep_not_null($ans_end_date)) {
			$question['question_substr'] = mb_substr($question['question'], 0, 43, CHARSET);
		} else {
			$question['question_substr'] = $question['question'];
		}
		$products_id = $question["products_id"];
		$tablink = '-question-answer';
		$cPath = tep_get_products_catagory_id($products_id);
		
		$rows ++;
		
		if (((! $_GET['que_id']) || ($_GET['que_id'] == $question['que_id'])) && (! $selected_item) && (substr($_GET['action'], 0, 4) != 'new_')) {
			
			$selected_item = $question;
		}
		
		if ((is_array($selected_item)) && ($question['que_id'] == $selected_item['que_id'])) {
			
			echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="show_this_ans(' . $question['que_id'] . ')">' . "\n";
		} else {
			
			echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="show_this_ans(' . $question['que_id'] . '); this.className=\'dataTableRowSelected\'">' . "\n";
		}
		
		?>

			   
			   <td class="dataTableContent"><input name="que_ids[]"
															type="checkbox" value="<?= $question['que_id']?>"></td>
														<td class="dataTableContent">
			   <?php
		if ($question['replay_has_checked'] == "1") {
			echo '<b style="color:#090">[Has Checked]</b>';
		}
		?>
			   <?php echo "<a target='_blank' href=".HTTP_CATALOG_SERVER."/".seo_get_products_path($products_id, true, $tablink)." title='".tep_db_output($question['question'])."'>".tep_db_output($question['question_substr'])."</a>"; ?></td>
														<td class="dataTableContent" nowrap>
														<?php if($question['is_top'])echo '<font color="red">是</font>';?>
														</td>
														<td class="dataTableContent" nowrap>
				<?php
		if (( int ) $question['products_id']) {
			echo '<a target="_blank" href="' . HTTP_CATALOG_SERVER . '/' . seo_get_products_path($products_id, true, $tablink) . '">' . $question['products_model'] . '</a>';
		} else {
			echo '全站咨询';
		}
		?>
				</td>

														<td class="dataTableContent" nowrap><?php echo $question['provider_tour_code']; ?></td>

														<td class="dataTableContent" align="center"><?php echo $question['que_sort_order']; ?></td>

														<td class="dataTableContent">
														<?php
														echo ($can_see_question_answers_email === true ? tep_db_output($question['customers_email']) : '[已隐藏]');
														?>
														</td>

														<td class="dataTableContent" align="center">
				
				<?php 
// echo $question['date'];
		
		$one = $question['date'];
		$date = ereg_replace('[^0-9]', '', $one);
		$date_year = substr($one, 0, 4);
		$date_month = substr($one, 5, 2);
		$date_day = substr($one, 8, 2);
		$date_hour = substr($one, 11, 2);
		$date_minute = substr($one, 14, 2);
		$date_second = substr($one, 17, 2);
		
		$questiondate = str_replace('-', '/', date('Y-m-d', mktime($date_hour, $date_minute, $date_second, $date_month, $date_day, $date_year)));
		// echo tep_db_output($questiondate);
		echo tep_db_output($one);
		
		?>
				</td>
														<td class="dataTableContent">
				<?php
		$qry_last_modified_by = "SELECT concat(a.admin_firstname, ' ', a.admin_lastname) as last_modified_by FROM " . TABLE_QUESTION_ANSWER . " qa, " . TABLE_ADMIN . " a WHERE qa.modified_by = a.admin_id AND qa.que_id = '" . $question['que_id'] . "' ORDER BY qa.ans_id DESC LIMIT 1";
		$res_last_modified_by = tep_db_query($qry_last_modified_by);
		$row_last_modified_by = tep_db_fetch_array($res_last_modified_by);
		echo tep_db_output($row_last_modified_by['last_modified_by']);
		?>
				</td>
														<td class="dataTableContent" align="center">
				<?php
		/*
		 * if((int)tep_get_question_anwers_count($question['que_id']) > 0 ){
		 * echo 'Yes'; }else{ echo 'No'; }
		 */
		// echo '最早回复的时间';
		/*
		 * $anwers_sql = tep_db_query("select date from " .
		 * TABLE_QUESTION_ANSWER . " where que_id = '" . $question['que_id'] .
		 * "' order by date asc limit 1"); $anwers_row =
		 * tep_db_fetch_array($anwers_sql);
		 */
		
		if (tep_not_null($question['first_answer_date']) && $question['first_answer_date'] != '0000-00-00 00:00:00') {
			$subtime = (strtotime($question['first_answer_date']) - strtotime($question['date'])) / 3600;
			if ($subtime >= 24) {
				echo '<span style="color:#FF0000">' . $question['first_answer_date'] . '</span>';
			} else {
				echo $question['first_answer_date'];
			}
		} else if (tep_not_null($question['date'])) {
			if ((strtotime("now") - strtotime($question['date'])) / 3600 >= 24) {
				echo '<b style="color:#FF0000">Delay</b>';
			}
		}
		?>
				&nbsp;
				</td>
														<td class="dataTableContent" align="right">
				<?php
		if ($question['que_id'] == $_GET['que_id'] || $question['que_id'] == $selected_item['que_id']) {
			echo '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' . $question['que_id'] . (isset($_GET['cID']) ? '&cID=' . $_GET['cID'] : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '') . '&action=view_question') . '">' . tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '') . '</a>';
		} else {
			echo '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' . $question['que_id'] . '&' . tep_get_all_get_params(array(
					'info',
					'x',
					'y',
					'que_id' 
			))) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
		}
		?>&nbsp;</td>

														</tr>
			  <?php
		// 问题的回复列表 start
		$need_include = false;
		$ans_tr_display = "none";
		if (tep_not_null($ans_start_date) || tep_not_null($ans_end_date)) {
			$need_include = true;
			$ans_tr_display = " ";
		}
		?>
			  <tr id="ans_tr_<?=$question['que_id']?>" style="display:<?= $ans_tr_display;?>;">
															<td colspan="10">
																<div id="ans_div_<?=$question['que_id']?>"
																	style="padding-left: 30px; font-size: 12px;">
				<?php
		if ($need_include == true) {
			include ('question_answers_ajax.php');
		}
		?>
				</div>
															</td>
														</tr>
			  <?php
		// 问题的回复列表 end
		?>

<?php
	}
	
	?>

              <tr>
															<td colspan="10"><table border="0" width="100%"
																	cellspacing="0" cellpadding="2">
																	<tr>
																		<td class="smallText" valign="top"><?php echo $question_split->display_count($question_answers_query_numrows, MAX_DISPLAY_NEW_QUEASION, $_GET['page'], ''); ?></td>
																		<td class="smallText" align="right">
					<?php echo $question_split->display_links($question_answers_query_numrows, MAX_DISPLAY_NEW_QUEASION, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'que_id'))); ?>
					
					<?php
	// 显示总的问题数和及时回复率=问题回复在24小时以内的/总问题数 start
	if ((tep_not_null($_GET['start_date']) || tep_not_null($_GET['end_date'])) && ! ( int ) $pID && ! ( int ) $_GET['question_tab_id'] && ! ( int ) $_GET['adminid'] && $off_tong_ji != true) {
		// 计算在24小时以内的问题回复率
		$where_exc_for_count = '';
		if (tep_not_null($_GET['start_date'])) {
			$where_exc_for_count .= ' AND q.date >="' . $_GET['start_date'] . ' 00:00:00' . '" ';
		}
		if (tep_not_null($_GET['end_date'])) {
			$where_exc_for_count .= ' AND q.date <="' . $_GET['end_date'] . ' 23:59:59' . '" ';
		}
		
		$query_string = "SELECT count(*) as total FROM " . TABLE_QUESTION . " q WHERE q.first_answer_date <= DATE_ADD(q.date, INTERVAL 1 DAY ) AND q.first_answer_date>'0000-00-00 00:00:00' AND q.first_answer_date IS NOT NULL " . $where_exc_for_count . " {$group_by} ";
		$re_sql = tep_db_query($query_string);
		
		$re_row = tep_db_fetch_array($re_sql);
		$re_proportion = '0';
		if (( int ) $re_row['total'] && ( int ) $question_answers_query_numrows) {
			// $re_proportion = ceil($re_row['total'] /
			// $question_answers_query_numrows * 10000) /100;
			// $re_proportion = intval($re_row['total'] /
			// $question_answers_query_numrows * 10000) /100;
			$re_proportion = round($re_row['total'] / $question_answers_query_numrows * 10000, 0) / 100;
			$re_proportion .= '%';
		}
		?>
					<div id="tmp_Statistical_Report" style="display:<?= 'none';?>;">
																				<table border="0" cellspacing="0" cellpadding="0">
																					<tr class="dataTableHeadingRow">
																						<td class="dataTableHeadingContent">&nbsp;时间&nbsp;</td>
																						<td class="dataTableHeadingContent">&nbsp;总问题&nbsp;</td>
																						<td class="dataTableHeadingContent">&nbsp;及时回复率&nbsp;</td>
																					</tr>
																					<tr class="dataTableRow">
																						<td class="dataTableContent"><?php echo $_GET['start_date'];?>&nbsp;至&nbsp;<?php $end = tep_not_null($_GET['end_date']) ? $_GET['end_date'] : 'Today'; echo $end;?></td>
																						<td class="dataTableContent" align="right"><?php echo $question_answers_query_numrows;?></td>
																						<td class="dataTableContent" align="right"><?php echo $re_proportion;?></td>
																					</tr>
																				</table>

																			</div> <script type="text/javascript">
						if(document.getElementById('tmp_Statistical_Report')!=null && document.getElementById('Statistical_Report')!=null ){
							document.getElementById('Statistical_Report').innerHTML = document.getElementById('tmp_Statistical_Report').innerHTML;
						}
					</script>
					<?php
	}
	// 显示总的问题数和及时回复率=问题回复在24小时以内的/总问题数 end
	?>
					
					</td>
																	</tr>

																</table></td>
														</tr>

													</table>
												</td>

<?php
	
	$heading = array();
	
	$contents = array();
	
	switch ($_GET['action']) {
		case 'show_copy' :
			$heading[] = array(
					'text' => '<b>copy Question </b>' 
			);
			$contents = array(
					'form' => tep_draw_form('news', FILENAME_QUESTION_ANSWERS, '&dealscat=' . $dealscat . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . '&action=copy') . tep_draw_hidden_field('que_id', $_GET['que_id']) 
			);
			
			// $contents[] = array('text' => TEXT_DELETE_ITEM_INTRO);
			
			$contents[] = array(
					'text' => '<br><b>' . $selected_item['question'] . '</b><b><br />copy to : <input type="text" name="copy_to" title="用逗号隔开，类似于 13，15，17"/><br /> show time: <input type="text" name="show_time" /> <br /> like : 2010-01-12 00:00:00</b>' 
			);
			
			$bottons_all = '';
			if ($can_copy_question_answers === true || $login_groups_id == 1) { // Service
			                                                                // Team
			                                                                // (senior)
				$bottons_all .= '<br><input type="submit" value="确定" /><a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' . $selected_item['que_id']) . '&dealscat=' . $dealscat . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
			}
			$contents[] = array(
					'align' => 'center',
					
					'text' => $bottons_all 
			);
			break;
		case 'top_show' :
			$heading[] = array(
					'text' => '<b>top Question </b>' 
			);
			$contents = array(
					'form' => tep_draw_form('news', FILENAME_QUESTION_ANSWERS, '&dealscat=' . $dealscat . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . '&action=top') . tep_draw_hidden_field('que_id', $_GET['que_id']) 
			);
			
			// $contents[] = array('text' => TEXT_DELETE_ITEM_INTRO);
			
			$contents[] = array(
					'text' => '<br><b>' . $selected_item['question'] . '</b>' 
			);
			
			$bottons_all = '';
			if ($can_top_question_answers === true || $login_groups_id == 1) { // Service
			                                                               // Team
			                                                               // (senior)
				$bottons_all .= '<br><input type="submit" value="确定" /> <a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' . $selected_item['que_id']) . '&dealscat=' . $dealscat . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
			}
			$contents[] = array(
					'align' => 'center',
					
					'text' => $bottons_all 
			);
			break;
		case 'move_banner' :
			$heading[] = array(
					'text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>' 
			);
			
			$contents = array(
					'form' => tep_draw_form('banners_move', FILENAME_QUESTION_ANSWERS, 'action=move_banner_confirm' . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '') . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['cPath']) ? '&cPath=' . $_GET['cPath'] . '' : '')) . tep_draw_hidden_field('que_id', $_GET['que_id']) 
			);
			
			// $contents[] = array('text' =>
			// TEXT_INFO_CURRENT_CATEGORIES.'<br>');
			
			$contents[] = array(
					'text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $selected_item['question']) 
			);
			
			$categories_array = array();
			// $categories_array[] = array('id' => '', 'text' =>
			// TEXT_NO_CATEGORY);
			$categories_query = tep_db_query("select icd.categories_id, icd.categories_name from " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd where language_id = '" . ( int ) $languages_id . "' order by icd.categories_name");
			while ( $categories_values = tep_db_fetch_array($categories_query) ) {
				$categories_array[] = array(
						'id' => $categories_values['categories_id'],
						'text' => $categories_values['categories_name'] 
				);
			}
			
			$delete_categories_array = array();
			$delete_categories_array[] = array(
					'id' => '0',
					'text' => 'No' 
			);
			$delete_categories_array[] = array(
					'id' => '1',
					'text' => 'Yes' 
			);
			
			$contents[] = array(
					'text' => '<br>' . TEXT_WANT_TO_DELETE_FRON_TORE_PAGE . '<br>' . tep_draw_pull_down_menu('delete_que_tour_page', $delete_categories_array) 
			);
			$contents[] = array(
					'text' => '<br>' . sprintf(TEXT_MOVE, $selected_item['question']) . '<br>' . tep_draw_pull_down_menu('faq_category', $categories_array) 
			);
			$contents[] = array(
					'align' => 'center',
					'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' . $_GET['que_id'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' 
			);
			break;
		
		case 'delete_question' : // generate box for confirming a news article
		                        // deletion
			
			$heading[] = array(
					'text' => '<b>Delete Question</b>' 
			);
			
			$contents = array(
					'form' => tep_draw_form('news', FILENAME_QUESTION_ANSWERS, '&dealscat=' . $dealscat . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . '&action=delete_question_confirm') . tep_draw_hidden_field('que_id', $_GET['que_id']) 
			);
			
			// $contents[] = array('text' => TEXT_DELETE_ITEM_INTRO);
			
			$contents[] = array(
					'text' => '<br><b>' . $selected_item['question'] . '</b>' 
			);
			
			$bottons_all = '';
			if ($can_check_question_answers === true || $login_groups_id == 1) { // Service
			                                                                 // Team
			                                                                 // (senior)
				$bottons_all .= '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' . $selected_item['que_id']) . '&dealscat=' . $dealscat . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
			}
			$contents[] = array(
					'align' => 'center',
					
					'text' => $bottons_all 
			);
			
			break;
		
		default :
			
			if ($rows > 0) {
				
				if (is_array($selected_item)) { // an item is selected, so make the
				                                // side box
					
					$heading[] = array(
							'text' => '' 
					);
					
					$bottons_all = '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' . $selected_item['que_id'] . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '') . '&action=view_question') . '">' . tep_image_button('button_view.gif', 'View Questions') . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' . $selected_item['que_id'] . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '') . '&dealscat=' . $dealscat . '&action=view_question&replay=ans') . '">' . tep_image_button('button_reply.gif', 'Reply') . '</a></br>
								
								<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' . $selected_item['que_id'] . (isset($_GET['cPath']) ? '&cPath=' . $_GET['cPath'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '') . '&action=move_to_leads') . '">' . tep_image_button('button_move_to_leads.gif', 'Move to Leads') . '</a>
								<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' . $selected_item['que_id'] . (isset($_GET['cPath']) ? '&cPath=' . $_GET['cPath'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '') . '&action=move_banner') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a><br>';
					
					if ($can_check_question_answers === true || $login_groups_id == 1) { // Service
					                                                                 // Team
					                                                                 // (senior)
						$bottons_all .= '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' . $selected_item['que_id'] . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '') . '&action=edit_question') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' . $selected_item['que_id'] . '&dealscat=' . $dealscat . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '') . '&action=delete_question') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a></br>';
					}
					
					$contents[] = array(
							'align' => 'center',
							
							'text' => $bottons_all 
					);
					// &nbsp;</br> <a href="' .
					// tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' .
					// $selected_item['que_id'] .
					// '&dealscat='.$dealscat.'&action=send_merchant_question')
					// . '">' . tep_image_button('button_send_que_merchant.gif',
					// 'Sent message to merchant') . '</a>
					$contents[] = array(
							'align' => 'center',
							
							'text' => '<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' . $selected_item['que_id'] . '&dealscat=' . $dealscat . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '') . '&action=top_show') . '"><input type="button" value="top" /></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_QUESTION_ANSWERS, 'que_id=' . $selected_item['que_id'] . '&dealscat=' . $dealscat . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] . '' : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '') . '&action=show_copy') . '"><input type="button" value="copy" /></a>' 
					);
					$contents[] = array(
							'text' => '<b>Customer email</b><br>' . ($can_see_question_answers_email === true ? $selected_item['customers_email'] : '[已隐藏]' ) 
					);
					
					$contents[] = array(
							'text' => '<br>' . $selected_item['content'] 
					);
				}
			} else { // create category/product info
				
				$heading[] = array(
						'text' => '<b>' . EMPTY_CATEGORY . '</b>' 
				);
				
				$contents[] = array(
						'text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name) 
				);
			}
			
			break;
	}
	
	if ((tep_not_null($heading)) && (tep_not_null($contents))) {
		
		echo '            <td width="25%" valign="top">' . "\n";
		
		$box = new box();
		
		echo $box->infoBox($heading, $contents);
		
		echo '            </td>' . "\n";
	}
	
	?>

          </tr>

										</table></td>

								</tr>

<?php
}

?>

    </table></td>

						<!-- body_text_eof //-->

					</tr>

				</table> <!-- body_eof //--> <!-- footer //-->

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //--> <br>
	
	</table>
</body>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>