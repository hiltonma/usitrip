<?php
/*

  Shipping Estimator Popup

  Shoppe Enhancement Controller - Copyright (c) 2003 WebMakers.com
  Linda McGrath - osCommerce@WebMakers.com

*/

  require("includes/application_top.php");

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo SHIPPING_ESTIMATOR_TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php  include(DIR_FS_JAVASCRIPT . 'popup_window_shipping.js'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

     <table bgcolor="#FFFFFF" border="0" width="100%" height="100%" cellspacing="3" cellpadding="3" align="center" valign="middle">
      <tr>
        <td><?php require(DIR_FS_MODULES . 'shipping_estimator.php'); ?></td>
      </tr>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>

      <tr>
        <td align="center">
          <table align="right" border="1" cellpadding="6" cellspacing="3">
            <tr>
              <td class="ShoppingCartShipping_main"><a href="javascript:window.close()"> Close Window </a></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

</body>
</html>
