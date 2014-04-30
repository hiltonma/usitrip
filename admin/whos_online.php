<?php
/*
 * $Id: whos_online.php,v 1.1.1.1 2004/03/04 23:39:02 ccwjr Exp $ osCommerce,
 * Open Source E-Commerce Solutions http://www.oscommerce.com Copyright (c) 2003
 * osCommerce Released under the GNU General Public License
 */

$xx_mins_ago = (time() - 900);

require ('includes/application_top.php');
$time0 = microtime(true);
$handle = false;
if(STORE_SESSIONS!='mysql'){
	$sdir = SESSION_WRITE_DIRECTORY;
	if(stripos($sdir, 'tmp') === false){
		die('session文件夹必须放到tmp之下');
	}
	$handle = opendir($sdir);
}

$data = array();
$n = 0;
while (false !== $file_name =(readdir($handle))) {
	if($file_name=='.' || $file_name=='..'){ continue; }
	$flie = $sdir.'/'.$file_name;
	$filemtime = filemtime($flie);
	//删除过期1440秒的文件或空文件
	if($filemtime < (time()-1440) || filesize($flie) < 1){
		unlink($flie);
		continue;
	}
	$sess = file_get_contents($flie);
	$data[$n]['session_id'] = str_replace('sess_','',$file_name);
	if($data[$n]['session_id'] == 'c9t5rk7i556f31em999jsa4hn4'){
		//echo $sess;exit;
	}
	$data[$n]['time_last_click'] = $filemtime;
	$matches = array();
	preg_match('/online_ip_address\|s:[0-9]{1,2}:"([0-9_\.]+)";/', $sess, $matches);
	$data[$n]['ip_address'] = $matches[1];
	//跳过找不到ip的数据
	if(!$data[$n]['ip_address']){ continue; }
	$matches = array();
	preg_match('/online_last_page_url\|s:[0-9]{1,3}:"([^"]+)";/', $sess, $matches);
	$data[$n]['last_page_url'] = tep_db_output($matches[1]);
	$matches = array();
	preg_match('/online_http_referer\|s:[0-9]{1,3}:"([^"]+)";/', $sess, $matches);
	$data[$n]['http_referer'] = tep_db_output($matches[1]);
	$matches = array();
	preg_match('/online_time_entry\|i:([0-9]+)/', $sess, $matches);
	$data[$n]['time_entry'] = $matches[1];
	$matches = array();
	preg_match('/customer_id\|s:[0-9]+:"([0-9]+)"/', $sess, $matches);
	$data[$n]['customer_id'] = $matches[1];
	$matches = array();
	preg_match('/customer_first_name\|s:[0-9]+:"([^"]+)"/', $sess, $matches);
	$data[$n]['full_name'] = tep_db_output($matches[1]);
	$n++;
}
//对data按指定的方法排序(最后访问时间降序，IP升序)
$ipCount = array();
foreach ($data as $key => $row) {
	$ltime[$key] = $row['time_last_click'];
	$ip[$key] = $row['ip_address'];
	$cId[$key] = $row['customer_id'];
	$ipCount[$row['ip_address']]+= 1;
}
//array_multisort($ltime, SORT_DESC, $ip, SORT_ASC, $data);
array_multisort($cId, SORT_DESC, $ip, SORT_ASC, $ltime, SORT_DESC, $data);
//ip访问次数排名
foreach ($ipCount as $_ip => $_n){
	$srot[$_ip] = $_n;
}
array_multisort($srot, SORT_DESC, $ipCount);
//访客总数
$total_cust = sizeof($data);
$time1 = microtime(true);
$getTime = $time1 - $time0;

// 备注添加删除
if ($_GET['ajax'] == "true") {
	include DIR_FS_CLASSES . 'Remark.class.php';
	$remark = new Remark('whos_online');
	$remark->checkAction($_GET['action'], $login_id); //添加删除动作，统一在方法里面处理了
}

// remove entries that have expired
//tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo CHARSET; ?>" />
<?php if($_GET["AT"]){  ?>
<meta http-equiv="refresh" content="<?= $_GET["AT"]?>;URL=whos_online.php?AT=<?= $_GET["AT"]?>&next_time=<?= time();?>#StatisticalResults" />
<?php }?>

<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/big5_gb-min.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript"
	src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
function add_lock_ip(ip){
	if(!confirm('您真要封锁'+ ip +'？')){ return false;}
	var url = "ip_lock.php";
	$.get(url,{"action":'addLock', "ip":ip}, function(text){
		if(text=='OK'){
			alert('封锁成功！1分钟后生效。');
			//window.location.reload();
		}else{
			alert(text);
		}
	}, 'text');
}
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0"
	leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
	<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

	<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('whos_online');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
		<tr>
			<td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0"
					width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1"
					class="columnLeft">
					<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
				</table></td>
			<!-- body_text //-->
			<td width="100%" valign="top">
			<fieldset>
			<legend>概述</legend>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td class="pageHeading">
过去24分钟内在线用户列表
<span class="dataTableContent"
						style="font-size: 10px; color: #909090">自动刷新：</span>


						<input type='button' value='不刷新'
						onClick="location.href='whos_online.php'"> <input
						type='button' value='10分钟'
						onClick="location.href='whos_online.php?AT=600'"> <input
						type='button' value='5分钟'
						onClick="location.href='whos_online.php?AT=300'"> <input
						type='button' value='60秒'
						onClick="location.href='whos_online.php?AT=60'"> <input
						type='button' value='30秒'
						onClick="location.href='whos_online.php?AT=30'"> <a
						target="_blank" href="<?= tep_href_link('ip_lock.php');?>">查看IP黑名单列表</a>

					</td>
				</tr>
				<tr>
				<td id="StatisticalResults" class="smallText">
					<?php echo '访客总数：'.$total_cust.'<br>ｉｐ总数：'.sizeof($ipCount).'<br>您的ｉｐ：'.tep_get_ip_address().'<br>数据用时：'.round($getTime, 3).'秒';?>
					<br />
					<?php
					echo '访问最多的前10位ｉｐ：';
					$n = 0;
					foreach ($ipCount as $_ip1 => $_num){
						$n++;
						if($n>10){ break; }
						echo '<a href="http://wq.apnic.net/apnic-bin/whois.pl?searchtext='.$_ip1.'" target="_blank">'.$_ip1.'</a><b style="'.($_num>=20 ? 'color:red' : '').'">('.$_num.'次<button type="button" onclick="add_lock_ip(\''.$_ip1.'\')">封</button>)</b> ';
					}
					?>
					</td>
				</tr>
			</table>
			</fieldset>
			<fieldset>
			<legend><a href="javascript:void(0)" onclick="jQuery('#whosList').toggle();">查看详细列表</a></legend>
			<table style="display:none;" id="whosList" border="0" width="100%" cellspacing="0"
											cellpadding="2">
											<tr class="dataTableHeadingRow">
												<td width=15 class="dataTableHeadingContent" align="left">会话ID</td>
												<td width=11 class="dataTableHeadingContent" align="left">客户ID</td>
												<td width=60 class="dataTableHeadingContent" align="left">客户名称</td>
												<td class="dataTableHeadingContent" align="left">IP地址</td>
												<td class="dataTableHeadingContent" align="left">查IP</td>
												<td class="dataTableHeadingContent" align="left">访问时间</td>
												<td class="dataTableHeadingContent" align="left">最近访问</td>
												<td class="dataTableHeadingContent" align="left">最近访问的页面</td>
												<td class="dataTableHeadingContent" align="left">来源页</td>
											</tr>
<?php
foreach ($data as $i => $v) {
	$whos_online = $data[$i];
	
	
	?>
				<tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" >
                <td width=10 class="dataTableContent" align="left"><?php echo $whos_online['session_id']; ?></td>
											<td width=11 class="dataTableContent" align="left"><a target="_blank"
												HREF="customers.php?selected_box=customers&cID=<?php echo $whos_online['customer_id']; ?>&action=edit"><?php echo $whos_online['customer_id']; ?></a></td>
											<td width=60 class="dataTableContent" align="left"><?php echo $whos_online['full_name']; ?></td>
											<td class="dataTableContent" align="left"><?php echo $whos_online['ip_address']; ?></td>
											<td class="dataTableContent" align="left" nowrap>
												<!--<a HREF="http://www.dnsstuff.com/tools/whois.ch/#ipInformation|type=ipv4&&value=<?php echo $whos_online['ip_address']; ?>" target="_blank">1</A>-->
												<a
												HREF="http://wq.apnic.net/apnic-bin/whois.pl?searchtext=<?php echo $whos_online['ip_address']; ?>"
												target="_blank">看IP1</A> <a
												href="http://www.ip138.com/ips.asp?ip=<?php echo $whos_online['ip_address']; ?>&action=2"
												target="_blank">看IP2</a>
												<button type="button"
													onClick="add_lock_ip('<?= $whos_online['ip_address']?>')">封IP</button>
											</td>
											<td class="dataTableContent" align="left"><?php echo date('H:i:s', $whos_online['time_entry']); ?></td>
											<td class="dataTableContent" align="left"><?php echo date('H:i:s', $whos_online['time_last_click']); ?></td>
											<td class="dataTableContent" align="left"><a
												HREF="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . $whos_online['last_page_url']; ?>"
												target="_blank"><?php if (eregi('^(.*)' . tep_session_name() . '=[a-f,0-9]+[&]*(.*)', $whos_online['last_page_url'], $array)) { echo $array[1] . $array[2]; } else { echo $whos_online['last_page_url']; } ?></a>&nbsp;</td>
<?php
	if ($whos_online['http_referer'] == "") {
		echo '<td class="dataTableContent" align="left">' . TEXT_HTTP_REFERER_NOT_FOUND . '</td>';
	} else {
		echo '<td class="dataTableContent" align="left"><a target="_blank" href="' . $whos_online['http_referer'] . '">来源页</a></td>';
	}
	?>
              </tr>
<?php
}
?>
  </table>
  			</fieldset>
	</td>
	<!-- body_text_eof //-->
	</tr>
	</table>
	<!-- body_eof //-->

	<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
	<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>