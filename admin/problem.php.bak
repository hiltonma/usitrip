<?php
/**
 * (商务中心)常见问题
 * @author wtj
 */
require ('includes/application_top.php');

require 'includes/classes/CommonProblem.class.php';
$problem = new CommonProblem();

if($_POST['action']){
	switch ($_POST['action']){
		case 'add_question_score':		//给提问者加1分
			if($problem->addQuestionScore($_POST['admin_id'], $_POST['score'], $_POST['problem_id'])){
				echo 'OK';
			}
		break;
		case 'add_sub_answer_score':	//给回答人加、减分
			if($problem->addAnswerScore($_POST['admin_id'], $_POST['score'], $_POST['answer_id'])){
				echo 'OK';
			}
		break;
		default:
			die($_POST['action']);
		break;		
	}
	exit;	//请勿删除此行代码！若疑问请找zhh
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>" />
<title>常见问答</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<link type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css" />
<link rel="stylesheet" type="text/css" href="includes/jquery-1.3.2/jquery_ui.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/javascript/add_global.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.9.1.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery.nyroModal-1.6.2.js" ></script>

<script type="text/javascript" src="includes/jquery-1.3.2/jquery-ui.js" ></script>
<style type="text/css">
#connter {
	width: 960px;
	margin: 0 auto;
}

table {
	border-collapse: collapse;
	border-spacing: 0;
}

table th,#TableList td {
	border: 1px solid #DCDCDC;
	background: #eee;
	line-height: 25px;
}

#TableList td {
	background: #fff;
	text-align: center;
}
.tr1{
	background: #DCDCDC ;
}
.contentTable,.contentTable td{ 
pading:5px; border:1px solid #DCDCDC; border-bottom-color:#666; border-right-color:#666;border-left-color:#666; width:300px; border-spacing:0;
}
.ui-helper-hidden-accessible{display:none}
</style>
<script type="text/javascript">
function addToTxt(value){
var doc=document.getElementById('agency_name');
if(doc.value!=''){
doc.value+=','+value;
}else{
doc.value=value;
}

}
$(function() {
	var availableTags =
<?php echo urldecode (json_encode($problem->getAgencySimple()));?>;
	$( "#agency_name" ).autocomplete({
		source: availableTags
	});
});
</script>
</head>

<body>
<?php

switch ($_GET[action]) {
	case 'show_add' :
		$str_tmp='<select onchange="addToTxt(this.value)"><option value="">选择地接商</option>';
		$agency_arr=$problem->getAgency();
		foreach($agency_arr as $value){
			if($value['id']!='')
			$str_tmp.='<option value="'.$value['id'].'">'.$value['id'].'</option>';
		}
		$str_tmp.='</select>';
		$str_type='<select name="problem_type" >';
		foreach($problem->getProblemType() as $key=>$value){
			$str_type.="<option value='$key'>$value</option>";
		}
		$str_type.='</option>';
		print <<<EOF
<form method="post" action="?action=add_problem" name="addProblem">
	<table class="contentTable" align="center">
		<tr>
			<td>提问内容:</td>
			<td><textarea name="content" cols="32" rows="5"></textarea></td>
		</tr>
		<tr>
		<td>$str_tmp</td>
		<td><input type="text" name="agency_name" id="agency_name" /></td>
		</tr>
		<tr>
		<td>旅游团号</td>
		<td><input type="text" name="travel_code" id="travel_code" /></td>
		</tr>
		<tr>
		<td>问题类型</td>
		<td>$str_type</td>
		</tr>
		<tr>
			<td><input type="button" onclick="location.href='$_SESSION[problem_url]'" value="返回" /></td>
			<td><input type="submit" value="确定" onclick="if(jQuery('#agency_name').val()==''){alert('供应商不能为空');return false;}else{return ture;}"/></td>
		</tr>
	</table>
</form>
</body>
</html>
EOF;
		require (DIR_WS_INCLUDES . 'application_bottom.php');
		exit();
		break;
	case 'show_change' :
		$info=$problem->getList($_GET['id']);
		$info=$info[0];
		$answer=$problem->getOneAnswer($_GET['id']);
		$str_tmp='';
		foreach($answer as $an){
		$str_tmp.='<br />'.$an['content'].'<br /><br />'.$an['two_time'].'-----'.$an['answer_ower_id'].'<br />';
		}
		print <<<EOF
<form method="post" action="?action=change&&id=$_GET[id]" name="addProblem">
	<table class="contentTable" align="center">
		<tr>
			<td>提问内容:</td>
			<td><textarea name="content" cols="32" rows="5">$info[problem_content]</textarea></td>
		</tr>
		<tr>
			<td>提问时间:</td>
			<td>$info[one_time]</td>
		</tr>
		<tr>
			<td>提问人:</td>
			<td>$info[admin_job_number]</td>
		</tr>
		<tr>
			<td>回答:</td>
			<td>$str_tmp</td>
		</tr>
		<tr>
			<td><input type="button" onclick="location.href='$_SESSION[problem_url]'" value="返回" /></td>
			<td><input type="submit" value="确定" /></td>
		</tr>
	</table>
</form>
</body>
</html>
EOF;
		require (DIR_WS_INCLUDES . 'application_bottom.php');
		exit();
		break;
	case 'go_answer' :
// 		print_r($_SESSION);
		$info=$problem->getList($_GET['id']);
		$info=$info[0];
		print <<<EOF
<form method="post" action="?action=add_answer&&id=$_GET[id]" name="addProblem">
	<table class="contentTable" align="center">
		<tr>
			<td>提问内容:</td>
			<td>$info[problem_content]</td>
		</tr>
		<tr>
			<td>提问时间:</td>
			<td>$info[one_time]</td>
		</tr>
		<tr>
			<td>提问人:</td>
			<td>$info[admin_job_number]</td>
		</tr>
		<tr>
			<td>回答:</td>
			<td><textarea name="content" cols="32" rows="5">$info[content]</textarea></td>
		</tr>
		<tr>
			<td><input type="button" onclick="location.href='$_SESSION[problem_url]'" value="返回" /></td>
			<td><input type="submit" value="确定" /></td>
		</tr>
	</table>
</form>
</body>
</html>
EOF;
		require (DIR_WS_INCLUDES . 'application_bottom.php');
		exit();
		break;
	case 'change_answer':
		$answer_info=$problem->getAnswerByProblem2($_GET['id']);
		$show='';
		foreach($answer_info as $key=>$v){
			$show.='<tr><td>
<textarea name="answer_content['.$key.']" cols="50" rows="5">'.$v['content'].'
</textarea>
<input type="hidden" name="answer_id['.$key.']" value="'.$v['answer_id'].'"/></td><td>'.$v['ower_id'].'</td><td>'.$v['add_time'].'</td></tr>';
		}
		print <<<EOF
<form method="post" action="problem.php?action=go_change_answer&id=$_GET[id]">
	<table  align="center" width="60%">
		<tr>
			<th>回答内容:</th>
			<th>回答人</th>
			<th>回答时间</th>
		</tr>
		$show;
		<tr>
			<td><input type="button" onclick="location.href='$_SESSION[problem_url]'" value="返回" /></td>
			<td><input type="submit" value="确定" /></td>
		</tr>
	</table>
</form>
</body>
</html>
EOF;
		require (DIR_WS_INCLUDES . 'application_bottom.php');
		exit();
	case 'add_problem' :
		$problem->addProblem($_POST['content'], $_SESSION['login_id'],$_POST['agency_name'],$_POST['travel_code'],$_POST['problem_type']);
		header('Location: ' . $_SESSION['problem_url']);
		exit();
		break;
	case 'add_answer' :
		$problem->addAnswer($_GET['id'], $_POST['content'], $_SESSION['login_id']);
		header('Location: '.$_SESSION['problem_url']);
		exit();
		break;
	case 'change' :
		$problem->changeProblem($_GET['id'],$_POST['content']);
		header('Location: '.$_SESSION['problem_url']);
		exit();
		break;
	case 'change_status' :
		$problem->changeOneColumn('internal_question', $_GET['id'], 'status', $_GET['status'], 'problem_id');
		$problem->changeOneColumn('internal_question', $_GET['id'], 'coss_time', date('Y-m-d H:i:s'), 'problem_id');
		exit();
		break;
	case 'change_im':
		$problem->changeOneColumn('internal_question', $_GET['id'], 'is_important', $_GET['im'], 'problem_id');
		exit();
		break;
	case 'go_change_answer':
		$problem->changeAnswer($_POST,$_SESSION['login_id'],$_GET['id']);
		header('Location: '.$_SESSION['problem_url']);
		break;
		
}
$up_name = isset($_GET['upname']) ? $_GET['upname'] : '';
$b_time = isset($_GET['b_time']) ? $_GET['b_time'] : '';
$e_time = isset($_GET['e_time']) ? $_GET['e_time'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$point_value = isset($_GET['point']) ? $_GET['point'] : '';
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$agency_name=isset($_GET['agency_name'])?$_GET['agency_name']:'';
$problem_type=isset($_GET['problem_type'])?$_GET['problem_type']:0;
$important_select=isset($_GET['important_select'])?$_GET['important_select']:'';
$travel_code=isset($_GET['travel_code'])?$_GET['travel_code']:'';
$data = array();
$status_array = $problem->getStatusArray();
$data = $problem->getList('',$up_name, $b_time, $e_time, $status, $point_value, $page,$important_select,$agency_name);
$problem_url = $_SERVER['REQUEST_URI'];
// tep_session_register('problem_url');
// $_SESSION['abc'] = $problem_url;
$_SESSION['problem_url']=$_SERVER['REQUEST_URI'];
// print_r($_SESSION);
//echo $_SEESION['problem_url'];
?>

<?php
require (DIR_WS_INCLUDES . 'header.php');
if ($messageStack->size > 0) {
	echo $messageStack->output();
}
?>
<h1>(商务中心)常见问题</h1>
<br /><br />
<?php echo $problem->getAgencyNumber();?>
<br /><br />
<form  action="" name="serach" method="get">
		<table width="100%" border="1">
			<tr>
				<td>提交人编号：</td>
				<td><input type="text" name="upname" value="<?=$up_name?>" /></td>
				<td>提交时间：</td>
				<td><input class="textTime" type="text" name="b_time" value="<?=$b_time?>" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"/></td>
				<td>--</td>
				<td><input class="textTime" type="text" name="e_time" value="<?=$e_time?>" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"/></td>
				<td>问题类别:</td>
				<td>
				<select name="problem_type">
				<?php foreach($problem->getProblemType() as $key=>$value){
					echo '<option value="'.$key.'" ',$key==$problem_type?'selected':'','>',$value,'</option>';
				}?>
				</select>
				</td>
				<td>状态:</td>
				<td>
					<select name="status">
					<option value='-1' <?php if($status=='-1') echo 'selected';?> >==ALL==</option>
						<?php foreach($status_array as $key=>$value){?>
						<option <?php if($key===$status) echo 'selected'?> value="<?=$key?>"><?=$value?></option>
						<?php }?>
					</select>
				</td>
			</tr>
			<tr>
				<td>关键字:</td>
				<td><input type="text" name="point" value="" /></td>
				<td>相关地接商编号:</td>
				<td>
				<input type="text" id="agency_name" name="agency_name" value="<?php echo $agency_name;?>"/>
				</td>
				<td>重要度：<select name="important_select">
      <option value="">请选择重要与否</option>
      <?php foreach($problem->getImprotant() as $key=>$value){?>
      <option value="<?=$key?>" style="color:<?=$value['color']?>" <?php if($key==$important_select&&$important_select!='') echo 'selected';?>>
        <?=$value['text']?>
        </option>
      <?php }?>
    </select></td>
				
				<td>旅行团号：</td>
				<td><input type="text" name="travel_code" value="<?=$travel_code?>"/></td>
				<td><input type="submit" value="SEARCH" /></td>
				<td><input type="button" value="添加"
					onclick="location.href='/admin/problem.php?action=show_add'" /></td>
				<td><input type="button" value="清空搜索结果"
					onclick="location.href='/admin/problem.php'" /></td>
				
			</tr>
		</table>
</form>
	<br />
	<br />
	<table width="100%" border="1">
		<tr>
			<th>编号</th>
			<th>提交时间</th>
			<th>提交人编号</th>
			<th>地接商编号</th>
			<th>问题类型</th>
			<th>旅游团号</th>
			<th>问题/<font color="#FF0000">回答</font></th>
			<th>回答内容</th>
			<th>状态</th>
		</tr>
		
  <?php $i=0;$problem_type_array=$problem->getProblemType();unset($problem_type_array[0]);foreach($data as $value){ $i++;?>
  <tr <?php if ($i%2==0) echo 'class="tr1"';?>>
    <td><?=$value['problem_id']?></td>
			<td><?=$value['one_time']?></td>
			<td>
			<?=tep_get_job_number_from_admin_id($value['ower_id'])?>
			<?php if($QuestionScore = $problem->getQuestionScore((int)$value['problem_id'])){
				echo ' <b>+'.$QuestionScore.'积分</b>';
			}elseif($can_add_problem_score === true){?>
			<button id="question_score_btn_<?= (int)$value['problem_id']?>" type="button" onclick="add_question_score(<?= (int)$value['ower_id']?>, <?= (int)$value['problem_id']?>);" >有效</button>
			<?php }?>
			</td>
			<td><?=$value['angency_id']?></td>
			<td><?=$problem_type_array[$value['problem_type']]?></td>
			<td><?php $product_id=$problem->getProductId($value['travel_code']);if($product_id){echo '<a target="_blank" href="../product_info.php?products_id='.$product_id['products_id'].'">'.$value['travel_code'].'</a>';}else{echo $value['travel_code'];}?></td>
			<td><?= nl2br($value['problem_content'])?>  <?php $answer_info=$problem->getAnswerByProblem($value['problem_id']);if(!$answer_info){
			?><a href="/admin/problem.php?action=go_answer&&id=<?=$value['problem_id']?>"><font color="#FF0000">【回答】</font></a><?php }if($can_edit_problem){?> &nbsp;&nbsp;<a href="/admin/problem.php?action=show_change&&id=<?=$value['problem_id']?>"><font color="#FF0000">【编辑】</font></a><?php }?></td>
			<td>
			<?php 
			if($AnswerScore = $problem->getAnswerScore((int)$v['answer_id'])){
					echo '<b>'.$AnswerScore.'积分</b><br>';
				}elseif($can_add_problem_answer_score === true&&$answer_info){?>
				<div>
				<select id="answerScoreSelect_<?= (int)$v['answer_id']?>">
				<option value="0">奖、惩</option>
				<option value="1">+1分</option>
				<option value="-1">-1分</option>
				</select>
				<button id="answerScoreBtn_<?= (int)$v['answer_id']?>" onclick="add_sub_answer_score(<?= (int)$v['ower_id']?>, <?= (int)$v['answer_id']?>);" type="button">OK</button>
				</div>
			<?php }
			foreach($answer_info as $v){?>
			<?php 
				echo '<br /><br />';
				echo nl2br($v['content'].'-----'.tep_get_job_number_from_admin_id($v['ower_id']));
				
			}
			if($answer_info)echo '<br><a href="/admin/problem.php?action=change_answer&&id='.$value['problem_id'].'"><font color="#FF0000">【编辑】</font></a>';
			?>
			
			</div>
			</td>
			<td>
			<nobr>
			<span id="status_text_<?=$value['problem_id']?>"><?=$status_array[$value['status']]?></span><?php if($value['coss_time']!='0000-00-00 00:00:00')echo '<br />',$value['coss_time'],'<br />';?>
	<select id="status_select_<?=$value['problem_id']?>">
					<option>请选择状态</option>
	<?php foreach($status_array as $key=>$status){?>
	<option value="<?=$key?>"><?=$status?></option>
	<?php }?>
	</select>
	<?php if($can_edit_problem_status){?><input type="button" value="update"
				onclick="changeStatus(<?=$value['problem_id']?>)" /><?php }?>
	<span id="im_txt_<?=$value['problem_id']?>"><?php 
	$important=$problem->getImprotant(); echo '<font color="',$important[$value['is_important']]['color'],'">',$important[$value['is_important']]['text'],'</font>'?></span>
	<select name="select" id="im_se_<?=$value['problem_id']?>">
      <option value="">请选择重要与否</option>
      <?php foreach($problem->getImprotant() as $k=>$v){?> 
      <option value="<?=$k?>" style="color:<?=$v['color']?>">
        <?=$v['text']?>
        </option>
      <?php }?>
    </select>
	<input type="button" value="update" onclick="changeIm(<?=$value['problem_id']?>)" />
	</nobr>
	</td>
  </tr>
  <?php }?>
  
  <tr>
  <td colspan="7" align="right"><?php echo '--第',$page,'页--',$problem->createPage($_SERVER['REQUEST_URI'])?></td>
  </tr>
	</table>
	<script language="javascript" type="text/javascript">
function changeStatus(id){
var myid=document.getElementById('status_select_'+id);

$.ajax({
    url: '/admin/problem.php?action=change_status&id='+id+'&status='+myid.value,
    type: 'GET',
    timeout: 1000,
    error: function(){
        alert('wait !!!');
    },
    success: function(xml){
        document.getElementById('status_text_'+id).innerHTML='<font color="red">'+myid.options[myid.selectedIndex].text+'</font>';
    }
});
}
function changeIm(id){
var myid=document.getElementById('im_se_'+id);

$.ajax({
    url: '/admin/problem.php?action=change_im&id='+id+'&im='+myid.value,
    type: 'GET',
    timeout: 1000,
    error: function(){
        alert('wait !!!');
    },
    success: function(xml){
        document.getElementById('im_txt_'+id).innerHTML='<font color="red">'+myid.options[myid.selectedIndex].text+'</font>';
    }
});
}

//给提问者加1分
function add_question_score(admin_id, problem_id){
	var score = 1;
	var btnId = '#question_score_btn_' + problem_id;
	$(btnId).attr('disabled','disabled').text('提交中，请稍候……');
	$.post('problem.php',{ 'action':"add_question_score", 'problem_id': problem_id, 'admin_id' : admin_id, 'score' : score}, function(t){
		if(t=='OK'){
			$(btnId).text(score + '积分添加成功！');
		}else{
			alert('出错了，找技术部去！');
		}
	}, 'text');
}
//给回复者加、减分
function add_sub_answer_score(admin_id, answer_id){
	var score = $('#answerScoreSelect_' + answer_id).val();
	var btnId = '#answerScoreBtn_' + answer_id;
	if(score != '1' && score != '-1'){
		alert('奖还是惩？请选一个！');
		return false;
	}
	$(btnId).attr('disabled','disabled').text('提交中，稍候……');
	$.post('problem.php',{ 'action':"add_sub_answer_score", 'answer_id': answer_id, 'admin_id' : admin_id, 'score' : score}, function(t){
		if(t=='OK'){
			$(btnId).text(score + '积分添加成功！');
		}else{
			alert('出错了，找技术部去！');
		}
	}, 'text');
}
</script>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>