<?php
/*
  $Id: invoice.php,v 1.2 2004/03/13 15:09:11 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN"><html <?php echo HTML_PARAMS; ?>>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title>Authorize.net Consolidated CRE Help Screen</title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN; ?>">
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="admin/includes/general.js"></script>
</head>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">


     <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading">Authorize.net Consolidated CRE Edition</td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
       <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
         <td class="main">

<div align="center"><h2>Configuration Help Screen</h2></div>
</td>
</tr>
<tr>
 <td align="center">
<a href=" https://freecreditcardprocessing.com/applynow/online_application.asp?code=chainreaction"> <h2>Link to Apply for Authorize.net account</h2></a><br>
(support the CRE project by using our authorize.net partner)
</td>
</tr>
<tr>
<td>
<hr align="center" size="4" width="450">
</td>
</tr>
<tr>
<td>
User your browser back button to return to the Authorize.net edit screen

<li>Credit Cart Test Info</li>
<p>This is an internal cart test number only.
 For Authorize.net testing you should use :
<li>Visa : 4007000000027</li>
<li>MasterCard : 5424000000000015</li>
 <LI>Discover :  6011000000000012</li>
 <li>American Express : 370000000000002</li>
 <br>
 Any expiration date after the current date should work.
 Any CVV code should work.
</p>
<li>Enable Authorize.net Module</li>
<p>True or False. True is on - False is off</p>
<li>Login Username</li>
<p>Your Authorize.net User ID goes here.</p>
<li>Login Transaction Key</li>
<p>Authorize.net Consolidated uses the AIM method of
connecting to Authorize.net.  You must use set the
 Password Required mode to ON on your gateway.
  Generate a Transaction Key and enter it here.
   Do not use your Authorize.net gateway
    password here. It will not work.
 </p>
<li>cURL Setup</li>
<p>Generally, enter Compiled if your Host has
cURL support compiled into the server.  Enter Not
 Compiled otherwise.  If you don't know - ask your
  Technical Support department.</p>
<li>cURL Path</li>
<p>The correct path to the cURL command on your server if it
is not compiled.  Some other server
  settings may prevent cURL from working even if it
   is compiled. In this case, you may be able to make
    this module work by setting the cURL path to various default
     locations where cURL might usually be found on a UNIX server
    (/usr/bin/curl, /usr/local/bin/curl, /bin/curl etc.</p>
<li>Transaction Mode</li>
<p>There are three alternatives.  Test, Test and Debug, and
Production. Test mode runs tests with no recording of
module function. Test and Debug mode runs tests and
prints certain variables contents in a file.  This filename is
currently hardcoded - look in catalog/temp for a file named authdebug.txt
You may have to change this filename depending on your servers security
settings.  You may also have to create a blank file with that name and chmod it to 777.
</p>
<li>Transaction Method</li>
<p>Select Credit Card.  Echeck support is not yet implemented.</p>
<li>Processing Mode</li>
<p>Authorize only approves the transaction, but does not transfer funds.  Authorize and Capture does both.</p>
<li>Sort Order of Display</li>
<p>This is the order in which the payment module is displayed on the
payment method selection screen by the checkout process module.
It should be three digits and be a higher number than that of the
paypal module if you are using this module with Paypal IPN.</p>
<li>Customer Notifications</li>
<p>True if you want authorize.net to send customer notifications, false if not.</p>
<li>Accepted Credit Cards</li>
<p>Select only  cards your merchant account allows you to accept.</p>
<li>Authorize.net Payment Zone</li>
<p>You may create a zone in which you wish to accept cards by this method. If you enter a zone here, only
those geographical areas within that zone will see this
module on checkout.</p>
<li>Authorize.net Set Order Status</li>
<p>This is the order status to which this module will set an order if it is successfully completed. Processing is suggested.</p>
<li>Enable CCV Code</li>
<p>Enable or disable CCV code.  Some merchant banks DO NOT USE CCV security checks.  If your gateway
is generating a lot of denials you should check to make sure this is set in accordance with
your merchant banks policies.</p>
<hr align="center" size="4" width="450">
  </tr>
        </td></table>
          </tr>
         </td>

   </table></td>
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
