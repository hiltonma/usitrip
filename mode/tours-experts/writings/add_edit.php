<?php
!defined('_MODE_KEY') && exit('Access error!');
if(!$isExpertsSelf || ($action=='edit' && !$aid)){
	//ObHeader(tep_href_link($baseUrl,"mod=writings&uid={$uid}"));
	!$isExpertsSelf && showmsg('访问错误\r\n您不是该专家,您不能在这里操作！',tep_href_link($baseUrl,"mod=writings&uid={$uid}"));
	($action=='edit' && !$aid) && showmsg('访问错误\r\n您要编辑的文章不存在！',tep_href_link($baseUrl,"mod=writings&uid={$uid}"));
}
//$crumb['Url'] = tep_href_link($baseUrl,"uid={$uid}&mod=writings&action={$action}");
if($_POST['isPost']==2){
	$crumbTitle = '保存草稿';
}else{
	if($action == 'edit'){
		$crumbTitle = '编辑文章';
		//$crumb['Url'] = tep_href_link($baseUrl,"uid={$uid}&mod=writings&action={$action}&aid={$aid}");
	}else{
		$crumbTitle = '发表文章';
	}
}
$crumb['Title'] = $crumbTitle;
$crumbData[] = $crumb;


if($_POST['isPost']>=1 && $_POST['isPost']<=2){
	$writings['is_pic']=0;
	if($_POST['jQueryAjaxPost']=='true'){
		$writings = utf8tohtml($writings);
	}
	if(preg_match('~<img[^>]*?>~is',$writings['content'])){
		$writings['is_pic']=1;
	}
	$aid = intval($_POST['aid']);
	$writings['tid']=(int)$writings['tid'];
	$writings['allow_share'] = intval($writings['allow_share'])?1:0;
	$writings['title'] = htmlspecialchars($writings['title'], ENT_QUOTES);
	if($action=='add' || $_POST['isPost']!=2)$writings['time'] = 'now()';
	$writings['is_draft'] = $_POST['isPost']==2?1:0;
	if($action=="edit"){
		$writings = html_to_db($writings);
		tep_db_perform('experts_writings', $writings, 'update', 'aid='.(int)$aid.' and uid='.(int)$uid);
	}else{
		$writings['uid']=$uid;
		$writings = html_to_db($writings);
		tep_db_perform('experts_writings', $writings);
	}
	if($_POST['jQueryAjaxPost']=='true' && $_POST['isPost']==2){
		//==========================================
		$notes_content = '文章草稿已经保存成功！';
		$out_time = 3; //延迟3秒关闭
		$goto_url = '';			
		$js_str = write_success_notes($out_time, $notes_content, $gotourl);
		echo db_to_html($js_str);
		//===============================================
		exit();	
	}
	showmsg($crumbTitle.',操作成功！',tep_href_link($baseUrl,"mod=writings&uid={$uid}"));
}else{
	$aid = intval($_GET['aid']);
	//====================================================
	$writingsType = getWritingsType($customer_id);
	//====================================================
	$is_draft = false;
	$writings = array();
	if($action == 'edit'){
		$sql = "SELECT * FROM `experts_writings` WHERE uid='{$customer_id}'";
		$sql .= " and `aid` = '{$aid}'";
		$writings = tep_db_get_one($sql);
		!$writings && showmsg('访问错误\r\n您要编辑的文章不存在！',tep_href_link($baseUrl,"mod=writings&uid={$uid}"));
		
		$writings['title'] = db_to_html($writings['title']);
		$writings['content'] = db_to_html($writings['content']);
		${'checked'.$writings['allow_share']} = 'checked';
	}
	!isset($writings['allow_share']) && $checked1 = 'checked';
	($writings['is_draft']=='1') && $is_draft = true;
}
?>