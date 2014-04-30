<?php
//define main mode
define('_MODE_KEY','tours-experts');
if($_GET['mod'] == 'edit' || isset($_GET['ajax']) || isset($_POST['ajax']) || isset($_POST['jQueryAjaxPost'])){
	define('AJAX_MOD',1);	
}
require_once('includes/application_top.php');
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

require_once(DIR_FS_FUNCTIONS . 'dleno.function.php');

$BreadOff = true;
$content = _MODE_KEY;
$baseName = '走四方旅行专家';
$baseUrl = _MODE_KEY.'.php';
$baseDir = _MODE_KEY.'/';
$baseUrl_HrefLink = tep_href_link($baseUrl);
$breadcrumb->add(db_to_html($baseName), $baseUrl_HrefLink);

$expertsWritingsGroup = array();
$expertsWritingsGroup[0] = array('id'=>0,'name'=>db_to_html('你如果想去以下景点，就找她为你解惑吧...'));
$expertsWritingsGroup[1] = array('id'=>1,'name'=>db_to_html('同时也是这些方面的专家...'));

$expertSlefWritingsGroup = array();
$expertSlefWritingsGroup[0] = array('id'=>0,'name'=>db_to_html('我所擅长的景点和区域...'));
$expertSlefWritingsGroup[1] = array('id'=>1,'name'=>db_to_html('我同时也是这些方面的专家...'));
//=====================================================================================
$affiliateStatusAllow = array('100006');//,'100027'
//=======seo start=====================================================================
$seoNotHtml = true;
$the_title = $baseName.' - 去美国旅游,美国加拿大地区旅游服务专家,走四方网';
$the_desc = '走四方网为您提供美国旅游与加拿大旅游套餐,绝对超值,游遍全美国与加拿大,我们的顾客可以享受种类繁多,内容丰富,度身为您定制的旅游套餐,以及我们优质的服务.';
$the_key_words = $baseName.',美国旅游,加拿大旅游';
//============is experts start  =======================================================
$isExperts = ($customers_group=='1' && $customer_id)?true:false;
//============Mode Start===============================================================
$mod = $_GET['mod'];
!$mod && $mod = 'default';

//Security restrictions. eg:mod=../../index
!ereg('^[A-Za-z0-9_]+$',$mod) && $mod = 'default';

$modePath = _TEP_ROOT_DIR_.'mode/'.$baseDir;

require_once($modePath.'lib/function.php');//load function

$ckeditorLanguage = 'zh';
if(strtolower(CHARSET)=='gb2312'){
	$ckeditorLanguage = 'zh-cn';
}
$modePFile = $modePath.$mod.'.php';
if(!is_file($modePFile)){
	$mod = 'default';
	$_GET['uid'] = '';
}
$modePFile = $modePath.$mod.'.php';
$modeHFile = $baseDir.$mod.'.tpl.htm';



//=======================Howard codes start
$uid = NULL;
//取得专家信息
if(tep_not_null($_GET['uid']) ){
	$uid = (int)$_GET['uid'];
	//get experts info
	$experts_row = tep_db_get_one('SELECT e.*,c.customers_group FROM `experts_remarks` e,customers c 
								WHERE e.uid = '.(int)$uid.' and e.uid = c.customers_id and c.customers_group="1" LIMIT 1 ');
	if(!(int)$experts_row['uid']){
		//$mod = 'default';
		//$uid = NULL;
		if($_GET['mod'] != 'edit'){
			$msg = '访问错误\r\n您访问的专家不存在！';
			$customer_id==$uid && $msg = '访问错误\r\n您现在还不是专家！\r\n或者您还没有填写您的专家信息！！';
			showmsg($msg,$baseUrl_HrefLink);
		}
	}else{
		$expertsInfo = $experts_row;
		/*$expertsInfo['sex'] = tep_db_output($experts_row['sex']);
		$expertsInfo['name'] = tep_db_output($experts_row['name']);
		$expertsInfo['remarks'] = tep_db_output($experts_row['remarks']);*/
		$expertsInfo['recom'] = intval($experts_row['recom']);
		$expertsInfo['photo'] = getPhoto($experts_row['photo']);
		$breadcrumb->add(db_to_html($expertsInfo['name'].'的主页'), tep_href_link($baseUrl,'mod=home&uid='.$uid));
		$the_title = $expertsInfo['name'].'的主页 - '.$the_title;
	}
	//print_r($expertsInfo);
}

$validation_include_js='true';	//载入表单验证的JS脚本
$validation_div_span='span';	//信息提示的格式，默认为div，可以设置成span

//=======================Howard codes end
//=====================is experts self==============================================
$isExpertsSelf = ($isExperts && $customer_id==$uid)?true:false;
$_accUploadCkeditor = 'deny';
if($isExpertsSelf){
	$_accUploadCkeditor='allow';
}
tep_session_register('_accUploadCkeditor');
//==================================================================================
$user_public_tpl = $baseDir.'user_public.tpl.htm';

require_once($modePFile);
//==============breadcrumb start=======================================================
$category_breadcrumb = '';
for ($ib=0, $nb=sizeof($breadcrumb->_trail); $ib<$nb; $ib++){
	$link_class = '';
	if(($nb-1) != $ib){
		$link_class = ' class="breadlink_gray"';
		$category_breadcrumb .= '&nbsp;<a href="' . $breadcrumb->_trail[$ib]['link'] . '" '.$link_class.'>' . cut_cnstr($breadcrumb->_trail[$ib]['title'],50) . '</a>&nbsp;';
		$category_breadcrumb .= '&gt;';
	}else{
		$category_breadcrumb .=  '&nbsp;<span title="' . trim($breadcrumb->_trail[$ib]['title']) . '">' . cut_cnstr($breadcrumb->_trail[$ib]['title'],50) . '</span>';
	}
}
//=====================================================================================

$the_title = db_to_html($the_title);
$the_desc = db_to_html($the_desc);
$the_key_words = db_to_html($the_key_words);

require_once(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require_once(DIR_FS_INCLUDES . 'application_bottom.php');
?>