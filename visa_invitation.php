<?php
require('includes/application_top.php');
if (!tep_session_is_registered('customer_id')) {
	$navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	exit;
}
$opid = (int)$_GET['opid'];
require(DIR_FS_CLASSES . 'visa_invitation.php');

$vi = new visa_invitation($opid, $_SESSION['customer_id']);
$check = $vi->check();
if ($check == false) {
	header("Content-type: text/html; charset=gb2312");
	echo('对不起！您无权查看该邀请函！请使用您收到邀请函的邮箱来登录查看。如果未注册，请注册！谢谢！<a href="' . tep_href_link('account.php','','SSL') . '">返回用户中心</a>');
	exit;
}

require(DIR_FS_CLASSES . 'visa_invitation_print.php');

try {
	$invitation = new visa_invitation_print($opid);
	$invitation_html = $invitation->doit(tep_get_customers_email($_SESSION['customer_id']));
} catch (Exception $e) {
	echo $e->getMessage();
	exit;
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo 'usitrip - Invitation - ' . $opid; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="print.css">
<script type="text/javascript">
function printPage(obj) {
	if (obj == 'true') {
		var body = window.document.body.innerHTML;
		var printArea = window.document.getElementById("printPage").innerHTML;
		window.document.body.innerHTML = printArea;
		window.print("", 5000);
		window.document.body.innerHTML = body;
	}
}
   
function doPrint(){
	var body = window.document.body.innerHTML;
	bdhtml=body;
	sprnstr="<!--startprint-->";
	eprnstr="<!--endprint-->";
	prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17);
	prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));
	window.document.body.innerHTML=prnhtml;
	window.print("", 5000);
	window.close();
}
</script>

</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<div id="menmtop">
<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
      <td width="100" align="left" valign="bottom" class="main"><a href="<?php echo tep_href_link(FILENAME_ACCOUNT,'','SSL')?>"><?php echo db_to_html('返回用户中心')?></a></td>
		<td valign="top" align="left" class="main"><script language="JavaScript">
  if (window.print) {
    document.write('<a href="javascript:void(0);" onClick="javascript:doPrint();" onMouseOut=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo db_to_html('打印') ?></a></center>');
  }
  else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
        </script></td>
        <td align="right" valign="bottom" class="main"><p align="right" class="main"><a href="javascript:window.close();"><img src='<?= DIR_WS_IMAGES;?>close_window.jpg' border=0></a></p></td>
      </tr>
    </table>
</div>

<?php
echo '<div id="printPage">';
echo '<!--startprint-->';
echo $invitation_html;
echo '<!--endprint-->';
echo '</div>';
require(DIR_FS_INCLUDES . 'application_bottom.php');
?>