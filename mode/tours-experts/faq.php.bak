<?php
!defined('_MODE_KEY') && exit('Access error!');

if($_GET['ajax']=='true' || $_POST['ajax']=='true'){	//ajax数据提交
	header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
	header( "Cache-Control: no-cache, must-revalidate" );
	header( "Pragma: no-cache" );

	if($_POST['atcion']=='SubmitQuestion'){	//专家普通页面提交咨询
		$error = false;
		$js_str = '';
		$q_content = ajax_to_general_string(tep_db_prepare_input($_POST['q_content']));
		$uid = (int)$_GET['uid'];
		if(strlen($q_content)<1){
			$error = true;
			$js_str = 'alert("请输入您的咨询内容！");';
			$js_str .= 'jQuery("#SubmitQuestionButton").attr("disabled",false);';
		}
		if($uid<1){
			$error = true;
			$js_str .= 'alert("uid参数丢失！");';
		}
		if(!(int)$customer_id){
			$error = true;
			$js_str .= 'alert("您还没有登陆，请先登陆！");isLogin=false;check_login("_Ajax_FastLogin");jQuery("#SubmitQuestionButton").attr("disabled",false);';
		}
		
		if($error == false){
			$this_time = date('Y-m-d H:i:s');
			$sql_data_array = array('uid' => $uid, 'q_content' => $q_content, 'q_add_time'=>$this_time, 'q_modified_time'=>$this_time, 'customers_id'=>$customer_id);
			$sql_data_array = html_to_db($sql_data_array);
			tep_db_perform('experts_question', $sql_data_array);
			$q_id = tep_db_insert_id();
			//==========================================
			$notes_content = '您的咨询已经提交，请等待专家回复，谢谢！';
			$out_time = 5; //延迟3秒关闭
			$goto_url = '';			
			$js_str .= write_success_notes($out_time, $notes_content, $gotourl);
			//===============================================
	
			$js_str .= 'jQuery("#SubmitQuestionButton").attr("disabled",false);';
			$js_str .= 'document.getElementById("SubmitQuestionForm").elements["q_content"].value="";';
			$js_str .= 'var add_dd = "<dl id=QuestionL_'.$q_id.'><dt>问：</dt><dd><p>'.nl2br(html_to_db(tep_db_output($q_content))).'</p> <span>'.tep_db_output(tep_customers_name($customer_id)).'</span><span>'.substr($this_time,0,16).'</span></dd></dl>"; ';
			$js_str .= 'var old_html = jQuery("#expertFaqList").html(); ';
			$js_str .= 'jQuery("#expertFaqList").html(add_dd + old_html);';
			
		}
		if(tep_not_null($js_str)){
			$js_str = '[JS]'.preg_replace('/[[:space:]]+/',' ',$js_str).'[/JS]';
			echo db_to_html($js_str);
		}
		exit;
	}
	
	if($_GET['atcion']=='set_useful_or_useless'){	//设置回复有用或无用
		if($_GET['ajax']=='true' && (int)$_GET['a_id'] && tep_not_null($_GET['val'])){
			$can_repeat_vote = false;	//是否允许重复投票
			if($can_repeat_vote == false){
				if($_COOKIE['votes']['E_Answers'][(int)$_GET['a_id']]=="true"){
					$js_str = '[JS]';
					$js_str .= 'jQuery("#faq_a_'.(int)$_GET['a_id'].'").html("<font style=\'color:red;\'>请勿重复投票！</font>");';
					$js_str .= '[/JS]';
					$js_str = preg_replace('/[[:space:]]+/',' ',$js_str);
					echo db_to_html($js_str);
					exit;
				}else{
					setcookie('votes[E_Answers]['.(int)$_GET['a_id'].']', 'true', time() +(3600*24*30*365));
				}
			}
			
			$set_field = ' `a_useful` = (`a_useful`+1) ';
			if($_GET['val']=="useless"){
				$set_field = ' `a_useless` = (`a_useless`+1) ';
			}
			
			tep_db_query('update experts_answers set '.$set_field.' where a_id="'.(int)$_GET['a_id'].'" ');	
			$sql = tep_db_query('SELECT a_useful, a_useless FROM `experts_answers` WHERE a_id="'.(int)$_GET['a_id'].'" Limit 1');
			$row = tep_db_fetch_array($sql);
			$js_str = 'jQuery("#useful_'.(int)$_GET['a_id'].'").html("'.$row['a_useful'].'");';
			$js_str .= 'jQuery("#useless_'.(int)$_GET['a_id'].'").html("'.$row['a_useless'].'");';
			$js_str .= 'jQuery("#faq_a_'.(int)$_GET['a_id'].'").html("非常感谢您的评价！");';
			if(tep_not_null($js_str)){
				$js_str = '[JS]'.preg_replace('/[[:space:]]+/',' ',$js_str).'[/JS]';
				echo db_to_html($js_str);
			}
		}
		//print_r($_GET);
		exit;
	}
}
$crumbTitle = '旅客所有咨询';
$the_title = $crumbTitle .' - '.$the_title;
$breadcrumb->add(db_to_html($crumbTitle), tep_href_link($baseUrl,'mod='.$mod.'&uid='.$uid));

$expwhere='';
if($isExpertsSelf && $action=='noaned'){
	$expwhere = " and q_answered='0'";
}elseif(!$isExpertsSelf){
	$expwhere = " and (q_answered='1' or customers_id='{$customer_id}')";
}

$query = tep_db_query('SELECT count(q_id) as rows FROM `experts_question` WHERE uid="'.$uid.'" '.$expwhere.' Order By q_id DESC ');
$query = tep_db_fetch_array($query);
$total=(int)$query["rows"];
$db_perpage = 15;
$pages=makePager($total,'page',$db_perpage,false);
$limit=" LIMIT ".($page-1)*$db_perpage.",$db_perpage;";

$query = tep_db_query('SELECT * FROM `experts_question` WHERE uid="'.$uid.'" '.$expwhere.' Order By q_id DESC '.$limit);
// 取得所有旅客咨询
$Question = array();
$loop = 0;
while($e_Question = tep_db_fetch_array($query)){
	$Question[$loop]['question'] = $e_Question;
	$Question[$loop]['question']['customers_name'] = tep_customers_name($e_Question['customers_id']);
	$Question[$loop]['question']['time'] = substr($e_Question['q_add_time'],0,16);
	$Question[$loop]['question']['customers_space_links'] = tep_href_link('individual_space.php','customers_id='.(int)$e_Question['customers_id']);
	
	//取得回复子帖
	$e_answers_sql = tep_db_query('SELECT * FROM experts_answers WHERE q_id="'.(int)$e_Question['q_id'].'" Order By a_id DESC; ');
	$lp = 0;
	while($e_answers = tep_db_fetch_array($e_answers_sql)){
		$Question[$loop]['answers'][$lp] = $e_answers;
		$Question[$loop]['answers'][$lp]['time'] = substr($e_answers['a_add_time'],0,16);
		$lp++;
	}
	$loop++;
}
?>