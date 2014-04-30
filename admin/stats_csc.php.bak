<?php
/*
本统计表以订单为中心，可能有重复的客户产生
*/
require('includes/application_top.php');
define('HEADING_TITLE','CSC Statistics ');

if(tep_not_null($_GET['start_date'])){
	$start_date = trim(tep_db_prepare_input($_GET['start_date']));
	$end_date = trim(tep_db_prepare_input($_GET['end_date']));
}else{
	//$now = getdate(time());
	//$lastMonth = mktime(0,0,0,$now['mon'],-1,$now['year']);
	//$start_date = $now['year'].'-'.date('m',$lastMonth)."-01";
	$start_date = date('Y-m-1',time());
	$end_date = date('Y-m-d' , time());
}

$start_date_sql= $start_date." 0:0:0";
$end_date_sql = $end_date.' 23:59:59';

$startDate = strtotime($start_date_sql);
$endDate = strtotime($end_date_sql);

$start = date('Ymd',$startDate);
$end = date('Ymd',$endDate);
$dayspan = ceil(($endDate - $startDate)/(3600*24));
if($_GET['download']=='download'){
	$download = true;
}else
	$download = false; //是否下载数据
	
if($_GET['average'] == '1'){
	$average = true;
}else 
	$average = false;

//根据人员ID对数据进行划分///------------------------
function groupByPerson($rawdata){
	global $users2;
	$grouped =array();
	foreach($users2 as $id=>$name){
		$grouped[$id] = array('name'=>$name , 'userId'=>$id , 'records'=>array());
	}
	foreach($rawdata as $row){
		$row['userName'] = $users2[$row['userId']];		
		if(!isset($grouped[$row['userId']])) {
			$grouped[$row['userId']] = array('name'=>$row['userName'] ,'userId'=>$row['userId'] ,'records'=>array());
		}
		$grouped[$row['userId']]['records'][] = $row ;
	}
	return $grouped;
}
function download_excel_document($filename , $data , $heading = array()){
	header("Content-type:application/vnd.ms-excel");  
	header("Content-Disposition:filename=".$filename.".xls");  
	if(!empty($heading)){
		echo implode("\t",$heading);
		echo "\n";
	}
	foreach($data as $row){
		echo implode("\t",$row);
		echo "\n";
	}
	exit;
}

function do_average($total , $days=0){
	if($days == 0 )	$days = $GLOBALS['dayspan'];
	
	return floor((floatval($total)/$days)*100)/100;
}
///------------------------

//处理多个人员选择 BEGIN
$users = array();
$users2 = array();
if(is_array($_GET['userId']) && is_array($_GET['userName'])){	
	for($i=0; $i<count($_GET['userId']);$i++){
		$users[] = array('id'=>$_GET['userId'][$i],'name'=>$_GET['userName'][$i]);
		$users2[$_GET['userId'][$i]] = $_GET['userName'][$i];		
	}
}
$user_ids = array();
foreach($users as $u) $user_ids[] = intval($u['id']);
//处理多个人员选择 END

//
if($_GET['search_type'] == 'orders_status'){//统计订单状态
	if(in_array($_GET['order_field'],array('orders_status_id','total','updated_by')) &&in_array($_GET['order_type'],array('DESC','ASC'))){
		$orderQuery = $_GET['order_field'].' '.$_GET['order_type'];
	}else{
		$orderQuery = "";
	}
	if(empty($users)){
		//不选择人员统计订单总体更新情况
		$sql = "SELECT orders_status_id,COUNT(*) AS total  FROM  orders_status_history WHERE date_added >= '".$start_date_sql."'  AND date_added <= '".$end_date_sql."' GROUP BY orders_status_id ";	
		if($orderQuery == '') $sql.= ' ORDER BY total DESC' ;else $sql.= ' ORDER BY '.$orderQuery;
		$searchQuery = tep_db_query($sql);
		$records = array();
		while($row = tep_db_fetch_array($searchQuery)) $records[] = $row ;
		$CONTENT = 'stats_csc_order_status_overview';
		$LIST_TITLE = 'Order Status Overview';
	}else{
		//分别列印出单人的订单更新情况 并加总		
		$sql = "SELECT orders_status_id,updated_by AS userId,COUNT(*) AS total  FROM  orders_status_history WHERE updated_by IN (".implode(',',$user_ids).") AND date_added >= '".$start_date_sql."'  AND date_added <= '".$end_date_sql."' GROUP BY orders_status_id,updated_by  ";
		if($orderQuery == '') $sql.= ' ORDER BY updated_by ASC' ;else $sql.= ' ORDER BY updated_by ASC,'.$orderQuery;
		$searchQuery = tep_db_query($sql);
		$records = array();
		while($row = tep_db_fetch_array($searchQuery)) {	
			$records[] = $row ;		
		}
		$records = groupByPerson($records);
		$CONTENT = 'stats_csc_order_status_personal';
		$LIST_TITLE = 'Order Status Personal View';
	}
	//echo $sql;
}else if($_GET['search_type'] == 'qa'){ //统计QA PA LEADS 
	if(empty($user_ids)){		
		$records = array();
		//QA
		$searchQuery = tep_db_query("SELECT COUNT(*) AS total FROM statistics_qa_history  WHERE add_time >= '".$start_date_sql."'  AND add_time <= '".$end_date_sql."'");
		$row = tep_db_fetch_array($searchQuery);
		$records[] = array('statsName'=>'QA','total'=>$row['total']);
		//LEADS
		$searchQuery = tep_db_query("SELECT COUNT(*) AS total FROM tour_leads_info_answer  WHERE date_added >= '".$start_date_sql."'  AND date_added <= '".$end_date_sql."'");
		$row = tep_db_fetch_array($searchQuery);
		$records[] = array('statsName'=>'Leads','total'=>$row['total']);
		//PA
		$searchQuery = tep_db_query("SELECT COUNT(*) AS total FROM provider_order_products_status_history  WHERE provider_status_update_date >= '".$start_date_sql."'  AND provider_status_update_date <= '".$end_date_sql."'");
		$row = tep_db_fetch_array($searchQuery);
		$records[] = array('statsName'=>'PA','total'=>$row['total']);
		
		//IP Email QTB
		$searchQuery = tep_db_query("SELECT SUM(email_answer) AS total_email_answer,SUM(phone_callin) AS total_phone_callin,SUM(phone_callout) AS total_phone_callout,SUM(qtb_callin) AS total_qtb_callin,SUM(qtb_callout) AS total_qtb_callout  FROM statistics_workload   WHERE workload_date >= ".$start."  AND workload_date <= ".$end."");
			
		$row = tep_db_fetch_array($searchQuery);
		$records[] = array('statsName'=>'E-Mail Answer','total'=>$row['total_email_answer']);
		$records[] = array('statsName'=>'IP电话接听','total'=>$row['total_phone_callin']);
		$records[] = array('statsName'=>'IP电话呼出','total'=>$row['total_phone_callout']);
		$records[] = array('statsName'=>'启通宝接听','total'=>$row['total_qtb_callin']);
		$records[] = array('statsName'=>'启通宝呼出','total'=>$row['total_qtb_callout']);
		//average
		$title_extra ='';
		if($average === true){
			foreach($records as $key=>$record){
				$records [$key]['total'] = do_average($record['total']);
			}
			$title_extra = 'Average';
		}
		
		if($download === true){			
			download_excel_document('Service_Workload_Overview_'.$title_extra."_".date('Y.m.d',$startDate).'_To_'.date('Y.m.d',$endDate), $records);
		}else{
			$CONTENT = 'stats_csc_qa_pa_leads_overview';
			$LIST_TITLE = 'Service Workload Overview '.$title_extra;
		}
	}else{
		$records = array();
		foreach($users as $u){
			$records[$u['id']] =array('userName'=>$u['name'] , 'userId'=>$u['id'],
			'os'=>0,'qa'=>0,'pa'=>0,'leads'=>0,'email'=>0,'phone_callin'=>0,'phone_callout'=>0,'qtb_callin'=>0,'qtb_callout'=>0,
			'os_avg'=>0,'qa_avg'=>0,'pa_avg'=>0,'leads_avg'=>0,'email_avg'=>0,'phone_callin_avg'=>0,'phone_callout_avg'=>0,'qtb_callin_avg'=>0,'qtb_callout_avg'=>0
			);
		}
		$searchQuery = tep_db_query( "SELECT COUNT(*) AS total ,updated_by AS userId FROM  orders_status_history WHERE date_added >= '".$start_date_sql."'  AND date_added <= '".$end_date_sql."' AND  updated_by IN (".implode(',',$user_ids).") GROUP BY updated_by");
		while($row = tep_db_fetch_array($searchQuery)){
				$records[$row['userId']]['os'] = intval($row['total']);
		}
		//QA
		$searchQuery = tep_db_query("SELECT COUNT(*) AS total,operator_id AS userId FROM statistics_qa_history  WHERE add_time >= '".$start_date_sql."'  AND add_time <= '".$end_date_sql."'  AND operator_id IN (".implode(',',$user_ids).") GROUP BY operator_id");
		while($row = tep_db_fetch_array($searchQuery)){
			$records[$row['userId']]['qa'] = intval($row['total']);
		}
		//LEADS
		$searchQuery = tep_db_query("SELECT COUNT(*) AS total,modified_by AS userId FROM tour_leads_info_answer  WHERE date_added >= '".$start_date_sql."'  AND date_added <= '".$end_date_sql."'  AND modified_by IN (".implode(',',$user_ids).") GROUP BY modified_by");
		while($row = tep_db_fetch_array($searchQuery)){
			$records[$row['userId']]['leads'] = intval($row['total']);
		}
		//PA
		$searchQuery = tep_db_query("SELECT COUNT(*) AS total,popc_updated_by AS userId FROM provider_order_products_status_history  WHERE provider_status_update_date >= '".$start_date_sql."'  AND provider_status_update_date <= '".$end_date_sql."'  AND popc_updated_by IN (".implode(',',$user_ids).") GROUP BY popc_updated_by");
		while($row = tep_db_fetch_array($searchQuery)){
			$records[$row['userId']]['pa'] = intval($row['total']);
		}
		//IP Email 启通宝统计		
		$searchQuery = tep_db_query("
		SELECT SUM(email_answer) AS total_email_answer, SUM(phone_callin) AS total_phone_callin,
		SUM(phone_callout) AS total_phone_callout,SUM(qtb_callin) AS total_qtb_callin,SUM(qtb_callout) AS total_qtb_callout,
		admin_id AS userId FROM statistics_workload   WHERE workload_date >= ".$start."  AND workload_date <= ".$end."  AND admin_id IN (".implode(',',$user_ids).") GROUP BY admin_id");
		while($row = tep_db_fetch_array($searchQuery)){			
			$records[$row['userId']]['email'] = intval($row['total_email_answer']);			
			$records[$row['userId']]['phone_callin'] =  intval($row['total_phone_callin']);			
			$records[$row['userId']]['phone_callout'] =  intval($row['total_phone_callout']);			
			$records[$row['userId']]['qtb_callin'] =  intval($row['total_qtb_callin']);			
			$records[$row['userId']]['qtb_callout'] =  intval($row['total_qtb_callout']);
		}		
		//计算平均值
		$title_extra = '';
		if($average === true){
			foreach($records as $userId=>$userData){
				foreach($userData as $key=>$dataItem){
					if($key != 'userName' && $key != 'userId'){
						$records[$userId][$key] = do_average($dataItem);
					}
				}
			}
			$title_extra = 'Average';
		}	
		if($download === true){
			$heading = array('Name','OrderStatus Updates','QA','Leads' ,'PA','E-Mail Answer','IP Phone Callin','IP Phone Callout','QTB Callin','QTB Callout');
			$excelData = array();			
			foreach($records as $row){
				$excelData[] = array($row['userName'],$row['os'],$row['qa'],$row['leads'],$row['pa'],$row['email'],$row['phone_callin'],$row['phone_callout'],$row['qtb_callin'],$row['qtb_callout']);
			}		
			download_excel_document('Service_Workload_Personal_'.$title_extra.'_'.date('Y.m.d',$startDate).'_To_'.date('Y.m.d',$endDate), $excelData,$heading);
		}else{
			$CONTENT = 'stats_csc_qa_pa_leads_personal';
			$LIST_TITLE = 'Service Workload  Personal '.$title_extra;
		}
	}
}else if($_GET['search_type'] == 'report'){ //统计所有项目
		/*if(empty($user_ids)){*/
			$records = array();
			//Order
			$searchQuery = tep_db_query( "SELECT COUNT(*) AS total  FROM  orders_status_history WHERE date_added >= '".$start_date_sql."'  AND date_added <= '".$end_date_sql."'");
			$row = tep_db_fetch_array($searchQuery);
			$records[] = array('statsName'=>'Order Status','total'=>$row['total']);
			//QA
			$searchQuery = tep_db_query("SELECT COUNT(*) AS total FROM statistics_qa_history  WHERE add_time >= '".$start_date_sql."'  AND add_time <= '".$end_date_sql."'");
			$row = tep_db_fetch_array($searchQuery);
			$records[] = array('statsName'=>'QA','total'=>$row['total']);
			//LEADS
			$searchQuery = tep_db_query("SELECT COUNT(*) AS total FROM tour_leads_info_answer  WHERE date_added >= '".$start_date_sql."'  AND date_added <= '".$end_date_sql."'");
			$row = tep_db_fetch_array($searchQuery);
			$records[] = array('statsName'=>'Leads','total'=>$row['total']);
			//PA
			$searchQuery = tep_db_query("SELECT COUNT(*) AS total FROM provider_order_products_status_history  WHERE provider_status_update_date >= '".$start_date_sql."'  AND provider_status_update_date <= '".$end_date_sql."'");
			$row = tep_db_fetch_array($searchQuery);
			$records[] = array('statsName'=>'PA','total'=>$row['total']);
			//IP Email QTB
			$searchQuery = tep_db_query("SELECT SUM(email_answer) AS total_email_answer,SUM(phone_callin) AS total_phone_callin,SUM(phone_callout) AS total_phone_callout,SUM(qtb_callin) AS total_qtb_callin,SUM(qtb_callout) AS total_qtb_callout  FROM statistics_workload   WHERE workload_date >= ".$start."  AND workload_date <= ".$end."");
			
			$row = tep_db_fetch_array($searchQuery);
			$records[] = array('statsName'=>'E-Mail Answer','total'=>$row['total_email_answer']);
			$records[] = array('statsName'=>'IP电话接听','total'=>$row['total_phone_callin']);
			$records[] = array('statsName'=>'IP电话呼出','total'=>$row['total_phone_callout']);
			$records[] = array('statsName'=>'启通宝接听','total'=>$row['total_qtb_callin']);
			$records[] = array('statsName'=>'启通宝呼出','total'=>$row['total_qtb_callout']);
			//查询会计工作量状况			
			$searchQuery = tep_db_query("SELECT COUNT(*) AS total,operate_type FROM statistics_accountant_history   WHERE add_time >= '".$start_date_sql."'  AND add_time <= '".$end_date_sql."'  GROUP BY operate_type");
			while($row = tep_db_fetch_array($searchQuery)){
				if($row['operate_type'] == '1') $statsName='Charge Capture Report Updates';
				else if($row['operate_type'] == '2') $statsName='Uncharged Report Updates';
				else if($row['operate_type'] == '3') $statsName='Payment History Report (Current) Updates';						
				$records[] = array('statsName'=>$statsName,'total'=>$row['total']);
			}		
			//计算平均值
			foreach($records as $key=>$v){
				$records[$key]['avg'] = do_average($v['total']);
			}
			//查询处于各自状态的订单各有多少个
			$records2 = array();
			$searchQuery = tep_db_query("SELECT COUNT(*) AS total , orders_status FROM `orders`  WHERE date_purchased >= '".$start_date_sql."'  AND date_purchased <= '".$end_date_sql."' GROUP BY orders_status");
			while($row = tep_db_fetch_array($searchQuery)){
				$records2[] = $row;
			}
						//订单状态处理构成
			$records3 = array();
			$searchQuery = tep_db_query("SELECT orders_status_id,COUNT(*) AS total  FROM  orders_status_history WHERE date_added >= '".$start_date_sql."'  AND date_added <= '".$end_date_sql."' GROUP BY orders_status_id ");	
			while($row = tep_db_fetch_array($searchQuery)){
				$records3[] = $row;
			}
			
			$CONTENT = 'stats_csc_report_overview';
			$LIST_TITLE = '各项目操作历史总量统计';
			/*
		
		}else{			
			$records = array();
			foreach($users as $u){
				$records[$u['id']] =array('userName'=>$u['name'] , 'userId'=>$u['id'],'os'=>0,'qa'=>0,'pa'=>0,'leads'=>0);
			}
			//Order
			$searchQuery = tep_db_query( "SELECT COUNT(*) AS total ,updated_by AS userId FROM  orders_status_history WHERE date_added >= '".$start_date_sql."'  AND date_added <= '".$end_date_sql."' AND  updated_by IN (".implode(',',$user_ids).") GROUP BY updated_by");
			while($row = tep_db_fetch_array($searchQuery)){
				$records[$row['userId']]['os'] = $row['total'];
			}
			//QA
			$searchQuery = tep_db_query("SELECT COUNT(*) AS total , operator_id AS userId FROM statistics_qa_history  WHERE add_time >= '".$start_date_sql."'  AND add_time <= '".$end_date_sql."' AND operator_id IN (".implode(',',$user_ids).") GROUP BY operator_id");
			while($row = tep_db_fetch_array($searchQuery)){
				$records[$row['userId']]['qa'] = $row['total'];
			}
			//LEADS
			$searchQuery = tep_db_query("SELECT COUNT(*) AS total , modified_by AS userId FROM tour_leads_info_answer  WHERE date_added >= '".$start_date_sql."'  AND date_added <= '".$end_date_sql."' AND modified_by IN (".implode(',',$user_ids).") GROUP BY modified_by");
			while($row = tep_db_fetch_array($searchQuery)){
				$records[$row['userId']]['leads'] = $row['total'];
			}
			//PA
			$searchQuery = tep_db_query("SELECT COUNT(*) AS total,popc_updated_by AS userId FROM provider_order_products_status_history  WHERE provider_status_update_date >= '".$start_date_sql."'  AND provider_status_update_date <= '".$end_date_sql."' AND popc_updated_by IN (".implode(',',$user_ids).") GROUP BY popc_updated_by");
			while($row = tep_db_fetch_array($searchQuery)){
				$records[$row['userId']]['pa'] = $row['total'];
			}
			$CONTENT = 'stats_csc_report_personal';
			$LIST_TITLE = 'Personal View';			
		}*/
}elseif($_GET['search_type'] == 'accountant'){
	//会计工作量统计	
	if(empty($user_ids)){
		$records = array();
		$searchQuery = tep_db_query("SELECT COUNT(*) AS total,operate_type FROM statistics_accountant_history   WHERE add_time >= '".$start_date_sql."'  AND add_time <= '".$end_date_sql."'  GROUP BY operate_type");
		while($row = tep_db_fetch_array($searchQuery)){
			if($row['operate_type'] == '1') $statsName='Charge Capture Report Updates';
			else if($row['operate_type'] == '2') $statsName='Uncharged Report Updates';
			else if($row['operate_type'] == '3') $statsName='Payment History Report (Current) Updates';						
			$records[] = array('statsName'=>$statsName,'total'=>$row['total']);
		}
		//average
		$title_extra ='';
		if($average === true){
			foreach($records as $key=>$record){
				$records [$key]['total'] = do_average($record['total']);
			}
			$title_extra = 'Average';
		}
		
		if($download === true){			
			download_excel_document('Accountant_Workload_Overview_'.$title_extra."_".date('Y.m.d',$startDate).'_To_'.date('Y.m.d',$endDate), $records);
		}else{	
			$CONTENT = 'stats_csc_qa_pa_leads_overview';
			$LIST_TITLE = 'Accountant Workload '.$title_extra.' Overview';
		}
		
	}else{
		$records = array();
		foreach($users as $u){
			$records[$u['id']] =array('userName'=>$u['name'] , 'userId'=>$u['id'],'1'=>0,'2'=>0,'3'=>0,'os'=>0);
		}
		
		$searchQuery = tep_db_query( "SELECT COUNT(*) AS total ,updated_by AS userId FROM  orders_status_history WHERE date_added >= '".$start_date_sql."'  AND date_added <= '".$end_date_sql."' AND  updated_by IN (".implode(',',$user_ids).") GROUP BY updated_by");
		while($row = tep_db_fetch_array($searchQuery)){
			$records[$row['userId']]['os'] = intval($row['total']);
		}
		
		$searchQuery = tep_db_query("SELECT COUNT(*) AS total,admin_id AS userId ,operate_type FROM statistics_accountant_history   WHERE add_time >= '".$start_date_sql."'  AND add_time <= '".$end_date_sql."' AND admin_id IN (".implode(',',$user_ids).") GROUP BY admin_id,operate_type");
		while($row = tep_db_fetch_array($searchQuery)){
			$records[$row['userId']][$row['operate_type']] = $row['total'];
		}
		//计算平均值
		$title_extra = '';
		if($average === true){
			foreach($records as $userId=>$userData){
				foreach($userData as $key=>$dataItem){
					if($key != 'userName' && $key != 'userId'){
						$records[$userId][$key] = do_average($dataItem);
					}
				}
			}
			$title_extra = 'Average';
		}	
		if($download === true){
			$heading = array('Name','Charge Capture Report Updates ','Payment History Report (Current) Updates','Order Status Updates ');
			$excelData = array();			
			foreach($records as $row){
				$excelData[] = array($row['userName'],$row[1],$row[3],$row['os']);
			}		
			download_excel_document('Accountant_Workload_Personal_'.$title_extra.'_'.date('Y.m.d',$startDate).'_To_'.date('Y.m.d',$endDate), $excelData,$heading);
		}else{
			$CONTENT = 'stats_csc_accountant_personal';
			$LIST_TITLE = 'Accountant Workload '.$title_extra.' Personal  View';
		}
	}
}

?>
<?php require(DIR_FS_ADMIN . 'templates/ver1/stats_csc.tpl.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
