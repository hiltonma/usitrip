<?php
require('includes/application_top.php');
//mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
$db_system_logs = 'Slogs';
tep_db_connect('localhost', 'root', 'GoodLuck2008', 'system_logs', $db_system_logs) or die('Unable to connect to database server!');

//mysql_select_db('test');

ini_set('display_errors', '1'); 
error_reporting(E_ALL & ~E_NOTICE);
ini_set('auto_detect_line_endings', 'On'); 
ini_set('memory_limit','256M');
$logs_path = '/var/log/httpd/usitrip.com-access_log/';

$i = 0;
$start=microtime(true);
if($_GET['start_date']!=""){
	$sql_date = date('Y-m-d H:i:s', strtotime($_GET['start_date']));
	$sql_date_end = date('Y-m-d 23:59:59', strtotime($_GET['start_date']));
	$logs_date = date('Ymd', strtotime($_GET['start_date']));
	//如果查无当天的表数据则要先写入数据库start(只能是昨天或昨天之前的)
	if(date('YmdH',(time()-86400)) >= $logs_date.'08'){
		$sql = tep_db_query('SELECT id, date FROM `apache_access_log` WHERE 1 and log_date="'.$logs_date.'" order by date DESC limit 1', $db_system_logs);
		$row = tep_db_fetch_array($sql);
		if(!(int)$row['id']){
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
						$insert_into_sql = "INSERT INTO `apache_access_log` (`ip`, `date`, `page`, `log_date`) VALUES ('{$userinfo[0]}', '".$date."', '".mysql_real_escape_string($userinfo[6])."','".$logs_date."');";
						tep_db_query($insert_into_sql, $db_system_logs);
						usleep(10);
						//print_r($userinfo);
					}
				}
				fclose($handle);
				tep_db_query('OPTIMIZE TABLE `apache_access_log` ', $db_system_logs);
			}else{
				$msn = "The file $filename does not exist";
			}
		}
	}

	
	//当天访问量
	$sql = tep_db_query('SELECT count(*) as ClickNum, count(DISTINCT ip) as ClickIp FROM `apache_access_log` WHERE `date`>="'.$sql_date.'" AND `date`<="'.$sql_date_end.'" ', $db_system_logs);
	$row = tep_db_fetch_array($sql);
	$ClickNum = $row['ClickNum'];
	$ClickIp = $row['ClickIp'];
	
	//访问最多的10个IP
	$sql = tep_db_query('SELECT count(ip) as total, ip FROM `apache_access_log` WHERE `date`>="'.$sql_date.'" AND `date`<="'.$sql_date_end.'" Group By ip Order By total DESC Limit 10', $db_system_logs);
	$MaxClickIp = "";
	while($rows = tep_db_fetch_array($sql)){
		$MaxClickIp .= '<div><span style="width:200px">'.$rows['ip'].'</span> <span>('.$rows['total'].'次)</span></div>';
	}
	
	//瞬间访问最多的IP
	$sql = tep_db_query('SELECT `date`, ip, count(ip) AS IPNum FROM `apache_access_log` WHERE `date`>="'.$sql_date.'" AND `date`<="'.$sql_date_end.'" GROUP BY  `date`,`ip` HAVING IPNum > 5 order by IPNum DESC', $db_system_logs);
	$Concurrent = "";
	while($rows = tep_db_fetch_array($sql)){
		$Concurrent .= '<div><span style="width:200px">'.$rows['ip'].'</span> <span>('.$rows['IPNum'].'次)</span> <span>'.$rows['date'].'</span></div>';
	}

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>查看系统日志</title>
<link rel="stylesheet" type="text/css" href="includes/jquery-1.3.2/nyroModal.css">
<link rel="stylesheet" type="text/css" href="css/new_sys_indexDdan.css">
<link rel="stylesheet" type="text/css" href="css/new_sys_index.css">

<script type="text/javascript" src="includes/javascript/calendar.js"></script>
<style type="text/css">
<!--
td,ul { margin:5px; padding:5px;}
tr {border:#033 solid}
table {margin:20px; display:block; clear:both;}
-->
</style>
</head>

<body>
<div id="Msn"><?= $msn;?></div>
<div id="Search">

<form method="get" enctype="application/x-www-form-urlencoded" target="_self" id="SearchForm">
	<ul>
	日期：<input type="text" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" value="<?= $_GET['start_date']?>" name="start_date" class="textTime"><input type="text" class="textAll" name="start_H" value="<?= $_GET['start_H']?>"style="width:30px;" >：<input type="text" class="textAll" name="start_I" value="<?= $_GET['start_I']?>" style="width:30px;" >：<input type="text" class="textAll" name="start_S" value="<?= $_GET['start_S']?>" style="width:30px;" >
	至
	<input type="text" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" value="<?= $_GET['end_date']?>"  name="end_date" class="textTime"><input type="text" class="textAll" name="end_H" value="<?= $_GET['end_H']?>" style="width:30px;" >：<input type="text" class="textAll" name="end_I" value="<?= $_GET['end_I']?>" style="width:30px;" >：<input type="text" class="textAll" name="end_S" value="<?= $_GET['end_S']?>" style="width:30px;" > 日志文件地址：<input type="text" class="textAll" name="logs_path" value="<?= $logs_path?>" style="width:300px;" readonly="readonly" >
</ul>
<ul>IP：<input type="text" class="textAll" name="IP" value="<?= $_GET['IP']?>" style="width:300px;" ></ul>
<ul>	
	<button type="submit" class="Allbutton">确定</button>
</ul>
<ul>tail -f /var/log/httpd/usitrip.com-access_log/usitrip.com-access_log.<?= date('Ymd')?> 动态看最后几行的日志</ul>
</form></div>
<div style="margin-top:10px;">
  	<?php
	if(tep_not_null($sql_date)){
	?>
	<table border="0" cellspacing="0" cellpadding="0">
  		
		<tr>
  			<td align="right" valign="top">当天访问量&nbsp;
  			</td>
  			<td align="left" valign="top">
			<?= $ClickNum;?>
			</td>
  		</tr>
		<tr>
  			<td align="right" valign="top">当天访问IP&nbsp;
  			</td>
  			<td align="left" valign="top">
			<?= $ClickIp;?>
			</td>
  		</tr>
		<tr>
  			<td align="right" valign="top">当天访问次数最多的10个IP&nbsp;
  			</td>
  			<td align="left" valign="top">
			<?= $MaxClickIp;?>
			</td>
  		</tr>
		<tr>
  			<td align="right" valign="top">当天瞬间访问最多的IP&nbsp;
  			</td>
  			<td align="left" valign="top">
			<?= $Concurrent;?>
			</td>
  		</tr>
  		
		</table>
  	<?php
	}
	?>
  	</div>

<div>
<?php
$end=microtime(true);
$usedTime = "Time:".number_format($end-$start,2,'.','')." seconds<br>";
echo $usedTime;
?>
</div>
</body>
</html>