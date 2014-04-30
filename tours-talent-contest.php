<?php
/**
 * 走四方达人大赛活动首页
 * 
 * @author Administrator
 * @category Project
 * @copyright Copyright(c) 2011 
 * @version $Id$
 */
 
 define('_MODE_KEY','tours-talent-contest');
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
 /* SEO START*/
 $seoNotHtml = true;
 $the_title = $baseName.' - 去美国旅游,美国加拿大地区旅游服务专家,走四方网';
 $the_desc = '走四方网为您提供美国旅游与加拿大旅游套餐,绝对超值,游遍全美国与加拿大,我们的顾客可以享受种类繁多,内容丰富,度身为您定制的旅游套餐,以及我们优质的服务.';
 $the_key_words = $baseName.',美国旅游,加拿大旅游';
 /* SEO END */
 
 /* Mode Start */
 $mod = $_GET['mod'];
 !$mod && $mod = 'index';
 //Security restrictions. eg:mod=../../index
 !ereg('^[A-Za-z0-9_]+$',$mod) && $mod = 'default';

 $modePath = _TEP_ROOT_DIR_.'mode/'.$baseDir;
 $ckeditorLanguage = 'zh';
 if(strtolower(CHARSET)=='gb2312'){
 	$ckeditorLanguage = 'zh-cn';   
    $smarty->assign('charset','gb2312');
 }

 if(strtolower(CHARSET)=='big5'){
 	$ckeditorLanguage = 'zh-cn';
    $smarty->assign('charset','big5');
    echo "<script>location.href='tours-talent-contest.php?language=sc'</script>";
 }

 $modePFile = $modePath.$mod.'.php';
 if(!is_file($modePFile)){
 	$mod = 'index';
 	$_GET['uid'] = '';
 }
 $modePFile = $modePath.$mod.'.php';
 $modeHFile = $baseDir.$mod.'.tpl.html';

  
   

 $validation_include_js='true';	//载入表单验证的JS脚本
 $validation_div_span='span';	//信息提示的格式，默认为div，可以设置成span
 
 
 /* 模块目录 */
 $mode_path = _TEP_ROOT_DIR_.'mode/'.$baseDir;
 
 $content = 'tours-talent-contest';
 $BreadOff=true;
 $HideDateSelectLayer = true;
 $is_tours_talent = true; 
 
 $smarty->assign('severname','http://'.$_SERVER['SERVER_NAME']);
 /*取得已经登录的用户名*/
 
 if(tep_session_is_registered('customer_id') ){   
    $customer_id = $_SESSION['customer_id'];
    $smarty->assign('customer_first_name',$customer_first_name);
    $smarty->assign('customer_id',$customer_id);
    $smarty->assign('customer_id1',$customer_id);
 }
 
 $referer_url = $_SERVER['HTTP_REFERER'];
 
 require_once($modePFile);  
 
 $smarty->display($modeHFile);
 unset($smarty);
?>