<?php
/*
  $Id: header.php,v 1.1.1.1 2004/03/04 23:39:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/


?>

<table border="0" width="100%" height="82" cellspacing="0" cellpadding="0" background="images/logo-banner_bg.gif">
  <tr>
    <td background="images/logo-banner_bg.gif"><a href="https://www.usitrip.com">
    <img src="images/admin_logo.gif" border="0"></a>
    </td>
    <td>&nbsp;</td>
<td style="background:url(images/admin_logo_right.gif) no-repeat top right;" width="950" height="82">
<table border="0" width="100%" height="72" cellspacing="0" cellpadding="0">
  <tr>
    <td class="headerBarContent" align="center">

<!--<font COLOR="#333333" SIZE="1"><strong>If you would like professional hosting and real support<br> for this application
    please go to:<br>
    <a href="http://www.chainreactionweb.com" class="headerLink">www.chainreactionweb.com</a>
    </strong></font>-->
    </td>
    </tr>
  <tr>
    <td class="headerBarContent" align="center">&nbsp;
    <!--<a href="http://www.creloaded.com/" target="_blank" class="headerLink">
    Help Desk</a>&nbsp; |&nbsp;
    <a href="http://www.chainreactionweb.com/" class="headerLink">Chainreactionweb</a>&nbsp;
    |&nbsp; <a href="http://www.oscommerce.com" class="headerLink">osCommerce</a>&nbsp;|-->
    
	<?php  echo '<a href="' . tep_href_link('admin_account.php', '', 'NONSSL') . '" class="headerLink">';?>欢迎您，工号<?= tep_get_admin_customer_name($login_id); ?></a>&nbsp;
	|&nbsp; <?php  echo '<a href="' . tep_href_link('notebook.php', '', 'NONSSL') . '" class="headerLink">';?>留言本</a>&nbsp;
    |&nbsp; <?php  echo '<a href="' . tep_href_link('salestrack_index.php', '', 'NONSSL') . '" class="headerLink">';?>销售跟踪记录列表</a>&nbsp;
	|&nbsp; <?php  echo '<a href="' . tep_href_link('order_complaints.php', '', 'NONSSL') . '" class="headerLink">';?>投诉</a>&nbsp;
    |&nbsp; <?php  echo '<a href="' . tep_href_link('zhh_system_index.php', '', 'NONSSL') . '" class="headerLink">';?>知识库系统</a>&nbsp;
    |&nbsp; <?php  echo '<a href="' . tep_catalog_href_link() . '" class="headerLink">';?>Catalog</a>&nbsp;
    |&nbsp; <?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_ADMINISTRATION . '</a>';?>&nbsp;
    |&nbsp; <?php echo '<a href="' . tep_href_link(FILENAME_LOGOFF, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_LOGOFF . '</a>';?>&nbsp;
    </td>
  </tr>
  <tr>
  <td class="timeBox">
  <style>
  .timeBox { font-size:12px}
  .timeBox b{ font-size:12px}
  </style>
  <?php //显示当前时间 start {
	include_once(DIR_WS_FUNCTIONS.'/timezone.php');
	$datetime = array();
	$datetime['BeijingTime'] = array('name'=>'北京','time'=>get_zone_time('Asia/Shanghai'));
	$datetime['LosAngelesTime'] = array('name'=>'洛杉矶', 'time'=>get_zone_time('America/Los_Angeles'));
	$datetime['NewYorkTime'] = array('name'=>'纽约', 'time'=>get_zone_time('America/New_York'));
	$datetime['HonoluluTime'] = array('name'=>'夏威夷', 'time'=>get_zone_time('Pacific/Honolulu'));
	$_systime = date('Y-m-d H:i:s');
	?>
	<script type="text/javascript">
	function _get_time(id, timestr){
		var str = Date.parse(timestr); /* m/d/Y H:i:s */
		var space = 1000;
		setInterval(function(){
			str += space;
			var date = new Date(str);
			var year = date.getFullYear();
			var month = (date.getMonth()+1);
			if(month < 10){ month = '0' + month.toString();}
			var day = date.getDate();
			if(day < 10){ day = '0' + day.toString();}
			var hours = date.getHours();
			if(hours < 10){ hours = '0' + hours.toString();}
			var minutes = date.getMinutes();
			if(minutes < 10){ minutes = '0' + minutes.toString();}
			var seconds = date.getSeconds();
			if(seconds < 10){ seconds = '0' + seconds.toString();}
			var milliseconds = date.getMilliseconds();

			document.getElementById(id).innerHTML = '<b>'+year + '-' + month + '-' + day + '</b> '+ hours +':'+ minutes +':' + seconds;
		},space);
	}
	</script>
	
	当前时间	
	<?php foreach($datetime as $key => $date){
		if($date['time']==$_systime){ $_systime = '采用<b>'.$date['name'].'</b>时间'; }
	?>
	[<?= $date['name']?>]<span id="<?= $key;?>"><?= $date['time']?></span>
	<script type="text/javascript">
	_get_time("<?= $key?>","<?= date("m/d/Y H:i:s", (strtotime($date['time'])+1))?>");
	</script>
	<?php }?>
	
	[服务器<?=$_systime;?>]
	<?php //显示当前时间 end }?>
  </td>
  </tr>
</table>
</td>
    </tr>
</table>
<script type="text/javascript" src="includes/menu.js"></script>
<script type="text/javascript" src="includes/big5_gb-min.js"></script>
<?php if (MENU_DHTML == 'True') require(DIR_WS_INCLUDES . 'header_navigation.php'); 

  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
?>
