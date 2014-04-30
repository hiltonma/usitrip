<?php
!defined('_MODE_KEY') && exit('Access error!');
$POST = false;
if(!isset($_GET['expertsAjax']) || $_GET['expertsAjax']!='true'){
	if(!isset($_POST['jQueryAjaxPost']) || $_POST['jQueryAjaxPost']!='true'){
		!$_SERVER['HTTP_REFERER'] && $_SERVER['HTTP_REFERER'] = $baseUrl_HrefLink;
		ObHeader($_SERVER['HTTP_REFERER']);
	}
}

if(isset($_POST['jQueryAjaxPost']) && $_POST['jQueryAjaxPost']=='true'){
	$POST = true;
}
$action = $_GET['action'];
$tmod = $_GET['tmod'];
!$tmod && $tmod = 'default';

require_once($modePath.'lib/edit.class.php');//load class
$expertsAjax = new expertsAjax();

$uft8Charset=false;
if(!$isExperts){
	if($action == 'photo' && $POST){
		$utf8Charset = true;
	}
	$action = '#error#';
}

if($action == 'remarks'){
	if($POST){
		//Add_S($_POST['remarks']);
		$remarks = $_POST['remarks'];
		//ajax charset
		
		$remarks['name'] = utf8tohtml($remarks['name']);
		$remarks['sex'] = utf8tohtml($remarks['sex']);
		$remarks['remarks'] = utf8tohtml($remarks['remarks']);
		
		if(trim($remarks['name'])=='' || trim($remarks['sex'])=='' || trim($remarks['remarks'])==''){
			$expertsAjax->addData('Error',db_to_html('姓名，性别，专家简介不能为空！'));
		}else{
			$query = tep_db_get_one("SELECT uid FROM `experts_remarks` where `uid`='{$customer_id}'");
			
			$data_array = array('name'=> $remarks['name'],
								'sex'=> $remarks['sex'],
								'remarks'=> $remarks['remarks']);
			if($query){
				$data_array = html_to_db($data_array);
				tep_db_perform('experts_remarks', $data_array, 'update', 'uid='.(int)$customer_id);
			}else{
				$data_array['uid'] = (int)$customer_id;
				$data_array = html_to_db($data_array);
				tep_db_perform('experts_remarks', $data_array);
			}
			
			$expertsAjax->addData('name',tep_db_output($remarks['name']));
			$expertsAjax->addData('sex',tep_db_output($remarks['sex']));
			$expertsAjax->addData('remarks',tep_db_output($remarks['remarks']));
		}
	}else{
		$expertsAjax->addData('Title',db_to_html('编辑专家简介'));
		$sql="SELECT * FROM `experts_remarks` WHERE `uid`='{$customer_id}'";
		$remarks = tep_db_get_one($sql);
		${'checked'.($remarks['sex']=='女'?0:1)} = 'checked';
		$remarks['name'] = db_to_html($remarks['name']);
		$remarks['sex'] = db_to_html($remarks['sex']);
		$remarks['remarks'] = db_to_html($remarks['remarks']);
	}
	
}else if($action == 'photo'){//charset:utf-8
	if($POST){
		$photo = $_FILES['photo'];
		if($photo['error']==UPLOAD_ERR_OK){
			$upload_dir = DIR_PHOTOS_FS_IMAGES;
			$file_type = explode(".",$photo["name"]);
			$file_type = strtolower($file_type[count($file_type)-1]);
			$allowExts = array('gif','jpg','jpeg','png');
			if(!in_array($file_type,$allowExts)){
				$expertsAjax->addData('Error',htmltoutf8('只能上传.gif;.jpg;.jpeg;.png;的图片文件！'));
			}else{
				if(($photo["size"]/1024)>1024*2){
					$expertsAjax->addData('Error',htmltoutf8('上传文件不能超过2M！'));
				}else{
					$upload_name = 'experts_photo_'.$customer_id.'_'.time().'.'.$file_type;
					$savefilename = $upload_dir.$upload_name;
					$up_file = $photo["tmp_name"];
					if(function_exists("move_uploaded_file") && @move_uploaded_file($up_file,$savefilename)){
						@chmod($savefilename,0777);
					}elseif(@copy($up_file,$savefilename)){
						@chmod($savefilename,0777);
					}elseif(is_readable($up_file)){
						writeover($savefilename,readover($up_file));
						if(file_exists($savefilename)){
							@chmod($savefilename,0777);
						}
					}
					if(!is_file($savefilename)){
						$expertsAjax->addData('Error',htmltoutf8('照片上传失败。'));
					}else{
						$query = tep_db_get_one("SELECT uid FROM `experts_remarks` where `uid`='{$customer_id}'");
						$data_array = array('photo'=> $upload_name);
						if($query){
							$data_array = html_to_db($data_array);
							tep_db_perform('experts_remarks', $data_array, 'update', 'uid='.(int)$customer_id);
						}else{
							$data_array['uid'] = (int)$customer_id;
							$data_array = html_to_db($data_array);
							tep_db_perform('experts_remarks', $data_array);
						}
						$expertsAjax->addData('photosrc',DIR_WS_IMAGES.'photos/'.$upload_name);
						@unlink($upload_dir.$_POST['oldPhoto']);
					}
				}
			}
			@unlink($up_file);
		}else{
			$expertsAjax->addData('Error',htmltoutf8('照片上传失败。'));
		}
	}else{
		$expertsAjax->addData('Title',db_to_html('上传照片'));
		$photo = tep_db_get_one("SELECT photo FROM `experts_remarks` where `uid`='{$customer_id}'");
		$photo = $photo['photo'];
	}
}else if($action == 'add_writings_type'){
	if($POST){
		$add_writings_type = $_POST['add_writings_type'];
		$add_writings_type['name'] = utf8tohtml($add_writings_type['name']);
		if(trim($add_writings_type['name'])==''){
			$expertsAjax->addData('Error',db_to_html('请输入文章类别的名称。'));
		}else{
			$add_writings_type['group_id'] = intval($add_writings_type['group_id']);
			$data_array = array('group_id'=> $add_writings_type['group_id'],
								'name'=> $add_writings_type['name'],
								'uid'=> $customer_id);
			$data_array = html_to_db($data_array);
			tep_db_perform('experts_writings_type', $data_array);
			$tid = tep_db_insert_id();
			$expertsAjax->addData('name',tep_db_output($add_writings_type['name']));
			$expertsAjax->addData('group_id',intval($add_writings_type['group_id']));
			$expertsAjax->addData('group_name',tep_db_output($expertsWritingsGroup[intval($add_writings_type['group_id'])]['name']));
			$expertsAjax->addData('tid',intval($tid));
			$expertsAjax->addData('url',tep_href_link($baseUrl,"mod=writings&uid={$customer_id}&tid={$tid}"));
		}
	}else{
		$isWritingsTG = (isset($_GET['gid']) && is_numeric($_GET['gid']))?true:false;
		if($isWritingsTG)$gid = intval($_GET['gid']);
		$expertsAjax->addData('Title',db_to_html('新增文章类别'));
	}
}else if($action == 'edit_writings_type'){
	if($POST){
		$add_writings_type = $_POST['add_writings_type'];
		$tid = intval($add_writings_type['tid']);
		$add_writings_type['name'] = utf8tohtml($add_writings_type['name']);
		if(trim($add_writings_type['name'])==''){
			$expertsAjax->addData('Error',db_to_html('请输入文章类别的名称。'));
		}else{
			$add_writings_type['group_id'] = intval($add_writings_type['group_id']);
			$data_array = array('group_id'=> $add_writings_type['group_id'],
								'name'=> $add_writings_type['name'],
								'uid'=> $customer_id);
			$data_array = html_to_db($data_array);
			tep_db_perform('experts_writings_type', $data_array, 'update', 'tid='.$tid);
			$expertsAjax->addData('name',tep_db_output($add_writings_type['name']));
			$expertsAjax->addData('group_id',intval($add_writings_type['group_id']));
			$expertsAjax->addData('group_name',tep_db_output($expertsWritingsGroup[intval($add_writings_type['group_id'])]['name']));
			$expertsAjax->addData('tid',$tid);
			$expertsAjax->addData('url',tep_href_link($baseUrl,"mod=writings&uid={$customer_id}&tid={$tid}"));
		}
	}else{
		$expertsAjax->addData('Title',db_to_html('编辑文章类别'));
	}
}else if($action == 'del_writings_type'){
	if($POST){
		$add_writings_type = $_POST['add_writings_type'];
		$tid = intval($add_writings_type['tid']);
		$writings = tep_db_get_one("SELECT count(aid) as rows FROM `experts_writings` where `uid`='{$customer_id}' and tid='{$tid}'");
		$writings = intval($writings['rows']);
		if($writings>0){
			$expertsAjax->addData('Error',db_to_html('该类别下还有文章，您不能删除！'));
		}else{
			tep_db_query("DELETE FROM `experts_writings_type` WHERE `uid`='{$customer_id}' and `tid` = '{$tid}';");
			$expertsAjax->addData('tid',$tid);
			$expertsAjax->addData('group_id',intval($add_writings_type['group_id']));
		}
	}else{
		$expertsAjax->addData('Title',db_to_html('删除文章类别'));
		$expertsAjax->addData('Label',db_to_html('删除类别时，请确保类别下无文章。'));
	}
}else if($action == 'writings_type'){
	if($POST){
		$job = $_POST['job'];
		$gid = intval($_POST['gid']);
		!$expertsWritingsGroup[$gid] && $gid=0;
		if($job == 'move'){
			$tids = $_POST['tids'];
			foreach($tids as $tid){
				tep_db_query("UPDATE `experts_writings_type` set `group_id` = '{$gid}' WHERE `uid`='{$customer_id}' and `tid` = '{$tid}';");
			}
			$expertsAjax->addData('tids',join('/',$tids));
		}else if($job == 'update'){
			$wtname = utf8tohtml($_POST['wtname']);
			$tids = array();
			foreach($wtname as $tid=>$name){
				$tids[]=$tid;
				tep_db_query("UPDATE `experts_writings_type` set `name` = '".html_to_db($name)."' WHERE `uid`='{$customer_id}' and `tid` = '{$tid}' and `group_id` = '{$gid}';");
				$expertsAjax->addData('tid_'.$tid,$name);
			}
			$expertsAjax->addData('tids',join('/',$tids));
		}else if($job == 'del'){
			$tid = intval($_POST['tid']);
			$writings = tep_db_get_one("SELECT count(aid) as rows FROM `experts_writings` where `uid`='{$customer_id}' and tid='{$tid}'");
			$writings = intval($writings['rows']);
			if($writings>0){
				$expertsAjax->addData('Error',db_to_html('该类别下还有文章，您不能删除！'));
			}else{
				tep_db_query("DELETE FROM `experts_writings_type` WHERE `uid`='{$customer_id}' and `tid` = '{$tid}';");
				$expertsAjax->addData('tid',$tid);
			}
			$expertsAjax->addData('closepop','1');
		}
		$expertsAjax->addData('job',$job);
		$expertsAjax->addData('gid',$gid);
	}else{
		$gid = intval($_GET['gid']);
		!$expertsWritingsGroup[$gid] && $gid=0;
		$expertsAjax->addData('Title',db_to_html('管理分类'));
		$expertsAjax->addData('Label',db_to_html('删除类别时，请确保类别下无文章。'));
		
		$query = tep_db_query("SELECT * FROM `experts_writings_type` WHERE `uid`='{$customer_id}' and group_id='{$gid}' order by tid");
		while ($rt = tep_db_fetch_array($query)){
			$writings = tep_db_get_one("SELECT count(aid) as rows FROM `experts_writings` where `uid`='{$customer_id}' and tid='{$rt['tid']}'");
			$rt['writingsnum'] = intval($writings['rows']);
			$writingsType[] = $rt;
		}
	}
}else if($action == 'delwritings'){
	if($POST){
		$aid = intval($_POST['aid']);
		$tid = intval($_POST['tid']);
		tep_db_query("DELETE FROM `experts_writings` where `uid`='{$customer_id}' and aid='{$aid}' and tid='{$tid}'");
		$expertsAjax->addData('aid',$aid);
		$expertsAjax->addData('tid',$tid);
	}else{
		$aid = intval($_GET['aid']);
		$expertsAjax->addData('Title',db_to_html('删除文章'));
		$writings = tep_db_get_one("SELECT title,tid FROM `experts_writings` where `uid`='{$customer_id}' and aid='{$aid}'");
	}
}else if($action == 'add_answer'){
	if($POST){
		$error = false;
		$a_content = tep_db_prepare_input(utf8tohtml($_POST['a_content']));
		$q_id = (int)$_POST['q_id'];
		$uid = (int)$customer_id;
		
		if(!(int)$q_id){
			$error = true;
			$expertsAjax->addData('Error',db_to_html('无q_id，请刷新页面！'));
		}
		if(trim($a_content)==''){
			$error = true;
			$expertsAjax->addData('Error',db_to_html('请输入您的回复内容！'));
		}
		
		if($error == false){
			$a_content = htmlspecialchars($a_content, ENT_QUOTES);
			$a_content = preg_replace("~((http(s)?://[a-zA-Z0-9\.]*?([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}\S+)|(http(s)?://(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])\S+))~is",
									  "<a href='\\1' target='_blank'>\\1</a>",
									  $a_content);
			
			$this_time = date('Y-m-d H:i:s');
			$data_array = array('q_id'=> $q_id,
								'a_content'=> $a_content,
								'uid'=> $uid,
								'a_add_time' => $this_time,
								'a_modified_time' => $this_time);
			$data_array = html_to_db($data_array);
			tep_db_perform('experts_answers', $data_array);
			$a_id = tep_db_insert_id();
			$query = tep_db_get_one('select q_answered from experts_question where q_answered ="0" and q_id="'.$q_id.'" ');
			if($query){
				tep_db_query('update experts_question set q_answered ="1" where q_id="'.$q_id.'" ');
				$expertsAjax->addData('iscount','1');
			}
			$expertsAjax->addData('a_id',$a_id);
			$expertsAjax->addData('q_id',$q_id);
			$expertsAjax->addData('a_content',nl2br($a_content));
			$expertsAjax->addData('answer_tile',db_to_html("答："));
			$expertsAjax->addData('time',substr($this_time,0,16));
			$expertsAjax->addData('expert_name',db_to_html($expertsInfo['name']));
			$expertsAjax->addData('expert_name_url',tep_href_link($baseUrl,"uid={$uid}&mod=home"));
			$expertsAjax->addData('Useful',db_to_html("有用"));
			$expertsAjax->addData('Useless',db_to_html("没用"));
			$expertsAjax->addData('delanswers_text',db_to_html("删除"));
			$expertsAjax->addData('delanswers_href',tep_href_link($baseUrl,"uid={$uid}&mod=edit&action=delanswers&qid={$q_id}&aid={$a_id}"));
			$expertsAjax->addData('tipname',db_to_html("此回复对我"));
			
		}
	}else{
		$expertsAjax->addData('Title',db_to_html('回复'));
	}
}else if($action == 'delanswers'){
	if($POST){
		$aid = intval($_POST['aid']);
		$qid = intval($_POST['qid']);
		tep_db_query('delete From experts_answers where a_id="'.$aid.'" and q_id="'.$qid.'" and uid="'.$customer_id.'"');
		$query = tep_db_get_one('select a_id from experts_answers where q_id="'.$qid.'" and uid="'.$customer_id.'"');
		if(!$query){
			tep_db_query('update experts_question set q_answered ="0" where q_id="'.$qid.'" and uid="'.$customer_id.'"');
			$expertsAjax->addData('iscount','1');
		}
		$expertsAjax->addData('aid',$aid);
		$expertsAjax->addData('qid',$qid);
	}else{
		$aid = intval($_GET['aid']);
		$qid = intval($_GET['qid']);
		$expertsAjax->addData('Title',db_to_html('删除回复'));
	}
}else if($action == 'delquestion'){
	if($POST){
		$qid = intval($_POST['qid']);
		$query = tep_db_get_one('select q_answered from experts_question where q_id="'.$qid.'" and uid="'.$customer_id.'"');
		$expertsAjax->addData('answered',$query['q_answered']);
		tep_db_query('delete From experts_question where q_id="'.$qid.'" and uid="'.$customer_id.'"');
		tep_db_query('delete From experts_answers where q_id="'.$qid.'" and uid="'.$customer_id.'"');
		$expertsAjax->addData('qid',$qid);
	}else{
		$qid = intval($_GET['qid']);
		$expertsAjax->addData('Title',db_to_html('删除咨询'));
	}
}else{
	if($POST){
		$errorMsg = '访问失败，您还没有登陆，或者您不是专家用户！';
		$errorMsg = $utf8Charset ? htmltoutf8($errorMsg) : db_to_html($errorMsg);
		$expertsAjax->addData('Error',$errorMsg);
	}else{
		$expertsAjax->addData('Title',db_to_html('Error:'));
	}
}

if($POST){
	$expertsAjax->addData('ACTION',$action);
	$expertsAjax->addData('TMOD',$tmod);
	$expertsAjax->addData('customer_id',$customer_id);
}else{
	require(_SMARTY_ROOT_."write_smarty_vars.php");
	$expertsAjax->addData('Content',$smarty->fetch($modeHFile));
}

$expertsAjax->output();
exit;
?>