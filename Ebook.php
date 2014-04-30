<?php
require_once('includes/application_top.php');
define('ONLY_USE_NEW_CSS', true);

$the_title = db_to_html('走四方网美国出行指南 - 走四方网');
$breadcrumb->add(db_to_html('《走四方网美国出行指南》'), tep_href_link('Ebook.php'));

$download_file_name_exe = 'download/exe.guidebook_to_united_states';
$download_file_name_pdf = 'download/pdf.guidebook_to_united_states';
$download_file_name_chm = 'download/chm.guidebook_to_united_states';
$download_file_name_umd = 'download/umd.guidebook_to_united_states';
$style1_01 = 'style1_01';
$style1_02 = 'style1_02';
$style1_03 = 'style1_03';
if(strtolower(CHARSET)=='big5'){
	//$download_file_name = 'download/guidebook_to_united_states';
	$style1_01 .= '_ft';
	$style1_02 .= '_ft';
	$style1_03 .= '_ft';
}

$content = 'ebook';
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');

/* 以下内容属于旧的ebook，目前已经被取消 Howard
//$language = $_GET['language'] = $HTTP_GET_VARS['language'] = 'sc';
define('_MODE_KEY','Ebook');
require_once('includes/application_top.php');
require_once(DIR_FS_FUNCTIONS . 'dleno.function.php');
$Title = '走四方网美国出行指南';
$No = $_GET['No'];
!ereg('^[A-Za-z0-9_]+$',$No) && $No = 'new';

$NoRoot = '_'._MODE_KEY.'/'.$No.'/';

if(!is_dir(_TEP_ROOT_DIR_.$NoRoot)){
	$No = 'new';
	$NoRoot = '_'._MODE_KEY.'/'.$No.'/';
}
$WEBROOT = HTTP_SERVER.'/'.$NoRoot;
$NoHtmlDir = _TEP_ROOT_DIR_.$NoRoot;

$downloadUrl = tep_href_link(_MODE_KEY.'.php','download');
$Content = readover($NoHtmlDir.'#index.html');
preg_match('~<No>(.+)</No>~is',$Content, $matches);
$No = trim($matches[1]);
$Content = str_replace("<No>$No</No>",'',$Content);
preg_match('~<title>(.+)</title>~is',$Content, $matches);
$Title = $matches[1].' - '.$Title;
$Content = str_replace("<title>".$matches[1]."</title>",'',$Content);

is_numeric($No) && $Title = '第 '.$No.' 期 - '.$Title;


if(CHARSET == 'big5'){
	$Content = str_replace('cn.usitrip.com','208.109.123.18',$Content);
	
	$Content = str_replace('"images/','"'.$WEBROOT.'images_ft/',$Content);
	$Content = str_replace('\'images/','\''.$WEBROOT.'images_ft/',$Content);
	$Content = str_replace(':images/',':'.$WEBROOT.'images_ft/',$Content);
}else{
	$Content = str_replace('208.109.123.18','cn.usitrip.com',$Content);
	
	$Content = str_replace('"images/','"'.$WEBROOT.'images/',$Content);
	$Content = str_replace('\'images/','\''.$WEBROOT.'images/',$Content);
	$Content = str_replace(':images/',':'.$WEBROOT.'images/',$Content);
}
$Content = str_replace('"css/','"'.$WEBROOT.'css/',$Content);
$Content = str_replace('\'css/','\''.$WEBROOT.'css/',$Content);

//require(_SMARTY_ROOT_."write_smarty_vars.php");
//$smarty->display(_MODE_KEY . '.tpl.htm');
*/

?>