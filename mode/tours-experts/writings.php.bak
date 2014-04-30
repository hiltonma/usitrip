<?php
!defined('_MODE_KEY') && exit('Access error!');
if(!$uid){
	ObHeader($baseUrl_HrefLink);
}
$crumbData = array();
$action = $_GET['action'];
!$action && $action = 'default';
//Security restrictions. eg:action=../../index
!ereg('^[A-Za-z0-9_]+$',$action) && $action = 'default';
$actionPath = $modePath.$mod.'/';

$loadAction = $action;
if($action=='add' || $action=='edit'){
	$loadAction = 'add_edit';
}

$actionPFile = $actionPath.$loadAction.'.php';
if(!is_file($actionPFile)){
	$loadAction = 'default';
}
$actionPFile = $actionPath.$loadAction.'.php';
$actionHFile = _MODE_KEY.'/'.$mod.'/'.$loadAction.'.tpl.htm';
//===================================================
$crumbTitle = 'кЫспндуб';
$crumb['Url'] = tep_href_link($baseUrl,"uid={$uid}&mod=writings");

$crumb['Title'] = $crumbTitle;
$crumbData[] = $crumb;
//===================================================
require_once($actionPFile);

foreach($crumbData as $crumb){
	!$crumb['NoSeo'] && $the_title = $crumb['Title'].' - '.$the_title;
	!$crumb['Url'] && $crumb['Url'] = 'javascript:;';
	$breadcrumb->add(db_to_html($crumb['Title']),$crumb['Url']);
}
?>