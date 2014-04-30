<?php
/*
  $Id: froogle.php,v 1.00 2004/09/07

  Froogle Data Feed admin module

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
  
  Contribution created by: Chemo
*/

   //include the files
   require ('includes/application_top.php');
   require (DIR_WS_CLASSES . 'froogle.php');
	$froogle = new froogle; //new Froogle class
	$froogle->makedata(); //make the data
	$froogle->savetofile(rtrim($froogle->fields, $froogle->format['newline']), $froogle->savefilename); //save the data to file

//this section is to send the headers before output to the screen 
switch($_REQUEST['action']) {
	case upload:
	$froogle->upload(rtrim($froogle->fields, $froogle->format['newline']), $froogle->targetfilename);
	break;
	case download:
	$froogle->file_download($froogle->savefilename, $froogle->targetfilename);
	break;
	case viewtext:
	$froogle->viewfile(rtrim($froogle->fields, $froogle->format['newline']), $froogle->savefilename, 'text/plain');
	break;
	default:
	break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Froogle Feed</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body>
		<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
    	<td width="<?php echo BOX_WIDTH; ?>" valign="top">
			<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
		<!-- left_navigation //-->
		<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
		<!-- left_navigation_eof //-->
      		</table>
		</td>
	  <td>
		<p class="pageHeading">Froogle Feed</p>

<div align="center"><a href="froogle.php?action=upload">Upload</a>
				  &nbsp;|&nbsp;
				  <a href="froogle.php?action=download">Download</a>
				  &nbsp;|&nbsp;
				  <a href="froogle.php?action=viewhtml">View HTML Version</a>
				  &nbsp;|&nbsp;
				  <a href="froogle.php?action=viewtext" target="_blank">View TXT Version</a>
				  </div>
<hr>
<?php
switch($_REQUEST['action']) {
	case viewhtml:
	default:
	$froogle->viewfileHTML($froogle->links);
	break;
}
?>
</td>
	</tr></table>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>