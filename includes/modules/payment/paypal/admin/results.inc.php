<?php
/*
  $Id: results.inc.php,v 2.6a 2004/07/14 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<meta name="copyright" content="2004">
<meta name="author" content="developer&#064;devosc.com">
<style type='text/css'>
body {background-color: #FFFFFF;}
body, td, th {font-family: sans-serif; font-size:12px;}
.p {margin-top:0px;padding-top:0px;}
.box, .boxEmail {border:1px solid black; width:100%;}
table.box td {color: #003366; }
input { border:1px solid #003366;}
</style>
</head>
<body>
<form name="ipn" method="GET" action="<?echo $_SERVER['HTTP_REFERER']?>">
<input type="hidden" name="action" value="test"/>
<table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2">
      <tr valign="middle">
        <td align="center">
<table border="0" width="780" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="0" class="box">
          <tr>
            <td align="center"><a href="http://www.oscommerce.com"><img border="0" src="<?php echo DIR_WS_MODULES . 'payment/paypal/images/oscommerce.gif'; ?>" alt=" osCommerce " title=" osCommerce " /></a><h1 class="p"> <?php echo PROJECT_VERSION; ?></h1></td>
          </tr>
          <tr>
            <td class="pageHeading" style="color:green" align="center">PayPal_Shopping_Cart_IPN</td>
          </tr>
          <tr>
            <td class="pageHeading" style="color:blue; text-align:center; padding-top:5px;">IPN Test Results</td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
          </tr>
        </table></td>
      </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '25'); ?></td>
          </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="0" class="box">
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
          </tr>
<?php if(!$debug->error) { ?>
          <tr>
            <td class="pageHeading" style="color:red" align="center">Test Complete!</td>
          </tr>
<?php } else { ?>
          <tr>
            <td class="pageHeading" style="color:red" align="center">Test Not Valid!</td>
          </tr>
<?php } ?>
<?php if($debug->enabled) { ?>
          <tr>
            <td style="padding:5px;" align="left"><?php echo $debug->info(true); ?></td>
          </tr>
<?php } ?>
          <tr>
            <td style="color:blue; text-align:center; padding-top:5px;"><input type="submit" value="Continue"></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '15'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td align="center" class="smallText">
<?php
/*
  The following copyright announcement is in compliance
  to section 2c of the GNU General Public License, and
  thus can not be removed, or can only be modified
  appropriately.

  For more information please read the following
  Frequently Asked Questions entry on the osCommerce
  support site:

  http://www.oscommerce.com/community.php/faq,26/q,50

  Please leave this comment intact together with the
  following copyright announcement.
*/
?>
E-Commerce Engine Copyright &copy; 2003 <a href="http://www.oscommerce.com" target="_blank">osCommerce</a><br>
osCommerce provides no warranty and is redistributable under the <a href="http://www.fsf.org/licenses/gpl.txt" target="_blank">GNU General Public License</a>
    </td>
  </tr>
  <tr>
    <td><?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5'); ?></td>
  </tr>
  <tr>
    <td align="center" class="smallText">Powered by <a href="http://www.oscommerce.com" target="_blank">osCommerce</a></td>
  </tr>
</table>
</td>
      </tr>
    </table>
</td>
</tr>
</table>
</form>
</body>
</html>
