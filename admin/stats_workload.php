<?php
/*
客户工作量数据输入
*/
require('includes/application_top.php');
define('HEADING_TITLE','CSC Statistics ');
define('TEXT_DISPLAY_NUMBER_OF_WORKLOAD', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> workload information)');
//------------------------------------------------------------------
function draw_service_list($name , $default , $add_param = ''){
	$query = tep_db_query('SELECT admin_id , admin_firstname,admin_lastname,admin_source FROM admin WHERE admin_groups_id IN (5,7) ORDER BY admin_source DESC , admin_firstname ASC');
	$html = '<select name="'.$name.'" '.$add_param . ' >' ;
	$html .= '<option value="0"> 选择人员 </option>';
	while($row = tep_db_fetch_array($query)){
		$color = $row['admin_source'] == 0? 'blue':'red';
		$select = $row['admin_id'] == $default? ' selected ':'';
		$html .= '<option value="'.$row['admin_id'].'" '.$select.' style="color:'. $color.'">'.ucwords($row['admin_firstname'].' '.$row['admin_lastname']).'</option>';
	}
	$html.='</select>';
	return $html;
}

function date_to_str($date){
	$dateint = intval($date);
	if($dateint < 20050000 || $dateint > 21000000){
		return '';
	}
	$datestr = strval($date);
	return substr($datestr,0,4).'-'.substr($datestr,4,2).'-'.substr($datestr,6,2);
}
function str_to_date($str){
	$str = str_replace('-','',trim($str));
	$dateint = intval($str);
	if($dateint < 20050000 || $dateint > 21000000){
		return '0';
	}else{
		return $dateint;
	}
}
//------------------------------------------------------------------

$ACTION  = tep_not_null($_GET['action'])? strval($_GET['action']):'list'  ;

 if($ACTION  == 'do_add'){ 	
 	$_POST['workload_date'] = str_to_date($_POST['workload_date']);
 	if($_POST['admin_id'] == 0  || $_POST['workload_date']== 0 ){ 		
 		$messageStack->add('You not select user or invalid date !' ,'error'); 	
 		extract($_POST);
 	}else{
 		$query = tep_db_query('SELECT *  FROM statistics_workload WHERE admin_id=\''.intval($_POST['admin_id']).'\' AND workload_date = \''.intval($_POST['workload_date']).'\'');
 		$num = tep_db_fetch_array($query);
 		if(!empty($num)){ 			
 			$messageStack->add('Duplication Workload Record ' ,'error');
 			tep_redirect(tep_href_link('stats_workload.php','action=edit&workload_id='.$num['workload_id']));
 		}else{
		 	$sql_data_array = array(
		 		'workload_date' => intval($_POST['workload_date']),
		 		'admin_id'=>intval($_POST['admin_id']),
		 		'email_answer'=>intval($_POST['email_answer']),
		 		'phone_callin'=>intval($_POST['phone_callin']),
		 		'phone_callout'=>intval($_POST['phone_callout']),
		 		'qtb_callin'=>intval($_POST['qtb_callin']),
		 		'qtb_callout'=>intval($_POST['qtb_callout']),
		 		'created_date'=>date('Y-m-d H:i:s'),
		 		'crearted_user'=>$login_id
		 	);
		 	$result = tep_db_perform('statistics_workload', $sql_data_array); 	
		 	if($result !==  false){
		 		$messageStack->add('Add Workload Record Success' ,'success');
		 	}else{
		 		$messageStack->add('Add Workload Record Fail' ,'error');
		 	}
		 	$workload_date = $sql_data_array['workload_date'];
 		}
 	} 	
}elseif($ACTION  == 'edit'){
	$id = intval($_GET['workload_id']);
	$db_query = tep_db_query("SELECT * FROM statistics_workload WHERE   workload_id = ".$id);
	$row = tep_db_fetch_array($db_query);
	if(!tep_not_null($row)){
		$messageStack->add('Record not founded!' ,'fail');
	}else{
		extract($row);
		$edit_workload_id = $row['workload_id'];
		$do_action = 'do_edit';
	}
}elseif($ACTION  == 'do_edit'){
	$sql = 'UPDATE statistics_workload SET email_answer='.intval($_POST['email_answer'])
	.', phone_callin='.intval($_POST['phone_callin'])
	.', phone_callout='.intval($_POST['phone_callout'])
	.', qtb_callin='.intval($_POST['qtb_callin'])
	.', qtb_callout='.intval($_POST['qtb_callout'])
	.',last_modified_user='.$login_id
	.',last_modified_date = \''.date('Y-m-d H:i:s').'\''
	.' WHERE workload_id = '.intval($_POST['workload_id']);
	tep_db_query($sql);
	$messageStack->add('Record updated!' ,'success');
	
}elseif($ACTION  == 'delete'){
	$id = intval($_GET['workload_id']);
	$result = tep_db_query('DELETE FROM  statistics_workload WHERE workload_id = \''.$id.'\'');
	if($result !== false){
		$messageStack->add('Delete Workload Record Success' ,'success');
	}
}

if(!tep_not_null($do_action))$do_action = 'do_add';
if(!tep_not_null($workload_date))$workload_date  =  date('Ymd');
if(!tep_not_null($admin_id))$admin_id  =  $login_id;
if(!tep_not_null($email_answer)) $email_answer=0;
if(!tep_not_null($phone_callin))$phone_callin=0;
if(!tep_not_null($phone_callout))$phone_callout=0;
if(!tep_not_null($qtb_callin))$qtb_callin=0;
if(!tep_not_null($qtb_callout))$qtb_callout=0;



$query_row = "select * from statistics_workload  WHERE 1 order by workload_date DESC, admin_id ASC";
$workload_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $query_row, $query_numrow);
$workload_query = tep_db_query($query_row);
$workloadinfo_list = array();
while ($info = tep_db_fetch_array($workload_query)) {
	$workloadinfo_list[] = $info;
}

include(DIR_FS_ADMIN . 'templates/ver1/stats_workload.tpl.php');


require(DIR_WS_INCLUDES . 'application_bottom.php'); 

?>