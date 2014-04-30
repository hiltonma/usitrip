<?php
	chdir('../../../../../');
	include_once('includes/configure.php');
	include(DIR_FS_ADMIN.'includes/application_top.php'); // Needed for admin security
	
  // #We need current stylesheet to be loaded into editor for enhanced editing.
  $template_query = tep_db_query("select configuration_id, configuration_title, configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_TEMPLATE'");
  $template = tep_db_fetch_array($template_query);
  $CURR_TEMPLATE = $template['configuration_value'] . '/';
?>
<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
<script language="javascript" src="../../tiny_mce_popup.js"></script>
<title>Preview page</title>
<link href="<?php echo HTTP_SERVER.DIR_WS_TEMPLATES . $CURR_TEMPLATE;?>stylesheet.css" rel="stylesheet" type="text/css">
<script language="javascript">
    window.focus;
    window.resizeTo(600,400);
    tinyMCE.setWindowArg('mce_windowresize', false);
    var form = window.opener.document.adminForm
    var title = form.title.value;
    
    var alltext = tinyMCE.getContent();

</script>
</head>
<body>
<table align="center" width="90%" cellspacing="2" cellpadding="2" border="0">
	<tr>
		<td>
<b>Editor contents:</b> <hr />
{$content}
<br /><hr />
</td>
	</tr>
</table>
<table align="center" width="90%" cellspacing="2" cellpadding="2" border="0">
	<tr>
		<td align="right"><a href="#" onClick="window.close()"><b>Close</b></a></td>
		<td align="left"><a href="javascript:;" onClick="window.print(); return false"><b>Print</b></a></td>
	</tr>
</table>
</body>
</html>