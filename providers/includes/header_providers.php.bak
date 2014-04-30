<?php
/*
  $Id: header.php,v 1.1.1.1 2004/03/04 23:40:39 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// Howard added by 2011-07-27, We need let pa only used http://cn.tours...../providers {
if(HTTP_SERVER!=SCHINESE_HTTP_SERVER && IS_LIVE_SITES ===true){
	echo '<meta http-equiv="Refresh" content="0;URL='.SCHINESE_HTTP_SERVER.'/providers/index.php" />';
	exit;
}
//}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<?php if(strtolower(CHARSET)=='gb2312'){?>
<script type="text/javascript" src="/big5_gb-min.js"></script>
<?php }else{?>
<script type="text/javascript" src="/gb_big5-min.js"></script>
<?php }?>

<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<table border="0" width="98%" cellspacing="0" cellpadding="0" align="center">
	<tr valign="top">
		<td>
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr bgcolor="#75BAFF">
					<td>
					
					<div style="float:left"><a href="<?php echo HTTP_SERVER;?>"><?php echo tep_image(BACK_PATH.FILENAME_PROVIDERS_LOGO);?></a></div>
					<div style="float:left"><h1>供应商后台管理系统</h1></div>
					<div style="float:left" class="heard">
					<?php if(tep_not_null($_SESSION['providers_id'])){?>
					<a href="<?php echo tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ORDERS, "", "SSL");?>"><?php echo PROVIDER_MENU_ORDERS;?></a> | 
					<a href="<?php echo tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_ACCOUNT, "", "SSL");?>"><?php echo PROVIDER_MENU_ACCOUNT;?></a> | 
					<a href="<?php echo tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_USERS, "", "SSL");?>"><?php echo PROVIDER_MENU_USERS;?></a> | 
						
					<?php if(0){	//禁止供应商修改公司信息和产品信息?>
					<a href="<?php echo tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_SOLD_DATES, "", "SSL");?>"><?php echo PROVIDER_MENU_SOLDOUT_DATES;?></a> | 
					<?php 
						if($_SESSION['parent_providers_id']=="0"){?>
						<a href="<?php echo tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_AGENCY, "", "SSL");?>"><?php echo PROVIDER_MENU_AGENCY;?></a> | 
					<?php
						}
					}
					?>
						
						<a href="<?php echo tep_href_link(DIR_WS_PROVIDERS.FILENAME_PROVIDERS_LOGOFF, "", "SSL");?>"><?php echo PROVIDER_MENU_LOGOFF;?></a> &nbsp;</div>
					<?php }?>
					</td>
					
				</tr>
				<tr>
					<td>