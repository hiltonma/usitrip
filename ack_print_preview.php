<?php
  require('includes/application_top.php');
  require(DIR_FS_LANGUAGES . $language . '/ack_print_preview.php');
  define('IMAGE_BUTTON_PRINT',db_to_html('´òÓ¡'));
  if (!tep_session_is_registered('customer_id')){
    $navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }
?>
<html>
<head>
<title><?php echo NAVBAR_TITLE;?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>

<body>
<table width="60%" align="center" border="0" cellspacing="0" cellpadding="0">
 <tr>
        <td valign="top" align="left" class="main"><script language="JavaScript">
  if (window.print) {
    document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo IMAGE_BUTTON_PRINT; ?></a></center>');
  }
  else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
        </script></td>
        <td align="right" valign="bottom" class="main">
		<p align="right" class="main"><a href="javascript:window.close();"><img src='<?= DIR_WS_IMAGES;?>close_window.jpg' border=0></a></p>
		</td>
      </tr>
  <tr> 
    <td>
     		<img src="images/usi4trip_email.gif" border="0">

     </td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2" class="main" align="center"><h3><u><?php echo HEADING_TITLE;?></u></h3></td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2" class="main" ><?php echo TEXT_HEADING_I;?><u><?php echo stripslashes($HTTP_POST_VARS['fname']); ?></u><?php echo TEXT_PARA_1;?><u><?php echo $HTTP_POST_VARS['oamount']; ?></u><?php echo TEXT_PARA_1_2;?></td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp; </td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp; </td>
  </tr>
  <tr> 
    <td colspan="2" class="main" ><?php echo TEXT_CARD_NUMBER;?> <u><?php echo $HTTP_POST_VARS['ccnumber']; ?></u></td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp; </td>
  </tr>
  <tr> 
    <td colspan="2" class="main" ><?php echo TEXT_EXPIRATION_DATE;?> <u><?php echo $HTTP_POST_VARS['ccexpire1']; ?></u> / <u><?php echo $HTTP_POST_VARS['ccexpire2']; ?></u></td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp; </td>
  </tr>
  <tr style="display:none"> 
    <td colspan="2" class="main" ><?php echo TEXT_CARD_CVV;?> <u><?php echo $HTTP_POST_VARS['cccode']; ?></u></td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp; </td>
  </tr>
  <tr> 
    <td colspan="2" class="main" ><?php echo TEXT_NAME_APPEAR_ON_CARD;?> <u><?php echo stripslashes($HTTP_POST_VARS['ccname']); ?></u></td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp; </td>
  </tr>
  <tr> 
    <td colspan="2" class="main" ><?php echo TEXT_BILLING_ADDRESS;?> <u><?php echo stripslashes($HTTP_POST_VARS['ccbaddress']);?></u>
    </td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp; </td>
  </tr>
   <tr> 
    <td colspan="2">&nbsp; </td>
  </tr>
  <tr> 
    <td colspan="2"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="50%" class="main" ><?php echo TEXT_SCANNER_NOTES;?>
		  </td>
          <td><table width="100%" border="1" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="250">&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp; </td>
  </tr>
  <tr> 
    <td colspan="2">X___________________________________________________________________________ 
    </td>
  </tr>
  <tr> 
    <td width="50%" class="main" ><?php echo TEXT_CARD_HOLDERS_SIGN;?> </td>
    <td width="50%" class="main" ><?php echo TEXT_DATE;?> </td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
</table>


</body>
</html>
