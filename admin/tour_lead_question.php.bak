<?php

require_once('includes/application_top.php');
// 备注添加删除
if($_GET['ajax']=="true"){
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('tour_lead_question');
	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
}
if( $HTTP_GET_VARS['lead_id'] != ''){
	$lead_id =  $HTTP_GET_VARS['lead_id'] ;
}

$payments_statuses = array();
$payments_status_array = array();
$payments_status_query = tep_db_query("select tour_leads_status_id, tour_leads_status_name from " . TABLE_TOUR_LEADS_STATUS . " ");
while ($payments_status = tep_db_fetch_array($payments_status_query)) {
	$payments_statuses[] = array('id' => $payments_status['tour_leads_status_id'],
	'text' => $payments_status['tour_leads_status_name']);
	$payments_status_array[$payments_status['tour_leads_status_id']] = $payments_status['tour_leads_status_name'];
}

if ($HTTP_GET_VARS['action']) {

	switch ($HTTP_GET_VARS['action']) {


		case 'recover_to_question':
			$lead_id = (int)$HTTP_GET_VARS['lead_id'];
			if((int)$lead_id){
				$l_sql = tep_db_query('SELECT * FROM `tour_leads_info` WHERE lead_id="'.(int)$lead_id.'" limit 1');
				$l_row = tep_db_fetch_array($l_sql);
				if(tep_not_null($l_row['lead_comment'])){
					$sql_data_array = array('products_id' => (int)$l_row['products_id'],
					'customers_name' => $l_row['lead_fname'],
					'customers_email' => $l_row['lead_email'],
					'question' => $l_row['lead_comment'],
					//'date_added' => 'now()',
					'date' => $l_row['date_added'],
					'que_id' => $l_row['que_id']
					);

					tep_db_perform('tour_question', $sql_data_array);

					if((int)$lead_id){
						tep_db_query('DELETE FROM `tour_leads_info` WHERE lead_id="'.(int)$lead_id.'" limit 1');

						//更新首页的咨询xml文档
						update_xml_for_index_page_customer_questions();

						$messageStack->add_session('更新成功！请目前已经在Question Asked页面。', 'success');
						tep_redirect(tep_href_link('question_answers.php','que_id='.$l_row['que_id']));
						exit;
					}

				}
			}


			break;

		case 'delete_tour_type_confirm' : //user has confirmed deletion of news article.

		if ($HTTP_POST_VARS['lead_id']) {

			$lead_id = tep_db_prepare_input($HTTP_POST_VARS['lead_id']);

			tep_db_query("delete from " . TABLE_TOUR_LEADS_INFO . " where lead_id = '" . tep_db_input($lead_id) . "'");
			tep_db_query("delete from " . TABLE_TOUR_LEADS_INFO_ANSWER . " where lead_id = '" . tep_db_input($lead_id) . "'");
		}



		//tep_redirect(tep_href_link(FILENAME_TOUR_LEAD_QUESTION));
		//tep_redirect(tep_href_link(FILENAME_TOUR_LEAD_QUESTION));
		tep_redirect(tep_href_link(FILENAME_TOUR_LEAD_QUESTION, tep_get_all_get_params(array('action'))));

		break;

		case 'update_lead_status':

			$lead_id = $HTTP_GET_VARS['lead_id'];

			//$replay_name = tep_db_prepare_input($HTTP_POST_VARS['replay_name']);

			//  $replay_email = tep_db_prepare_input($HTTP_POST_VARS['replay_email']);

			$anwers = tep_db_prepare_input($HTTP_POST_VARS['anwers']);

			$lead_status_id = tep_db_prepare_input($HTTP_POST_VARS['lead_status_id']);

			$anwers = tep_db_prepare_input($HTTP_POST_VARS['anwers']);

			$lead_status_id = tep_db_prepare_input($HTTP_POST_VARS['lead_status_id']);

			$extra_message_send = tep_db_prepare_input($HTTP_POST_VARS['extra_message_send']);

			$customer_notified = '0';
			if (isset($HTTP_POST_VARS['notify']) && ($HTTP_POST_VARS['notify'] == 'on')) {
				$notify_comments = '';
				$customer_notified = '1';

				//write mail start

				//$fromemail = STORE_OWNER_EMAIL_ADDRESS;
				//$sownername = STORE_OWNER;
				$fromemail = STORE_OWNER_EMAIL_ADDRESS;
				$sownername = STORE_OWNER;
				$tablink = '-question-answer';

				if(isset($HTTP_POST_VARS['email_subject']) && $HTTP_POST_VARS['email_subject'] != ''){
					$subject = $HTTP_POST_VARS['email_subject'];
				}else{
					$subject="Your Question on usitrip.com is answered";
				}
				$findcustomerdata = tep_db_query("select * from " . TABLE_TOUR_LEADS_INFO . " where lead_id = '".(int)$lead_id ."'");
				if($customerdata = tep_db_fetch_array($findcustomerdata)) {
					$c_email = $customerdata["lead_email"];
					//$products_id = $customerdata["products_id"];
					//$cPath = tep_get_products_catagory_id($products_id);
					//$c_name = ucfirst(substr($customerdata["customers_name"], 0, 1)).substr($customerdata["customers_name"], 1, strlen($customerdata["customers_name"]));
					$message = 	"<table width='100%' border='0' cellpadding='0' cellspacing='0' >".
					"<tr>".
					"<td valign='top' colspan='2'>$anwers</td>".
					"</tr>".
					"<tr>".
					"<td valign='top' colspan='2'><br><br>$extra_message_send</td>".
					"</tr>".
					"</table>";
					// zhh fix and added

					$to_name = $c_name;
					$to_email_address = $c_email;
					$email_subject = $subject;
					$email_text = $message;

					$from_email_name = STORE_OWNER;
					$from_email_address = STORE_OWNER_EMAIL_ADDRESS;

					tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true');
				}

				//write mail end

			}





			tep_db_query("insert into " . TABLE_TOUR_LEADS_INFO_ANSWER . " (lead_id,lead_comment_answer,lead_status_id,date_added,customer_notified, modified_by) values ('" . (int)$lead_id . "', '" .  tep_db_input($anwers) ."', '" . (int)$lead_status_id . "', now(),'".$customer_notified."', '".$login_id."')");


			//update status or current lead id start
			$sql_data_array = array(
			'lead_status_id'   => tep_db_prepare_input($HTTP_POST_VARS['lead_status_id'])

			);



			tep_db_perform(TABLE_TOUR_LEADS_INFO, $sql_data_array, 'update', "lead_id = '" . $lead_id . "'");

			//update status or current lead id end
			define('SUCCESS_ORDER_UPDATED', 'Success: Lead has been successfully updated.');
			$messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
			tep_redirect(tep_href_link(FILENAME_TOUR_LEAD_QUESTION, tep_get_all_get_params(array('action')) . 'action=view_question'));
			break;
		case 'addreplyprocess':
			//send ans from admin bof

			if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'addreplyprocess')) {

				$lead_id = $HTTP_GET_VARS['lead_id'];

				$replay_name = tep_db_prepare_input($HTTP_POST_VARS['replay_name']);

				$replay_email = tep_db_prepare_input($HTTP_POST_VARS['replay_email']);

				$anwers = tep_db_prepare_input($HTTP_POST_VARS['anwers']);

				$lead_status_id = tep_db_prepare_input($HTTP_POST_VARS['lead_status_id']);

				$replay_name = ucfirst(substr($replay_name, 0, 1)).substr($replay_name, 1, strlen($replay_name));

				if($replay_name != '' && $anwers != '' ) {
					tep_db_query("insert into " . TABLE_TOUR_LEADS_INFO_ANSWER . " (lead_id,lead_comment_answer,lead_status_id,date_added, modified_by) values ('" . (int)$lead_id . "', '" .  tep_db_input($anwers) ."', '" . (int)$lead_status_id . "', now(), '".$login_id."')");


					//update status or current lead id start
					$sql_data_array = array(
					'lead_status_id'   => tep_db_prepare_input($HTTP_POST_VARS['lead_status_id'])

					);



					tep_db_perform(TABLE_TOUR_LEADS_INFO, $sql_data_array, 'update', "lead_id = '" . $lead_id . "'");

					//update status or current lead id end
					//tep_redirect(tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params(array('action'))));
					// send mali to customer
					// send mail to merchant  --scs Bof

					//$fromemail = 'service@samszone.com';
					//$fromemail = STORE_OWNER_EMAIL_ADDRESS;
					//$sownername = STORE_OWNER;
					$fromemail = $replay_email;
					$sownername = $replay_name;
					$tablink = '-question-answer';
					$subject="Your Question on usitrip.com is answered";

					$findcustomerdata = tep_db_query("select * from " . TABLE_TOUR_LEADS_INFO . " where lead_id = '".(int)$lead_id ."'");
					if($customerdata = tep_db_fetch_array($findcustomerdata)) {
						$c_email = $customerdata["lead_email"];
						//$products_id = $customerdata["products_id"];
						//$cPath = tep_get_products_catagory_id($products_id);
						//$c_name = ucfirst(substr($customerdata["customers_name"], 0, 1)).substr($customerdata["customers_name"], 1, strlen($customerdata["customers_name"]));
						$message = 	"<table width='100%' border='0' cellpadding='0' cellspacing='0' >".
						"<tr>".
						"<td valign='top' colspan='2'>$anwers</td>".
						"</tr>".
						"<tr>".
						"<td valign='top'><br><br>Thanks<br><a href='".HTTP_SERVER."'>www.usitrip.com</a></td>".
						"</tr>".
						"</table>";

						// zhh fix and added

						$to_name = $c_name;
						$to_email_address = $c_email;
						$email_subject = $subject;
						$email_text = $message;

						$from_email_name = $sownername;
						$from_email_address = $fromemail;

						tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true');

					}

					// send mail to merchant  --scs Bof

					tep_redirect(tep_href_link(FILENAME_TOUR_LEAD_QUESTION, "lead_id=$lead_id&action=view_question",'NONSSL',false,true,"qanda"));


				}

				// send mail to cutomer




			}

			//send ans from admin eof
			break;


		case 'addtourproccess': //insert a new news article.



		$sql_data_array = array(
		'lead_comment'   => tep_db_prepare_input($HTTP_POST_VARS['lead_comment']),

		'products_type_date' => 'now()', //uses the inbuilt mysql function 'now'

		);




		tep_db_perform(TABLE_TOUR_LEADS_INFO, $sql_data_array);

		$lead_id = tep_db_insert_id(); //not actually used ATM -- just there in case

		//}



		tep_redirect(tep_href_link(FILENAME_TOUR_LEAD_QUESTION,'lead_id='.$lead_id));



		break;



		case 'update_que_ans': //user wants to modify a news article.

		if($HTTP_GET_VARS['lead_id']) {

			$sql_data_array = array(
			'lead_comment'   => tep_db_prepare_input($HTTP_POST_VARS['lead_comment'])

			);



			tep_db_perform(TABLE_TOUR_LEADS_INFO, $sql_data_array, 'update', "lead_id = '" . tep_db_prepare_input($HTTP_GET_VARS['lead_id']) . "'");

		}



		// tep_redirect(tep_href_link(FILENAME_TOUR_LEAD_QUESTION));
		tep_redirect(tep_href_link(FILENAME_TOUR_LEAD_QUESTION,"lead_id=". tep_db_prepare_input($HTTP_GET_VARS['lead_id'])));
		break;







	}



}



?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo HEADING_TITLE; ?></title>

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



<script language="javascript"><!--
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

	if(document.product_queston_write.lead_comment.value == ""){
		alert("Please enter the value for tour category type name");
		document.product_queston_write.lead_comment.focus();
		return false;
	}


	return  true;
}

//--></script>

<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>

</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">

<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->



<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('tour_lead_question');
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

<?php

if ($HTTP_GET_VARS['action'] == 'edit_tour_type') { //insert or edit a news item

	if ( isset($HTTP_GET_VARS['lead_id']) ) { //editing exsiting news item

		$que_ans_query = tep_db_query("select *  from " . TABLE_TOUR_LEADS_INFO . " where lead_id = '" . $HTTP_GET_VARS['lead_id'] . "'");

		$que_ans = tep_db_fetch_array($que_ans_query);

	}


	?>

	<tr>

	<td><table border="0" width="100%" cellspacing="0" cellpadding="0">

	<tr>

	<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

	<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>

	</tr>

	</table></td>

	</tr>

	<tr>

	<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

	</tr>

	<tr><?php echo tep_draw_form('edit_quesion', FILENAME_TOUR_LEAD_QUESTION, isset($HTTP_GET_VARS['lead_id']) ? 'lead_id='.$HTTP_GET_VARS['lead_id'].'&action=update_que_ans' : 'action=insert_stores_deals', 'post', 'enctype="multipart/form-data"'); ?>

	<td><table border="0" cellspacing="0" cellpadding="2">




	<tr>

	<td class="main"><?php echo 'Tours Category Name' ?></td>

	<td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('lead_comment', stripslashes($que_ans['lead_comment'])); ?></td>

	</tr>


	</table></td>

	</tr>

	<tr>

	<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

	</tr>

	<tr>

	<td class="main" align="right">

	<?php

	isset($HTTP_GET_VARS['lead_id']) ? $cancel_button = '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_TOUR_LEAD_QUESTION,'lead_id='.$HTTP_GET_VARS['lead_id']). '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' : $cancel_button = '';

	echo tep_image_submit('button_insert.gif', IMAGE_INSERT) . $cancel_button;

	?>

	</td>

	</form></tr>

	<?php

}else if ($HTTP_GET_VARS['action'] == 'view_question') {

	//amit added to delete answer start
	if(isset($HTTP_GET_VARS['deleteansid']) && $HTTP_GET_VARS['deleteansid'] != ''){
		tep_db_query("delete from " . TABLE_TOUR_LEADS_INFO_ANSWER . " where lead_ans_id = '" . tep_db_input($HTTP_GET_VARS['deleteansid']) . "'");
	}
	//amit added to delete answer end

	?>
	<tr>

	<td><table border="0" width="100%" cellspacing="0" cellpadding="0">

	<tr>

	<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

	<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>

	</tr>

	</table></td>

	</tr>

	<tr>

	<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>

	</tr>
	<tr>

	<td>
	<!--  view body text start  -->


	<?php

	if(isset($HTTP_GET_VARS['replay']) && $HTTP_GET_VARS['replay']=='ans'){?>


	<?php
	echo tep_draw_form('product_question_answer_write', FILENAME_TOUR_LEAD_QUESTION, 'action=addreplyprocess&lead_id='.$lead_id, 'post', 'onSubmit="return checkForm();"');
	?>
	<table border="0" width="100%" cellspacing="2" cellpadding="2" class="unnamed1">

	<?php
	if($success == true){
		?>
		<tr>

		<td  colspan="3" class="main"><b>Your replay send successfully.</b></td>

		</tr>
		<?php
	}else{

		?>

		<tr>

		<td width="23%" class="main"><b>Lead Status:</b>&nbsp;<font color="#FF0000">(required)</font></td>

		<td colspan="2" class="main"><?php

		echo tep_draw_pull_down_menu('lead_status_id', $payments_statuses, tep_get_current_lead_status_from_id($lead_id));
		?></td>

		</tr>

		<tr>

		<td width="23%" class="main"><b>Your Name:</b>&nbsp;<font color="#FF0000">(required)</font></td>

		<td colspan="2" class="main"><?php echo tep_draw_input_field('replay_name',STORE_OWNER); ?></td>

		</tr>

		<tr>

		<td class="main"><b>Your E-mail:</b>&nbsp;<font color="#FF0000">(required)</font></td>

		<td colspan="2" class="main"><?php echo tep_draw_input_field('replay_email',STORE_OWNER_EMAIL_ADDRESS); ?></td>

		</tr>


		<tr>

		<td class="main"><b>Your Answers:</b>&nbsp;<font color="#FF0000">(required)</font></td>

		<td width="63%"  class="main"><?php echo tep_draw_textarea_field('anwers', 'soft', 45, 10); ?></td>

		<td width="14%"  class="main">&nbsp;</td>
		</tr>



		<?php // BOF: WebMakers.com Added: Split to two rows ?>

		<tr>

		<td class="main">&nbsp;</td>

		<td class="main"><input type="submit" name="submit" value="submit"></td>
		<td  class="main">&nbsp;</td>
		</tr>
		<tr>

		<td colspan="3">
		<table border="0" width="100%" cellspacing="0" cellpadding="2">

		<tr>

		<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

		<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_TOUR_LEAD_QUESTION, tep_get_all_get_params(array('cPath','replay','page'))) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>


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

	<?php }else{
		include('includes/javascript/editor.php');
		echo tep_load_html_editor();
		echo tep_insert_html_editor('anwers','simple','200');

		?>
		<table border="0" width="100%" cellspacing="0" cellpadding="2" class="unnamed1" >
		<?php

		$question_query_raw = "select * from " . TABLE_TOUR_LEADS_INFO ." where lead_id = '" . (int)$HTTP_GET_VARS['lead_id'] . "'";

		$question_query = tep_db_query($question_query_raw);

		while ($question = tep_db_fetch_array($question_query)) {

			?>

			<tr>

			<td width="100%" valign="top">

			<table width="100%"  border="0" cellspacing="2" cellpadding="4">
			<tr>
			<td class="main" width="25%" valign="top"><b><?php echo 'Customer:'; ?></b></td>
			<td class="main"><?php
			$question['lead_fname'] = ucfirst(substr($question['lead_fname'], 0, 1)).substr($question['lead_fname'], 1, strlen($question['lead_fname']));
			$question['lead_lname'] = ucfirst(substr($question['lead_lname'], 0, 1)).substr($question['lead_lname'], 1, strlen($question['lead_lname']));

			echo tep_db_output($question['lead_fname']." ".$question['lead_lname']);
			?></td>
			</tr>

			<tr>
			<td class="main"  valign="top"><b><?php echo 'Lead Email:'; ?></b></td>
			<td class="main"><?php
			echo tep_db_output($question['lead_email']);
			?></td>
			</tr>

			<tr>
			<td class="main"  valign="top"><b><?php echo 'Tour Name:'; ?></b></td>
			<td class="main"><?php
			echo '<a target="_blank" href="'.tep_catalog_href_link(FILENAME_PRODUCT_INFO,'products_id='.$question['products_id']).'">';
			echo tep_get_products_name($question['products_id']) .' ['. tep_get_products_model($question['products_id']).']'.' ['. tep_get_provider_tourcode($question['products_id']).']';
			echo '</a>';
			?></td>
			</tr>

			<tr>
			<td class="main"  valign="top"><b><?php echo 'Day Phone:'; ?></b></td>
			<td class="main"><?php echo tep_db_output($question['lead_dayphone']);?></td>
			</tr>

			<tr>
			<td class="main" valign="top"><b><?php echo 'Evening Phone:'; ?></b></td>
			<td class="main"><?php echo tep_db_output($question['lead_eveningphone']);?></td>
			</tr>

			<tr>
			<td class="main"  valign="top"><b><?php echo 'Best time to call:'; ?></b></td>
			<td class="main"><?php echo tep_db_output($question['lead_besttimetocall']);?></td>
			</tr>

			<tr>
			<td class="main" nowrap valign="top"><b><?php echo 'Question or comment:'; ?></b></td>
			<td class="main"><?php echo tep_db_output($question['lead_comment']); ?></td>
			</tr>

			<tr>
			<td class="main" valign="top"><b><?php echo 'Lead Status:'; ?></b></td>
			<td class="main"><?php echo tep_get_tour_leads_staus_name($question['lead_status_id']);?></td>
			</tr>

			<tr>
			<td class="main" valign="top"><b><?php echo 'Lead Date and Time:'; ?></b></td>
			<td class="main"><?php
			echo tep_datetime_short(tep_db_output($question['date_added']));
			?></td>
			</tr>
			<tr>
			<td class="main" valign="top"><b>参加人数</b></td>
			<td class="main"><?php
			echo tep_db_output($question['lead_guest_num']);
			?></td>
			</tr>
			<tr>
			<td class="main" valign="top"><b>是否持有美国/加拿大签证</b></td>
			<td class="main"><?php
			echo tep_db_output($question['lead_have_visa']);
			?></td>
			</tr>

			<tr>
			<td class="main" valign="top"><b>预计出发时间</b></td>
			<td class="main"><?php
			echo tep_db_output($question['lead_departure_date']);
			?></td>
			</tr>

			<tr>
			<td class="main" valign="top"><b>预计行程天数</b></td>
			<td class="main"><?php
			echo tep_db_output($question['lead_tour_day_num']);
			?></td>
			</tr>

			</table>


			<table border="0" width="100%" cellspacing="2" cellpadding="2">
			<tr>
			<td colspan="2" class="main" height="25" >&nbsp;
			</td>
			</tr>
			</table>

			</td>

			</tr>

			<tr>
			<td class="main">
			<table border="1" cellspacing="0" cellpadding="5">
			<tr>
			<td class="smallText" align="center"><b><?php echo 'Date Added'; ?></b></td>
			<td class="smallText" align="center"><b><?php echo 'Customer Notified'; ?></b></td>
			<td class="smallText" align="center"><b><?php echo 'Status'; ?></b></td>
			<td class="smallText" align="center"><b><?php echo 'Comments'; ?></b></td>
			<!--<td class="smallText" align="center"><b><?php //echo 'Delete'; ?></b></td>-->
			</tr>

			<?php
			$question_query_answer_raw = tep_db_query("select * from " . TABLE_TOUR_LEADS_INFO_ANSWER ." where lead_id = '" . (int)$question['lead_id'] . "' order by date_added");
			if(tep_db_num_rows($question_query_answer_raw)> 0){
				while ($question_ans = tep_db_fetch_array($question_query_answer_raw)) {
					?>
					<tr bgcolor="#DCF4FE">
					<td class="smallText" nowrap><?php echo tep_datetime_short($question_ans['date_added']);	?></td>
					<td class="smallText" align="center">
					<?php
					if ($question_ans['customer_notified'] == '1') {
						echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "\n";
					} else {
						echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "\n";
					}
					?>
					</td>
					<td class="smallText"><?php
					echo  tep_get_tour_leads_staus_name($question_ans['lead_status_id']);
					?></td>
					<td class="smallText"><?php echo $question_ans['lead_comment_answer'];  ?></td>
					<!-- <td class="smallText">
					<?php // echo '<a href="' . tep_href_link(FILENAME_TOUR_LEAD_QUESTION, "lead_id=".$question['lead_id']."&action=view_question&deleteansid=".$question_ans['lead_ans_id']."") . '">' . tep_image_button('button_delete.gif', 'Reply') . '</a>'; ?>
					</td> -->
					</tr>
					<?php

				} //end of while
			}else{

				echo 	 '          <tr>' . "\n" .
				'            <td class="smallText" colspan="4">'.TEXT_NO_RECORD_FOUND.'</td>' . "\n" .
				'          </tr>' . "\n";

			} //end of if
			?>

			</table>

			</td>
			</tr>



			<tr>
			<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
			</tr>
			<tr><?php echo tep_draw_form('status', FILENAME_TOUR_LEAD_QUESTION, tep_get_all_get_params(array('action','deleteansid')) . 'action=update_lead_status'); ?>
			<td class="main"></td>
			</tr>
			<tr>
			<td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
			</tr>
			<tr>
			<td><table border="0" cellspacing="0" cellpadding="2">
			<tr>
			<td><table border="0" cellspacing="0" cellpadding="2">
			<tr>
			<td class="main"><b>Email Subject:</b>
			</td>
			<td class="main">
			<?php echo tep_draw_input_field('email_subject', TEXT_DEFAULT_EMAIL_SUBJECT, 'size="60"'); ?>
			<!--<input type="text" name="email_subject" value="" size="60">-->
			</td>
			</tr>
			<tr> <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td></tr>
			<tr>
			<td class="main"><b><?php echo 'Comments:'; ?></b></td>
			<td> <?php
			$default_comment_added = '尊敬的 '. $question['lead_fname']." ".$question['lead_lname']. ': '.TEXT_HEADING_EMAIL_DEAR. $question['lead_fname']." ".$question['lead_lname'].':<br /> 您好！<br />';
			$default_comment_added .= TEXT_DEFULT_ANSWER_REPLY_CONTENT;
			echo tep_draw_textarea_field('anwers', 'soft', '60', '8',$default_comment_added);

			$ext_msg_con_str = 'Your question was regarding	<a target="_blank" href="'.tep_catalog_href_link(FILENAME_PRODUCT_INFO,'products_id='.$question['products_id']).'">'.tep_get_products_name($question['products_id']) .' ['. tep_get_products_model($question['products_id']).']</a><br><br>';

			$ext_msg_con_str .=  $question['lead_comment'];

			echo tep_draw_hidden_field('extra_message_send', $ext_msg_con_str);
			?>
			</td>
			</tr>
			<tr>  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td></tr>


			<tr>
			<td class="main"><b><?php echo 'Lead Status:'; ?></b></td><td> <?php echo tep_draw_pull_down_menu('lead_status_id', $payments_statuses, tep_get_current_lead_status_from_id($lead_id)); ?></td>
			</tr>
			<tr>  <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td></tr>

			<tr>
			<td class="main"><b><?php echo 'Notify Customer:'; ?></b> <?php echo tep_draw_checkbox_field('notify', '', false); ?></td>
			<td class="main"><b><?php echo 'Append Comments:'; ?></b> <?php echo tep_draw_checkbox_field('notify_comments', '', true); ?></td>
			</tr>
			<tr> <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td></tr>
			<tr>
			<td class="main"></td>
			<td><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td></tr>
			</table></td>
			<td valign="top"><?php //echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
			</tr>
			</table></td>
			</form></tr>
			<!--  check for answer of the question BOF--->

			<!--  check for answer of the question EOF--->




			<?php
		}  //end of while loop
		?>


		</table>
		<table border="0" width="100%" cellspacing="0" cellpadding="2">

		<tr>

		<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

		<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_TOUR_LEAD_QUESTION, tep_get_all_get_params(array('dealscat','replay','action','deleteansid'))) . '">' . tep_image_button('button_back.gif', 'Back') . '</a>'; ?></td>

		<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

		</tr>

		</table>

		<?php }?>

		<!-- view body text end -->


		</td>

		</tr>

		<?php }else  if ($HTTP_GET_VARS['action'] == 'addtourtype') {

			?>

			<tr>
			<td>

			<table border="0" width="100%" cellspacing="0" cellpadding="0">

			<tr>
			<td colspan="2" class="pageHeading">Add Tour Category Type</td>
			</tr>

			<?php
			echo tep_draw_form('product_queston_write', FILENAME_TOUR_LEAD_QUESTION, 'action=addtourproccess', 'post', 'onSubmit="return checkForm1();"');
			?>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">


			<tr>

			<td>
			</br>
			<table width="100%" border="0" cellspacing="0" cellpadding="2">


			<tr>

			<td align="center" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

			<tr>

			<td></br><table border="0" width="100%" cellspacing="2" cellpadding="2" class="unnamed1" >

			<tr>

			<td width="25%" class="main"><b>Tour Category Name:</b>&nbsp;<font color="#FF0000">(required)</font></td>

			<td width="75%" class="main"><?php echo tep_draw_input_field('lead_comment'); ?></td>

			</tr>

			<tr>
			<td >&nbsp;
			</td>
			<td>
			<input type="submit" name="submit" value="Add Tour Category">
			</td>

			</tr>

			<?php // BOF: WebMakers.com Added: Split to two rows ?>

			</table></td>




			</table></td>

			</table></td>

			</tr>

			</table></form>

			<tr>
			<td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">

			<tr>

			<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

			<td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_TOUR_LEAD_QUESTION, tep_get_all_get_params(array('cPath','replay','page','action'))) . '">' . tep_image_button('button_back.gif', 'Back') . '</a>'; ?></td>

			<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>

			</tr>

			</table></td>

			</tr>


			</table>
			</td>
			</tr>

			<?php
		}else {

			?>

			<tr>

			<td>

			<table border="0" width="100%" cellspacing="0" cellpadding="0">

			<tr>

			<td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

			<td align="right"><?php // echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?>


			</td>

			</tr>
			<tr><?php
			define('HEADING_TITLE_STATUS','Status:');
			define('TEXT_ALL_PAYMENTS','All status');

			echo tep_draw_form('status', FILENAME_TOUR_LEAD_QUESTION, '', 'get'); ?>
			<td class="smallText" align="right"><?php echo HEADING_TITLE_STATUS . ' ' . tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_PAYMENTS)), $payments_statuses), '', 'onChange="this.form.submit();"'); ?></td>
			</form></tr>

			</table>

			</td>

			</tr>

			<tr>

			<td><table border="0" width="100%" cellspacing="0" cellpadding="0">

			<tr>

			<td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

			<tr class="dataTableHeadingRow">
			<td class="dataTableHeadingContent">#ID</td>

			<td class="dataTableHeadingContent" nowrap>First Name</td>

			<td class="dataTableHeadingContent" nowrap>Last Name</td>

			<td class="dataTableHeadingContent" nowrap>Comment</td>

			<td class="dataTableHeadingContent" nowrap="nowrap">Tour Name</td>


			<td class="dataTableHeadingContent" nowrap><?php echo TEXT_HEADING_TOUR_CODE;?><br><?php echo "<a  href=tour_lead_question.php?sortorder=tourcode&".tep_get_all_get_params(array('cPath','selected_box','sortorder','page'))."><b>".Asc."</b></a>"; ?>&nbsp;&nbsp;<?php echo "<a  href=tour_lead_question.php?sortorder=tourcode-desc&".tep_get_all_get_params(array('cPath','selected_box','sortorder','page'))."><b>".Desc."</b></a>"; ?></td>

			<td class="dataTableHeadingContent" nowrap><?php echo 'Provider Code';?><br><?php echo "<a  href=tour_lead_question.php?sortorder=providercode&".tep_get_all_get_params(array('cPath','selected_box','sortorder','page'))."><b>".Asc."</b></a>"; ?>&nbsp;&nbsp;<?php echo "<a  href=tour_lead_question.php?sortorder=providercode-desc&".tep_get_all_get_params(array('cPath','selected_box','sortorder','page'))."><b>".Desc."</b></a>"; ?></td>

			<td class="dataTableHeadingContent">Status</td>

			<td class="dataTableHeadingContent" width="10%" align="center">Date<br><?php echo "<a  href=tour_lead_question.php?sortorder=date&".tep_get_all_get_params(array('cPath','selected_box','sortorder','page'))."><b>".Asc."</b></a>"; ?>&nbsp;&nbsp;<?php echo "<a  href=tour_lead_question.php?sortorder=date-desc&".tep_get_all_get_params(array('cPath','selected_box','sortorder','page'))."><b>".Desc."</b></a>"; ?></td>

			<td class="dataTableHeadingContent" align="left"><?php echo TXT_MODIFIED_BY;?><br></td>

			<td class="dataTableHeadingContent">Reply Date</td>
			<!--<td class="dataTableHeadingContent" align="center"><?php //echo INDEX_FEATURE_STATUS; ?></td>-->

			<td class="dataTableHeadingContent" width="5%" align="right">Action</td>

			</tr>


			<?php
			//Start - check if lead answerd or not
			$qry_set_replayed_leads = "UPDATE " . TABLE_TOUR_LEADS_INFO ." SET lead_replied=1 WHERE lead_id IN (SELECT lead_id FROM ".TABLE_TOUR_LEADS_INFO_ANSWER.")";
			$res_set_replayed_leads = tep_db_query($qry_set_replayed_leads);

			$qry_not_replayed_leads = "SELECT lead_id FROM " . TABLE_TOUR_LEADS_INFO ." l, " . TABLE_PRODUCTS ." p WHERE l.products_id=p.products_id and l.lead_replied=0";
			$res_not_replayed_leads = tep_db_query($qry_not_replayed_leads);
			$num_not_replayed_leads = tep_db_num_rows($res_not_replayed_leads);
			//End - check if lead answerd or not

			switch (trim($HTTP_GET_VARS['sortorder'])) {

				case "ans":
					$order = " li.lead_id ";
					break;
				case "ans-desc":
					$order = " li.lead_id DESC";
					break;
				case "date":
					$order = " li.date_added";
					break;
				case "date-desc":
					$order = " li.date_added DESC";
					break;
				case "tourcode":
					$order = " p.products_model";
					break;
				case "tourcode-desc":
					$order = " p.products_model DESC";
					break;
				case "providercode":
					$order = " p.provider_tour_code";
					break;
				case "providercode-desc":
					$order = " p.provider_tour_code DESC";
					break;
				default:
					if($num_not_replayed_leads=="0"){
						$order = " li.date_added DESC, li.lead_replied ASC ";
					}else{
						$order = "li.lead_id DESC";
					}
					break;

			}





			//echo  $order;

			$rows = 0;
			/*
			if(isset($HTTP_GET_VARS['sortorde']) && $HTTP_GET_VARS['sortorde']=''){

			}else


			*/

			//$question_query_raw = "select count(*) as count,q.lead_id,q.products_id,q.question,q.date from " . TABLE_TOUR_LEADS_INFO_ANSWER . " as qa," . TABLE_TOUR_LEADS_INFO . " as q where q.lead_id = qa.lead_id  group by qa.lead_id order by count";

			if(isset($HTTP_GET_VARS['status']) && $HTTP_GET_VARS['status'] != ''){
				//$whereadded = " where li.products_id=p.products_id and li.lead_status_id=".$HTTP_GET_VARS['status'];
				$whereadded = " and li.lead_status_id=".$HTTP_GET_VARS['status'];
			}else{
				//$whereadded =" where li.products_id=p.products_id ";
				$whereadded =" ";
			}

			$question_query_raw = "select distinct(li.lead_id), li.lead_fname, li.lead_lname, li.products_id, li.lead_status_id, li.date_added , p.products_model, p.provider_tour_code, lia.date_added as first_answer_date from " . TABLE_TOUR_LEADS_INFO ." li LEFT JOIN " . TABLE_PRODUCTS ." p ON li.products_id=p.products_id LEFT JOIN ".TABLE_TOUR_LEADS_INFO_ANSWER." as lia on lia.lead_id = li.lead_id LEFT JOIN ".TABLE_TOUR_LEADS_INFO_ANSWER." lia2 ON lia.lead_id = li.lead_id and lia.lead_id = lia2.lead_id AND lia.lead_ans_id > lia2.lead_ans_id AND lia.date_added > lia2.date_added where lia2.date_added IS NULL    $whereadded  order by $order";
			//echo $question_query_raw;
			// $question_split = new splitPageResults($question_query_raw, MAX_DISPLAY_NEW_REVIEWS);
			$question_answers_query_numrows = 20;
			define('MAX_DISPLAY_NEW_QUEASION','20');
			$question_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_NEW_QUEASION, $question_query_raw, $question_answers_query_numrows);


			//if ($question_split->number_of_rows > 0) {

			// $question_query = tep_db_query($question_split->sql_query);

			$question_query = tep_db_query($question_query_raw);
			while ($question = tep_db_fetch_array($question_query)) {

				if($rows == 0 && (!$action) && (!$selected_item) && !isset($HTTP_GET_VARS['lead_id'])){
					$HTTP_GET_VARS['lead_id'] = $question['lead_id'];
				}



				$rows++;



				if ( ((@$HTTP_GET_VARS['lead_id'] == $question['lead_id'])) && (!$selected_item) && (substr($HTTP_GET_VARS['action'], 0, 4) != 'new_') ) {

					$selected_item = $question;

				}

				if ( (is_array($selected_item)) && ($question['lead_id'] == $selected_item['lead_id']) ) {

					echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_TOUR_LEAD_QUESTION, 'lead_id=' . $question['lead_id'].(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '') .(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '').(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : ''))  . '\'">' . "\n";

				} else {

					echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . tep_href_link(FILENAME_TOUR_LEAD_QUESTION, 'lead_id=' . $question['lead_id'].(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '').(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '')) . '\'">' . "\n";

				}

				?>
				<td class="dataTableContent"><?php echo $question['lead_id']; ?>
				<td class="dataTableContent" nowrap><?php echo stripslashes(tep_db_output($question['lead_fname'])); ?>
				</td>
				<td class="dataTableContent" nowrap><?php echo stripslashes(tep_db_output($question['lead_lname'])); ?>
				</td>
				<td class="dataTableContent"><?php echo stripslashes(tep_db_output($question['lead_comment'])); ?>
				</td>
				<td class="dataTableContent"><?php
				echo '<a target="_blank" href="'.tep_catalog_href_link(FILENAME_PRODUCT_INFO,'products_id='.$question['products_id']).'">';
				echo tep_get_products_name($question['products_id']);
				echo '</a>';
				?>
				</td>
				<td class="dataTableContent" nowrap><?php
				echo $question['products_model']; ?>
				</td>
				<td class="dataTableContent" nowrap><?php
				echo $question['provider_tour_code']; ?>
				</td>
				<td class="dataTableContent" nowrap><?php
				echo tep_get_tour_leads_staus_name($question['lead_status_id']); ?>
				</td>
				<td class="dataTableContent" >

				<?php //echo $question['date_added'];
				/*$one = $question['date_added'];
				$date = ereg_replace('[^0-9]', '', $one);
				$date_year = substr($one,0,4);
				$date_month    = substr($one,5,2);
				$date_day = substr($one,8,2);
				$date_hour = substr($one,11,2);
				$date_minute = substr($one,14,2);
				$date_second = substr($one,17,2);

				$questiondate = str_replace('-','/',date('Y-m-d', mktime($date_hour, $date_minute, $date_second, $date_month, $date_day, $date_year)));

				echo $questiondate;
				*/
				echo tep_datetime_short(tep_db_output($question['date_added']));
				?>


				</td>
				<td class="dataTableContent">
				<?php
				$qry_last_modified_by = "SELECT concat(a.admin_firstname, ' ', a.admin_lastname) as last_modified_by FROM ".TABLE_TOUR_LEADS_INFO_ANSWER." la, ".TABLE_ADMIN." a WHERE la.modified_by = a.admin_id AND la.lead_id = '".$question['lead_id']."' ORDER BY la.lead_ans_id DESC LIMIT 1";
				$res_last_modified_by = tep_db_query($qry_last_modified_by);
				$row_last_modified_by = tep_db_fetch_array($res_last_modified_by);
				echo tep_db_output($row_last_modified_by['last_modified_by']); ?>
				</td>
				<td class="dataTableContent" >
				<?php
				if(tep_not_null($question['first_answer_date']) && $question['first_answer_date']!='0000-00-00 00:00:00'){
					$subtime = (strtotime($question['first_answer_date']) - strtotime($question['date_added']))/3600;
					if($subtime>=24){
						echo '<span style="color:#FF0000">'.tep_datetime_short($question['first_answer_date']).'</span>';
					}else{
						echo tep_datetime_short($question['first_answer_date']);
					}
				}else if(tep_not_null($question['date_added'])){
					if((strtotime("now")-strtotime($question['date_added'])) /3600 >= 24 ){
						echo '<b style="color:#FF0000">Delay</b>';
					}
				}
				?>
				</td>

				<td class="dataTableContent" align="right"><?php if ($question['lead_id'] == $HTTP_GET_VARS['lead_id']) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_TOUR_LEAD_QUESTION, 'lead_id=' . $question['lead_id'].'&'.tep_get_all_get_params(array('info', 'x', 'y', 'action','dealscat' , 'lead_id'))) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>

				</tr>

				<?php

			}

			?>

			<tr>
			<td colspan="12"><table border="0" width="100%" cellspacing="0" cellpadding="2">
			<tr>
			<td class="smallText" valign="top"><?php echo $question_split->display_count($question_answers_query_numrows, MAX_DISPLAY_NEW_QUEASION, $HTTP_GET_VARS['page'], ''); ?></td>
			<td class="smallText" align="right"><?php echo $question_split->display_links($question_answers_query_numrows, MAX_DISPLAY_NEW_QUEASION, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'lead_id'))); ?></td>
			</tr>

			</table></td>
			</tr>

			</table></td>

			<?php

			$heading = array();

			$contents = array();

			switch ($HTTP_GET_VARS['action']) {

				case 'delete_tour_type': //generate box for confirming a news article deletion

				$heading[] = array('text'   => '<b>Delete</b>');



				$contents = array('form'    => tep_draw_form('news', FILENAME_TOUR_LEAD_QUESTION, '&action=delete_tour_type_confirm'.(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '').(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '')) . tep_draw_hidden_field('lead_id', $HTTP_GET_VARS['lead_id']));

				// $contents[] = array('text'  => TEXT_DELETE_ITEM_INTRO);

				$contents[] = array('text'  => '<br><b>' . $selected_item['lead_id'] . '</b>');



				$contents[] = array('align' => 'center',

				'text'  => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_TOUR_LEAD_QUESTION, 'lead_id=' . $selected_item['lead_id']).(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '') .(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '').'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');

				break;



				default:

					if ($rows > 0) {

						if (is_array($selected_item)) { //an item is selected, so make the side box

							$recover_buttom ='';
							if((int)$selected_item['que_id']){
								$recover_buttom = '<a href="' . tep_href_link(FILENAME_TOUR_LEAD_QUESTION, 'lead_id=' . $selected_item['lead_id'] . '&action=recover_to_question') . '">' . tep_image_button('button_recover_to_question.gif', 'Recover to question') . '</a>';
							}

							$heading[] = array('text' => '');

							$contents[] = array('align' => 'center',

							'text' => '<a href="' . tep_href_link(FILENAME_TOUR_LEAD_QUESTION, 'lead_id=' . $selected_item['lead_id'] . '&action=view_question'.(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '').(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '')) . '">' . tep_image_button('button_view.gif', 'View') . '</a>
								
								<a href="' . tep_href_link(FILENAME_TOUR_LEAD_QUESTION, 'lead_id=' . $selected_item['lead_id'] . '&action=delete_tour_type'.(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['status']) ? '&status=' . $HTTP_GET_VARS['status'] . '' : '')) .(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a></br>
								'.$recover_buttom
								);

								$contents[] = array('text' => '<br>' . $selected_item['content']);

						}

					} else { // create category/product info

						$heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');



						$contents[] = array('text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name));

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

			<?php

		}

		?>

		</table></td>

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

