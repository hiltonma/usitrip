<?php
/**
 * 服务端运行的Cron脚本，每天执行一次。If you will edit the file, Please contact Howard.
 * @author Howard
 * @modify by Howard at 2011-06-18 00:35:03
 */
if($_SERVER['SERVER_PROTOCOL']=="HTTP/1.1"){
}else{
	$smsajax = true;	
}

if($_SERVER['SERVER_PROTOCOL']=="HTTP/1.1"){
	header('Content-type: text/plain');
}

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/configure_local.php')){
	  include('includes/configure_local.php');
  }elseif(file_exists('includes/local/configure.php')){
	  include('includes/local/configure.php');
  }else{
// include server parameters
	include('includes/configure.php');
  }


$start_time0 = microtime(true);
ini_set('display_errors', '1'); 
ini_set('auto_detect_line_endings', 'On'); 
ini_set('memory_limit','256M');
ini_set("max_execution_time", 3600); //有效期为1个小时
set_time_limit(0);
error_reporting(E_ALL & ~E_NOTICE);


$link0 = new mysqli(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);	//网站数据库
$link1 = new mysqli(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, 'system_logs');	//系统日志数据库

$today = date("Y-m-d");
$totime = date("H:i:s");

$echo_str = "";
$echo_str.=$today." ".$totime."--------------".__FILE__."\n";

	// 删除今天以前的已售完的日期 Delete old date for soldout dates {-----------------------------------------------
	$qry_remove_sold_dates = "DELETE FROM products_soldout_dates WHERE DATE_FORMAT(products_soldout_date, '%Y-%m-%d') <= DATE_FORMAT('".$today."', '%Y-%m-%d')";
	$res_remove_sold_dates = $link0->query($qry_remove_sold_dates);
	$link0->query('OPTIMIZE TABLE products_soldout_dates ');
	
	$qry_remove_left_seats = "DELETE FROM products_remaining_seats WHERE DATE_FORMAT(departure_date, '%Y-%m-%d') <= DATE_FORMAT('".$today."', '%Y-%m-%d')";
	$res_remove_left_seats = $link0->query($qry_remove_left_seats);
	$link0->query('OPTIMIZE TABLE products_remaining_seats ');
	
	$start_time1 = microtime(true);
	$echo_str.="(1) Successfully deleted products_soldout_dates ".(int)$qry_remove_sold_dates->num_rows." rows, products_remaining_seats ".(int)$res_remove_left_seats->num_rows." rows. (Used ".round(($start_time1-$start_time0),3)."s)"."\n";
	// 删除今天以前的已售完的日期 Delete old date for soldout dates }-----------------------------------------------

	// 自动写网站访问日志到数据库 Write apache logs to system_logs {------------------------------------------------
	
	$logs_date = date('Ymd', (time()-86400));
	$logs_path = '/var/log/httpd/usitrip.com-access_log/';
	if(date("H")=="08"){ //只能在8点执行这个程序
		$query = $link1->query('SELECT id FROM `apache_access_log` WHERE 1 and log_date="'.$logs_date.'" limit 1');
		if(!(int)$query->num_rows){
			
			$filename = $logs_path.'usitrip.com-access_log.'.$logs_date;
			if(file_exists($filename)){
				$filter_type = '/(\.gif|\.jpg|\.css|\.js|\.png|\.bmp|\.jpeg|\.cur|\.icon|\.ico)/i';	//排除文件.gif, .jpg, .css, .js, .png
				$handle = fopen($filename, "rb");
				while($userinfo = fscanf ($handle, "%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n") ){
					$i++;
					if(!preg_match($filter_type, $userinfo[6])){
						$date = substr($userinfo[3],1);
						list($day, $month, $year, $hours, $minutes, $seconds) = split('[/:]', $date);
						$date = $year.'-'.$month.'-'.$day.' '.$hours.':'.$minutes.':'.$seconds;
						$date = date('Y-m-d H:i:s', strtotime($date));
						$insert_into_sql = "INSERT INTO `apache_access_log` (`ip`, `date`, `page`, `log_date`) VALUES ('{$userinfo[0]}', '".$date."', '".$link1->real_escape_string($userinfo[6])."','".$logs_date."');";
						$link1->query($insert_into_sql);
						//usleep(10);
					}
				}
				fclose($handle);
				$link1->query('OPTIMIZE TABLE `apache_access_log` ');
			}else{
				$echo_str.= "The file $filename does not exist"."\n";
			}
		}
	}
	
	$start_time2 = microtime(true);
	$echo_str.= "(2) Write apache logs to system_logs, log_date ".$logs_date.". (Used ".round(($start_time2-$start_time1),3)."s)"."\n";

	// 自动写网站访问日志到数据库 Write apache logs to system_logs }------------------------------------------------
	// 自动更新出发城市对应的目的城市数据 {-------------------------------------------------------------------------
	
	function checkCityAlreadyExists($startCityID, $endCityID){	//检查记录是否存在
		global $link0;
		$checks = $link0->query('SELECT start_city_id FROM `departure_city_to_destinations_attractions` WHERE start_city_id="'.(int)$startCityID.'" and end_city_id="'.(int)$endCityID.'" ');
		if(!(int)$checks->num_rows){
			$link0->query('INSERT INTO `departure_city_to_destinations_attractions` (`start_city_id`, `end_city_id`) VALUES ("'.(int)$startCityID.'", "'.(int)$endCityID.'")  ');
		}
	}
	
	$link0->query('TRUNCATE TABLE `departure_city_to_destinations_attractions` ');	//先清空该表以便后面插入数据
	$query = $link0->query('SELECT p.departure_city_id, pd.city_id FROM `products` p, `products_destination` pd WHERE p.products_status="1" and p.products_id=pd.products_id and p.departure_city_id>=1 ');
	if((int)$query->num_rows){
		while($rows = $query->fetch_array()){
			$startCityIds = explode(',',$rows["departure_city_id"]);
			$endCityIds = explode(',',$rows["city_id"]);
			foreach((array)$startCityIds as $key => $val){
				foreach((array)$endCityIds as $endKey => $endVal){
					//写数据
					if((int)$endCityIds[$endKey]){
						checkCityAlreadyExists((int)$startCityIds[$key], (int)$endCityIds[$endKey]);
					}
				}
			}
		}
	}
	$start_time3 = microtime(true);
	$echo_str.= "(3) update departure_city_to_destinations_attractions (Used ".round(($start_time3-$start_time2),3)."s)"."\n";
	// 自动更新出发城市对应的目的城市数据 }-------------------------------------------------------------------------

	// 自动更新所有产品的出发日期用于高级搜索选项 {-------------------------------------------------------------------------
	
	if($_SERVER['SERVER_PROTOCOL']=="HTTP/1.1"){	//只在浏览器模式下运行
		require('includes/application_top.php');
		if(!is_object($currencies)){
			require_once(DIR_WS_CLASSES . 'currencies.php');
			$currencies = new currencies();
		}
		$link0->query('TRUNCATE TABLE `products_departure_dates_for_search` ');	//先清空该表以便后面插入数据
		$productsDepDate = $link0->query('SELECT products_id FROM `products` WHERE products_status="1" Order BY products_id ASC');
		require_once('includes/functions/get_avaliabledate.php');
		while($rows = $productsDepDate->fetch_array()){
			//1.输入明确的产品日期
			/*
			$a_sql = $link0->query('SELECT products_id,available_date FROM `products_available_date` WHERE products_id="'.$rows['products_id'].'" and available_date>"'.$today.'" ');
			while($a_rows = $a_sql->fetch_array()){
				$link0->query('INSERT INTO `products_departure_dates_for_search` (`products_id`, `departure_date`) VALUES ("'.$a_rows['products_id'].'", "'.$a_rows['available_date'].'")  ');
			}
			*/
			//2.输入运算后的产品日期
			
			$dateArray = get_avaliabledate($rows['products_id']);
			foreach((array)$dateArray as $key => $val){
				$date = substr($key,0,10);
				if($date>=$today){
					$check = $link0->query('SELECT products_id FROM `products_departure_dates_for_search` WHERE products_id="'.$rows['products_id'].'" AND departure_date="'.$date.'"');
					if(!(int)$check->num_rows){
						$link0->query('INSERT INTO `products_departure_dates_for_search` (`products_id`, `departure_date`) VALUES ("'.$rows['products_id'].'", "'.$date.'")  ');
					}
				}
			}
			
		}
	}
	$start_time4 = microtime(true);
	$echo_str.= "(4) Get Products Departure Date (Used ".round(($start_time4-$start_time3),3)."s)"."\n";
	
	// 自动更新所有产品的出发日期用于高级搜索选项 }-------------------------------------------------------------------------

$end_time = microtime(true);
$used_time = round(($end_time-$start_time0),3);
$echo_str.=$_SERVER['SERVER_PROTOCOL']."\n";
$echo_str.="Total time: ".$used_time."\n\n";

$log_name = basename(__FILE__,'.php').'_logs.txt';

$file = DIR_FS_CATALOG."tmp/".$log_name;
if($handle = @fopen($file, 'ab')){
	if(flock($handle, LOCK_EX)){
		fwrite($handle, $echo_str);
		flock($handle, LOCK_UN);
	}
	fclose($handle);
}

$link0->close();
echo $echo_str;

exit;
?>