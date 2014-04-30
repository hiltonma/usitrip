<?php
require_once('includes/application_top.php');
if(isset($_GET['ajax']) || isset($_POST['ajax'])){
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	header("Content-type: text/html; charset=" . CHARSET);

	$ajax = true;
}


//取得某个问题的回复列表
$que_id = (int)$question['que_id'] ? (int)$question['que_id'] : (int)$_GET['que_id'];
if((int)$que_id){	
	$ans_sql = tep_db_query('SELECT * FROM '.TABLE_QUESTION_ANSWER.' WHERE que_id="'.$que_id.'" Order By date DESC ');
	$html_string = "";
	while($ans_rows = tep_db_fetch_array($ans_sql)){
		$bottons_all = '';
		if($can_check_question_answers === true || $login_groups_id==1 || $ans_rows['modified_by']==$login_id){ // Service Team (senior)
			$bottons_all = ' <button type="button" onclick="location=&quot;'.tep_href_link("question_answers.php","que_id=".$que_id."&action=update_ans_of_que&updateansid=".$ans_rows['ans_id']).'&quot;"> Edit </button>&nbsp;&nbsp;<button type="button" onclick="window.open(&quot;'.tep_href_link("question_answers.php","que_id=".$que_id."&action=update_ans_of_que&updateansid=".$ans_rows['ans_id']).'&quot;)"> Open New Window Edit </button>&nbsp;&nbsp;<button type="button" onclick="show_this_ans('.$que_id.', 1)"> Refresh </button>';
			$display_set_has_checked_button = true;
			if($can_check_question_answers === true || $login_groups_id==1){
				if($ans_rows['has_checked']!="1" || $display_set_has_checked_button == true){
					$bottons_all .= '&nbsp;&nbsp;<button type="button" onclick="set_has_checked('.$ans_rows['ans_id'].','.$que_id.')"> Set Has Checked </button>';
				}
				$bottons_all .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" onclick="fast_del_ans('.$ans_rows['ans_id'].','.$que_id.')"> Delete </button>';
			}
		}
		$has_checked_string = "";
		if($ans_rows['has_checked']=="1"){ $has_checked_string = '<b style="color:#090">[Has Checked]</b>';}
		$html_string .= '<div style="padding-bottom:10px;">';
		$html_string .= '	<div>'.$has_checked_string.' Ans time:'.db_to_html($ans_rows['date']).'&nbsp;&nbsp;Modified By:'.db_to_html(tep_get_admin_customer_name($ans_rows['modified_by'])).'</div>
				<div>'.nl2br(db_to_html($ans_rows['ans'])).'</div>
				<div>'.$bottons_all.'</div>
			</div><hr>';
	}
	if($ajax!=true){
		echo $html_string;
	}else{
		$js_str = ' document.getElementById("ans_div_'.$que_id.'").innerHTML = "'.addslashes($html_string).'";';
		$js_str .= ' document.getElementById("ans_tr_'.$que_id.'").style.display = ""; ';
		$js_string = '[JS]'.preg_replace('/[[:space:]]+/',' ',$js_str).'[/JS]';
		echo $js_string;
	}
}
?>