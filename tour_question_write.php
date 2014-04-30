<?php
/*
$Id: product_reviews_write.php,v 1.1.1.1 2004/03/04 23:38:02 ccwjr Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

require('includes/application_top.php');
require(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_QUESTION_WRITE);
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');


$_POST['aryFormData'] = 1;

//amitcommecnted for login eof

if(isset($_POST['aryFormData'])){

	$aryFormData = $_POST['aryFormData'];

	$success = false;

	if(isset($_GET['cPath']) &&  $_GET['cPath'] != ''){
		$cPath =  $_GET['cPath'];
	}
	if(isset($_GET['mnu']) &&  $_GET['mnu'] != ''){
		$mnu =  $_GET['mnu'];
	}
	if(isset($_GET['products_id']) &&  $_GET['products_id'] != ''){
		$products_id =  $_GET['products_id'];
	}


	if (isset($_GET['action']) && ($_GET['action'] == 'process')) {

		$customers_name = ajax_to_general_string(tep_db_prepare_input($HTTP_POST_VARS['customers_name']));

		$customers_email = tep_db_prepare_input($HTTP_POST_VARS['customers_email']);

		$c_customers_email = tep_not_null($HTTP_POST_VARS['c_customers_email'])? tep_db_prepare_input($HTTP_POST_VARS['c_customers_email']) : $customers_email;

		$questions = ajax_to_general_string(tep_db_prepare_input($HTTP_POST_VARS['questions']));

		$error = false;

		if($customers_name == ''){
			$error = true;
			$error_msg .= TEXT_ERROR_MSG_YOUR_NAME;
		}

		if($customers_email == ''){
			$error = true;
			$error_msg .= TEXT_ERROR_MSG_YOUR_EMAIL;
		} else {
			if(is_CheckvalidEmail($customers_email) != true){
				$error = true;
				$error_msg .= TEXT_ERROR_MSG_VALID_EMAIL;
			}
		}


		if($customers_email != $c_customers_email && $customers_email != ''){
			$error = true;
			$error_msg .= TEXT_ERROR_MSG_YOUR_EMAIL_CONFIRMATION;
		}

		$question_query = tep_db_query('SELECT question  FROM '.TABLE_QUESTION.' WHERE customers_email =\''.tep_db_input($customers_email).'\' AND products_id='.(int)$_GET['products_id']);
		$new_question = preg_replace("/(\s|\n)+/",'',html_to_db(ajax_to_general_string($HTTP_POST_VARS['questions'])));
		while($old_question = tep_db_fetch_array($question_query)) {
			$old_question = preg_replace("/(\s|\n)+/",'',$old_question['question']);
			if($old_question == $new_question){
				$error = true;
				$error_msg .= db_to_html("请不要重复提交问题!");
				break;
			}
		}

		if (strlen($questions) == 0) {
			$error = true;
			$error_msg .= TEXT_ERROR_MSG_YOUR_QUESTION;
		}

		if ($error == false && $questions != '' && $customers_email != '') {

			$accept_newsletter = (int)$HTTP_POST_VARS['accept_newsletter'];
			//检查用户是否重复提交文件


			tep_db_query(html_to_db ("insert into " . TABLE_QUESTION . " (que_id,products_id, question ,date,customers_name,customers_email,languages_id,accept_newsletter, customers_ip) values ('" . (int)$insert_id . "', '" . (int)$_GET['products_id'] . "', '" .  tep_db_input($questions) ."', now(),'" . tep_db_input($customers_name) . "','" . tep_db_input($customers_email) . "','" . (int)$languages_id . "','".(int)$accept_newsletter."','".tep_get_ip_address()."' )"));

			$que_insert_id = tep_db_insert_id();
			$success = true;

			// auto get question start
			auto_add_question_tag_id((int)$que_insert_id);
			// auto get question end

			// write to products_index
			$index_type = 'question';
			auto_add_product_index((int)$_GET['products_id'],$index_type );
			// write to products_index end

			header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
			header( "Cache-Control: no-cache, must-revalidate" );
			header( "Pragma: no-cache" );
			$jsQuestion = addcslashes(tep_db_output($questions),"\"\n\t\r");
			$jsCustomersName = addcslashes(tep_db_output($customers_name),"\"\n\t\r");
			$jsstr= '[JS]';
			$jsstr.='if(document.getElementById("QuestionNoContent"))jQuery("#QuestionNoContent").hide();';
			$jsstr.='if(document.getElementById("SearchNav"))jQuery("#SearchNav").hide();';
			$jsstr .= 'jQuery("#ConcultList").find("li").eq(0).before("			<li><div class=\\"ask\\">            	<dl>                	<dt>                    	<span><img width=\\"60\\" height=\\"55\\" src=\\"image/touxiang_no-sex.gif\\"></span>                        <em>' . $jsCustomersName . '</em>                    </dt>                    <dd>                    	<ul>                        	<li class=\\"s_1 font_bold color_blue\\">' . $jsQuestion . '</li>                            <li class=\\"s_3 color_b3b3b3\\">' . date('Y-m-d H:i') . '</li>                        </ul>                    </dd>                  </dl>            </div></li>");';
			
			//<li><div class=\\"ask\\"><p>'.$jsQuestion.'</p><div class=\\"signature\\"><span>'.$jsCustomersName.'</span>'.date('Y-m-d H:i').'</div></div></li>");';
			$jsstr .= 'jQuery("#ConcultList").fadeIn("slow");showPopup("QuestionDoneTip","QuestionDoneTipConCompare",1);';
			$jsstr .= 'window.setTimeout("closePopup(\'QuestionDoneTip\');",5000);';
			$jsstr .= 'document.product_question_write.questions.value="";';
			$jsstr .='jQuery("html,body").delay(5000).animate({scrollTop: jQuery("#two2").offset().top},1000);';
			$jsstr .= '[/JS]';
			echo $jsstr;

		}else{
			header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
			header( "Cache-Control: no-cache, must-revalidate" );
			header( "Pragma: no-cache" );
			$jsstr= '[JS]';
			$jsstr.='alert("'.str_replace('<br/>','\n',$error_msg).'");';
			$jsstr .= '[/JS]';
			echo $jsstr;
		}

	}

}else{
	echo '程序出错了。8888888';
}

?>
